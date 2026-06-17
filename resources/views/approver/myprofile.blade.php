@extends('layouts.approver')

@section('title', 'Approver Dashboard')

@section('portal-name', 'Approver Portal')
@section('brand-icon', 'bi bi-person-check')
@section('dashboard-route', route('approver.dashboard'))
@section('user-name', Auth::guard('approver')->user()->name)
@section('user-role', 'Application Approver')
@section('user-initial', strtoupper(substr(Auth::guard('approver')->user()->name, 0, 1)))
@section('logout-route', route('approver.logout'))

@section('sidebar-menu')
    <a href="{{ route('approver.dashboard') }}" class="sidebar-menu-item">
        <i class="bi bi-speedometer2"></i>
        <span>{{ __('approver.dashboard') }}</span>
    </a>
    <a href="{{ route('approver.assignedtome') }}" class="sidebar-menu-item">
        <i class="bi bi-inbox"></i>
        <span>{{ __('approver.assigned_to_me') }}</span>
    </a>
    <a href="{{ route('approver.myprofile') }}" class="sidebar-menu-item active">
        <i class="bi bi-person"></i>
        <span>{{ __('approver.my_profile') }}</span>
    </a>
    <a href="{{ route('approver.notifications.index') }}" class="sidebar-menu-item">
        <i class="bi bi-bell"></i>
        <span>{{ __('approver.notifications') }}</span>
    </a>
    
@endsection

@section('custom-styles')
<style>
    .dashboard-header {
        background: linear-gradient(135deg, #c9a84c 0%, #a07828 100%);
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
        background: linear-gradient(135deg, #173361 0%, #173361 100%);
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
                    <i class="text-primary me-2"></i>{{ __('approver.approver_information') }}
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
                <span class="text-muted">{{ __('approver.employee_id') }}:</span>
                <span class="fw-semibold">{{ $user->employee_id }}</span>
                </div>
                <div class="info-row">
                    <span class="text-muted">{{ __('approver.name') }}:</span>
                    <span class="fw-semibold">{{ $user->name }}</span>
                </div>
                <div class="info-row">
                    <span class="text-muted">{{ __('approver.phone_number') }}:</span>
                    <span class="fw-semibold">{{ $user->phone_number}}</span>
                </div>
                <div class="info-row">
                    <span class="text-muted">{{ __('approver.email') }}:</span>
                    <span class="fw-semibold">{{ $user->email}}</span>
                </div>
                <div class="info-row">
                    <span class="text-muted">{{ __('approver.department') }}:</span>
                    <span class="fw-semibold">{{ $user->department ?? 'N/A' }}</span>
                </div>
                <div class="info-row">
                    <span class="text-muted">{{ __('approver.designation') }}:</span>
                    <span class="fw-semibold">{{ $user->designation ?? 'N/A' }}</span>
                </div>
                <div class="info-row">
                    <span class="text-muted">{{ __('approver.status') }}:</span>
                    <span class="fw-semibold">{{ $user->status ?? 'N/A' }}</span>
                </div>
                @if($user->vacancy_id)
                <div class="info-row">
                    <span class="text-muted">{{ __('approver.assigned_job') }}:</span>
                    <span class="fw-semibold">{{ $user->vacancy->title ?? 'N/A' }}</span>
                </div>
                @endif
            </div>

            <!-- Change Password Section -->
            <div class="info-card">
                <h5>
                    <i class="text-primary"></i>{{ __('approver.change_password') }}
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

                <form action="{{ route('approver.change.password') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">{{ __('approver.current_password') }}</label>
                        <input type="password" name="current_password" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">{{ __('approver.new_password') }}</label>
                        <input type="password" name="new_password" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">{{ __('approver.confirm_new_password') }}</label>
                        <input type="password" name="new_password_confirmation" class="form-control" required>
                    </div>

                    <button type="submit" class="quick-action-btn">
                        {{ __('approver.update_password') }}
                    </button>
                </form>
            </div>

    
</div>
@endsection
