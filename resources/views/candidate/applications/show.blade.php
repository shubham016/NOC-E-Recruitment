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
    <div class="card shadow-lg border-0">
        <div class="card-header bg-light text-dark text-center py-2">
            <h3 class="mb-0 fw-bold">NOC | View Application Form</h3>
        </div>

        <div class="card-body px-5 pt-3 pb-5">

            {{-- Clickable Tabs Navigation --}}
            <div class="step-tabs mb-5">
                <div class="d-flex flex-wrap justify-content-between border-bottom position-relative">
                    <div class="tab-item active" data-step="1">
                        <span class="tab-circle">1</span>
                        <span class="tab-label d-none d-md-inline">Personal</span>
                    </div>
                    <div class="tab-item" data-step="2">
                        <span class="tab-circle">2</span>
                        <span class="tab-label d-none d-md-inline">General</span>
                    </div>
                    <div class="tab-item" data-step="3">
                        <span class="tab-circle">3</span>
                        <span class="tab-label d-none d-md-inline">Address</span>
                    </div>
                    <div class="tab-item" data-step="4">
                        <span class="tab-circle">4</span>
                        <span class="tab-label d-none d-md-inline">Education</span>
                    </div>
                    <div class="tab-item" data-step="5">
                        <span class="tab-circle">5</span>
                        <span class="tab-label d-none d-md-inline">Experience</span>
                    </div>
                    <div class="tab-item" data-step="6">
                        <span class="tab-circle">6</span>
                        <span class="tab-label d-none d-md-inline">Documents</span>
                    </div>
                    <div class="tab-item" data-step="7">
                        <span class="tab-circle">7</span>
                        <span class="tab-label d-none d-md-inline">Payment</span>
                    </div>
                </div>
            </div>

            {{-- STEP 1: Personal Info --}}
            <div class="step active" id="step1">
                <h5 class="mb-4 text-dark">Step 1 — Personal Information</h5>

                <div class="row mb-3">
                    <div class="col-md-6 mb-3">
                        <strong>Full Name (English):</strong>
                        <p class="mb-0">{{ $applicationform->name_english ?? '-' }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Full Name (Nepali):</strong>
                        <p class="mb-0">{{ $applicationform->name_nepali ?? '-' }}</p>
                    </div>
                </div>

                <div class="row mb-3">
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
                    <div class="col-md-3 mb-3">
                        <strong>Email:</strong>
                        <p class="mb-0">{{ $applicationform->email ?? '-' }}</p>
                    </div>
                    <div class="col-md-3 mb-3">
                        <strong>Phone Number:</strong>
                        <p class="mb-0">{{ $applicationform->phone ?? '-' }}</p>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4 mb-3">
                        <strong>Advertisement Number:</strong>
                        <p class="mb-0">{{ $applicationform->advertisement_no ?? '-' }}</p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <strong>Applying Position:</strong>
                        <p class="mb-0">{{ $applicationform->applying_position ?? '-' }}</p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <strong>Department:</strong>
                        <p class="mb-0">{{ $applicationform->department ?? '-' }}</p>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4 mb-3">
                        <strong>Age:</strong>
                        <p class="mb-0">{{ $applicationform->age ?? '-' }} {{ $applicationform->age ? 'years' : '' }}</p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <strong>Alternate Phone Number:</strong>
                        <p class="mb-0">{{ $applicationform->alternate_phone_number ?? '-' }}</p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <strong>Gender:</strong>
                        <p class="mb-0">{{ ucfirst($applicationform->gender ?? '-') }}</p>
                    </div>
                </div>

                <div class="row mb-3">
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

                <div class="row mb-3">
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

                <div class="row mb-3">
                    <div class="col-md-4 mb-3">
                        <strong>Father Name (बुबाको नाम):</strong>
                        <p class="mb-0">{{ $applicationform->father_name_english ?? '-' }}</p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <strong>Mother Name (आमाको नाम):</strong>
                        <p class="mb-0">{{ $applicationform->mother_name_english ?? '-' }}</p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <strong>Grandfather Name (हजुरबुबाको नाम):</strong>
                        <p class="mb-0">{{ $applicationform->grandfather_name_english ?? '-' }}</p>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4 mb-3">
                        <strong>Father's Qualification (बुबाको योग्यता):</strong>
                        <p class="mb-0">{{ $applicationform->father_qualification ?? '-' }}</p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <strong>Mother's Qualification (आमाको योग्यता):</strong>
                        <p class="mb-0">{{ $applicationform->mother_qualification ?? '-' }}</p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <strong>Parent's Occupation:</strong>
                        <p class="mb-0">{{ $applicationform->parent_occupation ?? '-' }}</p>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4 mb-3">
                        <strong>Blood Group:</strong>
                        <p class="mb-0">{{ $applicationform->blood_group ?? '-' }}</p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <strong>Nationality:</strong>
                        <p class="mb-0">{{ $applicationform->nationality ?? '-' }}</p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <strong>Are you NOC Employee?:</strong>
                        <p class="mb-0">{{ ucfirst($applicationform->noc_employee ?? '-') }}</p>
                    </div>
                </div>

                <div class="row mb-3">
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
                </div>

                <div class="d-flex justify-content-end">
                    <button type="button" class="btn btn-light next-btn">Next</button>
                </div>
            </div>

            {{-- STEP 2: General Info --}}
            <div class="step d-none" id="step2">
                <h5 class="mb-4 text-dark">Step 2 — General Information</h5>

                <div class="row mb-3">
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

                <div class="row mb-3">
                    <div class="col-md-6 mb-3">
                        <strong>Mother Tongue:</strong>
                        <p class="mb-0">{{ $applicationform->mother_tongue ?? '-' }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Employment Status:</strong>
                        <p class="mb-0">{{ ucfirst($applicationform->employment_status ?? '-') }}</p>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4 mb-3">
                        <strong>Physical Disability:</strong>
                        <p class="mb-0">{{ ucfirst($applicationform->physical_disability ?? '-') }}</p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <strong>Disability Details:</strong>
                        <p class="mb-0">{{ $applicationform->disability_other ?? '-' }}</p>
                    </div>
                    <div class="col-md-4 mb-3">
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

                <div class="row mb-3">
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

                <div class="d-flex justify-content-between">
                    <button type="button" class="btn btn-secondary prev-btn">Back</button>
                    <button type="button" class="btn btn-light next-btn">Next</button>
                </div>
            </div>

            {{-- STEP 3: Address Info --}}
            <div class="step d-none" id="step3">
                <h5 class="mb-4 text-dark">Step 3 — Address Information</h5>

                <h6 class="mb-3 text-secondary">Permanent Address</h6>
                <div class="row mb-3">
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

                <div class="row mb-3">
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

                <h6 class="mb-3 text-secondary mt-4">Mailing/Current Address</h6>
                @if($applicationform->same_as_permanent)
                    <div class="alert alert-info mb-3">
                        <i class="fas fa-info-circle"></i> Same as Permanent Address
                    </div>
                @endif

                <div class="row mb-3">
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

                <div class="row mb-3">
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

                <div class="d-flex justify-content-between">
                    <button type="button" class="btn btn-secondary prev-btn">Back</button>
                    <button type="button" class="btn btn-light next-btn">Next</button>
                </div>
            </div>

            {{-- STEP 4: Education --}}
            <div class="step d-none" id="step4">
                <h5 class="mb-4 text-dark">Step 4 — Educational Background</h5>

                <div class="row mb-3">
                    <div class="col-md-6 mb-3">
                        <strong>Highest Education Level:</strong>
                        <p class="mb-0">{{ $applicationform->education_level ?? '-' }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Field of Study:</strong>
                        <p class="mb-0">{{ $applicationform->field_of_study ?? '-' }}</p>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6 mb-3">
                        <strong>Institution Name:</strong>
                        <p class="mb-0">{{ $applicationform->institution_name ?? '-' }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Graduation Year:</strong>
                        <p class="mb-0">{{ $applicationform->graduation_year ?? '-' }}</p>
                    </div>
                </div>

                <div class="d-flex justify-content-between">
                    <button type="button" class="btn btn-secondary prev-btn">Back</button>
                    <button type="button" class="btn btn-light next-btn">Next</button>
                </div>
            </div>

           {{-- STEP 5: Experience --}}
            <div class="step d-none" id="step5">
                <h5 class="mb-4 text-dark">Step 5 — Work Experience</h5>

                <div class="mb-3">
                    <strong>Has Work Experience:</strong>
                    <p class="mb-0">{{ ucfirst($applicationform->has_work_experience ?? '-') }}</p>
                </div>

                @for ($i = 1; $i <= 3; $i++)
                    @php
                        $org = "exp{$i}_organization";
                        $pos = "exp{$i}_position";
                        $start = "exp{$i}_start_date";
                        $end = "exp{$i}_end_date";
                        $years = "exp{$i}_years";
                        $doc = "exp{$i}_document";
                    @endphp

                    @if(!empty($applicationform->$org) || !empty($applicationform->$pos))
                        <div class="border rounded p-3 mb-3">
                            <h6 class="text-primary">Experience {{ $i }}</h6>

                            <div class="row">
                                <div class="col-md-6">
                                    <strong>Organization:</strong>
                                    <p>{{ $applicationform->$org ?? '-' }}</p>
                                </div>

                                <div class="col-md-6">
                                    <strong>Position:</strong>
                                    <p>{{ $applicationform->$pos ?? '-' }}</p>
                                </div>

                                <div class="col-md-6">
                                    <strong>Start Date:</strong>
                                    <p>{{ $applicationform->$start ?? '-' }}</p>
                                </div>

                                <div class="col-md-6">
                                    <strong>End Date:</strong>
                                    <p>{{ $applicationform->$end ?? '-' }}</p>
                                </div>

                                <div class="col-md-6">
                                    <strong>Years:</strong>
                                    <p>{{ $applicationform->$years ?? '-' }}</p>
                                </div>

                                <div class="col-md-6">
                                    <strong>Document:</strong>
                                    <p>
                                        @if(!empty($applicationform->$doc))
                                            <iframe 
                                                src="{{ asset('storage/' . $applicationform->$doc) }}" 
                                                width="100%" 
                                                height="250px"
                                                style="border:1px solid #ccc;">
                                            </iframe>
                                        @else
                                            -
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif
                @endfor

                <div class="d-flex justify-content-between">
                    <button type="button" class="btn btn-secondary prev-btn">Back</button>
                    <button type="button" class="btn btn-light next-btn">Next</button>
                </div>
            </div>

            {{-- STEP 6: Documents --}}
            <div class="step d-none" id="step6">
                <h5 class="mb-4 text-dark">Step 6 — Uploaded Documents</h5>

                <div class="row mb-3">
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
                                    <i class="fas fa-file-alt"></i> View Document
                                </a>
                            @else
                                <span class="text-muted">Not uploaded</span>
                            @endif
                        </p>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6 mb-3">
                        <strong>Character Certificate:</strong>
                        <p class="mb-0">
                            @if($applicationform->character)
                                <a href="{{ asset('storage/' . $applicationform->character) }}" target="_blank" class="btn btn-sm bg-light">
                                    <i class="fas fa-file-alt"></i> View Certificate
                                </a>
                            @else
                                <span class="text-muted">Not uploaded</span>
                            @endif
                        </p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Equivalency Certificate:</strong>
                        <p class="mb-0">
                            @if($applicationform->equivalent)
                                <a href="{{ asset('storage/' . $applicationform->equivalent) }}" target="_blank" class="btn btn-sm bg-light">
                                    <i class="fas fa-file-alt"></i> View Certificate
                                </a>
                            @else
                                <span class="text-muted">Not uploaded</span>
                            @endif
                        </p>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6 mb-3">
                        <strong>Work Experience Certificate:</strong>
                        <p class="mb-0">
                            @if($applicationform->work_experience)
                                <a href="{{ asset('storage/' . $applicationform->work_experience) }}" target="_blank" class="btn btn-sm bg-light">
                                    <i class="fas fa-file-alt"></i> View Certificate
                                </a>
                            @else
                                <span class="text-muted">Not uploaded</span>
                            @endif
                        </p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Transcript Certificate:</strong>
                        <p class="mb-0">
                            @if(!empty($applicationform->transcript))
                                <a href="{{ asset('storage/' . $applicationform->transcript) }}" target="_blank" class="btn btn-sm bg-light">
                                    <i class="fas fa-file-alt"></i> View Certificate
                                </a>
                            @else
                                <span class="text-muted">Not uploaded</span>
                            @endif
                        </p>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6 mb-3">
                        <strong>Signature:</strong>
                        <p class="mb-0">
                            @if($applicationform->signature)
                                <a href="{{ asset('storage/' . $applicationform->signature) }}" target="_blank" class="btn btn-sm bg-light">
                                    <i class="fas fa-file"></i> View Signature
                                </a>
                            @else
                                <span class="text-muted">Not uploaded</span>
                            @endif
                        </p>
                    </div>
                </div>

                <div class="d-flex justify-content-between">
                    <button type="button" class="btn btn-secondary prev-btn">Back</button>
                    <button type="button" class="btn btn-light next-btn">Next</button>
                </div>
            </div>

            {{-- STEP 7: Payment Status --}}
            <div class="step d-none" id="step7">
                <h5 class="mb-4 text-dark">Step 7 — Payment Details & Application Status</h5>

                <div class="row mb-3">
                    <div class="col-md-6 mb-3">
                        <strong>Payment ID:</strong>
                        <p class="mb-0">{{ $applicationform->payment->id ?? '-' }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Gateway:</strong>
                        <p class="mb-0">{{ $applicationform->payment->gateway ?? '-' }}</p>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6 mb-3">
                        <strong>Amount:</strong>
                        <p class="mb-0">{{ $applicationform->payment->amount ?? '-' }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Transaction ID:</strong>
                        <p class="mb-0">{{ $applicationform->payment->transaction_id ?? '-' }}</p>
                    </div>
                </div>

                <hr class="my-4">

                <h6 class="mb-3 text-secondary">Application Status</h6>

                <div class="row mb-3">
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

                <div class="row mb-3">
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

                <div class="row mb-3">
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

                <div class="d-flex justify-content-between">
                    <button type="button" class="btn btn-secondary prev-btn">Back</button>
                    <a href="{{ route('candidate.applications.index') }}" class="btn btn-danger">
                        <i class="fas fa-arrow-left"></i> Back to Applications
                    </a>
                </div>
            </div>

        </div>
    </div>
</div>

@push('styles')
<style>
    /* ===== CLICKABLE TABS STYLING ===== */
    .step-tabs {
        position: relative;
        margin-bottom: 2.5rem;
    }

    .step-tabs .d-flex {
        gap: 10px;
        overflow-x: auto;
        padding-bottom: 10px;
    }

    .tab-item {
        flex: 1;
        text-align: center;
        padding: 15px 8px;
        cursor: pointer;
        transition: all 0.3s ease;
        position: relative;
        min-width: 120px;
        user-select: none;
    }

    .tab-circle {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 40px;
        height: 40px;
        background: #e9ecef;
        color: #6c757d;
        border-radius: 50%;
        font-weight: bold;
        font-size: 1.1rem;
        transition: all 0.3s ease;
        margin-bottom: 8px;
    }

    .tab-label {
        font-size: 0.9rem;
        color: #6c757d;
        display: block;
        transition: color 0.3s ease;
    }

    /* Active / Completed State */
    .tab-item.active .tab-circle,
    .tab-item.completed .tab-circle {
        background: #000000;
        color: white;
    }

    .tab-item.active .tab-label,
    .tab-item.completed .tab-label {
        color: #000000;
        font-weight: 600;
    }

    /* Hover */
    .tab-item:hover .tab-circle {
        background: #000000;
        color: white;
    }

    .tab-item:hover .tab-label {
        color: #000000;
    }

    /* Step Visibility */
    .step {
        transition: opacity 0.4s ease;
    }

    .step.active {
        opacity: 1;
    }

    .step.d-none {
        opacity: 0;
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        pointer-events: none;
        visibility: hidden;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .tab-label {
            font-size: 0.8rem;
        }

        .tab-item {
            padding: 12px 4px;
        }

        .tab-circle {
            width: 35px;
            height: 35px;
            font-size: 1rem;
        }
    }

    /* Card Body Styling */
    .card-body strong {
        color: #495057;
        font-weight: 600;
    }

    .card-body p {
        color: #212529;
        padding: 0.5rem;
        background-color: #f8f9fa;
        border-radius: 0.25rem;
        margin-bottom: 0 !important;
    }

    .border-bottom {
        border-bottom: 2px solid #dee2e6 !important;
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        let currentStep = 1;
        const totalSteps = 7;

        // Update Tabs & Progress
        function updateTabsAndProgress() {
            document.querySelectorAll('.tab-item').forEach((tab, index) => {
                const stepNum = index + 1;
                tab.classList.remove('active', 'completed');
                if (stepNum < currentStep) {
                    tab.classList.add('completed');
                } else if (stepNum === currentStep) {
                    tab.classList.add('active');
                }
            });
        }

        // Show Specific Step
        function showStep(step) {
            document.querySelectorAll('.step').forEach(s => s.classList.add('d-none'));
            const el = document.getElementById('step' + step);
            if (el) {
                el.classList.remove('d-none');
                el.classList.add('active');
            }
            currentStep = step;
            updateTabsAndProgress();
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        // Clickable Tabs
        document.querySelectorAll('.tab-item').forEach(tab => {
            tab.addEventListener('click', () => {
                const targetStep = parseInt(tab.getAttribute('data-step'));
                showStep(targetStep);
            });
        });

        // Next Button
        document.querySelectorAll('.next-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                if (currentStep < totalSteps) {
                    showStep(currentStep + 1);
                }
            });
        });

        // Previous Button
        document.querySelectorAll('.prev-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                if (currentStep > 1) {
                    showStep(currentStep - 1);
                }
            });
        });

        // Initialize
        showStep(1);
        console.log('✓ View form initialized with circular tabs navigation');
    });
</script>
@endpush

@endsection