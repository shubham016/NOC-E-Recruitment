<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payment;
use Illuminate\Support\Facades\Http;
use App\Models\ApplicationForm;
use App\Models\JobPosting;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    private function findCandidateApplication($draftId): ?ApplicationForm
    {
        $candidate = Auth::guard('candidate')->user();

        if (!$candidate) {
            return null;
        }

        return ApplicationForm::where('id', $draftId)
            ->where('citizenship_number', $candidate->citizenship_number)
            ->first();
    }

    private function paymentBelongsToCurrentCandidate(Payment $payment): bool
    {
        $candidate = Auth::guard('candidate')->user();

        if (!$candidate) {
            return false;
        }

        $application = $payment->application;

        return $application
            && $application->citizenship_number === $candidate->citizenship_number;
    }

    private function clearPendingPaymentAttempts(ApplicationForm $application, string $gateway): void
    {
        Payment::where('draft_id', $application->id)
            ->where('gateway', $gateway)
            ->where('status', Payment::STATUS_PENDING)
            ->delete();
    }

    private function missingConfigKeys(string $prefix, array $values): array
    {
        return collect($values)
            ->filter(fn ($value) => blank($value))
            ->keys()
            ->map(fn ($key) => $prefix . '.' . $key)
            ->all();
    }

    private function effectiveApplicationFee(JobPosting $job): float
    {
        $isDoubleDastur = $job->deadline && now()->gt($job->deadline)
            && $job->double_dastur_fee
            && $job->double_dastur_date
            && now()->lte($job->double_dastur_date);

        return (float) ($isDoubleDastur ? $job->double_dastur_fee : ($job->application_fee ?? 0));
    }

    private function feeOptionsForJob(JobPosting $job): array
    {
        $options = [];
        $fee = $this->effectiveApplicationFee($job);
        $advNo = $job->advertisement_no ?: (string) $job->id;

        if ($job->category === 'internal_appraisal') {
            $options[] = ['category' => 'internal_appraisal', 'adv_no' => $advNo, 'fee' => $fee];
        }

        if ($job->category === 'open' || $job->has_open) {
            $options[] = ['category' => 'open', 'adv_no' => $advNo, 'fee' => $fee];
        }

        if ($job->category === 'inclusive' || $job->has_inclusive) {
            $options[] = ['category' => 'inclusive', 'adv_no' => $advNo, 'fee' => $fee];
        }

        if ($job->has_internal_open) {
            $options[] = ['category' => 'internal_open', 'adv_no' => $advNo, 'fee' => $fee];
        }

        if ($job->has_internal_inclusive) {
            $options[] = ['category' => 'internal_inclusive', 'adv_no' => $advNo, 'fee' => $fee];
        }

        return $options;
    }

    private function sumFeesForCategories($options, array $categories): float
    {
        $selected = array_flip(array_unique($categories));
        $seenAdvNos = [];
        $total = 0;

        foreach ($options as $option) {
            if (!isset($selected[$option['category']]) || isset($seenAdvNos[$option['adv_no']])) {
                continue;
            }

            $seenAdvNos[$option['adv_no']] = true;
            $total += (float) $option['fee'];
        }

        return $total;
    }

    private function calculateGroupedFee(ApplicationForm $application): float
    {
        $job = $application->jobPosting;
        $selectedCategories = $application->applied_category ?? [];

        if (!$job || empty($selectedCategories)) {
            return 0;
        }

        $groupJobs = JobPosting::where('status', 'active')
            ->where(function ($q) {
                $q->where('deadline', '>=', now())
                    ->orWhere(function ($inner) {
                        $inner->whereNotNull('double_dastur_date')
                            ->where('double_dastur_date', '>=', now());
                    });
            })
            ->where('position', $job->position)
            ->where('level', $job->level)
            ->where('service_group', $job->service_group)
            ->orderBy('advertisement_no', 'asc')
            ->get();

        if ($groupJobs->isEmpty()) {
            $groupJobs = collect([$job]);
        }

        $options = $groupJobs
            ->flatMap(fn (JobPosting $groupJob) => $this->feeOptionsForJob($groupJob))
            ->values();

        $hasOpen = in_array('open', $selectedCategories, true)
            || in_array('internal_open', $selectedCategories, true);
        $hasInclusive = in_array('inclusive', $selectedCategories, true)
            || in_array('internal_inclusive', $selectedCategories, true);

        $feeCategories = $selectedCategories;
        if ($hasInclusive && !$hasOpen) {
            $feeCategories = array_values(array_filter(
                $selectedCategories,
                fn ($category) => !in_array($category, ['inclusive', 'internal_inclusive'], true)
            ));

            if ($options->contains('category', 'open')) {
                $feeCategories[] = 'open';
            }

            if ($options->contains('category', 'internal_open')) {
                $feeCategories[] = 'internal_open';
            }
        }

        $total = $this->sumFeesForCategories($options, $feeCategories);

        if ($total <= 0 && $feeCategories !== $selectedCategories) {
            $total = $this->sumFeesForCategories($options, $selectedCategories);
        }

        return $total;
    }

    private function paymentFailure(string $message = 'Something went wrong with your payment. Please try again.', ?string $gateway = null)
    {
        return view('candidate.payment.failure', compact('message', 'gateway'));
    }


    // =======================
    // Shared amount calculator
    // =======================

    /**
     * Calculate the correct payable amount for an application.
     * Mirrors the category summary rule used by the candidate application form.
     */
    private function calculateAmount(ApplicationForm $application): float
    {
        $groupedFee = $this->calculateGroupedFee($application);
        if ($groupedFee > 0) {
            return $groupedFee;
        }

        return ((float) $application->total_fee > 0)
            ? (float) $application->total_fee
            : (float) (optional($application->jobPosting)->application_fee ?? 0);
    }

    // =======================
    // Esewa Payment
    // =======================
    // ESEWA START
        public function startEsewa($draftId)
        {
            $application = $this->findCandidateApplication($draftId);

            if (!$application) {
                return redirect()->route('candidate.applications.index')
                    ->with('error', 'Application not found or unauthorized.');
            }

            $amount = $this->calculateAmount($application);

            if ($amount <= 0) {
                return redirect()->back()->with('error', 'Invalid payment amount. Please contact support.');
            }

            $tax_amount = 0;
            // Send as integer string — eSewa displays exactly what we POST, no decimals wanted
            $amount       = (string) (int) round($amount);
            $total_amount = (string) ((int) $amount + $tax_amount);
            $transaction_uuid = uniqid('txn_');
            $product_code = config('services.esewa.merchant_id');
            $secret = config('services.esewa.secret_key');
            $esewaBaseUrl = rtrim(config('services.esewa.base_url'), '/');
            $successUrl = route('candidate.payment.esewa.success');
            $failureUrl = route('candidate.payment.esewa.failure');

            $missingConfig = $this->missingConfigKeys('services.esewa', [
                'merchant_id' => $product_code,
                'secret_key' => $secret,
                'base_url' => $esewaBaseUrl,
            ]);

            if ($missingConfig) {
                return redirect()->back()->with('error', 'eSewa is not configured. Missing: ' . implode(', ', $missingConfig));
            }

            $this->clearPendingPaymentAttempts($application, 'esewa');

            // Create pending payment
            $payment = Payment::create([
                'draft_id' => $application->id,
                'gateway' => 'esewa',
                'amount' => $amount,
                'status' => 'pending',
                'txRef' => $transaction_uuid
            ]);

            // Generate signature according to eSewa doc (HMAC SHA256)
            $signed_field_names = 'total_amount,transaction_uuid,product_code';
            $string_to_sign = "total_amount=$total_amount,transaction_uuid=$transaction_uuid,product_code={$product_code}";
            $signature = base64_encode(hash_hmac('sha256', $string_to_sign, $secret, true));
            $esewaUrl = $esewaBaseUrl . '/api/epay/main/v2/form';

            return view('payment.esewa', compact(
                'payment', 'amount', 'tax_amount', 'total_amount', 'transaction_uuid',
                'product_code', 'successUrl', 'failureUrl', 'signed_field_names', 'signature',
                'esewaUrl'
            ));
        }

    // ESEWA SUCCESS
       public function esewaSuccess(Request $request)
            {
                if (!$request->has('data')) {
                    return $this->paymentFailure('eSewa did not return payment data.', 'esewa');
                }

                $decoded = json_decode(base64_decode($request->data), true);

                if (!$decoded) {
                    return $this->paymentFailure('Invalid eSewa payment response.', 'esewa');
                }

                $transaction_uuid = $decoded['transaction_uuid'] ?? null;
                $signature = $decoded['signature'] ?? null;
                $signed_field_names = $decoded['signed_field_names'] ?? null;

                if (!$transaction_uuid || !$signature || !$signed_field_names) {
                    return $this->paymentFailure('Incomplete eSewa payment response.', 'esewa');
                }

                $fields = explode(',', $signed_field_names);

                $string_to_sign = "";

                foreach ($fields as $field) {
                    if (!array_key_exists($field, $decoded)) {
                        return $this->paymentFailure('eSewa response is missing signed field: ' . $field, 'esewa');
                    }
                    $string_to_sign .= $field . "=" . $decoded[$field] . ",";
                }

                $string_to_sign = rtrim($string_to_sign, ",");

                $secret = config('services.esewa.secret_key');

                if (!$secret) {
                    return $this->paymentFailure('eSewa secret key is not configured.', 'esewa');
                }

                $generated_signature = base64_encode(hash_hmac('sha256', $string_to_sign, $secret, true));

                if ($generated_signature !== $signature) {
                    return $this->paymentFailure('eSewa payment signature verification failed.', 'esewa');
                }

                $payment = Payment::where('txRef', $transaction_uuid)->first();

                if (!$payment) {
                    return $this->paymentFailure('Payment record was not found for this eSewa transaction.', 'esewa');
                }

                if (!$this->paymentBelongsToCurrentCandidate($payment)) {
                    return $this->paymentFailure('Unauthorized eSewa payment verification.', 'esewa');
                }

            if (($decoded['status'] ?? '') === "COMPLETE") {
            $payment->status = 'paid';
            $payment->transaction_id = $decoded['transaction_code'] ?? null;
            $payment->save();

            session(['draft_id' => $payment->draft_id]);

            return $this->paymentSuccess($payment, $decoded);
            }

            return $this->paymentFailure('eSewa payment was not completed.', 'esewa'); // outside the if block

         }

// ESEWA FAILURE
            public function esewaFailure()
            {
                return $this->paymentFailure('eSewa payment was cancelled or failed.', 'esewa');
            }




        // =======================
        // Khalti Payment
        // =======================

        public function startKhalti($draftId)
        {
            $application = $this->findCandidateApplication($draftId);

            if (!$application) {
                return redirect()->route('candidate.applications.index')
                    ->with('error', 'Application not found or unauthorized.');
            }

            $amount = $this->calculateAmount($application);

            if ($amount <= 0) {
                return redirect()->back()->with('error', 'Invalid payment amount. Please contact support.');
            }

            // Khalti requires amount in paisa as a strict integer
            $amount_in_paisa = (int) round($amount * 100);
            $txRef = uniqid('khalti_');

            $secretKey = config('services.khalti.secret_key');
            $khaltiBaseUrl = rtrim(config('services.khalti.base_url', 'https://dev.khalti.com/api/v2'), '/');

            $missingConfig = $this->missingConfigKeys('services.khalti', [
                'secret_key' => $secretKey,
                'base_url' => $khaltiBaseUrl,
            ]);

            if ($missingConfig) {
                return redirect()->back()->with('error', 'Khalti is not configured. Missing: ' . implode(', ', $missingConfig));
            }

            $candidateName  = $application->name_english ?? 'Candidate';
            $candidateEmail = $application->email ?? 'candidate@example.com';
            $candidatePhone = $application->phone ?? '9800000000';

            try {
                $response = Http::withHeaders([
                    'Authorization' => 'Key ' . $secretKey,
                    'Content-Type' => 'application/json',
                ])->post($khaltiBaseUrl . '/epayment/initiate/', [
                    "return_url" => route('candidate.payment.khalti.success'),
                    "website_url" => url('/'),
                    "amount" => $amount_in_paisa,
                    "purchase_order_id" => $txRef,
                    "purchase_order_name" => "Application Fee",
                    "customer_info" => [
                        "name" => $candidateName,
                        "email" => $candidateEmail,
                        "phone" => $candidatePhone
                    ]
                ]);
            } catch (\Throwable $e) {
                return redirect()->back()->with('error', 'Khalti initiation failed: ' . $e->getMessage());
            }

            $responseJson = $response->json() ?? [];
            $paymentUrl = $responseJson['payment_url'] ?? null;
            $pidx = $responseJson['pidx'] ?? null;

            if ($response->successful() && $paymentUrl && $pidx) {
                $this->clearPendingPaymentAttempts($application, 'khalti');

                Payment::create([
                    'draft_id' => $application->id,
                    'gateway' => 'khalti',
                    'amount' => $amount,
                    'status' => 'pending',
                    'transaction_id' => $pidx,
                    'txRef' => $txRef
                ]);

                return redirect()->away($paymentUrl)->withHeaders([
                    'Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0',
                    'Pragma' => 'no-cache',
                ]);
            }

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
                return $this->paymentFailure('Khalti did not return a payment reference.', 'khalti');
            }

            try {
                $response = Http::withHeaders([
                    'Authorization' => 'Key ' . config('services.khalti.secret_key'),
                    'Content-Type' => 'application/json',
                ])->post(rtrim(config('services.khalti.base_url', 'https://dev.khalti.com/api/v2'), '/') . '/epayment/lookup/', [
                    "pidx" => $pidx
                ]);
            } catch (\Throwable $e) {
                return $this->paymentFailure('Could not verify Khalti payment: ' . $e->getMessage(), 'khalti');
            }

            $data = $response->json();

            if (!$response->successful() || !is_array($data)) {
                return $this->paymentFailure('Khalti lookup failed.', 'khalti');
            }

            $purchaseOrderId = $data['purchase_order_id'] ?? $request->purchase_order_id ?? null;
            $status = strtolower($data['status'] ?? $request->status ?? '');

            if (!$purchaseOrderId || $status !== 'completed') {
                return $this->paymentFailure('Khalti payment is not completed.', 'khalti');
            }

            // Find payment
            $payment = Payment::where('transaction_id', $pidx)
                ->orWhere('txRef', $purchaseOrderId)
                ->first();

            if (!$payment) {
                return $this->paymentFailure('Payment record was not found for this Khalti transaction.', 'khalti');
            }

            if (!$this->paymentBelongsToCurrentCandidate($payment)) {
                return $this->paymentFailure('Unauthorized Khalti payment verification.', 'khalti');
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
                return $this->paymentFailure('Khalti payment was cancelled or failed.', 'khalti');
            }

            public function verifyKhalti(Request $request)
            {
                return $this->khaltiSuccess($request);
            }


        // =======================
        // ConnectIps Payment
        // =======================

        public function startConnectIps($draftId)
        {
            $application = $this->findCandidateApplication($draftId);

            if (!$application) {
                return redirect()->route('candidate.applications.index')
                    ->with('error', 'Application not found or unauthorized.');
            }

            $amount = $this->calculateAmount($application);
            if ($amount <= 0) {
                return redirect()->back()->with('error', 'Invalid payment amount. Please contact support.');
            }

            $amountInPaisa = (int) round($amount * 100);

            $txnId = 'CIPS' . now()->format('YmdHis') . random_int(1000, 9999);
            $txnDate = now()->format('d-m-Y H:i:s');
            $successUrl = route('candidate.payment.connectips.success');
            $failureUrl = route('candidate.payment.connectips.failure');

            $merchantId = config('services.connectips.merchant_id');
            $appId = config('services.connectips.app_id');
            $appName = config('services.connectips.app_name');
            $txnUrl = config('services.connectips.txn_url');

            $missingConfig = $this->missingConfigKeys('services.connectips', [
                'merchant_id' => $merchantId,
                'app_id' => $appId,
                'app_name' => $appName,
                'txn_url' => $txnUrl,
            ]);

            if ($missingConfig) {
                return redirect()->back()->with('error', 'ConnectIPS is not configured. Missing: ' . implode(', ', $missingConfig));
            }

            $referenceId = $txnId;
            $remarks = trim("Application Fee");
            $particulars = trim("Application Payment");

            $tokenString = "MERCHANTID={$merchantId},APPID={$appId},APPNAME={$appName},TXNID={$txnId},TXNDATE={$txnDate},TXNCRNCY=NPR,TXNAMT={$amountInPaisa},REFERENCEID={$referenceId},REMARKS={$remarks},PARTICULARS={$particulars},TOKEN=TOKEN";

            try {
                $token = $this->generateConnectIpsToken($tokenString);
            } catch (\Throwable $e) {
                return redirect()->back()->with('error', 'ConnectIPS initiation failed: ' . $e->getMessage());
            }

            $this->clearPendingPaymentAttempts($application, 'connectips');

            Payment::create([
                'draft_id' => $application->id,
                'gateway' => 'connectips',
                'amount' => $amount,
                'status' => 'pending',
                'txRef' => $txnId
            ]);

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
                'txnUrl',
                'token'
            ));
        }

        private function generateConnectIpsToken($data)
        {
            $privateKey = $this->loadConnectIpsPrivateKey();
            $keyResource = openssl_pkey_get_private($privateKey);

            if (!$keyResource) {
                throw new \Exception("Invalid ConnectIPS private key.");
            }

            openssl_sign($data, $signature, $keyResource, OPENSSL_ALGO_SHA256);

            return base64_encode($signature);
        }

        private function loadConnectIpsPrivateKey(): string
        {
            $pfxPath = config('services.connectips.pfx_path');
            if ($pfxPath && file_exists($pfxPath)) {
                $pfx = file_get_contents($pfxPath);
                $certificates = [];

                if (!openssl_pkcs12_read($pfx, $certificates, config('services.connectips.pfx_password'))) {
                    throw new \Exception("Invalid ConnectIPS PFX file or password.");
                }

                if (empty($certificates['pkey'])) {
                    throw new \Exception("ConnectIPS PFX file does not contain a private key.");
                }

                return $certificates['pkey'];
            }

            $privateKeyPath = config('services.connectips.private_key_path');

            if (!config('services.connectips.allow_private_key_fallback')) {
                throw new \Exception("Official ConnectIPS PFX certificate not found. Place it at storage/app/connectips/merchant.pfx or set CONNECTIPS_PFX_PATH.");
            }

            if (!$privateKeyPath || !file_exists($privateKeyPath)) {
                throw new \Exception("ConnectIPS private key file not found.");
            }

            return file_get_contents($privateKeyPath);
        }

        private function validateConnectIps($txnId, $amount)
        {
            $merchantId = config('services.connectips.merchant_id');
            $appId = config('services.connectips.app_id');
            $appPassword = config('services.connectips.app_password');
            $validateUrl = config('services.connectips.validate_url');

            $missingConfig = $this->missingConfigKeys('services.connectips', [
                'merchant_id' => $merchantId,
                'app_id' => $appId,
                'app_password' => $appPassword,
                'validate_url' => $validateUrl,
            ]);

            if ($missingConfig) {
                throw new \RuntimeException('ConnectIPS is not configured. Missing: ' . implode(', ', $missingConfig));
            }

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

            $data = $response->json();

            if (!$response->successful() || !is_array($data)) {
                throw new \RuntimeException('ConnectIPS validation request failed.');
            }

            return $data;
        }

        public function connectipsSuccess(Request $request)
        {
            $txnId = $request->TXNID;

            if (!$txnId) {
                return $this->paymentFailure('ConnectIPS did not return a transaction id.', 'connectips');
            }

            $payment = Payment::where('txRef', $txnId)->first();

            if (!$payment) {
                return $this->paymentFailure('Payment record was not found for this ConnectIPS transaction.', 'connectips');
            }

            if (!$this->paymentBelongsToCurrentCandidate($payment)) {
                return $this->paymentFailure('Unauthorized ConnectIPS payment verification.', 'connectips');
            }

            try {
                $validation = $this->validateConnectIps($txnId, $payment->amount * 100);
            } catch (\Throwable $e) {
                return $this->paymentFailure('Could not verify ConnectIPS payment: ' . $e->getMessage(), 'connectips');
            }

            if (!$validation || ($validation['responseCode'] ?? '') !== '00') {
                return $this->paymentFailure('ConnectIPS payment validation failed.', 'connectips');
            }

            $payment->status = 'paid';
            $payment->transaction_id = $validation['txnId'] ?? null;
            $payment->save();

            session(['draft_id' => $payment->draft_id]);

            return $this->paymentSuccess($payment, $validation);
        }


        public function connectipsFailure()
        {
            return $this->paymentFailure('ConnectIPS payment was cancelled or failed.', 'connectips');
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
