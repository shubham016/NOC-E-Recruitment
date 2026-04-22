@extends('layouts.dashboard')

@section('title', 'Change Password')
@section('portal-name', 'Admin Portal')
@section('brand-icon', 'bi bi-shield-lock')
@section('dashboard-route', route('admin.dashboard'))
@section('logout-route', route('admin.logout'))

@section('sidebar-menu')
    <a href="{{ route('admin.dashboard') }}" class="sidebar-menu-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
        <i class="bi bi-speedometer2"></i>
        <span>Dashboard</span>
    </a>
    <a href="{{ route('admin.jobs.index') }}" class="sidebar-menu-item {{ request()->routeIs('admin.jobs.*') ? 'active' : '' }}">
        <i class="bi bi-briefcase"></i>
        <span>Job Management</span>
    </a>
    <a href="{{ route('admin.applications.index') }}" class="sidebar-menu-item {{ request()->routeIs('admin.applications.*') ? 'active' : '' }}">
        <i class="bi bi-file-earmark-text"></i>
        <span>Applications</span>
    </a>
    <a href="{{ route('admin.candidates.index') }}" class="sidebar-menu-item {{ request()->routeIs('admin.candidates.*') ? 'active' : '' }}">
        <i class="bi bi-people"></i>
        <span>Candidates</span>
    </a>
    <a href="{{ route('admin.reviewers.index') }}" class="sidebar-menu-item {{ request()->routeIs('admin.reviewers.*') ? 'active' : '' }}">
        <i class="bi bi-person-check"></i>
        <span>Reviewers</span>
    </a>
    <a href="{{ route('admin.approvers.index') }}" class="sidebar-menu-item {{ request()->routeIs('admin.approvers.*') ? 'active' : '' }}">
        <i class="bi bi-person-check-fill"></i>
        <span>Approvers</span>
    </a>
    <a href="{{ route('admin.profile') }}" class="sidebar-menu-item {{ request()->routeIs('admin.profile*') ? 'active' : '' }}">
        <i class="bi bi-person-circle"></i>
        <span>My Profile</span>
    </a>
@endsection

@section('content')
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="page-title">Change Password</h1>
                <p class="page-subtitle">Update your account password</p>
            </div>
            <a href="{{ route('admin.profile') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-2"></i>Back to Profile
            </a>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="bi bi-shield-lock me-2 text-primary"></i>Change Your Password</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.change-password.post') }}">
                        @csrf

                        <!-- Current Password -->
                        <div class="mb-4">
                            <label for="current_password" class="form-label">Current Password <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-lock"></i></span>
                                <input type="password" class="form-control @error('current_password') is-invalid @enderror"
                                    id="current_password" name="current_password" required>
                                <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('current_password')">
                                    <i class="bi bi-eye" id="current_password_icon"></i>
                                </button>
                            </div>
                            @error('current_password')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- New Password -->
                        <div class="mb-4">
                            <label for="password" class="form-label">New Password <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                                <input type="password" class="form-control @error('password') is-invalid @enderror"
                                    id="password" name="password" required minlength="8">
                                <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('password')">
                                    <i class="bi bi-eye" id="password_icon"></i>
                                </button>
                            </div>
                            <small class="text-muted">Password must be at least 8 characters long</small>
                            @error('password')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Confirm New Password -->
                        <div class="mb-4">
                            <label for="password_confirmation" class="form-label">Confirm New Password <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                                <input type="password" class="form-control" id="password_confirmation"
                                    name="password_confirmation" required minlength="8">
                                <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('password_confirmation')">
                                    <i class="bi bi-eye" id="password_confirmation_icon"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Password Strength Info -->
                        <div class="alert alert-info">
                            <h6 class="alert-heading"><i class="bi bi-info-circle me-2"></i>Password Requirements</h6>
                            <ul class="mb-0 ps-3">
                                <li>Minimum 8 characters long</li>
                                <li>Should contain uppercase and lowercase letters</li>
                                <li>Should include numbers and special characters</li>
                            </ul>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="{{ route('admin.profile') }}" class="btn btn-secondary">
                                <i class="bi bi-x-circle me-2"></i>Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle me-2"></i>Change Password
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    function togglePassword(fieldId) {
        const field = document.getElementById(fieldId);
        const icon = document.getElementById(fieldId + '_icon');

        if (field.type === 'password') {
            field.type = 'text';
            icon.classList.remove('bi-eye');
            icon.classList.add('bi-eye-slash');
        } else {
            field.type = 'password';
            icon.classList.remove('bi-eye-slash');
            icon.classList.add('bi-eye');
        }
    }
</script>
@endsection
