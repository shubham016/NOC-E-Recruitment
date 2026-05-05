@extends('layouts.dashboard')

@section('title', 'Manage Reviewers')

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

@section('custom-styles')
    <style>
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

        .reviewer-row {
            transition: all 0.2s;
        }

        .reviewer-row:hover {
            background-color: #f8fafc;
        }

        /* Modern Table */
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
            color: #000;
            border: 1px solid #060606;
            vertical-align: middle;
            padding: 1rem 1.5rem;
        }

        .modern-table tbody tr {
            transition: all 0.2s;
        }

        .modern-table tbody tr:hover {
            background: #f8fafc;
        }

        .search-section {
            background: white;
            border-radius: 0.5rem;
            padding: 1.5rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .reviewer-avatar {
            width: 50px;
            height: 50px;
            border-radius: 8px;
            object-fit: cover;
        }

        .reviewer-avatar-placeholder {
            width: 50px;
            height: 50px;
            border-radius: 8px;
            background: linear-gradient(135deg, #c9a84c 0%, #a07828 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 700;
            font-size: 1.25rem;
        }

        .status-badge-simple {
            padding: 0.375rem 0.75rem;
            border-radius: 6px;
            font-size: 0.875rem;
            font-weight: 600;
        }

        .status-active {
            background: #d1fae5;
            color: #065f46;
        }

        .status-inactive {
            background: #f3f4f6;
            color: #6b7280;
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-1">Manage Reviewers</h1>
                <p class="text-muted mb-0">View and manage all application reviewers</p>
            </div>
            <a href="{{ route('admin.reviewers.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle me-2"></i>Add New Reviewer
            </a>
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

        <!-- Statistics Cards -->
        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <div class="stat-card">
                    <div class="d-flex align-items-center gap-3">
                        <div class="bg-primary bg-opacity-10 rounded-3 p-3">
                            <i class="bi bi-people-fill text-primary fs-4"></i>
                        </div>
                        <div>
                            <h3 class="fw-bold mb-0">{{ $stats['total'] }}</h3>
                            <small class="text-muted">Total Reviewers</small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-card">
                    <div class="d-flex align-items-center gap-3">
                        <div class="bg-success bg-opacity-10 rounded-3 p-3">
                            <i class="bi bi-check-circle-fill text-success fs-4"></i>
                        </div>
                        <div>
                            <h3 class="fw-bold mb-0">{{ $stats['active'] }}</h3>
                            <small class="text-muted">Active Reviewers</small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-card">
                    <div class="d-flex align-items-center gap-3">
                        <div class="bg-danger bg-opacity-10 rounded-3 p-3">
                            <i class="bi bi-x-circle-fill text-danger fs-4"></i>
                        </div>
                        <div>
                            <h3 class="fw-bold mb-0">{{ $stats['inactive'] }}</h3>
                            <small class="text-muted">Inactive Reviewers</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Search & Filter Section -->
        <div class="search-section mb-4">
            <form method="GET" class="row g-3">
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Search</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="bi bi-search"></i>
                        </span>
                        <input type="text" name="search" class="form-control"
                            placeholder="Search by name, email, phone..." value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Department</label>
                    <select name="department" class="form-select">
                        <option value="">All Departments</option>
                        @foreach($departments as $dept)
                            <option value="{{ $dept }}" {{ request('department') == $dept ? 'selected' : '' }}>
                                {{ $dept }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label fw-semibold">Status</label>
                    <select name="status" class="form-select">
                        <option value="">All Status</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">&nbsp;</label>
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-search me-1"></i> Search
                    </button>
                </div>
            </form>
        </div>

        <!-- Reviewers Table -->
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3">
                <div class="d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 fw-bold">
                        <i class="bi bi-list-ul text-primary me-2"></i>Reviewers List
                    </h6>
                    <span class="badge bg-primary ms-2"> Total {{ $reviewers->total() }}</span>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0 modern-table w-100"
                        style="table-layout: auto; white-space: nowrap;">
                        <thead class="table-light">
                            <tr>
                                <th class="text-center text-uppercase">S.N</th>
                                <th class="text-center text-uppercase">Reviewer</th>
                                <th class="text-center text-uppercase">Email</th>
                                <th class="text-center text-uppercase">Phone</th>
                                <th class="text-center text-uppercase">Department</th>
                                <th class="text-center text-uppercase">Designation</th>
                                <th class="text-center text-uppercase">Status</th>
                                <th class="text-center text-uppercase">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="text-center align-middle">
                            @forelse($reviewers as $reviewer)
                                <tr class="reviewer-row">
                                    <td>{{ $reviewers->firstItem() + $loop->index }}</td>
                                    <td>
                                        <div class="d-flex align-items-center justify-content-center gap-2">
                                            @if($reviewer->photo)
                                                <img src="{{ asset('storage/' . $reviewer->photo) }}"
                                                    alt="{{ $reviewer->name }}" class="reviewer-avatar">
                                            @else
                                                <div class="reviewer-avatar-placeholder">
                                                    {{ strtoupper(substr($reviewer->name, 0, 1)) }}
                                                </div>
                                            @endif
                                            <strong>{{ $reviewer->name }}</strong>
                                        </div>
                                    </td>
                                    <td>{{ $reviewer->email }}</td>
                                    <td>{{ $reviewer->phone ?? '-' }}</td>
                                    <td>{{ $reviewer->department ?? '-' }}</td>
                                    <td>{{ $reviewer->designation ?? '-' }}</td>
                                    <td>
                                        <span class="status-badge-simple {{ $reviewer->status == 'active' ? 'status-active' : 'status-inactive' }}">
                                            {{ ucfirst($reviewer->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('admin.reviewers.show', $reviewer->id) }}"
                                                class="btn btn-outline-primary" title="View">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.reviewers.edit', $reviewer->id) }}"
                                                class="btn btn-outline-secondary" title="Edit">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <button type="button" class="btn btn-outline-warning"
                                                data-bs-toggle="modal" data-bs-target="#toggleModal{{ $reviewer->id }}"
                                                title="Toggle Status">
                                                <i class="bi bi-arrow-repeat"></i>
                                            </button>
                                            <button type="button" class="btn btn-outline-danger"
                                                onclick="confirmDelete({{ $reviewer->id }})" title="Delete">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>

                                <!-- Toggle Status Modal -->
                                <div class="modal fade" id="toggleModal{{ $reviewer->id }}" tabindex="-1">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <form action="{{ route('admin.reviewers.toggle-status', $reviewer->id) }}"
                                                method="POST">
                                                @csrf
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Change Reviewer Status</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <p>Are you sure you want to
                                                        <strong>{{ $reviewer->status == 'active' ? 'deactivate' : 'activate' }}</strong>
                                                        {{ $reviewer->name }}?
                                                    </p>
                                                    @if($reviewer->status == 'active')
                                                        <div class="alert alert-warning">
                                                            <i class="bi bi-exclamation-triangle me-2"></i>
                                                            This reviewer will not be able to log in once deactivated.
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal">Cancel</button>
                                                    <button type="submit"
                                                        class="btn btn-{{ $reviewer->status == 'active' ? 'warning' : 'success' }}">
                                                        {{ $reviewer->status == 'active' ? 'Deactivate' : 'Activate' }}
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-5">
                                        <i class="bi bi-inbox display-1 text-muted"></i>
                                        <h5 class="text-muted mt-3">No Reviewers Found</h5>
                                        <p class="text-muted">
                                            @if(request()->hasAny(['search', 'status', 'department']))
                                                No reviewers match your search criteria.
                                            @else
                                                Start by adding your first reviewer!
                                            @endif
                                        </p>
                                        @if(request()->hasAny(['search', 'status', 'department']))
                                            <a href="{{ route('admin.reviewers.index') }}" class="btn btn-primary mt-2">
                                                <i class="bi bi-x-circle me-2"></i>Clear Filters
                                            </a>
                                        @else
                                            <a href="{{ route('admin.reviewers.create') }}" class="btn btn-primary mt-2">
                                                <i class="bi bi-plus-circle me-2"></i>Add New Reviewer
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            @if($reviewers->hasPages())
                <div class="card-footer bg-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="text-muted small">
                            Showing {{ $reviewers->firstItem() }} to {{ $reviewers->lastItem() }} of
                            {{ $reviewers->total() }}
                        </div>
                        <div>
                            {{ $reviewers->links() }}
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Delete Confirmation Forms (Hidden) -->
    @foreach($reviewers as $reviewer)
        <form id="deleteForm{{ $reviewer->id }}" method="POST"
            action="{{ route('admin.reviewers.destroy', $reviewer->id) }}" style="display: none;">
            @csrf
            @method('DELETE')
        </form>
    @endforeach
@endsection

@section('scripts')
    <script>
        function confirmDelete(reviewerId) {
            if (confirm('Are you sure you want to delete this reviewer? This action cannot be undone.')) {
                const form = document.getElementById('deleteForm' + reviewerId);
                form.submit();
            }
        }
    </script>
@endsection
