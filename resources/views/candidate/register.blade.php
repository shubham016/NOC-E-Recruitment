@extends('layouts.guest')

@section('title', 'Candidate Registration')

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
        max-width: 900px;
        display: flex;
        flex-direction: column;
        border-radius: 4px;
        overflow: hidden;
        box-shadow: 0 8px 40px rgba(0,0,0,0.25), 0 2px 8px rgba(0,0,0,0.15);
        animation: cardIn 0.5s ease forwards;
    }

    @keyframes cardIn {
        from { opacity: 0; transform: translateY(20px); }
        to   { opacity: 1; transform: translateY(0); }
    }

    /* ─── Full-width Header Bar ─────────────────────── */
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

    /* ─── Card Body (logo + form side by side) ──────── */
    .noc-card-body {
        display: flex;
        background: #fff;
    }

    /* ─── Left Panel (Logo) ─────────────────────────── */
    .noc-left {
        width: 260px;
        flex-shrink: 0;
        background: #ffffff;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 3rem 2rem;
        position: relative;
    }

    .noc-left::after {
        content: '';
        position: absolute;
        right: 0;
        top: 0;
        bottom: 0;
        width: 4px;
        background: linear-gradient(180deg, #1a2a4a 0%, #c9a84c 50%, #1a7a6a 100%);
    }

    .noc-logo-circle {
        width: 170px;
        height: 170px;
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

    .noc-logo-circle .logo-fallback {
        font-size: 4rem;
        color: #1a2a4a;
    }

    /* ─── Right Panel (Form) ────────────────────────── */
    .noc-right {
        flex: 1;
        display: flex;
        flex-direction: column;
        background: #ffffff;
    }

    /* Form body */
    .noc-form-body {
        flex: 1;
        padding: 2rem 2.5rem;
    }

    /* Section subtitle */
    .noc-subtitle {
        font-size: 0.78rem;
        color: #888;
        text-transform: uppercase;
        letter-spacing: 1.5px;
        margin-bottom: 1.5rem;
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

    /* ─── Alerts ────────────────────────────────────── */
    .noc-alert {
        background: #fff5f5;
        border-left: 4px solid #c0392b;
        border-radius: 2px;
        padding: 0.75rem 1rem;
        margin-bottom: 1.25rem;
        font-size: 0.875rem;
        color: #c0392b;
        animation: slideIn 0.3s ease;
    }

    .noc-alert ul {
        margin: 0.4rem 0 0 1rem;
        padding: 0;
    }

    .noc-alert-success {
        background: #f0fdf4;
        border-left: 4px solid #16a34a;
        border-radius: 2px;
        padding: 0.75rem 1rem;
        margin-bottom: 1.25rem;
        font-size: 0.875rem;
        color: #15803d;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        animation: slideIn 0.3s ease;
    }

    @keyframes slideIn {
        from { opacity: 0; transform: translateX(-10px); }
        to   { opacity: 1; transform: translateX(0); }
    }

    /* ─── Form Grid ─────────────────────────────────── */
    .noc-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 0 1.25rem;
    }

    /* ─── Input groups ──────────────────────────────── */
    .noc-field {
        margin-bottom: 1.1rem;
    }

    .noc-field label {
        display: block;
        font-size: 0.8rem;
        font-weight: 600;
        color: #444;
        margin-bottom: 0.35rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .noc-field label .req {
        color: #c0392b;
        margin-left: 2px;
    }

    .noc-input-group {
        position: relative;
    }

    .noc-input-group input,
    .noc-input-group select {
        width: 100%;
        height: 42px;
        border: 1px solid #d0d0d0;
        border-radius: 2px;
        padding: 0 3rem 0 1rem;
        font-family: 'Open Sans', sans-serif;
        font-size: 0.875rem;
        color: #333;
        background: #f9f9f9;
        transition: border-color 0.2s, background 0.2s, box-shadow 0.2s;
        outline: none;
        appearance: none;
        -webkit-appearance: none;
    }

    .noc-input-group input::placeholder {
        color: #aaa;
        font-size: 0.82rem;
    }

    .noc-input-group input:focus,
    .noc-input-group select:focus {
        border-color: #1a2a4a;
        background: #fff;
        box-shadow: 0 0 0 3px rgba(26,42,74,0.08);
    }

    .noc-input-group input.is-invalid,
    .noc-input-group select.is-invalid {
        border-color: #c0392b;
    }

    .noc-input-icon {
        position: absolute;
        right: 1rem;
        top: 50%;
        transform: translateY(-50%);
        color: #aaa;
        font-size: 0.9rem;
        pointer-events: none;
        transition: color 0.2s;
    }

    .noc-input-group:focus-within .noc-input-icon {
        color: #1a2a4a;
    }

    .noc-invalid-feedback {
        font-size: 0.75rem;
        color: #c0392b;
        margin-top: 0.25rem;
        display: flex;
        align-items: center;
        gap: 0.3rem;
    }

    /* ─── Submit button ─────────────────────────────── */
    .noc-submit-row {
        display: flex;
        justify-content: flex-end;
        margin-top: 1.5rem;
    }

    .noc-register-btn {
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

    .noc-register-btn:hover {
        background: linear-gradient(135deg, #d4b55a 0%, #c9a84c 100%);
        transform: translateY(-1px);
        box-shadow: 0 6px 20px rgba(201,168,76,0.45);
    }

    .noc-register-btn:active {
        transform: translateY(0);
    }

    /* ─── Footer ────────────────────────────────────── */
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
    @media (max-width: 700px) {
        .noc-card-body {
            flex-direction: column;
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
            width: 120px;
            height: 120px;
        }

        .noc-row {
            grid-template-columns: 1fr;
        }

        .noc-form-body {
            padding: 1.5rem;
        }

        .noc-form-footer {
            padding: 1rem 1.5rem;
        }

        .noc-submit-row {
            justify-content: stretch;
        }

        .noc-register-btn {
            width: 100%;
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

        {{-- ── Full-width header bar ── --}}
        <div class="noc-form-header">
            <i class="bi bi-person-plus header-icon"></i>
            <h2>Candidate Registration</h2>
        </div>

        {{-- ── Card body: logo left, form right ── --}}
        <div class="noc-card-body">

            {{-- Left: Logo --}}
            <!-- <div class="noc-left">
                <div class="noc-logo-circle">
                    <img
                        src="{{ asset('images/images.png') }}"
                        alt="NOC Logo"
                        onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';"
                    >
                    <span class="logo-fallback" style="display:none;">
                        <i class="bi bi-building"></i>
                    </span>
                </div>
            </div> -->

            {{-- Right: Form --}}
            <div class="noc-right">
                <div class="noc-form-body">

                    <div class="noc-subtitle">Create Your Account</div>

                    {{-- Error alert --}}
                    @if ($errors->any())
                        <div class="noc-alert">
                            <div style="display:flex;align-items:center;gap:0.5rem;">
                                <i class="bi bi-exclamation-triangle-fill"></i>
                                <strong>Please fix the following errors:</strong>
                            </div>
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- Success alert --}}
                    @if (session('success'))
                        <div class="noc-alert-success">
                            <i class="bi bi-check-circle-fill"></i>
                            {{ session('success') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('candidate.register.post') }}" novalidate>
                        @csrf

                        {{-- Full Name --}}
                        <div class="noc-field">
                            <label for="name">Full Name <span class="req">*</span></label>
                            <div class="noc-input-group">
                                <input
                                    type="text"
                                    id="name"
                                    name="name"
                                    value="{{ old('name') }}"
                                    placeholder="Enter your full name"
                                    class="{{ $errors->has('name') ? 'is-invalid' : '' }}"
                                    required
                                    autofocus
                                >
                                <span class="noc-input-icon"><i class="bi bi-person"></i></span>
                                @error('name')
                                    <div class="noc-invalid-feedback"><i class="bi bi-x-circle"></i> {{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- Email --}}
                        <div class="noc-field">
                            <label for="email">Email Address <span class="req">*</span></label>
                            <div class="noc-input-group">
                                <input
                                    type="email"
                                    id="email"
                                    name="email"
                                    value="{{ old('email') }}"
                                    placeholder="Enter your email address"
                                    class="{{ $errors->has('email') ? 'is-invalid' : '' }}"
                                    required
                                >
                                <span class="noc-input-icon"><i class="bi bi-envelope"></i></span>
                                @error('email')
                                    <div class="noc-invalid-feedback"><i class="bi bi-x-circle"></i> {{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <!-- Phone -->
                        <div class="noc-field">
                            <label for="phone">Phone Number <span class="req">*</span></label>
                            <div class="noc-input-group">
                                <input
                                    type="tel"
                                    id="phone"
                                    name="phone"
                                    value="{{ old('phone') }}"
                                    placeholder="Enter your Mobile Number"
                                    class="{{ $errors->has('phone') ? 'is-invalid' : '' }}"
                                    required
                                >
                                <span class="noc-input-icon"><i class="bi bi-telephone"></i></span>
                                @error('phone')
                                    <div class="noc-invalid-feedback"><i class="bi bi-x-circle"></i> {{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- Gender, DOB & NOC Employee (3 columns) --}}
                        <div class="noc-row" style="grid-template-columns: 1fr 1fr 1fr;">
                            <div class="noc-field">
                                <label for="gender">Gender <span class="req">*</span></label>
                                <div class="noc-input-group">
                                    <select
                                        id="gender"
                                        name="gender"
                                        class="{{ $errors->has('gender') ? 'is-invalid' : '' }}"
                                        required
                                    >
                                        <option value="">Select Gender</option>
                                        <option value="Male"   {{ old('gender') == 'Male'   ? 'selected' : '' }}>Male</option>
                                        <option value="Female" {{ old('gender') == 'Female' ? 'selected' : '' }}>Female</option>
                                        <option value="Other"  {{ old('gender') == 'Other'  ? 'selected' : '' }}>Other</option>
                                    </select>
                                    <span class="noc-input-icon"><i class="bi bi-gender-ambiguous"></i></span>
                                    @error('gender')
                                        <div class="noc-invalid-feedback"><i class="bi bi-x-circle"></i> {{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="noc-field">
                                <label for="date_of_birth_bs">Date of Birth (BS) <span class="req">*</span></label>
                                <div class="noc-input-group">
                                    <input
                                        type="text"
                                        id="date_of_birth_bs"
                                        name="date_of_birth_bs"
                                        value="{{ old('date_of_birth_bs') }}"
                                        placeholder="YYYY-MM-DD"
                                        class="{{ $errors->has('date_of_birth_bs') ? 'is-invalid' : '' }}"
                                        required
                                    >
                                    <span class="noc-input-icon"><i class="bi bi-calendar-event"></i></span>
                                    @error('date_of_birth_bs')
                                        <div class="noc-invalid-feedback"><i class="bi bi-x-circle"></i> {{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="noc-field">
                                <label for="noc_employee">NOC Employee <span class="req">*</span></label>
                                <div class="noc-input-group">
                                    <select
                                        id="noc_employee"
                                        name="noc_employee"
                                        class="{{ $errors->has('noc_employee') ? 'is-invalid' : '' }}"
                                        required
                                    >
                                        <option value="">Select</option>
                                        <option value="yes" {{ old('noc_employee') == 'yes' ? 'selected' : '' }}>Yes</option>
                                        <option value="no"  {{ old('noc_employee') == 'no'  ? 'selected' : '' }}>No</option>
                                    </select>
                                    <span class="noc-input-icon"><i class="bi bi-person-badge"></i></span>
                                    @error('noc_employee')
                                        <div class="noc-invalid-feedback"><i class="bi bi-x-circle"></i> {{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Citizenship Number & NID --}}
                        <div class="noc-row">
                            <div class="noc-field">
                                <label for="citizenship_number">Citizenship Number <span class="req">*</span></label>
                                <div class="noc-input-group">
                                    <input
                                        type="text"
                                        id="citizenship_number"
                                        name="citizenship_number"
                                        value="{{ old('citizenship_number') }}"
                                        placeholder="Enter citizenship number"
                                        class="{{ $errors->has('citizenship_number') ? 'is-invalid' : '' }}"
                                        required
                                    >
                                    <span class="noc-input-icon"><i class="bi bi-card-text"></i></span>
                                    @error('citizenship_number')
                                        <div class="noc-invalid-feedback"><i class="bi bi-x-circle"></i> {{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="noc-field">
                                <label for="nid">National ID (NID)</label>
                                <div class="noc-input-group">
                                    <input
                                        type="text"
                                        id="nid"
                                        name="nid"
                                        value="{{ old('nid') }}"
                                        placeholder="Enter national ID number"
                                        class="{{ $errors->has('nid') ? 'is-invalid' : '' }}"
                                    >
                                    <span class="noc-input-icon"><i class="bi bi-card-text"></i></span>
                                    @error('nid')
                                        <div class="noc-invalid-feedback"><i class="bi bi-x-circle"></i> {{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Issue District & Issue Date --}}
                        <div class="noc-row">
                            <div class="noc-field">
                                <label for="citizenship_issue_distric">Issue District <span class="req">*</span></label>
                                <div class="noc-input-group">
                                    <input
                                        type="text"
                                        id="citizenship_issue_distric"
                                        name="citizenship_issue_distric"
                                        value="{{ old('citizenship_issue_distric') }}"
                                        placeholder="District name"
                                        class="{{ $errors->has('citizenship_issue_distric') ? 'is-invalid' : '' }}"
                                        required
                                    >
                                    <span class="noc-input-icon"><i class="bi bi-geo-alt"></i></span>
                                    @error('citizenship_issue_distric')
                                        <div class="noc-invalid-feedback"><i class="bi bi-x-circle"></i> {{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="noc-field">
                                <label for="citizenship_issue_date_bs">Issue Date (BS) <span class="req">*</span></label>
                                <div class="noc-input-group">
                                    <input
                                        type="text"
                                        id="citizenship_issue_date_bs"
                                        name="citizenship_issue_date_bs"
                                        value="{{ old('citizenship_issue_date_bs') }}"
                                        placeholder="YYYY-MM-DD"
                                        class="{{ $errors->has('citizenship_issue_date_bs') ? 'is-invalid' : '' }}"
                                        required
                                    >
                                    <span class="noc-input-icon"><i class="bi bi-calendar-check"></i></span>
                                    @error('citizenship_issue_date_bs')
                                        <div class="noc-invalid-feedback"><i class="bi bi-x-circle"></i> {{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Password & Confirm Password --}}
                        <div class="noc-row">
                            <div class="noc-field">
                                <label for="password">Password <span class="req">*</span></label>
                                <div class="noc-input-group">
                                    <input
                                        type="password"
                                        id="password"
                                        name="password"
                                        placeholder="Min 8 characters"
                                        class="{{ $errors->has('password') ? 'is-invalid' : '' }}"
                                        required
                                        minlength="8"
                                        autocomplete="new-password"
                                    >
                                    <span class="noc-input-icon"><i class="bi bi-lock"></i></span>
                                    @error('password')
                                        <div class="noc-invalid-feedback"><i class="bi bi-x-circle"></i> {{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="noc-field">
                                <label for="password_confirmation">Confirm Password <span class="req">*</span></label>
                                <div class="noc-input-group">
                                    <input
                                        type="password"
                                        id="password_confirmation"
                                        name="password_confirmation"
                                        placeholder="Re-enter password"
                                        required
                                        minlength="8"
                                        autocomplete="new-password"
                                    >
                                    <span class="noc-input-icon"><i class="bi bi-lock-fill"></i></span>
                                </div>
                            </div>
                        </div>

                        {{-- Submit --}}
                        <div class="noc-submit-row">
                            <button type="submit" class="noc-register-btn">
                                <i class="bi bi-person-plus me-1"></i> CREATE ACCOUNT
                            </button>
                        </div>

                    </form>
                </div>

                {{-- Footer --}}
                <div class="noc-form-footer">
                    <span>Already have an account?</span>
                    <a href="{{ route('candidate.login') }}">
                        <i class="bi bi-box-arrow-in-right"></i> Login here
                    </a>
                </div>

            </div>
        </div>
    </div>

</div>
@endsection