@extends('layouts.dashboard')

@section('title', 'Admin Dashboard')

@section('portal-name', 'Admin Portal')
@section('brand-icon', 'bi bi-shield-check')
@section('dashboard-route', route('admin.dashboard'))
@section('user-name', Auth::guard('admin')->user()->name)
@section('user-role', 'System Administrator')
@section('user-initial', strtoupper(substr(Auth::guard('admin')->user()->name, 0, 1)))
@section('logout-route', route('admin.logout'))

@section('sidebar-menu')
    <a href="{{ route('admin.dashboard') }}" class="sidebar-menu-item active">
        <i class="bi bi-speedometer2"></i>
        <span>Dashboard</span>
    </a>
    <a href="{{ route('admin.jobs.create') }}" class="sidebar-menu-item">
        <i class="bi bi-briefcase"></i>
        <span>Post Vacancy</span>
        <span class="badge bg-primary ms-auto">{{ $stats['total_jobs'] }}</span>
    </a>
    <a href="{{ route('admin.applications.index') }}" class="sidebar-menu-item">
        <i class="bi bi-file-earmark-text"></i>
        <span>Applications</span>
        <span class="badge bg-warning text-dark ms-auto">{{ $stats['pending_applications'] }}</span>
    </a>
    <a href="#" class="sidebar-menu-item">
        <i class="bi bi-people"></i>
        <span>Candidates</span>
        <span class="badge bg-info ms-auto">{{ $stats['total_candidates'] }}</span>
    </a>
    <a href="#" class="sidebar-menu-item">
        <i class="bi bi-person-badge"></i>
        <span>Reviewers</span>
        <span class="badge bg-success ms-auto">{{ $stats['active_reviewers'] }}</span>
    </a>
    <a href="#" class="sidebar-menu-item">
        <i class="bi bi-bar-chart"></i>
        <span>Reports</span>
    </a>
    <a href="#" class="sidebar-menu-item">
        <i class="bi bi-gear"></i>
        <span>Settings</span>
    </a>
@endsection

@section('custom-styles')
<style>
    .admin-header {
        background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
        border-radius: 12px;
        padding: 2rem;
        color: white;
        margin-bottom: 2rem;
        position: relative;
        overflow: hidden;
        box-shadow: 0 4px 15px rgba(99, 102, 241, 0.3);
    }

    .admin-header::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -10%;
        width: 300px;
        height: 300px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
    }

    .hero-content {
        position: relative;
        z-index: 1;
    }

    .stat-card {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        transition: all 0.3s ease;
        border: 1px solid #e5e7eb;
        height: 100%;
        position: relative;
        overflow: hidden;
    }

    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 4px;
        height: 100%;
        background: linear-gradient(180deg, var(--accent-color) 0%, transparent 100%);
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
    }

    .stat-card.primary::before { --accent-color: #6366f1; }
    .stat-card.success::before { --accent-color: #10b981; }
    .stat-card.warning::before { --accent-color: #f59e0b; }
    .stat-card.info::before { --accent-color: #3b82f6; }

    .stat-icon {
        width: 60px;
        height: 60px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        margin-bottom: 1rem;
    }

    .growth-indicator {
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
    }

    .growth-indicator.positive {
        background: #d1fae5;
        color: #065f46;
    }

    .growth-indicator.negative {
        background: #fee2e2;
        color: #991b1b;
    }

    .activity-item {
        padding: 1rem;
        border-bottom: 1px solid #f3f4f6;
        transition: background 0.2s;
    }

    .activity-item:last-child {
        border-bottom: none;
    }

    .activity-item:hover {
        background: #f9fafb;
    }

    .job-card {
        padding: 1rem;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        margin-bottom: 0.75rem;
        transition: all 0.3s ease;
    }

    .job-card:hover {
        border-color: #6366f1;
        box-shadow: 0 2px 8px rgba(99, 102, 241, 0.1);
    }

    .reviewer-card {
        background: white;
        border: 1px solid #e5e7eb;
        border-radius: 10px;
        padding: 1rem;
        transition: all 0.3s ease;
    }

    .reviewer-card:hover {
        transform: translateX(5px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    }

    .chart-container {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        border: 1px solid #e5e7eb;
    }
</style>
@endsection

@section('content')
<!-- Header -->
<div class="admin-header">
    <div class="hero-content">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h4 class="fw-bold mb-1">
                    <i class="bi bi-shield-check me-2"></i>
                    Welcome back, {{ Auth::guard('admin')->user()->name }}! 👋
                </h4>
                <p class="mb-0 opacity-90">Here's what's happening with your recruitment system today</p>
            </div>
            <div class="text-end">
                <h6 class="mb-1 fw-bold">{{ now()->format('l') }}</h6>
                <p class="mb-0 opacity-90">{{ now()->format('F d, Y') }}</p>
            </div>
        </div>
    </div>
</div>

<!-- Main Statistics Cards -->
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="stat-card primary">
            <div class="stat-icon bg-primary bg-opacity-10 text-primary">
                <i class="bi bi-briefcase-fill"></i>
            </div>
            <div class="d-flex justify-content-between align-items-start mb-2">
                <div>
                    <h3 class="fw-bold mb-0">{{ $stats['active_jobs'] }}</h3>
                    <p class="text-muted mb-0 small">Active Vacancy</p>
                </div>
                @if($growth['jobs_posted'] != 0)
                <span class="growth-indicator {{ $growth['jobs_posted'] > 0 ? 'positive' : 'negative' }}">
                    <i class="bi bi-arrow-{{ $growth['jobs_posted'] > 0 ? 'up' : 'down' }}"></i>
                    {{ abs($growth['jobs_posted']) }}%
                </span>
                @endif
            </div>
            <small class="text-muted">
                <i class="bi bi-plus-circle me-1"></i>{{ $thisMonth['jobs_posted'] }} posted this month
            </small>
        </div>
    </div>

    <div class="col-md-3">
        <div class="stat-card warning">
            <div class="stat-icon bg-warning bg-opacity-10 text-warning">
                <i class="bi bi-file-earmark-text-fill"></i>
            </div>
            <div class="d-flex justify-content-between align-items-start mb-2">
                <div>
                    <h3 class="fw-bold mb-0">{{ $stats['pending_applications'] }}</h3>
                    <p class="text-muted mb-0 small">Pending Reviews</p>
                </div>
                @if($growth['applications'] != 0)
                <span class="growth-indicator {{ $growth['applications'] > 0 ? 'positive' : 'negative' }}">
                    <i class="bi bi-arrow-{{ $growth['applications'] > 0 ? 'up' : 'down' }}"></i>
                    {{ abs($growth['applications']) }}%
                </span>
                @endif
            </div>
            <small class="text-muted">
                <i class="bi bi-inbox me-1"></i>{{ $thisMonth['applications'] }} received this month
            </small>
        </div>
    </div>

    <div class="col-md-3">
        <div class="stat-card info">
            <div class="stat-icon bg-info bg-opacity-10 text-info">
                <i class="bi bi-people-fill"></i>
            </div>
            <div class="d-flex justify-content-between align-items-start mb-2">
                <div>
                    <h3 class="fw-bold mb-0">{{ $stats['total_candidates'] }}</h3>
                    <p class="text-muted mb-0 small">Total Candidates</p>
                </div>
                @if($growth['candidates_registered'] != 0)
                <span class="growth-indicator {{ $growth['candidates_registered'] > 0 ? 'positive' : 'negative' }}">
                    <i class="bi bi-arrow-{{ $growth['candidates_registered'] > 0 ? 'up' : 'down' }}"></i>
                    {{ abs($growth['candidates_registered']) }}%
                </span>
                @endif
            </div>
            <small class="text-muted">
                <i class="bi bi-person-plus me-1"></i>{{ $thisMonth['candidates_registered'] }} registered this month
            </small>
        </div>
    </div>

    <div class="col-md-3">
        <div class="stat-card success">
            <div class="stat-icon bg-success bg-opacity-10 text-success">
                <i class="bi bi-person-badge-fill"></i>
            </div>
            <div class="d-flex justify-content-between align-items-start mb-2">
                <div>
                    <h3 class="fw-bold mb-0">{{ $stats['active_reviewers'] }}</h3>
                    <p class="text-muted mb-0 small">Active Reviewers</p>
                </div>
            </div>
            <small class="text-muted">
                <i class="bi bi-check-circle me-1"></i>{{ $stats['total_reviewers'] }} total reviewers
            </small>
        </div>
    </div>
</div>

<!-- Main Content Grid -->
<div class="row g-3">
    <!-- Left Column -->
    <div class="col-lg-8">
        <!-- Recent Applications -->
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-header bg-white border-0 py-3">
                <div class="d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 fw-bold">
                        <i class="bi bi-clock-history text-primary me-2"></i>Recent Applications
                    </h6>
                    <a href="#" class="btn btn-sm btn-outline-primary">View All</a>
                </div>
            </div>
            <div class="card-body p-0">
                @forelse($recentApplications as $application)
                <div class="activity-item">
                    <div class="d-flex justify-content-between align-items-start">
                        <div class="d-flex gap-3 flex-grow-1">
                            <div class="bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center" 
                                 style="width: 40px; height: 40px; flex-shrink: 0;">
                                <span class="fw-bold text-primary">
                                    {{ strtoupper(substr($application->candidate->name, 0, 1)) }}
                                </span>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-1 fw-semibold">{{ $application->candidate->name }}</h6>
                                <p class="mb-1 small text-muted">
                                    Applied for <strong>{{ $application->job->title }}</strong>
                                </p>
                                <small class="text-muted">
                                    <i class="bi bi-clock me-1"></i>{{ $application->created_at->diffForHumans() }}
                                </small>
                            </div>
                        </div>
                        <div class="text-end">
                            @php
                                $statusBadge = match($application->status) {
                                    'pending' => 'bg-warning text-dark',
                                    'under_review' => 'bg-info text-white',
                                    'shortlisted' => 'bg-success text-white',
                                    'rejected' => 'bg-danger text-white',
                                    default => 'bg-secondary text-white'
                                };
                            @endphp
                            <span class="badge {{ $statusBadge }}">
                                {{ ucfirst(str_replace('_', ' ', $application->status)) }}
                            </span>
                        </div>
                    </div>
                </div>
                @empty
                <div class="text-center py-5">
                    <i class="bi bi-inbox fs-1 text-muted"></i>
                    <p class="text-muted mt-3">No recent applications</p>
                </div>
                @endforelse
            </div>
        </div>

        <!-- Top Jobs by Applications -->
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0 py-3">
                <h6 class="mb-0 fw-bold">
                    <i class="bi bi-bar-chart-fill text-success me-2"></i>Top Jobs by Applications
                </h6>
            </div>
            <div class="card-body">
                @forelse($topJobs as $job)
                <div class="job-card">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-1 fw-semibold">{{ $job->title }}</h6>
                            <small class="text-muted">
                                <i class="bi bi-building me-1"></i>{{ $job->department }}
                                <span class="mx-2">•</span>
                                <i class="bi bi-geo-alt me-1"></i>{{ $job->location }}
                            </small>
                        </div>
                        <div class="text-end">
                            <h5 class="mb-0 fw-bold text-primary">{{ $job->applications_count }}</h5>
                            <small class="text-muted">Applications</small>
                        </div>
                    </div>
                </div>
                @empty
                <div class="text-center py-4">
                    <p class="text-muted mb-0">No jobs posted yet</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Right Column -->
    <div class="col-lg-4">
        <!-- Quick Actions -->
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-header bg-white border-0 py-3">
                <h6 class="mb-0 fw-bold">
                    <i class="bi bi-lightning-fill text-warning me-2"></i>Quick Actions
                </h6>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <button class="btn btn-primary" onclick="alert('Feature coming soon!')">
                        <i class="bi bi-plus-circle me-2"></i>Post New Job
                    </button>
                    <button class="btn btn-outline-primary" onclick="alert('Feature coming soon!')">
                        <i class="bi bi-person-plus me-2"></i>Add Reviewer
                    </button>
                    <button class="btn btn-outline-secondary" onclick="alert('Feature coming soon!')">
                        <i class="bi bi-download me-2"></i>Export Report
                    </button>
                </div>
            </div>
        </div>

        <!-- Active Reviewers -->
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-header bg-white border-0 py-3">
                <h6 class="mb-0 fw-bold">
                    <i class="bi bi-person-badge text-success me-2"></i>Active Reviewers
                </h6>
            </div>
            <div class="card-body">
                @forelse($reviewerStats as $reviewer)
                <div class="reviewer-card mb-2">
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <div class="bg-success bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center" 
                             style="width: 35px; height: 35px;">
                            <span class="fw-bold text-success small">
                                {{ strtoupper(substr($reviewer->name, 0, 1)) }}
                            </span>
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="mb-0 small fw-semibold">{{ $reviewer->name }}</h6>
                            <small class="text-muted" style="font-size: 0.7rem;">{{ $reviewer->email }}</small>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between small">
                        <span class="text-muted">
                            <i class="bi bi-check-circle me-1"></i>{{ $reviewer->total_reviewed }} reviewed
                        </span>
                        <span class="text-warning">
                            <i class="bi bi-hourglass me-1"></i>{{ $reviewer->pending }} pending
                        </span>
                    </div>
                </div>
                @empty
                <div class="text-center py-3">
                    <p class="text-muted small mb-0">No active reviewers</p>
                </div>
                @endforelse
            </div>
        </div>

        <!-- System Status -->
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0 py-3">
                <h6 class="mb-0 fw-bold">
                    <i class="bi bi-activity text-info me-2"></i>System Status
                </h6>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span class="small">Database</span>
                    <span class="badge bg-success">
                        <i class="bi bi-check-circle"></i> Healthy
                    </span>
                </div>
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span class="small">Storage</span>
                    <span class="badge bg-success">
                        <i class="bi bi-check-circle"></i> Healthy
                    </span>
                </div>
                <div class="d-flex justify-content-between align-items-center">
                    <span class="small">System Load</span>
                    <span class="badge bg-success">
                        <i class="bi bi-check-circle"></i> Normal
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    console.log('Admin Dashboard Loaded Successfully!');
</script>
@endsection