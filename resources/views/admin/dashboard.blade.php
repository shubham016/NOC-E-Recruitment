@extends('layouts.dashboard')

@section('title', 'Dashboard')

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
        <span class="badge bg-primary ms-auto"></span>
    </a>
    <a href="{{ route('admin.jobs.index') }}" class="sidebar-menu-item">
        <i class="bi bi-file-earmark-text"></i>
        <span>Vacancy List</span>
        <span class="badge bg-primary ms-auto">{{ $stats['total_jobs'] }}</span>
    </a>
    <a href="{{ route('admin.applications.index') }}" class="sidebar-menu-item">
        <i class="bi bi-file-earmark-text"></i>
        <span>Applications</span>
        <span class="badge bg-warning text-dark ms-auto">{{ $stats['pending_applications'] }}</span>
    </a>
    <a href="{{ route('admin.candidates.index') }}" class="sidebar-menu-item">
        <i class="bi bi-people"></i>
        <span>Candidates</span>
        <span class="badge bg-info ms-auto">{{ $stats['total_candidates'] }}</span>
    </a>

    <a href="{{ route('admin.reviewers.index') }}" class="sidebar-menu-item">
        <i class="bi bi-person-badge"></i>
        <span>Reviewers</span>
        <span class="badge bg-success ms-auto">{{ $stats['active_reviewers'] }}</span>
    </a>

    <a href="{{ route('admin.hr-administrators.index') }}" class="sidebar-menu-item">
        <i class="bi bi-person-badge"></i>
        <span>HR Administrators</span>
        <span class="badge bg-success ms-auto">{{ \App\Models\Admin::where('status', 'active')->count() }}</span>
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
        /* Perfect Alignment System */
        * {
            box-sizing: border-box;
        }

        :root {
            --primary: #6366f1;
            --primary-dark: #4f46e5;
            --secondary: #64748b;
            --success: #10b981;
            --warning: #f59e0b;
            --info: #3b82f6;
            --danger: #ef4444;

            --gray-50: #f9fafb;
            --gray-100: #f3f4f6;
            --gray-200: #e5e7eb;
            --gray-300: #d1d5db;
            --gray-400: #9ca3af;
            --gray-500: #64748b;
            --gray-600: #475569;
            --gray-700: #334155;
            --gray-800: #1e293b;
            --gray-900: #0f172a;

            --white: #ffffff;
            --border: 1px solid #e5e7eb;
            --radius: 12px;
            --shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
        }

        /* Page Header */
        .page-header {
            background: linear-gradient(135deg, #2196F3 0%, #1976d2 100%);
            padding: 32px;
            border-radius: var(--radius);
            margin-bottom: 32px;
            color: var(--white);
        }

        .header-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header-title {
            font-size: 28px;
            font-weight: 700;
            margin: 0 0 8px 0;
            line-height: 1.2;
        }

        .header-subtitle {
            font-size: 16px;
            opacity: 0.95;
            margin: 0;
        }

        .header-date {
            text-align: right;
            font-size: 14px;
            opacity: 0.95;
        }

        /* Stats Grid - Perfect Alignment */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 24px;
            margin-bottom: 32px;
        }

        .stat-box {
            background: var(--white);
            border: var(--border);
            border-radius: var(--radius);
            padding: 24px;
            transition: all 0.2s ease;
        }

        .stat-box:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            margin-bottom: 16px;
        }

        .stat-value {
            font-size: 32px;
            font-weight: 700;
            line-height: 1;
            margin-bottom: 8px;
            color: var(--gray-900);
        }

        .stat-label {
            font-size: 14px;
            color: var(--gray-600);
            font-weight: 500;
            margin-bottom: 12px;
        }

        .stat-meta {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding-top: 12px;
            border-top: 1px solid var(--gray-100);
        }

        .stat-badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 4px 10px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 600;
        }

        .badge-up {
            background: #d1fae5;
            color: #065f46;
        }

        .badge-down {
            background: #fee2e2;
            color: #991b1b;
        }

        .stat-text {
            font-size: 13px;
            color: var(--gray-500);
        }

        /* Content Layout - Perfect Grid */
        .content-layout {
            display: grid;
            grid-template-columns: 1fr 380px;
            gap: 24px;
        }

        /* Card Component */
        .card {
            background: var(--white);
            border: var(--border);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            margin-bottom: 24px;
        }

        .card-header {
            padding: 20px 24px;
            border-bottom: var(--border);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .card-title {
            font-size: 18px;
            font-weight: 600;
            color: var(--gray-900);
            margin: 0;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .card-link {
            font-size: 14px;
            font-weight: 500;
            color: #1565C0;
            text-decoration: none;
        }

        .card-link:hover {
            color: #1976d2;
        }

        .card-body {
            padding: 24px;
        }

        /* List Items - Perfect Alignment */
        .list-item {
            padding: 20px 24px;
            border-bottom: 1px solid var(--gray-100);
            display: flex;
            align-items: center;
            gap: 16px;
            transition: background 0.15s ease;
        }

        .list-item:hover {
            background: var(--gray-50);
        }

        .list-item:last-child {
            border-bottom: none;
        }

        .item-avatar {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            font-weight: 600;
            flex-shrink: 0;
        }

        .item-content {
            flex: 1;
            min-width: 0;
        }

        .item-name {
            font-size: 15px;
            font-weight: 600;
            color: var(--gray-900);
            margin: 0 0 4px 0;
        }

        .item-text {
            font-size: 14px;
            color: var(--gray-600);
            margin: 0;
        }

        .item-meta {
            font-size: 13px;
            color: var(--gray-500);
            margin: 6px 0 0 0;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .item-badge {
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 600;
            white-space: nowrap;
        }

        .badge-pending {
            background: #fef3c7;
            color: #92400e;
        }

        .badge-under_review {
            background: #dbeafe;
            color: #1e40af;
        }

        .badge-shortlisted {
            background: #d1fae5;
            color: #065f46;
        }

        .badge-rejected {
            background: #fee2e2;
            color: #991b1b;
        }

        /* Job Cards */
        .job-card {
            padding: 20px 24px;
            border-bottom: 1px solid var(--gray-100);
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 20px;
            transition: background 0.15s ease;
        }

        .job-card:hover {
            background: var(--gray-50);
        }

        .job-card:last-child {
            border-bottom: none;
        }

        .job-info {
            flex: 1;
        }

        .job-title {
            font-size: 15px;
            font-weight: 600;
            color: var(--gray-900);
            margin: 0 0 6px 0;
        }

        .job-meta {
            font-size: 13px;
            color: var(--gray-500);
            margin: 0;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .job-count-box {
            text-align: center;
            min-width: 80px;
        }

        .job-count {
            font-size: 28px;
            font-weight: 700;
            color: #1976d2;
            line-height: 1;
            margin: 0 0 4px 0;
        }

        .job-count-label {
            font-size: 11px;
            color: var(--gray-500);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: 600;
        }

        /* Sidebar Widgets */
        .widget {
            background: var(--white);
            border: var(--border);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            margin-bottom: 24px;
        }

        .widget-header {
            padding: 16px 20px;
            border-bottom: var(--border);
        }

        .widget-title {
            font-size: 16px;
            font-weight: 600;
            color: var(--gray-900);
            margin: 0;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .widget-body {
            padding: 16px 20px;
        }

        /* Action Buttons */
        .btn-action {
            width: 100%;
            padding: 12px 20px;
            font-size: 14px;
            font-weight: 600;
            border-radius: 8px;
            border: none;
            cursor: pointer;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            margin-bottom: 12px;
            text-decoration: none;
        }

        .btn-action:last-child {
            margin-bottom: 0;
        }

        .btn-primary {
            background: linear-gradient(135deg, #2196F3, #2196F3);
            color: var(--white);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 16px rgba(99, 102, 241, 0.3);
            color: var(--white);
        }

        .btn-secondary {
            background: var(--white);
            color: #2196F3;
            border: 2px solid var(--gray-200);
        }

        .btn-secondary:hover {
            border-color: #2196F3;
            background: #eef2ff;
            color: #1976d2;
        }

        /* Reviewer Items */
        .reviewer-item {
            padding: 16px 20px;
            border-bottom: 1px solid var(--gray-100);
            transition: background 0.15s ease;
        }

        .reviewer-item:hover {
            background: var(--gray-50);
        }

        .reviewer-item:last-child {
            border-bottom: none;
        }

        .reviewer-row {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 10px;
        }

        .reviewer-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            font-weight: 600;
            flex-shrink: 0;
        }

        .reviewer-info {
            flex: 1;
            min-width: 0;
        }

        .reviewer-name {
            font-size: 14px;
            font-weight: 600;
            color: var(--gray-900);
            margin: 0 0 2px 0;
        }

        .reviewer-email {
            font-size: 12px;
            color: var(--gray-500);
            margin: 0;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .reviewer-stats {
            display: flex;
            gap: 16px;
            font-size: 13px;
        }

        .stat-item {
            display: flex;
            align-items: center;
            gap: 6px;
        }

        /* Status Rows */
        .status-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 14px 0;
            border-bottom: 1px solid var(--gray-100);
        }

        .status-row:last-child {
            border-bottom: none;
            padding-bottom: 0;
        }

        .status-label {
            font-size: 14px;
            color: var(--gray-700);
            font-weight: 500;
        }

        .status-indicator {
            display: flex;
            align-items: center;
            gap: 6px;
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 600;
            background: #d1fae5;
            color: #065f46;
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 60px 24px;
        }

        .empty-icon {
            width: 64px;
            height: 64px;
            margin: 0 auto 16px;
            border-radius: 50%;
            background: var(--gray-100);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--gray-400);
            font-size: 28px;
        }

        .empty-title {
            font-size: 16px;
            font-weight: 600;
            color: var(--gray-900);
            margin: 0 0 6px 0;
        }

        .empty-text {
            font-size: 14px;
            color: var(--gray-500);
            margin: 0;
        }

        /* Responsive */
        @media (max-width: 1200px) {
            .content-layout {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 768px) {
            .stats-grid {
                grid-template-columns: 1fr;
            }

            .page-header {
                padding: 24px;
            }

            .header-title {
                font-size: 24px;
            }

            .stat-value {
                font-size: 28px;
            }
        }
    </style>
@endsection

@section('content')
    <!-- Page Header -->
    <div class="page-header">
        <div class="header-row">
            <div>
                <h1 class="header-title">Welcome back, {{ Auth::guard('admin')->user()->name }}!</h1>
                <p class="header-subtitle">Here's what's happening with your recruitment system today</p>
            </div>
            <div class="header-date">
                <div style="font-weight: 600; margin-bottom: 4px;">{{ now()->format('l') }}</div>
                <div>{{ now()->format('F d, Y') }}</div>
            </div>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="stats-grid">
        <!-- Stat 1 -->
        <div class="stat-box">
            <div class="stat-icon bg-primary bg-opacity-10 text-primary">
                <i class="bi bi-briefcase-fill"></i>
            </div>
            <div class="stat-value">{{ $stats['active_jobs'] }}</div>
            <div class="stat-label">Active Vacancies</div>
            <div class="stat-meta">
                @if($growth['jobs_posted'] != 0)
                    <span class="stat-badge {{ $growth['jobs_posted'] > 0 ? 'badge-up' : 'badge-down' }}">
                        <i class="bi bi-arrow-{{ $growth['jobs_posted'] > 0 ? 'up' : 'down' }}"></i>
                        {{ abs($growth['jobs_posted']) }}%
                    </span>
                @endif
                <span class="stat-text">{{ $thisMonth['jobs_posted'] }} this month</span>
            </div>
        </div>

        <!-- Stat 2 -->
        <div class="stat-box">
            <div class="stat-icon bg-warning bg-opacity-10 text-warning">
                <i class="bi bi-file-earmark-text-fill"></i>
            </div>
            <div class="stat-value">{{ $stats['pending_applications'] }}</div>
            <div class="stat-label">Pending Reviews</div>
            <div class="stat-meta">
                @if($growth['applications'] != 0)
                    <span class="stat-badge {{ $growth['applications'] > 0 ? 'badge-up' : 'badge-down' }}">
                        <i class="bi bi-arrow-{{ $growth['applications'] > 0 ? 'up' : 'down' }}"></i>
                        {{ abs($growth['applications']) }}%
                    </span>
                @endif
                <span class="stat-text">{{ $thisMonth['applications'] }} received</span>
            </div>
        </div>

        <!-- Stat 3 - Total Candidates -->
        <div class="stat-box">
            <div class="stat-icon bg-info bg-opacity-10 text-info">
                <i class="bi bi-people-fill"></i>
            </div>
            <div class="stat-value">{{ $stats['total_candidates'] }}</div>
            <div class="stat-label">Registered Candidates</div>
            <div class="stat-meta">
                @if($growth['candidates'] != 0)
                    <span class="stat-badge {{ $growth['candidates'] > 0 ? 'badge-up' : 'badge-down' }}">
                        <i class="bi bi-arrow-{{ $growth['candidates'] > 0 ? 'up' : 'down' }}"></i>
                        {{ abs($growth['candidates']) }}%
                    </span>
                @endif
                <span class="stat-text">{{ $thisMonth['candidates'] }} this month</span>
            </div>
        </div>

        <!-- Stat 4 -->
        <div class="stat-box">
            <div class="stat-icon bg-success bg-opacity-10 text-success">
                <i class="bi bi-person-badge-fill"></i>
            </div>
            <div class="stat-value">{{ $stats['active_reviewers'] }}</div>
            <div class="stat-label">Active Reviewers</div>
            <div class="stat-meta">
                <span class="stat-text">{{ $stats['total_reviewers'] }} total reviewers</span>
            </div>
        </div>
    </div>

    <!-- Content Layout -->
    <div class="content-layout">
        <!-- Main Content -->
        <div>
            <!-- Recent Applications -->
            <!-- Recent Applications -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="bi bi-clock-history text-primary"></i>
                        Recent Applications
                    </h3>
                    <a href="{{ route('admin.applications.index') }}" class="card-link">View All →</a>
                </div>
                <div>
                    @forelse($recentApplications as $application)
                        <div class="list-item">
                            <div class="item-avatar bg-primary bg-opacity-10 text-primary">
                                {{ strtoupper(substr($application->candidate->name ?? 'U', 0, 1)) }}
                            </div>
                            <div class="item-content">
                                <h4 class="item-name">{{ $application->candidate->name ?? 'Unknown' }}</h4>
                                <p class="item-text">Applied for
                                    <strong>{{ $application->jobPosting->title ?? 'Position' }}</strong>
                                </p>
                                <p class="item-meta">
                                    <i class="bi bi-clock"></i>
                                    {{ $application->created_at->diffForHumans() }}
                                </p>
                            </div>
                            <span class="item-badge badge-{{ $application->status }}">
                                {{ ucfirst(str_replace('_', ' ', $application->status)) }}
                            </span>
                        </div>
                    @empty
                        <div class="empty-state">
                            <div class="empty-icon">
                                <i class="bi bi-inbox"></i>
                            </div>
                            <h4 class="empty-title">No Recent Applications</h4>
                            <p class="empty-text">New applications will appear here</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Top Jobs -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="bi bi-trophy-fill text-warning"></i>
                        Total Vacancies by Applications
                    </h3>
                </div>
                <div>
                    @forelse($topJobs as $job)
                        <div class="job-card">
                            <div class="job-info">
                                <h4 class="job-title">{{ $job->title }}</h4>
                                <p class="job-meta">
                                    <span>
                                        <i class="bi bi-building"></i>
                                        {{ $job->department }}
                                    </span>
                                    <span>
                                        <i class="bi bi-geo-alt"></i>
                                        {{ $job->location }}
                                    </span>
                                </p>
                            </div>
                            <div class="job-count-box">
                                <div class="job-count">{{ $job->applications_count }}</div>
                                <div class="job-count-label">Applications</div>
                            </div>
                        </div>
                    @empty
                        <div class="empty-state">
                            <div class="empty-icon">
                                <i class="bi bi-briefcase"></i>
                            </div>
                            <h4 class="empty-title">No Vacancy Posted</h4>
                            <p class="empty-text">Create your first vacancy posting</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div>
            <!-- Quick Actions -->
            <div class="widget">
                <div class="widget-header">
                    <h3 class="widget-title">
                        <i class="bi bi-lightning-fill text-warning"></i>
                        Quick Actions
                    </h3>
                </div>
                <div class="widget-body">
                    <a href="{{ route('admin.jobs.create') }}" class="btn-action btn-primary">
                        <i class="bi bi-plus-circle"></i>
                        Post New Vacancy
                    </a>
                    <button class="btn-action btn-secondary" onclick="alert('Coming soon!')">
                        <i class="bi bi-person-plus"></i>
                        Add Reviewer
                    </button>
                    <button class="btn-action btn-secondary" onclick="alert('Coming soon!')">
                        <i class="bi bi-download"></i>
                        Export Report
                    </button>
                </div>
            </div>

            <!-- Active Reviewers -->
            <div class="widget">
                <div class="widget-header">
                    <h3 class="widget-title">
                        <i class="bi bi-person-badge text-success"></i>
                        Active Reviewers
                    </h3>
                </div>
                <div>
                    @forelse($reviewerStats as $reviewer)
                        <div class="reviewer-item">
                            <div class="reviewer-row">
                                <div class="reviewer-avatar bg-success bg-opacity-10 text-success">
                                    {{ strtoupper(substr($reviewer->name, 0, 1)) }}
                                </div>
                                <div class="reviewer-info">
                                    <h4 class="reviewer-name">{{ $reviewer->name }}</h4>
                                    <p class="reviewer-email">{{ $reviewer->email }}</p>
                                </div>
                            </div>
                            <div class="reviewer-stats">
                                <span class="stat-item text-success">
                                    <i class="bi bi-check-circle-fill"></i>
                                    {{ $reviewer->total_reviewed }} reviewed
                                </span>
                                <span class="stat-item text-warning">
                                    <i class="bi bi-hourglass"></i>
                                    {{ $reviewer->pending }} pending
                                </span>
                            </div>
                        </div>
                    @empty
                        <div class="empty-state" style="padding: 40px 20px;">
                            <div class="empty-icon" style="width: 48px; height: 48px; font-size: 20px; margin-bottom: 12px;">
                                <i class="bi bi-person-badge"></i>
                            </div>
                            <h4 class="empty-title" style="font-size: 14px;">No Active Reviewers</h4>
                            <p class="empty-text" style="font-size: 13px;">Add reviewers to start</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- System Status -->
            <div class="widget">
                <div class="widget-header">
                    <h3 class="widget-title">
                        <i class="bi bi-activity text-info"></i>
                        System Status
                    </h3>
                </div>
                <div class="widget-body">
                    <div class="status-row">
                        <span class="status-label">Database</span>
                        <span class="status-indicator">
                            <i class="bi bi-check-circle-fill"></i>
                            Healthy
                        </span>
                    </div>
                    <div class="status-row">
                        <span class="status-label">Storage</span>
                        <span class="status-indicator">
                            <i class="bi bi-check-circle-fill"></i>
                            Healthy
                        </span>
                    </div>
                    <div class="status-row">
                        <span class="status-label">System Load</span>
                        <span class="status-indicator">
                            <i class="bi bi-check-circle-fill"></i>
                            Normal
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        console.log('✅ Perfect Dashboard Loaded!');
    </script>
@endsection