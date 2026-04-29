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
    @include('admin.partials.sidebar')
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('css/government-professional.css') }}">
<style>
    /* Page Header Spacing */
    .gov-page-header {
        margin-bottom: 2rem !important;
        padding: 2rem;
        background: linear-gradient(135deg, #c9a84c 0%, #a07828 100%);
        border-radius: 10px;
    }

    .gov-page-header .gov-page-title {
        color: white !important;
    }

    .gov-page-header .gov-page-subtitle {
        color: rgba(255, 255, 255, 0.9) !important;
    }

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
    }

    .modern-table tbody tr:hover {
        background: #f8fafc;
    }

    /* Compact columns for NOC Employee and Employee Code */
    .modern-table th.compact-col-noc {
        width: 90px;
        max-width: 90px;
        white-space: normal;
        word-wrap: break-word;
        padding: 0.75rem 0.5rem !important;
        line-height: 1.2;
        font-size: 0.75rem;
    }

    .modern-table td.compact-col-noc {
        width: 90px;
        max-width: 90px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        padding: 0.5rem !important;
    }

    .modern-table th.compact-col-code {
        width: 110px;
        max-width: 110px;
        white-space: normal;
        word-wrap: break-word;
        padding: 0.75rem 0.5rem !important;
        line-height: 1.2;
        font-size: 0.75rem;
    }

    .modern-table td.compact-col-code {
        width: 110px;
        max-width: 110px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        padding: 0.5rem !important;
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
            <div style="position: relative; z-index: 10;">
                <button type="button" class="btn" data-bs-toggle="modal" data-bs-target="#bulkActionModal"
                   style="border: 2px solid white; color: white; padding: 0.5rem 1.5rem; font-weight: 600; border-radius: 6px; transition: all 0.2s; cursor: pointer; background: transparent;">
                    <i class="bi bi-lightning-fill me-1"></i> Bulk Actions
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
                <h3 class="gov-stats-number">{{ $stats['approved'] ?? 0 }}</h3>
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
                            @foreach($vacancies as $vacancy)
                                <option value="{{ $vacancy->id }}" {{ request('job_id') == $vacancy->id ? 'selected' : '' }}>
                                    {{ $vacancy->title }}
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

    <!-- Bulk Actions Bar -->
    <div id="bulkActionsBar" class="gov-card" style="display: none; margin-bottom: 1.5rem;">
        <div class="gov-card-body">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <span class="fw-bold text-dark me-3">
                        <i class="bi bi-check-square me-2"></i>
                        <span id="selectedCount">0</span> application(s) selected
                    </span>
                    <button type="button" class="btn btn-sm btn-outline-secondary" onclick="clearSelection()">
                        <i class="bi bi-x-circle me-1"></i>Clear Selection
                    </button>
                </div>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-sm btn-success" onclick="exportSelected('csv')">
                        <i class="bi bi-file-earmark-excel me-1"></i>Export to Excel
                    </button>
                    <button type="button" class="btn btn-sm btn-danger" onclick="exportSelected('pdf')">
                        <i class="bi bi-file-earmark-pdf me-1"></i>Export to PDF
                    </button>
                </div>
            </div>
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
                                <th class="text-center text-uppercase">S.N</th>
                                <th class="text-center text-uppercase">Candidate Information</th>
                                <th class="text-center text-uppercase">Vacancy Applied For</th>
                                <th class="text-center text-uppercase">Contact Details</th>
                                <th class="text-center text-uppercase">Application Date</th>
                                <th class="text-center text-uppercase compact-col-noc">NOC Employee</th>
                                <th class="text-center text-uppercase compact-col-code">Employee Code</th>
                                <th class="text-center text-uppercase">Assigned Reviewer</th>
                                <th class="text-center text-uppercase">Assigned Approver</th>
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
                                            @if($application->passport_size_photo)
                                                <img src="{{ asset('storage/' . $application->passport_size_photo) }}"
                                                     class="gov-avatar me-3"
                                                     alt="Photo">
                                            @else
                                                <div class="gov-avatar-placeholder me-3">
                                                    {{ strtoupper(substr($application->name_english ?? '?', 0, 1)) }}
                                                </div>
                                            @endif
                                            <div>
                                                <div class="gov-font-semibold gov-text-md" style="color: #1f2937;">
                                                    {{ $application->name_english ?? '-' }}
                                                </div>
                                                <small class="gov-text-sm" style="color: #6b7280;">
                                                    Application ID: {{ $application->id }}
                                                </small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="gov-font-semibold gov-text-md" style="color: #1f2937;">
                                            {{ $application->applying_position ?? '-' }}
                                        </div>
                                        <small class="gov-text-sm" style="color: #6b7280;">
                                            {{ $application->advertisement_no ?? '-' }}
                                        </small>
                                    </td>
                                    <td>
                                        <div class="gov-text-sm d-flex align-items-center" style="line-height: 1.6; margin-bottom: 0.25rem;">
                                            <i class="bi bi-envelope me-2" style="color: #6b7280; flex-shrink: 0;"></i>
                                            <span>{{ Str::limit(trim($application->email ?? '-'), 22) }}</span>
                                        </div>
                                        <div class="gov-text-sm d-flex align-items-center" style="line-height: 1.6;">
                                            <i class="bi bi-telephone me-2" style="color: #6b7280; flex-shrink: 0;"></i>
                                            <span>{{ trim($application->phone ?? '-') }}</span>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <div class="gov-font-semibold gov-text-sm" style="color: #1f2937;">
                                            {{ adToBS($application->submitted_at ?? $application->created_at) }}
                                        </div>
                                        <small class="gov-text-sm" style="color: #6b7280;">
                                            {{ ($application->submitted_at ?? $application->created_at)->format('h:i A') }}
                                        </small>
                                    </td>
                                    <td class="compact-col-noc">
                                        {{ $application->noc_employee == 'yes' ? 'Yes' : 'No' }}
                                    </td>
                                    <td class="compact-col-code">
                                        @if($application->noc_employee == 'yes' && $application->noc_id_card)
                                            <a href="{{ asset('storage/' . $application->noc_id_card) }}" target="_blank" class="text-primary" title="View NOC ID Card">
                                                <i class="bi bi-file-earmark-text"></i> View
                                            </a>
                                        @else
                                            -
                                        @endif
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
                                    <td>
                                        @if($application->approver)
                                            <div class="gov-font-semibold gov-text-sm" style="color: #1f2937;">
                                                {{ $application->approver->name }}
                                            </div>
                                            <small class="gov-text-sm" style="color: #6b7280;">
                                                {{ Str::limit($application->approver->email, 20) }}
                                            </small>
                                        @else
                                            <span class="gov-badge gov-badge-secondary">Not Assigned</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <div class="gov-font-semibold gov-text-sm" style="color: #1f2937; font-weight: 600;">
                                            {{ $application->status_label }}
                                        </div>
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
                                                    data-bs-target="#assignApproverModal{{ $application->id }}"
                                                    title="Assign Approver"
                                                    style="background-color: #7c3aed; color: #fff; border-color: #7c3aed;">
                                                <i class="bi bi-person-check-fill"></i>
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

                                <!-- Assign Approver Modal -->
                                <div class="modal fade" id="assignApproverModal{{ $application->id }}" tabindex="-1">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content" style="border-radius: 14px; border: none; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);">
                                            <form action="{{ route('admin.applications.assignApprover', $application) }}" method="POST">
                                                @csrf
                                                <div class="modal-header" style="background: linear-gradient(to bottom, white 0%, #f9fafb 100%); border-bottom: 2px solid #e5e7eb; padding: 1.5rem;">
                                                    <h5 class="modal-title fw-bold" style="color: #1f2937;">
                                                        <i class="bi bi-person-check-fill me-2" style="color: #7c3aed;"></i>Assign Approver
                                                    </h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body p-4">
                                                    <div class="mb-3">
                                                        <label class="gov-form-label">Select Approver</label>
                                                        <select name="approver_id" class="form-select gov-form-select" required style="height: 50px;">
                                                            <option value="">-- Choose Approver --</option>
                                                            @foreach($approvers as $approver)
                                                                <option value="{{ $approver->id }}" {{ $application->approver_id == $approver->id ? 'selected' : '' }}>
                                                                    {{ $approver->name }} ({{ $approver->email }})
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="modal-footer" style="background: linear-gradient(to top, #f9fafb 0%, white 100%); border-top: 2px solid #e5e7eb; padding: 1.25rem;">
                                                    <button type="button" class="gov-btn gov-btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                    <button type="submit" class="gov-btn gov-btn-primary" style="background-color: #7c3aed; border-color: #7c3aed;">
                                                        <i class="bi bi-person-check-fill"></i> Assign Approver
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
                    @if($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong>Validation Error:</strong>
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <div class="mb-3">
                        <label class="gov-form-label">Select Action Type</label>
                        <select name="action" id="bulkAction" class="form-select gov-form-select" required style="height: 50px;">
                            <option value="">-- Choose Action --</option>
                            <option value="update_status">Update Status</option>
                            <option value="assign_reviewer">Assign Reviewer</option>
                            <option value="assign_approver">Assign Approver</option>
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
                                <option value="{{ $reviewer->id }}">{{ $reviewer->name }} ({{ $reviewer->email }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3 d-none" id="approverSelection">
                        <label class="gov-form-label">Assign to Approver</label>
                        <select name="approver_id" class="form-select gov-form-select">
                            <option value="">-- Select Approver --</option>
                            @foreach($approvers as $approver)
                                <option value="{{ $approver->id }}">{{ $approver->name }} ({{ $approver->email }})</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Advertisement Number assignment — shown when reviewer or approver is selected --}}
                    <div class="mb-3 d-none" id="advertisementSelection">
                        <hr class="my-3">
                        <label class="gov-form-label fw-bold">Assign by Advertisement Number</label>
                        <small class="d-block text-muted mb-2">Select an advertisement to assign <strong>all its applications</strong> to the person above.</small>
                        <select name="job_posting_id" id="jobPostingSelect" class="form-select gov-form-select">
                            <option value="">-- Select Advertisement Number --</option>
                            @foreach($vacancies as $vacancy)
                                <option value="{{ $vacancy->id }}" data-count="{{ $vacancy->applications_count }}">
                                    {{ $vacancy->advertisement_no }} - {{ $vacancy->title }}{{ $vacancy->level ? ' - Level ' . $vacancy->level : '' }}
                                </option>
                            @endforeach
                        </select>
                        <small class="text-muted mt-1 d-block">Leave blank to use checkbox selection instead.</small>
                    </div>

                    <div class="gov-alert gov-alert-info mb-0" id="modalInfoAlert">
                        <i class="bi bi-info-circle"></i>
                        <span id="modalInfoText"><span id="modalSelectedCount">0</span> application(s) selected via checkboxes</span>
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
    // Function to open bulk action modal
    function openBulkActionModal() {
        console.log('openBulkActionModal() called');
        const modalElement = document.getElementById('bulkActionModal');
        if (modalElement) {
            try {
                const modal = new bootstrap.Modal(modalElement);
                modal.show();
                console.log('✓ Modal opened via openBulkActionModal()');
                return true;
            } catch (error) {
                console.error('✗ Error opening modal:', error);
                return false;
            }
        } else {
            console.error('✗ Modal element not found!');
            return false;
        }
    }

    // Debug: Check if Bootstrap is loaded
    console.log('Bootstrap loaded:', typeof bootstrap !== 'undefined');
    console.log('Modal element exists:', document.getElementById('bulkActionModal') !== null);

    // Test modal opening on page load
    document.addEventListener('DOMContentLoaded', function() {
        const modalElement = document.getElementById('bulkActionModal');

        // IMPORTANT: Force close any open modals on page load to prevent blocking
        if (modalElement) {
            // Remove show class and hide modal if it's open
            modalElement.classList.remove('show');
            modalElement.style.display = 'none';
            modalElement.setAttribute('aria-hidden', 'true');
            modalElement.removeAttribute('aria-modal');

            // Remove modal backdrop if exists
            const backdrop = document.querySelector('.modal-backdrop');
            if (backdrop) {
                backdrop.remove();
            }

            // Remove modal-open class from body
            document.body.classList.remove('modal-open');
            document.body.style.overflow = '';
            document.body.style.paddingRight = '';

            console.log('✓ Bulk Action Modal found and ensured closed');
        }

        if (modalElement) {
            console.log('✓ Bulk Action Modal ready');

            // Update count when modal is shown
            modalElement.addEventListener('show.bs.modal', function() {
                const count = document.querySelectorAll('.application-checkbox:checked').length;
                const modalCountElement = document.getElementById('modalSelectedCount');
                if (modalCountElement) {
                    modalCountElement.textContent = count;
                }
                console.log('Modal opening with', count, 'applications selected');
            });
        } else {
            console.error('✗ Bulk Action Modal NOT found');
        }



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

    // Update Count and Show/Hide Bulk Actions Bar
    function updateSelectedCount() {
        const count = document.querySelectorAll('.application-checkbox:checked').length;
        const countElement = document.getElementById('selectedCount');
        const bulkActionsBar = document.getElementById('bulkActionsBar');

        if (countElement) {
            countElement.textContent = count;
        }

        // Only update modal info if no advertisement number is selected
        const jobPostingSelect = document.getElementById('jobPostingSelect');
        if (!jobPostingSelect || !jobPostingSelect.value) {
            const infoText = document.getElementById('modalInfoText');
            if (infoText) {
                infoText.innerHTML = '<span id="modalSelectedCount">' + count + '</span> application(s) selected via checkboxes';
            }
        }

        // Show/hide bulk actions bar based on selection
        if (bulkActionsBar) {
            if (count > 0) {
                bulkActionsBar.style.display = 'block';
            } else {
                bulkActionsBar.style.display = 'none';
            }
        }
    }

    // Clear Selection
    function clearSelection() {
        document.querySelectorAll('.application-checkbox:checked').forEach(cb => {
            cb.checked = false;
        });
        if (document.getElementById('selectAll')) {
            document.getElementById('selectAll').checked = false;
        }
        updateSelectedCount();
    }

    // Export Selected Applications
    function exportSelected(type) {
        const selected = [];

        document.querySelectorAll('.application-checkbox:checked').forEach(cb => {
            selected.push(cb.value);
        });

        if (selected.length === 0) {
            alert('Please select at least one application to export.');
            return;
        }

        // Use the existing export route with type and selected IDs
        let url = "{{ route('admin.applications.export') }}";
        url += '?type=' + type + '&ids=' + selected.join(',');

        window.location.href = url;
    }

    // Make functions globally accessible
    window.clearSelection = clearSelection;
    window.exportSelected = exportSelected;

    // Bulk Action Type
    const bulkActionSelect = document.getElementById('bulkAction');
    if (bulkActionSelect) {
        bulkActionSelect.addEventListener('change', function() {
            const statusDiv      = document.getElementById('statusSelection');
            const reviewerDiv    = document.getElementById('reviewerSelection');
            const approverDiv    = document.getElementById('approverSelection');
            const advertisementDiv = document.getElementById('advertisementSelection');

            // Hide all sections first
            if (statusDiv)        statusDiv.classList.add('d-none');
            if (reviewerDiv)      reviewerDiv.classList.add('d-none');
            if (approverDiv)      approverDiv.classList.add('d-none');
            if (advertisementDiv) advertisementDiv.classList.add('d-none');

            // Reset advertisement dropdown and info text
            const jobPostingSelect = document.getElementById('jobPostingSelect');
            if (jobPostingSelect) jobPostingSelect.value = '';
            updateModalInfoText();

            // Show relevant section based on action
            if (this.value === 'update_status') {
                if (statusDiv) statusDiv.classList.remove('d-none');
            } else if (this.value === 'assign_reviewer') {
                if (reviewerDiv)      reviewerDiv.classList.remove('d-none');
                if (advertisementDiv) advertisementDiv.classList.remove('d-none');
            } else if (this.value === 'assign_approver') {
                if (approverDiv)      approverDiv.classList.remove('d-none');
                if (advertisementDiv) advertisementDiv.classList.remove('d-none');
            }
        });
    }

    // Update the info alert based on advertisement selection vs checkbox selection
    function updateModalInfoText() {
        const jobPostingSelect   = document.getElementById('jobPostingSelect');
        const infoText           = document.getElementById('modalInfoText');
        const bulkActionVal      = document.getElementById('bulkAction')?.value;
        if (!infoText) return;

        const selectedOption = jobPostingSelect ? jobPostingSelect.options[jobPostingSelect.selectedIndex] : null;
        const jobCount       = selectedOption && selectedOption.value ? parseInt(selectedOption.dataset.count || 0) : null;

        // Get the selected reviewer or approver name
        let assigneeName = '';
        if (bulkActionVal === 'assign_reviewer') {
            const reviewerSel = document.querySelector('#reviewerSelection select');
            const reviewerOpt = reviewerSel ? reviewerSel.options[reviewerSel.selectedIndex] : null;
            if (reviewerOpt && reviewerOpt.value) assigneeName = reviewerOpt.text;
        } else if (bulkActionVal === 'assign_approver') {
            const approverSel = document.querySelector('#approverSelection select');
            const approverOpt = approverSel ? approverSel.options[approverSel.selectedIndex] : null;
            if (approverOpt && approverOpt.value) assigneeName = approverOpt.text;
        }

        if (jobCount !== null) {
            const advLabel = selectedOption.text.trim();
            let msg = '<strong>' + jobCount + '</strong> applications will be assigned from advertisement <strong>' + advLabel + '</strong>';
            if (assigneeName) msg += ' to <strong>' + assigneeName + '</strong>';
            infoText.innerHTML = msg;
        } else {
            const checkboxCount = document.querySelectorAll('.application-checkbox:checked').length;
            let msg = '<span id="modalSelectedCount">' + checkboxCount + '</span> applications selected via checkboxes';
            if (assigneeName) msg += ' — will be assigned to <strong>' + assigneeName + '</strong>';
            infoText.innerHTML = msg;
        }
    }

    // Wire advertisement, reviewer, and approver dropdowns to update info text
    document.getElementById('jobPostingSelect')?.addEventListener('change', updateModalInfoText);
    document.querySelector('#reviewerSelection select')?.addEventListener('change', updateModalInfoText);
    document.querySelector('#approverSelection select')?.addEventListener('change', updateModalInfoText);

    // Bulk Form Submit
    const bulkForm = document.getElementById('bulkActionForm');
    if (bulkForm) {
        bulkForm.addEventListener('submit', function(e) {
            const action         = document.getElementById('bulkAction')?.value;
            const jobPostingId   = document.getElementById('jobPostingSelect')?.value;
            const selected       = document.querySelectorAll('.application-checkbox:checked');

            // For assign actions: either an advertisement number OR checkbox selection is required
            if ((action === 'assign_reviewer' || action === 'assign_approver')) {
                if (!jobPostingId && selected.length === 0) {
                    e.preventDefault();
                    alert('Please select an advertisement number OR check at least one application.');
                    return false;
                }
                // If advertisement number chosen, no need to append checkbox IDs
                if (jobPostingId) {
                    return true; // job_posting_id is already in the form as a named select
                }
            }

            // For update_status: checkboxes required
            if (action === 'update_status' && selected.length === 0) {
                e.preventDefault();
                alert('Please select at least one application to update status.');
                return false;
            }

            // Append checked checkbox IDs as hidden inputs
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

    // Export Data with current filters
    function exportData() {
        console.log('Exporting data with current filters...');

        // Get current filter values from the form
        const search = document.querySelector('input[name="search"]')?.value || '';
        const status = document.querySelector('select[name="status"]')?.value || '';
        const jobId = document.querySelector('select[name="job_id"]')?.value || '';
        const reviewerId = document.querySelector('select[name="reviewer_id"]')?.value || '';
        const dateFrom = document.querySelector('input[name="date_from"]')?.value || '';
        const dateTo = document.querySelector('input[name="date_to"]')?.value || '';
        const sortBy = '{{ request("sort_by", "created_at") }}';
        const sortOrder = '{{ request("sort_order", "desc") }}';

        // Build export URL with filters
        const exportUrl = new URL('{{ route("admin.applications.export") }}', window.location.origin);

        if (search) exportUrl.searchParams.append('search', search);
        if (status) exportUrl.searchParams.append('status', status);
        if (jobId) exportUrl.searchParams.append('job_id', jobId);
        if (reviewerId) exportUrl.searchParams.append('reviewer_id', reviewerId);
        if (dateFrom) exportUrl.searchParams.append('date_from', dateFrom);
        if (dateTo) exportUrl.searchParams.append('date_to', dateTo);
        exportUrl.searchParams.append('sort_by', sortBy);
        exportUrl.searchParams.append('sort_order', sortOrder);

        console.log('Export URL:', exportUrl.toString());

        // Show loading indicator
        const btn = document.getElementById('exportDataButton');
        const originalHtml = btn.innerHTML;
        btn.innerHTML = '<i class="bi bi-hourglass-split"></i> Exporting...';
        btn.disabled = true;

        // Redirect to export URL (will download file)
        window.location.href = exportUrl.toString();

        // Reset button after delay
        setTimeout(() => {
            btn.innerHTML = originalHtml;
            btn.disabled = false;
        }, 3000);
    }

    // Auto-dismiss alerts
    setTimeout(() => {
        const alerts = document.querySelectorAll('.alert-dismissible');
        alerts.forEach(alert => {
            const bsAlert = bootstrap.Alert.getInstance(alert) || new bootstrap.Alert(alert);
            if (bsAlert) bsAlert.close();
        });
    }, 5000);

    // Reopen bulk action modal if there are validation errors (only after modal cleanup)
    @if($errors->any())
        setTimeout(() => {
            const modalEl = document.getElementById('bulkActionModal');
            if (modalEl) {
                const bulkModal = new bootstrap.Modal(modalEl);
                bulkModal.show();
                console.log('ℹ️ Modal reopened due to validation errors');
            }
        }, 600); // Wait for cleanup to complete
    @endif

    // Debug: Log form submission
    document.getElementById('bulkActionForm')?.addEventListener('submit', function(e) {
        console.log('Form submitting with action:', document.getElementById('bulkAction')?.value);
        console.log('Selected applications:', document.querySelectorAll('.application-checkbox:checked').length);
    });

    }); // end DOMContentLoaded
</script>
@endsection
