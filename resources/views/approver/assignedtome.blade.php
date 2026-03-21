@extends('layouts.app')

@section('title', 'Application Approver')

@section('portal-name', 'approver Portal')
@section('brand-icon', 'bi bi-clipboard-check')
@section('dashboard-route', route('approver.dashboard'))
@section('user-name', Auth::guard('approver')->user()->name)
@section('user-role', 'Application approver')
@section('user-initial', strtoupper(substr(Auth::guard('approver')->user()->name, 0, 1)))
@section('logout-route', route('approver.logout'))

@section('sidebar-menu')
    <a href="{{ route('approver.dashboard') }}" class="sidebar-menu-item">
        <i class="bi bi-speedometer2"></i>
        <span>Dashboard</span>
    </a>
    <a href="{{ route('approver.assignedtome', ['status' => 'assigned']) }}" class="sidebar-menu-item active">
        <i class="bi bi-inbox"></i>
        <span>Assigned to Me</span>
    </a>
@endsection

@section('custom-styles')
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

    .priority-high {
        border-left-color: #ef4444 !important;
        background: linear-gradient(to right, rgba(239, 68, 68, 0.02) 0%, white 100%);
    }

    .priority-medium {
        border-left-color: #f59e0b !important;
        background: linear-gradient(to right, rgba(245, 158, 11, 0.02) 0%, white 100%);
    }

    .priority-low {
        border-left-color: #10b981 !important;
        background: linear-gradient(to right, rgba(16, 185, 129, 0.02) 0%, white 100%);
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

    .modern-table tbody td.nowrap {
        white-space: nowrap;
    }

    .modern-table tbody td.text-col {
        max-width: 200px;
    }

    .modern-table tbody tr {
        transition: all 0.2s;
    }

    .modern-table tbody tr:hover {
        background: #f8fafc;
    }

    #bulkActionsBar {
        background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
        border-left: 4px solid #ff0000;
    }

    #bulkActionsBar .card-body {
        background: white;
        border-radius: 8px;
    }

    .form-check-input:checked {
        background-color: #ff0000;
        border-color: #ff0000;
    }

    .application-card.selected {
        background-color: #eff6ff !important;
        border-left-color: #ff0000 !important;
    }
</style>
@endsection

@section('content')
<div class="container-fluid px-4 py-4">
    <!-- Page Header -->
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h3 class="fw-bold mb-1">
                    <i class="bi me-2"></i>Application Approves
                </h3>
                <p class="mb-0 opacity-90">Approve and process candidate applications</p>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="stat-card">
                <div class="d-flex align-items-center gap-3">
                    <div>
                        <h3 class="fw-bold mb-0"></h3>
                        <small class="text-muted">Total Applications</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="d-flex align-items-center gap-3">
                    <div>
                        <h3 class="fw-bold mb-0"></h3>
                        <small class="text-muted">Assigned to Me</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="d-flex align-items-center gap-3">
                    <div>
                        <h3 class="fw-bold mb-0"></h3>
                        <small class="text-muted">Approved</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="d-flex align-items-center gap-3">
                    <div>
                        <h3 class="fw-bold mb-0"></h3>
                        <small class="text-muted">Rejected</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Search and Filter -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('approver.assignedtome') }}" id="filterForm">
                <div class="row g-3">
                    <div class="col-md-4">
                        <input type="text" name="search" class="form-control" placeholder="Search candidates..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-3">
                        <select name="status" class="form-select">
                            <option value="">All Status</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="assigned" {{ request('status') == 'assigned' ? 'selected' : '' }}>Assigned</option>
                            <option value="reviewed" {{ request('status') == 'reviewed' ? 'selected' : '' }}>Reviewed</option>
                            <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                            <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
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
                            <i class="me-1"></i> Search
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Bulk Actions Bar (Hidden by default, shown when items selected) -->
    <div id="bulkActionsBar" class="card border-0 shadow-sm mb-3" style="display: none;">
        <div class="card-body py-3">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <span class="fw-bold text-dark me-3">
                        <i class="bi me-2"></i>
                        <span id="selectedCount">0</span> application(s) selected
                    </span>
                    <button type="button" class="btn btn-sm btn-outline-secondary" onclick="clearSelection()">
                        <i class="bi me-1"></i>Clear Selection
                    </button>
                </div>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-sm btn-success" onclick="exportSelected('csv')">
                        <i class="bi me-1"></i>Export to Excel
                    </button>
                    <button type="button" class="btn btn-sm btn-danger" onclick="exportSelected('pdf')">
                        <i class="bi me-1"></i>Export to PDF
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Applications Table -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3">
            <div class="d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-bold">
                    <i class="text-dark me-2"></i>Applications List
                </h6>
                <span class="badge bg-danger">{{ $applications->total() }} Total</span>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive" style="overflow-x: auto;">
                <table class="modern-table table table-hover mb-0" style="min-width: 1200px;">
                    <thead>
                        <tr>
                            <th style="width: 40px;">
                                <input type="checkbox" id="selectAll" class="form-check-input" onchange="toggleSelectAll(this)">
                            </th>
                            <th>S.N.</th>
                            <th>Candidate</th>
                            <th>Position</th>
                            <th>Department</th>
                            <th>Applied Date</th>
                            <th>Deadline</th>
                            <th>Priority</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($applications as $index => $application)
                            @php
                                $daysRemaining = $application->jobPosting ? (int) now()->diffInDays($application->jobPosting->deadline, false) : 0;

                                // Check if admin set manual priority
                                if ($application->manual_priority) {
                                    $priorityClass = 'priority-' . $application->manual_priority;
                                    $priorityText = ucfirst($application->manual_priority);
                                    $priorityBadge = match($application->manual_priority) {
                                        'critical' => 'bg-dark',
                                        'high' => 'bg-danger',
                                        'medium' => 'bg-warning',
                                        'low' => 'bg-success',
                                        'normal' => 'bg-secondary',
                                        default => 'bg-secondary'
                                    };
                                } else {
                                    // Auto priority based on deadline
                                    $priorityClass = '';
                                    $priorityBadge = 'bg-secondary';
                                    $priorityText = 'Normal';

                                    if ($daysRemaining <= 2) {
                                        $priorityClass = 'priority-high';
                                        $priorityBadge = 'bg-danger';
                                        $priorityText = 'High';
                                    } elseif ($daysRemaining <= 5) {
                                        $priorityClass = 'priority-medium';
                                        $priorityBadge = 'bg-warning';
                                        $priorityText = 'Medium';
                                    } elseif ($daysRemaining <= 10) {
                                        $priorityClass = 'priority-low';
                                        $priorityBadge = 'bg-success';
                                        $priorityText = 'Low';
                                    }
                                }

                                $statusColors = [
                                    'pending' => 'bg-warning text-dark',
                                    'assigned' => 'bg-info',
                                    'reviewed' => 'bg-success',
                                    'approved' => 'bg-success',
                                    'rejected' => 'bg-danger',
                                ];
                                $statusColor = $statusColors[$application->status] ?? 'bg-secondary';
                            @endphp
                            <tr class="application-card {{ $priorityClass }}">
                                <td class="text-center">
                                    <input type="checkbox" class="form-check-input application-checkbox"
                                           value="{{ $application->id }}"
                                           onchange="updateBulkActions()">
                                </td>
                                <td class="nowrap">{{ $applications->firstItem() + $index }}</td>
                                <td class="text-col">
                                    <strong class="d-block">{{ $application->name_english ?? 'N/A' }}</strong>
                                    <small class="text-muted">{{ $application->email ?? 'N/A' }}</small>
                                </td>
                                <td class="text-col">{{ $application->jobPosting->title ?? 'N/A' }}</td>
                                <td class="text-col">{{ $application->jobPosting->department ?? 'N/A' }}</td>
                                <td class="nowrap">
                                    <strong class="text-success d-block">{{ adToBS($application->submitted_at ?? $application->created_at) }}</strong>
                                    <small class="text-muted">{{ ($application->submitted_at ?? $application->created_at)->format('h:i A') }}</small>
                                </td>
                                <td class="nowrap">
                                    @if($application->jobPosting)
                                        <strong class="text-danger d-block">{{ $application->jobPosting->deadline->format('M d, Y') }}</strong>
                                        @if($application->jobPosting->deadline_bs)
                                            <small class="text-muted d-block">{{ $application->jobPosting->deadline_bs }} (BS)</small>
                                        @endif
                                        <small class="badge {{ $daysRemaining <= 5 ? 'bg-danger' : 'bg-secondary' }}">
                                            {{ $daysRemaining }} days left
                                        </small>
                                    @else
                                        N/A
                                    @endif
                                </td>
                                <td class="nowrap">
                                    @if($application->manual_priority)
                                        <span class="badge {{ $priorityBadge }}">
                                            {{ $priorityText }}
                                        </span>
                                        @if($application->priority_note)
                                            <br>
                                            <small class="text-muted" title="{{ $application->priority_note }}">
                                                
                                            </small>
                                        @endif
                                    @else
                                        <span class="badge {{ $priorityBadge }}">{{ $priorityText }}</span>
                                        <br>
                                        <small class="text-muted">(Auto)</small>
                                    @endif
                                </td>
                                <td class="nowrap">
                                    <span class="badge {{ $statusColor }}">{{ ucfirst($application->status) }}</span>
                                </td>
                                <td class="nowrap">
                                    <a href="{{ route('approver.applications.show', $application->id) }}" class="btn btn-sm btn-danger">
                                        <i class="bi"></i> Review
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="text-center py-5">
                                    <i class="display-1 text-muted"></i>
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

@section('scripts')
<script>
// Toggle select all checkboxes
function toggleSelectAll(checkbox) {
    const checkboxes = document.querySelectorAll('.application-checkbox');
    checkboxes.forEach(cb => {
        cb.checked = checkbox.checked;
        // Highlight selected rows
        const row = cb.closest('tr');
        if (checkbox.checked) {
            row.classList.add('selected');
        } else {
            row.classList.remove('selected');
        }
    });
    updateBulkActions();
}

// Update bulk actions bar visibility and count
function updateBulkActions() {
    const checkboxes = document.querySelectorAll('.application-checkbox');
    const checkedBoxes = document.querySelectorAll('.application-checkbox:checked');
    const count = checkedBoxes.length;
    const bulkActionsBar = document.getElementById('bulkActionsBar');
    const selectedCount = document.getElementById('selectedCount');

    // Highlight/unhighlight rows based on checkbox state
    checkboxes.forEach(cb => {
        const row = cb.closest('tr');
        if (cb.checked) {
            row.classList.add('selected');
        } else {
            row.classList.remove('selected');
        }
    });

    if (count > 0) {
        bulkActionsBar.style.display = 'block';
        selectedCount.textContent = count;
    } else {
        bulkActionsBar.style.display = 'none';
        document.getElementById('selectAll').checked = false;
    }

    // Update select all checkbox state
    const allChecked = checkboxes.length > 0 && count === checkboxes.length;
    document.getElementById('selectAll').checked = allChecked;
}

// Clear all selections
function clearSelection() {
    document.querySelectorAll('.application-checkbox').forEach(cb => {
        cb.checked = false;
    });
    document.getElementById('selectAll').checked = false;
    updateBulkActions();
}

// Export selected applications
function exportSelected(format) {
    const checkboxes = document.querySelectorAll('.application-checkbox:checked');
    const ids = Array.from(checkboxes).map(cb => cb.value);

    if (ids.length === 0) {
        alert('⚠️ Please select at least one application to export.');
        return;
    }

    // Show toast notification
    showExportMessage(format === 'csv' ? 'Excel' : 'PDF', ids.length);

    // Build URL with selected IDs
    const baseUrl = format === 'csv'
        ? '{{ route("approver.applications.exportCsv") }}'
        : '{{ route("approver.applications.exportPdf") }}';

    const params = new URLSearchParams();
    ids.forEach(id => params.append('ids[]', id));

    // Add current filters to maintain context
    const currentParams = new URLSearchParams(window.location.search);
    for (let [key, value] of currentParams) {
        if (key !== 'page') {
            params.append(key, value);
        }
    }

    // Trigger download
    window.location.href = ${baseUrl}?${params.toString()};
}

function showExportMessage(format, count) {
    const toast = document.createElement('div');
    toast.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
        padding: 1rem 1.5rem;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
        z-index: 9999;
        font-weight: 500;
        animation: slideIn 0.3s ease-out;
    `;
    toast.innerHTML = <i class="me-2"></i> Exporting ${count} application(s) to ${format}... Download will start shortly.;

    document.body.appendChild(toast);

    setTimeout(() => {
        toast.style.animation = 'slideOut 0.3s ease-out';
        setTimeout(() => toast.remove(), 300);
    }, 3000);
}

// Add animations
const style = document.createElement('style');
style.textContent = `
    @keyframes slideIn {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
    @keyframes slideOut {
        from {
            transform: translateX(0);
            opacity: 1;
        }
        to {
            transform: translateX(100%);
            opacity: 0;
        }
    }
`;
document.head.appendChild(style);
</script>
@endsection