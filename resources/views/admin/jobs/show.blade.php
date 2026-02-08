@extends('layouts.dashboard')

@section('title', 'Vacancy Details')

@php
    // Dynamically detect which guard is authenticated
    if (Auth::guard('admin')->check()) {
        $currentUser = Auth::guard('admin')->user();
        $portalName = 'Admin Portal';
        $userRole = 'System Administrator';
        $dashboardRoute = route('admin.dashboard');
        $logoutRoute = route('admin.logout');
        $jobsIndexRoute = route('admin.jobs.index');
        $jobsEditRoute = route('admin.jobs.edit', $job->id);
        $jobsDuplicateRoute = route('admin.jobs.duplicate', $job->id);
        $jobsChangeStatusRoute = route('admin.jobs.changeStatus', $job->id);
        $jobsDestroyRoute = route('admin.jobs.destroy', $job->id);
    } else {
        $currentUser = Auth::guard('hr_administrator')->user();
        $portalName = 'HR Administrator Portal';
        $userRole = 'HR Administrator';
        $dashboardRoute = route('hr-administrator.dashboard');
        $logoutRoute = route('hr-administrator.logout');
        $jobsIndexRoute = route('hr-administrator.jobs.index');
        $jobsEditRoute = route('hr-administrator.jobs.edit', $job->id);
        $jobsDuplicateRoute = route('hr-administrator.jobs.duplicate', $job->id);
        $jobsChangeStatusRoute = route('hr-administrator.jobs.changeStatus', $job->id);
        $jobsDestroyRoute = route('hr-administrator.jobs.destroy', $job->id);
    }
@endphp

@section('portal-name', $portalName)
@section('brand-icon', 'bi bi-shield-check')
@section('dashboard-route', $dashboardRoute)
@section('user-name', $currentUser->name)
@section('user-role', $userRole)
@section('user-initial', strtoupper(substr($currentUser->name, 0, 1)))
@section('logout-route', $logoutRoute)

@section('sidebar-menu')
    <a href="{{ $dashboardRoute }}" class="sidebar-menu-item">
        <i class="bi bi-speedometer2"></i>
        <span>Dashboard</span>
    </a>
    <a href="{{ $jobsIndexRoute }}" class="sidebar-menu-item active">
        <i class="bi bi-briefcase"></i>
        <span>Vacancy Postings</span>
    </a>
    <a href="#" class="sidebar-menu-item">
        <i class="bi bi-file-earmark-text"></i>
        <span>Applications</span>
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
        .page-header {
            background: linear-gradient(135deg, #dc2626 0%, #991b1b 100%);
            border-radius: 12px;
            padding: 2rem;
            color: white;
            margin-bottom: 2rem;
            box-shadow: 0 4px 12px rgba(220, 38, 38, 0.3);
        }

        .govt-badge {
            background: rgba(255, 255, 255, 0.2);
            border: 2px solid rgba(255, 255, 255, 0.3);
            padding: 0.5rem 1rem;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            font-weight: 600;
            margin-bottom: 1rem;
        }

        .detail-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            border: 1px solid #e5e7eb;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .detail-header {
            border-bottom: 2px solid #e5e7eb;
            padding-bottom: 1rem;
            margin-bottom: 1.5rem;
            position: relative;
        }

        .detail-header::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 60px;
            height: 2px;
            background: #dc2626;
        }

        .detail-row {
            display: flex;
            padding: 0.75rem 0;
            border-bottom: 1px solid #f3f4f6;
        }

        .detail-row:last-child {
            border-bottom: none;
        }

        .detail-label {
            font-weight: 600;
            color: #6b7280;
            width: 200px;
            flex-shrink: 0;
        }

        .detail-value {
            color: #1f2937;
            font-weight: 500;
        }

        .status-badge {
            padding: 0.5rem 1rem;
            border-radius: 6px;
            font-size: 0.875rem;
            font-weight: 600;
            display: inline-block;
        }

        .stat-card {
            background: white;
            border-radius: 10px;
            padding: 1.5rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            border: 1px solid #e5e7eb;
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);
        }

        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            margin-bottom: 0.75rem;
        }

        .action-btn {
            padding: 0.5rem 1.5rem;
            font-weight: 600;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .action-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .applications-table {
            width: 100%;
        }

        .applications-table th {
            background: #f9fafb;
            padding: 1rem;
            font-weight: 600;
            color: #6b7280;
            border-bottom: 2px solid #e5e7eb;
        }

        .applications-table td {
            padding: 1rem;
            border-bottom: 1px solid #f3f4f6;
        }

        .applications-table tr:hover {
            background: #f9fafb;
        }

        .qualification-box {
            background: #f9fafb;
            border-left: 4px solid #dc2626;
            padding: 1rem;
            border-radius: 6px;
            white-space: pre-wrap;
        }

        .timeline-item {
            position: relative;
            padding-left: 2rem;
            padding-bottom: 1rem;
            border-left: 2px solid #e5e7eb;
        }

        .timeline-item:last-child {
            border-left: none;
        }

        .timeline-dot {
            position: absolute;
            left: -6px;
            top: 0;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: #dc2626;
        }

        .deadline-box {
            background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
            border: 2px solid #f59e0b;
            border-radius: 10px;
            padding: 1rem;
        }

        .deadline-nepali {
            font-size: 1.1rem;
            font-weight: 700;
            color: #bb2124;
        }

        .deadline-english {
            font-size: 0.9rem;
            color: #bb2124;
        }
    </style>
@endsection

@section('content')
    <!-- Page Header -->
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-start">
            <div>
                <div class="govt-badge">
                    <i class="bi bi-building-fill"></i>
                    <span>नेपाल सरकार | Government of Nepal</span>
                </div>
                <h3 class="fw-bold mb-2">
                    <i class="bi bi-file-text-fill me-2"></i>Vacancy Details
                </h3>
                <p class="mb-0 opacity-90">विज्ञापन विवरण</p>
            </div>
            <a href="{{ $jobsIndexRoute }}" class="btn btn-light btn-lg">
                <i class="bi bi-arrow-left me-2"></i>Back to List
            </a>
        </div>
    </div>

    <!-- Success/Error Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row g-4">
        <!-- Main Content Column -->
        <div class="col-lg-8">
            <!-- Vacancy Information Card -->
            <div class="detail-card">
                <div class="detail-header">
                    <div class="d-flex justify-content-between align-items-start">
                        <h5 class="fw-bold text-danger mb-0">
                            <i class="bi bi-info-circle-fill me-2"></i>Vacancy Information
                        </h5>
                        <span
                            class="status-badge {{ $job->status == 'active' ? 'bg-success text-white' : ($job->status == 'draft' ? 'bg-warning text-dark' : 'bg-danger text-white') }}">
                            {{ ucfirst($job->status) }}
                        </span>
                    </div>
                </div>

                <div class="detail-row">
                    <div class="detail-label">Advertisement No.</div>
                    <div class="detail-value">
                        <strong class="text-danger">{{ $job->advertisement_no }}</strong>
                    </div>
                </div>

                <div class="detail-row">
                    <div class="detail-label">Position / Level</div>
                    <div class="detail-value">{{ $job->position_level }}</div>
                </div>

                <div class="detail-row">
                    <div class="detail-label">Service / Group</div>
                    <div class="detail-value">{{ $job->service_group }}</div>
                </div>

                <div class="detail-row">
                    <div class="detail-label">Category</div>
                    <div class="detail-value">
                        @if($job->category == 'open')
                            <span class="badge bg-success">खुल्ला (Open)</span>
                        @else
                            <span class="badge bg-info">समावेशी (Inclusive)</span>
                        @endif
                    </div>
                </div>

                @if($job->inclusive_type)
                    <div class="detail-row">
                        <div class="detail-label">Inclusive Type</div>
                        <div class="detail-value">
                            <span class="badge bg-primary">{{ $job->inclusive_type }}</span>
                        </div>
                    </div>
                @endif

                <div class="detail-row">
                    <div class="detail-label">Demand Post (Number)</div>
                    <div class="detail-value">
                        <strong class="text-danger fs-5">{{ $job->number_of_posts }}</strong>
                        <small class="text-muted ms-2">positions available</small>
                    </div>
                </div>

                <div class="detail-row">
                    <div class="detail-label">Department</div>
                    <div class="detail-value">{{ $job->department }}</div>
                </div>

                <div class="detail-row">
                    <div class="detail-label">Location</div>
                    <div class="detail-value">
                        <i class="bi bi-geo-alt-fill text-danger me-1"></i>{{ $job->location }}
                    </div>
                </div>

                <div class="detail-row">
                    <div class="detail-label">Job Type</div>
                    <div class="detail-value">
                        <span class="badge bg-secondary">{{ ucfirst($job->job_type) }}</span>
                    </div>
                </div>

                <!-- Enhanced Deadline Display with Nepali Date -->
                <div class="detail-row">
                    <div class="detail-label">Application Deadline</div>
                    <div class="detail-value">
                        <div>
                            <div class="deadline-nepali" id="deadline-bs-display">
                                <i class="bi bi-hourglass-split me-1"></i>Loading...
                            </div>
                            <div class="deadline-english">
                                <i class="bi bi-calendar-date me-1"></i>
                                <strong>{{ $job->deadline->format('Y-m-d') }}</strong>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="detail-row">
                    <div class="detail-label">Posted On</div>
                    <div class="detail-value">
                        {{ $job->created_at->format('F d, Y') }}
                        <small class="text-muted">({{ $job->created_at->diffForHumans() }})</small>
                    </div>
                </div>

                @if($job->postedBy)
                    <div class="detail-row">
                        <div class="detail-label">Posted By</div>
                        <div class="detail-value">
                            <i class="bi bi-person-fill text-danger me-1"></i>{{ $job->postedBy->name }}
                        </div>
                    </div>
                @endif
            </div>

            <!-- Qualification Card -->
            <div class="detail-card">
                <div class="detail-header">
                    <h5 class="fw-bold text-danger mb-0">
                        <i class="bi bi-mortarboard-fill me-2"></i>Minimum Educational Qualification
                    </h5>
                    <small class="text-muted">आवश्यक शिक्षक योग्यता</small>
                </div>
                <div class="qualification-box">
                    {{ $job->minimum_qualification }}
                </div>
            </div>

            <!-- Description Card -->
            @if($job->description)
                <div class="detail-card">
                    <div class="detail-header">
                        <h5 class="fw-bold text-danger mb-0">
                            <i class="bi bi-file-text-fill me-2"></i>Description
                        </h5>
                    </div>
                    <div class="qualification-box">
                        {{ $job->description }}
                    </div>
                </div>
            @endif

            <!-- Applications List -->
            @if($job->applications_count > 0)
                <div class="detail-card">
                    <div class="detail-header">
                        <h5 class="fw-bold text-danger mb-0">
                            <i class="bi bi-people-fill me-2"></i>Recent Applications
                            <span class="badge bg-danger ms-2">{{ $job->applications_count }}</span>
                        </h5>
                    </div>

                    <div class="table-responsive">
                        <table class="applications-table">
                            <thead>
                                <tr>
                                    <th>Candidate</th>
                                    <th>Applied Date</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($job->applications->take(5) as $application)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="bg-danger bg-opacity-10 rounded-circle p-2 me-2">
                                                    <i class="bi bi-person-fill text-danger"></i>
                                                </div>
                                                <div>
                                                    <div class="fw-bold">{{ $application->candidate->user->name ?? 'N/A' }}</div>
                                                    <small
                                                        class="text-muted">{{ $application->candidate->user->email ?? '' }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ $application->created_at->format('M d, Y') }}</td>
                                        <td>
                                            <span class="badge 
                                                            {{ $application->status == 'pending' ? 'bg-warning' : '' }}
                                                            {{ $application->status == 'under_review' ? 'bg-info' : '' }}
                                                            {{ $application->status == 'shortlisted' ? 'bg-success' : '' }}
                                                            {{ $application->status == 'rejected' ? 'bg-danger' : '' }}">
                                                {{ ucfirst(str_replace('_', ' ', $application->status)) }}
                                            </span>
                                        </td>
                                        <td>
                                            <a href="#" class="btn btn-sm btn-outline-danger">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted py-4">
                                            <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                            No applications yet
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($job->applications_count > 5)
                        <div class="text-center mt-3">
                            <a href="#" class="btn btn-outline-danger">
                                View All {{ $job->applications_count }} Applications
                                <i class="bi bi-arrow-right ms-1"></i>
                            </a>
                        </div>
                    @endif
                </div>
            @endif
        </div>

        <!-- Sidebar Column -->
        <div class="col-lg-4">
            <!-- Actions Card -->
            <div class="detail-card">
                <div class="detail-header">
                    <h6 class="fw-bold mb-0">
                        <i class="bi bi-lightning-fill text-danger me-2"></i>Quick Actions
                    </h6>
                </div>

                <div class="d-grid gap-2">
                    <a href="{{ $jobsEditRoute }}" class="btn btn-outline-danger action-btn">
                        <i class="bi bi-pencil-square me-2"></i>Edit Vacancy
                    </a>

                    <form action="{{ $jobsDuplicateRoute }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-outline-secondary action-btn w-100">
                            <i class="bi bi-files me-2"></i>Duplicate Vacancy
                        </button>
                    </form>

                    @if($job->status == 'active')
                        <form action="{{ $jobsChangeStatusRoute }}" method="POST">
                            @csrf
                            <input type="hidden" name="status" value="closed">
                            <button type="submit" class="btn btn-outline-warning action-btn w-100"
                                onclick="return confirm('Close this vacancy?')">
                                <i class="bi bi-lock-fill me-2"></i>Close Vacancy
                            </button>
                        </form>
                    @elseif($job->status == 'closed')
                        <form action="{{ $jobsChangeStatusRoute }}" method="POST">
                            @csrf
                            <input type="hidden" name="status" value="active">
                            <button type="submit" class="btn btn-outline-success action-btn w-100"
                                onclick="return confirm('Reopen this vacancy?')">
                                <i class="bi bi-unlock-fill me-2"></i>Reopen Vacancy
                            </button>
                        </form>
                    @endif

                    <form action="{{ $jobsDestroyRoute }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger action-btn w-100"
                            onclick="return confirm('⚠️ Are you sure? This action cannot be undone!')">
                            <i class="bi bi-trash-fill me-2"></i>Delete Vacancy
                        </button>
                    </form>
                </div>
            </div>

            <!-- Statistics Card -->
            <div class="detail-card">
                <div class="detail-header">
                    <h6 class="fw-bold mb-0">
                        <i class="bi bi-graph-up text-danger me-2"></i>Application Statistics
                    </h6>
                </div>

                <div class="row g-3">
                    <div class="col-6">
                        <div class="text-center p-3 bg-light rounded">
                            <div class="fs-3 fw-bold text-danger">{{ $applicationStats['total'] ?? 0 }}</div>
                            <small class="text-muted">Total</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="text-center p-3 bg-warning bg-opacity-10 rounded">
                            <div class="fs-3 fw-bold text-warning">{{ $applicationStats['pending'] ?? 0 }}</div>
                            <small class="text-muted">Pending</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="text-center p-3 bg-info bg-opacity-10 rounded">
                            <div class="fs-3 fw-bold text-info">{{ $applicationStats['under_review'] ?? 0 }}</div>
                            <small class="text-muted">Under Review</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="text-center p-3 bg-success bg-opacity-10 rounded">
                            <div class="fs-3 fw-bold text-success">{{ $applicationStats['shortlisted'] ?? 0 }}</div>
                            <small class="text-muted">Shortlisted</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Timeline Card -->
            <div class="detail-card">
                <div class="detail-header">
                    <h6 class="fw-bold mb-0">
                        <i class="bi bi-clock-history text-danger me-2"></i>Timeline
                    </h6>
                </div>

                <div class="timeline-item">
                    <div class="timeline-dot"></div>
                    <div>
                        <strong class="d-block">Vacancy Posted</strong>
                        <small class="text-muted">{{ $job->created_at->format('M d, Y h:i A') }}</small>
                    </div>
                </div>

                @if($job->updated_at != $job->created_at)
                    <div class="timeline-item">
                        <div class="timeline-dot bg-warning"></div>
                        <div>
                            <strong class="d-block">Last Updated</strong>
                            <small class="text-muted">{{ $job->updated_at->format('M d, Y h:i A') }}</small>
                        </div>
                    </div>
                @endif

                <div class="timeline-item">
                    <div class="timeline-dot bg-danger"></div>
                    <div>
                        <strong class="d-block">Application Deadline</strong>
                        <small class="text-danger d-block" id="timeline-deadline-bs">Loading BS date...</small>
                        <small class="text-danger">{{ $job->deadline->format('Y-m-d') }}</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
(function() {
    'use strict';

    console.log('📝 === Show Page Date Display Initializing ===');

    // Convert English numerals to Nepali for display
    function englishToNepali(str) {
        if (!str) return str;
        const map = {'0':'०', '1':'१', '2':'२', '3':'३', '4':'४', '5':'५', '6':'६', '7':'७', '8':'८', '9':'९'};
        return str.replace(/[0-9]/g, d => map[d]);
    }

    function waitForConverter() {
        if (!window.nepaliLibrariesReady || typeof window.adToBS !== 'function') {
            console.log('⏳ Waiting for converter...');
            setTimeout(waitForConverter, 100);
            return;
        }

        console.log('✅ Converter ready!');
        displayNepaliDates();
    }

    function displayNepaliDates() {
        // Get the deadline date from the page (passed from Laravel)
        const deadlineAD = '{{ $job->deadline->format("Y-m-d") }}';
        console.log('📅 Deadline AD:', deadlineAD);

        // Convert AD to BS
        const deadlineBS = window.adToBS(deadlineAD);
        console.log('📅 Deadline BS:', deadlineBS);

        if (deadlineBS) {
            // Convert to Nepali numerals for display
            const deadlineBSNepali = englishToNepali(deadlineBS);
            console.log('📅 Deadline BS (Nepali):', deadlineBSNepali);

            // Update the main deadline display
            const deadlineBSDisplay = document.getElementById('deadline-bs-display');
            if (deadlineBSDisplay) {
                deadlineBSDisplay.innerHTML = '<i class="bi bi-calendar-week me-1"></i>' + deadlineBSNepali + ' बि.सं.';
            }

            // Update the timeline deadline
            const timelineDeadlineBS = document.getElementById('timeline-deadline-bs');
            if (timelineDeadlineBS) {
                timelineDeadlineBS.textContent = deadlineBSNepali + ' बि.सं.';
            }

            console.log('✅ Nepali dates displayed successfully!');
        } else {
            // Fallback if conversion fails
            const deadlineBSDisplay = document.getElementById('deadline-bs-display');
            if (deadlineBSDisplay) {
                deadlineBSDisplay.innerHTML = '<i class="bi bi-calendar-week me-1"></i>Date conversion unavailable';
            }

            const timelineDeadlineBS = document.getElementById('timeline-deadline-bs');
            if (timelineDeadlineBS) {
                timelineDeadlineBS.textContent = '';
            }
        }
    }

    waitForConverter();
})();
</script>
@endsection