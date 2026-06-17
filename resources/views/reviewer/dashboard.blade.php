@extends('layouts.reviewer')

@section('title', 'Reviewer Dashboard')

@section('portal-name', 'Reviewer Portal')
@section('brand-icon', 'bi bi-clipboard-check')
@section('dashboard-route', route('reviewer.dashboard'))
@section('user-name', Auth::guard('reviewer')->user()->name)
@section('user-role', 'Application Reviewer')
@section('user-initial', strtoupper(substr(Auth::guard('reviewer')->user()->name, 0, 1)))
@section('logout-route', route('reviewer.logout'))

@section('sidebar-menu')
    <a href="{{ route('reviewer.dashboard') }}" class="sidebar-menu-item active">
        <i class="bi bi-speedometer2"></i>
        <span>{{ __('reviewer.dashboard') }}</span>
    </a>
    <a href="{{ route('reviewer.applications.index') }}" class="sidebar-menu-item">
        <i class="bi bi-inbox"></i>
        <span>{{ __('reviewer.assigned_to_me') }}</span>
    </a>
    <a href="{{ route('reviewer.myprofile') }}" class="sidebar-menu-item">
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
        box-shadow: 0 4px 12px rgba(100, 116, 139, 0.3);
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

    .progress-card {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        border: 1px solid #e5e7eb;
    }

    .application-item {
        padding: 1rem;
        background: white;
        border-radius: 8px;
        border-left: 4px solid transparent;
        margin-bottom: 1rem;
        transition: all 0.3s ease;
    }

    .application-item:hover {
        transform: translateX(8px);
        box-shadow: 0 3px 10px rgba(0, 0, 0, 0.08);
    }

    .application-item.priority-high {
        border-left-color: #ef4444;
        background: linear-gradient(to right, rgba(239, 68, 68, 0.02) 0%, white 100%);
    }

    .application-item.priority-medium {
        border-left-color: #f59e0b;
        background: linear-gradient(to right, rgba(245, 158, 11, 0.02) 0%, white 100%);
    }

    .application-item.priority-low {
        border-left-color: #10b981;
        background: linear-gradient(to right, rgba(16, 185, 129, 0.02) 0%, white 100%);
    }

    .activity-item {
        padding: 1rem;
        border-bottom: 1px solid #f3f4f6;
    }

    .activity-item:last-child {
        border-bottom: none;
    }

    .info-card {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        border: 1px solid #e5e7eb;
        margin-bottom: 1.5rem;
    }

    .info-card h5 {
        color: #64748b;
        font-weight: 700;
        border-bottom: 2px solid #e5e7eb;
        padding-bottom: 0.75rem;
        margin-bottom: 1rem;
    }
</style>
@endsection

@section('content')
<div class="container-fluid px-4 py-4">

    <!-- Dashboard Header -->
        <div class="dashboard-header">
        <div class="row align-items-center">
            <!-- Left side: Welcome message -->
            <div class="col-md-8">
                <h2 class="fw-bold mb-2">
                    {{ __('reviewer.welcome') }}, {{ Auth::guard('reviewer')->user()->name }}!
                </h2>
            </div>

            <!-- Right side: Calendar -->
            <div class="col-md-4 text-end"> <!-- text-end aligns content to right -->
                <small>
                    <span id="english-date"></span> <br> <span id="nepali-date"></span>
                </small>
                
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="stat-card">
                <div class="stat-icon" style="background: rgba(234, 179, 8, 0.1); color: #eab308;">
                    <i class="bi bi-inbox"></i>
                </div>
                <h3 class="fw-bold mb-0">{{ $status['assigned'] }}</h3>
                <p class="text-muted mb-0 small">{{ __('reviewer.pending_review') }}</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card">
                <div class="stat-icon" style="background: rgba(34, 197, 94, 0.1); color: #22c55e;">
                    <i class="bi bi-check-circle"></i>
                </div>
                <h3 class="fw-bold mb-0">{{ $status['reviewed'] }}</h3>
                <p class="text-muted mb-0 small">{{ __('reviewer.reviewed_applications') }}</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card">
                <div class="stat-icon" style="background: rgba(239, 68, 68, 0.1); color: #ef4444;">
                    <i class="bi bi-x-circle"></i>
                </div>
                <h3 class="fw-bold mb-0">{{ $status['rejected'] }}</h3>
                <p class="text-muted mb-0 small">{{ __('reviewer.rejected_applications') }}</p>
            </div>
        </div>
        <!-- <div class="col-md-4">
            <div class="stat-card">
                <div class="stat-icon" style="background: rgba(16, 185, 129, 0.1); color: #10b981;">
                    <i class="bi bi-check-circle"></i>
                </div>
                <h3 class="fw-bold mb-0">{{ $status['approved'] }}</h3>
                <p class="text-muted mb-0 small">{{ __('reviewer.approved_applications') }}</p>
            </div>
        </div> -->
    </div>

    <div class="row g-4">
        <!-- Left Column -->
        <div>
            <!-- Today's Progress -->
            <div class="progress-card mb-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0 fw-bold">
                        <i class="bi text-primary me-2"></i>{{ __('reviewer.todays_progress') }}
                    </h5>
                    <span class="badge bg-success">{{ $todaystatus['reviewed_today'] }} / {{ $todaystatus['daily_target'] }}</span>
                </div>

                <div class="progress mb-3" style="height: 25px;">
                    <div class="progress-bar bg-success" role="progressbar"
                         style="width: {{ min($progressPercentage, 100) }}%">
                        {{ $progressPercentage }}%
                    </div>
                </div>

                <div class="row g-3 text-center">
                    <div class="col-6">
                        <div class="p-3 bg-success bg-opacity-10 rounded">
                            <div class="fw-bold text-success fs-5">{{ $todaystatus['reviewed_today'] }}</div>
                            <small class="text-muted">{{ __('reviewer.reviewed_today') }}</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-3 bg-warning bg-opacity-10 rounded">
                            <div class="fw-bold text-warning fs-5">{{ $todaystatus['pending_review'] }}</div>
                            <small class="text-muted">{{ __('reviewer.pending_review') }}</small>
                        </div>
                    </div>
                </div>
            </div>
    </div>
</div>
@endsection
