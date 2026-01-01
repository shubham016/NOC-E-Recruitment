@extends('layouts.app')

@section('title', 'HR Administrator Login')

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
            background: linear-gradient(135deg, #0369a1 0%, #0284c7 100%);
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

        .btn-hr-login {
            background: linear-gradient(135deg, #0369a1 0%, #0284c7 100%);
            border: none;
            padding: 0.875rem;
            font-weight: 600;
            letter-spacing: 0.5px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(3, 105, 161, 0.3);
        }

        .btn-hr-login:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(3, 105, 161, 0.4);
            background: linear-gradient(135deg, #0284c7 0%, #0369a1 100%);
        }

        .btn-hr-login:active {
            transform: translateY(-1px);
        }

        .form-control:focus {
            border-color: #0369a1;
            box-shadow: 0 0 0 0.25rem rgba(3, 105, 161, 0.15);
        }

        .form-check-input:checked {
            background-color: #0369a1;
            border-color: #0369a1;
        }

        .card {
            border: none;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            overflow: hidden;
        }

        .footer-links a {
            color: #0369a1;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s ease;
        }

        .footer-links a:hover {
            color: #0284c7;
            text-decoration: underline;
        }

        .alert {
            border: none;
            border-left: 4px solid #dc3545;
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
                                <img src="{{ asset('images/noc_logo.png') }}" alt="Nepal Oil Corporation"
                                    style="width: 60px; height: 60px; object-fit: auto; border-radius: 8px; padding: 2px;">
                            </div>
                            <h1 class="h3 fw-bold mb-2">HR Administrator Portal</h1>
                            <p class="mb-0 opacity-90 small">E-Recruitment Management System</p>
                        </div>

                        <!-- Body -->
                        <div class="card-body p-4 p-md-5">
                            <!-- Success Alert -->
                            @if (session('success'))
                                <div class="alert alert-success d-flex align-items-center alert-dismissible fade show"
                                    role="alert">
                                    <i class="bi bi-check-circle-fill me-2 fs-5"></i>
                                    <div>
                                        {{ session('success') }}
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
                            <form method="POST" action="{{ route('hr-administrator.login.post') }}" novalidate>
                                @csrf

                                <!-- Email Field -->
                                <div class="mb-4">
                                    <label for="email" class="form-label fw-semibold text-dark">
                                        <i class="bi bi-envelope me-1"></i>Email Address
                                        <span class="nepali-label" style="color: #6b7280;">(इमेल)</span>
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
                                <div class="mb-4">
                                    <label for="password" class="form-label fw-semibold text-dark">
                                        <i class="bi bi-lock me-1"></i>Password
                                        <span class="nepali-label" style="color: #6b7280;">(पासवर्ड)</span>
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
                                    <button type="submit" class="btn btn-primary btn-lg btn-hr-login text-white">
                                        <i class="bi bi-box-arrow-in-right me-2"></i>Sign In to Dashboard
                                    </button>
                                </div>
                            </form>
                        </div>

                        <!-- Footer -->
                        <div class="card-footer bg-light border-0 py-4">
                            <div class="text-center footer-links">
                                <small class="text-muted">
                                    <span class="me-2">Access other portals:</span><br class="d-sm-none">
                                    <a href="{{ route('admin.login') }}" class="text-decoration-none">
                                        <i class="bi bi-shield-check me-1"></i>Admin Portal
                                    </a>
                                    <span class="mx-2 text-muted">•</span>
                                    <a href="{{ route('reviewer.login') }}" class="text-decoration-none">
                                        <i class="bi bi-search me-1"></i>Reviewer Portal
                                    </a>
                                    <span class="mx-2 text-muted">•</span>
                                    <a href="{{ route('candidate.login') }}" class="text-decoration-none">
                                        <i class="bi bi-person-circle me-1"></i>Candidate Portal
                                    </a>
                                </small>
                            </div>
                        </div>
                    </div>

                    <!-- Copyright -->
                    <div class="text-center mt-4">
                        <small class="text-white opacity-75">
                            © 2025 NOC E-Recruitment Management System. All Rights Reserved.
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection