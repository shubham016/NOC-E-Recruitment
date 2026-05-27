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
            ->whereIn('status', ['completed', 'paid'])
            ->first();

        if ($completedPayment) {
            return redirect()
                ->route('candidate.applications.show', $application->id)
                ->with('info', 'Payment has already been completed for this application.');
        }

        $job = $application->jobPosting;

        // Block payment if both regular deadline and double dastur window have fully expired
        if ($job) {
            $fullyExpired = $job->status !== 'active'
                || (
                    $job->deadline && now()->gt($job->deadline)
                    && (!$job->double_dastur_date || now()->gt($job->double_dastur_date))
                );

            if ($fullyExpired) {
                return redirect()->route('candidate.applications.index')
                    ->with('error', 'The application deadline for this vacancy has fully expired. Payment is no longer accepted.');
            }
        }

        // Determine if the primary job is in double dastur period
        $inDoubleDastur = $job
            && $job->deadline && now()->gt($job->deadline)
            && $job->double_dastur_fee && $job->double_dastur_date
            && now()->lte($job->double_dastur_date);

        if ($inDoubleDastur) {
            // Recalculate total across ALL selected categories using each sibling job's double_dastur_fee.
            // This mirrors the JS updateTotalFee() logic in the application form.
            $selectedCategories = $application->applied_category ?? [];
            if (!is_array($selectedCategories)) {
                $selectedCategories = json_decode($selectedCategories, true) ?? [];
            }

            // Load all sibling jobs (same position+level+service_group, still within double dastur window)
            $siblingJobs = \App\Models\JobPosting::where('status', 'active')
                ->where('position', $job->position)
                ->where('level', $job->level)
                ->where('service_group', $job->service_group)
                ->where('double_dastur_date', '>=', now())
                ->get();

            // Map each selected category to its sibling job's double_dastur_fee
            $amount = 0;
            foreach ($selectedCategories as $cat) {
                $match = null;
                foreach ($siblingJobs as $sj) {
                    if ($cat === 'open'               && ($sj->has_open || $sj->category === 'open'))                { $match = $sj; break; }
                    if ($cat === 'inclusive'           && ($sj->has_inclusive || $sj->category === 'inclusive'))      { $match = $sj; break; }
                    if ($cat === 'internal_open'       && $sj->has_internal_open)                                    { $match = $sj; break; }
                    if ($cat === 'internal_inclusive'  && $sj->has_internal_inclusive)                               { $match = $sj; break; }
                    if ($cat === 'internal_appraisal'  && $sj->category === 'internal_appraisal')                    { $match = $sj; break; }
                }
                if ($match) {
                    $amount += (float) ($match->double_dastur_fee ?: $match->application_fee);
                }
            }

            // Fallback: if nothing matched, use primary job's double_dastur_fee
            if ($amount <= 0) {
                $amount = (float) $job->double_dastur_fee;
            }
        } else {
            // Normal period: use stored total_fee, fall back to application_fee
            $amount = ((float) $application->total_fee > 0)
                ? (float) $application->total_fee
                : (float) (optional($job)->application_fee ?? 0);
        }

        if ($amount <= 0) {
            return redirect()->route('candidate.applications.index')
                ->with('error', 'Invalid payment amount. Please contact support.');
        }

        // Send as integer string — eSewa displays exactly what we POST, no decimals wanted
        $amount = (string) (int) round($amount);
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
