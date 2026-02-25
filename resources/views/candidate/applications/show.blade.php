@extends('layouts.app')

@section('title', 'View Registration')

@section('content')
@section('sidebar-menu')
    <a href="{{ route('candidate.dashboard') }}" class="sidebar-menu-item">
        <i class="bi bi-speedometer2"></i>
        <span>Dashboard</span>
    </a>
    <a href="{{ route('candidate.jobs.index') }}" class="sidebar-menu-item">
        <i class="bi bi-search"></i>
        <span>Vacancy</span>
    </a>
    <a href="{{ route('candidate.applications.index') }}" class="sidebar-menu-item active">
        <i class="bi bi-file-earmark-text"></i>
        <span>My Applications</span>
    </a>
    <a href="{{ route('candidate.viewresult') }}" class="sidebar-menu-item">
        <i class="bi bi-file-earmark-check"></i>
        <span>View Result</span>
    </a>
    <a href="{{ route('candidate.admit-card') }}" class="sidebar-menu-item">
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
                <a href="{{ route('candidate.applications.index') }}" class="btn btn-light btn-sm">
                    <i class="fas fa-arrow-left"></i> Back
                </a>
                @if($application->canEdit())
                    <a href="{{ route('candidate.applications.edit', $application->id) }}" class="btn btn-warning btn-sm ms-1">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                @endif
                @if($application->status === 'pending' && (!$application->payment || !$application->payment->isCompleted()))
                    <a href="{{ route('candidate.payment.esewa', $application->id) }}" class="btn btn-success btn-sm ms-1">
                        <i class="fas fa-credit-card"></i> Pay Now
                    </a>
                @endif
            </div>
        </div>

        <div class="card-body">

            {{-- Status Alert --}}
            <div class="mb-4">
                <span class="badge bg-{{ $application->statusColor }} fs-6 px-3 py-2">
                    {{ $application->statusLabel }}
                </span>
                @if($application->payment && $application->payment->isCompleted())
                    <span class="badge bg-success fs-6 px-3 py-2 ms-2">
                        <i class="fas fa-check-circle"></i> Payment Verified
                    </span>
                @endif
            </div>

            {{-- SECTION 1: Personal Information --}}
            <div class="mb-4">
                <h5 class="text-primary border-bottom pb-2 mb-3">
                    <i class="fas fa-user"></i> Personal Information
                </h5>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <strong>Full Name (English):</strong>
                        <p class="mb-0">{{ $application->name_english ?? '-' }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Full Name (Nepali):</strong>
                        <p class="mb-0">{{ $application->name_nepali ?? '-' }}</p>
                    </div>
                    <div class="col-md-3 mb-3">
                        <strong>Birth Date (A.D):</strong>
                        <p class="mb-0">
                            @if($application->birth_date_ad)
                                {{ $application->birth_date_ad->format('F d, Y') }}
                            @else
                                -
                            @endif
                        </p>
                    </div>
                    <div class="col-md-3 mb-3">
                        <strong>Birth Date (B.S):</strong>
                        <p class="mb-0">{{ $application->birth_date_bs ?? '-' }}</p>
                    </div>
                    <div class="col-md-3 mb-3">
                        <strong>Age:</strong>
                        <p class="mb-0">{{ $application->age ? $application->age . ' years' : '-' }}</p>
                    </div>
                    <div class="col-md-3 mb-3">
                        <strong>Gender:</strong>
                        <p class="mb-0">{{ ucfirst($application->gender ?? '-') }}</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <strong>Advertisement Number:</strong>
                        <p class="mb-0">{{ $application->advertisement_no ?? ($application->jobPosting->advertisement_no ?? '-') }}</p>
                    </div>
                    <div class="col-md-3 mb-3">
                        <strong>Applying Position:</strong>
                        <p class="mb-0">{{ $application->applying_position ?? ($application->jobPosting->title ?? '-') }}</p>
                    </div>
                    <div class="col-md-3 mb-3">
                        <strong>Department:</strong>
                        <p class="mb-0">{{ $application->department ?? ($application->jobPosting->department ?? '-') }}</p>
                    </div>
                    <div class="col-md-3 mb-3">
                        <strong>Phone Number:</strong>
                        <p class="mb-0">{{ $application->phone ?? '-' }}</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <strong>Alternate Phone Number:</strong>
                        <p class="mb-0">{{ $application->alternate_phone_number ?? '-' }}</p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <strong>Email:</strong>
                        <p class="mb-0">{{ $application->email ?? ($application->candidate->email ?? '-') }}</p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <strong>Marital Status:</strong>
                        <p class="mb-0">{{ ucfirst($application->marital_status ?? '-') }}</p>
                    </div>
                </div>
                @if($application->marital_status === 'Married')
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <strong>Spouse Name:</strong>
                        <p class="mb-0">{{ $application->spouse_name_english ?? '-' }}</p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <strong>Spouse Nationality:</strong>
                        <p class="mb-0">{{ $application->spouse_nationality ?? '-' }}</p>
                    </div>
                </div>
                @endif
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <strong>Blood Group:</strong>
                        <p class="mb-0">{{ $application->blood_group ?? '-' }}</p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <strong>Nationality:</strong>
                        <p class="mb-0">{{ $application->nationality ?? '-' }}</p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <strong>NOC Employee:</strong>
                        <p class="mb-0">{{ ucfirst($application->noc_employee ?? '-') }}</p>
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
                        <p class="mb-0">{{ $application->citizenship_number ?? '-' }}</p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <strong>Citizenship Issue Date (B.S):</strong>
                        <p class="mb-0">{{ $application->citizenship_issue_date_bs ?? '-' }}</p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <strong>Citizenship Issue District:</strong>
                        <p class="mb-0">{{ $application->citizenship_issue_district ?? '-' }}</p>
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
                        <p class="mb-0">{{ $application->father_name_english ?? '-' }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Father's Qualification:</strong>
                        <p class="mb-0">{{ $application->father_qualification ?? '-' }}</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <strong>Mother Name:</strong>
                        <p class="mb-0">{{ $application->mother_name_english ?? '-' }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Mother's Qualification:</strong>
                        <p class="mb-0">{{ $application->mother_qualification ?? '-' }}</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <strong>Parent's Occupation:</strong>
                        <p class="mb-0">{{ $application->parent_occupation ?? '-' }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Grandfather Name:</strong>
                        <p class="mb-0">{{ $application->grandfather_name_english ?? '-' }}</p>
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
                            {{ $application->religion ?? '-' }}
                            @if($application->religion === 'Other' && $application->religion_other)
                                ({{ $application->religion_other }})
                            @endif
                        </p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <strong>Community:</strong>
                        <p class="mb-0">
                            {{ $application->community ?? '-' }}
                            @if($application->community === 'Other' && $application->community_other)
                                ({{ $application->community_other }})
                            @endif
                        </p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <strong>Ethnic Group:</strong>
                        <p class="mb-0">
                            {{ $application->ethnic_group ?? '-' }}
                            @if($application->ethnic_group === 'Other' && $application->ethnic_group_other)
                                ({{ $application->ethnic_group_other }})
                            @endif
                        </p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <strong>Mother Tongue:</strong>
                        <p class="mb-0">{{ $application->mother_tongue ?? '-' }}</p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <strong>Employment Status:</strong>
                        <p class="mb-0">{{ ucfirst($application->employment_status ?? '-') }}</p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <strong>Physical Disability:</strong>
                        <p class="mb-0">{{ ucfirst($application->physical_disability ?? '-') }}</p>
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
                        <p class="mb-0">{{ $application->permanent_province ?? '-' }}</p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <strong>District:</strong>
                        <p class="mb-0">{{ $application->permanent_district ?? '-' }}</p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <strong>Municipality:</strong>
                        <p class="mb-0">{{ $application->permanent_municipality ?? '-' }}</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <strong>Ward No.:</strong>
                        <p class="mb-0">{{ $application->permanent_ward ?? '-' }}</p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <strong>Tole:</strong>
                        <p class="mb-0">{{ $application->permanent_tole ?? '-' }}</p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <strong>House Number:</strong>
                        <p class="mb-0">{{ $application->permanent_house_number ?? '-' }}</p>
                    </div>
                </div>
            </div>

            {{-- SECTION 6: Mailing Address --}}
            <div class="mb-4">
                <h5 class="text-primary border-bottom pb-2 mb-3">
                    <i class="fas fa-envelope"></i> Mailing/Current Address
                </h5>
                @if($application->same_as_permanent)
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> Same as Permanent Address
                    </div>
                @else
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <strong>Province:</strong>
                        <p class="mb-0">{{ $application->mailing_province ?? '-' }}</p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <strong>District:</strong>
                        <p class="mb-0">{{ $application->mailing_district ?? '-' }}</p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <strong>Municipality:</strong>
                        <p class="mb-0">{{ $application->mailing_municipality ?? '-' }}</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <strong>Ward No.:</strong>
                        <p class="mb-0">{{ $application->mailing_ward ?? '-' }}</p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <strong>Tole:</strong>
                        <p class="mb-0">{{ $application->mailing_tole ?? '-' }}</p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <strong>House Number:</strong>
                        <p class="mb-0">{{ $application->mailing_house_number ?? '-' }}</p>
                    </div>
                </div>
                @endif
            </div>

            {{-- SECTION 7: Educational Background --}}
            <div class="mb-4">
                <h5 class="text-primary border-bottom pb-2 mb-3">
                    <i class="fas fa-graduation-cap"></i> Educational Background
                </h5>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <strong>Highest Education Level:</strong>
                        <p class="mb-0">{{ $application->education_level ?? '-' }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Field of Study:</strong>
                        <p class="mb-0">{{ $application->field_of_study ?? '-' }}</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <strong>Institution Name:</strong>
                        <p class="mb-0">{{ $application->institution_name ?? '-' }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Graduation Year:</strong>
                        <p class="mb-0">{{ $application->graduation_year ?? '-' }}</p>
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
                        <p class="mb-0">{{ ucfirst($application->has_work_experience ?? '-') }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Years of Experience:</strong>
                        <p class="mb-0">{{ $application->years_of_experience !== null ? $application->years_of_experience . ' years' : '-' }}</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <strong>Previous Organization:</strong>
                        <p class="mb-0">{{ $application->previous_organization ?? '-' }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Previous Position:</strong>
                        <p class="mb-0">{{ $application->previous_position ?? '-' }}</p>
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
                            @if($application->passport_photo)
                                <a href="{{ asset('storage/' . $application->passport_photo) }}" target="_blank" class="btn btn-sm bg-light">
                                    <i class="fas fa-image"></i> View Photo
                                </a>
                            @else
                                <span class="text-muted">Not uploaded</span>
                            @endif
                        </p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Citizenship Certificate:</strong>
                        <p class="mb-0">
                            @if($application->citizenship_certificate)
                                <a href="{{ asset('storage/' . $application->citizenship_certificate) }}" target="_blank" class="btn btn-sm bg-light">
                                    <i class="fas fa-file-alt"></i> View Document
                                </a>
                            @else
                                <span class="text-muted">Not uploaded</span>
                            @endif
                        </p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <strong>Transcript/Educational Certificates:</strong>
                        <p class="mb-0">
                            @if($application->educational_certificates)
                                <a href="{{ asset('storage/' . $application->educational_certificates) }}" target="_blank" class="btn btn-sm bg-light">
                                    <i class="fas fa-file-alt"></i> View Certificate
                                </a>
                            @else
                                <span class="text-muted">Not uploaded</span>
                            @endif
                        </p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Character Certificate:</strong>
                        <p class="mb-0">
                            @if($application->character_certificate)
                                <a href="{{ asset('storage/' . $application->character_certificate) }}" target="_blank" class="btn btn-sm bg-light">
                                    <i class="fas fa-file-alt"></i> View Certificate
                                </a>
                            @else
                                <span class="text-muted">Not uploaded</span>
                            @endif
                        </p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <strong>Equivalency Certificate:</strong>
                        <p class="mb-0">
                            @if($application->equivalency_certificate)
                                <a href="{{ asset('storage/' . $application->equivalency_certificate) }}" target="_blank" class="btn btn-sm bg-light">
                                    <i class="fas fa-file-alt"></i> View Certificate
                                </a>
                            @else
                                <span class="text-muted">Not uploaded</span>
                            @endif
                        </p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Work Experience Certificate:</strong>
                        <p class="mb-0">
                            @if($application->experience_certificates)
                                <a href="{{ asset('storage/' . $application->experience_certificates) }}" target="_blank" class="btn btn-sm bg-light">
                                    <i class="fas fa-file-alt"></i> View Certificate
                                </a>
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
                            @if($application->noc_id_card)
                                <a href="{{ asset('storage/' . $application->noc_id_card) }}" target="_blank" class="btn btn-sm bg-light">
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
                            @if($application->ethnic_certificate)
                                <a href="{{ asset('storage/' . $application->ethnic_certificate) }}" target="_blank" class="btn btn-sm bg-light">
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
                            @if($application->disability_certificate)
                                <a href="{{ asset('storage/' . $application->disability_certificate) }}" target="_blank" class="btn btn-sm bg-light">
                                    <i class="fas fa-file"></i> View Certificate
                                </a>
                            @else
                                <span class="text-muted">Not uploaded</span>
                            @endif
                        </p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Signature:</strong>
                        <p class="mb-0">
                            @if($application->signature)
                                <a href="{{ asset('storage/' . $application->signature) }}" target="_blank" class="btn btn-sm bg-light">
                                    <i class="fas fa-file"></i> View Signature
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
                        <p class="mb-0">{{ $application->id ?? '-' }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Application Status:</strong>
                        <p class="mb-0">
                            <span class="badge bg-{{ $application->statusColor }}">{{ $application->statusLabel }}</span>
                        </p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <strong>Terms Agreed:</strong>
                        <p class="mb-0">
                            @if($application->terms_agree)
                                <span class="badge bg-success">Yes</span>
                            @else
                                <span class="badge bg-secondary">No</span>
                            @endif
                        </p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Same as Permanent Address:</strong>
                        <p class="mb-0">
                            @if($application->same_as_permanent)
                                <span class="badge bg-success">Yes</span>
                            @else
                                <span class="badge bg-secondary">No</span>
                            @endif
                        </p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <strong>Submitted At:</strong>
                        <p class="mb-0">
                            {{ $application->submitted_at ? $application->submitted_at->format('F d, Y h:i A') : '-' }}
                        </p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Last Updated:</strong>
                        <p class="mb-0">
                            {{ $application->updated_at ? $application->updated_at->format('F d, Y h:i A') : '-' }}
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
                    @if($application->canEdit())
                        <a href="{{ route('candidate.applications.edit', $application->id) }}" class="btn btn-warning me-2">
                            <i class="fas fa-edit"></i> Edit Application
                        </a>
                    @endif
                    @if($application->status === 'pending' && (!$application->payment || !$application->payment->isCompleted()))
                        <a href="{{ route('candidate.payment.esewa', $application->id) }}" class="btn btn-success">
                            <i class="fas fa-credit-card"></i> Proceed to Payment
                        </a>
                    @endif
                    @if($application->canGenerateAdmitCard())
                        <a href="{{ route('candidate.admit-card.show', $application->id) }}" class="btn btn-primary">
                            <i class="fas fa-id-card"></i> View Admit Card
                        </a>
                    @endif
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
</style>
@endpush
@endsection
