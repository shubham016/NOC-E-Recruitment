@extends('layouts.guest')

@section('title', 'Verify Email - OTP')

@section('custom-styles')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Rajdhani:wght@400;500;600;700&family=Open+Sans:wght@300;400;600&display=swap');

    * { margin: 0; padding: 0; box-sizing: border-box; }

    body {
        font-family: 'Open Sans', sans-serif;
        background: #e8e8e8;
        min-height: 100vh;
    }

    .noc-login-page {
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 2rem 1rem;
        background: #dcdcdc url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23b0b0b0' fill-opacity='0.15'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
    }

    /* ── Card ── */
    .noc-card {
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 8px 40px rgba(0,0,0,0.13);
        overflow: hidden;
        width: 100%;
        max-width: 480px;
    }

    /* ── Header bar (matches register page sidebar gradient) ── */
    .noc-card-header {
        background: linear-gradient(90deg, #1a1a1a 0%, #2d2d2d 60%, #c9a84c 100%);
        padding: 2rem;
        text-align: center;
        color: #fff;
    }

    .noc-card-header .verify-icon {
        width: 70px;
        height: 70px;
        background: rgba(201,168,76,0.25);
        border: 2px solid #c9a84c;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1rem;
        font-size: 32px;
        color: #c9a84c;
    }

    .noc-card-header h2 {
        font-family: 'Rajdhani', sans-serif;
        font-size: 1.6rem;
        font-weight: 700;
        margin-bottom: 0.25rem;
        color: #fff;
    }

    .noc-card-header p {
        font-size: 0.875rem;
        color: rgba(255,255,255,0.8);
        margin: 0;
    }

    .noc-card-header .email-badge {
        display: inline-block;
        margin-top: 0.4rem;
        background: rgba(201,168,76,0.2);
        border: 1px solid rgba(201,168,76,0.5);
        color: #c9a84c;
        font-weight: 600;
        font-size: 0.85rem;
        padding: 0.2rem 0.75rem;
        border-radius: 20px;
    }

    /* ── Body ── */
    .noc-card-body { padding: 2rem; }

    /* ── Alerts ── */
    .alert-success {
        background: #f0fdf4;
        border-left: 4px solid #16a34a;
        color: #15803d;
        padding: 0.75rem 1rem;
        border-radius: 6px;
        font-size: 0.875rem;
        margin-bottom: 1rem;
    }

    .alert-danger {
        background: #fff5f5;
        border-left: 4px solid #c0392b;
        color: #c0392b;
        padding: 0.75rem 1rem;
        border-radius: 6px;
        font-size: 0.875rem;
        margin-bottom: 1rem;
    }

    /* ── OTP inputs ── */
    .otp-label {
        display: block;
        text-align: center;
        font-size: 0.875rem;
        font-weight: 600;
        color: #1a2a4a;
        margin-bottom: 1rem;
    }

    .otp-container {
        display: flex;
        justify-content: center;
        gap: 10px;
        margin-bottom: 1.25rem;
    }

    .otp-input {
        width: 56px;
        height: 56px;
        font-size: 22px;
        font-weight: 700;
        text-align: center;
        border: 2px solid #d0d0d0;
        border-radius: 8px;
        color: #1a2a4a;
        background: #f9f9f9;
        transition: border-color 0.2s, box-shadow 0.2s;
        outline: none;
    }

    .otp-input:focus {
        border-color: #1a2a4a;
        box-shadow: 0 0 0 3px rgba(201,168,76,0.2);
        background: #fff;
    }

    /* ── Expiry note ── */
    .otp-expiry {
        text-align: center;
        font-size: 0.8rem;
        color: #888;
        margin-bottom: 1.5rem;
    }

    .otp-expiry i { color: #c9a84c; }

    /* ── Submit button ── */
    .noc-verify-btn {
        width: 100%;
        padding: 0.75rem;
        background: linear-gradient(135deg, #c9a84c 0%, #b8941f 100%);
        border: none;
        border-radius: 8px;
        color: #fff;
        font-family: 'Rajdhani', sans-serif;
        font-size: 1rem;
        font-weight: 700;
        letter-spacing: 0.5px;
        cursor: pointer;
        transition: background 0.2s, transform 0.15s, box-shadow 0.2s;
        margin-bottom: 1rem;
    }

    .noc-verify-btn:hover {
        background: linear-gradient(135deg, #d4b55a 0%, #c9a84c 100%);
        transform: translateY(-1px);
        box-shadow: 0 6px 16px rgba(201,168,76,0.35);
    }

    /* ── Resend & back links ── */
    .noc-links {
        text-align: center;
        font-size: 0.875rem;
        color: #888;
        margin-top: 0.5rem;
    }

    .noc-links .resend-btn {
        background: none;
        border: none;
        padding: 0;
        color: #c9a84c;
        font-weight: 600;
        cursor: pointer;
        text-decoration: underline;
        font-size: 0.875rem;
    }

    .noc-links .resend-btn:hover { color: #b8941f; }

    .noc-back-link {
        display: block;
        text-align: center;
        margin-top: 1rem;
        color: #888;
        font-size: 0.8rem;
        text-decoration: none;
    }

    .noc-back-link:hover { color: #1a2a4a; }

    @media (max-width: 480px) {
        .otp-input { width: 44px; height: 44px; font-size: 18px; }
        .noc-card-body { padding: 1.5rem; }
    }
</style>
@endsection

@section('content')
<div class="noc-login-page">
    <div class="noc-card">

        {{-- Header --}}
        <div class="noc-card-header">
            <div class="verify-icon">
                <i class="bi bi-envelope-check"></i>
            </div>
            <h2>Verify Your Email</h2>
            <p>We've sent a 6-digit code to</p>
            <span class="email-badge">{{ $email }}</span>
        </div>

        {{-- Body --}}
        <div class="noc-card-body">

            @if (session('success'))
                <div class="alert-success">
                    <i class="bi bi-check-circle me-1"></i>{{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="alert-danger">
                    <i class="bi bi-exclamation-circle me-1"></i>{{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('candidate.verify.otp.post') }}" id="otpForm">
                @csrf

                <label class="otp-label">Enter 6-Digit OTP Code</label>

                <div class="otp-container">
                    <input type="text" class="otp-input" maxlength="1" id="otp1" inputmode="numeric" autofocus>
                    <input type="text" class="otp-input" maxlength="1" id="otp2" inputmode="numeric">
                    <input type="text" class="otp-input" maxlength="1" id="otp3" inputmode="numeric">
                    <input type="text" class="otp-input" maxlength="1" id="otp4" inputmode="numeric">
                    <input type="text" class="otp-input" maxlength="1" id="otp5" inputmode="numeric">
                    <input type="text" class="otp-input" maxlength="1" id="otp6" inputmode="numeric">
                </div>
                <input type="hidden" name="otp" id="otpValue">

                <p class="otp-expiry">
                    <i class="bi bi-clock me-1"></i>Code expires in 10 minutes
                </p>

                <button type="submit" class="noc-verify-btn">
                    <i class="bi bi-check-circle me-2"></i>Verify Email
                </button>
            </form>

            <div class="noc-links">
                Didn't receive the code?
                <form method="POST" action="{{ route('candidate.resend.otp') }}" style="display:inline">
                    @csrf
                    <button type="submit" class="resend-btn">
                        <i class="bi bi-arrow-clockwise me-1"></i>Resend OTP
                    </button>
                </form>
            </div>

            <a href="{{ route('candidate.login') }}" class="noc-back-link">
                <i class="bi bi-arrow-left me-1"></i>Back to Login
            </a>
        </div>
    </div>
</div>

<script>
    const inputs = document.querySelectorAll('.otp-input');

    inputs.forEach((input, index) => {
        input.addEventListener('input', (e) => {
            e.target.value = e.target.value.replace(/[^0-9]/g, '');
            if (e.target.value.length === 1 && index < inputs.length - 1) {
                inputs[index + 1].focus();
            }
        });
        input.addEventListener('keydown', (e) => {
            if (e.key === 'Backspace' && e.target.value === '' && index > 0) {
                inputs[index - 1].focus();
            }
        });
    });

    document.getElementById('otpForm').addEventListener('submit', function (e) {
        const otp = Array.from(inputs).map(i => i.value).join('');
        document.getElementById('otpValue').value = otp;
        if (otp.length !== 6) {
            e.preventDefault();
            alert('Please enter all 6 digits.');
        }
    });
</script>
@endsection
