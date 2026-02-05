@extends('layouts.dashboard')

@section('title', 'All Applications')

@section('portal-name', 'Reviewer Portal')
@section('brand-icon', 'bi bi-clipboard-check')
@section('dashboard-route', route('reviewer.dashboard'))
@section('user-name', Auth::guard('reviewer')->user()->name)
@section('user-role', 'Application Reviewer')
@section('user-initial', strtoupper(substr(Auth::guard('reviewer')->user()->name, 0, 1)))
@section('logout-route', route('reviewer.logout'))

@section('sidebar-menu')
    <a href="{{ route('reviewer.dashboard') }}" class="sidebar-menu-item">
        <i class="bi bi-speedometer2"></i>
        <span>Dashboard</span>
    </a>
    <a href="{{ route('reviewer.applications.index') }}" class="sidebar-menu-item active">
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
    .stat-card {
        background: white;
        border-radius: 10px;
        padding: 1.25rem;
        transition: all 0.3s ease;
        border: 1px solid #e2e8f0;
        height: 100%;
    }

    .stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    }

    .stat-icon {
        width: 48px;
        height: 48px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .application-row {
        transition: all 0.3s ease;
        border-left: 3px solid transparent;
    }

    .application-row:hover {
        background-color: #f8fafc;
        border-left-color: #64748b;
        transform: translateX(2px);
    }

    .application-row.high-priority {
        border-left-color: #ef4444;
        background: linear-gradient(to right, rgba(239, 68, 68, 0.02) 0%, white 100%);
    }

    .application-row.medium-priority {
        border-left-color: #f59e0b;
        background: linear-gradient(to right, rgba(245, 158, 11, 0.02) 0%, white 100%);
    }

    .application-row.low-priority {
        border-left-color: #10b981;
        background: linear-gradient(to right, rgba(16, 185, 129, 0.02) 0%, white 100%);
    }

    .filter-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        background: #f1f5f9;
        border-radius: 8px;
        font-size: 0.875rem;
        margin-right: 0.5rem;
        margin-bottom: 0.5rem;
    }

    .filter-badge .remove-filter {
        cursor: pointer;
        color: #64748b;
        text-decoration: none;
        transition: color 0.2s;
    }

    .filter-badge .remove-filter:hover {
        color: #ef4444;
    }

    .page-header {
        background: linear-gradient(135deg, #64748b 0%, #475569 100%);
        border-radius: 12px;
        padding: 1.5rem;
        color: white;
        margin-bottom: 1.5rem;
        box-shadow: 0 4px 12px rgba(100, 116, 139, 0.2);
    }
</style>
@endsection

@section('content')
<!-- Page Header -->
<div class="page-header">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h4 class="fw-bold mb-1">
                <i class="bi bi-list-check me-2"></i>Applications Management
            </h4>
            <p class="mb-0 opacity-90">Review and manage all job applications</p>
        </div>
        <a href="{{ route('reviewer.dashboard') }}" class="btn btn-light">
            <i class="bi bi-arrow-left me-2"></i>Back to Dashboard
        </a>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="stat-card">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-icon bg-primary bg-opacity-10">
                    <i class="bi bi-inbox-fill text-primary fs-4"></i>
                </div>
                <div>
                    <h3 class="fw-bold mb-0">{{ $stats['total'] }}</h3>
                    <small class="text-muted">Total Applications</small>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-icon bg-warning bg-opacity-10">
                    <i class="bi bi-hourglass-split text-warning fs-4"></i>
                </div>
                <div>
                    <h3 class="fw-bold mb-0">{{ $stats['pending'] }}</h3>
                    <small class="text-muted">Pending Review</small>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-icon bg-success bg-opacity-10">
                    <i class="bi bi-star-fill text-success fs-4"></i>
                </div>
                <div>
                    <h3 class="fw-bold mb-0">{{ $stats['shortlisted'] }}</h3>
                    <small class="text-muted">Shortlisted</small>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-icon bg-danger bg-opacity-10">
                    <i class="bi bi-x-circle-fill text-danger fs-4"></i>
                </div>
                <div>
                    <h3 class="fw-bold mb-0">{{ $stats['rejected'] }}</h3>
                    <small class="text-muted">Rejected</small>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Search and Filters -->
<div class="card border-0 shadow-sm mb-3">
    <div class="card-body">
        <form method="GET" action="{{ route('reviewer.applications.index') }}" id="searchForm">
            <div class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label class="form-label small text-muted mb-1">Search</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0">
                            <i class="bi bi-search text-muted"></i>
                        </span>
                        <input type="text" class="form-control border-start-0" name="search" 
                               placeholder="Name, email, or job title..." 
                               value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-2">
                    <label class="form-label small text-muted mb-1">Status</label>
                    <select class="form-select" name="status">
                        <option value="">All Status</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="under_review" {{ request('status') == 'under_review' ? 'selected' : '' }}>Under Review</option>
                        <option value="shortlisted" {{ request('status') == 'shortlisted' ? 'selected' : '' }}>Shortlisted</option>
                        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label small text-muted mb-1">Priority</label>
                    <select class="form-select" name="priority">
                        <option value="">All Priority</option>
                        <option value="high" {{ request('priority') == 'high' ? 'selected' : '' }}>High</option>
                        <option value="medium" {{ request('priority') == 'medium' ? 'selected' : '' }}>Medium</option>
                        <option value="low" {{ request('priority') == 'low' ? 'selected' : '' }}>Low</option>
                        <option value="normal" {{ request('priority') == 'normal' ? 'selected' : '' }}>Normal</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label small text-muted mb-1">Job Position</label>
                    <select class="form-select" name="job_id">
                        <option value="">All Jobs</option>
                        @foreach($jobs as $job)
                            <option value="{{ $job->id }}" {{ request('job_id') == $job->id ? 'selected' : '' }}>
                                {{ $job->title }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-1">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-search"></i>
                    </button>
                </div>
            </div>
        </form>

        <!-- Active Filters Display -->
        @if(request()->hasAny(['search', 'status', 'priority', 'job_id']))
            <div class="mt-3 pt-3 border-top">
                <small class="text-muted me-2 fw-semibold">Active Filters:</small>
                @if(request('search'))
                    <span class="filter-badge">
                        <i class="bi bi-search"></i>
                        "{{ request('search') }}"
                        <a href="{{ request()->fullUrlWithout('search') }}" class="remove-filter">
                            <i class="bi bi-x-lg"></i>
                        </a>
                    </span>
                @endif
                @if(request('status'))
                    <span class="filter-badge">
                        <i class="bi bi-funnel"></i>
                        Status: {{ ucfirst(str_replace('_', ' ', request('status'))) }}
                        <a href="{{ request()->fullUrlWithout('status') }}" class="remove-filter">
                            <i class="bi bi-x-lg"></i>
                        </a>
                    </span>
                @endif
                @if(request('priority'))
                    <span class="filter-badge">
                        <i class="bi bi-flag"></i>
                        Priority: {{ ucfirst(request('priority')) }}
                        <a href="{{ request()->fullUrlWithout('priority') }}" class="remove-filter">
                            <i class="bi bi-x-lg"></i>
                        </a>
                    </span>
                @endif
                @if(request('job_id'))
                    <span class="filter-badge">
                        <i class="bi bi-briefcase"></i>
                        Job: {{ $jobs->find(request('job_id'))->title ?? 'Unknown' }}
                        <a href="{{ request()->fullUrlWithout('job_id') }}" class="remove-filter">
                            <i class="bi bi-x-lg"></i>
                        </a>
                    </span>
                @endif
                <a href="{{ route('reviewer.applications.index') }}" class="btn btn-sm btn-outline-secondary ms-2">
                    <i class="bi bi-arrow-clockwise me-1"></i>Clear All
                </a>
            </div>
        @endif
    </div>
</div>

<!-- Applications Table -->
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white border-0 py-3">
        <div class="d-flex justify-content-between align-items-center">
            <h6 class="mb-0 fw-bold">
                <i class="bi bi-list-ul text-primary me-2"></i>Applications List
                <span class="badge bg-primary ms-2">{{ $applications->total() }}</span>
            </h6>
            <div class="d-flex gap-2">
                <select class="form-select form-select-sm" style="width: auto;" onchange="changeSorting(this.value)">
                    <option value="created_at-desc" {{ request('sort_by') == 'created_at' && request('sort_order') == 'desc' ? 'selected' : '' }}>
                        Newest First
                    </option>
                    <option value="created_at-asc" {{ request('sort_by') == 'created_at' && request('sort_order') == 'asc' ? 'selected' : '' }}>
                        Oldest First
                    </option>
                    <option value="deadline-asc" {{ request('sort_by') == 'deadline' && request('sort_order') == 'asc' ? 'selected' : '' }}>
                        Deadline (Urgent)
                    </option>
                </select>
            </div>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">Candidate</th>
                        <th>Job Position</th>
                        <th>Applied Date</th>
                        <th>Deadline</th>
                        <th>Status</th>
                        <th>Priority</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($applications as $application)
                        @php
                            $daysRemaining = (int) now()->diffInDays($application->job->deadline, false);
                            $priorityClass = 'normal-priority';
                            $priorityBadge = 'bg-secondary';
                            $priorityText = 'Normal';
                            $timeColor = 'text-muted';
                            
                            if ($daysRemaining <= 2) {
                                $priorityClass = 'high-priority';
                                $priorityBadge = 'bg-danger';
                                $priorityText = 'High';
                                $timeColor = 'text-danger';
                            } elseif ($daysRemaining <= 5) {
                                $priorityClass = 'medium-priority';
                                $priorityBadge = 'bg-warning';
                                $priorityText = 'Medium';
                                $timeColor = 'text-warning';
                            } elseif ($daysRemaining <= 10) {
                                $priorityClass = 'low-priority';
                                $priorityBadge = 'bg-success';
                                $priorityText = 'Low';
                                $timeColor = 'text-success';
                            }

                            $statusBadge = match($application->status) {
                                'pending' => 'bg-warning text-dark',
                                'under_review' => 'bg-info text-white',
                                'shortlisted' => 'bg-success text-white',
                                'rejected' => 'bg-danger text-white',
                                default => 'bg-secondary text-white'
                            };
                        @endphp
                        <tr class="application-row {{ $priorityClass }}">
                            <td class="ps-4">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center" 
                                         style="width: 40px; height: 40px;">
                                        <span class="fw-bold text-primary">
                                            {{ strtoupper(substr($application->candidate->name, 0, 1)) }}
                                        </span>
                                    </div>
                                    <div>
                                        <div class="fw-semibold">{{ $application->candidate->name }}</div>
                                        <small class="text-muted">{{ $application->candidate->email }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div>
                                    <div class="fw-semibold">{{ $application->job->title }}</div>
                                    <small class="text-muted">
                                        <i class="bi bi-building me-1"></i>{{ $application->job->department }}
                                    </small>
                                </div>
                            </td>
                            <td>
                                <div>
                                    <div class="small">{{ $application->created_at->format('M d, Y') }}</div>
                                    <small class="text-muted">{{ $application->created_at->diffForHumans() }}</small>
                                </div>
                            </td>
                            <td>
                                <div>
                                    <div class="small">{{ $application->job->deadline->format('M d, Y') }}</div>
                                    <small class="{{ $timeColor }}">
                                        <i class="bi bi-clock me-1"></i>{{ $daysRemaining }} {{ $daysRemaining == 1 ? 'day' : 'days' }}
                                    </small>
                                </div>
                            </td>
                            <td>
                                <span class="badge {{ $statusBadge }}">
                                    {{ ucfirst(str_replace('_', ' ', $application->status)) }}
                                </span>
                            </td>
                            <td>
                                <span class="badge {{ $priorityBadge }}">
                                    <i class="bi bi-flag-fill me-1"></i>{{ $priorityText }}
                                </span>
                            </td>
                            <td class="text-center">
                                <button class="btn btn-sm btn-outline-primary review-btn" 
                                        data-app-id="{{ $application->id }}"
                                        title="Review Application">
                                    <i class="bi bi-eye me-1"></i>Review
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <div class="py-4">
                                    <i class="bi bi-inbox display-1 text-muted"></i>
                                    <h5 class="text-muted mt-3">No Applications Found</h5>
                                    <p class="text-muted">Try adjusting your search or filter criteria</p>
                                    @if(request()->hasAny(['search', 'status', 'priority', 'job_id']))
                                        <a href="{{ route('reviewer.applications.index') }}" class="btn btn-primary mt-2">
                                            <i class="bi bi-arrow-clockwise me-2"></i>Clear Filters
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    
    @if($applications->hasPages())
        <div class="card-footer bg-white border-0 py-3">
            <div class="d-flex justify-content-between align-items-center">
                <div class="text-muted small">
                    Showing <strong>{{ $applications->firstItem() }}</strong> to <strong>{{ $applications->lastItem() }}</strong> of <strong>{{ $applications->total() }}</strong> applications
                </div>
                <div>
                    {{ $applications->links() }}
                </div>
            </div>
        </div>
    @endif
</div>

<!-- Review Modal (Same as Dashboard) -->
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
                    <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-3 text-muted fw-semibold">Loading application details...</p>
                </div>

                <!-- Application Content -->
                <div id="modalContent" style="display: none;">
                    <div class="row g-0">
                        <!-- Left: Candidate Info -->
                        <div class="col-lg-8 border-end">
                            <div class="p-4">
                                <h6 class="fw-bold mb-3 pb-2 border-bottom">
                                    <i class="bi bi-person-circle text-primary me-2"></i>Candidate Information
                                </h6>
                                <div class="row g-3 mb-4">
                                    <div class="col-md-6">
                                        <label class="small text-muted mb-1">Full Name</label>
                                        <p class="fw-semibold mb-0" id="candidateName">-</p>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="small text-muted mb-1">Email Address</label>
                                        <p class="mb-0" id="candidateEmail">-</p>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="small text-muted mb-1">Phone Number</label>
                                        <p class="mb-0" id="candidatePhone">-</p>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="small text-muted mb-1">Address</label>
                                        <p class="mb-0" id="candidateAddress">-</p>
                                    </div>
                                </div>

                                <h6 class="fw-bold mb-3 pb-2 border-bottom">
                                    <i class="bi bi-briefcase text-warning me-2"></i>Position Details
                                </h6>
                                <div class="row g-3 mb-4">
                                    <div class="col-12">
                                        <label class="small text-muted mb-1">Job Title</label>
                                        <p class="fw-semibold mb-0" id="jobTitle">-</p>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="small text-muted mb-1">Department</label>
                                        <p class="mb-0" id="jobDepartment">-</p>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="small text-muted mb-1">Location</label>
                                        <p class="mb-0" id="jobLocation">-</p>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="small text-muted mb-1">Type</label>
                                        <p class="mb-0" id="jobType">-</p>
                                    </div>
                                    <div class="col-12">
                                        <label class="small text-muted mb-1">Salary Range</label>
                                        <p class="mb-0 fw-semibold text-success" id="salaryRange">-</p>
                                    </div>
                                </div>

                                <h6 class="fw-bold mb-3 pb-2 border-bottom">
                                    <i class="bi bi-file-text text-success me-2"></i>Cover Letter
                                </h6>
                                <div class="bg-light rounded-3 p-3 mb-4">
                                    <p class="mb-0" id="coverLetter" style="white-space: pre-wrap; line-height: 1.7;">-</p>
                                </div>

                                <h6 class="fw-bold mb-3 pb-2 border-bottom">
                                    <i class="bi bi-file-earmark-pdf text-danger me-2"></i>Resume / CV
                                </h6>
                                <div>
                                    <button class="btn btn-outline-primary btn-sm">
                                        <i class="bi bi-download me-2"></i>Download Resume
                                    </button>
                                    <small class="text-muted ms-2">
                                        <i class="bi bi-info-circle me-1"></i>Feature coming soon
                                    </small>
                                </div>
                            </div>
                        </div>

                        <!-- Right: Review Actions -->
                        <div class="col-lg-4 bg-light">
                            <div class="p-4">
                                <h6 class="fw-bold mb-3 pb-2 border-bottom">
                                    <i class="bi bi-pencil-square text-info me-2"></i>Review Panel
                                </h6>

                                <div class="mb-3">
                                    <label class="small text-muted mb-2 d-block">Current Status</label>
                                    <span class="badge bg-warning text-dark" id="currentStatus">Pending</span>
                                </div>

                                <div class="mb-4">
                                    <label class="small text-muted mb-2 d-block">Application Date</label>
                                    <p class="mb-0 small fw-semibold" id="appliedDate">-</p>
                                </div>

                                <hr>

                                <form id="reviewForm">
                                    <input type="hidden" id="applicationId">

                                    <div class="mb-4">
                                        <label class="form-label fw-semibold">
                                            <i class="bi bi-chat-left-text me-1"></i>Reviewer Notes
                                        </label>
                                        <textarea class="form-control" id="reviewerNotes" rows="5" 
                                            placeholder="Add your review comments and feedback here..."
                                            style="resize: none;"></textarea>
                                        <small class="text-muted">
                                            <i class="bi bi-info-circle"></i> Notes are required when rejecting
                                        </small>
                                    </div>

                                    <div class="d-grid gap-2">
                                        <button type="button" class="btn btn-success btn-action" data-status="shortlisted">
                                            <i class="bi bi-check-circle me-2"></i>Shortlist Candidate
                                        </button>
                                        <button type="button" class="btn btn-danger btn-action" data-status="rejected">
                                            <i class="bi bi-x-circle me-2"></i>Reject Application
                                        </button>
                                        <button type="button" class="btn btn-info btn-action" data-status="under_review">
                                            <i class="bi bi-arrow-repeat me-2"></i>Mark Under Review
                                        </button>
                                    </div>
                                </form>

                                <div id="reviewedInfo" style="display: none;" class="mt-4 p-3 bg-white rounded-3 border">
                                    <h6 class="fw-bold mb-2 small text-secondary">
                                        <i class="bi bi-clock-history me-1"></i>Previous Review
                                    </h6>
                                    <div class="small">
                                        <p class="mb-1"><strong>Reviewed by:</strong> <span id="reviewedBy">-</span></p>
                                        <p class="mb-1"><strong>Review Date:</strong> <span id="reviewedAt">-</span></p>
                                        <p class="mb-0"><strong>Notes:</strong> <span id="previousNotes">-</span></p>
                                    </div>
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
    // Review Modal Handling (Same as Dashboard)
    document.addEventListener('DOMContentLoaded', function () {
        const reviewButtons = document.querySelectorAll('.review-btn');
        const reviewModal = new bootstrap.Modal(document.getElementById('reviewModal'));
        const modalLoading = document.getElementById('modalLoading');
        const modalContent = document.getElementById('modalContent');

        reviewButtons.forEach(button => {
            button.addEventListener('click', function () {
                const appId = this.getAttribute('data-app-id');
                loadApplicationData(appId);
                reviewModal.show();
            });
        });

        function loadApplicationData(appId) {
            modalLoading.style.display = 'block';
            modalContent.style.display = 'none';

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

            const statusBadge = document.getElementById('currentStatus');
            statusBadge.textContent = capitalizeFirst(data.status.replace('_', ' '));
            statusBadge.className = 'badge ' + getStatusClass(data.status);

            document.getElementById('reviewerNotes').value = '';

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

        function capitalizeFirst(str) {
            return str.charAt(0).toUpperCase() + str.slice(1);
        }

        const actionButtons = document.querySelectorAll('.btn-action');
        actionButtons.forEach(button => {
            button.addEventListener('click', function () {
                const status = this.getAttribute('data-status');
                const appId = document.getElementById('applicationId').value;
                const notes = document.getElementById('reviewerNotes').value;

                if (status === 'rejected' && !notes.trim()) {
                    alert('Please provide a reason for rejection');
                    document.getElementById('reviewerNotes').focus();
                    return;
                }

                updateApplicationStatus(appId, status, notes, this);
            });
        });

        function updateApplicationStatus(appId, status, notes, button) {
            const originalText = button.innerHTML;
            button.disabled = true;
            button.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Processing...';
            document.querySelectorAll('.btn-action').forEach(btn => btn.disabled = true);

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
                        showToast('Success', data.message, 'success');
                        reviewModal.hide();
                        setTimeout(() => location.reload(), 1000);
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

        function showToast(title, message, type) {
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

            const toastContainer = document.createElement('div');
            toastContainer.className = 'toast-container position-fixed top-0 end-0 p-3';
            toastContainer.style.zIndex = '9999';
            toastContainer.innerHTML = toastHtml;
            document.body.appendChild(toastContainer);

            const toastElement = toastContainer.querySelector('.toast');
            const toast = new bootstrap.Toast(toastElement);
            toast.show();

            toastElement.addEventListener('hidden.bs.toast', () => toastContainer.remove());
        }
    });

    // Sorting Function
    function changeSorting(value) {
        const [sortBy, sortOrder] = value.split('-');
        const url = new URL(window.location.href);
        url.searchParams.set('sort_by', sortBy);
        url.searchParams.set('sort_order', sortOrder);
        window.location.href = url.toString();
    }
</script>
@endsection