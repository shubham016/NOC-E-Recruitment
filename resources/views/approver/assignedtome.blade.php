@extends('layouts.approver')
@section('title', 'Approver Dashboard')
@section('portal-name', 'Approver Portal')
@section('brand-icon', 'bi bi-person-check')
@section('dashboard-route', route('approver.dashboard'))
@section('user-name', Auth::guard('approver')->user()->name)
@section('user-role', 'Application Approver')
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
    <a href="{{ route('approver.myprofile') }}" class="sidebar-menu-item">
        <i class="bi bi-person"></i>
        <span>My Profile</span>
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
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="stat-card">
                <div  style="background: rgba(234, 179, 8, 0.1); color: #eab308;">
                    
                </div>
                <h3 class="fw-bold mb-0">{{ $stats['pending_applications'] }}</h3>
                <p class="text-muted mb-0 small">Pending Applications</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div  style="background: rgba(34, 197, 94, 0.1); color: #22c55e;">
                    
                </div>
                <h3 class="fw-bold mb-0">{{ $stats['approved_applications'] }}</h3>
                <p class="text-muted mb-0 small">Approved</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div  style="background: rgba(239, 68, 68, 0.1); color: #ef4444;">
                    
                </div>
                <h3 class="fw-bold mb-0">{{ $stats['rejected_applications'] }}</h3>
                <p class="text-muted mb-0 small">Rejected</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div  style="background: rgba(99, 102, 241, 0.1); color: #6366f1;">
                    
                </div>
                <h3 class="fw-bold mb-0">{{ $stats['total_applications'] }}</h3>
                <p class="text-muted mb-0 small">Total Applications</p>
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
                        <button type="submit" class="btn btn-success w-100">
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
                <span >{{ $applications->total() }} Total</span>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive" style="overflow-x: auto;">
                <table class="modern-table table table-hover mb-0" style="min-width: 1200px;">
                    <thead>
                        <tr>
                            <th style="width: 40px;">
                                <input type="checkbox" id="selectAll" class="form-check-input">
                            </th>
                            <th>Advertisement No</th>
                            <th>App ID</th>
                            <th>Photo</th>
                            <th>Full Name</th>
                            <th>Vacancy Type</th>
                            <th>Payment</th>
                            <th>Applied Date</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($applications as $index => $application)
                            @php
                                $daysRemaining = $application->jobPosting ? (int) now()->diffInDays($application->jobPosting->deadline, false) : 0;

                               

                                $statusColors = [
                                    'pending' => 'bg-warning text-dark',
                                    'assigned' => 'bg-info',
                                    'reviewed' => 'bg-success',
                                    'approved' => 'bg-success',
                                    'rejected' => 'bg-danger',
                                ];
                                $statusColor = $statusColors[$application->status] ?? 'bg-secondary';
                            @endphp
                            <tr class="application-card ">
                                <td class="text-center">
                                    <input type="checkbox" class="form-check-input application-checkbox"
                                           value="{{ $application->id }}">
                                </td>
                                <td class="text-col">{{ $application->jobPosting->advertisement_no ?? 'N/A' }}</td>
                                <td class="text-col">{{ $application->id ?? 'N/A' }}</td>
                                <td class="text-col">
                                    @if($application->passport_size_photo)
                                        <img 
                                            src="{{ asset('storage/passport-photos/' . $application->passport_size_photo) }}" 
                                            alt="Passport Photo" 
                                            style="width: 60px; height: 60px; object-fit: cover; border-radius: 4px;"
                                            onerror="this.onerror=null;this.src='{{ asset('images/default-user.png') }}';"
                                        >
                                    @else
                                        N/A
                                    @endif
                                </td>
                                <td class="text-col">
                                    <span class="d-block">{{ $application->name_english ?? 'N/A' }}</span>
                                    <small class="text-muted">{{ $application->name_nepali ?? 'N/A' }}</small>
                                </td>
                                <td class="text-col">{{ $application->jobPosting->category ?? 'N/A' }}</td>
                                <td class="text-col">
                                    <span class="d-block">{{ $application->payment->status ?? 'N/A' }}</span>
                                    <small class="text-muted">Rs. {{ $application->payment->amount ?? 'N/A' }}</small>
                                </td>
                                <td class="nowrap">
                                    <span class="d-block">{{ ($application->submitted_at ?? $application->created_at)->format('M d, Y') }}</span>
                                    <small class="text-muted">{{ ($application->submitted_at ?? $application->created_at)->format('h:i A') }}</small>
                                </td>
                                <td class="nowrap">
                                    <span >{{ ucfirst($application->status) }}</span>
                                </td>
                                <td class="nowrap">
                                    <a href="{{ route('approver.applications.show', $application->id) }}" 
                                    class="btn btn-sm 
                                    {{ in_array($application->status, ['approved', 'rejected']) ? 'btn-success' : 'btn-danger' }}">
                                        
                                        <i class="bi {{ in_array($application->status, ['approved', 'rejected']) ? 'bi-check-circle' : 'bi-eye' }} me-1"></i>
                                        
                                        {{ in_array($application->status, ['approved', 'rejected']) ? 'Checked' : 'Check' }}
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
document.addEventListener('DOMContentLoaded', function () {

    const selectAll = document.getElementById('selectAll');
    const checkboxes = document.querySelectorAll('.application-checkbox');

    // Select all
    selectAll.addEventListener('click', function () {
        checkboxes.forEach(cb => {
            cb.checked = selectAll.checked;

            const row = cb.closest('tr');
            cb.checked
                ? row.classList.add('selected')
                : row.classList.remove('selected');
        });

        updateBulkActions();
    });

    // Individual checkbox
    checkboxes.forEach(cb => {
        cb.addEventListener('click', function () {
            const row = cb.closest('tr');
            cb.checked
                ? row.classList.add('selected')
                : row.classList.remove('selected');

            updateBulkActions();
        });
    });

    function updateBulkActions() {
        const checkedBoxes = document.querySelectorAll('.application-checkbox:checked');
        const count = checkedBoxes.length;

        const bulkActionsBar = document.getElementById('bulkActionsBar');
        const selectedCount = document.getElementById('selectedCount');

        if (count > 0) {
            bulkActionsBar.style.display = 'block';
            selectedCount.textContent = count;
        } else {
            bulkActionsBar.style.display = 'none';
        }

        selectAll.checked = checkboxes.length === count;
    }

    window.clearSelection = function () {
        checkboxes.forEach(cb => {
            cb.checked = false;
            cb.closest('tr').classList.remove('selected');
        });
        selectAll.checked = false;
        updateBulkActions();
    };

    function exportSelected(type) {
    const selected = [];

    document.querySelectorAll('.application-checkbox:checked').forEach(cb => {
        selected.push(cb.value);
    });

    if (selected.length === 0) {
        alert('Please select at least one application');
        return;
    }

    let url = '';

    if (type === 'csv') {
        url = "{{ route('approver.applications.exportCsv') }}";
    } else if (type === 'pdf') {
        url = "{{ route('approver.applications.exportPdf') }}";
    }

    // Attach selected IDs as query params
    url += '?ids=' + selected.join(',');

    window.location.href = url;
}
window.exportSelected = exportSelected;

});
</script>
@endsection