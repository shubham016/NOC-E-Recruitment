@extends('layouts.dashboard')

@section('title', 'My Profile')
@section('portal-name', 'HR Administrator Portal')
@section('brand-icon', 'bi bi-person-badge')
@section('dashboard-route', route('hr-administrator.dashboard'))
@section('logout-route', route('hr-administrator.logout'))

@section('sidebar-menu')
    <a href="{{ route('hr-administrator.dashboard') }}" class="sidebar-menu-item {{ request()->routeIs('hr-administrator.dashboard') ? 'active' : '' }}">
        <i class="bi bi-speedometer2"></i>
        <span>Dashboard</span>
    </a>
    <a href="{{ route('hr-administrator.vacancies.index') }}" class="sidebar-menu-item {{ request()->routeIs('hr-administrator.vacancies.*') ? 'active' : '' }}">
        <i class="bi bi-briefcase"></i>
        <span>Vacancy Management</span>
    </a>
    <a href="{{ route('hr-administrator.applications.index') }}" class="sidebar-menu-item {{ request()->routeIs('hr-administrator.applications.*') ? 'active' : '' }}">
        <i class="bi bi-file-earmark-text"></i>
        <span>Applications</span>
    </a>
    <a href="{{ route('hr-administrator.candidates.index') }}" class="sidebar-menu-item {{ request()->routeIs('hr-administrator.candidates.*') ? 'active' : '' }}">
        <i class="bi bi-people"></i>
        <span>Candidates</span>
    </a>
    <a href="{{ route('hr-administrator.reviewers.index') }}" class="sidebar-menu-item {{ request()->routeIs('hr-administrator.reviewers.*') ? 'active' : '' }}">
        <i class="bi bi-person-check"></i>
        <span>Reviewers</span>
    </a>
    <a href="{{ route('hr-administrator.profile.show') }}" class="sidebar-menu-item {{ request()->routeIs('hr-administrator.profile.*') || request()->routeIs('hr-administrator.change-password*') ? 'active' : '' }}">
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
                        @if ($hrAdministrator->photo)
                            <img src="{{ asset('storage/' . $hrAdministrator->photo) }}" alt="{{ $hrAdministrator->name }}"
                                class="rounded-circle" style="width: 120px; height: 120px; object-fit: cover;">
                        @else
                            <div class="rounded-circle mx-auto d-flex align-items-center justify-content-center"
                                style="width: 120px; height: 120px; background: linear-gradient(135deg, #c9a84c 0%, #a07828 100%); color: white; font-size: 3rem; font-weight: 600;">
                                {{ substr($hrAdministrator->name, 0, 1) }}
                            </div>
                        @endif
                    </div>
                    <h4 class="mb-1">{{ $hrAdministrator->name }}</h4>
                    <p class="text-muted mb-3">HR Administrator</p>
                    <div class="d-grid gap-2">
                        <a href="{{ route('hr-administrator.profile.edit') }}" class="btn btn-outline-primary">
                            <i class="bi bi-pencil me-2"></i>Edit Profile
                        </a>
                        <a href="{{ route('hr-administrator.change-password') }}" class="btn btn-outline-secondary">
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
                            <p class="mb-0 fw-semibold">{{ $hrAdministrator->name }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small">Email Address</label>
                            <p class="mb-0 fw-semibold">{{ $hrAdministrator->email }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small">Phone Number</label>
                            <p class="mb-0 fw-semibold">{{ $hrAdministrator->phone ?? 'Not provided' }}</p>
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
                    <h5 class="mb-0"><i class="bi bi-bar-chart me-2 text-primary"></i>My Statistics</h5>
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
                                    <h3 class="mb-0">{{ $stats['total_vacancies_posted'] }}</h3>
                                    <p class="text-muted mb-0 small">Total Vacancies Posted</p>
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
                                        style="width: 50px; height: 50px; background: rgba(220, 53, 69, 0.2);">
                                        <i class="bi bi-x-circle text-danger" style="font-size: 1.5rem;"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h3 class="mb-0">{{ $stats['closed_vacancies'] }}</h3>
                                    <p class="text-muted mb-0 small">Closed Vacancies</p>
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
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
