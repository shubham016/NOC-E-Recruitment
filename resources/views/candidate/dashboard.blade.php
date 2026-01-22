@extends('layouts.dashboard')

@section('title', 'Candidate Dashboard')

@section('portal-name', 'Candidate Portal')
@section('brand-icon', 'bi bi-briefcase')
@section('dashboard-route', route('candidate.dashboard'))
@section('user-name', Auth::guard('candidate')->user()->name)
{{-- @section('user-role', 'Job Seeker') --}}
@section('user-initial', strtoupper(substr(Auth::guard('candidate')->user()->name, 0, 1)))
@section('logout-route', route('candidate.logout'))

@section('sidebar-menu')
    <a href="{{ route('candidate.dashboard') }}" class="sidebar-menu-item active">
        <i class="bi bi-speedometer2"></i>
        <span>Dashboard</span>
    </a>
    <a href="{{ route('candidate.jobs.index') }}" class="sidebar-menu-item">
        <i class="bi bi-search"></i>
        <span>Browse Vacancy</span>
        <span class="badge bg-primary ms-auto">{{ $stats['active_jobs'] }}</span>
    </a>
    <a href="{{ route('candidate.applications.index') }}" class="sidebar-menu-item">
        <i class="bi bi-file-earmark-text"></i>
        <span>My Applications</span>
        <span class="badge bg-warning text-dark ms-auto">{{ $stats['total_applications'] }}</span>
    </a>
    <a href="#" class="sidebar-menu-item">
        <i class="bi bi-bookmark"></i>
        <span>Saved Vacancy</span>
    </a>
    <a href="{{ route('candidate.profile.show') }}" class="sidebar-menu-item">
        <i class="bi bi-person"></i>
        <span>My Profile</span>
    </a>
    <a href="#" class="sidebar-menu-item">
        <i class="bi bi-file-earmark-pdf"></i>
        <span>Resume</span>
    </a>
    <a href="#" class="sidebar-menu-item">
        <i class="bi bi-bell"></i>
        <span>Notifications</span>
    </a>
    <a href="{{ route('candidate.settings.index') }}" class="sidebar-menu-item">
        <i class="bi bi-gear"></i>
        <span>Settings</span>
    </a>
@endsection

@section('custom-styles')
    <style>
        .job-card {
            transition: all 0.3s ease;
            cursor: pointer;
            border-left: 4px solid transparent;
        }

        .job-card:hover {
            border-left-color: #10b981;
            transform: translateX(5px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1) !important;
        }

        .company-logo {
            width: 50px;
            height: 50px;
            border-radius: 8px;
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 700;
            font-size: 1.25rem;
            flex-shrink: 0;
        }

        .progress-circle {
            position: relative;
            display: inline-block;
        }

        .profile-checklist .list-group-item {
            border: none;
            padding: 0.75rem 0;
        }

        .btn-apply {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            border: none;
            transition: all 0.3s ease;
        }

        .btn-apply:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(16, 185, 129, 0.3);
        }

        .tip-card {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
        }

        .badge-custom {
            padding: 0.35rem 0.65rem;
            font-weight: 500;
            font-size: 0.75rem;
        }
    </style>
@endsection

@section('content')
    <!-- Success Message -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Page Header -->
    <div class="page-header mb-4">
        <h1 class="page-title">Welcome back, {{ Auth::guard('candidate')->user()->name }}! 🎯</h1>
        <p class="page-subtitle">Track your applications and discover new opportunities that match your skills.</p>
    </div>

    <!-- Stats Cards -->
    <div class="row g-4 mb-4">
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card stat-card h-100">
                <div class="stat-icon blue">
                    <i class="bi bi-file-earmark-text-fill"></i>
                </div>
                <h3 class="h2 fw-bold mb-1">{{ $stats['total_applications'] }}</h3>
                <p class="text-muted mb-2">Total Applications</p>
                <small class="text-info">
                    <i class="bi bi-info-circle me-1"></i>All time
                </small>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card stat-card h-100">
                <div class="stat-icon orange">
                    <i class="bi bi-hourglass-split"></i>
                </div>
                <h3 class="h2 fw-bold mb-1">{{ $stats['pending'] + $stats['under_review'] }}</h3>
                <p class="text-muted mb-2">Pending Review</p>
                <small class="text-warning">
                    <i class="bi bi-clock me-1"></i>In progress
                </small>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card stat-card h-100">
                <div class="stat-icon emerald">
                    <i class="bi bi-star-fill"></i>
                </div>
                <h3 class="h2 fw-bold mb-1">{{ $stats['shortlisted'] }}</h3>
                <p class="text-muted mb-2">Shortlisted</p>
                <small class="text-success">
                    <i class="bi bi-check-circle me-1"></i>Great news!
                </small>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card stat-card h-100">
                <div class="stat-icon slate">
                    <i class="bi bi-briefcase-fill"></i>
                </div>
                <h3 class="h2 fw-bold mb-1">{{ $stats['active_jobs'] }}</h3>
                <p class="text-muted mb-2">Active Vacancy</p>
                <small class="text-muted">
                    <i class="bi bi-search me-1"></i>Available
                </small>
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="row g-4">
        <!-- Left Column - Job Recommendations & Applications -->
        <div class="col-12 col-xl-8">
            <!-- My Applications -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-0 py-3">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <h5 class="mb-0 fw-bold">
                            <i class="bi bi-file-earmark-text text-primary me-2"></i>My Recent Applications
                        </h5>
                        <a href="{{ route('candidate.applications.index') }}" class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-arrow-right me-1"></i>View All
                        </a>
                    </div>
                </div>
                <div class="card-body p-0">
                    @if($recentApplications->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="border-0 ps-4">Position</th>
                                        <th class="border-0">Applied Date</th>
                                        <th class="border-0">Status</th>
                                        <th class="border-0 pe-4">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentApplications as $application)
                                        <tr>
                                            <td class="ps-4">
                                                <div>
                                                    <div class="fw-semibold text-dark">{{ $application->jobPosting->title }}</div>
                                                    <small class="text-muted">
                                                        <i
                                                            class="bi bi-building me-1"></i>{{ $application->jobPosting->department }}
                                                        <span class="mx-1">•</span>
                                                        <i
                                                            class="bi bi-geo-alt-fill me-1"></i>{{ $application->jobPosting->location }}
                                                    </small>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="text-muted">{{ $application->created_at->format('M d, Y') }}</span>
                                                <br>
                                                <small class="text-muted">{{ $application->created_at->diffForHumans() }}</small>
                                            </td>
                                            <td>
                                                @php
                                                    $statusColors = [
                                                        'pending' => 'warning',
                                                        'under_review' => 'info',
                                                        'shortlisted' => 'success',
                                                        'rejected' => 'danger'
                                                    ];
                                                    $statusIcons = [
                                                        'pending' => 'hourglass-split',
                                                        'under_review' => 'arrow-repeat',
                                                        'shortlisted' => 'check-circle',
                                                        'rejected' => 'x-circle'
                                                    ];
                                                    $color = $statusColors[$application->status] ?? 'secondary';
                                                    $icon = $statusIcons[$application->status] ?? 'circle';
                                                @endphp
                                                <span class="badge bg-{{ $color }} badge-custom">
                                                    <i
                                                        class="bi bi-{{ $icon }} me-1"></i>{{ ucfirst(str_replace('_', ' ', $application->status)) }}
                                                </span>
                                            </td>
                                            <td class="pe-4">
                                                <a href="{{ route('candidate.applications.show', $application->id) }}"
                                                    class="btn btn-sm btn-outline-primary">
                                                    <i class="bi bi-eye"></i> View
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="bi bi-inbox" style="font-size: 48px; color: #9ca3af;"></i>
                            <h5 class="mt-3 text-muted">No Applications Yet</h5>
                            <p class="text-muted">Start applying to jobs to see your applications here</p>
                            <a href="{{ route('candidate.jobs.index') }}" class="btn btn-primary mt-2">
                                <i class="bi bi-search me-2"></i>Browse Vacancy
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Recommended Jobs -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0 fw-bold">
                        <i class="bi bi-lightbulb text-warning me-2"></i>Recommended Vacancies for You
                    </h5>
                </div>
                <div class="card-body">
                    @if($recommendedJobs->count() > 0)
                        @foreach($recommendedJobs->take(3) as $job)
                            <!-- Job Card -->
                            <div class="card job-card border shadow-sm mb-3">
                                <div class="card-body p-4">
                                    <div class="d-flex justify-content-between align-items-start mb-3">
                                        <div class="d-flex gap-3 flex-grow-1">
                                            <div class="company-logo">{{ strtoupper(substr($job->department, 0, 2)) }}</div>
                                            <div class="flex-grow-1">
                                                <h6 class="fw-bold mb-1">{{ $job->title }}</h6>
                                                <p class="text-muted mb-2">{{ $job->department }}</p>
                                                <div class="d-flex gap-2 flex-wrap">
                                                    <span class="badge bg-light text-dark border">
                                                        <i class="bi bi-geo-alt me-1"></i>{{ $job->location }}
                                                    </span>
                                                    <span class="badge bg-light text-dark border">
                                                        <i class="bi bi-clock me-1"></i>{{ $job->job_type ?? 'Full-time' }}
                                                    </span>
                                                    @if($job->salary_min && $job->salary_max)
                                                        <span class="badge bg-light text-dark border">
                                                            <i
                                                                class="bi bi-currency-dollar me-1"></i>${{ number_format($job->salary_min / 1000) }}k-${{ number_format($job->salary_max / 1000) }}k
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <button class="btn btn-sm btn-outline-secondary border-0">
                                            <i class="bi bi-bookmark fs-5"></i>
                                        </button>
                                    </div>

                                    <p class="text-muted mb-3 small">
                                        {{ Str::limit($job->description, 150) }}
                                    </p>

                                    @if($job->requirements)
                                        <div class="d-flex flex-wrap gap-1 mb-3">
                                            @foreach(explode(',', Str::limit($job->requirements, 100, '')) as $skill)
                                                @if(trim($skill))
                                                    <span class="badge bg-primary bg-opacity-10 text-primary">{{ trim($skill) }}</span>
                                                @endif
                                            @endforeach
                                        </div>
                                    @endif

                                    <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                                        <small class="text-muted">
                                            <i class="bi bi-clock-history me-1"></i>Posted {{ $job->created_at->diffForHumans() }}
                                        </small>
                                        <a href="{{ route('candidate.jobs.show', $job->id) }}"
                                            class="btn btn-primary btn-apply px-4">
                                            <i class="bi bi-eye me-2"></i>View Details
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach

                        <!-- View More Button -->
                        <div class="text-center mt-4">
                            <a href="{{ route('candidate.jobs.index') }}" class="btn btn-outline-primary px-5">
                                <i class="bi bi-arrow-right-circle me-2"></i>View More Vacancies ({{ $recommendedJobs->count() }}
                                Available)
                            </a>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="bi bi-briefcase" style="font-size: 48px; color: #9ca3af;"></i>
                            <h5 class="mt-3 text-muted">No Vacancy Available</h5>
                            <p class="text-muted">Check back later for new opportunities</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Right Column - Profile & Quick Actions -->
        <div class="col-12 col-xl-4">
            <!-- Quick Actions -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0 fw-bold">
                        <i class="bi bi-lightning-charge text-warning me-2"></i>Quick Actions
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-3">
                        <a href="{{ route('candidate.jobs.index') }}" class="btn btn-primary btn-lg">
                            <i class="bi bi-search me-2"></i>Browse All Vacancy
                        </a>
                        <a href="{{ route('candidate.applications.index') }}" class="btn btn-outline-primary btn-lg">
                            <i class="bi bi-file-earmark-text me-2"></i>My Applications
                        </a>
                        <a href="{{ route('candidate.profile.edit') }}" class="btn btn-outline-secondary btn-lg">
                            <i class="bi bi-person-gear me-2"></i>Edit Profile
                        </a>
                    </div>

                    <hr class="my-4">

                    <h6 class="fw-bold mb-3">
                        <i class="bi bi-bar-chart me-2"></i>Application Stats
                    </h6>

                    @php
                        $responseRate = $stats['total_applications'] > 0
                            ? round((($stats['under_review'] + $stats['shortlisted']) / $stats['total_applications']) * 100)
                            : 0;
                    @endphp

                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="text-muted small">Response Rate</span>
                        <span
                            class="fw-bold text-{{ $responseRate > 50 ? 'success' : 'warning' }}">{{ $responseRate }}%</span>
                    </div>
                    <div class="progress mb-3" style="height: 8px;">
                        <div class="progress-bar bg-{{ $responseRate > 50 ? 'success' : 'warning' }}" role="progressbar"
                            style="width: {{ $responseRate }}%"></div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-muted small">Total Applications</span>
                        <span class="badge bg-primary">{{ $stats['total_applications'] }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-muted small">Shortlisted</span>
                        <span class="badge bg-success">{{ $stats['shortlisted'] }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-muted small">Rejected</span>
                        <span class="badge bg-danger">{{ $stats['rejected'] }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection