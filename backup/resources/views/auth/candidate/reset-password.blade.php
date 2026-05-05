@extends('layouts.app')

@section('title', 'Reset Password')

@section('custom-styles')
    <style>
        .reset-wrapper {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .reset-card {
            max-width: 480px;
            width: 100%;
            background: white;
            border-radius: 16px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            overflow: hidden;
        }

        .reset-header {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            padding: 2rem;
            text-align: center;
        }

        .reset-icon {
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

        .btn-reset {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            border: none;
            padding: 12px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-reset:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 16px rgba(16, 185, 129, 0.3);
        }

        .form-control:focus {
            border-color: #10b981;
            box-shadow: 0 0 0 0.25rem rgba(16, 185, 129, 0.15);
        }

        @media (max-width: 576px) {
            .reset-card {
                margin: 1rem;
            }
        }
    </style>
@endsection

@section('content')
    <div class="reset-wrapper">
        <div class="reset-card">
            <div class="reset-header">
                <div class="reset-icon">
                    <i class="bi bi-lock-fill"></i>
                </div>
                <h2 class="mb-2">Set New Password</h2>
                <p class="mb-0 opacity-90">Create a strong password for your account</p>
            </div>

            <div class="p-4">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <i class="bi bi-exclamation-circle me-2"></i>{{ $errors->first() }}
                    </div>
                @endif

                <form method="POST" action="{{ route('candidate.password.reset.post') }}">
                    @csrf

                    <div class="mb-4">
                        <label for="password" class="form-label fw-semibold">
                            <i class="bi bi-lock me-1"></i>New Password
                        </label>
                        <input type="password" class="form-control form-control-lg @error('password') is-invalid @enderror"
                            id="password" name="password" placeholder="Enter new password (min 8 characters)" required
                            autofocus>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="password_confirmation" class="form-label fw-semibold">
                            <i class="bi bi-lock-fill me-1"></i>Confirm Password
                        </label>
                        <input type="password" class="form-control form-control-lg" id="password_confirmation"
                            name="password_confirmation" placeholder="Re-enter new password" required>
                    </div>

                    <div class="alert alert-info border-0 mb-4">
                        <i class="bi bi-info-circle me-2"></i>
                        <small>
                            <strong>Password Requirements:</strong><br>
                            • Minimum 8 characters<br>
                            • Mix of letters and numbers recommended<br>
                            • Avoid using common passwords
                        </small>
                    </div>

                    <button type="submit" class="btn btn-primary btn-lg btn-reset w-100 text-white">
                        <i class="bi bi-check-circle me-2"></i>Reset Password
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection