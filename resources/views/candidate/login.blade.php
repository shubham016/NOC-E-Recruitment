@extends('layouts.guest')

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

    .btn-candidate-login:active {
        transform: translateY(-1px);
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
                        <!-- Error Alert -->
                        @if ($errors->any())
                            <div class="alert alert-danger d-flex align-items-center alert-dismissible fade show" role="alert">
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
                                <input 
                                    type="email" 
                                    class="form-control form-control-lg @error('email') is-invalid @enderror" 
                                    id="email" 
                                    name="email" 
                                    value="{{ old('email') }}"
                                    placeholder="Enter your email"
                                    required
                                    autofocus
                                >
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
                                </label>
                                <input 
                                    type="password" 
                                    class="form-control form-control-lg @error('password') is-invalid @enderror" 
                                    id="password" 
                                    name="password"
                                    placeholder="Enter your password"
                                    required
                                >
                                @error('password')
                                    <div class="invalid-feedback">
                                        <i class="bi bi-x-circle me-1"></i>{{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <!-- Remember Me -->
                            <div class="mb-4">
                                <div class="form-check">
                                    <input 
                                        class="form-check-input" 
                                        type="checkbox" 
                                        value="1" 
                                        id="remember" 
                                        name="remember"
                                    >
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
                        <div class="text-center footer-links">
                        <span class="me-2">Register Candidate:</span><br class="d-sm-none">
                                <a href="{{ route('candidate.register') }}" class="text-decoration-none">
                                    <i class="bi bi-shield-lock me-1"></i>Register
                                </a>
                        </div>
                        <div class="text-center footer-links">
                            <small class="text-muted">
                                <span class="me-2">Access other portals:</span><br class="d-sm-none">
                                <a href="{{ route('admin.login') }}" class="text-decoration-none">
                                    <i class="bi bi-shield-lock me-1"></i>Admin Portal
                                </a>
                                <span class="mx-2 text-muted">•</span>
                                <a href="{{ route('reviewer.login') }}" class="text-decoration-none">
                                    <i class="bi bi-clipboard-check me-1"></i>Reviewer Portal
                                </a>
                            </small>
                        </div>
                    </div>
                </div>

                <!-- Copyright -->
                <div class="text-center mt-4">
                    <small class="text-white opacity-75">
                        © 2024 Recruitment Management System. All rights reserved.
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection