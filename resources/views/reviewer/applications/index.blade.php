@extends('layouts.dashboard')

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
    <a href="{{ route('reviewer.applications.index') }}" class="sidebar-menu-item active">
        <i class="bi bi-hourglass-split"></i>
        <span>Pending Reviews</span>
        <span class="badge bg-warning text-dark ms-auto">{{ $stats['pending'] }}</span>
    </a>
    <a href="{{ route('reviewer.applications.index', ['status' => 'approved']) }}" class="sidebar-menu-item">
        <i class="bi bi-check-circle"></i>
        <span>Approved</span>
        <span class="badge bg-success ms-auto">{{ $stats['approved'] }}</span>
    </a>
    <a href="{{ route('reviewer.applications.index', ['status' => 'rejected']) }}" class="sidebar-menu-item">
        <i class="bi bi-x-circle"></i>
        <span>Rejected</span>
        <span class="badge bg-danger ms-auto">{{ $stats['rejected'] }}</span>
    </a>
@endsection

@section('custom-styles')
<style>
    .page-header {
        background: linear-gradient(135deg, #64748b 0%, #475569 100%);
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
    }

    .modern-table thead th {
        background: #f9fafb;
        padding: 1rem;
        font-weight: 700;
        color: #374151;
        font-size: 0.875rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border: 1px solid #e5e7eb;
        text-align: center;
    }

    .modern-table tbody td {
        padding: 1rem;
        border: 1px solid #e5e7eb;
        vertical-align: middle;
        text-align: center;
    }

    .modern-table tbody tr {
        transition: all 0.2s;
    }

    .modern-table tbody tr:hover {
        background: #f8fafc;
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
                    <div class="bg-primary bg-opacity-10 rounded-3 p-3">
                        <i class="bi bi-file-earmark-text text-primary fs-4"></i>
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
                    <div class="bg-warning bg-opacity-10 rounded-3 p-3">
                        <i class="bi bi-hourglass-split text-warning fs-4"></i>
                    </div>
                    <div>
                        <h3 class="fw-bold mb-0">{{ $stats['pending'] }}</h3>
                        <small class="text-muted">Pending Reviews</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="d-flex align-items-center gap-3">
                    <div class="bg-success bg-opacity-10 rounded-3 p-3">
                        <i class="bi bi-check-circle-fill text-success fs-4"></i>
                    </div>
                    <div>
                        <h3 class="fw-bold mb-0">{{ $stats['approved'] }}</h3>
                        <small class="text-muted">Approved</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="d-flex align-items-center gap-3">
                    <div class="bg-danger bg-opacity-10 rounded-3 p-3">
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

    <!-- Search and Filter -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('reviewer.applications.index') }}">
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
                            <i class="bi bi-search me-1"></i> Filter
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Applications Table -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3">
            <div class="d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-bold">
                    <i class="bi bi-list-ul text-primary me-2"></i>Applications List
                </h6>
                <span class="badge bg-primary">{{ $applications->total() }} Total</span>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="modern-table table table-hover mb-0">
                    <thead>
                        <tr>
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

                                $statusColors = [
                                    'pending' => 'bg-warning text-dark',
                                    'approved' => 'bg-success',
                                    'rejected' => 'bg-danger',
                                ];
                                $statusColor = $statusColors[$application->status] ?? 'bg-secondary';
                            @endphp
                            <tr class="application-card {{ $priorityClass }}">
                                <td>{{ $applications->firstItem() + $index }}</td>
                                <td>
                                    <div class="text-start">
                                        <strong>{{ $application->name_english ?? 'N/A' }}</strong>
                                        <br>
                                        <small class="text-muted">{{ $application->email ?? 'N/A' }}</small>
                                    </div>
                                </td>
                                <td>{{ $application->jobPosting->title ?? 'N/A' }}</td>
                                <td>{{ $application->jobPosting->department ?? 'N/A' }}</td>
                                <td>{{ $application->created_at->format('M d, Y') }}</td>
                                <td>
                                    @if($application->jobPosting)
                                        <span class="text-danger fw-bold">{{ $application->jobPosting->deadline->format('M d, Y') }}</span>
                                        <br>
                                        <small class="text-muted">{{ $daysRemaining }} days left</small>
                                    @else
                                        N/A
                                    @endif
                                </td>
                                <td>
                                    <span class="badge {{ $priorityBadge }}">{{ $priorityText }}</span>
                                </td>
                                <td>
                                    <span class="badge {{ $statusColor }}">{{ ucfirst($application->status) }}</span>
                                </td>
                                <td>
                                    <a href="{{ route('reviewer.applications.show', $application->id) }}" class="btn btn-sm btn-primary">
                                        <i class="bi bi-eye"></i> Review
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center py-5">
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
