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
                        <i class="bi bi-funnel me-1"></i>Filter
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Approvers Table -->
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th>Employee ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Department</th>
                            <th>Designation</th>
                            <th style="min-width: 200px;">Assign Vacancy</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($approvers as $approver)
                            <tr>
                                <td class="fw-semibold">{{ $approver->employee_id }}</td>
                                <td>{{ $approver->name }}</td>
                                <td>{{ $approver->email }}</td>
                                <td>{{ $approver->department }}</td>
                                <td>{{ $approver->designation ?? 'N/A' }}</td>
                                <td>
                                    <form action="{{ route('admin.approvers.assign-vacancy', $approver->id) }}" method="POST" class="d-flex gap-1">
                                        @csrf
                                        <select name="vacancy_id" class="form-select form-select-sm" onchange="this.form.submit()">
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
                                    @if($approver->status === 'active')
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-danger">Inactive</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('admin.approvers.show', $approver->id) }}" class="btn btn-outline-primary" title="View">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.approvers.edit', $approver->id) }}" class="btn btn-outline-warning" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('admin.approvers.toggle-status', $approver->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-outline-secondary" title="Toggle Status">
                                                <i class="bi bi-toggle-{{ $approver->status === 'active' ? 'on' : 'off' }}"></i>
                                            </button>
                                        </form>
                                        <form action="{{ route('admin.approvers.destroy', $approver->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger" title="Delete">
                                                <i class="bi bi-trash"></i>
                                            </button>
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
