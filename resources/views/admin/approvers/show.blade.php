@extends('layouts.dashboard')

@section('title', 'View Approver')

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
                    <h2 class="mb-2 fw-bold"><i class="bi bi-person-vcard me-2"></i>Approver Details</h2>
                    <p class="mb-0 opacity-90">View complete approver information</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.approvers.edit', $approver->id) }}" class="btn btn-light">
                        <i class="bi bi-pencil me-2"></i>Edit
                    </a>
                    <a href="{{ route('admin.approvers.index') }}" class="btn btn-outline-light">
                        <i class="bi bi-arrow-left me-2"></i>Back
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Left Column - Personal Information -->
        <div class="col-lg-8">
            <!-- Personal Details -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="bi bi-person me-2"></i>Personal Information</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="text-muted small">Employee ID</label>
                            <p class="fw-semibold mb-0">{{ $approver->employee_id }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small">Full Name</label>
                            <p class="fw-semibold mb-0">{{ $approver->name }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small">Email Address</label>
                            <p class="fw-semibold mb-0">{{ $approver->email }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small">Phone Number</label>
                            <p class="fw-semibold mb-0">{{ $approver->phone_number ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small">Department</label>
                            <p class="fw-semibold mb-0">{{ $approver->department }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small">Designation</label>
                            <p class="fw-semibold mb-0">{{ $approver->designation ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Assignment Details -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="bi bi-briefcase me-2"></i>Assignment Details</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-12">
                            <label class="text-muted small">Assigned Vacancy</label>
                            @if($approver->vacancy)
                                <p class="fw-semibold mb-0">
                                    <span class="badge bg-info">{{ $approver->vacancy->title }}</span>
                                </p>
                                <small class="text-muted">Can only approve applications for this specific job</small>
                            @else
                                <p class="fw-semibold mb-0">
                                    <span class="badge bg-secondary">All Vacancies</span>
                                </p>
                                <small class="text-muted">Can approve applications for all job postings</small>
                            @endif
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small">Account Status</label>
                            <p class="mb-0">
                                @if($approver->status === 'active')
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-danger">Inactive</span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- System Information -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="bi bi-clock-history me-2"></i>System Information</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="text-muted small">Created At</label>
                            <p class="fw-semibold mb-0">{{ $approver->created_at->format('M d, Y h:i A') }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small">Last Updated</label>
                            <p class="fw-semibold mb-0">{{ $approver->updated_at->format('M d, Y h:i A') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column - Photo & Actions -->
        <div class="col-lg-4">
            <!-- Photo -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="bi bi-image me-2"></i>Photo</h5>
                </div>
                <div class="card-body text-center">
                    @if($approver->photo)
                        <img src="{{ asset('storage/' . $approver->photo) }}" alt="Approver Photo"
                             class="img-fluid rounded" style="max-width: 100%;">
                    @else
                        <div class="text-muted py-5">
                            <i class="bi bi-person-circle" style="font-size: 5rem;"></i>
                            <p class="mt-2">No photo uploaded</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="bi bi-lightning me-2"></i>Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.approvers.edit', $approver->id) }}" class="btn btn-primary">
                            <i class="bi bi-pencil me-2"></i>Edit Details
                        </a>

                        <form action="{{ route('admin.approvers.toggle-status', $approver->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-warning w-100">
                                <i class="bi bi-toggle-{{ $approver->status === 'active' ? 'on' : 'off' }} me-2"></i>
                                {{ $approver->status === 'active' ? 'Deactivate' : 'Activate' }}
                            </button>
                        </form>

                        <form action="{{ route('admin.approvers.destroy', $approver->id) }}" method="POST"
                              onsubmit="return confirm('Are you sure you want to delete this approver?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger w-100">
                                <i class="bi bi-trash me-2"></i>Delete Approver
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@if(session('success'))
<script>
    alert('{{ session('success') }}');
</script>
@endif
@endsection
