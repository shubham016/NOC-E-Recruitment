<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Session Expired</title>
    <style>
        body { font-family: sans-serif; display: flex; align-items: center; justify-content: center; min-height: 100vh; margin: 0; background: #f3f4f6; }
        .box { background: #fff; border-radius: 8px; padding: 40px 48px; text-align: center; max-width: 420px; box-shadow: 0 2px 12px rgba(0,0,0,.08); }
        h1 { font-size: 4rem; margin: 0 0 8px; color: #c9a84c; }
        h2 { font-size: 1.3rem; margin: 0 0 12px; color: #1f2937; }
        p  { color: #6b7280; margin: 0 0 28px; }
        a  { display: inline-block; padding: 10px 28px; background: #c9a84c; color: #fff; border-radius: 6px; text-decoration: none; font-weight: 600; }
        a:hover { background: #a07828; }
    </style>
    <script>
        // Auto-redirect back after 1 second so the user gets a fresh form
        window.onload = function () {
            if (document.referrer) {
                window.location.href = document.referrer;
            }
        };
    </script>
</head>
<body>
    <div class="box">
        <h1>419</h1>
        <h2>Session Expired</h2>
        <p>Your session has expired. Please go back and try again.</p>
        <a href="javascript:history.back()">Go Back</a>
    </div>
</body>
</html>
