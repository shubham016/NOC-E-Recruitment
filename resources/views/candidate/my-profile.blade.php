@extends('layouts.app')

@section('title', 'View Profile')

@section('sidebar-menu')
    <a href="{{ route('candidate.dashboard') }}" class="sidebar-menu-item">
        <i class="bi bi-speedometer2"></i>
        <span>Dashboard</span>
    </a>
    <a href="{{ route('candidate.jobs.index') }}" class="sidebar-menu-item">
        <i class="bi bi-search"></i>
        <span>Vacancy</span>
    </a>
    <a href="{{ route('candidate.applications.index') }}" class="sidebar-menu-item">
        <i class="bi bi-file-earmark-text"></i>
        <span>My Applications</span>
    </a>
    <a href="{{ route('candidate.viewresult') }}" class="sidebar-menu-item">
        <i class="bi bi-file-earmark-check"></i>
        <span>View Result</span>
    </a>
    <!-- <a href="{{ route('candidate.my-profile') }}" class="sidebar-menu-item active">
        <i class="bi bi-person"></i>
        <span>My Profile</span>
    </a> -->
    <a href="{{ route('candidate.admit-card') }}" class="sidebar-menu-item">
        <i class="bi bi-box-arrow-down"></i>
        <span>Download Admit Card</span>
    </a>
    <a href="{{ route('candidate.change-password') }}" class="sidebar-menu-item">
        <i class="bi bi-lock"></i>
        <span>Change Password</span>
    </a>
@endsection

@section('content')

<div class="card shadow-sm">
    <div class="card-header bg-light text-dark d-flex justify-content-between align-items-center">
        <h4 class="mb-0"><i class="bi bi-pencil-square me-2"></i>My Profile</h4>
        <a href="{{ route('candidate.edit-profile') }}" class="btn btn-danger btn-sm">
            <i class="bi bi-pencil me-1"></i>Edit Profile
        </a>
    </div>

    <div class="card-body">

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle me-2"></i>
                <strong>Please fix the following errors:</strong>
                <ul class="mb-0 mt-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <form action="{{ route('candidate.my-profile.update') }}" method="POST" novalidate>
            @csrf
            @method('PUT')

            {{-- Personal Info --}}
            <h6 class="text-uppercase text-muted fw-semibold mb-3 border-bottom pb-2"
                style="font-size:.75rem;letter-spacing:.08em;">Personal Information</h6>

            <div class="row g-3 mb-4">

                <div class="col-md-4">
                    <label class="form-label fw-semibold">Full Name <span class="text-danger">*</span></label>
                    <div class="form-control bg-light">
                        {{ ucfirst($candidate?->name ?? '—') }}
                    </div>
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold">Email Address <span class="text-danger">*</span></label>
                    <div class="form-control bg-light">
                        {{ ucfirst($candidate?->email ?? '—') }}
                    </div>
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold">Phone Number <span class="text-danger">*</span></label>
                    <div class="form-control bg-light">
                        {{ ucfirst($candidate?->phone ?? '—') }}
                    </div>
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold">Gender<span class="text-danger">*</span></label>

                    <div class="form-control bg-light">
                        {{ ucfirst($candidate?->gender ?? '—') }}
                    </div>
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold">Date of Birth (BS)<span class="text-danger">*</span></label>
                    <div class="form-control bg-light">
                        {{ ucfirst($candidate?->date_of_birth_bs ?? '—') }}
                    </div>
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold">NOC Employee<span class="text-danger">*</span></label>
                    <div class="form-control bg-light">
                        {{ ucfirst($candidate?->noc_employee ?? '—') }}
                    </div>
                </div>

            </div>

            {{-- Citizenship --}}
            <h6 class="text-uppercase text-muted fw-semibold mb-3 border-bottom pb-2"
                style="font-size:.75rem;letter-spacing:.08em;">Citizenship and National ID Details</h6>

            <div class="row g-3 mb-4">

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Citizenship Number<span class="text-danger">*</span></label>
                    <div class="form-control bg-light">
                        {{ ucfirst($candidate?->citizenship_number ?? '—') }}
                    </div>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">National ID Number<span class="text-danger">*</span></label>
                    <div class="form-control bg-light">
                        {{ ucfirst($candidate?->nid ?? '—') }}
                    </div>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Issue District<span class="text-danger">*</span></label>
                    <div class="form-control bg-light">
                        {{ ucfirst($candidate?->citizenship_issue_distric ?? '—') }}
                    </div>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Issue Date (BS)<span class="text-danger">*</span></label>
                    <div class="form-control bg-light">
                        {{ ucfirst($candidate?->citizenship_issue_date_bs ?? '—') }}
                    </div>
                </div>

            </div>
        </form>
    </div>
</div>

@endsection