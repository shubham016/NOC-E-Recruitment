@extends('layouts.dashboard')

@section('title', 'Audit Logs')

@section('portal-name', __('admin.portal_name'))
@section('brand-icon', 'bi bi-shield-check')
@section('dashboard-route', route('admin.dashboard'))
@section('user-name', Auth::guard('admin')->user()->name)
@section('user-role', __('admin.system_administrator'))
@section('user-initial', strtoupper(substr(Auth::guard('admin')->user()->name, 0, 1)))
@section('logout-route', route('admin.logout'))

@section('sidebar-menu')
    @include('admin.partials.sidebar')
@endsection

@push('styles')
<style>
    .audit-header {
        background: linear-gradient(135deg, #1a3a6b 0%, #122a52 100%);
        color: #fff;
        border-radius: 10px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
    }

    .audit-stat {
        border: 1px solid #d0daea;
        border-radius: 8px;
        background: #fff;
        padding: 1rem;
        height: 100%;
    }

    .audit-stat-label {
        color: #64748b;
        font-size: 0.85rem;
        margin-bottom: 0.25rem;
    }

    .audit-stat-value {
        color: #122a52;
        font-size: 1.65rem;
        font-weight: 700;
        line-height: 1;
    }

    .audit-filter-card,
    .audit-table-card {
        border: 0;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(15, 23, 42, 0.08);
    }

    .audit-status {
        border-radius: 999px;
        padding: 0.25rem 0.65rem;
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
    }

    .audit-status-success {
        background: #d1fae5;
        color: #065f46;
    }

    .audit-status-failed {
        background: #fee2e2;
        color: #991b1b;
    }

    .audit-action {
        color: #122a52;
        font-weight: 700;
        text-transform: capitalize;
    }

    .audit-user-type {
        background: #e8eef6;
        color: #122a52;
        border: 1px solid #c8d4e8;
        border-radius: 999px;
        padding: 0.25rem 0.6rem;
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: capitalize;
    }

    .audit-search-btn {
        background: linear-gradient(135deg, #1a3a6b 0%, #122a52 100%);
        border-color: #122a52;
        color: #fff;
    }

    .audit-search-btn:hover,
    .audit-search-btn:focus {
        background: linear-gradient(135deg, #122a52 0%, #0f2141 100%);
        border-color: #0f2141;
        color: #fff;
    }

    .audit-table {
        border: 1px solid #d0daea;
    }

    .audit-table th,
    .audit-table td {
        border-right: 1px solid #d0daea;
        text-align: center;
        vertical-align: middle;
    }

    .audit-table th:last-child,
    .audit-table td:last-child {
        border-right: 0;
    }

    .audit-table thead th {
        color: #122a52;
        font-weight: 700;
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-4 py-4">
    <div class="audit-header">
        <div class="d-flex justify-content-between align-items-center gap-3 flex-wrap">
            <div>
                <h3 class="fw-bold mb-1"><i class="bi bi-shield-lock me-2"></i>Audit Logs</h3>
                <p class="mb-0 opacity-90">Track admin, reviewer, and approver login activity.</p>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-3 col-6">
            <div class="audit-stat">
                <div class="audit-stat-label">Total Records</div>
                <div class="audit-stat-value">{{ number_format($stats['total']) }}</div>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="audit-stat">
                <div class="audit-stat-label">Successful Logins</div>
                <div class="audit-stat-value">{{ number_format($stats['successful_logins']) }}</div>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="audit-stat">
                <div class="audit-stat-label">Failed Logins</div>
                <div class="audit-stat-value">{{ number_format($stats['failed_logins']) }}</div>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="audit-stat">
                <div class="audit-stat-label">Logouts</div>
                <div class="audit-stat-value">{{ number_format($stats['logouts']) }}</div>
            </div>
        </div>
    </div>

    <div class="card audit-filter-card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.audit.index') }}">
                <div class="row g-3 align-items-end">
                    <div class="col-lg-3">
                        <label class="form-label fw-semibold">Search</label>
                        <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Name, email/ID, IP">
                    </div>
                    <div class="col-lg-2 col-md-4">
                        <label class="form-label fw-semibold">Portal</label>
                        <select name="user_type" class="form-select">
                            <option value="">All</option>
                            @foreach(['admin' => 'Admin', 'reviewer' => 'Reviewer', 'approver' => 'Approver'] as $value => $label)
                                <option value="{{ $value }}" {{ request('user_type') === $value ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-2 col-md-4">
                        <label class="form-label fw-semibold">Action</label>
                        <select name="action" class="form-select">
                            <option value="">All</option>
                            <option value="login" {{ request('action') === 'login' ? 'selected' : '' }}>Login</option>
                            <option value="logout" {{ request('action') === 'logout' ? 'selected' : '' }}>Logout</option>
                        </select>
                    </div>
                    <div class="col-lg-2 col-md-4">
                        <label class="form-label fw-semibold">Status</label>
                        <select name="status" class="form-select">
                            <option value="">All</option>
                            <option value="success" {{ request('status') === 'success' ? 'selected' : '' }}>Success</option>
                            <option value="failed" {{ request('status') === 'failed' ? 'selected' : '' }}>Failed</option>
                        </select>
                    </div>
                    <div class="col-lg-1 col-md-6">
                        <label class="form-label fw-semibold">From</label>
                        <input type="date" name="date_from" value="{{ request('date_from') }}" class="form-control">
                    </div>
                    <div class="col-lg-1 col-md-6">
                        <label class="form-label fw-semibold">To</label>
                        <input type="date" name="date_to" value="{{ request('date_to') }}" class="form-control">
                    </div>
                    <div class="col-lg-1">
                        <button type="submit" class="btn audit-search-btn w-100">
                            <i class="bi bi-search"></i>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card audit-table-card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 audit-table">
                    <thead class="table-light">
                        <tr>
                            <th>Date/Time</th>
                            <th>Portal</th>
                            <th>User</th>
                            <th>Identifier</th>
                            <th>Action</th>
                            <th>Status</th>
                            <th>IP Address</th>
                            <th>Reason</th>
                            <th>User Agent</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($logs as $log)
                            <tr>
                                <td class="text-nowrap">{{ $log->attempted_at?->format('Y-m-d h:i A') }}</td>
                                <td><span class="audit-user-type">{{ $log->user_type }}</span></td>
                                <td>{{ $log->user_name ?: '-' }}</td>
                                <td>{{ $log->user_identifier ?: '-' }}</td>
                                <td><span class="audit-action">{{ $log->action }}</span></td>
                                <td>
                                    <span class="audit-status audit-status-{{ $log->status === 'success' ? 'success' : 'failed' }}">
                                        {{ $log->status }}
                                    </span>
                                </td>
                                <td>{{ $log->ip_address ?: '-' }}</td>
                                <td>{{ $log->failure_reason ?: '-' }}</td>
                                <td class="small text-muted" style="max-width: 280px;">{{ \Illuminate\Support\Str::limit($log->user_agent, 90) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center py-5 text-muted">No audit records found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($logs->hasPages())
            <div class="card-footer bg-white">
                {{ $logs->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>
</div>
@endsection
