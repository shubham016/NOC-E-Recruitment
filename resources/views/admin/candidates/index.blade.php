@extends('layouts.dashboard')

@section('title', 'Manage Candidates')

@section('portal-name', 'Admin Portal')
@section('brand-icon', 'bi bi-shield-lock')
@section('dashboard-route', route('admin.dashboard'))
@section('user-name', Auth::guard('admin')->user()->name)
@section('user-role', 'Administrator')
@section('user-initial', strtoupper(substr(Auth::guard('admin')->user()->name, 0, 1)))
@section('logout-route', route('admin.logout'))

@section('sidebar-menu')
    <a href="{{ route('admin.dashboard') }}" class="sidebar-menu-item">
        <i class="bi bi-speedometer2"></i>
        <span>Dashboard</span>
    </a>
    <a href="{{ route('admin.jobs.index') }}" class="sidebar-menu-item">
        <i class="bi bi-briefcase"></i>
        <span>Job Postings</span>
    </a>
    <a href="{{ route('admin.applications.index') }}" class="sidebar-menu-item">
        <i class="bi bi-file-earmark-text"></i>
        <span>Applications</span>
    </a>
    <a href="{{ route('admin.candidates.index') }}" class="sidebar-menu-item active">
        <i class="bi bi-people"></i>
        <span>Candidates</span>
    </a>
    <a href="{{ route('admin.reviewers.index') }}" class="sidebar-menu-item">
        <i class="bi bi-person-badge"></i>
        <span>Reviewers</span>
    </a>
@endsection

@section('custom-styles')
    <style>
        .stat-card {
            border-left: 4px solid #6366f1;
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
        }

        .stat-card h2 {
            font-size: 2rem;
            font-weight: 700;
            margin: 0;
        }

        .stat-card h6 {
            color: #6b7280;
            font-size: 0.875rem;
            margin-bottom: 0.5rem;
        }

        .badge-status {
            padding: 0.375rem 0.75rem;
            font-size: 0.75rem;
            font-weight: 600;
            border-radius: 0.375rem;
        }

        .table-hover tbody tr:hover {
            background-color: #f9fafb;
            cursor: pointer;
        }

        .search-section {
            background: white;
            border-radius: 0.5rem;
            padding: 1.5rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .btn-action {
            padding: 0.375rem 0.75rem;
            font-size: 0.875rem;
        }

        .empty-state {
            padding: 3rem 1rem;
            text-align: center;
        }

        .empty-state i {
            font-size: 4rem;
            color: #d1d5db;
            margin-bottom: 1rem;
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-1">Manage Candidates</h1>
                <p class="text-muted mb-0">View and manage all registered candidates</p>
            </div>
        </div>

        <!-- Success/Error Messages -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Stats Cards -->
        <div class="row g-4 mb-4">
            <div class="col-md-3">
                <div class="card stat-card h-100">
                    <div class="card-body">
                        <h6 class="text-muted">Total Candidates</h6>
                        <h2 class="text-primary">{{ $stats['total'] }}</h2>
                        <small class="text-muted">
                            <i class="bi bi-people me-1"></i>All registered
                        </small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card stat-card h-100" style="border-left-color: #10b981;">
                    <div class="card-body">
                        <h6 class="text-muted">Active</h6>
                        <h2 class="text-success">{{ $stats['active'] }}</h2>
                        <small class="text-muted">
                            <i class="bi bi-check-circle me-1"></i>Active accounts
                        </small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card stat-card h-100" style="border-left-color: #ef4444;">
                    <div class="card-body">
                        <h6 class="text-muted">Inactive</h6>
                        <h2 class="text-danger">{{ $stats['inactive'] }}</h2>
                        <small class="text-muted">
                            <i class="bi bi-x-circle me-1"></i>Inactive accounts
                        </small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card stat-card h-100" style="border-left-color: #8b5cf6;">
                    <div class="card-body">
                        <h6 class="text-muted">This Month</h6>
                        <h2 class="text-purple">{{ $stats['this_month'] }}</h2>
                        <small class="text-muted">
                            <i class="bi bi-calendar me-1"></i>New registrations
                        </small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Search & Filter Section -->
        <div class="search-section mb-4">
            <form method="GET" class="row g-3">
                <div class="col-md-5">
                    <label class="form-label fw-semibold">Search</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="bi bi-search"></i>
                        </span>
                        <input type="text" name="search" class="form-control"
                            placeholder="Search by name, email, username, mobile..." value="{{ $search }}">
                    </div>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Status</label>
                    <select name="status" class="form-select">
                        <option value="">All Status</option>
                        <option value="active" {{ $status === 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ $status === 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">&nbsp;</label>
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-search me-1"></i> Search
                    </button>
                </div>
                <div class="col-md-2">
                    <label class="form-label">&nbsp;</label>
                    <a href="{{ route('admin.candidates.index') }}" class="btn btn-secondary w-100">
                        <i class="bi bi-x-circle me-1"></i> Clear
                    </a>
                </div>
            </form>
        </div>

        <!-- Candidates Table -->
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0 py-3">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold">
                        <i class="bi bi-people me-2"></i>Candidates List
                    </h5>
                    <div class="text-muted">
                        <small>Showing {{ $candidates->firstItem() ?? 0 }} to {{ $candidates->lastItem() ?? 0 }} of
                            {{ $candidates->total() }} entries</small>
                    </div>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="border-0 ps-4">ID</th>
                                <th class="border-0">Name</th>
                                <th class="border-0">Username</th>
                                <th class="border-0">Email</th>
                                <th class="border-0">Mobile</th>
                                <th class="border-0">Applications</th>
                                <th class="border-0">Status</th>
                                <th class="border-0">Registered</th>
                                <th class="border-0 pe-4">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($candidates as $candidate)
                                <tr>
                                    <td>{{ $candidate->id }}</td>
                                    <td>
                                        <strong>{{ $candidate->name }}</strong>
                                    </td>
                                    <td>{{ $candidate->username }}</td>
                                    <td>{{ $candidate->email }}</td>
                                    <td>{{ $candidate->mobile_number }}</td>
                                    <td>
                                        <span class="badge bg-info">
                                            {{ $candidate->applications_count }} Applications
                                        </span>
                                    </td>
                                    <td>
                                        @if($candidate->status === 'active')
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-danger">Inactive</span>
                                        @endif
                                    </td>
                                    <td>{{ $candidate->created_at->format('M d, Y') }}</td>
                                    <td>
                                        <a href="{{ route('admin.candidates.show', $candidate->id) }}"
                                            class="btn btn-sm btn-primary">
                                            <i class="bi bi-eye"></i> View
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center py-4">
                                        <i class="bi bi-inbox" style="font-size: 48px; color: #ccc;"></i>
                                        <p class="text-muted mt-2">No candidates found</p>
                                    </td>
                                </tr>
                            @endforelse

                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($candidates->hasPages())
                    <div class="card-footer bg-white border-0">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="text-muted">
                                Showing {{ $candidates->firstItem() }} to {{ $candidates->lastItem() }} of
                                {{ $candidates->total() }} results
                            </div>
                            <div>
                                {{ $candidates->links() }}
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection