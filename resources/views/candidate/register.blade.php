@extends('layouts.guest')

@section('title', 'Candidate Registration')

@section('custom-styles')
<style>
    .login-wrapper {
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 2rem 0;
    }

    .login-card {
        max-width: 700px;
        width: 100%;
    }

    .card-header-custom {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        border: none;
        padding: 2.5rem 2rem;
    }

    .login-icon-wrapper {
        width: 80px;
        height: 80px;
        background: rgba(255, 255, 255, 0.25);
        backdrop-filter: blur(10px);
        margin: 0 auto 1.5rem;
    }

    .btn-candidate-register {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        border: none;
        padding: 0.875rem;
        font-weight: 600;
        letter-spacing: 0.5px;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(16, 185, 129, 0.3);
    }

    .btn-candidate-register:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(16, 185, 129, 0.4);
        background: linear-gradient(135deg, #059669 0%, #047857 100%);
    }

    .btn-candidate-register:active {
        transform: translateY(-1px);
    }

    .form-control:focus,
    .form-select:focus {
        border-color: #10b981;
        box-shadow: 0 0 0 0.25rem rgba(16, 185, 129, 0.15);
    }

    .card {
        border: none;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        overflow: hidden;
    }

    .footer-links a {
        color: #10b981;
        text-decoration: none;
        font-weight: 500;
        transition: color 0.3s ease;
    }

    .footer-links a:hover {
        color: #059669;
        text-decoration: underline;
    }

    .alert {
        border: none;
        border-left: 4px solid #dc3545;
    }

    .alert-danger {
        border-left-color: #dc3545;
    }

    @media (max-width: 576px) {
        .login-card {
            margin: 1rem;
        }
        
        .card-header-custom {
            padding: 2rem 1.5rem;
        }

        .card-body {
            padding: 2rem 1.5rem !important;
        }
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center align-items-center login-wrapper">
        <div class="col-12 col-sm-11 col-md-10 col-lg-8 col-xl-7">
            <div class="login-card">
                <div class="card">
                    <!-- Header -->
                    <div class="card-header card-header-custom text-center text-white">
                        <div class="login-icon-wrapper rounded-circle d-flex align-items-center justify-content-center">
                            <i class="bi bi-person-plus fs-1"></i>
                        </div>
                        <h1 class="h3 fw-bold mb-2">Candidate Registration</h1>
                        <p class="mb-0 opacity-90 small">Create Your Account to Apply for Positions</p>
                    </div>

                    <!-- Body -->
                    <div class="card-body p-4 p-md-5">
                        <!-- Error Alert -->
                        @if ($errors->any())
                            <div class="alert alert-danger d-flex align-items-center alert-dismissible fade show" role="alert">
                                <i class="bi bi-exclamation-triangle-fill me-2 fs-5"></i>
                                <div>
                                    <strong>Error!</strong>
                                    <ul class="mb-0 mt-2">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        <!-- Success Alert -->
                        @if (session('success'))
                            <div class="alert alert-success d-flex align-items-center alert-dismissible fade show" role="alert">
                                <i class="bi bi-check-circle-fill me-2 fs-5"></i>
                                <div>{{ session('success') }}</div>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        <!-- Registration Form -->
                        <form method="POST" action="{{ route('candidate.register.post') }}" novalidate>
                            @csrf

                            <!-- Name Row -->
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="first_name" class="form-label fw-semibold text-dark">
                                        <i class="bi bi-person me-1"></i>First Name <span class="text-danger">*</span>
                                    </label>
                                    <input
                                        type="text"
                                        class="form-control form-control-lg @error('first_name') is-invalid @enderror"
                                        id="first_name"
                                        name="first_name"
                                        value="{{ old('first_name') }}"
                                        placeholder="First name"
                                        required
                                        autofocus
                                    >
                                    @error('first_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="middle_name" class="form-label fw-semibold text-dark">
                                        <i class="bi bi-person me-1"></i>Middle Name
                                    </label>
                                    <input
                                        type="text"
                                        class="form-control form-control-lg @error('middle_name') is-invalid @enderror"
                                        id="middle_name"
                                        name="middle_name"
                                        value="{{ old('middle_name') }}"
                                        placeholder="Middle name (optional)"
                                    >
                                    @error('middle_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="last_name" class="form-label fw-semibold text-dark">
                                        <i class="bi bi-person me-1"></i>Last Name <span class="text-danger">*</span>
                                    </label>
                                    <input
                                        type="text"
                                        class="form-control form-control-lg @error('last_name') is-invalid @enderror"
                                        id="last_name"
                                        name="last_name"
                                        value="{{ old('last_name') }}"
                                        placeholder="Last name"
                                        required
                                    >
                                    @error('last_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Username & Mobile -->
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="username" class="form-label fw-semibold text-dark">
                                        <i class="bi bi-at me-1"></i>Username <span class="text-danger">*</span>
                                    </label>
                                    <input
                                        type="text"
                                        class="form-control form-control-lg @error('username') is-invalid @enderror"
                                        id="username"
                                        name="username"
                                        value="{{ old('username') }}"
                                        placeholder="letters, numbers, _"
                                        required
                                    >
                                    @error('username')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="mobile_number" class="form-label fw-semibold text-dark">
                                        <i class="bi bi-phone me-1"></i>Mobile Number <span class="text-danger">*</span>
                                    </label>
                                    <input
                                        type="text"
                                        class="form-control form-control-lg @error('mobile_number') is-invalid @enderror"
                                        id="mobile_number"
                                        name="mobile_number"
                                        value="{{ old('mobile_number') }}"
                                        placeholder="10-digit mobile number"
                                        required
                                    >
                                    @error('mobile_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Email -->
                            <div class="mb-3">
                                <label for="email" class="form-label fw-semibold text-dark">
                                    <i class="bi bi-envelope me-1"></i>Email Address <span class="text-danger">*</span>
                                </label>
                                <input
                                    type="email"
                                    class="form-control form-control-lg @error('email') is-invalid @enderror"
                                    id="email"
                                    name="email"
                                    value="{{ old('email') }}"
                                    placeholder="Enter your email address"
                                    required
                                >
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Gender & Date of Birth -->
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="gender" class="form-label fw-semibold text-dark">
                                        <i class="bi bi-gender-ambiguous me-1"></i>Gender
                                    </label>
                                    <select
                                        class="form-select form-select-lg @error('gender') is-invalid @enderror"
                                        id="gender"
                                        name="gender"
                                    >
                                        <option value="">Select Gender</option>
                                        <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                                        <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                                        <option value="other" {{ old('gender') == 'other' ? 'selected' : '' }}>Other</option>
                                    </select>
                                    @error('gender')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="date_of_birth_bs" class="form-label fw-semibold text-dark">
                                        <i class="bi bi-calendar-event me-1"></i>Date of Birth (BS)
                                    </label>
                                    <input
                                        type="text"
                                        class="form-control form-control-lg @error('date_of_birth_bs') is-invalid @enderror"
                                        id="date_of_birth_bs"
                                        name="date_of_birth_bs"
                                        value="{{ old('date_of_birth_bs') }}"
                                        placeholder="YYYY-MM-DD"
                                    >
                                    @error('date_of_birth_bs')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Citizenship Number -->
                            <div class="mb-3">
                                <label for="citizenship_number" class="form-label fw-semibold text-dark">
                                    <i class="bi bi-card-text me-1"></i>Citizenship Number
                                </label>
                                <input
                                    type="text"
                                    class="form-control form-control-lg @error('citizenship_number') is-invalid @enderror"
                                    id="citizenship_number"
                                    name="citizenship_number"
                                    value="{{ old('citizenship_number') }}"
                                    placeholder="Enter citizenship number"
                                >
                                @error('citizenship_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Citizenship Issue District & Date -->
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="citizenship_issue_district" class="form-label fw-semibold text-dark">
                                        <i class="bi bi-geo-alt me-1"></i>Issue District
                                    </label>
                                    <input
                                        type="text"
                                        class="form-control form-control-lg @error('citizenship_issue_district') is-invalid @enderror"
                                        id="citizenship_issue_district"
                                        name="citizenship_issue_district"
                                        value="{{ old('citizenship_issue_district') }}"
                                        placeholder="District name"
                                    >
                                    @error('citizenship_issue_district')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="citizenship_issue_date_bs" class="form-label fw-semibold text-dark">
                                        <i class="bi bi-calendar-check me-1"></i>Issue Date (BS)
                                    </label>
                                    <input
                                        type="text"
                                        class="form-control form-control-lg @error('citizenship_issue_date_bs') is-invalid @enderror"
                                        id="citizenship_issue_date_bs"
                                        name="citizenship_issue_date_bs"
                                        value="{{ old('citizenship_issue_date_bs') }}"
                                        placeholder="YYYY-MM-DD"
                                    >
                                    @error('citizenship_issue_date_bs')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Password & Confirm Password -->
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="password" class="form-label fw-semibold text-dark">
                                        <i class="bi bi-lock me-1"></i>Password <span class="text-danger">*</span>
                                    </label>
                                    <input
                                        type="password"
                                        class="form-control form-control-lg @error('password') is-invalid @enderror"
                                        id="password"
                                        name="password"
                                        placeholder="Min 8 characters"
                                        required
                                        minlength="8"
                                    >
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="password_confirmation" class="form-label fw-semibold text-dark">
                                        <i class="bi bi-lock-fill me-1"></i>Confirm Password <span class="text-danger">*</span>
                                    </label>
                                    <input
                                        type="password"
                                        class="form-control form-control-lg"
                                        id="password_confirmation"
                                        name="password_confirmation"
                                        placeholder="Re-enter password"
                                        required
                                        minlength="8"
                                    >
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <div class="d-grid gap-2 mt-4">
                                <button type="submit" class="btn btn-primary btn-lg btn-candidate-register text-white">
                                    <i class="bi bi-person-plus me-2"></i>Create Account
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Footer -->
                    <div class="card-footer bg-light border-0 py-4">
                        <div class="text-center footer-links">
                            <small class="text-muted">
                                Already have an account? 
                                <a href="{{ route('candidate.login') }}" class="text-decoration-none">
                                    <i class="bi bi-box-arrow-in-right me-1"></i>Login here
                                </a>
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection