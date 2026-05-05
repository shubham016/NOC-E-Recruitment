@extends('layouts.app')

@section('title', 'Forgot Password')

@section('custom-styles')
    <style>
        .forgot-wrapper {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .forgot-card {
            max-width: 480px;
            width: 100%;
            background: white;
            border-radius: 16px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            overflow: hidden;
        }

        .forgot-header {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            padding: 2rem;
            text-align: center;
        }

        .forgot-icon {
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

        .btn-send-otp {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            border: none;
            padding: 12px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-send-otp:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 16px rgba(16, 185, 129, 0.3);
        }

        .back-link {
            color: #10b981;
            text-decoration: none;
            font-weight: 600;
        }

        .back-link:hover {
            color: #059669;
            text-decoration: underline;
        }

        .form-control:focus {
            border-color: #10b981;
            box-shadow: 0 0 0 0.25rem rgba(16, 185, 129, 0.15);
        }

        @media (max-width: 576px) {
            .forgot-card {
                margin: 1rem;
            }
        }
    </style>
@endsection

@section('content')
    <div class="forgot-wrapper">
        <div class="forgot-card">
            <div class="forgot-header">
                <div class="forgot-icon">
                    <i class="bi bi-key"></i>
                </div>
                <h2 class="mb-2">Forgot Password?</h2>
                <p class="mb-0 opacity-90">No worries! We'll send you an OTP to reset it</p>
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

                <form method="POST" action="{{ route('candidate.forgot.password.post') }}">
                    @csrf

                    <div class="mb-4">
                        <label for="email" class="form-label fw-semibold">
                            <i class="bi bi-envelope me-1"></i>Email Address
                        </label>
                        <input type="email" class="form-control form-control-lg @error('email') is-invalid @enderror"
                            id="email" name="email" value="{{ old('email') }}" placeholder="Enter your registered email"
                            required autofocus>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">
                            We'll send a 6-digit OTP code to this email
                        </small>
                    </div>

                    <button type="submit" class="btn btn-primary btn-lg btn-send-otp w-100 text-white mb-3">
                        <i class="bi bi-send me-2"></i>Send OTP Code
                    </button>
                </form>

                <div class="text-center">
                    <a href="{{ route('candidate.login') }}" class="back-link">
                        <i class="bi bi-arrow-left me-1"></i>Back to Login
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection