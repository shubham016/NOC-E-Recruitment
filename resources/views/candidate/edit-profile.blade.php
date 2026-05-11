@extends('layouts.app')

@section('title', 'Edit Profile')

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
        <h4 class="mb-0"><i class="bi bi-pencil-square me-2"></i>Edit Profile</h4>
        <a href="{{ route('candidate.my-profile') }}" class="btn btn-sm btn-secondary">
            <i class="bi bi-arrow-left me-1"></i> Back to Profile
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
                    <input type="text"
                           name="name"
                           class="form-control @error('name') is-invalid @enderror"
                           value="{{ old('name', $candidate->name) }}"
                           placeholder="Full name"
                           required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold">Email Address <span class="text-danger">*</span></label>
                    <input type="email"
                           name="email"
                           class="form-control @error('email') is-invalid @enderror"
                           value="{{ old('email', $candidate->email) }}"
                           placeholder="Email address"
                           required>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold">Phone Number <span class="text-danger">*</span></label>
                    <input type="text"
                           name="phone"
                           class="form-control @error('phone') is-invalid @enderror"
                           value="{{ old('phone', $candidate->phone) }}"
                           placeholder="e.g. 98XXXXXXXX"
                           required>
                    @error('phone')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- FIX 1: name="gender", values capitalised to match DB (Male/Female/Other)
                     and controller validation rule 'in:Male,Female,Other' --}}
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Gender <span class="text-danger">*</span></label>
                    <select name="gender"
                            class="form-select @error('gender') is-invalid @enderror"
                            required>
                        <option value="">-- Select Gender --</option>
                        <option value="Male"   {{ old('gender', $candidate->gender) === 'Male'   ? 'selected' : '' }}>Male</option>
                        <option value="Female" {{ old('gender', $candidate->gender) === 'Female' ? 'selected' : '' }}>Female</option>
                        <option value="Other"  {{ old('gender', $candidate->gender) === 'Other'  ? 'selected' : '' }}>Other</option>
                    </select>
                    @error('gender')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold">Date of Birth (BS) <span class="text-danger">*</span></label>
                    <input type="text"
                           name="date_of_birth_bs"
                           class="form-control @error('date_of_birth_bs') is-invalid @enderror"
                           value="{{ old('date_of_birth_bs', $candidate->date_of_birth_bs) }}"
                           placeholder="e.g. 2050-01-15">
                    @error('date_of_birth_bs')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- FIX 2: name was incorrectly set to "gender" — changed to "noc_employee"
                     Values stored as Yes/No string in candidate_registration --}}
                <div class="col-md-4">
                    <label class="form-label fw-semibold">NOC Employee <span class="text-danger">*</span></label>
                    <select name="noc_employee"
                            id="nocEmployeeSelect"
                            class="form-select @error('noc_employee') is-invalid @enderror">
                        <option value="">-- Select --</option>
                        <option value="Yes" {{ old('noc_employee', $candidate->noc_employee) === 'Yes' ? 'selected' : '' }}>Yes</option>
                        <option value="No"  {{ old('noc_employee', $candidate->noc_employee) === 'No'  ? 'selected' : '' }}>No</option>
                    </select>
                    @error('noc_employee')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- employee_id shown only when NOC Employee = Yes --}}
                <div class="col-md-4" id="employeeIdWrapper"
     style="{{ old('noc_employee', $candidate?->noc_employee) === 'Yes' ? '' : 'display:none;' }}">

    <label class="form-label fw-semibold">
        Employee ID

        @if(old('noc_employee', $candidate?->noc_employee) === 'Yes')
            <span class="text-danger">*</span>
        @endif
    </label>

        <input type="text"
            name="employee_id"
            class="form-control @error('employee_id') is-invalid @enderror"
            value="{{ old('employee_id', $candidate?->employee_id) }}"
            placeholder="Employee ID"
            {{ old('noc_employee', $candidate?->noc_employee) === 'Yes' ? 'required' : '' }}>

        @error('employee_id')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

            </div>

            {{-- Citizenship & NID --}}
            <h6 class="text-uppercase text-muted fw-semibold mb-3 border-bottom pb-2"
                style="font-size:.75rem;letter-spacing:.08em;">Citizenship and National ID Details</h6>

            <div class="row g-3 mb-4">

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Citizenship Number <span class="text-danger">*</span></label>
                    <input type="text"
                           name="citizenship_number"
                           class="form-control @error('citizenship_number') is-invalid @enderror"
                           value="{{ old('citizenship_number', $candidate->citizenship_number) }}"
                           placeholder="Citizenship number">
                    @error('citizenship_number')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">National ID Number <span class="text-danger">*</span></label>
                    <input type="text"
                           name="nid"
                           class="form-control @error('nid') is-invalid @enderror"
                           value="{{ old('nid', $candidate->nid) }}"
                           placeholder="NID number">
                    @error('nid')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- citizenship_issue_distric — one 't', matches DB column exactly --}}
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Issue District <span class="text-danger">*</span></label>
                    <input type="text"
                           name="citizenship_issue_distric"
                           class="form-control @error('citizenship_issue_distric') is-invalid @enderror"
                           value="{{ old('citizenship_issue_distric', $candidate->citizenship_issue_distric) }}"
                           placeholder="e.g. Kathmandu">
                    @error('citizenship_issue_distric')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Issue Date (BS) <span class="text-danger">*</span></label>
                    <input type="text"
                           name="citizenship_issue_date_bs"
                           class="form-control @error('citizenship_issue_date_bs') is-invalid @enderror"
                           value="{{ old('citizenship_issue_date_bs', $candidate->citizenship_issue_date_bs) }}"
                           placeholder="e.g. 2065-05-20">
                    @error('citizenship_issue_date_bs')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

            </div>

            {{-- Actions --}}
            <div class="d-flex gap-2 justify-content-end border-top pt-3">
                <a href="{{ route('candidate.my-profile') }}" class="btn btn-secondary">
                    <i class="bi bi-x-circle me-1"></i> Cancel
                </a>
                <button type="submit" class="btn btn-danger">
                    <i class="bi bi-check-circle me-1"></i> Save Changes
                </button>
            </div>

        </form>
    </div>
</div>

@push('scripts')
<script>
    document.getElementById('nocEmployeeSelect').addEventListener('change', function () {
        document.getElementById('employeeIdWrapper').style.display =
            this.value === 'Yes' ? '' : 'none';
    });
</script>
@endpush

@endsection