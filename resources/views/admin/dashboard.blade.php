@extends('layouts.dashboard')

@section('title', 'Admin Dashboard')

@section('portal-name', 'Admin Portal')
@section('brand-icon', 'bi bi-shield-lock')
@section('dashboard-route', route('admin.dashboard'))
@section('user-name', Auth::guard('admin')->user()->name)
@section('user-role', 'Super Administrator')
@section('user-initial', strtoupper(substr(Auth::guard('admin')->user()->name, 0, 1)))
@section('logout-route', route('admin.logout'))

@section('sidebar-menu')
    <a href="{{ route('admin.dashboard') }}" class="sidebar-menu-item active">
        <i class="bi bi-speedometer2"></i>
        <span>Dashboard</span>
    </a>
    <a href="#" class="sidebar-menu-item">
        <i class="bi bi-briefcase"></i>
        <span>Job Management</span>
    </a>
    <a href="#" class="sidebar-menu-item">
        <i class="bi bi-file-earmark-text"></i>
        <span>Applications</span>
    </a>
    <a href="#" class="sidebar-menu-item">
        <i class="bi bi-people"></i>
        <span>Candidates</span>
    </a>
    <a href="#" class="sidebar-menu-item">
        <i class="bi bi-person-badge"></i>
        <span>Reviewers</span>
    </a>
    <a href="#" class="sidebar-menu-item">
        <i class="bi bi-bar-chart"></i>
        <span>Analytics</span>
    </a>
    <a href="#" class="sidebar-menu-item">
        <i class="bi bi-envelope"></i>
        <span>Messages</span>
    </a>
    <a href="#" class="sidebar-menu-item">
        <i class="bi bi-gear"></i>
        <span>Settings</span>
    </a>
@endsection

@section('custom-styles')
<style>
    /* Admin-specific gradient backgrounds */
    .admin-gradient-card {
        background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%);
        color: white;
    }

    .admin-stat-card {
        position: relative;
        overflow: hidden;
    }

    .admin-stat-card::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -20%;
        width: 200px;
        height: 200px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
    }

    .chart-container {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    }

    .activity-item {
        padding: 1rem;
        border-left: 3px solid transparent;
        transition: all 0.3s ease;
    }

    .activity-item:hover {
        background: #f8fafc;
        border-left-color: #2563eb;
    }

    .metric-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        background: rgba(37, 99, 235, 0.1);
        border-radius: 50px;
        font-weight: 600;
        color: #2563eb;
    }

    .admin-action-btn {
        background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%);
        border: none;
        color: white;
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        font-weight: 600;
        transition: all 0.3s ease;
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
    }

    .admin-action-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(37, 99, 235, 0.4);
    }
</style>
@endsection

@section('content')
    <!-- Admin Header with Gradient -->
    <div class="admin-gradient-card rounded-3 p-4 mb-4 shadow">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h1 class="h2 fw-bold mb-2">Welcome back, {{ Auth::guard('admin')->user()->name }}! 👋</h1>
                <p class="mb-0 opacity-90">Here's your recruitment system overview for today, {{ now()->format('F d, Y') }}</p>
            </div>
            {{-- <div class="col-md-4 text-md-end mt-3 mt-md-0">
                <button class="btn btn-light btn-lg px-4">
                    <i class="bi bi-plus-circle me-2"></i>Post New Job
                </button>
            </div> --}}
        </div>
    </div>

    <!-- Stats Grid - Different Layout -->
    <div class="row g-4 mb-4">
        <div class="col-12 col-md-6 col-xl-3">
            <div class="card admin-stat-card border-0 shadow-sm h-100" style="background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);">
                <div class="card-body text-white position-relative">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <p class="mb-1 opacity-90">Active Jobs</p>
                            <h2 class="fw-bold mb-0">24</h2>
                        </div>
                        <div class="bg-white bg-opacity-25 p-3 rounded">
                            <i class="bi bi-briefcase-fill fs-3"></i>
                        </div>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-arrow-up-short"></i>
                        <small>12% increase</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-6 col-xl-3">
            <div class="card admin-stat-card border-0 shadow-sm h-100" style="background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);">
                <div class="card-body text-white position-relative">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <p class="mb-1 opacity-90">Applications</p>
                            <h2 class="fw-bold mb-0">1,847</h2>
                        </div>
                        <div class="bg-white bg-opacity-25 p-3 rounded">
                            <i class="bi bi-file-earmark-text-fill fs-3"></i>
                        </div>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-arrow-up-short"></i>
                        <small>8% increase</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-6 col-xl-3">
            <div class="card admin-stat-card border-0 shadow-sm h-100" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%);">
                <div class="card-body text-white position-relative">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <p class="mb-1 opacity-90">Total Candidates</p>
                            <h2 class="fw-bold mb-0">5,234</h2>
                        </div>
                        <div class="bg-white bg-opacity-25 p-3 rounded">
                            <i class="bi bi-people-fill fs-3"></i>
                        </div>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-arrow-up-short"></i>
                        <small>15% increase</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-6 col-xl-3">
            <div class="card admin-stat-card border-0 shadow-sm h-100" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);">
                <div class="card-body text-white position-relative">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <p class="mb-1 opacity-90">Pending Reviews</p>
                            <h2 class="fw-bold mb-0">45</h2>
                        </div>
                        <div class="bg-white bg-opacity-25 p-3 rounded">
                            <i class="bi bi-clock-history fs-3"></i>
                        </div>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-exclamation-circle"></i>
                        <small>Needs attention</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts and Analytics Row -->
    <div class="row g-4 mb-4">
        <div class="col-12 col-lg-8">
            <div class="chart-container">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h5 class="fw-bold mb-1">Application Trends</h5>
                        <p class="text-muted small mb-0">Monthly overview of applications received</p>
                    </div>
                    <div class="btn-group" role="group">
                        <button type="button" class="btn btn-sm btn-outline-primary active">6M</button>
                        <button type="button" class="btn btn-sm btn-outline-primary">1Y</button>
                        <button type="button" class="btn btn-sm btn-outline-primary">All</button>
                    </div>
                </div>
                <!-- Placeholder for chart -->
                <div class="bg-light rounded p-5 text-center">
                    <i class="bi bi-graph-up tex                t-muted" style="font-size: 4rem;"></i>
                    <p class="text-muted mt-3 mb-0">Application Trends Chart</p>
                    <small class="text-muted">Integration with Chart.js available</small>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0 fw-bold">Department Overview</h5>
                </div>
                <div class="card-body">
                    <div class="mb-4">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Engineering</span>
                            <span class="fw-bold">45%</span>
                        </div>
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar" style="width: 45%; background: #2563eb;"></div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Design</span>
                            <span class="fw-bold">25%</span>
                        </div>
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar" style="width: 25%; background: #8b5cf6;"></div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Marketing</span>
                            <span class="fw-bold">18%</span>
                        </div>
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar" style="width: 18%; background: #10b981;"></div>
                        </div>
                    </div>

                    <div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Operations</span>
                            <span class="fw-bold">12%</span>
                        </div>
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar" style="width: 12%; background: #f59e0b;"></div>
                        </div>
                    </div>

                    <hr class="my-4">

                    <div class="d-grid gap-2">
                        <button class="btn admin-action-btn">
                            <i class="bi bi-bar-chart-line me-2"></i>View Full Analytics
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity & Quick Stats -->
    <div class="row g-4">
        <div class="col-12 col-lg-7">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-bold">
                            <i class="bi bi-activity text-primary me-2"></i>Recent Activity
                        </h5>
                        <a href="#" class="btn btn-sm btn-outline-primary">View All</a>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="activity-item border-bottom">
                        <div class="d-flex justify-content-between align-items-start">
                            <div class="d-flex gap-3">
                                <div class="bg-primary bg-opacity-10 text-primary rounded p-2">
                                    <i class="bi bi-person-plus-fill"></i>
                                </div>
                                <div>
                                    <p class="mb-1 fw-semibold">New candidate registered</p>
                                    <p class="text-muted small mb-0">Sarah Williams created an account</p>
                                </div>
                            </div>
                            <small class="text-muted">2 min ago</small>
                        </div>
                    </div>

                    <div class="activity-item border-bottom">
                        <div class="d-flex justify-content-between align-items-start">
                            <div class="d-flex gap-3">
                                <div class="bg-success bg-opacity-10 text-success rounded p-2">
                                    <i class="bi bi-file-earmark-check-fill"></i>
                                </div>
                                <div>
                                    <p class="mb-1 fw-semibold">Application reviewed</p>
                                    <p class="text-muted small mb-0">John Reviewer marked Michael Brown as shortlisted</p>
                                </div>
                            </div>
                            <small class="text-muted">15 min ago</small>
                        </div>
                    </div>

                    <div class="activity-item border-bottom">
                        <div class="d-flex justify-content-between align-items-start">
                            <div class="d-flex gap-3">
                                <div class="bg-warning bg-opacity-10 text-warning rounded p-2">
                                    <i class="bi bi-briefcase-fill"></i>
                                </div>
                                <div>
                                    <p class="mb-1 fw-semibold">New job posted</p>
                                    <p class="text-muted small mb-0">Senior Laravel Developer position created</p>
                                </div>
                            </div>
                            <small class="text-muted">1 hour ago</small>
                        </div>
                    </div>

                    <div class="activity-item border-bottom">
                        <div class="d-flex justify-content-between align-items-start">
                            <div class="d-flex gap-3">
                                <div class="bg-info bg-opacity-10 text-info rounded p-2">
                                    <i class="bi bi-envelope-fill"></i>
                                </div>
                                <div>
                                    <p class="mb-1 fw-semibold">Application received</p>
                                    <p class="text-muted small mb-0">New application for Frontend Developer position</p>
                                </div>
                            </div>
                            <small class="text-muted">2 hours ago</small>
                        </div>
                    </div>

                    <div class="activity-item">
                        <div class="d-flex justify-content-between align-items-start">
                            <div class="d-flex gap-3">
                                <div class="bg-danger bg-opacity-10 text-danger rounded p-2">
                                    <i class="bi bi-x-circle-fill"></i>
                                </div>
                                <div>
                                    <p class="mb-1 fw-semibold">Job closed</p>
                                    <p class="text-muted small mb-0">UX Designer position deadline reached</p>
                                </div>
                            </div>
                            <small class="text-muted">3 hours ago</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-5">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0 fw-bold">
                        <i class="bi bi-lightning-charge text-warning me-2"></i>Quick Actions
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-6">
                            <button class="btn btn-outline-primary w-100 py-3">
                                <i class="bi bi-plus-circle d-block fs-3 mb-2"></i>
                                <small>Post Job</small>
                            </button>
                        </div>
                        <div class="col-6">
                            <button class="btn btn-outline-success w-100 py-3">
                                <i class="bi bi-person-plus d-block fs-3 mb-2"></i>
                                <small>Add Reviewer</small>
                            </button>
                        </div>
                        <div class="col-6">
                            <button class="btn btn-outline-info w-100 py-3">
                                <i class="bi bi-file-earmark-bar-graph d-block fs-3 mb-2"></i>
                                <small>Reports</small>
                            </button>
                        </div>
                        <div class="col-6">
                            <button class="btn btn-outline-secondary w-100 py-3">
                                <i class="bi bi-gear d-block fs-3 mb-2"></i>
                                <small>Settings</small>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0 fw-bold">
                        <i class="bi bi-shield-check text-success me-2"></i>System Health
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom">
                        <div>
                            <p class="mb-1 fw-semibold">Server Status</p>
                            <small class="text-muted">All systems operational</small>
                        </div>
                        <span class="metric-badge">
                            <i class="bi bi-check-circle-fill"></i>
                            Active
                        </span>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom">
                        <div>
                            <p class="mb-1 fw-semibold">Database</p>
                            <small class="text-muted">Connection stable</small>
                        </div>
                        <span class="metric-badge">
                            <i class="bi bi-check-circle-fill"></i>
                            Healthy
                        </span>
                    </div>

                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="mb-1 fw-semibold">Active Users</p>
                            <small class="text-muted">Current session count</small>
                        </div>
                        <span class="metric-badge">
                            <i class="bi bi-people-fill"></i>
                            142
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection