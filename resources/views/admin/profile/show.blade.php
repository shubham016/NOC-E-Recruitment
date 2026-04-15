@extends('layouts.dashboard')

@section('title', 'My Profile')
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
    <a href="{{ route('admin.hr-administrators.index') }}" class="sidebar-menu-item {{ request()->routeIs('admin.hr-administrators.*') ? 'active' : '' }}">
        <i class="bi bi-person-badge"></i>
        <span>HR Administrators</span>
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
        <h1 class="page-title">My Profile</h1>
        <p class="page-subtitle">View and manage your profile information</p>
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

    <div class="row">
        <!-- Profile Information Card -->
        <div class="col-lg-4 mb-4">
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <div class="mb-3">
                        @if ($admin->photo)
                            <img src="{{ asset('storage/' . $admin->photo) }}" alt="{{ $admin->name }}"
                                class="rounded-circle" style="width: 120px; height: 120px; object-fit: cover;">
                        @else
                            <div class="rounded-circle mx-auto d-flex align-items-center justify-content-center"
                                style="width: 120px; height: 120px; background: linear-gradient(135deg, #c9a84c 0%, #a07828 100%); color: white; font-size: 3rem; font-weight: 600;">
                                {{ substr($admin->name, 0, 1) }}
                            </div>
                        @endif
                    </div>
                    <h4 class="mb-1">{{ $admin->name }}</h4>
                    <p class="text-muted mb-3">System Administrator</p>
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.profile.edit') }}" class="btn btn-outline-primary">
                            <i class="bi bi-pencil me-2"></i>Edit Profile
                        </a>
                        <a href="{{ route('admin.change-password') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-lock me-2"></i>Change Password
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Profile Details & Statistics -->
        <div class="col-lg-8 mb-4">
            <!-- Contact Information -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="bi bi-person-lines-fill me-2 text-primary"></i>Contact Information</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="text-muted small">Full Name</label>
                            <p class="mb-0 fw-semibold">{{ $admin->name }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small">Email Address</label>
                            <p class="mb-0 fw-semibold">{{ $admin->email }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small">Phone Number</label>
                            <p class="mb-0 fw-semibold">{{ $admin->phone ?? 'Not provided' }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small">Status</label>
                            <p class="mb-0">
                                <span class="badge bg-success">Active</span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistics -->
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="bi bi-bar-chart me-2 text-primary"></i>System Overview</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="d-flex align-items-center p-3 bg-light rounded">
                                <div class="flex-shrink-0">
                                    <div class="rounded-circle d-flex align-items-center justify-content-center"
                                        style="width: 50px; height: 50px; background: rgba(201, 168, 76, 0.2);">
                                        <i class="bi bi-briefcase text-warning" style="font-size: 1.5rem;"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h3 class="mb-0">{{ $stats['total_vacancies'] }}</h3>
                                    <p class="text-muted mb-0 small">Total Vacancies</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex align-items-center p-3 bg-light rounded">
                                <div class="flex-shrink-0">
                                    <div class="rounded-circle d-flex align-items-center justify-content-center"
                                        style="width: 50px; height: 50px; background: rgba(40, 167, 69, 0.2);">
                                        <i class="bi bi-check-circle text-success" style="font-size: 1.5rem;"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h3 class="mb-0">{{ $stats['active_vacancies'] }}</h3>
                                    <p class="text-muted mb-0 small">Active Vacancies</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex align-items-center p-3 bg-light rounded">
                                <div class="flex-shrink-0">
                                    <div class="rounded-circle d-flex align-items-center justify-content-center"
                                        style="width: 50px; height: 50px; background: rgba(13, 110, 253, 0.2);">
                                        <i class="bi bi-file-earmark-text text-primary" style="font-size: 1.5rem;"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h3 class="mb-0">{{ $stats['total_applications'] }}</h3>
                                    <p class="text-muted mb-0 small">Total Applications</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex align-items-center p-3 bg-light rounded">
                                <div class="flex-shrink-0">
                                    <div class="rounded-circle d-flex align-items-center justify-content-center"
                                        style="width: 50px; height: 50px; background: rgba(255, 193, 7, 0.2);">
                                        <i class="bi bi-clock-history text-warning" style="font-size: 1.5rem;"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h3 class="mb-0">{{ $stats['pending_applications'] }}</h3>
                                    <p class="text-muted mb-0 small">Pending Applications</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
