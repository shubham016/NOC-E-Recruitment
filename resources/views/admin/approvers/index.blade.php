@extends('layouts.dashboard')

@section('title', 'Approvers Management')

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
            color: #000 !important;
            border: 1px solid #060606;
            vertical-align: middle;
        }

        .modern-table tbody td:not(:has(.badge, .btn)) {
            color: #000 !important;
        }

        .modern-table tbody tr {
            transition: all 0.2s;
        }

        .modern-table tbody tr:hover {
            background: #f8fafc;
        }

        /* Assign Vacancy Dropdown */
        .assign-vacancy-select {
            width: auto !important;
            max-width: 160px;
            padding: 0.25rem 1.75rem 0.25rem 0.5rem !important;
            font-size: 0.8rem;
            font-weight: 500;
        }

        /* Card Header Border */
        .card-header.border-bottom {
            border-bottom: 2px solid #dee2e6 !important;
        }

        /* Action Buttons */
        .gov-action-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 32px;
            height: 32px;
            border-radius: 6px;
            border: 1px solid #d1d5db;
            background: white;
            color: #374151;
            transition: all 0.2s;
            cursor: pointer;
            font-size: 0.875rem;
        }

        .gov-action-btn:hover {
            background: #f3f4f6;
            border-color: #9ca3af;
            color: #1f2937;
        }

        .gov-action-btn-success {
            border-color: #10b981;
            color: #10b981;
        }

        .gov-action-btn-success:hover {
            background: #ecfdf5;
            border-color: #059669;
            color: #059669;
        }

        .gov-action-btn-danger {
            border-color: #ef4444;
            color: #ef4444;
        }

        .gov-action-btn-danger:hover {
            background: #fef2f2;
            border-color: #dc2626;
            color: #dc2626;
        }
    </style>
@endsection

@section('content')
<div class="container-fluid px-4 py-4">
    <!-- Page Header -->
    <div class="card border-0 shadow-sm mb-4" style="background: linear-gradient(135deg, #c9a84c 0%, #a07828 100%);">
        <div class="card-body text-white p-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="mb-2 fw-bold"><i class="bi bi-person-check me-2"></i>Approvers Management</h2>
                    <p class="mb-0 opacity-90">Manage application approvers and their permissions</p>
                </div>
                <a href="{{ route('admin.approvers.create') }}" class="btn btn-light btn-lg">
                    <i class="bi bi-plus-circle me-2"></i>Add New Approver
                </a>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="stat-icon bg-primary bg-opacity-10 text-primary p-3 rounded">
                                <i class="bi bi-people fs-4"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h3 class="mb-0 fw-bold">{{ $stats['total'] }}</h3>
                            <p class="text-muted mb-0 small">Total Approvers</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="stat-icon bg-success bg-opacity-10 text-success p-3 rounded">
                                <i class="bi bi-check-circle fs-4"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h3 class="mb-0 fw-bold">{{ $stats['active'] }}</h3>
                            <p class="text-muted mb-0 small">Active Approvers</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="stat-icon bg-danger bg-opacity-10 text-danger p-3 rounded">
                                <i class="bi bi-x-circle fs-4"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h3 class="mb-0 fw-bold">{{ $stats['inactive'] }}</h3>
                            <p class="text-muted mb-0 small">Inactive Approvers</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.approvers.index') }}" class="row g-3">
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Search</label>
                    <input type="text" name="search" class="form-control" placeholder="Name, Email, Employee ID..." value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Status</label>
                    <select name="status" class="form-select">
                        <option value="">All Status</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Department</label>
                    <select name="department" class="form-select">
                        <option value="">All Departments</option>
                        @foreach($departments as $department)
                            <option value="{{ $department }}" {{ request('department') == $department ? 'selected' : '' }}>
                                {{ $department }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">
                        <!-- <i class="bi bi-funnel me-1"></i> -->
                        Search
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Approvers Table -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3 border-bottom">
            <div class="d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-bold">
                    <!-- <i class="bi bi-people-fill text-primary me-2"></i> -->
                    Approvers List
                </h6>
                <span class="badge bg-primary ms-2">Total {{ $approvers->total() }}</span>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 modern-table w-100"
                    style="table-layout: auto; white-space: nowrap;">
                    <thead class="table-light">
                        <tr>
                            <th class="text-center text-uppercase">S.N</th>
                            <th class="text-center text-uppercase">Employee ID</th>
                            <th class="text-center text-uppercase">Name</th>
                            <th class="text-center text-uppercase">Email</th>
                            <th class="text-center text-uppercase">Department</th>
                            <th class="text-center text-uppercase">Designation</th>
                            <th class="text-center text-uppercase">Status</th>
                            <th class="text-center text-uppercase" style="min-width: 200px;">Assign Vacancy</th>
                            <th class="text-center text-uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="text-center align-middle">
                        @forelse($approvers as $approver)
                            <tr>
                                <td>{{ $approvers->firstItem() + $loop->index }}</td>
                                <td>{{ $approver->employee_id }}</td>
                                <td>{{ $approver->name }}</td>
                                <td>{{ $approver->email }}</td>
                                <td>{{ $approver->department }}</td>
                                <td>{{ $approver->designation ?? 'N/A' }}</td>
                                <td>
                                    @if($approver->status === 'active')
                                        Active
                                    @else
                                        Inactive
                                    @endif
                                </td>
                                <td>
                                    <form action="{{ route('admin.approvers.assign-vacancy', $approver->id) }}" method="POST" class="d-flex justify-content-center">
                                        @csrf
                                        <select name="vacancy_id" class="form-select form-select-sm assign-vacancy-select" onchange="this.form.submit()">
                                            <option value="">All Vacancies</option>
                                            @foreach($vacancies as $vacancy)
                                                <option value="{{ $vacancy->id }}" {{ $approver->vacancy_id == $vacancy->id ? 'selected' : '' }}>
                                                    {{ Str::limit($vacancy->title, 30) }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </form>
                                </td>
                                <td>
                                    <div class="d-flex justify-content-center gap-2">
                                        <a href="{{ route('admin.approvers.show', $approver->id) }}"
                                           class="gov-action-btn"
                                           title="View Details">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.approvers.edit', $approver->id) }}"
                                           class="gov-action-btn"
                                           title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <button type="button"
                                                class="gov-action-btn"
                                                onclick="event.preventDefault(); if(confirm('Toggle status?')) document.getElementById('toggle-form-{{ $approver->id }}').submit();"
                                                title="Toggle Status">
                                            <i class="bi bi-toggle-{{ $approver->status === 'active' ? 'on' : 'off' }}"></i>
                                        </button>
                                        <form id="toggle-form-{{ $approver->id }}" action="{{ route('admin.approvers.toggle-status', $approver->id) }}" method="POST" style="display: none;">
                                            @csrf
                                        </form>
                                        <button type="button"
                                                class="gov-action-btn gov-action-btn-danger"
                                                onclick="event.preventDefault(); if(confirm('Are you sure you want to delete this approver?')) document.getElementById('delete-form-{{ $approver->id }}').submit();"
                                                title="Delete">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                        <form id="delete-form-{{ $approver->id }}" action="{{ route('admin.approvers.destroy', $approver->id) }}" method="POST" style="display: none;">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-4 text-muted">
                                    <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                    No approvers found
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
        {{ $approvers->links() }}
    </div>
</div>

@if(session('success'))
<script>
    alert('{{ session('success') }}');
</script>
@endif
@endsection
