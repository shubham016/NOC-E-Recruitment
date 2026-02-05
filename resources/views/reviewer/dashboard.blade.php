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
    <a href="#" class="sidebar-menu-item">
        <i class="bi bi-check-circle"></i>
        <span>Reviewed Applications</span>
    </a>
    <a href="#" class="sidebar-menu-item">
        <i class="bi bi-star"></i>
        <span>Shortlisted</span>
    </a>
    <a href="#" class="sidebar-menu-item">
        <i class="bi bi-x-circle"></i>
        <span>Rejected</span>
    </a>
    <a href="#" class="sidebar-menu-item">
        <i class="bi bi-briefcase"></i>
        <span>Job Positions</span>
    </a>
    <a href="#" class="sidebar-menu-item">
        <i class="bi bi-bar-chart"></i>
        <span>My Statistics</span>
    </a>
    <a href="#" class="sidebar-menu-item">
        <i class="bi bi-gear"></i>
        <span>Settings</span>
    </a>
@endsection

@section('custom-styles')
    <style>
        /* Reviewer Dashboard - Optimized for Laptop Screens */
        .reviewer-header {
            background: linear-gradient(135deg, #64748b 0%, #475569 100%);
            border-radius: 12px;
            padding: 1.25rem;
            color: white;
            margin-bottom: 1.25rem;
            position: relative;
            overflow: hidden;
            box-shadow: 0 3px 10px rgba(100, 116, 139, 0.25);
        }

        .reviewer-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -10%;
            width: 250px;
            height: 250px;
            background: rgba(255, 255, 255, 0.06);
            border-radius: 50%;
        }

        .hero-content {
            position: relative;
            z-index: 1;
        }

        .task-card {
            border-left: 4px solid transparent;
            transition: all 0.3s ease;
            cursor: pointer;
            background: white;
            border-radius: 8px;
            margin-bottom: 0;
            padding: 1rem;
        }

        .task-card:hover {
            transform: translateX(5px);
            box-shadow: 0 3px 10px rgba(100, 116, 139, 0.1);
        }

        .task-card.high-priority {
            border-left-color: #ef4444;
            background: linear-gradient(to right, rgba(239, 68, 68, 0.02) 0%, white 100%);
        }

        .task-card.medium-priority {
            border-left-color: #f59e0b;
            background: linear-gradient(to right, rgba(245, 158, 11, 0.02) 0%, white 100%);
        }

        .task-card.low-priority {
            border-left-color: #10b981;
            background: linear-gradient(to right, rgba(16, 185, 129, 0.02) 0%, white 100%);
        }

        .task-card.normal-priority {
            border-left-color: #64748b;
            background: linear-gradient(to right, rgba(100, 116, 139, 0.02) 0%, white 100%);
        }

        .reviewer-stat-icon {
            width: 40px;
            height: 40px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.15rem;
            flex-shrink: 0;
        }

        .action-timeline {
            position: relative;
            padding-left: 1.5rem;
        }

        .action-timeline::before {
            content: '';
            position: absolute;
            left: 11px;
            top: 0;
            bottom: 0;
            width: 2px;
            background: linear-gradient(to bottom, #e2e8f0 0%, #e2e8f0 100%);
            border-radius: 10px;
        }

        .timeline-item {
            position: relative;
            margin-bottom: 1rem;
            padding-bottom: 1rem;
        }

        .timeline-item:last-child {
            margin-bottom: 0;
            padding-bottom: 0;
        }

        .timeline-dot {
            position: absolute;
            left: -1.5rem;
            top: 4px;
            width: 16px;
            height: 16px;
            border-radius: 50%;
            background: white;
            border: 3px solid #64748b;
            box-shadow: 0 0 0 2px rgba(100, 116, 139, 0.08);
            z-index: 2;
        }

        .daily-target-box {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
            border-radius: 10px;
            padding: 1rem;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .achievement-badge {
            background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%);
            border-radius: 10px;
            padding: 1.25rem;
            color: white;
            text-align: center;
            box-shadow: 0 3px 10px rgba(251, 191, 36, 0.25);
            position: relative;
            overflow: hidden;
        }

        .achievement-badge::before {
            content: '';
            position: absolute;
            top: -40%;
            right: -20%;
            width: 120px;
            height: 120px;
            background: rgba(255, 255, 255, 0.08);
            border-radius: 50%;
        }

        .achievement-content {
            position: relative;
            z-index: 1;
        }

        .stat-mini-card {
            background: white;
            border-radius: 8px;
            padding: 0.875rem;
            transition: all 0.3s ease;
            border: 1px solid #e2e8f0;
            height: 100%;
        }

        .stat-mini-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 4px 12px rgba(100, 116, 139, 0.12);
            border-color: #cbd5e1;
        }

        .priority-badge {
            font-size: 0.7rem;
            padding: 0.3rem 0.7rem;
            border-radius: 6px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
        }

        .review-action-btn {
            background: linear-gradient(135deg, #64748b 0%, #475569 100%);
            border: none;
            color: white;
            padding: 0.45rem 1.1rem;
            border-radius: 6px;
            font-weight: 600;
            transition: all 0.3s ease;
            font-size: 0.8rem;
        }

        .review-action-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 3px 10px rgba(100, 116, 139, 0.25);
            color: white;
            background: linear-gradient(135deg, #475569 0%, #334155 100%);
        }

        .progress-breakdown-card {
            background: white;
            border-radius: 8px;
            padding: 0.75rem;
            transition: all 0.3s ease;
            border: 1px solid #e2e8f0;
            text-align: center;
        }

        .progress-breakdown-card:hover {
            transform: scale(1.02);
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.06);
        }

        .candidate-avatar-icon {
            width: 36px;
            height: 36px;
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
            font-weight: 700;
            flex-shrink: 0;
        }

        /* Progress Circle Animation */
        .progress-circle {
            animation: progressAnimation 1.5s ease-out;
        }

        @keyframes progressAnimation {
            from {
                stroke-dashoffset: 263.89;
            }
        }

        /* Application Card Alignment Fixes */
        .application-content {
            display: flex;
            align-items: center;
            height: 100%;
        }

        .application-details {
            flex: 1;
        }

        .application-meta {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            justify-content: center;
            height: 100%;
        }

        /* Responsive */
        @media (max-width: 1399px) {
            .reviewer-header {
                padding: 1rem;
            }

            .stat-mini-card {
                padding: 0.75rem;
            }
        }

        @media (max-width: 991px) {
            .task-card:hover {
                transform: translateX(3px);
            }

            .daily-target-box {
                margin-top: 0.75rem;
            }

            .application-content {
                flex-direction: column;
                align-items: flex-start;
            }

            .application-meta {
                align-items: flex-start;
                margin-top: 0.75rem;
                width: 100%;
            }
        }

        @media (max-width: 767px) {
            .reviewer-header {
                padding: 0.875rem;
            }

            .reviewer-stat-icon {
                width: 36px;
                height: 36px;
                font-size: 1rem;
            }

            .stat-mini-card {
                padding: 0.75rem;
            }

            .task-card {
                padding: 0.75rem !important;
            }

            .priority-badge {
                font-size: 0.65rem;
                padding: 0.25rem 0.6rem;
            }

            .review-action-btn {
                padding: 0.375rem 0.875rem;
                font-size: 0.75rem;
            }

            .candidate-avatar-icon {
                width: 32px;
                height: 32px;
                font-size: 0.9rem;
            }

            .action-timeline {
                padding-left: 1.25rem;
            }

            .timeline-dot {
                left: -1.25rem;
                width: 14px;
                height: 14px;
            }
        }
    </style>
@endsection

@section('content')
    <!-- Header -->
    <div class="reviewer-header">
        <div class="hero-content">
            <div class="row align-items-center g-2">
                <div class="col-lg-7">
                    <div class="d-flex align-items-start gap-2">
                        <div class="bg-white bg-opacity-20 rounded-2 p-2">
                            <i class="bi bi-clipboard-check fs-4"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold mb-1">
                                Good {{ date('H') < 12 ? 'Morning' : (date('H') < 18 ? 'Afternoon' : 'Evening') }},
                                {{ Auth::guard('reviewer')->user()->name }}! 👋
                            </h6>
                            <p class="mb-0 opacity-90" style="font-size: 0.85rem;">
                                You have <strong>{{ $stats['pending'] }} applications</strong> waiting for review
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-5">
                    <div class="daily-target-box">
                        <div class="d-flex justify-content-center align-items-center gap-2 mb-2">
                            <i class="bi bi-target"></i>
                            <span class="fw-bold small">Daily Target</span>
                        </div>
                        <div class="text-center fw-bold mb-2" style="font-size: 1.75rem; line-height: 1;">
                            {{ $todayStats['reviewed_today'] }}<span class="opacity-75" style="font-size: 1rem;">/{{ $todayStats['daily_target'] }}</span>
                        </div>
                        <div class="progress mb-2"
                            style="height: 5px; background: rgba(255,255,255,0.25); border-radius: 10px;">
                            <div class="progress-bar bg-white" style="width: {{ $progressPercentage }}%; border-radius: 10px;"></div>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="opacity-90" style="font-size: 0.75rem;">{{ $progressPercentage }}% Complete</small>
                            <span class="badge bg-white {{ $progressPercentage >= 80 ? 'text-success' : 'text-warning' }} px-2 py-1" style="font-size: 0.7rem;">
                                <i class="bi bi-fire {{ $progressPercentage >= 80 ? 'text-success' : 'text-danger' }}"></i> {{ $progressPercentage >= 80 ? 'On Track' : 'Behind' }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row g-2 mb-3">
        <div class="col-6 col-lg-3">
            <div class="stat-mini-card">
                <div class="d-flex align-items-center gap-2">
                    <div class="reviewer-stat-icon bg-warning bg-opacity-10 text-warning">
                        <i class="bi bi-hourglass-split"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-0">{{ $stats['pending'] }}</h5>
                        <small class="text-muted" style="font-size: 0.75rem;">Pending</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="stat-mini-card">
                <div class="d-flex align-items-center gap-2">
                    <div class="reviewer-stat-icon bg-success bg-opacity-10 text-success">
                        <i class="bi bi-check-circle-fill"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-0">{{ $stats['total_reviewed'] }}</h5>
                        <small class="text-muted" style="font-size: 0.75rem;">Reviewed</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="stat-mini-card">
                <div class="d-flex align-items-center gap-2">
                    <div class="reviewer-stat-icon bg-primary bg-opacity-10 text-primary">
                        <i class="bi bi-star-fill"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-0">{{ $stats['shortlisted'] }}</h5>
                        <small class="text-muted" style="font-size: 0.75rem;">Shortlisted</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="stat-mini-card">
                <div class="d-flex align-items-center gap-2">
                    <div class="reviewer-stat-icon bg-info bg-opacity-10 text-info">
                        <i class="bi bi-graph-up-arrow"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-0">{{ $stats['approval_rate'] }}%</h5>
                        <small class="text-muted" style="font-size: 0.75rem;">Rate</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="row g-3">
        <!-- Task List -->
        <div class="col-12 col-xl-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-2">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <div>
                            <h6 class="mb-0 fw-bold">
                                <i class="bi bi-list-task text-warning me-2"></i>Applications To Review
                            </h6>
                            <small class="text-muted" style="font-size: 0.75rem;">Prioritized by deadline</small>
                        </div>
                        <div class="btn-group btn-group-sm" role="group">
                            <button type="button" class="btn btn-outline-secondary active" style="font-size: 0.75rem;">
                                All <span class="badge bg-secondary ms-1">{{ $stats['pending'] }}</span>
                            </button>
                            <button type="button" class="btn btn-outline-secondary" style="font-size: 0.75rem;">
                                High <span class="badge bg-danger ms-1">8</span>
                            </button>
                            <button type="button" class="btn btn-outline-secondary" style="font-size: 0.75rem;">
                                Recent
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
                    @forelse($pendingApplications as $application)
                        @php
                            // FIX: Round days to integer to avoid decimal display
                            $daysRemaining = (int) now()->diffInDays($application->job->deadline, false);
                            $priorityClass = 'normal-priority';
                            $priorityBadge = 'bg-secondary';
                            $priorityIcon = 'bi-circle-fill';
                            $priorityText = 'Normal';
                            $timeColor = 'text-muted';
                            
                            if ($daysRemaining <= 2) {
                                $priorityClass = 'high-priority';
                                $priorityBadge = 'bg-danger';
                                $priorityIcon = 'bi-star-fill';
                                $priorityText = 'High';
                                $timeColor = 'text-danger';
                            } elseif ($daysRemaining <= 5) {
                                $priorityClass = 'medium-priority';
                                $priorityBadge = 'bg-warning';
                                $priorityIcon = 'bi-dash-circle-fill';
                                $priorityText = 'Medium';
                                $timeColor = 'text-warning';
                            } elseif ($daysRemaining <= 10) {
                                $priorityClass = 'low-priority';
                                $priorityBadge = 'bg-success';
                                $priorityIcon = 'bi-check-circle-fill';
                                $priorityText = 'Low';
                                $timeColor = 'text-success';
                            }
                        @endphp
                        
                        <!-- {{ ucfirst($priorityText) }} Priority -->
                        <div class="task-card {{ $priorityClass }} {{ !$loop->last ? 'border-bottom' : '' }}">
                            <div class="application-content">
                                <div class="application-details">
                                    <div class="d-flex align-items-start gap-2">
                                        <div class="candidate-avatar-icon {{ $priorityBadge }} bg-opacity-10 text-{{ str_replace('bg-', '', $priorityBadge) }}">
                                            <i class="bi {{ $priorityIcon == 'bi-star-fill' ? 'bi-exclamation-triangle-fill' : ($priorityIcon == 'bi-dash-circle-fill' ? 'bi-person-fill' : ($priorityIcon == 'bi-check-circle-fill' ? 'bi-person-check-fill' : 'bi-file-earmark-person-fill')) }}"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="fw-bold mb-0" style="font-size: 0.875rem;">{{ $application->candidate->name }}</h6>
                                            <p class="text-muted mb-1" style="font-size: 0.75rem;">
                                                <i class="bi bi-briefcase me-1"></i>{{ $application->job->title }}
                                            </p>
                                            <div class="d-flex gap-1 flex-wrap">
                                                <span class="badge bg-light text-dark" style="font-size: 0.65rem;">{{ ucfirst($application->job->job_type) }}</span>
                                                <span class="badge bg-light text-dark" style="font-size: 0.65rem;">{{ $application->job->location }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="application-meta">
                                    <div class="text-end mb-2">
                                        <small class="text-muted d-block" style="font-size: 0.7rem;">Applied</small>
                                        <span class="fw-semibold" style="font-size: 0.8rem;">{{ $application->created_at->format('M d, Y') }}</span>
                                        <br>
                                        <small class="{{ $timeColor }}" style="font-size: 0.7rem;">
                                            <i class="bi {{ $daysRemaining <= 2 ? 'bi-alarm-fill' : 'bi-clock-fill' }} me-1"></i>{{ $daysRemaining }} {{ $daysRemaining == 1 ? 'day' : 'days' }}
                                        </small>
                                    </div>
                                    <div class="text-end">
                                        <span class="priority-badge {{ $priorityBadge }} text-{{ $priorityBadge == 'bg-warning' ? 'dark' : 'white' }} d-inline-block mb-2">
                                            <i class="bi {{ $priorityIcon }}"></i>{{ $priorityText }}
                                        </span>
                                        <br>
                                        <button class="btn btn-outline-secondary btn-sm review-btn" data-app-id="{{ $application->id }}" style="font-size: 0.8rem; padding: 0.45rem 1.1rem;">
                                            <i class="bi bi-eye me-1"></i>Review
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-5">
                            <i class="bi bi-inbox fs-1 text-muted"></i>
                            <p class="text-muted mt-3">No pending applications at the moment</p>
                        </div>
                    @endforelse
                </div>
                <div class="card-footer bg-light text-center py-2">
                    <small class="text-muted d-block mb-2" style="font-size: 0.75rem;">
                        Showing {{ $pendingApplications->count() }} of {{ $stats['pending'] }} applications
                    </small>
                    {{-- FIX: Always show button if there are pending applications --}}
                    @if($stats['pending'] > 0)
                    <a href="{{ route('reviewer.applications.index') }}" class="btn btn-outline-primary btn-sm px-3" style="font-size: 0.75rem;">
                        <i class="bi bi-arrow-right-circle me-1"></i>View All Applications
                        <span class="badge bg-primary ms-1">{{ $stats['pending'] }}</span>
                    </a>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-12 col-xl-4">
            <!-- Progress -->
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header text-white py-2 border-0"
                    style="background: linear-gradient(135deg, #64748b 0%, #475569 100%);">
                    <h6 class="mb-0 fw-bold" style="font-size: 0.875rem;">
                        <i class="bi bi-bar-chart-line me-1"></i>Today's Progress
                        <span class="badge bg-white text-dark float-end" style="font-size: 0.65rem;">Live</span>
                    </h6>
                </div>
                <div class="card-body text-center py-3">
                    <div class="position-relative d-inline-block mb-3">
                        <svg width="100" height="100">
                            <circle cx="50" cy="50" r="42" fill="none" stroke="#e2e8f0" stroke-width="10" />
                            <circle cx="50" cy="50" r="42" fill="none" stroke="#64748b" stroke-width="10"
                                stroke-dasharray="263.89" stroke-dashoffset="{{ 263.89 - (263.89 * $progressPercentage / 100) }}" transform="rotate(-90 50 50)"
                                stroke-linecap="round" class="progress-circle" />
                        </svg>
                        <div class="position-absolute top-50 start-50 translate-middle">
                            <h3 class="fw-bold mb-0" style="color: #64748b;">{{ $progressPercentage }}%</h3>
                            <small class="text-muted" style="font-size: 0.7rem;">{{ $todayStats['reviewed_today'] }}/{{ $todayStats['daily_target'] }}</small>
                        </div>
                    </div>

                    <div class="row g-2">
                        <div class="col-6">
                            <div class="progress-breakdown-card bg-success bg-opacity-10">
                                <i class="bi bi-check-circle-fill text-success fs-5"></i>
                                <div class="fw-bold fs-6">{{ $todayStats['approved_today'] }}</div>
                                <small class="text-muted" style="font-size: 0.7rem;">Approved</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="progress-breakdown-card bg-danger bg-opacity-10">
                                <i class="bi bi-x-circle-fill text-danger fs-5"></i>
                                <div class="fw-bold fs-6">{{ $todayStats['rejected_today'] }}</div>
                                <small class="text-muted" style="font-size: 0.7rem;">Rejected</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="progress-breakdown-card bg-warning bg-opacity-10">
                                <i class="bi bi-pause-circle-fill text-warning fs-5"></i>
                                <div class="fw-bold fs-6">{{ $todayStats['on_hold_today'] }}</div>
                                <small class="text-muted" style="font-size: 0.7rem;">On Hold</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="progress-breakdown-card bg-info bg-opacity-10">
                                <i class="bi bi-hourglass-split text-info fs-5"></i>
                                <div class="fw-bold fs-6">{{ $todayStats['daily_target'] - $todayStats['reviewed_today'] }}</div>
                                <small class="text-muted" style="font-size: 0.7rem;">Remaining</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Activity -->
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header bg-white border-0 py-2">
                    <h6 class="mb-0 fw-bold" style="font-size: 0.875rem;">
                        <i class="bi bi-clock-history text-secondary me-1"></i>Recent Activity
                    </h6>
                </div>
                <div class="card-body py-2">
                    <div class="action-timeline">
                        @forelse($recentActivity as $activity)
                            @php
                                $statusColor = match($activity->status) {
                                    'shortlisted' => '#10b981',
                                    'rejected' => '#ef4444',
                                    'under_review' => '#3b82f6',
                                    default => '#64748b'
                                };
                                $statusText = match($activity->status) {
                                    'shortlisted' => 'Shortlisted Candidate',
                                    'rejected' => 'Rejected Application',
                                    'under_review' => 'Reviewed Application',
                                    default => 'Updated Application'
                                };
                            @endphp
                            <!-- Activity Item -->
                            <div class="timeline-item">
                                <div class="timeline-dot" style="border-color: {{ $statusColor }};"></div>
                                <div class="ms-3">
                                    <p class="fw-semibold mb-0" style="font-size: 0.8rem;">{{ $statusText }}</p>
                                    <p class="text-muted mb-1" style="font-size: 0.7rem;">{{ $activity->candidate->name }} - {{ $activity->job->title }}</p>
                                    <small class="text-muted" style="font-size: 0.65rem;">{{ $activity->reviewed_at->diffForHumans() }}</small>
                                </div>
                            </div>
                        @empty
                            <p class="text-muted small text-center py-3">No recent activity</p>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Achievement -->
            <div class="achievement-badge">
                <div class="achievement-content">
                    <i class="bi bi-award-fill" style="font-size: 2.5rem;"></i>
                    <h6 class="mt-2 fw-bold mb-2" style="font-size: 0.9rem;">🌟 
                        @if($stats['approval_rate'] >= 90)
                            Top Reviewer!
                        @elseif($stats['approval_rate'] >= 70)
                            Great Performance!
                        @else
                            Keep Going!
                        @endif
                    </h6>
                    <p class="mb-2 small opacity-90" style="font-size: 0.75rem;">{{ $stats['total_reviewed'] }} total reviews with {{ $stats['approval_rate'] }}% approval rate</p>
                    <button class="btn btn-light btn-sm fw-semibold w-100 mb-2" style="font-size: 0.75rem;">
                        <i class="bi bi-graph-up-arrow me-1"></i>View Stats
                    </button>
                    <button class="btn btn-outline-light btn-sm w-100" style="font-size: 0.75rem;">
                        <i class="bi bi-share-fill me-1"></i>Share
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Review Modal -->
    <div class="modal fade" id="reviewModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-gradient text-white border-0" style="background: linear-gradient(135deg, #64748b 0%, #475569 100%);">
                    <h5 class="modal-title fw-bold">
                        <i class="bi bi-file-earmark-person me-2"></i>Application Review
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-0">
                    <!-- Loading Spinner -->
                    <div id="modalLoading" class="text-center py-5">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mt-3 text-muted">Loading application details...</p>
                    </div>

                    <!-- Application Content -->
                    <div id="modalContent" style="display: none;">
                        <div class="row g-0">
                            <!-- Left: Candidate Info -->
                            <div class="col-lg-8 border-end">
                                <div class="p-4">
                                    <h6 class="fw-bold mb-3">
                                        <i class="bi bi-person-circle text-primary me-2"></i>Candidate Information
                                    </h6>
                                    <div class="row g-3 mb-4">
                                        <div class="col-md-6">
                                            <label class="small text-muted">Full Name</label>
                                            <p class="fw-semibold mb-0" id="candidateName">-</p>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="small text-muted">Email</label>
                                            <p class="mb-0" id="candidateEmail">-</p>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="small text-muted">Phone</label>
                                            <p class="mb-0" id="candidatePhone">-</p>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="small text-muted">Address</label>
                                            <p class="mb-0" id="candidateAddress">-</p>
                                        </div>
                                    </div>

                                    <hr>

                                    <h6 class="fw-bold mb-3">
                                        <i class="bi bi-briefcase text-warning me-2"></i>Position Details
                                    </h6>
                                    <div class="row g-3 mb-4">
                                        <div class="col-md-12">
                                            <label class="small text-muted">Job Title</label>
                                            <p class="fw-semibold mb-0" id="jobTitle">-</p>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="small text-muted">Department</label>
                                            <p class="mb-0" id="jobDepartment">-</p>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="small text-muted">Location</label>
                                            <p class="mb-0" id="jobLocation">-</p>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="small text-muted">Type</label>
                                            <p class="mb-0" id="jobType">-</p>
                                        </div>
                                        <div class="col-md-12">
                                            <label class="small text-muted">Salary Range</label>
                                            <p class="mb-0" id="salaryRange">-</p>
                                        </div>
                                    </div>

                                    <hr>

                                    <h6 class="fw-bold mb-3">
                                        <i class="bi bi-file-text text-success me-2"></i>Cover Letter
                                    </h6>
                                    <div class="bg-light rounded p-3 mb-4">
                                        <p class="mb-0 small" id="coverLetter" style="white-space: pre-wrap;">-</p>
                                    </div>

                                    <h6 class="fw-bold mb-3">
                                        <i class="bi bi-file-earmark-pdf text-danger me-2"></i>Resume
                                    </h6>
                                    <div id="resumeSection">
                                        <button class="btn btn-outline-primary btn-sm">
                                            <i class="bi bi-download me-1"></i>Download Resume
                                        </button>
                                        <p class="text-muted small mt-2 mb-0">
                                            <i class="bi bi-info-circle me-1"></i>Resume download feature coming soon
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Right: Review Actions -->
                            <div class="col-lg-4">
                                <div class="p-4 bg-light h-100">
                                    <h6 class="fw-bold mb-3">
                                        <i class="bi bi-pencil-square text-info me-2"></i>Review Actions
                                    </h6>

                                    <div class="mb-4">
                                        <label class="small text-muted d-block mb-2">Application Status</label>
                                        <span class="badge bg-warning text-dark" id="currentStatus">Pending</span>
                                    </div>

                                    <div class="mb-4">
                                        <label class="small text-muted d-block mb-2">Applied Date</label>
                                        <p class="mb-0 small" id="appliedDate">-</p>
                                    </div>

                                    <hr>

                                    <form id="reviewForm">
                                        <input type="hidden" id="applicationId">

                                        <div class="mb-3">
                                            <label class="form-label fw-semibold">Reviewer Notes</label>
                                            <textarea class="form-control" id="reviewerNotes" rows="4" 
                                                placeholder="Add your notes here..."></textarea>
                                            <small class="text-muted">
                                                <i class="bi bi-info-circle"></i> Required for rejection
                                            </small>
                                        </div>

                                        <div class="d-grid gap-2">
                                            <button type="button" class="btn btn-success btn-action" data-status="shortlisted">
                                                <i class="bi bi-check-circle me-2"></i>Shortlist
                                            </button>
                                            <button type="button" class="btn btn-danger btn-action" data-status="rejected">
                                                <i class="bi bi-x-circle me-2"></i>Reject
                                            </button>
                                            <button type="button" class="btn btn-info btn-action" data-status="under_review">
                                                <i class="bi bi-arrow-repeat me-2"></i>Under Review
                                            </button>
                                        </div>
                                    </form>

                                    <div id="reviewedInfo" style="display: none;" class="mt-4 p-3 bg-white rounded border">
                                        <h6 class="fw-bold mb-2 small">
                                            <i class="bi bi-clock-history me-1"></i>Previous Review
                                        </h6>
                                        <p class="mb-1 small"><strong>Reviewed by:</strong> <span id="reviewedBy">-</span></p>
                                        <p class="mb-1 small"><strong>Date:</strong> <span id="reviewedAt">-</span></p>
                                        <p class="mb-0 small"><strong>Notes:</strong> <span id="previousNotes">-</span></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        // Review Modal Handling
        document.addEventListener('DOMContentLoaded', function () {
            const reviewButtons = document.querySelectorAll('.review-btn');
            const reviewModal = new bootstrap.Modal(document.getElementById('reviewModal'));
            const modalLoading = document.getElementById('modalLoading');
            const modalContent = document.getElementById('modalContent');

            // Open modal and load application data
            reviewButtons.forEach(button => {
                button.addEventListener('click', function () {
                    const appId = this.getAttribute('data-app-id');
                    loadApplicationData(appId);
                    reviewModal.show();
                });
            });

            // Load application data via AJAX
            function loadApplicationData(appId) {
                modalLoading.style.display = 'block';
                modalContent.style.display = 'none';

                // Fetch real data from API
                fetch(`/reviewer/applications/${appId}/details`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            populateModal(data.application);
                            modalLoading.style.display = 'none';
                            modalContent.style.display = 'block';
                        } else {
                            alert('Failed to load application details');
                            reviewModal.hide();
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Error loading application details');
                        reviewModal.hide();
                    });
            }

            // Populate modal with data
            function populateModal(data) {
                document.getElementById('applicationId').value = data.id;
                document.getElementById('candidateName').textContent = data.candidate_name;
                document.getElementById('candidateEmail').textContent = data.candidate_email;
                document.getElementById('candidatePhone').textContent = data.candidate_phone || 'Not provided';
                document.getElementById('candidateAddress').textContent = data.candidate_address || 'Not provided';
                document.getElementById('jobTitle').textContent = data.job_title;
                document.getElementById('jobDepartment').textContent = data.job_department || 'Not specified';
                document.getElementById('jobLocation').textContent = data.job_location || 'Not specified';
                document.getElementById('jobType').textContent = data.job_type || 'Not specified';
                document.getElementById('salaryRange').textContent = data.salary_range;
                document.getElementById('coverLetter').textContent = data.cover_letter || 'No cover letter provided';
                document.getElementById('appliedDate').textContent = data.applied_at;

                // Update status badge
                const statusBadge = document.getElementById('currentStatus');
                statusBadge.textContent = capitalizeFirst(data.status.replace('_', ' '));
                statusBadge.className = 'badge ' + getStatusClass(data.status);

                // Clear previous notes
                document.getElementById('reviewerNotes').value = '';

                // Show/hide reviewed info
                const reviewedInfo = document.getElementById('reviewedInfo');
                if (data.reviewed_by) {
                    document.getElementById('reviewedBy').textContent = data.reviewed_by;
                    document.getElementById('reviewedAt').textContent = data.reviewed_at;
                    document.getElementById('previousNotes').textContent = data.reviewer_notes || 'No notes';
                    reviewedInfo.style.display = 'block';
                } else {
                    reviewedInfo.style.display = 'none';
                }
            }

            // Get status badge class
            function getStatusClass(status) {
                const classes = {
                    'pending': 'bg-warning text-dark',
                    'under_review': 'bg-info text-white',
                    'shortlisted': 'bg-success text-white',
                    'rejected': 'bg-danger text-white',
                    'accepted': 'bg-primary text-white'
                };
                return classes[status] || 'bg-secondary text-white';
            }

            // Capitalize first letter
            function capitalizeFirst(str) {
                return str.charAt(0).toUpperCase() + str.slice(1);
            }

            // Handle review actions
            const actionButtons = document.querySelectorAll('.btn-action');
            actionButtons.forEach(button => {
                button.addEventListener('click', function () {
                    const status = this.getAttribute('data-status');
                    const appId = document.getElementById('applicationId').value;
                    const notes = document.getElementById('reviewerNotes').value;

                    // Validate notes for rejection
                    if (status === 'rejected' && !notes.trim()) {
                        alert('Please provide a reason for rejection');
                        document.getElementById('reviewerNotes').focus();
                        return;
                    }

                    updateApplicationStatus(appId, status, notes, this);
                });
            });

            // Update application status
            function updateApplicationStatus(appId, status, notes, button) {
                // Show loading state
                const originalText = button.innerHTML;
                button.disabled = true;
                button.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Processing...';

                // Disable all action buttons
                document.querySelectorAll('.btn-action').forEach(btn => btn.disabled = true);

                // Send AJAX request
                fetch(`/reviewer/applications/${appId}/status`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        status: status,
                        reviewer_notes: notes
                    })
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Show success message
                            showToast('Success', data.message, 'success');

                            // Close modal
                            reviewModal.hide();

                            // Reload page after a short delay
                            setTimeout(() => {
                                location.reload();
                            }, 1000);
                        } else {
                            alert('Failed to update application status');
                            button.disabled = false;
                            button.innerHTML = originalText;
                            document.querySelectorAll('.btn-action').forEach(btn => btn.disabled = false);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Error updating application status');
                        button.disabled = false;
                        button.innerHTML = originalText;
                        document.querySelectorAll('.btn-action').forEach(btn => btn.disabled = false);
                    });
            }

            // Toast notification function
            function showToast(title, message, type) {
                // Create toast element
                const toastHtml = `
                    <div class="toast align-items-center text-white bg-${type === 'success' ? 'success' : 'danger'} border-0" role="alert">
                        <div class="d-flex">
                            <div class="toast-body">
                                <strong>${title}</strong><br>${message}
                            </div>
                            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                        </div>
                    </div>
                `;

                // Add to body
                const toastContainer = document.createElement('div');
                toastContainer.className = 'toast-container position-fixed top-0 end-0 p-3';
                toastContainer.innerHTML = toastHtml;
                document.body.appendChild(toastContainer);

                // Show toast
                const toastElement = toastContainer.querySelector('.toast');
                const toast = new bootstrap.Toast(toastElement);
                toast.show();

                // Remove after hidden
                toastElement.addEventListener('hidden.bs.toast', () => {
                    toastContainer.remove();
                });
            }
        });
    </script>
    
@endsection