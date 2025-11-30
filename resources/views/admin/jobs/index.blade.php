@extends('layouts.dashboard')

@section('title', 'Job Management')

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
    <a href="{{ route('admin.jobs.create') }}" class="sidebar-menu-item active">
        <i class="bi bi-briefcase"></i>
        <span>Post Vacancy</span>
        <span class="badge bg-primary ms-auto">{{ $stats['total'] }}</span>
    </a>
    <a href="{{ route('admin.applications.index') }}" class="sidebar-menu-item">
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

@section('custom-styles')
<style>
    .page-header {
        background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
        border-radius: 12px;
        padding: 1.5rem;
        color: white;
        margin-bottom: 1.5rem;
        box-shadow: 0 4px 12px rgba(99, 102, 241, 0.2);
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

    .job-row {
        transition: all 0.3s ease;
        border-left: 3px solid transparent;
    }

    .job-row:hover {
        background-color: #f8fafc;
        border-left-color: #6366f1;
        transform: translateX(2px);
    }

    .job-row.draft {
        border-left-color: #9ca3af;
        background: linear-gradient(to right, rgba(156, 163, 175, 0.02) 0%, white 100%);
    }

    .job-row.active {
        border-left-color: #10b981;
        background: linear-gradient(to right, rgba(16, 185, 129, 0.02) 0%, white 100%);
    }

    .job-row.closed {
        border-left-color: #ef4444;
        background: linear-gradient(to right, rgba(239, 68, 68, 0.02) 0%, white 100%);
    }
</style>
@endsection

@section('content')
<!-- Page Header -->
<div class="page-header">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h4 class="fw-bold mb-1">
                <i class="bi bi-briefcase me-2"></i>Job Management
            </h4>
            <p class="mb-0 opacity-90">Create and manage job postings</p>
        </div>
        <a href="{{ route('admin.jobs.create') }}" class="btn btn-light">
            <i class="bi bi-plus-circle me-2"></i>Post New Job
        </a>
    </div>
</div>

<!-- Success/Error Messages -->
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<!-- Statistics Cards -->
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="stat-card">
            <div class="d-flex align-items-center gap-3">
                <div class="bg-primary bg-opacity-10 rounded-3 p-3">
                    <i class="bi bi-briefcase-fill text-primary fs-4"></i>
                </div>
                <div>
                    <h3 class="fw-bold mb-0">{{ $stats['total'] }}</h3>
                    <small class="text-muted">Total Jobs</small>
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
                    <h3 class="fw-bold mb-0">{{ $stats['active'] }}</h3>
                    <small class="text-muted">Active Jobs</small>
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
                    <h3 class="fw-bold mb-0">{{ $stats['closed'] }}</h3>
                    <small class="text-muted">Closed Jobs</small>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="d-flex align-items-center gap-3">
                <div class="bg-warning bg-opacity-10 rounded-3 p-3">
                    <i class="bi bi-file-earmark-text text-warning fs-4"></i>
                </div>
                <div>
                    <h3 class="fw-bold mb-0">{{ $stats['draft'] }}</h3>
                    <small class="text-muted">Draft Jobs</small>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Search and Filters -->
<div class="card border-0 shadow-sm mb-3">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.jobs.index') }}">
            <div class="row g-3">
                <div class="col-md-4">
                    <input type="text" class="form-control" name="search" 
                           placeholder="Search jobs..." 
                           value="{{ request('search') }}">
                </div>
                <div class="col-md-2">
                    <select class="form-select" name="status">
                        <option value="">All Status</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="closed" {{ request('status') == 'closed' ? 'selected' : '' }}>Closed</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select class="form-select" name="job_type">
                        <option value="">All Types</option>
                        <option value="full-time" {{ request('job_type') == 'full-time' ? 'selected' : '' }}>Full-time</option>
                        <option value="part-time" {{ request('job_type') == 'part-time' ? 'selected' : '' }}>Part-time</option>
                        <option value="contract" {{ request('job_type') == 'contract' ? 'selected' : '' }}>Contract</option>
                        <option value="internship" {{ request('job_type') == 'internship' ? 'selected' : '' }}>Internship</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary flex-grow-1">
                            <i class="bi bi-search me-2"></i>Search
                        </button>
                        @if(request()->hasAny(['search', 'status', 'job_type']))
                            <a href="{{ route('admin.jobs.index') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-x-lg"></i>
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Jobs Table -->
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white py-3">
        <div class="d-flex justify-content-between align-items-center">
            <h6 class="mb-0 fw-bold">
                <i class="bi bi-list-ul text-primary me-2"></i>Job Listings
                <span class="badge bg-primary ms-2">{{ $jobs->total() }}</span>
            </h6>
            <select class="form-select form-select-sm" style="width: auto;" onchange="changeSorting(this.value)">
                <option value="created_at-desc" {{ request('sort_by') == 'created_at' && request('sort_order') == 'desc' ? 'selected' : '' }}>
                    Newest First
                </option>
                <option value="created_at-asc" {{ request('sort_by') == 'created_at' && request('sort_order') == 'asc' ? 'selected' : '' }}>
                    Oldest First
                </option>
                <option value="deadline-asc" {{ request('sort_by') == 'deadline' && request('sort_order') == 'asc' ? 'selected' : '' }}>
                    Deadline (Soon)
                </option>
            </select>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">Vacancy Title</th>
                        <th>Position</th>
                        <th>Service</th>
                        <th>Type</th>
                        <th>Deadline</th>
                        <th>Applications</th>
                        <th>Status</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($jobs as $job)
                        @php
                            $statusBadge = match($job->status) {
                                'active' => 'bg-success',
                                'closed' => 'bg-danger',
                                'draft' => 'bg-secondary',
                                default => 'bg-secondary'
                            };
                            
                            $daysRemaining = now()->diffInDays($job->deadline, false);
                            $deadlineColor = $daysRemaining <= 7 ? 'text-danger' : ($daysRemaining <= 14 ? 'text-warning' : 'text-success');
                        @endphp
                        <tr class="job-row {{ $job->status }}">
                            <td class="ps-4">
                                <div>
                                    <h6 class="mb-1 fw-semibold">{{ $job->title }}</h6>
                                    <small class="text-muted">Posted {{ $job->created_at->diffForHumans() }}</small>
                                </div>
                            </td>
                            <td>{{ $job->position_level }}</td>
                            <td>
                                {{-- <i class="bi bi-geo-alt me-1"></i> --}}
                                {{ $job->service_group }}
                            </td>
                            <td>
                                <span class="badge bg-light text-dark">{{ ucfirst($job->category) }}</span>
                            </td>
                            <td>
                                <div>
                                    <div class="small">{{ $job->deadline->format('M d, Y') }}</div>
                                    <small class="{{ $deadlineColor }}">
                                        {{-- <i class="bi bi-clock me-1"></i>{{ abs($daysRemaining) }} days --}}
                                    </small>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-primary">{{ $job->applications_count ?? 0 }}</span>
                            </td>
                            <td>
                                <span class="badge {{ $statusBadge }}">
                                    {{ ucfirst($job->status) }}
                                </span>
                            </td>
                            <td class="text-center">
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('admin.jobs.show', $job->id) }}" 
                                       class="btn btn-outline-primary" 
                                       title="View">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.jobs.edit', $job->id) }}" 
                                       class="btn btn-outline-secondary" 
                                       title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <button type="button" 
                                            class="btn btn-outline-danger" 
                                            onclick="confirmDelete({{ $job->id }})"
                                            title="Delete">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-5">
                                <i class="bi bi-inbox display-1 text-muted"></i>
                                <h5 class="text-muted mt-3">No Jobs Found</h5>
                                <p class="text-muted">Start by posting your first job!</p>
                                <a href="{{ route('admin.jobs.create') }}" class="btn btn-primary mt-2">
                                    <i class="bi bi-plus-circle me-2"></i>Post New Job
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    
    @if($jobs->hasPages())
        <div class="card-footer bg-white py-3">
            <div class="d-flex justify-content-between align-items-center">
                <div class="text-muted small">
                    Showing {{ $jobs->firstItem() }} to {{ $jobs->lastItem() }} of {{ $jobs->total() }}
                </div>
                <div>
                    {{ $jobs->links() }}
                </div>
            </div>
        </div>
    @endif
</div>

<!-- Delete Confirmation Form (Hidden) -->
<form id="deleteForm" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>
@endsection

@section('scripts')
<script>
    function changeSorting(value) {
        const [sortBy, sortOrder] = value.split('-');
        const url = new URL(window.location.href);
        url.searchParams.set('sort_by', sortBy);
        url.searchParams.set('sort_order', sortOrder);
        window.location.href = url.toString();
    }

    function confirmDelete(jobId) {
        if (confirm('Are you sure you want to delete this job? This action cannot be undone.')) {
            const form = document.getElementById('deleteForm');
            form.action = `/admin/jobs/${jobId}`;
            form.submit();
        }
    }
</script>
@endsection