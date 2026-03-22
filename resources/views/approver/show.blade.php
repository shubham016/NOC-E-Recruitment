@extends('layouts.app')

@section('title', 'Application Details')

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

    .info-card {
        background: white;
        border-radius: 10px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        border: 1px solid #e5e7eb;
    }

    .info-card h5 {
        color: #64748b;
        font-weight: 700;
        border-bottom: 2px solid #e5e7eb;
        padding-bottom: 0.75rem;
        margin-bottom: 1rem;
    }

    .info-row {
        display: flex;
        justify-content: space-between;
        padding: 0.75rem 0;
        border-bottom: 1px solid #f3f4f6;
    }

    .info-row:last-child {
        border-bottom: none;
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
</style>
@endsection

@section('content')
<div class="container-fluid px-4 py-4">
    <!-- Page Header -->
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h3 class="fw-bold mb-1">
                    <i class="bi bi-file-earmark-text me-2"></i>Application ID - {{ $application->id }}
                </h3>
                <p class="mb-0 opacity-90 small">Review application details</p>
            </div>
            <div>
                <a href="{{ route('approver.assignedtome') }}" class="btn btn-light">
                    <i class="bi bi-arrow-left me-1"></i>Back
                </a>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <!-- Left Column -->
        <div class="col-lg-8">
            <!-- Candidate Information -->
            <div class="info-card">
                <h5>
                    <i class="bi bi-person text-primary me-2"></i>Candidate Information
                </h5>
                <div class="info-row">
                    <span class="text-muted">Full Name:</span>
                    <span class="fw-semibold">{{ $application->name_english ?? 'N/A' }}</span>
                </div>
                <div class="info-row">
                    <span class="text-muted">Email:</span>
                    <span class="fw-semibold">{{ $application->email ?? 'N/A' }}</span>
                </div>
                <div class="info-row">
                    <span class="text-muted">Phone:</span>
                    <span class="fw-semibold">{{ $application->phone ?? 'N/A' }}</span>
                </div>
                <div class="info-row">
                    <span class="text-muted">Date of Birth:</span>
                    <span class="fw-semibold">{{ $application->birth_date_ad ? \Carbon\Carbon::parse($application->birth_date_ad)->format('M d, Y') : 'N/A' }}</span>
                </div>
            </div>

            <!-- Job Information -->
            <div class="info-card">
                <h5>
                    <i class="bi bi-briefcase text-success me-2"></i>Job Information
                </h5>
                <div class="info-row">
                    <span class="text-muted">Job Title:</span>
                    <span class="fw-semibold">{{ $application->jobPosting->title ?? 'N/A' }}</span>
                </div>
                <div class="info-row">
                    <span class="text-muted">Department:</span>
                    <span class="fw-semibold">{{ $application->jobPosting->department ?? 'N/A' }}</span>
                </div>
                <div class="info-row">
                    <span class="text-muted">Position Level:</span>
                    <span class="fw-semibold">{{ $application->jobPosting->position_level ?? 'N/A' }}</span>
                </div>
            </div>

            <!-- Application Details -->
            <div class="info-card">
                <h5>
                    <i class="bi bi-file-text text-warning me-2"></i>Application Details
                </h5>
                <div class="info-row">
                    <span class="text-muted">Application ID:</span>
                    <span class="fw-semibold">{{ $application->id }}</span>
                </div>
                <div class="info-row">
                    <span class="text-muted">Applied Date:</span>
                    <span class="fw-semibold">
                        {{ $application->created_at->format('M d, Y h:i A') }}
                        <small class="text-muted d-block">{{ adToBS($application->created_at) }} (BS)</small>
                    </span>
                </div>
                <div class="info-row">
                    <span class="text-muted">Status:</span>
                    <span>
                        @if($application->status === 'approved')
                            <span class="badge bg-success">Approved</span>
                        @elseif($application->status === 'rejected')
                            <span class="badge bg-danger">Rejected</span>
                        @else
                            <span class="badge bg-warning text-dark">Pending</span>
                        @endif
                    </span>
                </div>
                @if($application->reviewer)
                <div class="info-row">
                    <span class="text-muted">Reviewer:</span>
                    <span class="fw-semibold">{{ $application->reviewer->name }}</span>
                </div>
                @endif
            </div>
        </div>

        <!-- Right Column -->
        <div class="col-lg-4">
            <!-- Actions -->
            @if($application->status !== 'approved' && $application->status !== 'rejected')
            <div class="info-card">
                <h5>
                    <i class="bi bi-gear text-secondary me-2"></i>Actions
                </h5>
                <form action="{{ route('approver.applications.updateStatus', $application->id) }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Decision</label>
                        <select name="status" class="form-select" required>
                            <option value="">Select Decision</option>
                            <option value="approved">Approve</option>
                            <option value="rejected">Reject</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Remarks <span class="text-danger">*</span></label>
                        <textarea name="approver_notes" class="form-control" rows="4" placeholder="Add your remarks here..." required></textarea>
                    </div>
                    <button type="submit" class="btn btn-gold w-100">
                        <i class="bi bi-check-circle me-1"></i>Submit Decision
                    </button>
                </form>
            </div>
            @else
            <div class="info-card">
                <h5>
                    <i class="bi bi-info-circle text-info me-2"></i>Status
                </h5>
                <div class="alert alert-{{ $application->status === 'approved' ? 'success' : 'danger' }} mb-0">
                    This application has been <strong>{{ $application->status }}</strong>.
                </div>
            </div>
            @endif

            <!-- Application Timeline -->
            <div class="info-card">
                <h5>
                    <i class="bi bi-clock-history text-primary me-2"></i>Timeline
                </h5>
                <div class="timeline">
                    <div class="mb-3">
                        <div class="small text-muted">Applied</div>
                        <div class="fw-semibold">
                            {{ $application->created_at->format('M d, Y h:i A') }}
                            <small class="text-muted d-block">{{ adToBS($application->created_at) }} (BS)</small>
                        </div>
                    </div>
                    @if($application->approved_at)
                    <div class="mb-3">
                        <div class="small text-muted">Approved</div>
                        <div class="fw-semibold">
                            {{ $application->approved_at->format('M d, Y h:i A') }}
                            <small class="text-muted d-block">{{ adToBS($application->approved_at) }} (BS)</small>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
