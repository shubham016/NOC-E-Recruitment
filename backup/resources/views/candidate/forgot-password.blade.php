@extends('layouts.guest')

@section('title', 'Forgot Password')

@section('custom-styles')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Rajdhani:wght@400;500;600;700&family=Open+Sans:wght@300;400;600&display=swap');

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: 'Open Sans', sans-serif;
        background: #e8e8e8;
        min-height: 100vh;
    }

    /* ─── Page Wrapper ─────────────────────────────── */
    .noc-login-page {
        min-height: 100vh;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        background: #dcdcdc url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23b0b0b0' fill-opacity='0.15'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        padding: 2rem 1rem;
        position: relative;
    }

    /* ─── Brand Logo at Top ─────────────────────────── */
    .noc-brand {
        text-align: center;
        margin-bottom: 1.5rem;
    }

    .noc-brand-text {
        font-family: 'Rajdhani', sans-serif;
        font-size: 2.6rem;
        font-weight: 700;
        letter-spacing: 2px;
        line-height: 1;
    }

    .noc-brand-text .brand-noc  { color: #1a2a4a; }
    .noc-brand-text .brand-dot  { color: #c0392b; font-size: 2rem; }
    .noc-brand-text .brand-hris { color: #1a2a4a; font-size: 1.6rem; font-weight: 500; }

    /* ─── Card ──────────────────────────────────────── */
    .noc-card {
        width: 100%;
        max-width: 500px;
        display: flex;
        border-radius: 4px;
        overflow: hidden;
        box-shadow: 0 8px 40px rgba(0,0,0,0.25), 0 2px 8px rgba(0,0,0,0.15);
        animation: cardIn 0.5s ease forwards;
    }

    @keyframes cardIn {
        from { opacity: 0; transform: translateY(20px); }
        to   { opacity: 1; transform: translateY(0); }
    }

    /* ─── Left Panel ────────────────────────────────── */
    .noc-left {
        width: 300px;
        flex-shrink: 0;
        background: #ffffff;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 3rem 2rem;
        position: relative;
        gap: 1.5rem;
    }

    .noc-left::after {
        content: '';
        position: absolute;
        right: 0; top: 0; bottom: 0;
        width: 4px;
        background: linear-gradient(180deg, #1a2a4a 0%, #c9a84c 50%, #1a7a6a 100%);
    }

    .noc-logo-circle {
        width: 160px;
        height: 160px;
        border-radius: 50%;
        border: 6px solid #1a2a4a;
        outline: 3px solid #c9a84c;
        outline-offset: 4px;
        overflow: hidden;
        background: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 8px 30px rgba(0,0,0,0.2);
    }

    .noc-logo-circle img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .noc-left-info {
        text-align: center;
    }

    .noc-left-info p {
        font-size: 0.78rem;
        color: #888;
        line-height: 1.6;
        letter-spacing: 0.3px;
    }

    .noc-left-info .lock-icon {
        font-size: 2rem;
        color: #c9a84c;
        margin-bottom: 0.5rem;
        display: block;
    }

    /* ─── Right Panel ───────────────────────────────── */
    .noc-right {
        flex: 1;
        display: flex;
        flex-direction: column;
        background: #ffffff;
    }

    /* Header bar */
    .noc-form-header {
        background: linear-gradient(90deg, #1a1a1a 0%, #2d2d2d 60%, #c9a84c 100%);
        padding: 1.2rem 2rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .noc-form-header h2 {
        font-family: 'Rajdhani', sans-serif;
        font-size: 1.35rem;
        font-weight: 700;
        color: #ffffff;
        letter-spacing: 3px;
        text-transform: uppercase;
        margin: 0;
    }

    .noc-form-header .header-icon {
        color: #c9a84c;
        font-size: 1.2rem;
    }

    /* Form body */
    .noc-form-body {
        flex: 1;
        padding: 2rem 2.5rem 2rem;
    }

    /* Subtitle */
    .noc-subtitle {
        font-size: 0.78rem;
        color: #888;
        text-transform: uppercase;
        letter-spacing: 1.5px;
        margin-bottom: 1.75rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .noc-subtitle::after {
        content: '';
        flex: 1;
        height: 1px;
        background: #e0e0e0;
    }

    /* Description text */
    .noc-description {
        font-size: 0.855rem;
        color: #666;
        line-height: 1.65;
        margin-bottom: 1.75rem;
        padding: 0.85rem 1rem;
        background: #f7f9fc;
        border-left: 3px solid #1a2a4a;
        border-radius: 0 2px 2px 0;
    }

    /* Alert */
    .noc-alert {
        background: #fff5f5;
        border-left: 4px solid #c0392b;
        border-radius: 2px;
        padding: 0.75rem 1rem;
        margin-bottom: 1.5rem;
        font-size: 0.875rem;
        color: #c0392b;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        animation: slideIn 0.3s ease;
    }

    /* Session / success message */
    .noc-session-msg {
        background: #f0faf4;
        border-left: 4px solid #27ae60;
        border-radius: 2px;
        padding: 0.85rem 1rem;
        margin-bottom: 1.5rem;
        font-size: 0.875rem;
        color: #1e8449;
        display: flex;
        align-items: flex-start;
        gap: 0.5rem;
        animation: slideIn 0.3s ease;
    }

    .noc-session-msg i {
        margin-top: 2px;
        flex-shrink: 0;
    }

    @keyframes slideIn {
        from { opacity: 0; transform: translateX(-10px); }
        to   { opacity: 1; transform: translateX(0); }
    }

    /* Input groups */
    .noc-input-group {
        position: relative;
        margin-bottom: 1.25rem;
    }

    .noc-input-group input {
        width: 100%;
        height: 46px;
        border: 1px solid #d0d0d0;
        border-radius: 2px;
        padding: 0 3rem 0 1rem;
        font-family: 'Open Sans', sans-serif;
        font-size: 0.9rem;
        color: #333;
        background: #f9f9f9;
        transition: border-color 0.2s, background 0.2s, box-shadow 0.2s;
        outline: none;
    }

    .noc-input-group input::placeholder {
        color: #aaa;
        font-size: 0.875rem;
    }

    .noc-input-group input:focus {
        border-color: #1a2a4a;
        background: #fff;
        box-shadow: 0 0 0 3px rgba(26,42,74,0.08);
    }

    .noc-input-group input.is-invalid {
        border-color: #c0392b;
    }

    .noc-input-icon {
        position: absolute;
        right: 1rem;
        top: 50%;
        transform: translateY(-50%);
        color: #aaa;
        font-size: 1rem;
        pointer-events: none;
        transition: color 0.2s;
    }

    .noc-input-group:focus-within .noc-input-icon {
        color: #1a2a4a;
    }

    .noc-invalid-feedback {
        font-size: 0.78rem;
        color: #c0392b;
        margin-top: 0.3rem;
        display: flex;
        align-items: center;
        gap: 0.3rem;
    }

    /* Actions row */
    .noc-form-actions {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        margin-top: 0.5rem;
    }

    .noc-back-link {
        display: flex;
        align-items: center;
        gap: 0.4rem;
        font-size: 0.82rem;
        color: #1a6da8;
        text-decoration: none;
        font-weight: 600;
        transition: color 0.2s;
    }

    .noc-back-link:hover {
        color: #1a2a4a;
        text-decoration: underline;
    }

    .noc-login-btn {
        background: linear-gradient(135deg, #c9a84c 0%, #b8941f 100%);
        color: #fff;
        border: none;
        height: 42px;
        padding: 0 2.5rem;
        font-family: 'Rajdhani', sans-serif;
        font-size: 1rem;
        font-weight: 700;
        letter-spacing: 2px;
        text-transform: uppercase;
        border-radius: 2px;
        cursor: pointer;
        transition: background 0.2s, transform 0.15s, box-shadow 0.2s;
        box-shadow: 0 3px 12px rgba(201,168,76,0.35);
    }

    .noc-login-btn:hover {
        background: linear-gradient(135deg, #d4b55a 0%, #c9a84c 100%);
        transform: translateY(-1px);
        box-shadow: 0 6px 20px rgba(201,168,76,0.45);
    }

    .noc-login-btn:active {
        transform: translateY(0);
    }

    /* Footer */
    .noc-form-footer {
        border-top: 1px solid #f0f0f0;
        padding: 1rem 2.5rem;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        font-size: 0.82rem;
        color: #888;
        background: #fafafa;
    }

    .noc-form-footer a {
        color: #1a2a4a;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
        transition: color 0.2s;
    }

    .noc-form-footer a:hover {
        color: #c9a84c;
        text-decoration: underline;
    }

    /* ─── Responsive ────────────────────────────────── */
    @media (max-width: 650px) {
        .noc-card {
            flex-direction: column;
            max-width: 420px;
        }

        .noc-left {
            width: 100%;
            padding: 2rem;
        }

        .noc-left::after {
            width: 100%;
            height: 4px;
            top: auto;
            right: 0;
            left: 0;
            bottom: 0;
        }

        .noc-logo-circle {
            width: 110px;
            height: 110px;
        }

        .noc-form-body {
            padding: 1.5rem 1.5rem 1rem;
        }

        .noc-form-footer {
            padding: 1rem 1.5rem;
        }

        .noc-form-actions {
            flex-direction: column-reverse;
            align-items: stretch;
        }

        .noc-login-btn {
            width: 100%;
            text-align: center;
        }
    }
</style>
@endsection

@section('content')
<div class="noc-login-page">

    {{-- Brand --}}
    <div class="noc-brand">
        <div class="noc-brand-text">
           <img
                    src="{{ asset('images/images.png') }}"
                    alt="NOC Logo"
                    style="width: 80px; height: auto;"
                    onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';"
                >
            <span class="brand-noc">NOC</span><span class="brand-dot">•</span><span class="brand-hris">E-Recruitment</span>
        </div>
    </div>

    <div class="noc-card">

        {{-- ── Right: Form ── --}}
        <div class="noc-right">

            {{-- Header bar --}}
            <div class="noc-form-header">
                <i class="bi bi-key header-icon"></i>
                <h2>Forgot Password</h2>
            </div>

            {{-- Form body --}}
            <div class="noc-form-body">

                <div class="noc-subtitle">Account Recovery</div>

                {{-- Success message --}}
                @if (session('status'))
                    <div class="noc-session-msg">
                        <i class="bi bi-check-circle-fill"></i>
                        <span>{{ session('status') }}<br>
                            <small style="opacity:0.85;">Please check your inbox and click the reset link. The link will expire in <strong>60 minutes</strong>.</small>
                        </span>
                    </div>
                @endif

                {{-- Error alert --}}
                @if ($errors->any())
                    <div class="noc-alert">
                        <i class="bi bi-exclamation-triangle-fill"></i>
                        <strong>Error!</strong>&nbsp;{{ $errors->first() }}
                    </div>
                @endif

                {{-- Only show form if no success yet --}}
                @if (!session('status'))
                    <div class="noc-description">
                        <i class="bi bi-info-circle" style="color:#1a2a4a; margin-right:0.3rem;"></i>
                        Enter the email address associated with your candidate account. A password reset link will be sent to your inbox.
                    </div>

                    <form method="POST" action="{{ route('candidate.forgot.password.post') }}" novalidate>
                        @csrf

                        {{-- Email --}}
                        <div class="noc-input-group">
                            <input
                                type="email"
                                name="email"
                                value="{{ old('email') }}"
                                placeholder="Registered Email Address"
                                class="{{ $errors->has('email') ? 'is-invalid' : '' }}"
                                required
                                autofocus
                                autocomplete="email"
                            >
                            <span class="noc-input-icon"><i class="bi bi-envelope"></i></span>
                            @error('email')
                                <div class="noc-invalid-feedback">
                                    <i class="bi bi-x-circle"></i> {{ $message }}
                                </div>
                            @enderror
                        </div>

                        {{-- Actions --}}
                        <div class="noc-form-actions">
                            <a href="{{ route('candidate.login') }}" class="noc-back-link">
                                <i class="bi bi-arrow-left"></i> Back to Login
                            </a>
                            <button type="submit" class="noc-login-btn">
                                SEND LINK
                            </button>
                        </div>

                    </form>
                @else
                    {{-- After success: just show back to login button --}}
                    <div class="noc-form-actions" style="justify-content:center; margin-top:1rem;">
                        <a href="{{ route('candidate.login') }}" class="noc-login-btn" style="text-decoration:none; display:inline-flex; align-items:center; gap:0.5rem;">
                            <i class="bi bi-arrow-left"></i> BACK TO LOGIN
                        </a>
                    </div>
                @endif

            </div>

            {{-- Footer --}}
            <div class="noc-form-footer">
                <span>Remembered your password?</span>
                <a href="{{ route('candidate.login') }}">
                    <i class="bi bi-box-arrow-in-right"></i> Login here
                </a>
            </div>

        </div>
    </div>

</div>
@endsection