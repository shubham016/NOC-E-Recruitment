@extends('layouts.app')

@section('title', 'Candidate Login')

@section('custom-styles')
    <style>
        .login-wrapper {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-card {
            max-width: 480px;
            width: 100%;
        }

        .card-header-custom {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            border: none;
            padding: 3rem 2rem;
        }

        .login-icon-wrapper {
            width: 80px;
            height: 80px;
            background: rgba(255, 255, 255, 0.25);
            backdrop-filter: blur(10px);
            margin: 0 auto 1.5rem;
        }

        .btn-candidate-login {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            border: none;
            padding: 0.875rem;
            font-weight: 600;
            letter-spacing: 0.5px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(16, 185, 129, 0.3);
        }

        .btn-candidate-login:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(16, 185, 129, 0.4);
            background: linear-gradient(135deg, #059669 0%, #047857 100%);
        }

        .form-control:focus {
            border-color: #10b981;
            box-shadow: 0 0 0 0.25rem rgba(16, 185, 129, 0.15);
        }

        .form-check-input:checked {
            background-color: #10b981;
            border-color: #10b981;
        }

        .card {
            border: none;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            overflow: hidden;
        }

        .alert-success {
            border-left: 4px solid #10b981;
            background-color: #d1fae5;
            color: #065f46;
        }

        .alert-danger {
            border-left: 4px solid #dc3545;
        }

        .forgot-password-link {
            color: #10b981;
            text-decoration: none;
            font-weight: 500;
            font-size: 14px;
            transition: color 0.3s ease;
        }

        .forgot-password-link:hover {
            color: #059669;
            text-decoration: underline;
        }

        .register-link {
            text-align: center;
            margin-top: 24px;
            color: #ffffff;
        }

        .register-link a {
            color: #10b981;
            font-weight: 600;
            text-decoration: none;
            padding: 8px 16px;
            background: white;
            border-radius: 6px;
            display: inline-block;
            transition: all 0.3s ease;
        }

        .register-link a:hover {
            background: #10b981;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
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
            <div class="col-12 col-sm-10 col-md-8 col-lg-5 col-xl-4">
                <div class="login-card">
                    <div class="card">
                        <!-- Header -->
                        <div class="card-header card-header-custom text-center text-white">
                            <div class="login-icon-wrapper rounded-circle d-flex align-items-center justify-content-center">
                                <i class="bi bi-briefcase fs-1"></i>
                            </div>
                            <h1 class="h3 fw-bold mb-2">Candidate Portal</h1>
                            <p class="mb-0 opacity-90 small">Your Career Journey Starts Here</p>
                        </div>

                        <!-- Body -->
                        <div class="card-body p-4 p-md-5">
                            <!-- Success Alert (Registration/Email Verification Success) -->
                            @if (session('success'))
                                <div class="alert alert-success d-flex align-items-center alert-dismissible fade show"
                                    role="alert">
                                    <i class="bi bi-check-circle-fill me-2 fs-5"></i>
                                    <div>
                                        <strong>Success!</strong> {{ session('success') }}
                                    </div>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            @endif

                            <!-- Error Alert -->
                            @if ($errors->any())
                                <div class="alert alert-danger d-flex align-items-center alert-dismissible fade show"
                                    role="alert">
                                    <i class="bi bi-exclamation-triangle-fill me-2 fs-5"></i>
                                    <div>
                                        <strong>Error!</strong> {{ $errors->first() }}
                                    </div>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            @endif

                            <!-- Login Form -->
                            <form method="POST" action="{{ route('candidate.login.post') }}" novalidate>
                                @csrf

                                <!-- Email Field -->
                                <div class="mb-4">
                                    <label for="email" class="form-label fw-semibold text-dark">
                                        <i class="bi bi-envelope me-1"></i>Email Address
                                    </label>
                                    <input type="email"
                                        class="form-control form-control-lg @error('email') is-invalid @enderror" id="email"
                                        name="email" value="{{ old('email') }}" placeholder="Enter your email" required
                                        autofocus>
                                    @error('email')
                                        <div class="invalid-feedback">
                                            <i class="bi bi-x-circle me-1"></i>{{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <!-- Password Field -->
                                <div class="mb-3">
                                    <label for="password" class="form-label fw-semibold text-dark">
                                        <i class="bi bi-lock me-1"></i>Password
                                    </label>
                                    <input type="password"
                                        class="form-control form-control-lg @error('password') is-invalid @enderror"
                                        id="password" name="password" placeholder="Enter your password" required>
                                    @error('password')
                                        <div class="invalid-feedback">
                                            <i class="bi bi-x-circle me-1"></i>{{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <!-- Forgot Password Link -->
                                <div class="mb-4 text-end">
                                    <a href="{{ route('candidate.forgot.password') }}" class="forgot-password-link">
                                        <i class="bi bi-key me-1"></i>Forgot Password?
                                    </a>
                                </div>

                                <!-- Remember Me -->
                                <div class="mb-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="1" id="remember"
                                            name="remember">
                                        <label class="form-check-label text-muted" for="remember">
                                            Keep me signed in
                                        </label>
                                    </div>
                                </div>

                                <!-- Submit Button -->
                                <div class="d-grid gap-2">
                                    <button type="submit" class="btn btn-primary btn-lg btn-candidate-login text-white">
                                        <i class="bi bi-box-arrow-in-right me-2"></i>Sign In to Apply
                                    </button>
                                </div>
                            </form>
                        </div>

                        <!-- Footer -->
                        <div class="card-footer bg-light border-0 py-4">
                            <div class="text-center">
                                <small class="text-muted">
                                    <span class="me-2">Access other portals:</span><br class="d-sm-none">
                                    <a href="{{ route('admin.login') }}" class="text-decoration-none"
                                        style="color: #10b981;">
                                        <i class="bi bi-shield-lock me-1"></i>Admin
                                    </a>
                                    <span class="mx-2 text-muted">•</span>
                                    <a href="{{ route('reviewer.login') }}" class="text-decoration-none"
                                        style="color: #10b981;">
                                        <i class="bi bi-clipboard-check me-1"></i>Reviewer
                                    </a>
                                </small>
                            </div>
                        </div>
                    </div>

                    <!-- Register Link -->
                    <div class="register-link">
                        Don't have an account?
                        <a href="{{ route('candidate.register') }}">
                            <i class="bi bi-person-plus me-1"></i>Create Account
                        </a>
                    </div>

                    <!-- Copyright -->
                    <div class="text-center mt-4">
                        <small class="text-white opacity-75">
                            © 2025 Recruitment Management System. All rights reserved.
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection