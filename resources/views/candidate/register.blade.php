@extends('layouts.guest')

@section('title', 'Candidate Registration')

@section('custom-styles')
<link rel="stylesheet" href="https://nepalidatepicker.sajanmaharjan.com.np/v5/nepali.datepicker/css/nepali.datepicker.v5.0.6.min.css">

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
        flex-direction: column;
        align-items: center;
        justify-content: center;
        background: #dcdcdc url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23b0b0b0' fill-opacity='0.15'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        padding: 1.25rem 1rem;
    }

    /* Brand */
    .noc-brand {
        text-align: center;
        margin-bottom: 1rem;
    }

    .noc-brand-text {
        font-family: 'Rajdhani', sans-serif;
        font-size: 2rem;
        font-weight: 700;
        letter-spacing: 2px;
        line-height: 1;
    }

    .noc-brand-text .brand-noc  { color: #1a2a4a; }
    .noc-brand-text .brand-dot  { color: #c0392b; font-size: 1.6rem; }
    .noc-brand-text .brand-hris { color: #1a2a4a; font-size: 1.2rem; font-weight: 500; }

    /* Card */
    .noc-card {
        width: 100%;
        max-width: 860px;
        border-radius: 4px;
        overflow: hidden;
        box-shadow: 0 6px 28px rgba(0,0,0,0.2), 0 2px 6px rgba(0,0,0,0.12);
        animation: cardIn 0.4s ease forwards;
    }

    @keyframes cardIn {
        from { opacity: 0; transform: translateY(16px); }
        to   { opacity: 1; transform: translateY(0); }
    }

    /* Header */
    .noc-form-header {
        background: linear-gradient(90deg, #1a1a1a 0%, #2d2d2d 60%, #c9a84c 100%);
        padding: 0.7rem 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.6rem;
    }

    .noc-form-header h2 {
        font-family: 'Rajdhani', sans-serif;
        font-size: 1.1rem;
        font-weight: 700;
        color: #ffffff;
        letter-spacing: 3px;
        text-transform: uppercase;
        margin: 0;
    }

    .noc-form-header .header-icon { color: #c9a84c; font-size: 1rem; }

    /* Card body */
    .noc-card-body { background: #fff; }

    .noc-right {
        display: flex;
        flex-direction: column;
        background: #ffffff;
    }

    .noc-form-body {
        padding: 1.25rem 1.75rem 0.5rem;
    }

    /* Subtitle */
    .noc-subtitle {
        font-size: 0.7rem;
        color: #999;
        text-transform: uppercase;
        letter-spacing: 1.5px;
        margin-bottom: 0.9rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .noc-subtitle::after {
        content: '';
        flex: 1;
        height: 1px;
        background: #e8e8e8;
    }

    /* Alerts */
    .noc-alert {
        background: #fff5f5;
        border-left: 4px solid #c0392b;
        border-radius: 2px;
        padding: 0.6rem 0.9rem;
        margin-bottom: 0.9rem;
        font-size: 0.82rem;
        color: #c0392b;
    }

    .noc-alert ul { margin: 0.3rem 0 0 1rem; padding: 0; }

    .noc-alert-success {
        background: #f0fdf4;
        border-left: 4px solid #16a34a;
        border-radius: 2px;
        padding: 0.6rem 0.9rem;
        margin-bottom: 0.9rem;
        font-size: 0.82rem;
        color: #15803d;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    /* Grids */
    .noc-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 0 1rem;
    }

    .noc-row3 {
        display: grid;
        grid-template-columns: 1fr 1fr 1fr;
        gap: 0 1rem;
    }

    /* Fields */
    .noc-field { margin-bottom: 0.75rem; }

    .noc-field label {
        display: block;
        font-size: 0.72rem;
        font-weight: 600;
        color: #555;
        margin-bottom: 0.25rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .noc-field label .req { color: #c0392b; margin-left: 2px; }

    .noc-input-group { position: relative; }

    .noc-input-group input,
    .noc-input-group select {
        width: 100%;
        height: 36px;
        border: 1px solid #d4d4d4;
        border-radius: 2px;
        padding: 0 2.25rem 0 0.75rem;
        font-family: 'Open Sans', sans-serif;
        font-size: 0.8rem;
        color: #333;
        background: #f9f9f9;
        transition: border-color 0.2s, background 0.2s, box-shadow 0.2s;
        outline: none;
        appearance: none;
        -webkit-appearance: none;
    }

    .noc-input-group input::placeholder {
        color: #bbb;
        font-size: 0.77rem;
    }

    .noc-input-group input:focus,
    .noc-input-group select:focus {
        border-color: #1a2a4a;
        background: #fff;
        box-shadow: 0 0 0 2px rgba(26,42,74,0.07);
    }

    .noc-input-group input.is-invalid,
    .noc-input-group select.is-invalid { border-color: #c0392b; }

    .noc-input-icon {
        position: absolute;
        right: 0.65rem;
        top: 50%;
        transform: translateY(-50%);
        color: #bbb;
        font-size: 0.82rem;
        pointer-events: none;
        transition: color 0.2s;
        z-index: 2;
    }

    .noc-input-group:focus-within .noc-input-icon { color: #1a2a4a; }

    .noc-invalid-feedback {
        font-size: 0.72rem;
        color: #c0392b;
        margin-top: 0.2rem;
        display: flex;
        align-items: center;
        gap: 0.3rem;
    }

    /* Submit */
    .noc-submit-row {
        display: flex;
        justify-content: flex-end;
        margin-top: 0.75rem;
    }

    .noc-register-btn {
        background: linear-gradient(135deg, #c9a84c 0%, #b8941f 100%);
        color: #fff;
        border: none;
        height: 36px;
        padding: 0 2rem;
        font-family: 'Rajdhani', sans-serif;
        font-size: 0.95rem;
        font-weight: 700;
        letter-spacing: 2px;
        text-transform: uppercase;
        border-radius: 2px;
        cursor: pointer;
        transition: background 0.2s, transform 0.15s, box-shadow 0.2s;
        box-shadow: 0 3px 10px rgba(201,168,76,0.3);
    }

    .noc-register-btn:hover {
        background: linear-gradient(135deg, #d4b55a 0%, #c9a84c 100%);
        transform: translateY(-1px);
        box-shadow: 0 5px 16px rgba(201,168,76,0.4);
    }

    .noc-register-btn:active { transform: translateY(0); }

    /* Footer */
    .noc-form-footer {
        border-top: 1px solid #f0f0f0;
        padding: 0.65rem 1.75rem;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        font-size: 0.78rem;
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

    .noc-form-footer a:hover { color: #c9a84c; text-decoration: underline; }

    /* Responsive */
    @media (max-width: 700px) {
        .noc-row, .noc-row3 { grid-template-columns: 1fr; }
        .noc-form-body { padding: 1rem 1.25rem 0.5rem; }
        .noc-form-footer { padding: 0.65rem 1.25rem; }
        .noc-submit-row { justify-content: stretch; }
        .noc-register-btn { width: 100%; }
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
                style="width: 60px; height: auto; vertical-align: middle; margin-right: 0.4rem;"
                onerror="this.style.display='none';"
            >
            <span class="brand-noc">NOC</span><span class="brand-dot">•</span><span class="brand-hris">E-Recruitment</span>
        </div>
    </div>

    <div class="noc-card">

        {{-- Header --}}
        <div class="noc-form-header">
            <i class="bi bi-person-plus header-icon"></i>
            <h2>Candidate Registration</h2>
        </div>

        <div class="noc-card-body">
            <div class="noc-right">
                <div class="noc-form-body">

                    <div class="noc-subtitle">Create Your Account</div>

                    {{-- Errors --}}
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

                    {{-- Success --}}
                    @if (session('success'))
                        <div class="noc-alert-success">
                            <i class="bi bi-check-circle-fill"></i>
                            {{ session('success') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('candidate.register.post') }}">
                        @csrf

                        {{-- Name / Email / Phone (3 columns) --}}
                        <div class="noc-row3">
                            <div class="noc-field">
                                <label for="name">Full Name <span class="req">*</span></label>
                                <div class="noc-input-group">
                                    <input type="text" id="name" name="name" value="{{ old('name') }}"
                                        placeholder="Enter your full name"
                                        class="{{ $errors->has('name') ? 'is-invalid' : '' }}"
                                        required autofocus>
                                    <span class="noc-input-icon"><i class="bi bi-person"></i></span>
                                    @error('name')
                                        <div class="noc-invalid-feedback"><i class="bi bi-x-circle"></i> {{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="noc-field">
                                <label for="email">Email Address <span class="req">*</span></label>
                                <div class="noc-input-group">
                                    <input type="email" id="email" name="email" value="{{ old('email') }}"
                                        placeholder="Enter your email address"
                                        class="{{ $errors->has('email') ? 'is-invalid' : '' }}"
                                        required>
                                    <span class="noc-input-icon"><i class="bi bi-envelope"></i></span>
                                    @error('email')
                                        <div class="noc-invalid-feedback"><i class="bi bi-x-circle"></i> {{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="noc-field">
                                <label for="phone">Phone Number <span class="req">*</span></label>
                                <div class="noc-input-group">
                                    <input type="tel" id="phone" name="phone" value="{{ old('phone') }}"
                                        placeholder="Enter your mobile number"
                                        class="{{ $errors->has('phone') ? 'is-invalid' : '' }}"
                                        required>
                                    <span class="noc-input-icon"><i class="bi bi-telephone"></i></span>
                                    @error('phone')
                                        <div class="noc-invalid-feedback"><i class="bi bi-x-circle"></i> {{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

<<<<<<< HEAD
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
=======
                        {{-- Gender, DOB & NOC Employee --}}
                        <div class="noc-row3">
>>>>>>> 55e8c2322fd9818955a408f1f667542e5cee9f98
                            <div class="noc-field">
                                <label for="gender">Gender <span class="req">*</span></label>
                                <div class="noc-input-group">
                                    <select id="gender" name="gender"
                                        class="{{ $errors->has('gender') ? 'is-invalid' : '' }}" required>
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

                            {{-- UPDATED: DOB BS Date Picker --}}
                            <div class="noc-field">
                                <label for="date_of_birth_bs">Date of Birth (BS) <span class="req">*</span></label>
                                <div class="noc-input-group">
                                    <input type="text" id="date_of_birth_bs" name="date_of_birth_bs"
                                        value="{{ old('date_of_birth_bs') }}" placeholder="YYYY-MM-DD"
                                        autocomplete="off"
                                        class="{{ $errors->has('date_of_birth_bs') ? 'is-invalid' : '' }}" required>
                                    <span class="noc-input-icon"><i class="bi bi-calendar-event"></i></span>
                                    @error('date_of_birth_bs')
                                        <div class="noc-invalid-feedback"><i class="bi bi-x-circle"></i> {{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="noc-field">
<<<<<<< HEAD
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
=======
                                <label for="noc_employee">NOC Employee <span class="text-danger">*</span></label>
                                <div class="noc-input-group">
                                    <select id="noc_employee" name="noc_employee" required
                                        class="{{ $errors->has('noc_employee') ? 'is-invalid' : '' }}">
                                        <option value="">Select</option>
                                        <option value="yes" {{ old('noc_employee', $candidate->noc_employee ?? '') == 'yes' ? 'selected' : '' }}>Yes</option>
                                        <option value="no" {{ old('noc_employee', $candidate->noc_employee ?? '') == 'no' ? 'selected' : '' }}>No</option>
>>>>>>> 55e8c2322fd9818955a408f1f667542e5cee9f98
                                    </select>
                                    <span class="noc-input-icon"><i class="bi bi-person-badge"></i></span>
                                    @error('noc_employee')
                                        <div class="noc-invalid-feedback"><i class="bi bi-x-circle"></i> {{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

<<<<<<< HEAD
                        {{-- Citizenship Number & NID --}}
=======
                        {{-- Citizenship & NID --}}
>>>>>>> 55e8c2322fd9818955a408f1f667542e5cee9f98
                        <div class="noc-row">
                            <div class="noc-field">
                                <label for="citizenship_number">Citizenship Number <span class="req">*</span></label>
                                <div class="noc-input-group">
<<<<<<< HEAD
                                    <input
                                        type="text"
                                        id="citizenship_number"
                                        name="citizenship_number"
                                        value="{{ old('citizenship_number') }}"
                                        placeholder="Enter citizenship number"
                                        class="{{ $errors->has('citizenship_number') ? 'is-invalid' : '' }}"
                                        required
                                    >
=======
                                    <input type="text" id="citizenship_number" name="citizenship_number"
                                        value="{{ old('citizenship_number') }}" placeholder="Enter citizenship number"
                                        class="{{ $errors->has('citizenship_number') ? 'is-invalid' : '' }}" required>
>>>>>>> 55e8c2322fd9818955a408f1f667542e5cee9f98
                                    <span class="noc-input-icon"><i class="bi bi-card-text"></i></span>
                                    @error('citizenship_number')
                                        <div class="noc-invalid-feedback"><i class="bi bi-x-circle"></i> {{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="noc-field">
<<<<<<< HEAD
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
=======
                                <label for="nid">National ID Number</label>
                                <div class="noc-input-group">
                                    <input type="text" id="nid" name="nid" value="{{ old('nid') }}"
                                        placeholder="Enter national ID number"
                                        class="{{ $errors->has('nid') ? 'is-invalid' : '' }}">
>>>>>>> 55e8c2322fd9818955a408f1f667542e5cee9f98
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
                                    <input type="text" id="citizenship_issue_distric" name="citizenship_issue_distric"
                                        value="{{ old('citizenship_issue_distric') }}" placeholder="District name"
                                        class="{{ $errors->has('citizenship_issue_distric') ? 'is-invalid' : '' }}" required>
                                    <span class="noc-input-icon"><i class="bi bi-geo-alt"></i></span>
                                    @error('citizenship_issue_distric')
                                        <div class="noc-invalid-feedback"><i class="bi bi-x-circle"></i> {{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            {{-- UPDATED: Citizenship Issue Date BS Picker --}}
                            <div class="noc-field">
                                <label for="citizenship_issue_date_bs">Issue Date (BS) <span class="req">*</span></label>
                                <div class="noc-input-group">
                                    <input type="text" id="citizenship_issue_date_bs" name="citizenship_issue_date_bs"
                                        value="{{ old('citizenship_issue_date_bs') }}" placeholder="YYYY-MM-DD"
                                        autocomplete="off"
                                        class="{{ $errors->has('citizenship_issue_date_bs') ? 'is-invalid' : '' }}" required>
                                    <span class="noc-input-icon"><i class="bi bi-calendar-check"></i></span>
                                    @error('citizenship_issue_date_bs')
                                        <div class="noc-invalid-feedback"><i class="bi bi-x-circle"></i> {{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Password & Confirm --}}
                        <div class="noc-row">
                            <div class="noc-field">
                                <label for="password">Password <span class="req">*</span></label>
                                <div class="noc-input-group">
                                    <input type="password" id="password" name="password"
                                        placeholder="Min 8 characters"
                                        class="{{ $errors->has('password') ? 'is-invalid' : '' }}"
                                        required minlength="8" autocomplete="new-password">
                                    <span class="noc-input-icon"><i class="bi bi-lock"></i></span>
                                    @error('password')
                                        <div class="noc-invalid-feedback"><i class="bi bi-x-circle"></i> {{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="noc-field">
                                <label for="password_confirmation">Confirm Password <span class="req">*</span></label>
                                <div class="noc-input-group">
                                    <input type="password" id="password_confirmation" name="password_confirmation"
                                        placeholder="Re-enter password"
                                        required minlength="8" autocomplete="new-password">
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

{{-- Nepali Date Picker JS --}}
<script src="https://nepalidatepicker.sajanmaharjan.com.np/v5/nepali.datepicker/js/nepali.datepicker.v5.0.6.min.js"></script>

{{-- Date Picker Initialization --}}
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const dobInput = document.getElementById('date_of_birth_bs');
        const issueDateInput = document.getElementById('citizenship_issue_date_bs');

        if (dobInput && typeof dobInput.nepaliDatePicker === 'function') {
            dobInput.nepaliDatePicker({
                ndpYear: true,
                ndpMonth: true,
                ndpYearCount: 100
            });
        }

        if (issueDateInput && typeof issueDateInput.nepaliDatePicker === 'function') {
            issueDateInput.nepaliDatePicker({
                ndpYear: true,
                ndpMonth: true,
                ndpYearCount: 100
            });
        }
    });
</script>

<!-- BS/AD Date Converter -->
<script>
    // ============================================
    // Nepali (Bikram Sambat) <=> English (AD) Date Converter
    // No external CDN dependency - 100% reliable
    // Data source: Official Nepali Calendar
    // ============================================

    (function () {
        'use strict';

        // Official Nepali Calendar Data (days in each month for each year)
        // This is the ACCURATE data used by official converters
        const bsMonthData = {
            1975: [31, 31, 32, 32, 31, 30, 30, 29, 30, 29, 30, 30],
            1976: [31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 31],
            1977: [30, 32, 31, 32, 31, 30, 30, 30, 29, 30, 29, 31],
            1978: [31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30],
            1979: [31, 31, 32, 32, 31, 30, 30, 29, 30, 29, 30, 30],
            1980: [31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 31],
            1981: [31, 31, 31, 32, 31, 31, 29, 30, 30, 29, 30, 30],
            1982: [31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30],
            1983: [31, 31, 32, 32, 31, 30, 30, 29, 30, 29, 30, 30],
            1984: [31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 31],
            1985: [31, 31, 31, 32, 31, 31, 29, 30, 30, 29, 30, 30],
            1986: [31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30],
            1987: [31, 32, 31, 32, 31, 30, 30, 29, 30, 29, 30, 30],
            1988: [31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 31],
            1989: [31, 31, 31, 32, 31, 31, 30, 29, 30, 29, 30, 30],
            1990: [31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30],
            1991: [31, 32, 31, 32, 31, 30, 30, 29, 30, 29, 30, 30],
            1992: [31, 32, 31, 32, 31, 30, 30, 30, 29, 30, 29, 31],
            1993: [31, 31, 31, 32, 31, 31, 30, 29, 30, 29, 30, 30],
            1994: [31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30],
            1995: [31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 30],
            1996: [31, 32, 31, 32, 31, 30, 30, 30, 29, 30, 29, 31],
            1997: [31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30],
            1998: [31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30],
            1999: [31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 31],
            2000: [30, 32, 31, 32, 31, 30, 30, 30, 29, 30, 29, 31],
            2001: [31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30],
            2002: [31, 31, 32, 32, 31, 30, 30, 29, 30, 29, 30, 30],
            2003: [31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 31],
            2004: [30, 32, 31, 32, 31, 30, 30, 30, 29, 30, 29, 31],
            2005: [31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30],
            2006: [31, 31, 32, 32, 31, 30, 30, 29, 30, 29, 30, 30],
            2007: [31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 31],
            2008: [31, 31, 31, 32, 31, 31, 29, 30, 30, 29, 29, 31],
            2009: [31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30],
            2010: [31, 31, 32, 32, 31, 30, 30, 29, 30, 29, 30, 30],
            2011: [31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 31],
            2012: [31, 31, 31, 32, 31, 31, 29, 30, 30, 29, 30, 30],
            2013: [31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30],
            2014: [31, 31, 32, 32, 31, 30, 30, 29, 30, 29, 30, 30],
            2015: [31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 31],
            2016: [31, 31, 31, 32, 31, 31, 29, 30, 30, 29, 30, 30],
            2017: [31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30],
            2018: [31, 32, 31, 32, 31, 30, 30, 29, 30, 29, 30, 30],
            2019: [31, 32, 31, 32, 31, 30, 30, 30, 29, 30, 29, 31],
            2020: [31, 31, 31, 32, 31, 31, 30, 29, 30, 29, 30, 30],
            2021: [31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30],
            2022: [31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 30],
            2023: [31, 32, 31, 32, 31, 30, 30, 30, 29, 30, 29, 31],
            2024: [31, 31, 31, 32, 31, 31, 30, 29, 30, 29, 30, 30],
            2025: [31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30],
            2026: [31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 31],
            2027: [30, 32, 31, 32, 31, 30, 30, 30, 29, 30, 29, 31],
            2028: [31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30],
            2029: [31, 31, 32, 31, 32, 30, 30, 29, 30, 29, 30, 30],
            2030: [31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 31],
            2031: [30, 32, 31, 32, 31, 30, 30, 30, 29, 30, 29, 31],
            2032: [31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30],
            2033: [31, 31, 32, 32, 31, 30, 30, 29, 30, 29, 30, 30],
            2034: [31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 31],
            2035: [30, 32, 31, 32, 31, 31, 29, 30, 30, 29, 29, 31],
            2036: [31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30],
            2037: [31, 31, 32, 32, 31, 30, 30, 29, 30, 29, 30, 30],
            2038: [31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 31],
            2039: [31, 31, 31, 32, 31, 31, 29, 30, 30, 29, 30, 30],
            2040: [31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30],
            2041: [31, 31, 32, 32, 31, 30, 30, 29, 30, 29, 30, 30],
            2042: [31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 31],
            2043: [31, 31, 31, 32, 31, 31, 29, 30, 30, 29, 30, 30],
            2044: [31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30],
            2045: [31, 32, 31, 32, 31, 30, 30, 29, 30, 29, 30, 30],
            2046: [31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 31],
            2047: [31, 31, 31, 32, 31, 31, 30, 29, 30, 29, 30, 30],
            2048: [31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30],
            2049: [31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 30],
            2050: [31, 32, 31, 32, 31, 30, 30, 30, 29, 30, 29, 31],
            2051: [31, 31, 31, 32, 31, 31, 30, 29, 30, 29, 30, 30],
            2052: [31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30],
            2053: [31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 30],
            2054: [31, 32, 31, 32, 31, 30, 30, 30, 29, 30, 29, 31],
            2055: [31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30],
            2056: [31, 31, 32, 31, 32, 30, 30, 29, 30, 29, 30, 30],
            2057: [31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 31],
            2058: [30, 32, 31, 32, 31, 30, 30, 30, 29, 30, 29, 31],
            2059: [31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30],
            2060: [31, 31, 32, 32, 31, 30, 30, 29, 30, 29, 30, 30],
            2061: [31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 31],
            2062: [30, 32, 31, 32, 31, 31, 29, 30, 29, 30, 29, 31],
            2063: [31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30],
            2064: [31, 31, 32, 32, 31, 30, 30, 29, 30, 29, 30, 30],
            2065: [31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 31],
            2066: [31, 31, 31, 32, 31, 31, 29, 30, 30, 29, 29, 31],
            2067: [31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30],
            2068: [31, 31, 32, 32, 31, 30, 30, 29, 30, 29, 30, 30],
            2069: [31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 31],
            2070: [31, 31, 31, 32, 31, 31, 29, 30, 30, 29, 30, 30],
            2071: [31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30],
            2072: [31, 32, 31, 32, 31, 30, 30, 29, 30, 29, 30, 30],
            2073: [31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 31],
            2074: [31, 31, 31, 32, 31, 31, 30, 29, 30, 29, 30, 30],
            2075: [31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30],
            2076: [31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 30],
            2077: [31, 32, 31, 32, 31, 30, 30, 30, 29, 30, 29, 31],
            2078: [31, 31, 31, 32, 31, 31, 30, 29, 30, 29, 30, 30],
            2079: [31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30],
            2080: [31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 30],
            2081: [31, 32, 31, 32, 31, 30, 30, 30, 29, 30, 29, 31],
            2082: [31, 31, 31, 32, 31, 31, 30, 29, 30, 29, 30, 30],
            2083: [31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30],
            2084: [31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 31],
            2085: [30, 32, 31, 32, 31, 30, 30, 30, 29, 30, 29, 31],
            2086: [31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30],
            2087: [31, 31, 32, 32, 31, 30, 30, 29, 30, 29, 30, 30],
            2088: [31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 31],
            2089: [30, 32, 31, 32, 31, 31, 29, 30, 29, 30, 29, 31],
            2090: [31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30],
            2091: [31, 31, 32, 32, 31, 30, 30, 29, 30, 29, 30, 30],
            2092: [31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 31],
            2093: [31, 31, 31, 32, 31, 31, 29, 30, 30, 29, 29, 31],
            2094: [31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30],
            2095: [31, 31, 32, 32, 31, 30, 30, 29, 30, 29, 30, 30],
            2096: [31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 31],
            2097: [30, 32, 31, 32, 31, 31, 29, 30, 30, 29, 29, 31],
            2098: [31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30],
            2099: [31, 31, 32, 32, 31, 30, 30, 29, 30, 29, 30, 30]
        };

        // Reference point: 2000-01-01 BS = 1943-04-14 AD
        const bsStartYear = 2000;
        const bsStartMonth = 1;
        const bsStartDay = 1;
        const adRefDate = new Date(1943, 3, 14); // April 14, 1943

        function getTotalDaysInBsYear(year) {
            if (!bsMonthData[year]) return 365;
            return bsMonthData[year].reduce((sum, days) => sum + days, 0);
        }

        function getDaysInBsMonth(year, month) {
            if (!bsMonthData[year]) return 30;
            return bsMonthData[year][month - 1] || 30;
        }

        function countBsDays(year, month, day) {
            let totalDays = 0;

            for (let y = bsStartYear; y < year; y++) {
                totalDays += getTotalDaysInBsYear(y);
            }

            for (let m = 1; m < month; m++) {
                totalDays += getDaysInBsMonth(year, m);
            }

            totalDays += day - bsStartDay;

            return totalDays;
        }

        window.bsToAD = function (bsDateStr) {
            try {
                const parts = bsDateStr.split('-').map(Number);
                const bsYear = parts[0];
                const bsMonth = parts[1];
                const bsDay = parts[2];

                if (!bsYear || !bsMonth || !bsDay) {
                    console.error('Invalid BS date format');
                    return '';
                }

                const totalDays = countBsDays(bsYear, bsMonth, bsDay);

                const adDate = new Date(adRefDate);
                adDate.setDate(adDate.getDate() + totalDays);

                return adDate.getFullYear() + '-' +
                    String(adDate.getMonth() + 1).padStart(2, '0') + '-' +
                    String(adDate.getDate()).padStart(2, '0');
            } catch (error) {
                console.error('BS to AD error:', error);
                return '';
            }
        };

        window.adToBS = function (adDateStr) {
            try {
                const adDate = new Date(adDateStr);
                if (isNaN(adDate.getTime())) {
                    console.error('Invalid AD date');
                    return '';
                }

                const diffTime = adDate.getTime() - adRefDate.getTime();
                let totalDays = Math.floor(diffTime / (1000 * 60 * 60 * 24));

                let bsYear = bsStartYear;
                let bsMonth = bsStartMonth;
                let bsDay = bsStartDay;

                bsDay += totalDays;

                while (bsDay > getDaysInBsMonth(bsYear, bsMonth)) {
                    bsDay -= getDaysInBsMonth(bsYear, bsMonth);
                    bsMonth++;
                    if (bsMonth > 12) {
                        bsMonth = 1;
                        bsYear++;
                    }
                }

                while (bsDay < 1) {
                    bsMonth--;
                    if (bsMonth < 1) {
                        bsMonth = 12;
                        bsYear--;
                    }
                    bsDay += getDaysInBsMonth(bsYear, bsMonth);
                }

                return bsYear + '-' +
                    String(bsMonth).padStart(2, '0') + '-' +
                    String(bsDay).padStart(2, '0');
            } catch (error) {
                console.error('AD to BS error:', error);
                return '';
            }
        };

        window.nepaliLibrariesReady = true;
        console.log('Nepali Date Converter ready!');
    })();
</script>
@endsection