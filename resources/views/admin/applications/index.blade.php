@extends('layouts.dashboard')

@section('title', 'Applications Management')

@section('portal-name', 'Admin Portal')
@section('brand-icon', 'bi bi-shield-check')
@section('dashboard-route', route('admin.dashboard'))
@section('user-name', Auth::guard('admin')->user()->name)
@section('user-role', 'System Administrator')
@section('user-initial', strtoupper(substr(Auth::guard('admin')->user()->name, 0, 1)))
@section('logout-route', route('admin.logout'))

@section('sidebar-menu')
    <a href="{{ route('admin.dashboard') }}" class="sidebar-menu-item">
        <i class="bi bi-speedometer2"></i>
        <span>Dashboard</span>
    </a>
    <a href="{{ route('admin.jobs.create') }}" class="sidebar-menu-item">
        <i class="bi bi-briefcase"></i>
        <span>Post Vacancy</span>
    </a>
    <a href="{{ route('admin.applications.index') }}" class="sidebar-menu-item active">
        <i class="bi bi-file-earmark-text"></i>
        <span>Applications</span>
        <span class="badge bg-warning text-dark ms-auto">{{ $stats['pending'] }}</span>
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
        <span>Reports</span>
    </a>
    <a href="#" class="sidebar-menu-item">
        <i class="bi bi-gear"></i>
        <span>Settings</span>
    </a>
@endsection

@section('custom-styles')
    <style>
        :root {
            --primary: #6366f1;
            --primary-dark: #4f46e5;
            --success: #10b981;
            --warning: #f59e0b;
            --info: #3b82f6;
            --danger: #ef4444;
            --gray-50: #f9fafb;
            --gray-100: #f3f4f6;
            --gray-200: #e5e7eb;
            --gray-500: #64748b;
            --gray-600: #475569;
            --gray-700: #334155;
            --gray-900: #0f172a;
            --white: #ffffff;
            --border: 1px solid #e5e7eb;
            --radius: 12px;
            --shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
        }

        /* Page Header */
        .page-header {
            margin-bottom: 24px;
        }

        .page-title {
            font-size: 28px;
            font-weight: 700;
            color: var(--gray-900);
            margin: 0 0 8px 0;
        }

        .page-subtitle {
            font-size: 14px;
            color: var(--gray-500);
            margin: 0;
        }

        /* Stats Cards */
        .stats-row {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 16px;
            margin-bottom: 24px;
        }

        .stat-card {
            background: var(--white);
            border: var(--border);
            border-radius: 8px;
            padding: 16px;
            text-align: center;
            transition: all 0.2s ease;
        }

        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow);
        }

        .stat-value {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 4px;
        }

        .stat-label {
            font-size: 13px;
            color: var(--gray-600);
            font-weight: 500;
        }

        /* Filters Card */
        .filters-card {
            background: var(--white);
            border: var(--border);
            border-radius: var(--radius);
            padding: 20px;
            margin-bottom: 24px;
        }

        .filters-title {
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .filters-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 16px;
            margin-bottom: 16px;
        }

        .filter-group label {
            display: block;
            font-size: 13px;
            font-weight: 500;
            color: var(--gray-700);
            margin-bottom: 6px;
        }

        .filter-group input,
        .filter-group select {
            width: 100%;
            padding: 8px 12px;
            border: var(--border);
            border-radius: 6px;
            font-size: 14px;
        }

        .filter-group input:focus,
        .filter-group select:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
        }

        .filter-actions {
            display: flex;
            gap: 12px;
        }

        .btn {
            padding: 8px 16px;
            font-size: 14px;
            font-weight: 500;
            border-radius: 6px;
            border: none;
            cursor: pointer;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            text-decoration: none;
        }

        .btn-primary {
            background: var(--primary);
            color: var(--white);
        }

        .btn-primary:hover {
            background: var(--primary-dark);
        }

        .btn-secondary {
            background: var(--white);
            color: var(--gray-700);
            border: var(--border);
        }

        .btn-secondary:hover {
            background: var(--gray-50);
        }

        /* Table Card */
        .table-card {
            background: var(--white);
            border: var(--border);
            border-radius: var(--radius);
            overflow: hidden;
        }

        .table-header {
            padding: 16px 20px;
            border-bottom: var(--border);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .table-title {
            font-size: 16px;
            font-weight: 600;
            margin: 0;
        }

        .table-actions {
            display: flex;
            gap: 12px;
        }

        /* Table */
        .table-responsive {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead {
            background: var(--gray-50);
        }

        th {
            padding: 12px 20px;
            text-align: left;
            font-size: 12px;
            font-weight: 600;
            color: var(--gray-600);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: var(--border);
        }

        td {
            padding: 16px 20px;
            font-size: 14px;
            color: var(--gray-900);
            border-bottom: var(--border);
        }

        tbody tr:hover {
            background: var(--gray-50);
        }

        tbody tr:last-child td {
            border-bottom: none;
        }

        .candidate-cell {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .candidate-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: var(--primary);
            color: var(--white);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 14px;
            flex-shrink: 0;
        }

        .candidate-info h4 {
            font-size: 14px;
            font-weight: 600;
            margin: 0 0 2px 0;
        }

        .candidate-info p {
            font-size: 12px;
            color: var(--gray-500);
            margin: 0;
        }

        .status-badge {
            padding: 4px 10px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 600;
            text-transform: capitalize;
        }

        .status-pending {
            background: #fef3c7;
            color: #92400e;
        }

        .status-under_review {
            background: #dbeafe;
            color: #1e40af;
        }

        .status-shortlisted {
            background: #d1fae5;
            color: #065f46;
        }

        .status-rejected {
            background: #fee2e2;
            color: #991b1b;
        }

        .action-btn {
            padding: 6px 12px;
            font-size: 13px;
            font-weight: 500;
            border-radius: 6px;
            border: var(--border);
            background: var(--white);
            color: var(--gray-700);
            cursor: pointer;
            transition: all 0.2s ease;
            text-decoration: none;
            display: inline-block;
        }

        .action-btn:hover {
            background: var(--gray-50);
            border-color: var(--primary);
            color: var(--primary);
        }

        /* Pagination */
        .pagination-wrapper {
            padding: 16px 20px;
            border-top: var(--border);
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 60px 20px;
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
            font-size: 18px;
            font-weight: 600;
            color: var(--gray-900);
            margin: 0 0 8px 0;
        }

        .empty-text {
            font-size: 14px;
            color: var(--gray-500);
            margin: 0;
        }

        /* Responsive */
        @media (max-width: 1200px) {
            .stats-row {
                grid-template-columns: repeat(3, 1fr);
            }

            .filters-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 768px) {
            .stats-row {
                grid-template-columns: repeat(2, 1fr);
            }

            .filters-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
@endsection

@section('content')
    <!-- Page Header -->
    <div class="page-header">
        <h1 class="page-title">Applications Management</h1>
        <p class="page-subtitle">View and manage all job applications</p>
    </div>

    <!-- Success Message -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Statistics -->
    <div class="stats-row">
        <div class="stat-card">
            <div class="stat-value text-primary">{{ $stats['total'] }}</div>
            <div class="stat-label">Total Applications</div>
        </div>
        <div class="stat-card">
            <div class="stat-value text-warning">{{ $stats['pending'] }}</div>
            <div class="stat-label">Pending</div>
        </div>
        <div class="stat-card">
            <div class="stat-value text-info">{{ $stats['under_review'] }}</div>
            <div class="stat-label">Under Review</div>
        </div>
        <div class="stat-card">
            <div class="stat-value text-success">{{ $stats['shortlisted'] }}</div>
            <div class="stat-label">Shortlisted</div>
        </div>
        <div class="stat-card">
            <div class="stat-value text-danger">{{ $stats['rejected'] }}</div>
            <div class="stat-label">Rejected</div>
        </div>
    </div>

    <!-- Filters -->
    <div class="filters-card">
        <div class="filters-title">
            <i class="bi bi-funnel"></i>
            Filters
        </div>
        <form action="{{ route('admin.applications.index') }}" method="GET">
            <div class="filters-grid">
                <div class="filter-group">
                    <label>Search</label>
                    <input type="text" name="search" placeholder="Name, email, job..." value="{{ request('search') }}">
                </div>
                <div class="filter-group">
                    <label>Status</label>
                    <select name="status">
                        <option value="">All Statuses</option>
                        @foreach($statuses as $status)
                            <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>
                                {{ ucfirst(str_replace('_', ' ', $status)) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="filter-group">
                    <label>Job</label>
                    <select name="job_id">
                        <option value="">All Jobs</option>
                        @foreach($jobs as $job)
                            <option value="{{ $job->id }}" {{ request('job_id') == $job->id ? 'selected' : '' }}>
                                {{ $job->advertisement_no }} - {{ Str::limit($job->title, 30) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="filter-group">
                    <label>Reviewer</label>
                    <select name="reviewer_id">
                        <option value="">All Reviewers</option>
                        @foreach($reviewers as $reviewer)
                            <option value="{{ $reviewer->id }}" {{ request('reviewer_id') == $reviewer->id ? 'selected' : '' }}>
                                {{ $reviewer->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="filter-actions">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-search"></i>
                    Apply Filters
                </button>
                <a href="{{ route('admin.applications.index') }}" class="btn btn-secondary">
                    <i class="bi bi-x-circle"></i>
                    Clear
                </a>
            </div>
        </form>
    </div>

    <!-- Applications Table -->
    <div class="table-card">
        <div class="table-header">
            <h3 class="table-title">
                Applications ({{ $applications->total() }})
            </h3>
            <div class="table-actions">
                <button class="btn btn-secondary" onclick="alert('Export feature coming soon!')">
                    <i class="bi bi-download"></i>
                    Export
                </button>
            </div>
        </div>

        @if($applications->count() > 0)
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>Candidate</th>
                            <th>Job Position</th>
                            <th>Status</th>
                            <th>Reviewer</th>
                            <th>Applied Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($applications as $application)
                            <tr>
                                <td>
                                    <div class="candidate-cell">
                                        <div class="candidate-avatar">
                                            {{ strtoupper(substr($application->candidate->user->name ?? 'U', 0, 1)) }}
                                        </div>
                                        <div class="candidate-info">
                                            <h4>{{ $application->candidate->user->name ?? 'Unknown' }}</h4>
                                            <p>{{ $application->candidate->user->email ?? 'N/A' }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <strong>{{ $application->jobPosting->advertisement_no }}</strong><br>
                                        <small class="text-muted">{{ Str::limit($application->jobPosting->title, 40) }}</small>
                                    </div>
                                </td>
                                <td>
                                    <span class="status-badge status-{{ $application->status }}">
                                        {{ ucfirst(str_replace('_', ' ', $application->status)) }}
                                    </span>
                                </td>
                                <td>
                                    @if($application->reviewer)
                                        <small>{{ $application->reviewer->name }}</small>
                                    @else
                                        <small class="text-muted">Not assigned</small>
                                    @endif
                                </td>
                                <td>
                                    <small>{{ $application->created_at->format('M d, Y') }}</small><br>
                                    <small class="text-muted">{{ $application->created_at->diffForHumans() }}</small>
                                </td>
                                <td>
                                    <a href="{{ route('admin.applications.show', $application->id) }}" class="action-btn">
                                        <i class="bi bi-eye"></i>
                                        View
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="pagination-wrapper">
                {{ $applications->links() }}
            </div>
        @else
            <div class="empty-state">
                <div class="empty-icon">
                    <i class="bi bi-inbox"></i>
                </div>
                <h3 class="empty-title">No Applications Found</h3>
                <p class="empty-text">Try adjusting your filters or search criteria</p>
            </div>
        @endif
    </div>
@endsection

@section('scripts')
    <script>
        console.log('✅ Applications Management Loaded!');
    </script>
@endsection