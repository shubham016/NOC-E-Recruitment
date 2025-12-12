@extends('layouts.app')

@section('title', 'Verify OTP - Password Reset')

@section('custom-styles')
    <style>
        .verify-wrapper {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .verify-card {
            max-width: 500px;
            width: 100%;
            background: white;
            border-radius: 16px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            overflow: hidden;
        }

        .verify-header {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            padding: 2rem;
            text-align: center;
        }

        .verify-icon {
            width: 80px;
            height: 80px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            font-size: 36px;
        }

        .otp-input {
            width: 60px;
            height: 60px;
            font-size: 24px;
            font-weight: bold;
            text-align: center;
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            margin: 0 5px;
            transition: all 0.3s ease;
        }

        .otp-input:focus {
            border-color: #10b981;
            box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
            outline: none;
        }

        .btn-verify {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            border: none;
            padding: 12px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-verify:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 16px rgba(16, 185, 129, 0.3);
        }

        .resend-link {
            color: #10b981;
            text-decoration: none;
            font-weight: 600;
        }

        .resend-link:hover {
            color: #059669;
            text-decoration: underline;
        }

        @media (max-width: 576px) {
            .verify-card {
                margin: 1rem;
            }

            .otp-input {
                width: 45px;
                height: 45px;
                font-size: 20px;
                margin: 0 3px;
            }
        }
    </style>
@endsection

@section('content')
    <div class="verify-wrapper">
        <div class="verify-card">
            <div class="verify-header">
                <div class="verify-icon">
                    <i class="bi bi-shield-lock"></i>
                </div>
                <h2 class="mb-2">Verify OTP Code</h2>
                <p class="mb-0 opacity-90">Enter the code sent to</p>
                <p class="mb-0 fw-bold">{{ $email }}</p>
            </div>

            <div class="p-4">
                @if (session('success'))
                    <div class="alert alert-success">
                        <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <i class="bi bi-exclamation-circle me-2"></i>{{ $errors->first() }}
                    </div>
                @endif

                <form method="POST" action="{{ route('candidate.password.verify-otp.post') }}" id="otpForm">
                    @csrf

                    <div class="mb-4">
                        <label class="form-label text-center d-block mb-3">Enter 6-Digit OTP Code</label>
                        <div class="d-flex justify-content-center">
                            <input type="text" class="otp-input" maxlength="1" id="otp1" autofocus>
                            <input type="text" class="otp-input" maxlength="1" id="otp2">
                            <input type="text" class="otp-input" maxlength="1" id="otp3">
                            <input type="text" class="otp-input" maxlength="1" id="otp4">
                            <input type="text" class="otp-input" maxlength="1" id="otp5">
                            <input type="text" class="otp-input" maxlength="1" id="otp6">
                        </div>
                        <input type="hidden" name="otp" id="otpValue">
                    </div>

                    <div class="alert alert-info border-0">
                        <i class="bi bi-info-circle me-2"></i>
                        <small>The OTP code will expire in 10 minutes</small>
                    </div>

                    <button type="submit" class="btn btn-primary btn-lg btn-verify w-100 text-white mb-3">
                        <i class="bi bi-check-circle me-2"></i>Verify & Continue
                    </button>
                </form>

                <div class="text-center">
                    <p class="text-muted mb-2">Didn't receive the code?</p>
                    <form method="POST" action="{{ route('candidate.password.resend-otp') }}" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-link resend-link">
                            <i class="bi bi-arrow-clockwise me-1"></i>Resend OTP
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Auto-focus next input
        const inputs = document.querySelectorAll('.otp-input');
        inputs.forEach((input, index) => {
            input.addEventListener('input', (e) => {
                if (e.target.value.length === 1 && index < inputs.length - 1) {
                    inputs[index + 1].focus();
                }
            });

            input.addEventListener('keydown', (e) => {
                if (e.key === 'Backspace' && e.target.value === '' && index > 0) {
                    inputs[index - 1].focus();
                }
            });

            // Only allow numbers
            input.addEventListener('keypress', (e) => {
                if (!/[0-9]/.test(e.key)) {
                    e.preventDefault();
                }
            });
        });

        // Combine OTP values before submit
        document.getElementById('otpForm').addEventListener('submit', function (e) {
            const otp = Array.from(inputs).map(input => input.value).join('');
            document.getElementById('otpValue').value = otp;

            if (otp.length !== 6) {
                e.preventDefault();
                alert('Please enter all 6 digits');
            }
        });
    </script>
@endsection