@extends('layouts.dashboard')

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
        <span>Dashboard</span>
    </a>
    <a href="{{ route('reviewer.applications.index') }}" class="sidebar-menu-item">
        <i class="bi bi-hourglass-split"></i>
        <span>Pending Reviews</span>
        <span class="badge bg-warning text-dark ms-auto">{{ $stats['pending'] }}</span>
    </a>
    <a href="{{ route('reviewer.applications.index', ['status' => 'approved']) }}" class="sidebar-menu-item">
        <i class="bi bi-check-circle"></i>
        <span>Approved</span>
        <span class="badge bg-success ms-auto">{{ $stats['approved'] }}</span>
    </a>
    <a href="{{ route('reviewer.applications.index', ['status' => 'rejected']) }}" class="sidebar-menu-item">
        <i class="bi bi-x-circle"></i>
        <span>Rejected</span>
        <span class="badge bg-danger ms-auto">{{ $stats['rejected'] }}</span>
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
                    <i class="bi bi-clipboard-check me-2"></i>Welcome, {{ Auth::guard('reviewer')->user()->name }}!
                </h2>
                <p class="mb-0 opacity-90">
                    <i class="bi bi-calendar3 me-2"></i>{{ now()->format('l, F d, Y') }}
                </p>
            </div>
            <div class="col-md-4 text-md-end">
                <div class="d-inline-block bg-white bg-opacity-10 rounded-3 px-4 py-3">
                    <div class="fw-bold fs-4">{{ $stats['pending'] }}</div>
                    <small class="opacity-90">Pending Reviews</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-icon bg-warning bg-opacity-10">
                    <i class="bi bi-hourglass-split text-warning"></i>
                </div>
                <h3 class="fw-bold mb-0">{{ $stats['pending'] }}</h3>
                <p class="text-muted mb-0 small">Pending Reviews</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-icon bg-primary bg-opacity-10">
                    <i class="bi bi-check-circle text-primary"></i>
                </div>
                <h3 class="fw-bold mb-0">{{ $stats['total_reviewed'] }}</h3>
                <p class="text-muted mb-0 small">Total Reviewed</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-icon bg-success bg-opacity-10">
                    <i class="bi bi-star-fill text-success"></i>
                </div>
                <h3 class="fw-bold mb-0">{{ $stats['shortlisted'] }}</h3>
                <p class="text-muted mb-0 small">Shortlisted</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-icon bg-info bg-opacity-10">
                    <i class="bi bi-graph-up text-info"></i>
                </div>
                <h3 class="fw-bold mb-0">{{ $stats['approval_rate'] }}%</h3>
                <p class="text-muted mb-0 small">Approval Rate</p>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Left Column -->
        <div class="col-lg-8">
            <!-- Today's Progress -->
            <div class="progress-card mb-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0 fw-bold">
                        <i class="bi bi-calendar-check text-primary me-2"></i>Today's Progress
                    </h5>
                    <span class="badge bg-primary">{{ $todayStats['reviewed_today'] }} / {{ $todayStats['daily_target'] }}</span>
                </div>

                <div class="progress mb-3" style="height: 25px;">
                    <div class="progress-bar bg-primary" role="progressbar"
                         style="width: {{ min($progressPercentage, 100) }}%">
                        {{ $progressPercentage }}%
                    </div>
                </div>

                <div class="row g-3 text-center">
                    <div class="col-4">
                        <div class="p-3 bg-success bg-opacity-10 rounded">
                            <div class="fw-bold text-success fs-5">{{ $todayStats['approved_today'] }}</div>
                            <small class="text-muted">Approved</small>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="p-3 bg-danger bg-opacity-10 rounded">
                            <div class="fw-bold text-danger fs-5">{{ $todayStats['rejected_today'] }}</div>
                            <small class="text-muted">Rejected</small>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="p-3 bg-warning bg-opacity-10 rounded">
                            <div class="fw-bold text-warning fs-5">{{ $todayStats['on_hold_today'] }}</div>
                            <small class="text-muted">On Hold</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pending Applications -->
            <div class="info-card">
                <h5><i class="bi bi-list-task me-2"></i>Priority Applications</h5>

                @forelse($pendingApplications as $application)
                    @php
                        $daysRemaining = $application->jobPosting ? (int) now()->diffInDays($application->jobPosting->deadline, false) : 0;
                        $priorityClass = '';
                        $priorityBadge = 'bg-secondary';
                        $priorityText = 'Normal';

                        if ($daysRemaining <= 2) {
                            $priorityClass = 'priority-high';
                            $priorityBadge = 'bg-danger';
                            $priorityText = 'High';
                        } elseif ($daysRemaining <= 5) {
                            $priorityClass = 'priority-medium';
                            $priorityBadge = 'bg-warning';
                            $priorityText = 'Medium';
                        } elseif ($daysRemaining <= 10) {
                            $priorityClass = 'priority-low';
                            $priorityBadge = 'bg-success';
                            $priorityText = 'Low';
                        }
                    @endphp

                    <div class="application-item {{ $priorityClass }}">
                        <div class="d-flex justify-content-between align-items-start">
                            <div class="flex-grow-1">
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <div class="bg-primary bg-opacity-10 rounded-circle p-2">
                                        <i class="bi bi-person-fill text-primary"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-0 fw-bold">{{ $application->name_english ?? 'N/A' }}</h6>
                                        <small class="text-muted">{{ $application->email ?? 'N/A' }}</small>
                                    </div>
                                </div>
                                <div class="ms-5">
                                    <p class="mb-1">
                                        <i class="bi bi-briefcase text-muted me-1"></i>
                                        <strong>{{ $application->jobPosting->title ?? 'N/A' }}</strong>
                                    </p>
                                    <div class="d-flex gap-2">
                                        <span class="badge {{ $priorityBadge }}">{{ $priorityText }} Priority</span>
                                        @if($application->jobPosting)
                                            <span class="badge bg-light text-dark">{{ $daysRemaining }} days left</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div>
                                <a href="{{ route('reviewer.applications.show', $application->id) }}" class="btn btn-sm btn-primary">
                                    <i class="bi bi-eye me-1"></i>Review
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-5">
                        <i class="bi bi-inbox display-4 text-muted"></i>
                        <p class="text-muted mt-3">No pending applications</p>
                    </div>
                @endforelse

                @if($pendingApplications->count() > 0)
                    <div class="text-center mt-3">
                        <a href="{{ route('reviewer.applications.index') }}" class="btn btn-outline-primary">
                            View All Applications <i class="bi bi-arrow-right ms-1"></i>
                        </a>
                    </div>
                @endif
            </div>
        </div>

        <!-- Right Column -->
        <div class="col-lg-4">
            <!-- Recent Activity -->
            <div class="info-card">
                <h5><i class="bi bi-clock-history me-2"></i>Recent Activity</h5>

                @forelse($recentActivity as $activity)
                    @php
                        $statusColors = [
                            'approved' => 'bg-info',
                            'shortlisted' => 'bg-success',
                            'rejected' => 'bg-danger',
                            'selected' => 'bg-primary',
                        ];
                        $statusColor = $statusColors[$activity->status] ?? 'bg-secondary';
                    @endphp

                    <div class="activity-item">
                        <div class="d-flex align-items-start gap-2">
                            <div class="{{ $statusColor }} bg-opacity-10 rounded-circle p-2">
                                <i class="bi bi-check-circle {{ str_replace('bg-', 'text-', $statusColor) }}"></i>
                            </div>
                            <div class="flex-grow-1">
                                <p class="mb-1 fw-semibold small">{{ ucfirst($activity->status) }}</p>
                                <p class="mb-1 text-muted small">{{ $activity->name_english ?? 'N/A' }}</p>
                                <p class="mb-0 text-muted small">{{ $activity->jobPosting->title ?? 'N/A' }}</p>
                                <small class="text-muted">{{ $activity->reviewed_at->diffForHumans() }}</small>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-4">
                        <i class="bi bi-inbox fs-3 text-muted"></i>
                        <p class="text-muted small mt-2 mb-0">No recent activity</p>
                    </div>
                @endforelse
            </div>

            <!-- Quick Actions -->
            <div class="info-card">
                <h5><i class="bi bi-lightning-fill me-2"></i>Quick Actions</h5>

                <div class="d-grid gap-2">
                    <a href="{{ route('reviewer.applications.index') }}" class="btn btn-primary">
                        <i class="bi bi-eye me-2"></i>View All Applications
                    </a>
                    <a href="{{ route('reviewer.applications.index', ['status' => 'pending']) }}" class="btn btn-outline-warning">
                        <i class="bi bi-hourglass-split me-2"></i>Pending Reviews
                    </a>
                    <a href="{{ route('reviewer.applications.index', ['status' => 'shortlisted']) }}" class="btn btn-outline-success">
                        <i class="bi bi-star me-2"></i>Shortlisted
                    </a>
                </div>
            </div>

            <!-- Performance Summary -->
            <div class="info-card">
                <h5><i class="bi bi-trophy me-2"></i>Your Performance</h5>

                <div class="mb-3">
                    <div class="d-flex justify-content-between mb-1">
                        <small class="text-muted">Approval Rate</small>
                        <small class="fw-bold">{{ $stats['approval_rate'] }}%</small>
                    </div>
                    <div class="progress" style="height: 8px;">
                        <div class="progress-bar bg-success" style="width: {{ $stats['approval_rate'] }}%"></div>
                    </div>
                </div>

                <div class="row g-2 text-center mt-3">
                    <div class="col-6">
                        <div class="p-2 bg-light rounded">
                            <div class="fw-bold text-primary">{{ $stats['total_reviewed'] }}</div>
                            <small class="text-muted">Reviewed</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-2 bg-light rounded">
                            <div class="fw-bold text-success">{{ $stats['shortlisted'] }}</div>
                            <small class="text-muted">Shortlisted</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
