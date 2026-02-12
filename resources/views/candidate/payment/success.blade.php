<!DOCTYPE html>
<html>
<head>
    <title>Payment Successful</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
</head>
<body>

<div class="container mt-5">

    <div class="card shadow">
        <div class="card-header bg-success text-white">
            <h4 class="mb-0">Payment Receipt</h4>
        </div>

        <div class="card-body">

            <div class="alert alert-success">
                <strong>✓ Payment Successful!</strong>
                <br>
                Your payment has been completed successfully.
            </div>

            <h5 class="mb-3">Transaction Details</h5>

            <table class="table table-bordered">

                <tr>
                    <th>Transaction ID</th>
                    <td>{{ $payment->txRef }}</td>
                </tr>

                <tr>
                    <th>Payment Method</th>
                    <td>{{ strtoupper($payment->gateway) }}</td>
                </tr>

                <tr>
                    <th>Amount Paid</th>
                    <td>Rs. {{ $payment->amount }}</td>
                </tr>

                <tr>
                    <th>Status</th>
                    <td>
                        <span class="badge bg-success">
                            {{ ucfirst($payment->status) }}
                        </span>
                    </td>
                </tr>

                <tr>
                    <th>Date & Time</th>
                    <td>{{ $payment->created_at }}</td>
                </tr>

                <!-- <tr>
                    <th>Application ID</th>
                    <td>{{ $payment->application_id }}</td>
                </tr> -->

            </table>


            <h5 class="mt-4 mb-3">eSewa Response Details</h5>

            <table class="table table-sm table-bordered">

                <tr>
                    <th>eSewa Transaction Code</th>
                    <td>{{ $esewaData['transaction_code'] ?? 'N/A' }}</td>
                </tr>

                <tr>
                    <th>Total Amount</th>
                    <td>{{ $esewaData['total_amount'] ?? 'N/A' }}</td>
                </tr>

                <tr>
                    <th>Status</th>
                    <td>{{ $esewaData['status'] ?? 'N/A' }}</td>
                </tr>

                <tr>
                    <th>Transaction UUID</th>
                    <td>{{ $esewaData['transaction_uuid'] ?? 'N/A' }}</td>
                </tr>

                <tr>
                    <th>Product Code</th>
                    <td>{{ $esewaData['product_code'] ?? 'N/A' }}</td>
                </tr>

            </table>


            <div class="alert alert-info mt-3">
                📌 Please take a screenshot of this page for your records.
            </div>

            <div class="mt-4 d-flex justify-content-between">

                <button onclick="window.print()" class="btn btn-secondary">
                    Print / Save as PDF
                </button>

                <a href="{{ route('candidate.applications.index') }}" class="btn btn-primary">
                    Go to My Applications
                </a>

            </div>

        </div>
    </div>

</div>

</body>
</html>
