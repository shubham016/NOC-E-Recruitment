@extends('layouts.app')
@section('sidebar-menu')
@section('title', 'Approver Dashboard')
@section('portal-name', 'Approver Portal')
@section('user-name', Auth::guard('approver')->user()->name)
@section('brand-icon', 'bi bi-clipboard-check')
@section('dashboard-route', route('approver.dashboard'))
@section('user-role', 'Application Approver')
@section('user-initial', strtoupper(substr(Auth::guard('approver')->user()->name, 0, 1)))
@section('logout-route', route('approver.logout'))

@section('sidebar-menu')
    <a href="{{ route('approver.dashboard') }}" class="sidebar-menu-item {{ request()->routeIs('approver.dashboard') ? 'active' : '' }}">
        <i class="bi bi-speedometer2"></i>
        <span>Dashboard</span>
    </a>
    <a href="{{ route('approver.assignedtome') }}" class="sidebar-menu-item {{ request()->routeIs('approver.assignedtome') ? 'active' : '' }}">
        <i class="bi bi-inbox"></i>
        <span>Assigned to Me</span>
    </a>
@endsection

@section('custom-styles')
<style>
    .dashboard-header {
        background: linear-gradient(135deg, #64748b 0%, #475569 100%);
        border-radius: 12px;
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
            <div class="col-md-8">
                <h2 class="fw-bold mb-2">
                    <i class="bi bi-clipboard-check me-2"></i>Welcome, {{ Auth::guard('approver')->user()->name }} Approver!
                </h2>
                <p class="mb-0 opacity-90">
                    <i class="bi bi-calendar3 me-2"></i>
                </p>
            </div>
            <div class="col-md-4 text-md-end">
                <div class="d-inline-block bg-white bg-opacity-10 rounded-3 px-4 py-3">
                    <div class="fw-bold fs-4"></div>
                    <small class="opacity-90">Assigned to Me</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-icon bg-info bg-opacity-10">
                    <i class="bi bi-inbox text-info"></i>
                </div>
                <h3 class="fw-bold mb-0"></h3>
                <p class="text-muted mb-0 small">Assigned to Me</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-icon bg-primary bg-opacity-10">
                    <i class="bi bi-check-circle text-primary"></i>
                </div>
                <h3 class="fw-bold mb-0"></h3>
                <p class="text-muted mb-0 small">Total Reviewed</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-icon bg-danger bg-opacity-10">
                    <i class="bi bi-x-circle-fill text-danger"></i>
                </div>
                <h3 class="fw-bold mb-0"></h3>
                <p class="text-muted mb-0 small">Rejected</p>
            </div>
        </div>
    </div>

    <div class="row g-4">
        

        <!-- Right Column -->
        <div class="col-lg-4">
            <!-- Recent Activity -->
            <div class="info-card">
                <h5><i class="bi bi-clock-history me-2"></i>Recent Activity</h5>

               

                    <div class="activity-item">
                        <div class="d-flex align-items-start gap-2">
                            <div class="
                             bg-opacity-10 rounded-circle p-2">
                                <i class="bi bi-check-circle 
                                "></i>
                            </div>
                            <div class="flex-grow-1">
                               
                            
                                <p class="mb-0 text-muted small">
                                    
                                </p>
                                <small class="text-muted">
                                    
                                </small>
                            </div>
                        </div>
                    </div>
               
            </div>

            <!-- Quick Actions -->
            <div class="info-card">
                <h5><i class="bi bi-lightning-fill me-2"></i>Quick Actions</h5>

                <div class="d-grid gap-2">
                    <a href="" class="btn btn-primary">
                        <i class="bi bi-eye me-2"></i>View All Applications
                    </a>
                    <a href="" class="btn btn-outline-info">
                        <i class="bi bi-inbox me-2"></i>Assigned to Me
                    </a>
                    <a href="" class="btn btn-outline-success">
                        <i class="bi bi-check-circle me-2"></i>Approved
                    </a>
                </div>
            </div>

           
        </div>
    </div>
</div>
@endsection
