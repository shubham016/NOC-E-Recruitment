@extends('layouts.app')

@section('title', 'View Registration')

@section('content')
@section('sidebar-menu')
    <a href="{{ route('candidate.dashboard') }}" class="sidebar-menu-item">
        <i class="bi bi-speedometer2"></i>
        <span>Dashboard</span>
    </a>
    <a href="#" class="sidebar-menu-item">
        <i class="bi bi-search"></i>
        <span>Vacancy</span>
    </a>
    <a href="{{ route('candidate.applications.index') }}" class="sidebar-menu-item active">
        <i class="bi bi-file-earmark-text"></i>
        <span>My Applications</span>
    </a>
    <a href="#" class="sidebar-menu-item">
        <i class="bi bi-file-earmark-check"></i>
        <span>View Result</span>
    </a>
    <a href="{{ route('candidate.my-profile') }}" class="sidebar-menu-item">
        <i class="bi bi-person"></i>
        <span>My Profile</span>
    </a>
    <a href="#" class="sidebar-menu-item">
        <i class="bi bi-box-arrow-down"></i>
        <span>Download Admit Card</span>
    </a>
    <a href="{{ route('candidate.change-password') }}" class="sidebar-menu-item">
        <i class="bi bi-lock"></i>
        <span>Change Password</span>
    </a>
@endsection
<div class="container my-2">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h4 class="mb-0">View Application Details</h4>
            <div>
                <a href="{{ route('candidate.applications.edit', $applicationform) }}"
                    class="btn btn-warning btn-sm me-2">
                        <i class="fas fa-edit"></i> Edit
                    </a>



                <a href="{{ route('candidate.applications.index') }}" class="btn btn-light btn-sm">
                    <i class="fas fa-arrow-left"></i> Back
                </a>
            </div>
        </div>

        <div class="card-body">
            {{-- SECTION 1: Personal Information --}}
            <div class="mb-4">
                <h5 class="text-primary border-bottom pb-2 mb-3">
                    <i class="fas fa-user"></i> Personal Information
                </h5>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <strong>Full Name (English):</strong>
                        <p class="mb-0">{{ $applicationform->name_english ?? '-' }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Full Name (Nepali):</strong>
                        <p class="mb-0">{{ $applicationform->name_nepali ?? '-' }}</p>
                    </div>
                    <div class="col-md-3 mb-3">
                        <strong>Birth Date (A.D):</strong>
                        <p class="mb-0">
                            @if($applicationform->birth_date_ad)
                                {{ is_string($applicationform->birth_date_ad) ? \Carbon\Carbon::parse($applicationform->birth_date_ad)->format('F d, Y') : $applicationform->birth_date_ad->format('F d, Y') }}
                            @else
                                -
                            @endif
                        </p>
                    </div>
                    <div class="col-md-3 mb-3">
                        <strong>Birth Date (B.S):</strong>
                        <p class="mb-0">{{ $applicationform->birth_date_bs ?? '-' }}</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <strong>Age:</strong>
                        <p class="mb-0">{{ $applicationform->age ?? '-' }} {{ $applicationform->age ? 'years' : '' }}</p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <strong>Phone:</strong>
                        <p class="mb-0">{{ $applicationform->phone ?? '-' }}</p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <strong>Gender:</strong>
                        <p class="mb-0">{{ ucfirst($applicationform->gender ?? '-') }}</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <strong>Marital Status:</strong>
                        <p class="mb-0">{{ ucfirst($applicationform->marital_status ?? '-') }}</p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <strong>Spouse Name:</strong>
                        <p class="mb-0">{{ ucfirst($applicationform->spouse_name_english ?? '-') }}</p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <strong>Spouse Nationality:</strong>
                        <p class="mb-0">{{ ucfirst($applicationform->spouse_nationality ?? '-') }}</p>
                    </div>

                </div>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <strong>Blood Group:</strong>
                        <p class="mb-0">{{ $applicationform->blood_group ?? '-' }}</p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <strong>Nationality:</strong>
                        <p class="mb-0">{{ $applicationform->nationality ?? '-' }}</p>
                    </div>
                </div>
            </div>

            {{-- SECTION 2: Citizenship Information --}}
            <div class="mb-4">
                <h5 class="text-primary border-bottom pb-2 mb-3">
                    <i class="fas fa-id-card"></i> Citizenship Information
                </h5>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <strong>Citizenship Number:</strong>
                        <p class="mb-0">{{ $applicationform->citizenship_number ?? '-' }}</p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <strong>Citizenship Issue Date (B.S):</strong>
                        <p class="mb-0">
                            @if($applicationform->citizenship_issue_date_bs)
                                {{ is_string($applicationform->citizenship_issue_date_bs) ? \Carbon\Carbon::parse($applicationform->citizenship_issue_date_bs)->format('F d, Y') : $applicationform->citizenship_issue_date_bs->format('F d, Y') }}
                            @else
                                -
                            @endif
                        </p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <strong>Citizenship Issue District:</strong>
                        <p class="mb-0">{{ $applicationform->citizenship_issue_district ?? '-' }}</p>
                    </div>
                </div>
            </div>

            {{-- SECTION 3: Family Information --}}
            <div class="mb-4">
                <h5 class="text-primary border-bottom pb-2 mb-3">
                    <i class="fas fa-users"></i> Family Information
                </h5>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <strong>Father Name:</strong>
                        <p class="mb-0">{{ $applicationform->father_name_english ?? '-' }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Father's Qualification:</strong>
                        <p class="mb-0">{{ $applicationform->father_qualification ?? '-' }}</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <strong>Mother Name:</strong>
                        <p class="mb-0">{{ $applicationform->mother_name_english ?? '-' }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Mother's Qualification:</strong>
                        <p class="mb-0">{{ $applicationform->mother_qualification ?? '-' }}</p>
                    </div>
                </div>
                <div class="row">
                   <div class="col-md-6 mb-3">
                        <strong>Parent's Occupation:</strong>
                        <p class="mb-0">{{ $applicationform->parent_occupation ?? '-' }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Grandfather Name:</strong>
                        <p class="mb-0">{{ $applicationform->grandfather_name_english ?? '-' }}</p>
                    </div>
                </div>
            </div>

            {{-- SECTION 4: General Information --}}
            <div class="mb-4">
                <h5 class="text-primary border-bottom pb-2 mb-3">
                    <i class="fas fa-info-circle"></i> General Information
                </h5>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <strong>Religion:</strong>
                        <p class="mb-0">
                            {{ $applicationform->religion ?? '-' }}
                            @if($applicationform->religion === 'Other' && $applicationform->religion_other)
                                ({{ $applicationform->religion_other }})
                            @endif
                        </p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <strong>Community:</strong>
                        <p class="mb-0">
                            {{ $applicationform->community ?? '-' }}
                            @if($applicationform->community === 'Other' && $applicationform->community_other)
                                ({{ $applicationform->community_other }})
                            @endif
                        </p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <strong>Ethnic Group:</strong>
                        <p class="mb-0">
                            {{ $applicationform->ethnic_group ?? '-' }}
                            @if($applicationform->ethnic_group === 'Other' && $applicationform->ethnic_group_other)
                                ({{ $applicationform->ethnic_group_other }})
                            @endif
                        </p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <strong>Mother Tongue:</strong>
                        <p class="mb-0">{{ $applicationform->mother_tongue ?? '-' }}</p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <strong>Employment Status:</strong>
                        <p class="mb-0">{{ ucfirst($applicationform->employment_status ?? '-') }}</p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <strong>Other Employment Details:</strong>
                        <p class="mb-0">{{ $applicationform->employment_other ?? '-' }}</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <strong>Physical Disability:</strong>
                        <p class="mb-0">{{ ucfirst($applicationform->physical_disability ?? '-') }}</p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <strong>Disability Details:</strong>
                        <p class="mb-0">{{ $applicationform->disability_other ?? '-' }}</p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <strong>NOC Employee:</strong>
                        <p class="mb-0">{{ ucfirst($applicationform->noc_employee ?? '-') }}</p>
                    </div>
                </div>
            </div>

            {{-- SECTION 5: Permanent Address --}}
            <div class="mb-4">
                <h5 class="text-primary border-bottom pb-2 mb-3">
                    <i class="fas fa-home"></i> Permanent Address
                </h5>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <strong>Province:</strong>
                        <p class="mb-0">{{ $applicationform->permanent_province ?? '-' }}</p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <strong>District:</strong>
                        <p class="mb-0">{{ $applicationform->permanent_district ?? '-' }}</p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <strong>Municipality:</strong>
                        <p class="mb-0">{{ $applicationform->permanent_municipality ?? '-' }}</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <strong>Ward No.:</strong>
                        <p class="mb-0">{{ $applicationform->permanent_ward ?? '-' }}</p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <strong>Tole:</strong>
                        <p class="mb-0">{{ $applicationform->permanent_tole ?? '-' }}</p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <strong>House Number:</strong>
                        <p class="mb-0">{{ $applicationform->permanent_house_number ?? '-' }}</p>
                    </div>
                </div>
            </div>

            {{-- SECTION 6: Mailing Address --}}
            <div class="mb-4">
                <h5 class="text-primary border-bottom pb-2 mb-3">
                    <i class="fas fa-envelope"></i> Mailing/Current Address
                </h5>
                @if($applicationform->same_as_permanent)
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> Same as Permanent Address
                    </div>
                @endif
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <strong>Province:</strong>
                        <p class="mb-0">{{ $applicationform->mailing_province ?? '-' }}</p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <strong>District:</strong>
                        <p class="mb-0">{{ $applicationform->mailing_district ?? '-' }}</p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <strong>Municipality:</strong>
                        <p class="mb-0">{{ $applicationform->mailing_municipality ?? '-' }}</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <strong>Ward No.:</strong>
                        <p class="mb-0">{{ $applicationform->mailing_ward ?? '-' }}</p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <strong>Tole:</strong>
                        <p class="mb-0">{{ $applicationform->mailing_tole ?? '-' }}</p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <strong>House Number:</strong>
                        <p class="mb-0">{{ $applicationform->mailing_house_number ?? '-' }}</p>
                    </div>
                </div>
            </div>

            {{-- SECTION 7: Educational Background --}}
            <div class="mb-4">
                <h5 class="text-primary border-bottom pb-2 mb-3">
                    <i class="fas fa-graduation-cap"></i> Educational Background
                </h5>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <strong>Highest Education Level:</strong>
                        <p class="mb-0">{{ $applicationform->education_level ?? '-' }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Field of Study:</strong>
                        <p class="mb-0">{{ $applicationform->field_of_study ?? '-' }}</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <strong>Institution Name:</strong>
                        <p class="mb-0">{{ $applicationform->institution_name ?? '-' }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Graduation Year:</strong>
                        <p class="mb-0">{{ $applicationform->graduation_year ?? '-' }}</p>
                    </div>
                </div>
            </div>

            {{-- SECTION 8: Work Experience --}}
            <div class="mb-4">
                <h5 class="text-primary border-bottom pb-2 mb-3">
                    <i class="fas fa-briefcase"></i> Work Experience
                </h5>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <strong>Has Work Experience:</strong>
                        <p class="mb-0">{{ ucfirst($applicationform->has_work_experience ?? '-') }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Years of Experience:</strong>
                        <p class="mb-0">{{ $applicationform->years_of_experience ?? '-' }} {{ $applicationform->years_of_experience ? 'years' : '' }}</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <strong>Previous Organization:</strong>
                        <p class="mb-0">{{ $applicationform->previous_organization ?? '-' }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Previous Position:</strong>
                        <p class="mb-0">{{ $applicationform->previous_position ?? '-' }}</p>
                    </div>
                </div>
            </div>

            {{-- SECTION 9: Uploaded Documents --}}
            <div class="mb-4">
                <h5 class="text-primary border-bottom pb-2 mb-3">
                    <i class="fas fa-file-upload"></i> Uploaded Documents
                </h5>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <strong>Passport Size Photo:</strong>
                        <p class="mb-0">
                            @if($applicationform->passport_size_photo)
                                <a href="{{ asset('storage/' . $applicationform->passport_size_photo) }}" target="_blank" class="btn btn-sm bg-light">
                                    <i class="fas fa-image"></i> View Photo
                                </a>
                            @else
                                <span class="text-muted">Not uploaded</span>
                            @endif
                        </p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Citizenship/ID Document:</strong>
                        <p class="mb-0">
                            @if($applicationform->citizenship_id_document)
                                <a href="{{ asset('storage/' . $applicationform->citizenship_id_document) }}" target="_blank" class="btn btn-sm bg-light">
                                    <i class="fas fa-file-pdf"></i> View Document
                                </a>
                            @else
                                <span class="text-muted">Not uploaded</span>
                            @endif
                        </p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <strong>Resume/CV:</strong>
                        <p class="mb-0">
                            @if($applicationform->resume_cv)
                                <a href="{{ asset('storage/' . $applicationform->resume_cv) }}" target="_blank" class="btn btn-sm bg-light">
                                    <i class="fas fa-file-alt"></i> View Resume
                                </a>
                            @else
                                <span class="text-muted">Not uploaded</span>
                            @endif
                        </p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Educational Certificates:</strong>
                        <p class="mb-0">
                            @if($applicationform->educational_certificates)
                                @php
                                    $certificates = is_string($applicationform->educational_certificates) 
                                        ? json_decode($applicationform->educational_certificates, true) 
                                        : $applicationform->educational_certificates;
                                @endphp
                                @if(is_array($certificates) && count($certificates) > 0)
                                    @foreach($certificates as $index => $cert)
                                        <a href="{{ asset('storage/' . $cert) }}" target="_blank" class="btn btn-sm bg-light me-1 mb-1">
                                            <i class="fas fa-certificate"></i> Certificate {{ $index + 1 }}
                                        </a>
                                    @endforeach
                                @else
                                    <span class="text-muted">Not uploaded</span>
                                @endif
                            @else
                                <span class="text-muted">Not uploaded</span>
                            @endif
                        </p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <strong>NOC ID Card:</strong>
                        <p class="mb-0">
                            @if($applicationform->noc_id_card)
                                <a href="{{ asset('storage/' . $applicationform->noc_id_card) }}" target="_blank" class="btn btn-sm bg-light">
                                    <i class="fas fa-id-card"></i> View NOC ID
                                </a>
                            @else
                                <span class="text-muted">Not uploaded</span>
                            @endif
                        </p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Ethnic Certificate:</strong>
                        <p class="mb-0">
                            @if($applicationform->ethnic_certificate)
                                <a href="{{ asset('storage/' . $applicationform->ethnic_certificate) }}" target="_blank" class="btn btn-sm bg-light">
                                    <i class="fas fa-certificate"></i> View Certificate
                                </a>
                            @else
                                <span class="text-muted">Not uploaded</span>
                            @endif
                        </p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <strong>Disability Certificate:</strong>
                        <p class="mb-0">
                            @if($applicationform->disability_certificate)
                                <a href="{{ asset('storage/' . $applicationform->disability_certificate) }}" target="_blank" class="btn btn-sm bg-light">
                                    <i class="fas fa-file"></i> View Certificate
                                </a>
                            @else
                                <span class="text-muted">Not uploaded</span>
                            @endif
                        </p>
                    </div>
                </div>
            </div>

            {{-- SECTION 10: System Information --}}
            <div class="mb-4">
                <h5 class="text-primary border-bottom pb-2 mb-3">
                    <i class="fas fa-clock"></i> System Information
                </h5>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <strong>Registration ID:</strong>
                        <p class="mb-0">{{ $applicationform->id ?? '-' }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Application Status:</strong>
                        <p class="mb-0">
                            @if($applicationform->status == 'pending')
                                <span class="badge bg-warning text-dark">Pending</span>
                            @elseif($applicationform->status == 'approved')
                                <span class="badge bg-success">Approved</span>
                            @elseif($applicationform->status == 'rejected')
                                <span class="badge bg-danger">Rejected</span>
                            @else
                                <span class="badge bg-secondary">{{ ucfirst($applicationform->status ?? 'Unknown') }}</span>
                            @endif
                        </p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <strong>Terms Agreed:</strong>
                        <p class="mb-0">
                            @if($applicationform->terms_agree)
                                <span class="badge bg-success">Yes</span>
                            @else
                                <span class="badge bg-secondary">No</span>
                            @endif
                        </p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Same as Permanent Address:</strong>
                        <p class="mb-0">
                            @if($applicationform->same_as_permanent)
                                <span class="badge bg-success">Yes</span>
                            @else
                                <span class="badge bg-secondary">No</span>
                            @endif
                        </p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <strong>Created At:</strong>
                        <p class="mb-0">
                            @if($applicationform->created_at)
                                {{ is_string($applicationform->created_at) ? \Carbon\Carbon::parse($applicationform->created_at)->format('F d, Y h:i A') : $applicationform->created_at->format('F d, Y h:i A') }}
                            @else
                                -
                            @endif
                        </p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Last Updated:</strong>
                        <p class="mb-0">
                            @if($applicationform->updated_at)
                                {{ is_string($applicationform->updated_at) ? \Carbon\Carbon::parse($applicationform->updated_at)->format('F d, Y h:i A') : $applicationform->updated_at->format('F d, Y h:i A') }}
                            @else
                                -
                            @endif
                        </p>
                    </div>
                </div>
            </div>

            {{-- Action Buttons --}}
            <div class="d-flex justify-content-between mt-4 pt-3 border-top">
                <a href="{{ route('candidate.applications.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back to List
                </a>
                <div>
                    <a href="{{ route('candidate.applications.edit', $applicationform->id) }}" class="btn btn-warning me-2">
                        <i class="fas fa-edit"></i> Edit Registration
                    </a>
                    <form action="{{ route('candidate.applications.destroy', $applicationform->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this registration?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-trash"></i> Delete
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .card-body strong {
        color: #495057;
        font-weight: 600;
    }
    .card-body p {
        color: #212529;
        padding: 0.5rem;
        background-color: #f8f9fa;
        border-radius: 0.25rem;
    }
    .border-bottom {
        border-bottom: 2px solid #dee2e6 !important;
    }
</style>
@endpush
@endsection