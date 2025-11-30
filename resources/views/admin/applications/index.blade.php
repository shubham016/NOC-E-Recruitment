@extends('layouts.dashboard')

@section('title', 'Applications Management')

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
    <a href="{{ route('admin.jobs.index') }}" class="sidebar-menu-item">
        <i class="bi bi-briefcase"></i>
        <span>Post Vacancy</span>
    </a>
    <a href="{{ route('admin.applications.index') }}" class="sidebar-menu-item active">
        <i class="bi bi-file-earmark-text"></i>
        <span>Applications</span>
    </a>
    <a href="#" class="sidebar-menu-item">
        <i class="bi bi-people"></i>
        <span>Candidates</span>
    </a>
    <a href="#" class="sidebar-menu-item">
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
    <div class="container-fluid py-4">
        <!-- Page Header -->
        <div class="row mb-4">
            <div class="col">
                <h2 class="fw-bold">
                    <i class="bi bi-file-earmark-text-fill me-2 text-danger"></i>
                    Applications Management
                </h2>
                <p class="text-muted">Manage all job applications</p>
            </div>
            <div class="col-auto">
                <a href="{{ route('admin.applications.export', request()->all()) }}" class="btn btn-outline-danger">
                    <i class="bi bi-download me-2"></i>Export CSV
                </a>
            </div>
        </div>

        <!-- Success/Error Messages -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Statistics Cards -->
        <div class="row g-4 mb-4">
            <div class="col-md-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="text-muted small mb-1">Total Applications</p>
                                <h3 class="fw-bold mb-0">{{ $stats['total'] ?? 0 }}</h3>
                            </div>
                            <div class="bg-primary bg-opacity-10 p-3 rounded">
                                <i class="bi bi-file-earmark-text-fill fs-4 text-primary"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="text-muted small mb-1">Pending Review</p>
                                <h3 class="fw-bold mb-0 text-warning">{{ $stats['pending'] ?? 0 }}</h3>
                            </div>
                            <div class="bg-warning bg-opacity-10 p-3 rounded">
                                <i class="bi bi-clock-fill fs-4 text-warning"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="text-muted small mb-1">Under Review</p>
                                <h3 class="fw-bold mb-0 text-info">{{ $stats['under_review'] ?? 0 }}</h3>
                            </div>
                            <div class="bg-info bg-opacity-10 p-3 rounded">
                                <i class="bi bi-eye-fill fs-4 text-info"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="text-muted small mb-1">Shortlisted</p>
                                <h3 class="fw-bold mb-0 text-success">{{ $stats['shortlisted'] ?? 0 }}</h3>
                            </div>
                            <div class="bg-success bg-opacity-10 p-3 rounded">
                                <i class="bi bi-check-circle-fill fs-4 text-success"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Applications Table -->
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                @if($applications->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Candidate</th>
                                    <th>Vacancy</th>
                                    <th>Applied Date</th>
                                    <th>Status</th>
                                    <th>Reviewer</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($applications as $application)
                                    <tr>
                                        <td class="text-muted">#{{ $application->id }}</td>
                                        <td>
                                            <div>
                                                <strong>{{ $application->candidate->user->name ?? 'N/A' }}</strong>
                                                <br>
                                                <small
                                                    class="text-muted">{{ $application->candidate->user->email ?? 'N/A' }}</small>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-danger">
                                                {{ $application->jobPosting->advertisement_no }}
                                            </span>
                                            <br>
                                            <small class="text-muted">
                                                {{ Str::limit($application->jobPosting->position_level, 30) }}
                                            </small>
                                        </td>
                                        <td>
                                            <div>{{ $application->created_at->format('M d, Y') }}</div>
                                            <small class="text-muted">{{ $application->created_at->diffForHumans() }}</small>
                                        </td>
                                        <td>
                                            <span class="badge {{ $application->getStatusBadgeClass() }}">
                                                {{ $application->getStatusLabel() }}
                                            </span>
                                        </td>
                                        <td>
                                            @if($application->reviewer)
                                                <small>
                                                    <i class="bi bi-person-fill text-danger me-1"></i>
                                                    {{ $application->reviewer->name }}
                                                </small>
                                            @else
                                                <small class="text-muted">Unassigned</small>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.applications.show', $application->id) }}"
                                                class="btn btn-sm btn-outline-danger">
                                                <i class="bi bi-eye"></i> View
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if($applications->hasPages())
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <div class="text-muted small">
                                Showing {{ $applications->firstItem() }} to {{ $applications->lastItem() }}
                                of {{ $applications->total() }} applications
                            </div>
                            <div>
                                {{ $applications->links() }}
                            </div>
                        </div>
                    @endif
                @else
                    <!-- Empty State -->
                    <div class="text-center py-5">
                        <i class="bi bi-inbox display-1 text-muted"></i>
                        <h5 class="mt-3">No Applications Found</h5>
                        <p class="text-muted">There are no applications yet.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection