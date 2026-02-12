<!DOCTYPE html>
<html>
<head>
    <title>Payment Failed</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <div class="alert alert-danger">
        <h4>Payment Failed!</h4>
        <p>Something went wrong with your payment. Please try again.</p>
    </div>
    <a href="{{ route('candidate.dashboard') }}" class="btn btn-primary">Back to Dashboard</a>
</div>
</body>
</html>
