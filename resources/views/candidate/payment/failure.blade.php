<!DOCTYPE html>
<html>
<head>
    <title>Payment Failed</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <div class="alert alert-danger">
        <h4>Payment Failed{{ isset($gateway) ? ' - ' . strtoupper($gateway) : '' }}</h4>
        <p>{{ $message ?? session('error') ?? 'Something went wrong with your payment. Please try again.' }}</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('candidate.applications.index') }}" class="btn btn-danger">Back to Applications</a>
        <a href="{{ route('candidate.dashboard') }}" class="btn btn-outline-secondary">Dashboard</a>
    </div>
</div>
</body>
</html>
