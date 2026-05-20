<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payment;
use Illuminate\Support\Facades\Http;
use App\Models\ApplicationForm;

class PaymentController extends Controller
{


    // =======================
    // Shared amount calculator
    // =======================

    /**
     * Calculate the correct payable amount for an application.
     * In double dastur period: sums each selected category's double_dastur_fee
     * across all sibling jobs (mirrors JS updateTotalFee logic).
     * In normal period: uses stored total_fee, falls back to application_fee.
     */
    private function calculateAmount(ApplicationForm $application): float
    {
        $job = $application->jobPosting;

        $inDoubleDastur = $job
            && $job->deadline && now()->gt($job->deadline)
            && $job->double_dastur_fee && $job->double_dastur_date
            && now()->lte($job->double_dastur_date);

        if ($inDoubleDastur) {
            $selectedCategories = $application->applied_category ?? [];
            if (!is_array($selectedCategories)) {
                $selectedCategories = json_decode($selectedCategories, true) ?? [];
            }

            $siblingJobs = \App\Models\JobPosting::where('status', 'active')
                ->where('position', $job->position)
                ->where('level', $job->level)
                ->where('service_group', $job->service_group)
                ->where('double_dastur_date', '>=', now())
                ->get();

            $amount = 0;
            foreach ($selectedCategories as $cat) {
                $match = null;
                foreach ($siblingJobs as $sj) {
                    if ($cat === 'open'              && ($sj->has_open || $sj->category === 'open'))           { $match = $sj; break; }
                    if ($cat === 'inclusive'          && ($sj->has_inclusive || $sj->category === 'inclusive')) { $match = $sj; break; }
                    if ($cat === 'internal_open'      && $sj->has_internal_open)                               { $match = $sj; break; }
                    if ($cat === 'internal_inclusive' && $sj->has_internal_inclusive)                          { $match = $sj; break; }
                    if ($cat === 'internal_appraisal' && $sj->category === 'internal_appraisal')               { $match = $sj; break; }
                }
                if ($match) {
                    $amount += (float) ($match->double_dastur_fee ?: $match->application_fee);
                }
            }

            return $amount > 0 ? $amount : (float) $job->double_dastur_fee;
        }

        // Normal period
        return ((float) $application->total_fee > 0)
            ? (float) $application->total_fee
            : (float) (optional($job)->application_fee ?? 0);
    }

    // =======================
    // Esewa Payment
    // =======================
    // ESEWA START
        public function startEsewa($draftId)
        {
            $application = ApplicationForm::findOrFail($draftId);
            $amount = $this->calculateAmount($application);

            if ($amount <= 0) {
                return redirect()->back()->with('error', 'Invalid payment amount. Please contact support.');
            }

            $tax_amount = 0;
            // Send as integer string — eSewa displays exactly what we POST, no decimals wanted
            $amount       = (string) (int) round($amount);
            $total_amount = (string) ((int) $amount + $tax_amount);
            $transaction_uuid = uniqid('txn_');
            $product_code = 'EPAYTEST'; // sandbox
            $successUrl = route('candidate.payment.esewa.success');
            $failureUrl = route('candidate.payment.esewa.failure');

            // Create pending payment
            $payment = Payment::create([
                'draft_id' => $draftId,
                'gateway' => 'esewa',
                'amount' => $amount,
                'status' => 'pending',
                'txRef' => $transaction_uuid
            ]);

            // Generate signature according to eSewa doc (HMAC SHA256)
            $signed_field_names = 'total_amount,transaction_uuid,product_code';
            $string_to_sign = "total_amount=$total_amount,transaction_uuid=$transaction_uuid,product_code={$product_code}";
            $secret = env('ESEWA_SECRET_KEY'); // add your secret key to .env
            $signature = base64_encode(hash_hmac('sha256', $string_to_sign, $secret, true));

            return view('payment.esewa', compact(
                'payment', 'amount', 'tax_amount', 'total_amount', 'transaction_uuid',
                'product_code', 'successUrl', 'failureUrl', 'signed_field_names', 'signature'
            ));
        }

    // ESEWA SUCCESS
       public function esewaSuccess(Request $request)
            {
                if (!$request->has('data')) {
                    return view('candidate.payment.failure');
                }

                $decoded = json_decode(base64_decode($request->data), true);

                if (!$decoded) {
                    return view('candidate.payment.failure');
                }

                $transaction_uuid = $decoded['transaction_uuid'];
                $signature = $decoded['signature'];
                $signed_field_names = $decoded['signed_field_names'];

                $fields = explode(',', $signed_field_names);

                $string_to_sign = "";

                foreach ($fields as $field) {
                    $string_to_sign .= $field . "=" . $decoded[$field] . ",";
                }

                $string_to_sign = rtrim($string_to_sign, ",");

                $secret = env('ESEWA_SECRET_KEY');

                $generated_signature = base64_encode(hash_hmac('sha256', $string_to_sign, $secret, true));

                if ($generated_signature !== $signature) {
                    return view('candidate.payment.failure');
                }

                $payment = Payment::where('txRef', $transaction_uuid)->first();

                if (!$payment) {
                    return view('candidate.payment.failure');
                }

            if ($decoded['status'] === "COMPLETE") {
            $payment->status = 'paid';
            $payment->transaction_id = $decoded['transaction_code'] ?? null;
            $payment->save();

            session(['draft_id' => $payment->draft_id]);

            return $this->paymentSuccess($payment, $decoded);
            }

            return view('candidate.payment.failure'); // outside the if block

         }

// ESEWA FAILURE
            public function esewaFailure()
            {
                return view('candidate.payment.failure');
            }




        // =======================
        // Khalti Payment
        // =======================

        public function startKhalti($draftId)
        {
            $application = ApplicationForm::findOrFail($draftId);
            $amount = $this->calculateAmount($application);

            if ($amount <= 0) {
                return redirect()->back()->with('error', 'Invalid payment amount. Please contact support.');
            }

            // Khalti requires amount in paisa as a strict integer
            $amount_in_paisa = (int) round($amount * 100);
            $txRef = uniqid('khalti_');

            // Create pending payment
            $payment = Payment::create([
                'draft_id' => $draftId,
                'gateway' => 'khalti',
                'amount' => $amount,
                'status' => 'pending',
                'txRef' => $txRef
            ]);

            $candidateName  = $application->name_english ?? 'Candidate';
            $candidateEmail = $application->email ?? 'candidate@example.com';

            $response = Http::withHeaders([
                'Authorization' => 'Key ' . env('KHALTI_SECRET_KEY'),
                'Content-Type' => 'application/json',
            ])->post('https://dev.khalti.com/api/v2/epayment/initiate/', [
                "return_url" => route('candidate.payment.khalti.success'),
                "website_url" => url('/'),
                "amount" => $amount_in_paisa,
                "purchase_order_id" => $txRef,
                "purchase_order_name" => "Application Fee",
                "customer_info" => [
                    "name" => $candidateName,
                    "email" => $candidateEmail,
                    "phone" => "9800000000"
                ]
            ]);

            if ($response->successful() && isset($response['payment_url'])) {
                return redirect($response['payment_url']);
            }

            $responseJson = $response->json() ?? [];
            $khaltiError  = $responseJson['detail']
                ?? $responseJson['error_key']
                ?? ($response->body() ?: ('HTTP ' . $response->status()));

            return back()->with('error', 'Khalti initiation failed: ' . $khaltiError);
        }

        // Khalti success
        public function khaltiSuccess(Request $request)
        {
            $pidx = $request->pidx;

            if (!$pidx) {
                return view('candidate.payment.failure');
            }

            // Lookup Khalti payment
            $response = Http::withHeaders([
                'Authorization' => 'Key ' . env('KHALTI_SECRET_KEY'),
                'Content-Type' => 'application/json',
            ])->post('https://dev.khalti.com/api/v2/epayment/lookup/', [
                "pidx" => $pidx
            ]);

            $data = $response->json();

            // dd($data);

            $purchaseOrderId = $data['purchase_order_id'] ?? $request->purchase_order_id ?? null;
            $status = strtolower($data['status'] ?? $request->status ?? '');

            if (!$purchaseOrderId || $status !== 'completed') {
                return view('candidate.payment.failure');
            }

            // Find payment
            $payment = Payment::where('txRef', $purchaseOrderId)->first();

            if (!$payment) {
                return view('candidate.payment.failure');
            }

            // Update payment
            $payment->status = 'paid';
            $payment->transaction_id = $data['transaction_id'] ?? $request->transaction_id ?? null;
            $payment->save();

            session(['draft_id' => $payment->draft_id]);
            
            return $this->paymentSuccess($payment, $data);
            }
            public function khaltiFailure()
            {
                return view('candidate.payment.failure');
            }


        // =======================
        // ConnectIps Payment
        // =======================

        public function startConnectIps($draftId)
        {
            $application = ApplicationForm::findOrFail($draftId);
            $amount = $this->calculateAmount($application);
            $amountInPaisa = $amount * 100;; // Change if needed

            $txnId = uniqid('cips_');
            $txnDate = now()->format('d-m-Y H:i:s');
            $successUrl = route('candidate.payment.connectips.success');
            $failureUrl = route('candidate.payment.connectips.failure');

            Payment::create([
                'draft_id' => $draftId,
                'gateway' => 'connectips',
                'amount' => $amount,
                'status' => 'pending',
                'txRef' => $txnId
            ]);

            $merchantId = config('services.connectips.merchant_id');
            $appId = config('services.connectips.app_id');
            $appName = config('services.connectips.app_name');

            $referenceId = $txnId;
            $remarks = trim("Application Fee");
            $particulars = trim("Application Payment");

            $tokenString = "MERCHANTID={$merchantId},APPID={$appId},APPNAME={$appName},TXNID={$txnId},TXNDATE={$txnDate},TXNCRNCY=NPR,TXNAMT={$amountInPaisa},REFERENCEID={$referenceId},REMARKS={$remarks},PARTICULARS={$particulars},TOKEN=TOKEN";

            $token = $this->generateConnectIpsToken($tokenString);

            return view('payment.connectips', compact(
                'merchantId',
                'appId',
                'appName',
                'txnId',
                'txnDate',
                'amountInPaisa',
                'referenceId',
                'remarks',
                'particulars',
                'successUrl',
                'failureUrl',
                'token'
            ));
        }

        private function generateConnectIpsToken($data)
{
    $privateKeyPath = storage_path('app/connectips/private.key');

    if (!file_exists($privateKeyPath)) {
        throw new \Exception("Private key file not found.");
    }

    $privateKey = file_get_contents($privateKeyPath);

    $keyResource = openssl_pkey_get_private($privateKey);

    if (!$keyResource) {
        throw new \Exception("Invalid private key.");
    }

    openssl_sign($data, $signature, $keyResource, OPENSSL_ALGO_SHA256);

    return base64_encode($signature);
}

        private function validateConnectIps($txnId, $amount)
        {
            $merchantId = config('services.connectips.merchant_id');
            $appId = config('services.connectips.app_id');
            $appPassword = config('services.connectips.app_password');
            $validateUrl = config('services.connectips.validate_url');

            $tokenString = "MERCHANTID={$merchantId},APPID={$appId},REFERENCEID={$txnId},TXNAMT={$amount}";
            $token = $this->generateConnectIpsToken($tokenString);

            $response = Http::withBasicAuth($appId, $appPassword)
                ->post($validateUrl, [
                    "merchantId" => $merchantId,
                    "appId" => $appId,
                    "referenceId" => $txnId,
                    "txnAmt" => $amount,
                    "token" => $token
                ]);

            return $response->json();
        }

        public function connectipsSuccess(Request $request)
        {
            $txnId = $request->TXNID;

            if (!$txnId) {
                return view('candidate.payment.failure');
            }

            $payment = Payment::where('txRef', $txnId)->first();

            if (!$payment) {
                return view('candidate.payment.failure');
            }

            $validation = $this->validateConnectIps($txnId, $payment->amount * 100);
              dd($validation);

            if (!$validation || ($validation['responseCode'] ?? '') !== '00') {
                return view('candidate.payment.failure');
            }

            $payment->status = 'paid';
            $payment->transaction_id = $validation['txnId'] ?? null;
            $payment->save();

            session(['draft_id' => $payment->draft_id]);

            return $this->paymentSuccess($payment, $validation);
        }


        public function connectipsFailure()
        {
            return view('candidate.payment.failure');
        }









    // ==================================================
    // Esewa, Khalti and ConnectIps final success recipt
    // ==================================================

  public function paymentSuccess(Payment $payment, $gatewayData = null)
{
    $gateway = strtoupper($payment->gateway);


    $application = ApplicationForm::find($payment->draft_id);

    // Update application status to pending after successful payment
    if ($application && $application->status === 'draft') {
        $application->status = 'submitted';
        $application->save();
}

    // ALWAYS define candidateName
    $candidateName = 'N/A';
    if ($application && !empty($application->name_english)) {
        $candidateName = $application->name_english;
    } elseif (auth()->check() && !empty(auth()->user()->name)) {
        $candidateName = auth()->user()->name;
    }

    $applicationId = $payment->draft_id;

    $responseData = [];

    if ($gateway === 'ESEWA' && $gatewayData) {
        $responseData = [
            'transaction_code' => $gatewayData['transaction_code'] ?? 'N/A',
            'total_amount' => $gatewayData['total_amount'] ?? 'N/A',
            'status' => $gatewayData['status'] ?? 'N/A',
            'transaction_uuid' => $gatewayData['transaction_uuid'] ?? 'N/A',
            'product_code' => $gatewayData['product_code'] ?? 'N/A',
        ];
    } elseif ($gateway === 'KHALTI' && $gatewayData) {
        $responseData = [
            'transaction_code' => $gatewayData['transaction_id'] ?? 'N/A',
            'total_amount' => $payment->amount ?? 'N/A',
            'status' => $payment->status ?? 'N/A',
            'transaction_uuid' => $payment->txRef,
            'product_code' => $gatewayData['purchase_order_name'] ?? 'N/A',
        ];
    } elseif ($gateway === 'CONNECTIPS' && $gatewayData) {
        $responseData = [
            'transaction_code' => $gatewayData['txnId'] ?? 'N/A',
            'total_amount' => $payment->amount,
            'status' => $gatewayData['responseDescription'] ?? 'N/A',
            'transaction_uuid' => $payment->txRef,
            'product_code' => 'Application Fee',
        ];
    }

    return view('candidate.payment.success', [
        'payment' => $payment,
        'esewaData' => $responseData,
        'candidateName' => $candidateName,
        'applicationId' => $applicationId,
        'application' => $application,
    ]);
}


}