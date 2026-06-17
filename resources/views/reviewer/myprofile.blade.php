@extends('layouts.reviewer')

@section('title', 'Reviewer Dashboard')

@section('portal-name', 'Reviewer Portal')
@section('brand-icon', 'bi bi-person-check')
@section('dashboard-route', route('reviewer.dashboard'))
@section('user-name', Auth::guard('reviewer')->user()->name)
@section('user-role', 'Application Reviewer')
@section('user-initial', strtoupper(substr(Auth::guard('reviewer')->user()->name, 0, 1)))
@section('logout-route', route('reviewer.logout'))

@section('sidebar-menu')
    <a href="{{ route('reviewer.dashboard') }}" class="sidebar-menu-item">
        <i class="bi bi-speedometer2"></i>
        <span>{{ __('reviewer.dashboard') }}</span>
    </a>
    <a href="{{ route('reviewer.applications.index') }}" class="sidebar-menu-item">
        <i class="bi bi-inbox"></i>
        <span>{{ __('reviewer.assigned_to_me') }}</span>
    </a>
    <a href="{{ route('reviewer.myprofile') }}" class="sidebar-menu-item active">
        <i class="bi bi-person"></i>
        <span>{{ __('reviewer.my_profile') }}</span>
    </a>
    <a href="{{ route('reviewer.notifications.index') }}" class="sidebar-menu-item">
        <i class="bi bi-bell"></i>
        <span>{{ __('reviewer.notifications') }}</span>
    </a>
    
@endsection

@section('custom-styles')
<style>
    .dashboard-header {
        background: linear-gradient(135deg, #16315c 0%, #16315c 100%);
        border-radius: 10px;
        padding: 2rem;
        color: white;
        margin-bottom: 2rem;
        box-shadow: 0 4px 12px rgba(201, 168, 76, 0.3);
    }

    .stat-card {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        border: 1px solid #e5e7eb;
        transition: all 0.3s ease;
        height: 100%;
    }

    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);
    }

    .stat-icon {
        width: 56px;
        height: 56px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        margin-bottom: 1rem;
    }

    .info-card {
        background: white;
        border-radius: 12px;
        padding: 2rem;
        border: 1px solid #e5e7eb;
        margin-bottom: 1.5rem;
        max-width: 600px;
        margin-left: auto;
        margin-right: auto;
    }

    .info-card h5 {
        color: #64748b;
        font-weight: 700;
        border-bottom: 2px solid #e5e7eb;
        padding-bottom: 0.75rem;
        margin-bottom: 1rem;
    }

    .info-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.75rem 0;
        border-bottom: 1px solid #f3f4f6;
    }

    .info-row:last-child {
        border-bottom: none;
    }

    .quick-action-btn {
        display: block;
        background: linear-gradient(135deg, #16315c 0%, #16315c 100%);
        color: white;
        border: none;
        border-radius: 8px;
        padding: 1rem 1.5rem;
        width: 100%;
        margin-bottom: 1rem;
        transition: all 0.3s ease;
        font-weight: 600;
        text-decoration: none;
        text-align: center;
    }

    .quick-action-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(201, 168, 76, 0.4);
        color: white;
        text-decoration: none;
    }
</style>
@endsection

@section('content')
<div class="container-fluid px-4 py-4">
    <!-- Dashboard Header -->
    <div class="info-card">
                <h5>
                    <i class="text-primary me-2"></i>{{ __('reviewer.reviewer_information') }}
                </h5>
                <div class="text-center mb-4">
                    <img 
                        src="{{ $user->photo ? asset('storage/' . $user->photo) : asset('images/default-user.png') }}" 
                        alt="Profile Image"
                        class="rounded-circle shadow"
                        style="width: 120px; height: 120px; object-fit: cover;"
                    >
                </div>
                
                <div class="info-row">
                    <span class="text-muted">{{ __('reviewer.employee_id') }}:</span>
                    <span class="fw-semibold">{{ $user->employee_id }}</span>
                </div>
                <div class="info-row">
                    <span class="text-muted">{{ __('reviewer.name') }}:</span>
                    <span class="fw-semibold">{{ $user->name }}</span>
                </div>
                <div class="info-row">
                    <span class="text-muted">{{ __('reviewer.phone_number') }}:</span>
                    <span class="fw-semibold">{{ $user->phone}}</span>
                </div>
                <div class="info-row">
                    <span class="text-muted">{{ __('reviewer.email') }}:</span>
                    <span class="fw-semibold">{{ $user->email}}</span>
                </div>
                <div class="info-row">
                    <span class="text-muted">{{ __('reviewer.department') }}:</span>
                    <span class="fw-semibold">{{ $user->department ?? 'N/A' }}</span>
                </div>
                <div class="info-row">
                    <span class="text-muted">{{ __('reviewer.designation') }}:</span>
                    <span class="fw-semibold">{{ $user->designation ?? 'N/A' }}</span>
                </div>
                <div class="info-row">
                    <span class="text-muted">{{ __('reviewer.status') }}:</span>
                    <span class="fw-semibold">{{ $user->status ?? 'N/A' }}</span>
                </div>
                @if($user->vacancy_id)
                <div class="info-row">
                    <span class="text-muted">{{ __('reviewer.assigned_job') }}:</span>
                    <span class="fw-semibold">{{ $user->vacancy->title ?? 'N/A' }}</span>
                </div>
                @endif
            </div>

            <!-- Change Password Section -->
            <div class="info-card">
                <h5>
                    <i class="text-primary"></i>{{ __('reviewer.change_password') }}
                </h5>

                @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif

                <form action="{{ route('reviewer.change.password') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">{{ __('reviewer.current_password') }}</label>
                        <input type="password" name="current_password" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">{{ __('reviewer.new_password') }}</label>
                        <input type="password" name="new_password" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">{{ __('reviewer.confirm_new_password') }}</label>
                        <input type="password" name="new_password_confirmation" class="form-control" required>
                    </div>

                    <button type="submit" class="quick-action-btn">
                        {{ __('reviewer.update_password') }}
                    </button>
                </form>
            </div>

    
</div>
@endsection
