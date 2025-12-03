@extends('layouts.dashboard')

@section('title', 'Browse Jobs')

@section('portal-name', 'Candidate Portal')
@section('brand-icon', 'bi bi-person-circle')
@section('dashboard-route', route('candidate.dashboard'))
@section('user-name', Auth::guard('candidate')->user()->name)
@section('user-role', 'Candidate')
@section('user-initial', strtoupper(substr(Auth::guard('candidate')->user()->name, 0, 1)))
@section('logout-route', route('candidate.logout'))

@section('sidebar-menu')
    <a href="{{ route('candidate.dashboard') }}" class="sidebar-menu-item">
        <i class="bi bi-speedometer2"></i>
        <span>Dashboard</span>
    </a>
    <a href="{{ route('candidate.jobs.index') }}" class="sidebar-menu-item active">
        <i class="bi bi-briefcase"></i>
        <span>Browse Jobs</span>
    </a>
    <a href="{{ route('candidate.applications.index') }}" class="sidebar-menu-item">
        <i class="bi bi-file-earmark-text"></i>
        <span>My Applications</span>
    </a>
    <a href="{{ route('candidate.profile.edit') }}" class="sidebar-menu-item">
        <i class="bi bi-person"></i>
        <span>My Profile</span>
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

        /* Search Bar */
        .search-section {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            padding: 32px;
            border-radius: var(--radius);
            margin-bottom: 32px;
            color: var(--white);
        }

        .search-form {
            max-width: 600px;
            margin: 0 auto;
        }

        .search-input-group {
            display: flex;
            gap: 12px;
        }

        .search-input {
            flex: 1;
            padding: 14px 20px;
            border: none;
            border-radius: 8px;
            font-size: 15px;
        }

        .search-btn {
            padding: 14px 32px;
            background: var(--white);
            color: var(--primary);
            border: none;
            border-radius: 8px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .search-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        /* Filters */
        .filters-section {
            background: var(--white);
            border: var(--border);
            border-radius: var(--radius);
            padding: 20px;
            margin-bottom: 24px;
        }

        .filters-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 16px;
        }

        .filter-group label {
            display: block;
            font-size: 13px;
            font-weight: 500;
            color: var(--gray-700);
            margin-bottom: 6px;
        }

        .filter-group select {
            width: 100%;
            padding: 8px 12px;
            border: var(--border);
            border-radius: 6px;
            font-size: 14px;
        }

        /* Job Grid */
        .jobs-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 24px;
            margin-bottom: 32px;
        }

        /* Job Card */
        .job-card {
            background: var(--white);
            border: var(--border);
            border-radius: var(--radius);
            padding: 24px;
            transition: all 0.2s ease;
            position: relative;
        }

        .job-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
            border-color: var(--primary);
        }

        .job-header {
            margin-bottom: 16px;
        }

        .job-badge {
            display: inline-block;
            padding: 4px 12px;
            background: var(--primary);
            color: var(--white);
            border-radius: 6px;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 12px;
        }

        .job-title {
            font-size: 18px;
            font-weight: 700;
            color: var(--gray-900);
            margin: 0 0 8px 0;
            line-height: 1.3;
        }

        .job-company {
            font-size: 14px;
            color: var(--gray-600);
            margin: 0;
        }

        .job-details {
            display: flex;
            flex-direction: column;
            gap: 8px;
            margin-bottom: 16px;
            padding: 16px 0;
            border-top: var(--border);
            border-bottom: var(--border);
        }

        .job-detail-item {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
            color: var(--gray-600);
        }

        .job-detail-item i {
            color: var(--primary);
            width: 18px;
        }

        .job-description {
            font-size: 14px;
            color: var(--gray-600);
            line-height: 1.6;
            margin-bottom: 16px;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .job-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
        }

        .deadline-badge {
            padding: 6px 12px;
            background: var(--gray-100);
            color: var(--gray-700);
            border-radius: 6px;
            font-size: 12px;
            font-weight: 600;
        }

        .deadline-badge.urgent {
            background: #fee2e2;
            color: #991b1b;
        }

        .btn {
            padding: 10px 20px;
            font-size: 14px;
            font-weight: 600;
            border-radius: 8px;
            border: none;
            cursor: pointer;
            transition: all 0.2s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-primary {
            background: var(--primary);
            color: var(--white);
        }

        .btn-primary:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
            color: var(--white);
        }

        .btn-secondary {
            background: var(--white);
            color: var(--gray-700);
            border: var(--border);
        }

        .btn-secondary:hover {
            background: var(--gray-50);
        }

        .btn-success {
            background: var(--success);
            color: var(--white);
        }

        .btn-disabled {
            background: var(--gray-200);
            color: var(--gray-500);
            cursor: not-allowed;
        }

        .btn-disabled:hover {
            transform: none;
        }

        /* Applied Badge */
        .applied-badge {
            position: absolute;
            top: 16px;
            right: 16px;
            padding: 6px 12px;
            background: var(--success);
            color: var(--white);
            border-radius: 6px;
            font-size: 12px;
            font-weight: 600;
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            background: var(--white);
            border: var(--border);
            border-radius: var(--radius);
        }

        .empty-icon {
            width: 80px;
            height: 80px;
            margin: 0 auto 20px;
            border-radius: 50%;
            background: var(--gray-100);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--gray-400);
            font-size: 36px;
        }

        .empty-title {
            font-size: 20px;
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
        @media (max-width: 768px) {
            .jobs-grid {
                grid-template-columns: 1fr;
            }

            .filters-grid {
                grid-template-columns: 1fr;
            }

            .search-input-group {
                flex-direction: column;
            }
        }
    </style>
@endsection

@section('content')
    <!-- Page Header -->
    <div class="page-header">
        <h1 class="page-title">Browse Job Vacancies</h1>
        <p class="page-subtitle">Find your dream job and apply now</p>
    </div>

    <!-- Search Section -->
    <div class="search-section">
        <form action="{{ route('candidate.jobs.index') }}" method="GET" class="search-form">
            <div class="search-input-group">
                <input type="text" name="search" class="search-input"
                    placeholder="Search by job title, department, location..." value="{{ request('search') }}">
                <button type="submit" class="search-btn">
                    <i class="bi bi-search me-2"></i>
                    Search Jobs
                </button>
            </div>
        </form>
    </div>

    <!-- Filters -->
    <div class="filters-section">
        <form action="{{ route('candidate.jobs.index') }}" method="GET">
            <input type="hidden" name="search" value="{{ request('search') }}">
            <div class="filters-grid">
                <div class="filter-group">
                    <label>Department</label>
                    <select name="department" onchange="this.form.submit()">
                        <option value="">All Departments</option>
                        @foreach($departments as $dept)
                            <option value="{{ $dept }}" {{ request('department') == $dept ? 'selected' : '' }}>
                                {{ $dept }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="filter-group">
                    <label>Location</label>
                    <select name="location" onchange="this.form.submit()">
                        <option value="">All Locations</option>
                        @foreach($locations as $loc)
                            <option value="{{ $loc }}" {{ request('location') == $loc ? 'selected' : '' }}>
                                {{ $loc }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="filter-group">
                    <label>Position Level</label>
                    <select name="position_level" onchange="this.form.submit()">
                        <option value="">All Levels</option>
                        @foreach($positionLevels as $level)
                            <option value="{{ $level }}" {{ request('position_level') == $level ? 'selected' : '' }}>
                                {{ $level }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="filter-group">
                    <label>&nbsp;</label>
                    <a href="{{ route('candidate.jobs.index') }}" class="btn btn-secondary w-100">
                        <i class="bi bi-x-circle"></i>
                        Clear Filters
                    </a>
                </div>
            </div>
        </form>
    </div>

    <!-- Jobs Grid -->
    @if($jobs->count() > 0)
        <div class="jobs-grid">
            @foreach($jobs as $job)
                <div class="job-card">
                    @if(in_array($job->id, $appliedJobIds))
                        <span class="applied-badge">
                            <i class="bi bi-check-circle me-1"></i>
                            Applied
                        </span>
                    @endif

                    <div class="job-header">
                        <span class="job-badge">{{ $job->advertisement_no }}</span>
                        <h3 class="job-title">{{ $job->title }}</h3>
                        <p class="job-company">{{ $job->department }}</p>
                    </div>

                    <div class="job-details">
                        <div class="job-detail-item">
                            <i class="bi bi-geo-alt-fill"></i>
                            <span>{{ $job->location }}</span>
                        </div>
                        <div class="job-detail-item">
                            <i class="bi bi-briefcase-fill"></i>
                            <span>{{ $job->position_level }}</span>
                        </div>
                        <div class="job-detail-item">
                            <i class="bi bi-people-fill"></i>
                            <span>{{ $job->applications_count }} Applications</span>
                        </div>
                    </div>

                    <div class="job-description">
                        {{ Str::limit(strip_tags($job->description ?? 'No description available'), 150) }}
                    </div>

                    <div class="job-footer">
                        @php
                            $daysLeft = now()->diffInDays($job->deadline, false);
                            $isUrgent = $daysLeft <= 7;
                        @endphp
                        <span class="deadline-badge {{ $isUrgent ? 'urgent' : '' }}">
                            <i class="bi bi-clock me-1"></i>
                            @if($daysLeft > 0)
                                {{ $daysLeft }} days left
                            @else
                                Deadline today
                            @endif
                        </span>

                        <a href="{{ route('candidate.jobs.show', $job->id) }}" class="btn btn-primary">
                            View Details
                            <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center">
            {{ $jobs->links() }}
        </div>
    @else
        <div class="empty-state">
            <div class="empty-icon">
                <i class="bi bi-briefcase"></i>
            </div>
            <h3 class="empty-title">No Jobs Found</h3>
            <p class="empty-text">Try adjusting your search criteria or check back later for new opportunities</p>
        </div>
    @endif
@endsection