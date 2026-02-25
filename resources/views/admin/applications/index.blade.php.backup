@extends('layouts.dashboard')

@section('title', 'Applications Management - NOC E-Recruitment')

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
    </a>
    <a href="{{ route('admin.candidates.index') }}" class="sidebar-menu-item">
        <i class="bi bi-people"></i>
        <span>Candidates</span>
    </a>
    <a href="{{ route('admin.reviewers.index') }}" class="sidebar-menu-item">
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

@section('content')
<style>
    /* Table styling with subtle borders */
    .applications-table {
        border-collapse: separate;
        border-spacing: 0;
    }
    
    .applications-table thead th {
        background-color: #f8f9fa;
        font-weight: 600;
        font-size: 0.875rem;
        padding: 1rem;
        border: 1px solid #e5e7eb;
        border-bottom: 2px solid #d1d5db;
    }
    
    .applications-table tbody td {
        padding: 1rem;
        border: 1px solid #e5e7eb;
        vertical-align: middle;
        background-color: #ffffff;
    }
    
    .applications-table tbody tr:hover td {
        background-color: #f9fafb;
    }
</style>

<div class="container-fluid px-4 py-4">

    <!-- Page Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h2 class="fw-bold mb-1" style="color: #1e3a8a;">Applications Management</h2>
                    <p class="text-muted mb-0">Nepal Oil Corporation - E-Recruitment System</p>
                </div>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-outline-primary" onclick="exportData()">
                        <i class="bi bi-download me-1"></i>Export Data
                    </button>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#bulkActionModal">
                        <i class="bi bi-collection me-1"></i>Bulk Actions
                    </button>
                </div>
            </div>

            <!-- Success/Error Messages -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i><strong>Success!</strong> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i><strong>Error!</strong> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
        </div>
    </div>

    <!-- Statistics Overview -->
    <div class="row g-3 mb-4">
        <div class="col-xl-2 col-lg-4 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center p-4">
                    <div class="mb-2">
                        <i class="bi bi-file-earmark-text text-primary" style="font-size: 2rem;"></i>
                    </div>
                    <h3 class="fw-bold mb-1" style="color: #1e3a8a;">{{ $stats['total'] }}</h3>
                    <p class="text-muted mb-0 small">Total Applications</p>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-lg-4 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center p-4">
                    <div class="mb-2">
                        <i class="bi bi-clock-history text-warning" style="font-size: 2rem;"></i>
                    </div>
                    <h3 class="fw-bold mb-1 text-warning">{{ $stats['pending'] }}</h3>
                    <p class="text-muted mb-0 small">Pending Review</p>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-lg-4 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center p-4">
                    <div class="mb-2">
                        <i class="bi bi-eye text-info" style="font-size: 2rem;"></i>
                    </div>
                    <h3 class="fw-bold mb-1 text-info">{{ $stats['under_review'] }}</h3>
                    <p class="text-muted mb-0 small">Under Review</p>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-lg-4 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center p-4">
                    <div class="mb-2">
                        <i class="bi bi-check-circle text-success" style="font-size: 2rem;"></i>
                    </div>
                    <h3 class="fw-bold mb-1 text-success">{{ $stats['shortlisted'] }}</h3>
                    <p class="text-muted mb-0 small">Shortlisted</p>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-lg-4 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center p-4">
                    <div class="mb-2">
                        <i class="bi bi-x-circle text-danger" style="font-size: 2rem;"></i>
                    </div>
                    <h3 class="fw-bold mb-1 text-danger">{{ $stats['rejected'] }}</h3>
                    <p class="text-muted mb-0 small">Rejected</p>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-lg-4 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center p-4">
                    <div class="mb-2">
                        <i class="bi bi-arrow-left-circle text-secondary" style="font-size: 2rem;"></i>
                    </div>
                    <h3 class="fw-bold mb-1 text-secondary">{{ $stats['withdrawn'] }}</h3>
                    <p class="text-muted mb-0 small">Withdrawn</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters and Search Section -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white border-bottom py-3">
            <h5 class="mb-0 fw-bold" style="color: #1e3a8a;">
                <i class="bi bi-funnel me-2"></i>Filter Applications
            </h5>
        </div>
        <div class="card-body p-4">
            <form action="{{ route('admin.applications.index') }}" method="GET">
                <div class="row g-3">
                    <div class="col-lg-3">
                        <label class="form-label fw-semibold small">Search</label>
                        <input type="text" name="search" class="form-control" 
                               placeholder="Name, email, or job title..." 
                               value="{{ request('search') }}">
                    </div>
                    <div class="col-lg-2">
                        <label class="form-label fw-semibold small">Status</label>
                        <select name="status" class="form-select">
                            <option value="">All Status</option>
                            @foreach($statuses as $status)
                                <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>
                                    {{ ucfirst(str_replace('_', ' ', $status)) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-3">
                        <label class="form-label fw-semibold small">Job Position</label>
                        <select name="job_id" class="form-select">
                            <option value="">All Positions</option>
                            @foreach($jobs as $job)
                                <option value="{{ $job->id }}" {{ request('job_id') == $job->id ? 'selected' : '' }}>
                                    {{ $job->title }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-2">
                        <label class="form-label fw-semibold small">Reviewer</label>
                        <select name="reviewer_id" class="form-select">
                            <option value="">All Reviewers</option>
                            @foreach($reviewers as $reviewer)
                                <option value="{{ $reviewer->id }}" {{ request('reviewer_id') == $reviewer->id ? 'selected' : '' }}>
                                    {{ $reviewer->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-2">
                        <label class="form-label fw-semibold small">&nbsp;</label>
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="bi bi-search"></i> Search
                            </button>
                            <a href="{{ route('admin.applications.index') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-clockwise"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Applications Table -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-bottom py-3">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold" style="color: #1e3a8a;">
                    <i class="bi bi-table me-2"></i>Applications List
                </h5>
                <span class="badge bg-primary px-3 py-2">{{ $applications->total() }} Total</span>
            </div>
        </div>
        <div class="card-body p-0">
            @if($applications->count() > 0)
                <div class="table-responsive">
                    <table class="table applications-table mb-0">
                        <thead>
                            <tr>
                                <th class="text-center" style="width: 50px;">
                                    <input type="checkbox" id="selectAll" class="form-check-input">
                                </th>
                                <th class="text-center" style="width: 60px;">#</th>
                                <th style="width: 250px;">Candidate Information</th>
                                <th style="width: 220px;">Job Applied For</th>
                                <th style="width: 180px;">Contact Details</th>
                                <th class="text-center" style="width: 140px;">Application Date</th>
                                <th style="width: 140px;">Assigned Reviewer</th>
                                <th class="text-center" style="width: 130px;">Status</th>
                                <th class="text-center" style="width: 160px;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($applications as $index => $application)
                                <tr>
                                    <td class="text-center">
                                        <input type="checkbox" name="application_ids[]" 
                                               value="{{ $application->id }}" 
                                               class="form-check-input application-checkbox">
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-light text-dark">{{ $applications->firstItem() + $index }}</span>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($application->passport_photo)
                                                <img src="{{ asset('storage/' . $application->passport_photo) }}" 
                                                     class="rounded-circle me-3"
                                                     style="width: 45px; height: 45px; object-fit: cover; border: 2px solid #e5e7eb;"
                                                     alt="Photo">
                                            @else
                                                <div class="rounded-circle me-3 d-flex align-items-center justify-content-center"
                                                     style="width: 45px; height: 45px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; font-weight: bold; border: 2px solid #e5e7eb;">
                                                    {{ strtoupper(substr($application->candidate->name, 0, 1)) }}
                                                </div>
                                            @endif
                                            <div>
                                                <div class="fw-semibold">{{ $application->candidate->name }}</div>
                                                <small class="text-muted">ID: {{ $application->candidate->id }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="fw-semibold text-dark">{{ $application->jobPosting->title }}</div>
                                        <small class="text-muted">{{ $application->jobPosting->advertisement_no }}</small>
                                    </td>
                                    <td>
                                        <div class="small mb-1">
                                            <i class="bi bi-envelope text-muted me-1"></i>
                                            <span>{{ Str::limit($application->candidate->email, 20) }}</span>
                                        </div>
                                        <div class="small">
                                            <i class="bi bi-telephone text-muted me-1"></i>
                                            <span>{{ $application->phone }}</span>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <div class="fw-semibold">{{ $application->created_at->format('M d, Y') }}</div>
                                        <small class="text-muted">{{ $application->created_at->format('h:i A') }}</small>
                                    </td>
                                    <td>
                                        @if($application->reviewer)
                                            <div class="small fw-semibold text-dark">{{ $application->reviewer->name }}</div>
                                            <small class="text-muted">{{ Str::limit($application->reviewer->email, 18) }}</small>
                                        @else
                                            <span class="badge bg-secondary">Not Assigned</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <span class="badge px-3 py-2
                                            @if($application->status == 'pending') bg-warning text-dark
                                            @elseif($application->status == 'under_review') bg-info text-white
                                            @elseif($application->status == 'shortlisted') bg-success text-white
                                            @elseif($application->status == 'rejected') bg-danger text-white
                                            @else bg-secondary text-white
                                            @endif">
                                            {{ $application->status_label }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="d-flex justify-content-center gap-1">
                                            <a href="{{ route('admin.applications.show', $application->id) }}" 
                                               class="btn btn-sm btn-outline-primary" 
                                               title="View Details"
                                               style="width: 32px; height: 32px; padding: 0; display: inline-flex; align-items: center; justify-content: center;">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <button type="button" 
                                                    class="btn btn-sm btn-outline-success" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#assignModal{{ $application->id }}"
                                                    title="Assign Reviewer"
                                                    style="width: 32px; height: 32px; padding: 0;">
                                                <i class="bi bi-person-plus"></i>
                                            </button>
                                            <button type="button" 
                                                    class="btn btn-sm btn-outline-warning" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#statusModal{{ $application->id }}"
                                                    title="Update Status"
                                                    style="width: 32px; height: 32px; padding: 0;">
                                                <i class="bi bi-pencil"></i>
                                            </button>
                                            <button type="button" 
                                                    class="btn btn-sm btn-outline-danger" 
                                                    onclick="deleteApplication({{ $application->id }})"
                                                    title="Delete"
                                                    style="width: 32px; height: 32px; padding: 0;">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>

                                <!-- Status Update Modal -->
                                <div class="modal fade" id="statusModal{{ $application->id }}" tabindex="-1">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content border-0 shadow-lg">
                                            <form action="{{ route('admin.applications.updateStatus', $application) }}" method="POST">
                                                @csrf
                                                <div class="modal-header border-bottom">
                                                    <h5 class="modal-title fw-bold">Update Application Status</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body p-4">
                                                    <div class="mb-3">
                                                        <label class="form-label fw-semibold">Select New Status</label>
                                                        <select name="status" class="form-select form-select-lg" required>
                                                            @foreach($statuses as $status)
                                                                <option value="{{ $status }}" {{ $application->status == $status ? 'selected' : '' }}>
                                                                    {{ ucfirst(str_replace('_', ' ', $status)) }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div>
                                                        <label class="form-label fw-semibold">Admin Notes (Optional)</label>
                                                        <textarea name="admin_notes" class="form-control" rows="4" 
                                                                  placeholder="Add your review notes here..."></textarea>
                                                    </div>
                                                </div>
                                                <div class="modal-footer border-top">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                    <button type="submit" class="btn btn-primary">
                                                        <i class="bi bi-check-circle me-1"></i>Update Status
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                                <!-- Assign Reviewer Modal -->
                                <div class="modal fade" id="assignModal{{ $application->id }}" tabindex="-1">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content border-0 shadow-lg">
                                            <form action="{{ route('admin.applications.assignReviewer', $application) }}" method="POST">
                                                @csrf
                                                <div class="modal-header border-bottom">
                                                    <h5 class="modal-title fw-bold">Assign Reviewer</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body p-4">
                                                    <div class="mb-3">
                                                        <label class="form-label fw-semibold">Select Reviewer</label>
                                                        <select name="reviewer_id" class="form-select form-select-lg" required>
                                                            <option value="">-- Choose Reviewer --</option>
                                                            @foreach($reviewers as $reviewer)
                                                                <option value="{{ $reviewer->id }}" {{ $application->reviewer_id == $reviewer->id ? 'selected' : '' }}>
                                                                    {{ $reviewer->name }} ({{ $reviewer->email }})
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="alert alert-info mb-0">
                                                        <i class="bi bi-info-circle me-2"></i>
                                                        <small>Application status will automatically change to "Under Review"</small>
                                                    </div>
                                                </div>
                                                <div class="modal-footer border-top">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                    <button type="submit" class="btn btn-success">
                                                        <i class="bi bi-person-check me-1"></i>Assign Reviewer
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination Footer -->
                <div class="card-footer bg-light border-top py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="text-muted">
                            Showing <strong>{{ $applications->firstItem() }}</strong> to 
                            <strong>{{ $applications->lastItem() }}</strong> of 
                            <strong>{{ $applications->total() }}</strong> applications
                        </div>
                        <div>
                            {{ $applications->links() }}
                        </div>
                    </div>
                </div>
            @else
                <!-- Empty State -->
                <div class="text-center py-5">
                    <div class="mb-4">
                        <i class="bi bi-inbox" style="font-size: 5rem; color: #d1d5db;"></i>
                    </div>
                    <h4 class="fw-bold mb-2">No Applications Found</h4>
                    <p class="text-muted mb-4">
                        @if(request()->hasAny(['search', 'status', 'job_id', 'reviewer_id']))
                            No applications match your current filter criteria.<br>
                            Try adjusting your filters or search terms.
                        @else
                            There are no job applications in the system yet.<br>
                            Applications will appear here once candidates start applying.
                        @endif
                    </p>
                    @if(request()->hasAny(['search', 'status', 'job_id', 'reviewer_id']))
                        <a href="{{ route('admin.applications.index') }}" class="btn btn-primary">
                            <i class="bi bi-arrow-clockwise me-2"></i>Clear All Filters
                        </a>
                    @endif
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Bulk Action Modal -->
<div class="modal fade" id="bulkActionModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <form id="bulkActionForm" action="{{ route('admin.applications.bulkAction') }}" method="POST">
                @csrf
                <div class="modal-header border-bottom">
                    <h5 class="modal-title fw-bold">Bulk Actions</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Select Action Type</label>
                        <select name="action" id="bulkAction" class="form-select form-select-lg" required>
                            <option value="">-- Choose Action --</option>
                            <option value="update_status">Update Status</option>
                            <option value="assign_reviewer">Assign Reviewer</option>
                            <option value="delete">Delete Applications</option>
                        </select>
                    </div>

                    <div class="mb-3 d-none" id="statusSelection">
                        <label class="form-label fw-semibold">New Status</label>
                        <select name="status" class="form-select">
                            @foreach($statuses as $status)
                                <option value="{{ $status }}">{{ ucfirst(str_replace('_', ' ', $status)) }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3 d-none" id="reviewerSelection">
                        <label class="form-label fw-semibold">Assign to Reviewer</label>
                        <select name="reviewer_id" class="form-select">
                            <option value="">-- Select Reviewer --</option>
                            @foreach($reviewers as $reviewer)
                                <option value="{{ $reviewer->id }}">{{ $reviewer->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="alert alert-warning mb-0">
                        <i class="bi bi-info-circle me-2"></i>
                        <strong id="selectedCount">0</strong> application(s) selected
                    </div>
                </div>
                <div class="modal-footer border-top">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle me-1"></i>Apply Action
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Forms -->
@foreach($applications as $application)
    <form id="deleteForm{{ $application->id }}" action="{{ route('admin.applications.destroy', $application) }}" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>
@endforeach

@endsection

@section('scripts')
<script>
    // Select All Checkbox
    document.getElementById('selectAll')?.addEventListener('change', function() {
        const checkboxes = document.querySelectorAll('.application-checkbox');
        checkboxes.forEach(checkbox => checkbox.checked = this.checked);
        updateSelectedCount();
    });

    // Individual Checkbox
    document.querySelectorAll('.application-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            updateSelectedCount();
            const allChecked = document.querySelectorAll('.application-checkbox:checked').length === 
                              document.querySelectorAll('.application-checkbox').length;
            if (document.getElementById('selectAll')) {
                document.getElementById('selectAll').checked = allChecked;
            }
        });
    });

    // Update Count
    function updateSelectedCount() {
        const count = document.querySelectorAll('.application-checkbox:checked').length;
        const countElement = document.getElementById('selectedCount');
        if (countElement) {
            countElement.textContent = count;
        }
    }

    // Bulk Action Type
    const bulkActionSelect = document.getElementById('bulkAction');
    if (bulkActionSelect) {
        bulkActionSelect.addEventListener('change', function() {
            const statusDiv = document.getElementById('statusSelection');
            const reviewerDiv = document.getElementById('reviewerSelection');
            
            if (statusDiv) statusDiv.classList.add('d-none');
            if (reviewerDiv) reviewerDiv.classList.add('d-none');
            
            if (this.value === 'update_status' && statusDiv) {
                statusDiv.classList.remove('d-none');
            } else if (this.value === 'assign_reviewer' && reviewerDiv) {
                reviewerDiv.classList.remove('d-none');
            }
        });
    }

    // Bulk Form Submit
    const bulkForm = document.getElementById('bulkActionForm');
    if (bulkForm) {
        bulkForm.addEventListener('submit', function(e) {
            const selected = document.querySelectorAll('.application-checkbox:checked');
            
            if (selected.length === 0) {
                e.preventDefault();
                alert('Please select at least one application to perform this action.');
                return false;
            }

            const action = document.getElementById('bulkAction')?.value;
            if (action === 'delete') {
                if (!confirm(`Are you sure you want to delete ${selected.length} application(s)? This action cannot be undone.`)) {
                    e.preventDefault();
                    return false;
                }
            }

            selected.forEach(checkbox => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'application_ids[]';
                input.value = checkbox.value;
                this.appendChild(input);
            });
        });
    }

    // Delete Single
    function deleteApplication(id) {
        if (confirm('Are you sure you want to permanently delete this application? This action cannot be undone.')) {
            const form = document.getElementById('deleteForm' + id);
            if (form) form.submit();
        }
    }

    // Export
    function exportData() {
        alert('Export functionality will be implemented soon. This will allow you to download applications data in Excel/CSV format.');
    }

    // Auto-dismiss alerts
    setTimeout(() => {
        const alerts = document.querySelectorAll('.alert-dismissible');
        alerts.forEach(alert => {
            const bsAlert = bootstrap.Alert.getInstance(alert) || new bootstrap.Alert(alert);
            if (bsAlert) bsAlert.close();
        });
    }, 5000);
</script>
@endsection