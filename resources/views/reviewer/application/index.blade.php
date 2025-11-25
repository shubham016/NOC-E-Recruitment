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

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1">
                <i class="bi bi-list-check text-primary me-2"></i>All Applications
            </h4>
            <p class="text-muted mb-0">Review and manage job applications</p>
        </div>
        <a href="{{ route('reviewer.dashboard') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-2"></i>Back to Dashboard
        </a>
    </div>

    <!-- Statistics Cards -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center gap-3">
                        <div class="p-3 bg-primary bg-opacity-10 rounded-3">
                            <i class="bi bi-inbox-fill text-primary fs-4"></i>
                        </div>
                        <div>
                            <h3 class="fw-bold mb-0">{{ $stats['total'] }}</h3>
                            <small class="text-muted">Total</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center gap-3">
                        <div class="p-3 bg-warning bg-opacity-10 rounded-3">
                            <i class="bi bi-hourglass-split text-warning fs-4"></i>
                        </div>
                        <div>
                            <h3 class="fw-bold mb-0">{{ $stats['pending'] }}</h3>
                            <small class="text-muted">Pending</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center gap-3">
                        <div class="p-3 bg-success bg-opacity-10 rounded-3">
                            <i class="bi bi-star-fill text-success fs-4"></i>
                        </div>
                        <div>
                            <h3 class="fw-bold mb-0">{{ $stats['shortlisted'] }}</h3>
                            <small class="text-muted">Shortlisted</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center gap-3">
                        <div class="p-3 bg-danger bg-opacity-10 rounded-3">
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
    </div>

    <!-- Search Bar -->
    <div class="card border-0 shadow-sm mb-3">
        <div class="card-body">
            <form method="GET" action="{{ route('reviewer.applications.index') }}">
                <div class="row g-3">
                    <div class="col-md-5">
                        <input type="text" class="form-control" name="search" 
                               placeholder="Search by name, email, or job title..." 
                               value="{{ request('search') }}">
                    </div>
                    <div class="col-md-2">
                        <select class="form-select" name="status">
                            <option value="">All Status</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="under_review" {{ request('status') == 'under_review' ? 'selected' : '' }}>Under Review</option>
                            <option value="shortlisted" {{ request('status') == 'shortlisted' ? 'selected' : '' }}>Shortlisted</option>
                            <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select class="form-select" name="job_id">
                            <option value="">All Jobs</option>
                            @foreach($jobs as $job)
                                <option value="{{ $job->id }}" {{ request('job_id') == $job->id ? 'selected' : '' }}>
                                    {{ $job->title }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-search"></i> Search
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Applications Table -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-0 py-3">
            <h6 class="mb-0 fw-bold">
                Applications List
                <span class="badge bg-primary ms-2">{{ $applications->total() }} Total</span>
            </h6>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Candidate</th>
                            <th>Job Position</th>
                            <th>Applied Date</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($applications as $application)
                            <tr>
                                <td>
                                    <div>
                                        <div class="fw-semibold">{{ $application->candidate->name }}</div>
                                        <small class="text-muted">{{ $application->candidate->email }}</small>
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <div class="fw-semibold">{{ $application->job->title }}</div>
                                        <small class="text-muted">{{ $application->job->department }}</small>
                                    </div>
                                </td>
                                <td>
                                    <small>{{ $application->created_at->format('M d, Y') }}</small>
                                    <br>
                                    <small class="text-muted">{{ $application->created_at->diffForHumans() }}</small>
                                </td>
                                <td>
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
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-outline-primary review-btn" 
                                            data-app-id="{{ $application->id }}">
                                        <i class="bi bi-eye me-1"></i>Review
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5">
                                    <i class="bi bi-inbox fs-1 text-muted"></i>
                                    <p class="text-muted mt-3">No applications found</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($applications->hasPages())
            <div class="card-footer bg-white py-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="text-muted small">
                        Showing {{ $applications->firstItem() }} to {{ $applications->lastItem() }} of {{ $applications->total() }}
                    </div>
                    <div>
                        {{ $applications->links() }}
                    </div>
                </div>
            </div>
        @endif
    </div>
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
    // Same modal handling script from dashboard
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
            toastContainer.innerHTML = toastHtml;
            document.body.appendChild(toastContainer);

            const toastElement = toastContainer.querySelector('.toast');
            const toast = new bootstrap.Toast(toastElement);
            toast.show();

            toastElement.addEventListener('hidden.bs.toast', () => {
                toastContainer.remove();
            });
        }
    });
</script>
@endsection