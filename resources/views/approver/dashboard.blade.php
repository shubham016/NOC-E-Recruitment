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
    <a href="{{ route('approver.dashboard') }}" class="sidebar-menu-item active">
        <i class="bi bi-speedometer2"></i>
        <span>Dashboard</span>
    </a>
    <a href="{{ route('approver.assignedtome') }}" class="sidebar-menu-item">
        <i class="bi bi-inbox"></i>
        <span>Assigned to Me</span>
    </a>
    <a href="{{ route('approver.myprofile') }}" class="sidebar-menu-item">
        <i class="bi bi-person"></i>
        <span>My Profile</span>
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

    .info-row {
        display: flex;
        justify-content: space-between;
        padding: 0.75rem 0;
        border-bottom: 1px solid #f3f4f6;
    }

    .info-row:last-child {
        border-bottom: none;
    }

    .quick-action-btn {
        display: block;
        background: linear-gradient(135deg, #c9a84c 0%, #a07828 100%);
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
    <div class="dashboard-header">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h2 class="fw-bold mb-2">
                    Welcome, {{ Auth::guard('approver')->user()->name }}!
                </h2>
                <small><i class="mb-0 opacity-90">
                    <span id="english-date"></span> | <span id="nepali-date"></span>
                </i></small>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-icon" style="background: rgba(234, 179, 8, 0.1); color: #eab308;">
                    <i class="bi bi-inbox"></i>
                </div>
                <h3 class="fw-bold mb-0">{{ $stats['pending_applications'] }}</h3>
                <p class="text-muted mb-0 small">Pending Applications</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-icon" style="background: rgba(34, 197, 94, 0.1); color: #22c55e;">
                    <i class="bi bi-check-circle"></i>
                </div>
                <h3 class="fw-bold mb-0">{{ $stats['approved_applications'] }}</h3>
                <p class="text-muted mb-0 small">Approved</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-icon" style="background: rgba(239, 68, 68, 0.1); color: #ef4444;">
                    <i class="bi bi-x-circle"></i>
                </div>
                <h3 class="fw-bold mb-0">{{ $stats['rejected_applications'] }}</h3>
                <p class="text-muted mb-0 small">Rejected</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-icon" style="background: rgba(99, 102, 241, 0.1); color: #6366f1;">
                    <i class="bi bi-file-earmark-text"></i>
                </div>
                <h3 class="fw-bold mb-0">{{ $stats['total_applications'] }}</h3>
                <p class="text-muted mb-0 small">Total Applications</p>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Left Column -->
        <div class="col-lg-8">
            <!-- Quick Information -->
            <div class="info-card">
                <h5>
                    <i class="bi bi-info-circle text-primary me-2"></i>Approver Information
                </h5>
                <div class="info-row">
                    <span class="text-muted">Employee ID:</span>
                    <span class="fw-semibold">{{ $approver->employee_id }}</span>
                </div>
                <div class="info-row">
                    <span class="text-muted">Department:</span>
                    <span class="fw-semibold">{{ $approver->department ?? 'N/A' }}</span>
                </div>
                <div class="info-row">
                    <span class="text-muted">Designation:</span>
                    <span class="fw-semibold">{{ $approver->designation ?? 'N/A' }}</span>
                </div>
                <div class="info-row">
                    <span class="text-muted">Email:</span>
                    <span class="fw-semibold">{{ $approver->email }}</span>
                </div>
                @if($approver->vacancy_id)
                <div class="info-row">
                    <span class="text-muted">Assigned Job:</span>
                    <span class="fw-semibold">{{ $approver->vacancy->title ?? 'N/A' }}</span>
                </div>
                @endif
            </div>

            <!-- Welcome Message -->
            <!-- <div class="info-card">
                <h5>
                    <i class="bi bi-chat-left-quote text-success me-2"></i>Welcome Message
                </h5>
                <p class="mb-0 text-muted">
                    Welcome to the NOC E-Recruitment Approver Portal. As an approver, you have the responsibility to review and approve/reject applications assigned to you. Please ensure timely processing of all applications to maintain an efficient recruitment process.
                </p>
            </div> -->
        </div>

        <!-- Right Column -->
        <div class="col-lg-4">
            <!-- Quick Actions -->
            <div class="info-card">
                <h5>
                    <i class="bi bi-lightning text-warning me-2"></i>Quick Actions
                </h5>
                <a href="{{ route('approver.assignedtome') }}" class="quick-action-btn">
                    <i class="bi bi-inbox me-2"></i>Assigned Applications
                </a>
                <a href="{{ route('approver.assignedtome', ['status' => 'pending']) }}" class="quick-action-btn">
                    <i class="bi bi-clock-history me-2"></i>Pending Reviews
                </a>
                <a href="{{ route('approver.assignedtome', ['status' => 'approved']) }}" class="quick-action-btn">
                    <i class="bi bi-check-circle me-2"></i>Approved Applications
                </a>
            </div>

            <!-- System Status -->
            <div class="info-card">
                <h5>
                    <i class="bi bi-gear text-secondary me-2"></i>System Status
                </h5>
                <div class="info-row">
                    <span class="text-muted">Status:</span>
                    <span class="badge bg-success">Active</span>
                </div>
                <div class="info-row">
                    <span class="text-muted">Last Login:</span>
                    <span class="fw-semibold small">{{ now()->format('M d, Y h:i A') }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
