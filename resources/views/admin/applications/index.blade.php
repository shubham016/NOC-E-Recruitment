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

@push('styles')
<link rel="stylesheet" href="{{ asset('css/government-professional.css') }}">
<style>
    /* Modern Table - Matching Vacancy List Style */
    .modern-table {
        width: 100%;
        border-collapse: collapse;
    }

    .modern-table thead {
        background: #f9fafb;
    }

    .modern-table thead th {
        padding: 1.25rem 1.5rem;
        font-weight: 700;
        color: #000;
        font-size: 0.875rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border: 1px solid #000;
        white-space: nowrap;
        background: linear-gradient(135deg, #f9fafb 0%, #f3f4f6 100%);
        text-align: center;
    }

    .modern-table tbody td {
        padding: 1rem 1.25rem;
        color: #000;
        border: 1px solid #060606;
        vertical-align: middle;
    }

    .modern-table tbody tr {
        transition: all 0.2s;
        border-left: 3px solid transparent;
    }

    .modern-table tbody tr:hover {
        background: #f8fafc;
        border-left-color: #1e40af;
        transform: translateX(2px);
    }

    /* Status-based row styling */
    .application-row.pending {
        border-left-color: #fbbf24;
        background: linear-gradient(to right, rgba(251, 191, 36, 0.02) 0%, white 100%);
    }

    .application-row.approved {
        border-left-color: #10b981;
        background: linear-gradient(to right, rgba(16, 185, 129, 0.02) 0%, white 100%);
    }

    .application-row.rejected {
        border-left-color: #ef4444;
        background: linear-gradient(to right, rgba(239, 68, 68, 0.02) 0%, white 100%);
    }

</style>
@endpush

@section('content')
<div class="container-fluid px-4 py-4 gov-page-container">

    <!-- Professional Page Header -->
    <div class="gov-page-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="gov-page-title">Applications Management</h1>
                <p class="gov-page-subtitle">Nepal Oil Corporation - E-Recruitment System</p>
            </div>
            <div class="d-flex gap-3">
                <button type="button" class="gov-btn gov-btn-secondary" onclick="exportData()">
                    <i class="bi bi-download"></i> Export Data
                </button>
                <button type="button" class="gov-btn gov-btn-primary" data-bs-toggle="modal" data-bs-target="#bulkActionModal">
                    <i class="bi bi-check2-square"></i> Bulk Actions
                </button>
            </div>
        </div>
    </div>

    <!-- Success/Error Messages -->
    @if(session('success'))
        <div class="gov-alert gov-alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill"></i>
            <span>{{ session('success') }}</span>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="gov-alert gov-alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-circle-fill"></i>
            <span>{{ session('error') }}</span>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Statistics Cards - Modern Design -->
    <div class="row g-4 mb-4">
        <div class="col-xl-3 col-lg-3 col-md-6">
            <div class="gov-stats-card">
                <div class="gov-stats-icon" style="background: linear-gradient(135deg, #3b82f6 0%, #1e40af 100%);">
                    <i class="bi bi-file-earmark-text" style="color: white;"></i>
                </div>
                <h3 class="gov-stats-number">{{ $stats['total'] }}</h3>
                <p class="gov-stats-label">Total Applications</p>
            </div>
        </div>
        <div class="col-xl-3 col-lg-3 col-md-6">
            <div class="gov-stats-card">
                <div class="gov-stats-icon" style="background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%);">
                    <i class="bi bi-clock-history" style="color: white;"></i>
                </div>
                <h3 class="gov-stats-number">{{ $stats['pending'] }}</h3>
                <p class="gov-stats-label">Pending Review</p>
            </div>
        </div>
        <div class="col-xl-3 col-lg-3 col-md-6">
            <div class="gov-stats-card">
                <div class="gov-stats-icon" style="background: linear-gradient(135deg, #34d399 0%, #10b981 100%);">
                    <i class="bi bi-check-circle-fill" style="color: white;"></i>
                </div>
                <h3 class="gov-stats-number">{{ $stats['approved'] }}</h3>
                <p class="gov-stats-label">Approved</p>
            </div>
        </div>
        <div class="col-xl-3 col-lg-3 col-md-6">
            <div class="gov-stats-card">
                <div class="gov-stats-icon" style="background: linear-gradient(135deg, #f87171 0%, #ef4444 100%);">
                    <i class="bi bi-x-circle" style="color: white;"></i>
                </div>
                <h3 class="gov-stats-number">{{ $stats['rejected'] }}</h3>
                <p class="gov-stats-label">Rejected</p>
            </div>
        </div>
    </div>

    <!-- Filters Section -->
    <div class="gov-card">
        <div class="gov-card-header">
            <i class="bi bi-funnel"></i>
            <span>Filter Applications</span>
        </div>
        <div class="gov-card-body">
            <form action="{{ route('admin.applications.index') }}" method="GET">
                <div class="row g-3 align-items-end">
                    <div class="col-lg-3">
                        <label class="gov-form-label d-block mb-2">Search</label>
                        <input type="text" name="search" class="form-control gov-form-control"
                               placeholder="Name, email, vacancy title..."
                               value="{{ request('search') }}"
                               style="height: 45px;">
                    </div>
                    <div class="col-lg-2">
                        <label class="gov-form-label d-block mb-2">Status</label>
                        <select name="status" class="form-select" style="height: 45px;">
                            <option value="">All Status</option>
                            @foreach($statuses as $status)
                                <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>
                                    {{ ucfirst(str_replace('_', ' ', $status)) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-3">
                        <label class="gov-form-label d-block mb-2">Vacancy Position</label>
                        <select name="job_id" class="form-select" style="height: 45px;">
                            <option value="">All Positions</option>
                            @foreach($jobs as $job)
                                <option value="{{ $job->id }}" {{ request('job_id') == $job->id ? 'selected' : '' }}>
                                    {{ $job->title }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-2">
                        <label class="gov-form-label d-block mb-2">Reviewer</label>
                        <select name="reviewer_id" class="form-select" style="height: 45px;">
                            <option value="">All Reviewers</option>
                            @foreach($reviewers as $reviewer)
                                <option value="{{ $reviewer->id }}" {{ request('reviewer_id') == $reviewer->id ? 'selected' : '' }}>
                                    {{ $reviewer->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-2">
                        <label class="gov-form-label d-block mb-2">&nbsp;</label>
                        <div class="d-flex gap-2">
                            <button type="submit" class="gov-btn gov-btn-primary flex-grow-1" style="height: 45px;">
                                <i class="bi bi-search"></i> Search
                            </button>
                            <!-- <a href="{{ route('admin.applications.index') }}" class="gov-btn gov-btn-secondary" style="height: 45px; width: 45px; display: flex; align-items: center; justify-content: center; padding: 0;">
                                <i class="bi bi-arrow-clockwise"></i>
                            </a> -->
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Applications Table -->
    <div class="gov-card">
        <div class="gov-card-header">
            <div class="d-flex justify-content-between align-items-center w-100">
                <span><i class="bi bi-table"></i> Applications List</span>
                <span class="gov-badge gov-badge-primary">{{ $applications->total() }} Total</span>
            </div>
        </div>
        <div class="gov-card-body-no-padding">
            @if($applications->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0 modern-table w-100">
                        <thead class="table-light">
                            <tr>
                                <th class="text-center text-uppercase">
                                    <input type="checkbox" id="selectAll" class="form-check-input">
                                </th>
                                <th class="text-center text-uppercase">S.N.</th>
                                <th class="text-center text-uppercase">Candidate Information</th>
                                <th class="text-center text-uppercase">Vacancy Applied For</th>
                                <th class="text-center text-uppercase">Contact Details</th>
                                <th class="text-center text-uppercase">Application Date</th>
                                <th class="text-center text-uppercase">Assigned Reviewer</th>
                                <th class="text-center text-uppercase">Status</th>
                                <th class="text-center text-uppercase">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="text-center align-middle">
                            @foreach($applications as $index => $application)
                                <tr class="application-row {{ $application->status }}">
                                    <td class="text-center">
                                        <input type="checkbox" name="application_ids[]"
                                               value="{{ $application->id }}"
                                               class="form-check-input application-checkbox">
                                    </td>
                                    <td class="text-center">
                                        <span class="gov-badge gov-badge-secondary">{{ $applications->firstItem() + $index }}</span>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($application->passport_photo)
                                                <img src="{{ asset('storage/' . $application->passport_photo) }}"
                                                     class="gov-avatar me-3"
                                                     alt="Photo">
                                            @else
                                                <div class="gov-avatar-placeholder me-3">
                                                    {{ strtoupper(substr($application->candidate->name, 0, 1)) }}
                                                </div>
                                            @endif
                                            <div>
                                                <div class="gov-font-semibold gov-text-md" style="color: #1f2937;">
                                                    {{ $application->candidate->name }}
                                                </div>
                                                <small class="gov-text-sm" style="color: #6b7280;">
                                                    username: {{ $application->candidate->username }}
                                                </small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="gov-font-semibold gov-text-md" style="color: #1f2937;">
                                            {{ $application->jobPosting->title }}
                                        </div>
                                        <small class="gov-text-sm" style="color: #6b7280;">
                                            {{ $application->jobPosting->advertisement_no }}
                                        </small>
                                    </td>
                                    <td>
                                        <div class="mb-1 gov-text-sm">
                                            <i class="bi bi-envelope" style="color: #6b7280;"></i>
                                            {{ Str::limit($application->candidate->email, 22) }}
                                        </div>
                                        <div class="gov-text-sm">
                                            <i class="bi bi-telephone" style="color: #6b7280;"></i>
                                            {{ $application->phone }}
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <div class="gov-font-semibold gov-text-sm" style="color: #1f2937;">
                                            {{ $application->created_at->format('M d, Y') }}
                                        </div>
                                        <small class="gov-text-sm" style="color: #6b7280;">
                                            {{ $application->created_at->format('h:i A') }}
                                        </small>
                                    </td>
                                    <td>
                                        @if($application->reviewer)
                                            <div class="gov-font-semibold gov-text-sm" style="color: #1f2937;">
                                                {{ $application->reviewer->name }}
                                            </div>
                                            <small class="gov-text-sm" style="color: #6b7280;">
                                                {{ Str::limit($application->reviewer->email, 20) }}
                                            </small>
                                        @else
                                            <span class="gov-badge gov-badge-secondary">Not Assigned</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <span class="gov-badge
                                            @if($application->status == 'pending') gov-badge-warning
                                            @elseif($application->status == 'approved') gov-badge-success
                                            @elseif($application->status == 'rejected') gov-badge-danger
                                            @else gov-badge-secondary
                                            @endif">
                                            {{ $application->status_label }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center gap-2">
                                            <a href="{{ route('admin.applications.show', $application->id) }}"
                                               class="gov-action-btn"
                                               title="View Details">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <button type="button"
                                                    class="gov-action-btn gov-action-btn-success"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#assignModal{{ $application->id }}"
                                                    title="Assign Reviewer">
                                                <i class="bi bi-person-plus"></i>
                                            </button>
                                            <button type="button"
                                                    class="gov-action-btn"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#statusModal{{ $application->id }}"
                                                    title="Update Status">
                                                <i class="bi bi-pencil"></i>
                                            </button>
                                            <button type="button"
                                                    class="gov-action-btn gov-action-btn-danger"
                                                    onclick="deleteApplication({{ $application->id }})"
                                                    title="Delete">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>

                                <!-- Status Update Modal -->
                                <div class="modal fade" id="statusModal{{ $application->id }}" tabindex="-1">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content" style="border-radius: 14px; border: none; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);">
                                            <form action="{{ route('admin.applications.updateStatus', $application) }}" method="POST">
                                                @csrf
                                                <div class="modal-header" style="background: linear-gradient(to bottom, white 0%, #f9fafb 100%); border-bottom: 2px solid #e5e7eb; padding: 1.5rem;">
                                                    <h5 class="modal-title fw-bold" style="color: #1f2937;">
                                                        <i class="bi bi-pencil-square me-2" style="color: #1e40af;"></i>Update Application Status
                                                    </h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body p-4">
                                                    <div class="mb-3">
                                                        <label class="gov-form-label">Select New Status</label>
                                                        <select name="status" class="form-select gov-form-select" required style="height: 50px;">
                                                            @foreach($statuses as $status)
                                                                <option value="{{ $status }}" {{ $application->status == $status ? 'selected' : '' }}>
                                                                    {{ ucfirst(str_replace('_', ' ', $status)) }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div>
                                                        <label class="gov-form-label">Admin Notes (Optional)</label>
                                                        <textarea name="admin_notes" class="form-control gov-form-control" rows="4"
                                                                  placeholder="Add your review notes here..."></textarea>
                                                    </div>
                                                </div>
                                                <div class="modal-footer" style="background: linear-gradient(to top, #f9fafb 0%, white 100%); border-top: 2px solid #e5e7eb; padding: 1.25rem;">
                                                    <button type="button" class="gov-btn gov-btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                    <button type="submit" class="gov-btn gov-btn-primary">
                                                        <i class="bi bi-check-circle"></i> Update Status
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                                <!-- Assign Reviewer Modal -->
                                <div class="modal fade" id="assignModal{{ $application->id }}" tabindex="-1">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content" style="border-radius: 14px; border: none; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);">
                                            <form action="{{ route('admin.applications.assignReviewer', $application) }}" method="POST">
                                                @csrf
                                                <div class="modal-header" style="background: linear-gradient(to bottom, white 0%, #f9fafb 100%); border-bottom: 2px solid #e5e7eb; padding: 1.5rem;">
                                                    <h5 class="modal-title fw-bold" style="color: #1f2937;">
                                                        <i class="bi bi-person-plus me-2" style="color: #059669;"></i>Assign Reviewer
                                                    </h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body p-4">
                                                    <div class="mb-3">
                                                        <label class="gov-form-label">Select Reviewer</label>
                                                        <select name="reviewer_id" class="form-select gov-form-select" required style="height: 50px;">
                                                            <option value="">-- Choose Reviewer --</option>
                                                            @foreach($reviewers as $reviewer)
                                                                <option value="{{ $reviewer->id }}" {{ $application->reviewer_id == $reviewer->id ? 'selected' : '' }}>
                                                                    {{ $reviewer->name }} ({{ $reviewer->email }})
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="gov-alert gov-alert-info mb-0">
                                                        <i class="bi bi-info-circle"></i>
                                                        <small>Application status will automatically change to "Approved"</small>
                                                    </div>
                                                </div>
                                                <div class="modal-footer" style="background: linear-gradient(to top, #f9fafb 0%, white 100%); border-top: 2px solid #e5e7eb; padding: 1.25rem;">
                                                    <button type="button" class="gov-btn gov-btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                    <button type="submit" class="gov-btn gov-btn-primary">
                                                        <i class="bi bi-person-check"></i> Assign Reviewer
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
                <div class="gov-pagination-footer">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="gov-text-md" style="color: #6b7280;">
                            Showing <strong style="color: #1f2937;">{{ $applications->firstItem() }}</strong> to
                            <strong style="color: #1f2937;">{{ $applications->lastItem() }}</strong> of
                            <strong style="color: #1f2937;">{{ $applications->total() }}</strong> applications
                        </div>
                        <div>
                            {{ $applications->links() }}
                        </div>
                    </div>
                </div>
            @else
                <!-- Empty State -->
                <div class="gov-empty-state">
                    <div class="gov-empty-state-icon">
                        <i class="bi bi-inbox"></i>
                    </div>
                    <h4 class="gov-empty-state-title">No Applications Found</h4>
                    <p class="gov-empty-state-text">
                        @if(request()->hasAny(['search', 'status', 'job_id', 'reviewer_id']))
                            No applications match your current filter criteria.<br>
                            Try adjusting your filters or search terms.
                        @else
                            There are no job applications in the system yet.<br>
                            Applications will appear here once candidates start applying.
                        @endif
                    </p>
                    @if(request()->hasAny(['search', 'status', 'job_id', 'reviewer_id']))
                        <a href="{{ route('admin.applications.index') }}" class="gov-btn gov-btn-primary">
                            <i class="bi bi-arrow-clockwise"></i> Clear All Filters
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
        <div class="modal-content" style="border-radius: 14px; border: none; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);">
            <form id="bulkActionForm" action="{{ route('admin.applications.bulkAction') }}" method="POST">
                @csrf
                <div class="modal-header" style="background: linear-gradient(to bottom, white 0%, #f9fafb 100%); border-bottom: 2px solid #e5e7eb; padding: 1.5rem;">
                    <h5 class="modal-title fw-bold" style="color: #1f2937;">
                        <i class="bi bi-check2-square me-2" style="color: #1e40af;"></i>Bulk Actions
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="gov-form-label">Select Action Type</label>
                        <select name="action" id="bulkAction" class="form-select gov-form-select" required style="height: 50px;">
                            <option value="">-- Choose Action --</option>
                            <option value="update_status">Update Status</option>
                            <option value="assign_reviewer">Assign Reviewer</option>
                            <option value="delete">Delete Applications</option>
                        </select>
                    </div>

                    <div class="mb-3 d-none" id="statusSelection">
                        <label class="gov-form-label">New Status</label>
                        <select name="status" class="form-select gov-form-select">
                            @foreach($statuses as $status)
                                <option value="{{ $status }}">{{ ucfirst(str_replace('_', ' ', $status)) }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3 d-none" id="reviewerSelection">
                        <label class="gov-form-label">Assign to Reviewer</label>
                        <select name="reviewer_id" class="form-select gov-form-select">
                            <option value="">-- Select Reviewer --</option>
                            @foreach($reviewers as $reviewer)
                                <option value="{{ $reviewer->id }}">{{ $reviewer->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="gov-alert gov-alert-info mb-0">
                        <i class="bi bi-info-circle"></i>
                        <strong id="selectedCount">0</strong> application(s) selected
                    </div>
                </div>
                <div class="modal-footer" style="background: linear-gradient(to top, #f9fafb 0%, white 100%); border-top: 2px solid #e5e7eb; padding: 1.25rem;">
                    <button type="button" class="gov-btn gov-btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="gov-btn gov-btn-primary">
                        <i class="bi bi-check-circle"></i> Apply Action
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
