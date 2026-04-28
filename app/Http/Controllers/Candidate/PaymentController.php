<?php

namespace App\Http\Controllers\Candidate;

use App\Http\Controllers\Controller;
use App\Models\ApplicationForm;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    /**
     * Show eSewa payment page
     */
    public function showEsewa($applicationId)
    {
        if (!Session::has('candidate_logged_in')) {
            return redirect()->route('candidate.login')
                ->withErrors(['error' => 'Please login first']);
        }

        $candidate = DB::table('candidate_registration')
            ->where('id', Session::get('candidate_id'))
            ->first();

        $application = ApplicationForm::where('id', $applicationId)
            ->where('citizenship_number', $candidate->citizenship_number)
            ->firstOrFail();

        // Check if already paid
        $completedPayment = Payment::where('draft_id', $application->id)
            ->where('status', 'completed')
            ->first();

        if ($completedPayment) {
            return redirect()
                ->route('candidate.applications.show', $application->id)
                ->with('info', 'Payment has already been completed for this application.');
        }

        $amount = config('services.esewa.amount', 500);
        // $amount = $application->jobPosting->application_fee ?? 0;
        $txRef = 'TXN-' . strtoupper(Str::random(10)) . '-' . time();

        // Delete any pending payments and create a new one
        Payment::where('draft_id', $application->id)
            ->where('status', 'pending')
            ->delete();

        // Create new pending payment record
        $payment = Payment::create([
            'draft_id' => $application->id,
            'gateway' => 'esewa',
            'amount' => $amount,
            'status' => 'pending',
            'txRef' => $txRef,
        ]);

        $esewaConfig = [
            'amount' => $amount,
            'tax_amount' => 0,
            'total_amount' => $amount,
            'transaction_uuid' => $txRef,
            'product_code' => config('services.esewa.merchant_code', 'EPAYTEST'),
            'product_service_charge' => 0,
            'product_delivery_charge' => 0,
            'success_url' => route('candidate.payment.success'),
            'failure_url' => route('candidate.payment.failure'),
            'signed_field_names' => 'total_amount,transaction_uuid,product_code',
        ];

        // Generate signature
        $signedFieldNames = $esewaConfig['signed_field_names'];
        $fields = explode(',', $signedFieldNames);
        $signatureString = implode(',', array_map(function ($field) use ($esewaConfig) {
            return $field . '=' . $esewaConfig[$field];
        }, $fields));

        $secret = config('services.esewa.secret_key', '8gBm/:&EnhH.1/q');
        $esewaConfig['signature'] = base64_encode(hash_hmac('sha256', $signatureString, $secret, true));

        $esewaUrl = config('services.esewa.url', 'https://rc-epay.esewa.com.np/api/epay/main/v2/form');

        // Extract variables for the view
        $tax_amount = $esewaConfig['tax_amount'];
        $total_amount = $esewaConfig['total_amount'];
        $transaction_uuid = $esewaConfig['transaction_uuid'];
        $product_code = $esewaConfig['product_code'];
        $successUrl = $esewaConfig['success_url'];
        $failureUrl = $esewaConfig['failure_url'];
        $signed_field_names = $esewaConfig['signed_field_names'];
        $signature = $esewaConfig['signature'];

        return view('candidate.payment.esewa', compact(
            'application',
            'payment',
            'amount',
            'tax_amount',
            'total_amount',
            'transaction_uuid',
            'product_code',
            'successUrl',
            'failureUrl',
            'signed_field_names',
            'signature'
        ));
    }

    /**
     * Handle eSewa payment success callback
     */
    public function success(Request $request)
    {
        if (!Session::has('candidate_logged_in')) {
            return redirect()->route('candidate.login')
                ->withErrors(['error' => 'Please login first']);
        }

        $candidate = DB::table('candidate_registration')
            ->where('id', Session::get('candidate_id'))
            ->first();

        $encodedData = $request->query('data');
        if (!$encodedData) {
            return redirect()
                ->route('candidate.applications.index')
                ->with('error', 'Invalid payment response.');
        }

        $decodedData = json_decode(base64_decode($encodedData), true);

        if (!$decodedData || !isset($decodedData['transaction_uuid'])) {
            return redirect()
                ->route('candidate.applications.index')
                ->with('error', 'Invalid payment data.');
        }

        // Find the payment by txRef
        $payment = Payment::where('txRef', $decodedData['transaction_uuid'])->first();

        if (!$payment) {
            return redirect()
                ->route('candidate.applications.index')
                ->with('error', 'Payment record not found.');
        }

        // Verify ownership
        $application = $payment->application;
        if (!$application || $application->citizenship_number !== $candidate->citizenship_number) {
            return redirect()
                ->route('candidate.applications.index')
                ->with('error', 'Unauthorized payment verification.');
        }

        // Update payment status
        $payment->update([
            'status' => 'completed',
            'transaction_id' => $decodedData['transaction_code'] ?? null,
        ]);

        // Update application status from 'draft' to 'pending' (submitted)
        $application->update([
            'status' => 'pending',
            'submitted_at' => now(),
        ]);

        $candidateName  = $candidate->name;
        $applicationId  = $application->id;
        $esewaData      = $decodedData;

        return view('candidate.payment.success', compact(
            'payment', 'application', 'candidateName', 'applicationId', 'esewaData'
        ));
    }

    /**
     * Handle eSewa payment failure callback
     */
    public function failure(Request $request)
    {
        return view('candidate.payment.failure');
    }
}
