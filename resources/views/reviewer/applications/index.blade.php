@extends('layouts.apps')

@section('title', 'Application Reviews')

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
    <a href="{{ route('reviewer.applications.index', ['status' => 'assigned']) }}" class="sidebar-menu-item active">
        <i class="bi bi-inbox"></i>
        <span>Assigned to Me</span>
    </a>
    <a href="{{ route('reviewer.myprofile') }}" class="sidebar-menu-item">
        <i class="bi bi-person"></i>
        <span>My Profile</span>
    </a>
@endsection

@push('styles')
<style>
    .page-header {
        background: linear-gradient(135deg, #a07828 0%, #a07828 100%);
        border-radius: 12px;
        padding: 1.5rem;
        color: white;
        margin-bottom: 1.5rem;
        box-shadow: 0 4px 12px rgba(100, 116, 139, 0.3);
    }

    .stat-card {
        background: white;
        border-radius: 10px;
        padding: 1.25rem;
        transition: all 0.3s ease;
        border: 1px solid #e5e7eb;
        height: 100%;
    }

    .stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    }

    .application-card {
        transition: all 0.3s ease;
        border-left: 4px solid transparent;
    }

    .application-card:hover {
        background-color: #f8fafc;
        border-left-color: #64748b;
        transform: translateX(4px);
    }

    .modern-table {
        width: 100%;
        border-collapse: collapse;
        table-layout: auto;
    }

    .modern-table thead th {
        background: #f9fafb;
        padding: 0.75rem;
        font-weight: 700;
        color: #374151;
        font-size: 0.875rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border: 1px solid #e5e7eb;
        text-align: center;
        white-space: nowrap;
    }

    .modern-table tbody td {
        padding: 0.75rem;
        border: 1px solid #e5e7eb;
        vertical-align: middle;
        text-align: center;
    }

    .modern-table tbody td.nowrap { white-space: nowrap; }
    .modern-table tbody td.text-col { max-width: 200px; }
    .modern-table tbody tr { transition: all 0.2s; }
    .modern-table tbody tr:hover { background: #f8fafc; }

    .form-check-input:checked {
        background-color: #ff0000;
        border-color: #ff0000;
    }

    .application-card.selected {
        background-color: #fff5f5 !important;
        border-left-color: #ff0000 !important;
    }

    .passport-thumb {
        width: 50px;
        height: 50px;
        object-fit: cover;
        border-radius: 6px;
        border: 2px solid #e2e8f0;
    }

    #bulkActionsBar {
        display: none;
        padding: 0.85rem 1.25rem;
        border-bottom: 1px solid #e5e7eb;
        background: #ffffff;
        border-radius: 0;
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-4 py-4">

    <!-- Page Header -->
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h3 class="fw-bold mb-1">
                    <i class="bi bi-clipboard-check me-2"></i>Application Reviews
                </h3>
                <p class="mb-0 opacity-90">Review and process candidate applications</p>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="stat-card">
                <div class="d-flex align-items-center gap-3">
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
                    <div>
                        <h3 class="fw-bold mb-0">{{ $stats['assigned'] }}</h3>
                        <small class="text-muted">Assigned to Me</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="d-flex align-items-center gap-3">
                    <div>
                        <h3 class="fw-bold mb-0">{{ $stats['reviewed'] }}</h3>
                        <small class="text-muted">Reviewed</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="d-flex align-items-center gap-3">
                    <div>
                        <h3 class="fw-bold mb-0">{{ $stats['rejected'] }}</h3>
                        <small class="text-muted">Rejected</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Search and Filter -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('reviewer.applications.index') }}" id="filterForm">
                <div class="row g-3">
                    <div class="col-md-4">
                        <input type="text" name="search" class="form-control"
                               placeholder="Search candidates..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-3">
                       <select name="status" class="form-select">
                            <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>All Status</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="assigned" {{ request('status') == 'assigned' ? 'selected' : '' }}>Assigned</option>
                            <option value="reviewed" {{ request('status') == 'reviewed' ? 'selected' : '' }}>Reviewed</option>
                            <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                            <option value="edit" {{ request('status') == 'edit' ? 'selected' : '' }}>Edit</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select name="job_id" class="form-select">
                            <option value="">All Vacancies</option>
                            @foreach($jobs as $job)
                                <option value="{{ $job->id }}" {{ request('job_id') == $job->id ? 'selected' : '' }}>
                                    {{ $job->title }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-danger w-100">
                            <i class="bi bi-search me-1"></i> Search
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Applications Table Card -->
    <div class="card border-0 shadow-sm">

        <!-- Default header -->
        <div class="card-header bg-white py-3" id="tableHeaderDefault">
            <div class="d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-bold">
                    <i class="bi bi-list-ul text-dark me-2"></i>Applications List
                </h6>
                <span class="badge bg-danger">{{ $applications->total() }} Total</span>
            </div>
        </div>

        <!-- Bulk actions bar -->
        <div id="bulkActionsBar">
            <div class="d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center gap-2">
                    <span class="fw-bold text-dark">
                        <span id="selectedCount">0</span> application(s) selected
                    </span>
                    <button type="button" class="btn btn-sm btn-outline-secondary" id="clearSelectionBtn">
                        Clear Selection
                    </button>
                </div>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-sm btn-success px-3" id="exportCsvBtn">
                        Export to Excel
                    </button>
                    <button type="button" class="btn btn-sm btn-danger px-3" id="exportPdfBtn">
                        Export to PDF
                    </button>
                </div>
            </div>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive" style="overflow-x: auto;">
                <table class="modern-table table table-hover mb-0" style="min-width: 1400px;">
                    <thead>
                        <tr>
                            <th style="width: 40px;">
                                <input type="checkbox" id="selectAll" class="form-check-input">
                            </th>
                            <th>S.N.</th>
                            <th>AD No</th>
                            <th>Application ID</th>
                            <th>Photo</th>
                            <th>Candidate</th>
                            <th>Position</th>
                            <th>Department</th>
                            <th>Payment</th>
                            <th>Applied Date</th>
                            <th>Deadline</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($applications as $index => $application)
                            @php
                                $daysRemaining = $application->jobPosting
                                    ? (int) now()->diffInDays($application->jobPosting->deadline, false)
                                    : 0;

                                $statusColors = [
                                    'pending'  => 'bg-warning text-dark',
                                    'assigned' => 'bg-info',
                                    'reviewed' => 'bg-success',
                                    'rejected' => 'bg-danger',
                                ];
                                $statusColor = $statusColors[$application->status] ?? 'bg-secondary';
                            @endphp

                            <tr class="application-card">
                                <td class="text-center">
                                    <input type="checkbox"
                                           class="form-check-input application-checkbox"
                                           value="{{ $application->id }}">
                                </td>

                                <td class="nowrap">{{ $applications->firstItem() + $index }}</td>
                                <td class="nowrap">{{ $application->jobPosting->advertisement_no ?? 'N/A' }}</td>
                                <td class="nowrap">{{ $application->id }}</td>

                                <td>
                                    @if($application->passport_size_photo)
                                        <img src="{{ asset('storage/' . $application->passport_size_photo) }}"
                                             alt="Passport Photo"
                                             class="passport-thumb">
                                    @else
                                        <span class="text-muted">N/A</span>
                                    @endif
                                </td>

                                <td class="text-col">
                                    <strong class="d-block">{{ $application->name_english ?? 'N/A' }}</strong>
                                    <small class="text-muted d-block">{{ $application->name_nepali ?? '' }}</small>
                                    <small class="text-muted">{{ $application->email ?? '' }}</small>
                                </td>

                                <td class="text-col">{{ $application->jobPosting->title ?? 'N/A' }}</td>
                                <td class="text-col">{{ $application->jobPosting->department ?? 'N/A' }}</td>

                                <td class="nowrap">
                                    @php $paymentStatus = $application->payment->status ?? null; @endphp
                                    @if($paymentStatus === 'completed')
                                        <span class="badge bg-success">Completed</span>
                                    @elseif($paymentStatus)
                                        <span class="badge bg-warning text-dark">{{ ucfirst($paymentStatus) }}</span>
                                    @else
                                        <span class="text-muted">N/A</span>
                                    @endif
                                </td>

                                <td class="nowrap">
                                    @php $appliedDate = $application->submitted_at ?? $application->created_at; @endphp
                                    <strong class="text-success d-block">{{ adToBS($appliedDate) }}</strong>
                                    <small class="text-muted">{{ \Carbon\Carbon::parse($appliedDate)->format('h:i A') }}</small>
                                </td>

                                <td class="nowrap">
                                    @if($application->jobPosting && $application->jobPosting->deadline)
                                        @php
                                            $deadlineBS = $application->jobPosting->deadline_bs
                                                ?: adToBS($application->jobPosting->deadline->format('Y-m-d'));
                                        @endphp
                                        <strong class="text-danger d-block">{{ $deadlineBS }} (BS)</strong>
                                        <small class="text-muted d-block">{{ $application->jobPosting->deadline->format('M d, Y') }}</small>
                                        <small class="badge {{ $daysRemaining <= 5 ? 'bg-danger' : 'bg-secondary' }}">
                                            {{ $daysRemaining }} days left
                                        </small>
                                    @else
                                        N/A
                                    @endif
                                </td>

                                <td class="nowrap">
                                    <span class="badge {{ $statusColor }}">{{ ucfirst($application->status) }}</span>
                                </td>

                                <td class="nowrap">
                                    <a href="{{ route('reviewer.applications.show', $application->id) }}"
                                       class="btn btn-sm {{ in_array($application->status, ['rejected', 'reviewed']) ? 'btn-danger' : 'btn-warning' }}">
                                        <i class="bi {{ in_array($application->status, ['rejected', 'reviewed']) ? 'bi-check-circle' : 'bi-eye' }} me-1"></i>
                                        {{ in_array($application->status, ['rejected', 'reviewed']) ? 'View' : 'Review' }}
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="13" class="text-center py-5">
                                    <i class="bi bi-inbox display-1 text-muted"></i>
                                    <h5 class="text-muted mt-3">No Applications Found</h5>
                                    <p class="text-secondary">No applications match your criteria.</p>
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
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const selectAll = document.getElementById('selectAll');
    const checkboxes = document.querySelectorAll('.application-checkbox');
    const clearSelectionBtn = document.getElementById('clearSelectionBtn');
    const exportCsvBtn = document.getElementById('exportCsvBtn');
    const exportPdfBtn = document.getElementById('exportPdfBtn');

    if (selectAll) {
        selectAll.addEventListener('change', function () {
            toggleSelectAll(this);
        });
    }

    checkboxes.forEach(cb => {
        cb.addEventListener('change', updateBulkActions);
    });

    if (clearSelectionBtn) {
        clearSelectionBtn.addEventListener('click', clearSelection);
    }

    if (exportCsvBtn) {
        exportCsvBtn.addEventListener('click', function () {
            exportSelected('csv');
        });
    }

    if (exportPdfBtn) {
        exportPdfBtn.addEventListener('click', function () {
            exportSelected('pdf');
        });
    }

    updateBulkActions();
});

// Toggle all checkboxes
function toggleSelectAll(checkbox) {
    document.querySelectorAll('.application-checkbox').forEach(cb => {
        cb.checked = checkbox.checked;
        cb.closest('tr')?.classList.toggle('selected', checkbox.checked);
    });

    updateBulkActions();
}

// Show/hide bulk bar, swap with default header
function updateBulkActions() {
    const all = document.querySelectorAll('.application-checkbox');
    const checked = document.querySelectorAll('.application-checkbox:checked');
    const count = checked.length;

    all.forEach(cb => {
        cb.closest('tr')?.classList.toggle('selected', cb.checked);
    });

    const bulkBar = document.getElementById('bulkActionsBar');
    const defaultHeader = document.getElementById('tableHeaderDefault');
    const selectedCount = document.getElementById('selectedCount');
    const selectAll = document.getElementById('selectAll');

    if (bulkBar && defaultHeader) {
        if (count > 0) {
            bulkBar.style.display = 'block';
            defaultHeader.style.display = 'none';
        } else {
            bulkBar.style.display = 'none';
            defaultHeader.style.display = 'block';
        }
    }

    if (selectedCount) {
        selectedCount.textContent = count;
    }

    if (selectAll) {
        selectAll.checked = all.length > 0 && count === all.length;
        selectAll.indeterminate = count > 0 && count < all.length;
    }
}

// Clear all selections
function clearSelection() {
    document.querySelectorAll('.application-checkbox').forEach(cb => {
        cb.checked = false;
        cb.closest('tr')?.classList.remove('selected');
    });

    const selectAll = document.getElementById('selectAll');
    if (selectAll) {
        selectAll.checked = false;
        selectAll.indeterminate = false;
    }

    updateBulkActions();
}

// Export selected applications
function exportSelected(format) {
    const ids = Array.from(document.querySelectorAll('.application-checkbox:checked')).map(cb => cb.value);

    if (ids.length === 0) {
        alert('Please select at least one application to export.');
        return;
    }

    showExportToast(format === 'csv' ? 'Excel' : 'PDF', ids.length);

    const baseUrl = format === 'csv'
        ? '{{ route("reviewer.applications.exportCsv") }}'
        : '{{ route("reviewer.applications.exportPdf") }}';

    const params = new URLSearchParams();

    ids.forEach(id => {
        params.append('ids[]', id);
    });

    // Preserve active filters (exclude pagination)
    new URLSearchParams(window.location.search).forEach((val, key) => {
        if (key !== 'page') {
            params.append(key, val);
        }
    });

    window.location.href = `${baseUrl}?${params.toString()}`;
}

function showExportToast(format, count) {
    const toast = document.createElement('div');
    toast.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
        padding: 1rem 1.5rem;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(16,185,129,0.3);
        z-index: 9999;
        font-weight: 500;
        animation: toastIn 0.3s ease-out;
    `;
    toast.innerHTML = `<i class="bi bi-check-circle me-2"></i>Exporting ${count} application(s) to ${format}. Download starting shortly.`;

    document.body.appendChild(toast);

    setTimeout(() => {
        toast.style.animation = 'toastOut 0.3s ease-out forwards';
        setTimeout(() => toast.remove(), 300);
    }, 3000);
}

(function () {
    if (!document.getElementById('bulk-toast-animations')) {
        const animStyle = document.createElement('style');
        animStyle.id = 'bulk-toast-animations';
        animStyle.textContent = `
            @keyframes toastIn {
                from { transform: translateX(120%); opacity: 0; }
                to { transform: translateX(0); opacity: 1; }
            }
            @keyframes toastOut {
                from { transform: translateX(0); opacity: 1; }
                to { transform: translateX(120%); opacity: 0; }
            }
        `;
        document.head.appendChild(animStyle);
    }
})();
</script>
@endpush