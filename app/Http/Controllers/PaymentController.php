<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payment;
use App\Models\ApplicationForm;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    public function startEsewa($draftId)
    {
        $amount = 1000;
        $tax_amount = 0;
        $total_amount = $amount + $tax_amount;
        $transaction_uuid = uniqid('txn_');
        $product_code = 'EPAYTEST';
        $successUrl = route('candidate.payment.esewa.success');
        $failureUrl = route('candidate.payment.esewa.failure');

        $payment = Payment::create([
            'draft_id' => $draftId,
            'gateway'  => 'esewa',
            'amount'   => $amount,
            'status'   => 'pending',
            'txRef'    => $transaction_uuid
        ]);

        $signed_field_names = 'total_amount,transaction_uuid,product_code';
        $string_to_sign = "total_amount=$total_amount,transaction_uuid=$transaction_uuid,product_code={$product_code}";
        $secret = env('ESEWA_SECRET_KEY');
        $signature = base64_encode(hash_hmac('sha256', $string_to_sign, $secret, true));

        return view('payment.esewa', compact(
            'payment', 'amount', 'tax_amount', 'total_amount', 'transaction_uuid',
            'product_code', 'successUrl', 'failureUrl', 'signed_field_names', 'signature'
        ));
    }

    public function esewaSuccess(Request $request)
    {
        if (!$request->has('data')) {
            return view('candidate.payment.failure');
        }

        $decoded = json_decode(base64_decode($request->data), true);

        if (!$decoded) {
            return view('candidate.payment.failure');
        }

        $transaction_uuid    = $decoded['transaction_uuid'];
        $signature           = $decoded['signature'];
        $signed_field_names  = $decoded['signed_field_names'];

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
            // Update payment status
            $payment->status         = 'paid';
            $payment->transaction_id = $decoded['transaction_code'] ?? null;
            $payment->save();

            // Update application status from draft to pending
            ApplicationForm::where('id', $payment->draft_id)
                ->update(['status' => 'pending']);

            return view('candidate.payment.success', [
                'payment'   => $payment,
                'esewaData' => $decoded
            ]);
        }

        return view('candidate.payment.failure');
    }

    public function esewaFailure()
    {
        return view('candidate.payment.failure');
    }
}  // ← single closing brace for the class