<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }

        .container {
            background: #f9fafb;
            border-radius: 8px;
            padding: 30px;
            border: 1px solid #e5e7eb;
        }

        .header {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 8px 8px 0 0;
            margin: -30px -30px 20px -30px;
        }

        .otp-box {
            background: white;
            border: 2px dashed #10b981;
            border-radius: 8px;
            padding: 20px;
            text-align: center;
            margin: 20px 0;
        }

        .otp-code {
            font-size: 36px;
            font-weight: bold;
            color: #10b981;
            letter-spacing: 8px;
            font-family: 'Courier New', monospace;
        }

        .warning {
            background: #fef3c7;
            color: #92400e;
            padding: 15px;
            border-radius: 6px;
            margin: 20px 0;
            border-left: 4px solid #f59e0b;
        }

        .footer {
            text-align: center;
            color: #6b7280;
            font-size: 14px;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
        }

        .btn {
            display: inline-block;
            padding: 12px 24px;
            background: #10b981;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            margin: 10px 0;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1 style="margin: 0;">🔐 {{ $type === 'password_reset' ? 'Password Reset' : 'Email Verification' }}</h1>
        </div>

        <p>Hello <strong>{{ $name }}</strong>,</p>

        @if($type === 'password_reset')
            <p>We received a request to reset your password. Use the OTP code below to proceed with resetting your password:
            </p>
        @else
            <p>Thank you for registering with our Recruitment Portal! To complete your registration, please verify your
                email address using the OTP code below:</p>
        @endif

        <div class="otp-box">
            <p style="margin: 0 0 10px 0; font-size: 14px; color: #6b7280;">Your OTP Code</p>
            <div class="otp-code">{{ $otp }}</div>
            <p style="margin: 10px 0 0 0; font-size: 14px; color: #6b7280;">Valid for 10 minutes</p>
        </div>

        @if($type === 'password_reset')
            <p>If you didn't request a password reset, please ignore this email. Your password will remain unchanged.</p>
        @else
            <p>Enter this code on the verification page to activate your account and start applying for jobs.</p>
        @endif

        <div class="warning">
            <strong>⚠️ Security Notice:</strong><br>
            • Never share this OTP with anyone<br>
            • This code will expire in 10 minutes<br>
            • If you didn't request this, please contact support
        </div>

        <p>Need help? Contact our support team at <a href="mailto:support@recruitment.com">support@recruitment.com</a>
        </p>

        <div class="footer">
            <p><strong>Recruitment Management System</strong></p>
            <p>© {{ date('Y') }} All rights reserved</p>
            <p style="font-size: 12px; color: #9ca3af;">This is an automated email. Please do not reply to this message.
            </p>
        </div>
    </div>
</body>

</html>