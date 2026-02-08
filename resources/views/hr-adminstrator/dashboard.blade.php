@extends('layouts.dashboard')

@section('title', 'HR Administrator Dashboard')

@php
    $hrAdmin = Auth::guard('hr_administrator')->user();
@endphp

@section('portal-name', 'HR Administrator Portal')
@section('brand-icon', 'bi bi-person-badge')
@section('dashboard-route', route('hr-administrator.dashboard'))
@section('user-name', $hrAdmin->name ?? 'Guest')
@section('user-role', 'HR Administrator')
@section('user-initial', $hrAdmin ? strtoupper(substr($hrAdmin->name, 0, 1)) : 'H')
@section('logout-route', route('hr-administrator.logout'))

@section('sidebar-menu')
    <a href="{{ route('hr-administrator.dashboard') }}" class="sidebar-menu-item active">
        <i class="bi bi-speedometer2"></i>
        <span>Dashboard</span>
    </a>
    <a href="{{ route('hr-administrator.jobs.index') }}" class="sidebar-menu-item">
        <i class="bi bi-briefcase"></i>
        <span>Vacancies</span>
    </a>
    <a href="{{ route('hr-administrator.jobs.create') }}" class="sidebar-menu-item">
        <i class="bi bi-plus-circle"></i>
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
@endsection

@section('content')
    <style>
        .dashboard-header {
            background: linear-gradient(135deg, #1e3a8a 0%, #2563eb 50%, #3b82f6 100%);
            border-radius: 16px;
            padding: 2rem;
            color: white;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            border: 1px solid #e5e7eb;
            transition: all 0.3s;
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
            font-size: 1.5rem;
        }

        .stat-value {
            font-size: 2rem;
            font-weight: 700;
            color: #1e3a8a;
        }

        .stat-label {
            color: #6b7280;
            font-size: 0.875rem;
            font-weight: 500;
        }

        .quick-action-card {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            text-align: center;
            border: 2px solid #e5e7eb;
            transition: all 0.3s;
            text-decoration: none;
            display: block;
        }

        .quick-action-card:hover {
            border-color: #3b82f6;
            transform: translateY(-4px);
            box-shadow: 0 8px 16px rgba(59, 130, 246, 0.15);
        }

        .quick-action-icon {
            width: 64px;
            height: 64px;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            font-size: 1.75rem;
        }

        .table-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            border: 1px solid #e5e7eb;
            overflow: hidden;
        }

        .table-card-header {
            padding: 1.25rem 1.5rem;
            background: #f9fafb;
            border-bottom: 1px solid #e5e7eb;
        }
    </style>

    <div class="container-fluid px-4 py-4">
        <!-- Dashboard Header -->
        <div class="dashboard-header">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <h1 class="h3 mb-2 fw-bold">Welcome back, {{ $hrAdmin->name ?? 'HR Administrator' }}!</h1>
                    <p class="mb-0 opacity-90">Manage recruitment activities and track application progress</p>
                </div>
                <div class="col-lg-4 text-end">
                    <a href="{{ route('hr-administrator.jobs.create') }}" class="btn btn-light btn-lg">
                        <i class="bi bi-plus-circle me-2"></i>Post New Vacancy
                    </a>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row g-4 mb-4">
            <div class="col-lg-3 col-md-6">
                <div class="stat-card">
                    <div class="d-flex align-items-center gap-3">
                        <div class="stat-icon" style="background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%); color: #1e40af;">
                            <i class="bi bi-briefcase-fill"></i>
                        </div>
                        <div>
                            <div class="stat-value">{{ $stats['total_jobs'] ?? 0 }}</div>
                            <div class="stat-label">Total Vacancies</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="stat-card">
                    <div class="d-flex align-items-center gap-3">
                        <div class="stat-icon" style="background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%); color: #065f46;">
                            <i class="bi bi-check-circle-fill"></i>
                        </div>
                        <div>
                            <div class="stat-value">{{ $stats['active_jobs'] ?? 0 }}</div>
                            <div class="stat-label">Active Vacancies</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="stat-card">
                    <div class="d-flex align-items-center gap-3">
                        <div class="stat-icon" style="background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%); color: #92400e;">
                            <i class="bi bi-file-earmark-text-fill"></i>
                        </div>
                        <div>
                            <div class="stat-value">{{ $stats['total_applications'] ?? 0 }}</div>
                            <div class="stat-label">Total Applications</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="stat-card">
                    <div class="d-flex align-items-center gap-3">
                        <div class="stat-icon" style="background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%); color: #991b1b;">
                            <i class="bi bi-clock-fill"></i>
                        </div>
                        <div>
                            <div class="stat-value">{{ $stats['pending_applications'] ?? 0 }}</div>
                            <div class="stat-label">Pending Review</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <h5 class="fw-bold mb-3" style="color: #1e3a8a;">Quick Actions</h5>
        <div class="row g-4 mb-4">
            <div class="col-lg-3 col-md-6">
                <a href="{{ route('hr-administrator.jobs.create') }}" class="quick-action-card">
                    <div class="quick-action-icon" style="background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); color: white;">
                        <i class="bi bi-plus-lg"></i>
                    </div>
                    <h6 class="fw-bold mb-1" style="color: #1e3a8a;">Post Vacancy</h6>
                    <small class="text-muted">Create a new job posting</small>
                </a>
            </div>
            <div class="col-lg-3 col-md-6">
                <a href="{{ route('hr-administrator.applications.index') }}" class="quick-action-card">
                    <div class="quick-action-icon" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white;">
                        <i class="bi bi-file-earmark-check"></i>
                    </div>
                    <h6 class="fw-bold mb-1" style="color: #1e3a8a;">Review Applications</h6>
                    <small class="text-muted">Process pending applications</small>
                </a>
            </div>
            <div class="col-lg-3 col-md-6">
                <a href="{{ route('hr-administrator.candidates.index') }}" class="quick-action-card">
                    <div class="quick-action-icon" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); color: white;">
                        <i class="bi bi-people"></i>
                    </div>
                    <h6 class="fw-bold mb-1" style="color: #1e3a8a;">View Candidates</h6>
                    <small class="text-muted">Browse candidate profiles</small>
                </a>
            </div>
            <div class="col-lg-3 col-md-6">
                <a href="{{ route('hr-administrator.reviewers.index') }}" class="quick-action-card">
                    <div class="quick-action-icon" style="background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%); color: white;">
                        <i class="bi bi-person-check"></i>
                    </div>
                    <h6 class="fw-bold mb-1" style="color: #1e3a8a;">Manage Reviewers</h6>
                    <small class="text-muted">Assign and track reviewers</small>
                </a>
            </div>
        </div>

        <div class="row g-4">
            <!-- Recent Jobs -->
            <div class="col-lg-6">
                <div class="table-card">
                    <div class="table-card-header d-flex justify-content-between align-items-center">
                        <h6 class="mb-0 fw-bold" style="color: #1e3a8a;">
                            <i class="bi bi-briefcase me-2"></i>Recent Vacancies
                        </h6>
                        <a href="{{ route('hr-administrator.jobs.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Title</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center">Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentJobs ?? [] as $job)
                                    <tr>
                                        <td>
                                            <a href="{{ route('hr-administrator.jobs.show', $job->id) }}" class="text-decoration-none fw-semibold" style="color: #1e3a8a;">
                                                {{ Str::limit($job->title, 30) }}
                                            </a>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge {{ $job->status == 'active' ? 'bg-success' : ($job->status == 'draft' ? 'bg-warning' : 'bg-secondary') }}">
                                                {{ ucfirst($job->status) }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <small class="text-muted">{{ $job->created_at->format('M d') }}</small>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center text-muted py-4">
                                            <i class="bi bi-briefcase fs-1 d-block mb-2 opacity-50"></i>
                                            No vacancies posted yet
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Recent Applications -->
            <div class="col-lg-6">
                <div class="table-card">
                    <div class="table-card-header d-flex justify-content-between align-items-center">
                        <h6 class="mb-0 fw-bold" style="color: #1e3a8a;">
                            <i class="bi bi-file-earmark-text me-2"></i>Recent Applications
                        </h6>
                        <a href="{{ route('hr-administrator.applications.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Candidate</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center">Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentApplications ?? [] as $application)
                                    <tr>
                                        <td>
                                            <div class="fw-semibold" style="color: #1e3a8a;">
                                                {{ $application->candidate->name ?? 'Unknown' }}
                                            </div>
                                            <small class="text-muted">{{ Str::limit($application->jobPosting->title ?? '', 25) }}</small>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge 
                                                {{ $application->status == 'pending' ? 'bg-warning' : '' }}
                                                {{ $application->status == 'shortlisted' ? 'bg-success' : '' }}
                                                {{ $application->status == 'rejected' ? 'bg-danger' : '' }}
                                                {{ $application->status == 'under_review' ? 'bg-info' : '' }}
                                            ">
                                                {{ ucfirst(str_replace('_', ' ', $application->status)) }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <small class="text-muted">{{ $application->created_at->format('M d') }}</small>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center text-muted py-4">
                                            <i class="bi bi-file-earmark fs-1 d-block mb-2 opacity-50"></i>
                                            No applications received yet
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection