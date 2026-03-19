@extends('layouts.app')

@section('title', 'Assigned Applications')

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
    <a href="{{ route('approver.assignedtome') }}" class="sidebar-menu-item active">
        <i class="bi bi-inbox"></i>
        <span>Assigned to Me</span>
    </a>
    <a href="{{ route('approver.assignedtome', ['status' => 'approved']) }}" class="sidebar-menu-item">
        <i class="bi bi-check-circle"></i>
        <span>Approved</span>
    </a>
    <a href="{{ route('approver.assignedtome', ['status' => 'rejected']) }}" class="sidebar-menu-item">
        <i class="bi bi-x-circle"></i>
        <span>Rejected</span>
    </a>
@endsection

@section('custom-styles')
<style>
    .page-header {
        background: linear-gradient(135deg, #c9a84c 0%, #a07828 100%);
        border-radius: 12px;
        padding: 1.5rem;
        color: white;
        margin-bottom: 1.5rem;
        box-shadow: 0 4px 12px rgba(201, 168, 76, 0.3);
    }

    .filter-card {
        background: white;
        border-radius: 10px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        border: 1px solid #e5e7eb;
    }

    .modern-table {
        width: 100%;
        border-collapse: collapse;
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
    }

    .modern-table tbody td {
        padding: 0.75rem;
        border: 1px solid #e5e7eb;
        vertical-align: middle;
        text-align: center;
    }

    .modern-table tbody tr {
        transition: all 0.2s;
    }

    .modern-table tbody tr:hover {
        background: #fef3c7;
    }

    .btn-gold {
        background: linear-gradient(135deg, #c9a84c 0%, #a07828 100%);
        color: white;
        border: none;
    }

    .btn-gold:hover {
        background: linear-gradient(135deg, #a07828 0%, #c9a84c 100%);
        color: white;
    }

    .form-check-input:checked {
        background-color: #c9a84c;
        border-color: #c9a84c;
    }

    #bulkActionsBar {
        background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
        border-left: 4px solid #c9a84c;
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
                    <i class="bi bi-inbox me-2"></i>Assigned Applications
                </h3>
                <p class="mb-0 opacity-90 small">Review and manage applications assigned to you</p>
            </div>
            <div>
                <span class="badge bg-light text-dark fs-6 px-3 py-2">
                    Total: {{ $applications->total() }}
                </span>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="filter-card">
        <form method="GET" action="{{ route('approver.assignedtome') }}" class="row g-3">
            <div class="col-md-4">
                <label class="form-label fw-semibold small">Vacancy</label>
                <select name="vacancy_id" class="form-select">
                    <option value="">All Vacancies</option>
                    @foreach($vacancies as $vacancy)
                        <option value="{{ $vacancy->id }}" {{ request('vacancy_id') == $vacancy->id ? 'selected' : '' }}>
                            {{ $vacancy->title }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-3">
                <label class="form-label fw-semibold small">Status</label>
                <select name="status" class="form-select">
                    <option value="">All Status</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                </select>
            </div>

            <div class="col-md-3">
                <label class="form-label fw-semibold small">Search</label>
                <input type="text" name="search" class="form-control" placeholder="Candidate name or email" value="{{ request('search') }}">
            </div>

            <div class="col-md-2 d-flex align-items-end">
                <button type="submit" class="btn btn-gold w-100">
                    <i class="bi bi-funnel me-2"></i>Filter
                </button>
            </div>
        </form>
    </div>

    <!-- Bulk Actions Bar (Hidden by default) -->
    <div id="bulkActionsBar" class="card mb-3" style="display: none;">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <span class="fw-bold">
                        <span id="selectedCount">0</span> application(s) selected
                    </span>
                </div>
                <div class="btn-group">
                    <button type="button" class="btn btn-sm btn-gold" onclick="bulkExport('csv')">
                        <i class="bi bi-file-earmark-spreadsheet me-1"></i>Export CSV
                    </button>
                    <button type="button" class="btn btn-sm btn-gold" onclick="bulkExport('pdf')">
                        <i class="bi bi-file-earmark-pdf me-1"></i>Export PDF
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-secondary" onclick="clearSelection()">
                        <i class="bi bi-x-circle me-1"></i>Clear
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Applications Table -->
    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="modern-table">
                    <thead>
                        <tr>
                            <th style="width: 50px;">
                                <input type="checkbox" class="form-check-input" id="selectAll">
                            </th>
                            <th style="width: 60px;">S.N</th>
                            <th>Candidate Name</th>
                            <th>Email</th>
                            <th>Vacancy Title</th>
                            <th>Applied Date</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($applications as $application)
                            <tr>
                                <td>
                                    <input type="checkbox" class="form-check-input application-checkbox" value="{{ $application->id }}">
                                </td>
                                <td class="fw-semibold">{{ ($applications->currentPage() - 1) * $applications->perPage() + $loop->iteration }}</td>
                                <td>{{ $application->candidate->name ?? 'N/A' }}</td>
                                <td>{{ $application->candidate->email ?? 'N/A' }}</td>
                                <td>{{ $application->vacancy->title ?? 'N/A' }}</td>
                                <td>
                                    {{ $application->created_at->format('M d, Y') }}
                                    <small class="text-muted d-block">{{ adToBS($application->created_at) }} (BS)</small>
                                </td>
                                <td>
                                    @if($application->status === 'approved')
                                        <span class="badge bg-success">Approved</span>
                                    @elseif($application->status === 'rejected')
                                        <span class="badge bg-danger">Rejected</span>
                                    @else
                                        <span class="badge bg-warning text-dark">Pending</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('approver.show', $application->id) }}" class="btn btn-sm btn-gold">
                                        <i class="bi bi-eye"></i> View
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-4 text-muted">
                                    <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                    @if(!Auth::guard('approver')->user()->vacancy_id)
                                        <strong class="d-block mb-2">No Vacancy Assigned</strong>
                                        <p class="small">You have not been assigned to any vacancy yet. Please contact the administrator.</p>
                                    @else
                                        No applications found for your assigned vacancy
                                    @endif
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Pagination -->
    <div class="d-flex justify-content-center mt-4">
        {{ $applications->links() }}
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Select All Functionality
    document.getElementById('selectAll').addEventListener('change', function() {
        const checkboxes = document.querySelectorAll('.application-checkbox');
        checkboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
        updateBulkActionsBar();
    });

    // Individual checkbox change
    document.querySelectorAll('.application-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', updateBulkActionsBar);
    });

    function updateBulkActionsBar() {
        const selected = document.querySelectorAll('.application-checkbox:checked');
        const bulkBar = document.getElementById('bulkActionsBar');
        const countSpan = document.getElementById('selectedCount');

        if (selected.length > 0) {
            bulkBar.style.display = 'block';
            countSpan.textContent = selected.length;
        } else {
            bulkBar.style.display = 'none';
        }
    }

    function clearSelection() {
        document.querySelectorAll('.application-checkbox').forEach(checkbox => {
            checkbox.checked = false;
        });
        document.getElementById('selectAll').checked = false;
        updateBulkActionsBar();
    }

    function bulkExport(format) {
        const selected = Array.from(document.querySelectorAll('.application-checkbox:checked'))
            .map(cb => cb.value);

        if (selected.length === 0) {
            alert('Please select at least one application');
            return;
        }

        const url = format === 'csv'
            ? '{{ route("approver.applications.exportCsv") }}'
            : '{{ route("approver.applications.exportPdf") }}';

        const form = document.createElement('form');
        form.method = 'GET';
        form.action = url;

        selected.forEach(id => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'ids[]';
            input.value = id;
            form.appendChild(input);
        });

        document.body.appendChild(form);
        form.submit();
        document.body.removeChild(form);
    }
</script>
@endsection
