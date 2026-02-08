@extends('layouts.dashboard')

@section('title', 'HR Administrator Profile')

@php
    // Detect which guard is authenticated
    $currentUser = Auth::guard('admin')->user() ?? Auth::guard('hr_administrator')->user();
    $isAdmin = Auth::guard('admin')->check();
    $isHRAdmin = Auth::guard('hr_administrator')->check();
    
    // Set portal configuration based on user type
    $portalName = $isAdmin ? 'Admin Portal' : 'HR Administrator Portal';
    $brandIcon = $isAdmin ? 'bi bi-shield-check' : 'bi bi-briefcase-fill';
    $userRole = $isAdmin ? 'System Administrator' : 'HR Administrator';
    $dashboardRoute = $isAdmin ? route('admin.dashboard') : route('hr-administrator.dashboard');
    $logoutRoute = $isAdmin ? route('admin.logout') : route('hr-administrator.logout');
@endphp

@section('portal-name', $portalName)
@section('brand-icon', $brandIcon)
@section('dashboard-route', $dashboardRoute)
@section('user-name', $currentUser->name)
@section('user-role', $userRole)
@section('user-initial', strtoupper(substr($currentUser->name, 0, 1)))
@section('logout-route', $logoutRoute)

@section('sidebar-menu')
    @if($isAdmin)
        {{-- Super Admin Sidebar --}}
        <a href="{{ route('admin.dashboard') }}" class="sidebar-menu-item">
            <i class="bi bi-speedometer2"></i>
            <span>Dashboard</span>
        </a>
        <a href="{{ route('admin.jobs.create') }}" class="sidebar-menu-item">
            <i class="bi bi-briefcase"></i>
            <span>Post Vacancy</span>
        </a>
        <a href="{{ route('admin.applications.index') }}" class="sidebar-menu-item">
            <i class="bi bi-file-earmark-text"></i>
            <span>Applications</span>
        </a>
        <a href="{{ route('admin.candidates.index') }}" class="sidebar-menu-item">
            <i class="bi bi-people"></i>
            <span>Candidates</span>
        </a>
        <a href="{{ route('admin.hr-administrators.index') }}" class="sidebar-menu-item active">
            <i class="bi bi-person-badge"></i>
            <span>HR Administrators</span>
        </a>
        <a href="{{ route('admin.reviewers.index') }}" class="sidebar-menu-item">
            <i class="bi bi-person-check"></i>
            <span>Reviewers</span>
        </a>
        <a href="#" class="sidebar-menu-item">
            <i class="bi bi-bar-chart"></i>
            <span>Reports</span>
        </a>
        <a href="#" class="sidebar-menu-item">
            <i class="bi bi-gear"></i>
            <span>Settings</span>
        </a>
    @else
        {{-- HR Administrator Sidebar --}}
        <a href="{{ route('hr-administrator.dashboard') }}" class="sidebar-menu-item active">
            <i class="bi bi-speedometer2"></i>
            <span>Dashboard</span>
        </a>
        <a href="{{ route('hr-administrator.jobs.create') }}" class="sidebar-menu-item">
            <i class="bi bi-briefcase"></i>
            <span>Post Vacancy</span>
        </a>
        <a href="{{ route('hr-administrator.applications.index') }}" class="sidebar-menu-item">
            <i class="bi bi-file-earmark-text"></i>
            <span>Applications</span>
        </a>
        <a href="{{ route('hr-administrator.candidates.index') }}" class="sidebar-menu-item">
            <i class="bi bi-people"></i>
            <span>Candidates</span>
        </a>
        <a href="{{ route('hr-administrator.reviewers.index') }}" class="sidebar-menu-item">
            <i class="bi bi-person-check"></i>
            <span>Reviewers</span>
        </a>
        <a href="#" class="sidebar-menu-item">
            <i class="bi bi-bar-chart"></i>
            <span>Reports</span>
        </a>
        <a href="{{ route('hr-administrator.profile.show') }}" class="sidebar-menu-item">
            <i class="bi bi-gear"></i>
            <span>Settings</span>
        </a>
    @endif
@endsection

@section('content')
    <style>
        /* Profile Header Styles */
        .profile-header-card {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            border-radius: 16px;
            padding: 2rem;
            color: white;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-bottom: 2rem;
        }

        .profile-photo {
            width: 120px;
            height: 120px;
            border-radius: 16px;
            object-fit: cover;
            border: 4px solid rgba(255, 255, 255, 0.3);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        }

        .profile-photo-placeholder {
            width: 120px;
            height: 120px;
            border-radius: 16px;
            background: rgba(255, 255, 255, 0.2);
            border: 4px solid rgba(255, 255, 255, 0.3);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 3rem;
            font-weight: bold;
            color: white;
        }

        .profile-name {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            color: white;
        }

        .profile-email {
            font-size: 1.125rem;
            opacity: 0.95;
            margin-bottom: 0.25rem;
        }

        .profile-phone {
            font-size: 1rem;
            opacity: 0.9;
        }

        .status-badge-large {
            padding: 0.5rem 1.25rem;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.875rem;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: rgba(255, 255, 255, 0.2);
            border: 2px solid rgba(255, 255, 255, 0.3);
        }

        /* Stats Cards */
        .stat-card {
            background: white;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            padding: 1.5rem;
            text-align: center;
            transition: all 0.3s;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        }

        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
        }

        .stat-icon {
            width: 56px;
            height: 56px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.75rem;
            margin: 0 auto 1rem;
        }

        .stat-value {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 0.25rem;
        }

        .stat-label {
            font-size: 0.875rem;
            color: #6b7280;
            font-weight: 500;
        }

        /* Info Card */
        .info-card {
            background: white;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
            margin-bottom: 1.5rem;
        }

        .info-card-header {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 1.25rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid #f3f4f6;
        }

        .info-card-icon {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.25rem;
        }

        .info-card-title {
            font-size: 1.125rem;
            font-weight: 700;
            color: #1e3a8a;
            margin: 0;
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

        .info-label {
            font-weight: 600;
            color: #6b7280;
            font-size: 0.9375rem;
        }

        .info-value {
            font-weight: 600;
            color: #1f2937;
            font-size: 0.9375rem;
        }

        /* Job Table */
        .jobs-card {
            background: white;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
            overflow: hidden;
        }

        .jobs-card-header {
            padding: 1.5rem;
            background: #f9fafb;
            border-bottom: 1px solid #e5e7eb;
        }

        .jobs-table {
            width: 100%;
        }

        .jobs-table thead {
            background: #f9fafb;
        }

        .jobs-table th {
            padding: 1rem;
            font-weight: 600;
            color: #374151;
            font-size: 0.875rem;
            text-transform: uppercase;
            letter-spacing: 0.025em;
        }

        .jobs-table td {
            padding: 1rem;
            color: #1f2937;
            border-top: 1px solid #e5e7eb;
        }

        .jobs-table tr:hover {
            background: #f9fafb;
        }

        /* Permissions List */
        .permissions-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .permissions-list li {
            padding: 0.75rem 0;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            border-bottom: 1px solid #f3f4f6;
            font-size: 0.9375rem;
        }

        .permissions-list li:last-child {
            border-bottom: none;
        }

        .permission-icon {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            background: #d1fae5;
            color: #065f46;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.875rem;
        }

        /* Action Buttons */
        .action-btn-group {
            display: flex;
            gap: 0.75rem;
            flex-wrap: wrap;
        }

        .action-btn {
            padding: 0.625rem 1.5rem;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.875rem;
            border: none;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .action-btn-primary {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            color: white;
        }

        .action-btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.4);
            color: white;
        }

        .action-btn-danger {
            background: #ef4444;
            color: white;
        }

        .action-btn-danger:hover {
            background: #dc2626;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(239, 68, 68, 0.4);
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 3rem 1rem;
        }

        .empty-icon {
            width: 80px;
            height: 80px;
            margin: 0 auto 1rem;
            background: #f3f4f6;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #9ca3af;
            font-size: 2.5rem;
        }
    </style>

    <div class="container-fluid px-4 py-4">

        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="mb-3">
            <ol class="breadcrumb">
                @if($isAdmin)
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.dashboard') }}" style="color: #3b82f6;">Dashboard</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.hr-administrators.index') }}" style="color: #3b82f6;">HR Administrators</a>
                    </li>
                @else
                    <li class="breadcrumb-item">
                        <a href="{{ route('hr-administrator.dashboard') }}" style="color: #3b82f6;">Dashboard</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="#" style="color: #3b82f6;">Settings</a>
                    </li>
                @endif
                <li class="breadcrumb-item active">Profile</li>
            </ol>
        </nav>

        <!-- Success/Error Messages -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i><strong>Success!</strong> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i><strong>Error!</strong> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Profile Header Card -->
        <div class="profile-header-card">
            <div class="row align-items-center">
                <div class="col-auto">
                    @if($hrAdministrator->photo)
                        <img src="{{ asset('storage/' . $hrAdministrator->photo) }}" alt="{{ $hrAdministrator->name }}"
                            class="profile-photo">
                    @else
                        <div class="profile-photo-placeholder">
                            {{ strtoupper(substr($hrAdministrator->name, 0, 1)) }}
                        </div>
                    @endif
                </div>
                <div class="col">
                    <h1 class="profile-name">{{ $hrAdministrator->name }}</h1>
                    <div class="profile-email">
                        <i class="bi bi-envelope me-2"></i>{{ $hrAdministrator->email }}
                    </div>
                    @if($hrAdministrator->phone)
                        <div class="profile-phone">
                            <i class="bi bi-telephone me-2"></i>{{ $hrAdministrator->phone }}
                        </div>
                    @endif
                    <div class="mt-3">
                        <span class="status-badge-large">
                            <i class="bi bi-circle-fill" style="font-size: 0.5rem;"></i>
                            {{ ucfirst($hrAdministrator->status) }}
                        </span>
                    </div>
                </div>

                {{-- Action Buttons - Only show for Super Admin --}}
                @if($isAdmin)
                    <div class="col-auto">
                        <div class="action-btn-group">
                            <a href="{{ route('admin.hr-administrators.edit', $hrAdministrator->id) }}"
                                class="btn action-btn action-btn-primary">
                                <i class="bi bi-pencil"></i> Edit Profile
                            </a>
                            <div class="dropdown">
                                <button class="btn action-btn action-btn-primary dropdown-toggle" type="button"
                                    data-bs-toggle="dropdown">
                                    <i class="bi bi-three-dots-vertical"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li>
                                        <button type="button" class="dropdown-item" data-bs-toggle="modal"
                                            data-bs-target="#resetPasswordModal">
                                            <i class="bi bi-key me-2"></i>Reset Password
                                        </button>
                                    </li>
                                    <li>
                                        <button type="button" class="dropdown-item" data-bs-toggle="modal"
                                            data-bs-target="#toggleStatusModal">
                                            <i class="bi bi-arrow-repeat me-2"></i>Change Status
                                        </button>
                                    </li>
                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
                                    <li>
                                        <button type="button" class="dropdown-item text-danger" onclick="deleteAdmin()">
                                            <i class="bi bi-trash me-2"></i>Delete Account
                                        </button>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row g-3 mb-4">
            <div class="col-lg-3 col-md-6">
                <div class="stat-card">
                    <div class="stat-icon"
                        style="background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); color: white;">
                        <i class="bi bi-briefcase-fill"></i>
                    </div>
                    <div class="stat-value" style="color: #1e3a8a;">{{ $stats['total_jobs_posted'] }}</div>
                    <div class="stat-label">Total Vacancies Posted</div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="stat-card">
                    <div class="stat-icon"
                        style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white;">
                        <i class="bi bi-check-circle-fill"></i>
                    </div>
                    <div class="stat-value" style="color: #059669;">{{ $stats['active_jobs'] }}</div>
                    <div class="stat-label">Active Vacancies</div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="stat-card">
                    <div class="stat-icon"
                        style="background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); color: white;">
                        <i class="bi bi-x-circle-fill"></i>
                    </div>
                    <div class="stat-value" style="color: #dc2626;">{{ $stats['closed_jobs'] }}</div>
                    <div class="stat-label">Closed Vacancies</div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="stat-card">
                    <div class="stat-icon"
                        style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); color: white;">
                        <i class="bi bi-file-earmark-text-fill"></i>
                    </div>
                    <div class="stat-value" style="color: #d97706;">{{ $stats['total_applications'] }}</div>
                    <div class="stat-label">Total Applications</div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <!-- Recent Vacancy Postings -->
            <div class="col-lg-8">
                <div class="jobs-card">
                    <div class="jobs-card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0 fw-bold" style="color: #1e3a8a;">
                                <i class="bi bi-briefcase me-2"></i>Recent Vacancies
                            </h5>
                            <span class="badge bg-primary">{{ $recentJobs->count() }} Vacancies</span>
                        </div>
                    </div>
                    @if($recentJobs->count() > 0)
                        <table class="jobs-table">
                            <thead>
                                <tr>
                                    <th>Vacancy Title</th>
                                    <th>Department</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center">Posted</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentJobs as $job)
                                    <tr>
                                        <td>
                                            <div class="fw-semibold" style="color: #1e3a8a;">{{ $job->title }}</div>
                                            {{-- <small class="text-muted">{{ $job->advertisement_no }}</small>  --}}
                                        </td>
                                        <td>{{ $job->service_group }}</td>
                                        <td class="text-center">
                                            <span class="badge {{ $job->status == 'active' ? 'bg-success' : 'bg-secondary' }}">
                                                {{ ucfirst($job->status) }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <small>{{ $job->created_at->format('Y-m-d') }}</small>
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ $isAdmin ? route('admin.jobs.show', $job->id) : route('hr-administrator.jobs.show', $job->id) }}"
                                                class="btn btn-sm btn-outline-primary">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <div class="empty-state">
                            <div class="empty-icon">
                                <i class="bi bi-briefcase"></i>
                            </div>
                            <h5 class="fw-bold mb-2" style="color: #6b7280;">No Vacancy Posted Yet</h5>
                            <p class="text-muted mb-0">This administrator hasn't posted any job vacancies</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Account Information -->
                <div class="info-card">
                    <div class="info-card-header">
                        <div class="info-card-icon">
                            <i class="bi bi-info-circle"></i>
                        </div>
                        <h6 class="info-card-title">Account Information</h6>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Administrator ID</span>
                        <span class="info-value">#{{ str_pad($hrAdministrator->id, 4, '0', STR_PAD_LEFT) }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Status</span>
                        <span class="badge {{ $hrAdministrator->status == 'active' ? 'bg-success' : 'bg-secondary' }}">
                            {{ ucfirst($hrAdministrator->status) }}
                        </span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Account Created</span>
                        <span class="info-value">{{ $hrAdministrator->created_at->format('M d, Y') }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Last Updated</span>
                        <span class="info-value">{{ $hrAdministrator->updated_at->format('M d, Y') }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Member Since</span>
                        <span class="info-value">{{ $hrAdministrator->created_at->diffForHumans() }}</span>
                    </div>
                </div>

                <!-- Permissions -->
                <div class="info-card">
                    <div class="info-card-header">
                        <div class="info-card-icon">
                            <i class="bi bi-shield-check"></i>
                        </div>
                        <h6 class="info-card-title">Permissions</h6>
                    </div>
                    <ul class="permissions-list">
                        <li>
                            <div class="permission-icon">
                                <i class="bi bi-check"></i>
                            </div>
                            <span>Manage Vacancies</span>
                        </li>
                        <li>
                            <div class="permission-icon">
                                <i class="bi bi-check"></i>
                            </div>
                            <span>View Applications</span>
                        </li>
                        <li>
                            <div class="permission-icon">
                                <i class="bi bi-check"></i>
                            </div>
                            <span>Assign Reviewers</span>
                        </li>
                        <li>
                            <div class="permission-icon">
                                <i class="bi bi-check"></i>
                            </div>
                            <span>View Candidate Profiles</span>
                        </li>
                        <li>
                            <div class="permission-icon">
                                <i class="bi bi-check"></i>
                            </div>
                            <span>Generate Reports</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    {{-- Modals - Only show for Super Admin --}}
    @if($isAdmin)
        <!-- Reset Password Modal -->
        <div class="modal fade" id="resetPasswordModal" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow-lg" style="border-radius: 12px;">
                    <form action="{{ route('admin.hr-administrators.reset-password', $hrAdministrator->id) }}" method="POST">
                        @csrf
                        <div class="modal-header border-bottom">
                            <h5 class="modal-title fw-bold" style="color: #1e3a8a;">
                                <i class="bi bi-key me-2"></i>Reset Administrator Password
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body p-4">
                            <div class="mb-3">
                                <label class="form-label fw-semibold">New Password</label>
                                <input type="password" name="password" class="form-control" required>
                                <small class="text-muted">Minimum 8 characters with letters and numbers</small>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Confirm Password</label>
                                <input type="password" name="password_confirmation" class="form-control" required>
                            </div>
                            <div class="alert alert-warning mb-0">
                                <i class="bi bi-exclamation-triangle me-2"></i>
                                <small>The administrator will need to use this new password to log in.</small>
                            </div>
                        </div>
                        <div class="modal-footer border-top">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle me-2"></i>Reset Password
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Toggle Status Modal -->
        <div class="modal fade" id="toggleStatusModal" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow-lg" style="border-radius: 12px;">
                    <form action="{{ route('admin.hr-administrators.toggle-status', $hrAdministrator->id) }}" method="POST">
                        @csrf
                        <div class="modal-header border-bottom">
                            <h5 class="modal-title fw-bold" style="color: #1e3a8a;">
                                <i class="bi bi-arrow-repeat me-2"></i>Change Administrator Status
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body p-4">
                            <p class="mb-0">
                                Are you sure you want to {{ $hrAdministrator->status == 'active' ? 'deactivate' : 'activate' }}
                                <strong>{{ $hrAdministrator->name }}</strong>?
                            </p>
                            @if($hrAdministrator->status == 'active')
                                <div class="alert alert-warning mt-3 mb-0">
                                    <i class="bi bi-exclamation-triangle me-2"></i>
                                    <small>Deactivating will prevent this administrator from logging in.</small>
                                </div>
                            @endif
                        </div>
                        <div class="modal-footer border-top">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit"
                                class="btn btn-{{ $hrAdministrator->status == 'active' ? 'warning' : 'success' }}">
                                <i class="bi bi-check-circle me-2"></i>
                                {{ $hrAdministrator->status == 'active' ? 'Deactivate' : 'Activate' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Delete Form -->
        <form id="deleteForm" action="{{ route('admin.hr-administrators.destroy', $hrAdministrator->id) }}" method="POST"
            style="display: none;">
            @csrf
            @method('DELETE')
        </form>
    @endif

@endsection

@section('scripts')
    <script>
        @if($isAdmin)
            function deleteAdmin() {
                if (confirm('Are you sure you want to delete this administrator?\n\nThis action cannot be undone and will:\n- Remove all associated data\n- Delete all job postings created by this administrator\n- Remove all application records\n\nType DELETE to confirm.')) {
                    const confirmation = prompt('Type DELETE to confirm deletion:');
                    if (confirmation === 'DELETE') {
                        document.getElementById('deleteForm').submit();
                    } else {
                        alert('Deletion cancelled. You must type DELETE exactly to confirm.');
                    }
                }
            }
        @endif

        // Auto-dismiss alerts after 5 seconds
        setTimeout(() => {
            document.querySelectorAll('.alert-dismissible').forEach(alert => {
                const bsAlert = bootstrap.Alert.getInstance(alert) || new bootstrap.Alert(alert);
                if (bsAlert) bsAlert.close();
            });
        }, 5000);
    </script>
@endsection