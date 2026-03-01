<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f4; margin: 0; padding: 20px; }
        .container { max-width: 560px; margin: 0 auto; background: #fff; border-radius: 4px; overflow: hidden; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .header { background: linear-gradient(90deg, #1a1a1a, #2d2d2d, #c9a84c); padding: 20px 30px; }
        .header h1 { color: #fff; margin: 0; font-size: 1.3rem; letter-spacing: 2px; }
        .body { padding: 30px; color: #333; line-height: 1.6; }
        .btn { display: inline-block; margin: 20px 0; padding: 12px 30px; background: linear-gradient(135deg, #c9a84c, #b8941f); color: #fff; text-decoration: none; border-radius: 2px; font-weight: bold; letter-spacing: 1px; }
        .footer { background: #fafafa; padding: 15px 30px; font-size: 0.78rem; color: #999; border-top: 1px solid #eee; }
        .warning { background: #fff8e1; border-left: 3px solid #c9a84c; padding: 10px 15px; margin-top: 20px; font-size: 0.85rem; color: #856404; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>NOC • E-RECRUITMENT</h1>
        </div>
        <div class="body">
            <p>Hello <strong>{{ $candidate->name }}</strong>,</p>
            <p>We received a request to reset the password for your account. Click the button below to set a new password:</p>
            <a href="{{ $resetUrl }}" class="btn">RESET MY PASSWORD</a>
            <div class="warning">
                ⚠ This link will expire in <strong>60 minutes</strong>. If you did not request a password reset, you can safely ignore this email.
            </div>
            <p style="margin-top:20px; font-size:0.85rem; color:#666;">
                If the button doesn't work, copy and paste this link into your browser:<br>
                <span style="color:#1a6da8; word-break:break-all;">{{ $resetUrl }}</span>
            </p>
        </div>
        <div class="footer">
            NOC E-Recruitment System &mdash; This is an automated message, please do not reply.
        </div>
    </div>
</body>
</html>