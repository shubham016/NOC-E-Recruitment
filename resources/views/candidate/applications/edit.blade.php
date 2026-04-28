@extends('layouts.app')
@section('title', 'Edit Application Form')
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

{{-- ══════════════════════════════════════════════════════
     Nepali Date Picker CSS (load once, globally)
     ══════════════════════════════════════════════════════ --}}
@push('styles')
<link rel="stylesheet" href="https://nepalidatepicker.sajanmaharjan.com.np/v5/nepali.datepicker/css/nepali.datepicker.v5.0.6.min.css">
<style>
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
    .tab-item:hover .tab-circle {
        background: #000000;
        color: white;
    }
    .tab-item:hover .tab-label {
        color: #000000;
    }
    .progress-line {
        position: absolute;
        bottom: -1px;
        left: 0;
        height: 4px;
        background: #ff0000;
        width: 14.28%;
        transition: width 0.4s ease;
        z-index: 1;
    }
    @media (max-width: 768px) {
        .tab-label { font-size: 0.8rem; }
        .tab-item { padding: 12px 4px; }
        .tab-circle { width: 35px; height: 35px; font-size: 1rem; }
    }
    .step { transition: opacity 0.4s ease; }
    .step.active { opacity: 1; }
    .step.d-none {
        opacity: 0;
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        pointer-events: none;
        visibility: hidden;
    }
    .is-invalid { border-color: #dc3545 !important; }
    .invalid-feedback { color: #dc3545; font-size: 0.875rem; margin-top: 0.25rem; display: block; }
    .payment-box {
        border: 1px solid #ddd;
        padding: 15px;
        border-radius: 10px;
        cursor: pointer;
        transition: 0.3s;
        height: 160px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }
    .payment-box:hover { background: #f5f5f5; }
    .payment-logo {
        width: 150px;
        height: 60px;
        object-fit: contain;
        margin-bottom: 10px;
    }
    .ndp-wrapper { position: relative; }
    .ndp-wrapper input { padding-right: 2.25rem; }
    .ndp-icon {
        position: absolute; right: .65rem; top: 50%; transform: translateY(-50%);
        color: #bbb; font-size: .9rem; pointer-events: none; z-index: 2;
    }
    .ndp-wrapper:focus-within .ndp-icon { color: #1a2a4a; }
</style>
@endpush

<div class="container my-2">
    <div class="card shadow-lg border-0">
        <div class="card-header bg-light text-dark text-center py-2">
            <h3 class="mb-0 fw-bold">NOC | Edit Application Form</h3>
        </div>
        <div class="card-body px-5 pt-3 pb-5">

            {{-- Validation Errors --}}
            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show">
                    <strong>There were some problems with your input:</strong>
                    <ul class="mb-0 mt-2">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Clickable Tabs Navigation --}}
            <div class="step-tabs mb-5">
                <div class="d-flex justify-content-evenly border-bottom position-relative">
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
                        <span class="tab-label d-none d-md-inline">Preview</span>
                    </div>
                    <div class="tab-item" data-step="8">
                        <span class="tab-circle">8</span>
                        <span class="tab-label d-none d-md-inline">Payment</span>
                    </div>
                </div>
            </div>

            <form action="{{ route('candidate.applications.update', $applicationform->id) }}" method="POST" enctype="multipart/form-data" id="applicationform">
                @csrf
                @method('PUT')
                <input type="hidden" name="draft_id" id="draft_id" value="{{ $applicationform->id ?? '' }}">

<!-- STEP 1: Personal Info -->
                <div class="step" id="step1">
                    <h5 class="mb-4 text-dark">Step 1 — Personal Information</h5>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="name_english" class="form-label">Full Name (English) <span class="text-danger">*</span> <small>(पुरा नाम अंग्रेजी)</small></label>
                            <input type="text" name="name_english" id="name_english" class="form-control" value="{{ old('name_english', $applicationform->name_english) }}" required>
                        </div>
                        <div class="col-md-6">
                            <label for="name_nepali" class="form-label">Full Name (Nepali) <span class="text-danger">*</span> <small>(पुरा नाम नेपाली)</small></label>
                            <input
                                type="text"
                                name="name_nepali"
                                id="name_nepali"
                                class="form-control"
                                placeholder="नेपालीमा नाम लेख्नुहोस्"
                                value="{{ old('name_nepali', $applicationform->name_nepali) }}"
                                required
                                autocomplete="off"
                                inputmode="text"
                                style="ime-mode: active;"
                            >
                            <small class="text-muted">Only Devanagari (नेपाली) characters allowed</small>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <label for="birth_date_ad" class="form-label">Birth Date (A.D) <span class="text-danger">*</span> <small>(जन्म मिति A.D)</small></label>
                            <input type="date" name="birth_date_ad" id="birth_date_ad" class="form-control"
                                value="{{ old('birth_date_ad', $applicationform->birth_date_ad ? \Carbon\Carbon::parse($applicationform->birth_date_ad)->format('Y-m-d') : '') }}" required>
                        </div>
                        <div class="col-md-3">
                            <label for="birth_date_bs" class="form-label">Birth Date (B.S) <span class="text-danger">*</span> <small>(जन्म मिति B.S)</small></label>
                            <div class="ndp-wrapper">
                                <input type="text" name="birth_date_bs" id="birth_date_bs" class="form-control" placeholder="YYYY-MM-DD" autocomplete="off"
                                    value="{{ old('birth_date_bs', $applicationform->birth_date_bs) }}" required>
                                <span class="ndp-icon"><i class="bi bi-calendar-event"></i></span>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="text" name="email" id="email" class="form-control"
                                value="{{ old('email', $applicationform->email) }}" required>
                        </div>
                        <div class="col-md-3">
                            <label for="phone" class="form-label">Phone Number <span class="text-danger">*</span></label>
                            <input type="text" name="phone" id="phone" class="form-control"
                                value="{{ old('phone', $applicationform->phone) }}" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="advertisement_no" class="form-label">Advertisement Number <span class="text-danger">*</span></label>
                            <input type="text" name="advertisement_no" id="advertisement_no" class="form-control" value="{{ old('advertisement_no', $applicationform->advertisement_no ?? '') }}" readonly>
                        </div>
                        <div class="col-md-4">
                            <label for="applying_position" class="form-label">Applying Position <span class="text-danger">*</span></label>
                            <input type="text" name="applying_position" id="applying_position" class="form-control" value="{{ old('applying_position', $applicationform->applying_position ?? '') }}" readonly>
                        </div>
                        <div class="col-md-4">
                            <label for="department" class="form-label">Department <span class="text-danger">*</span></label>
                            <input type="text" name="department" class="form-control"
                                value="{{ old('department', $applicationform->department ?? '') }}" readonly>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="age" class="form-label">Age <span class="text-danger">*</span> <small>(उमेर)</small></label>
                            <input type="number" name="age" id="age" class="form-control" min="0"
                                value="{{ old('age', $applicationform->age) }}" required>
                        </div>
                        <div class="col-md-4">
                            <label for="alternate_phone_number" class="form-label">Alternate Phone Number <small>(वैकल्पिक फोन नम्बर)</small></label>
                            <input type="text" name="alternate_phone_number" id="alternate_phone_number" class="form-control" value="{{ old('alternate_phone_number', $applicationform->alternate_phone_number) }}">
                        </div>
                        <div class="col-md-4">
                            <label for="gender" class="form-label">Gender <span class="text-danger">*</span> <small>(लिङ्ग)</small></label>
                            <select name="gender" id="gender" class="form-select" required>
                                <option value="">-- Select / छान्नुहोस् --</option>
                                <option value="Male" {{ old('gender', $applicationform->gender) == 'Male' ? 'selected' : '' }}>Male / पुरुष</option>
                                <option value="Female" {{ old('gender', $applicationform->gender) == 'Female' ? 'selected' : '' }}>Female / महिला</option>
                                <option value="Other" {{ old('gender', $applicationform->gender) == 'Other' ? 'selected' : '' }}>Other / अन्य</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="marital_status" class="form-label">Marital Status <span class="text-danger">*</span></label>
                            <select name="marital_status" id="marital_status" class="form-select" required>
                                <option value="">-- Select --</option>
                                <option value="Single" {{ old('marital_status', $applicationform->marital_status) == 'Single' ? 'selected' : '' }}>Single</option>
                                <option value="Married" {{ old('marital_status', $applicationform->marital_status) == 'Married' ? 'selected' : '' }}>Married</option>
                                <option value="Divorced" {{ old('marital_status', $applicationform->marital_status) == 'Divorced' ? 'selected' : '' }}>Divorced</option>
                                <option value="Widowed" {{ old('marital_status', $applicationform->marital_status) == 'Widowed' ? 'selected' : '' }}>Widowed</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="spouse_name_english" class="form-label">Spouse Name (If Married)</label>
                            <input type="text" name="spouse_name_english" id="spouse_name_english" class="form-control" value="{{ old('spouse_name_english', $applicationform->spouse_name_english) }}">
                        </div>
                        <div class="col-md-4">
                            <label for="spouse_nationality" class="form-label">Spouse Nationality (If Married)</label>
                            <input type="text" name="spouse_nationality" id="spouse_nationality" class="form-control" value="{{ old('spouse_nationality', $applicationform->spouse_nationality) }}">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="citizenship_number" class="form-label">Citizenship Number <span class="text-danger">*</span></label>
                            <input type="text" name="citizenship_number" id="citizenship_number" class="form-control"
                                value="{{ old('citizenship_number', $applicationform->citizenship_number) }}" required>
                        </div>
                        <div class="col-md-4">
                            <label for="citizenship_issue_date_bs" class="form-label">Citizenship Issue Date (B.S)<span class="text-danger">*</span></label>
                            <div class="ndp-wrapper">
                                <input type="text" name="citizenship_issue_date_bs" id="citizenship_issue_date_bs" class="form-control" placeholder="YYYY-MM-DD" autocomplete="off"
                                    value="{{ old('citizenship_issue_date_bs', $applicationform->citizenship_issue_date_bs) }}" required>
                                <span class="ndp-icon"><i class="bi bi-calendar-check"></i></span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label for="citizenship_issue_district" class="form-label">Citizenship Issue District <span class="text-danger">*</span></label>
                            <input type="text" name="citizenship_issue_district" id="citizenship_issue_district" class="form-control"
                                value="{{ old('citizenship_issue_district', $applicationform->citizenship_issue_district) }}" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="father_name_english" class="form-label">Father Name (बुबाको नाम) <span class="text-danger">*</span></label>
                            <input type="text" name="father_name_english" id="father_name_english" class="form-control" value="{{ old('father_name_english', $applicationform->father_name_english) }}" required>
                        </div>
                        <div class="col-md-4">
                            <label for="mother_name_english" class="form-label">Mother Name (आमाको नाम) <span class="text-danger">*</span></label>
                            <input type="text" name="mother_name_english" id="mother_name_english" class="form-control" value="{{ old('mother_name_english', $applicationform->mother_name_english) }}" required>
                        </div>
                        <div class="col-md-4">
                            <label for="grandfather_name_english" class="form-label">Grandfather Name (हजुरबुबाको नाम) <span class="text-danger">*</span></label>
                            <input type="text" name="grandfather_name_english" id="grandfather_name_english" class="form-control" value="{{ old('grandfather_name_english', $applicationform->grandfather_name_english) }}" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="father_qualification" class="form-label">Father's Qualification (बुबाको योग्यता)</label>
                            <input type="text" name="father_qualification" id="father_qualification" class="form-control" value="{{ old('father_qualification', $applicationform->father_qualification) }}">
                        </div>
                        <div class="col-md-4">
                            <label for="mother_qualification" class="form-label">Mother's Qualification (आमाको योग्यता)</label>
                            <input type="text" name="mother_qualification" id="mother_qualification" class="form-control" value="{{ old('mother_qualification', $applicationform->mother_qualification) }}">
                        </div>
                        <div class="col-md-4">
                            <label for="parent_occupation" class="form-label">Parent's Occupation <span class="text-danger">*</span></label>
                            <input type="text" name="parent_occupation" id="parent_occupation" class="form-control" value="{{ old('parent_occupation', $applicationform->parent_occupation) }}" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="blood_group" class="form-label">Blood Group <span class="text-danger">*</span></label>
                            <input type="text" name="blood_group" id="blood_group" class="form-control" value="{{ old('blood_group', $applicationform->blood_group) }}" required>
                        </div>
                        <div class="col-md-4">
                            <label for="nationality" class="form-label">Nationality <span class="text-danger">*</span></label>
                            <input type="text" name="nationality" id="nationality" class="form-control" value="{{ old('nationality', $applicationform->nationality) }}" required>
                        </div>
                        <div class="col-md-4">
                            <label for="noc_employee" class="form-label">Are you NOC Employee? <span class="text-danger">*</span></label>
                            <select name="noc_employee" id="noc_employee" class="form-select" required>
                                <option value="">-- Select --</option>
                                <option value="yes" {{ old('noc_employee', $applicationform->noc_employee) == 'yes' ? 'selected' : '' }}>Yes</option>
                                <option value="no" {{ old('noc_employee', $applicationform->noc_employee) == 'no' ? 'selected' : '' }}>No</option>
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="noc_id_card" class="form-label">NOC ID Card</label>
                            @if($applicationform->noc_id_card)
                                <div class="input-group" id="noc_id_card_current">
                                    <a href="{{ asset('storage/' . $applicationform->noc_id_card) }}"
                                    target="_blank"
                                    class="form-control text-primary text-decoration-none bg-white">
                                        📄 View Current File
                                    </a>
                                    <button type="button" class="btn btn-outline-secondary"
                                            onclick="document.getElementById('noc_id_card_wrapper').classList.remove('d-none'); document.getElementById('noc_id_card_current').classList.add('d-none')">
                                        Change
                                    </button>
                                </div>
                                <div id="noc_id_card_wrapper" class="d-none mt-1">
                                    <input type="file" name="noc_id_card" id="noc_id_card"
                                        class="form-control" accept="image/*,application/pdf">
                                </div>
                            @else
                                <input type="file" name="noc_id_card" id="noc_id_card"
                                    class="form-control" accept="image/*,application/pdf">
                            @endif
                            <small class="text-muted d-block">Max Size: 700KB</small>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end">
                        <button type="button" class="btn btn-light next-btn">Next</button>
                    </div>
                </div>


<!-- STEP 2: General Info -->
                <div class="step d-none" id="step2">
                    <h5 class="mb-4 text-dark">Step 2 — General Information</h5>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="religion" class="form-label">Religion <span class="text-danger">*</span> <small>(धर्म)</small></label>
                            <select name="religion" id="religion" class="form-select" required>
                                <option value="">-- Select --</option>
                                <option value="Hindu" {{ old('religion', $applicationform->religion) == 'Hindu' ? 'selected' : '' }}>Hindu / हिन्दू</option>
                                <option value="Buddhist" {{ old('religion', $applicationform->religion) == 'Buddhist' ? 'selected' : '' }}>Buddhist / बौद्ध</option>
                                <option value="Christian" {{ old('religion', $applicationform->religion) == 'Christian' ? 'selected' : '' }}>Christian / ख्रीष्टिय</option>
                                <option value="Muslim" {{ old('religion', $applicationform->religion) == 'Muslim' ? 'selected' : '' }}>Muslim / मुस्लिम</option>
                                <option value="Other" {{ old('religion', $applicationform->religion) == 'Other' ? 'selected' : '' }}>Other / अन्य</option>
                            </select>
                            <input type="text" name="religion_other" id="religion_other" class="form-control mt-2 d-none" placeholder="If other, specify" value="{{ old('religion_other', $applicationform->religion_other) }}">
                        </div>
                        <div class="col-md-4">
                            <label for="community" class="form-label">Community <span class="text-danger">*</span> <small>(तपाई आफैलाई के बोलाउन रुचाउनुहुन्छ)</small></label>
                            <select name="community" id="community" class="form-select" required>
                                <option value="">-- Select --</option>
                                <option value="Male" {{ old('community', $applicationform->community) == 'Male' ? 'selected' : '' }}>पुरुष</option>
                                <option value="Female" {{ old('community', $applicationform->community) == 'Female' ? 'selected' : '' }}>महिला</option>
                                <option value="LGBTQ" {{ old('community', $applicationform->community) == 'LGBTQ' ? 'selected' : '' }}>LGBTQ+</option>
                                <option value="Other" {{ old('community', $applicationform->community) == 'Other' ? 'selected' : '' }}>Other / अन्य</option>
                            </select>
                            <input type="text" name="community_other" id="community_other" class="form-control mt-2 d-none" placeholder="If other, specify" value="{{ old('community_other', $applicationform->community_other) }}">
                        </div>
                        <div class="col-md-4">
                            <label for="ethnic_group" class="form-label">Ethnic Group <span class="text-danger">*</span> <small>(जातीय समूह)</small></label>
                            <select name="ethnic_group" id="ethnic_group" class="form-select" required>
                                <option value="">-- Select --</option>
                                <option value="Dalit" {{ old('ethnic_group', $applicationform->ethnic_group) == 'Dalit' ? 'selected' : '' }}>Dalit</option>
                                <option value="Janajati" {{ old('ethnic_group', $applicationform->ethnic_group) == 'Janajati' ? 'selected' : '' }}>Janajati</option>
                                <option value="Madhesi" {{ old('ethnic_group', $applicationform->ethnic_group) == 'Madhesi' ? 'selected' : '' }}>Madhesi</option>
                                <option value="Brahmin/Chhetri" {{ old('ethnic_group', $applicationform->ethnic_group) == 'Brahmin/Chhetri' ? 'selected' : '' }}>Brahmin / Chhetri</option>
                                <option value="Other" {{ old('ethnic_group', $applicationform->ethnic_group) == 'Other' ? 'selected' : '' }}>Other</option>
                            </select>
                            <input type="text" name="ethnic_group_other" id="ethnic_group_other" class="form-control mt-2 d-none" placeholder="If other, specify" value="{{ old('ethnic_group_other', $applicationform->ethnic_group_other) }}">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="ethnic_certificate" class="form-label">Ethnic Certificate</label>
                            @if($applicationform->ethnic_certificate)
                                <div class="input-group" id="ethnic_certificate_current">
                                    <a href="{{ asset('storage/' . $applicationform->ethnic_certificate) }}"
                                    target="_blank"
                                    class="form-control text-primary text-decoration-none bg-white">
                                        📄 View Current File
                                    </a>
                                    <button type="button" class="btn btn-outline-secondary"
                                            onclick="document.getElementById('ethnic_certificate_wrapper').classList.remove('d-none'); document.getElementById('ethnic_certificate_current').classList.add('d-none')">
                                        Change
                                    </button>
                                </div>
                                <div id="ethnic_certificate_wrapper" class="d-none mt-1">
                                    <input type="file" name="ethnic_certificate" id="ethnic_certificate"
                                        class="form-control" accept="image/*,application/pdf">
                                </div>
                            @else
                                <input type="file" name="ethnic_certificate" id="ethnic_certificate"
                                    class="form-control" accept="image/*,application/pdf">
                            @endif
                            <small class="text-muted">Max Size: 700KB</small>
                        </div>
                        <div class="col-md-6">
                            <label for="mother_tongue" class="form-label">Mother Tongue <span class="text-danger">*</span> <small>(मातृभाषा)</small></label>
                            <input type="text" name="mother_tongue" id="mother_tongue" class="form-control" value="{{ old('mother_tongue', $applicationform->mother_tongue) }}" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="employment_status" class="form-label">Employment Status <span class="text-danger">*</span> <small>(रोजगार स्थिति)</small></label>
                            <select name="employment_status" id="employment_status" class="form-select" required>
                                <option value="">-- Select --</option>
                                <option value="employed" {{ old('employment_status', $applicationform->employment_status) == 'employed' ? 'selected' : '' }}>Employed</option>
                                <option value="unemployed" {{ old('employment_status', $applicationform->employment_status) == 'unemployed' ? 'selected' : '' }}>Unemployed</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="physical_disability" class="form-label">Physical Disability <span class="text-danger">*</span> <small>(कुनै पनि असक्षमता?)</small></label>
                            <select name="physical_disability" id="physical_disability" class="form-select" required>
                                <option value="">-- Select --</option>
                                <option value="yes" {{ old('physical_disability', $applicationform->physical_disability) == 'yes' ? 'selected' : '' }}>Yes</option>
                                <option value="no" {{ old('physical_disability', $applicationform->physical_disability) == 'no' ? 'selected' : '' }}>No</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="disability_certificate" class="form-label">Disability Certificate (If Any)</label>
                            @if($applicationform->disability_certificate)
                                <div class="input-group" id="disability_certificate_current">
                                    <a href="{{ asset('storage/' . $applicationform->disability_certificate) }}"
                                    target="_blank"
                                    class="form-control text-primary text-decoration-none bg-white">
                                        📄 View Current File
                                    </a>
                                    <button type="button" class="btn btn-outline-secondary"
                                            onclick="document.getElementById('disability_certificate_wrapper').classList.remove('d-none'); document.getElementById('disability_certificate_current').classList.add('d-none')">
                                        Change
                                    </button>
                                </div>
                                <div id="disability_certificate_wrapper" class="d-none mt-1">
                                    <input type="file" name="disability_certificate" id="disability_certificate"
                                        class="form-control" accept="image/*,application/pdf">
                                </div>
                            @else
                                <input type="file" name="disability_certificate" id="disability_certificate"
                                    class="form-control" accept="image/*,application/pdf">
                            @endif
                            <small class="text-muted d-block">Max Size: 700KB</small>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between">
                        <button type="button" class="btn btn-secondary prev-btn">Back</button>
                        <button type="button" class="btn btn-light next-btn">Next</button>
                    </div>
                </div>

<!-- STEP 3: Address -->
        <div class="step d-none" id="step3">
            <h5 class="mb-4 text-dark">Step 3 — Permanent Address</h5>
            <div class="row mb-3">
                <div class="col-md-4">
                    <label for="permanent_province" class="form-label">Province <span class="text-danger">*</span></label>
                    <select name="permanent_province" id="permanent_province" class="form-select" required onchange="cascadeDistrict('permanent')">
                        <option value="">-- Select Province --</option>
                        @foreach(['Koshi','Madhesh','Bagmati','Gandaki','Lumbini','Karnali','Sudurpashchim'] as $province)
                            <option value="{{ $province }}" {{ old('permanent_province', $applicationform->permanent_province) == $province ? 'selected' : '' }}>{{ $province }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="permanent_district" class="form-label">District <span class="text-danger">*</span></label>
                    <select name="permanent_district" id="permanent_district" class="form-select" required onchange="cascadeMunicipality('permanent')" disabled>
                        <option value="">-- Select District --</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="permanent_municipality" class="form-label">Municipality <span class="text-danger">*</span></label>
                    <select name="permanent_municipality" id="permanent_municipality" class="form-select" required disabled>
                        <option value="">-- Select Municipality --</option>
                    </select>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-4">
                    <label for="permanent_ward" class="form-label">Ward No. <span class="text-danger">*</span></label>
                    <input type="text" name="permanent_ward" id="permanent_ward" class="form-control" value="{{ old('permanent_ward', $applicationform->permanent_ward) }}" required>
                </div>
                <div class="col-md-4">
                    <label for="permanent_tole" class="form-label">Tole</label>
                    <input type="text" name="permanent_tole" id="permanent_tole" class="form-control" value="{{ old('permanent_tole', $applicationform->permanent_tole) }}">
                </div>
                <div class="col-md-4">
                    <label for="permanent_house_number" class="form-label">House Number</label>
                    <input type="text" name="permanent_house_number" id="permanent_house_number" class="form-control" value="{{ old('permanent_house_number', $applicationform->permanent_house_number) }}">
                </div>
            </div>

            <h5 class="mb-4 text-dark mt-4">Mailing/Current Address</h5>
            <div class="form-check mb-4">
                <input type="checkbox" class="form-check-input" id="same_as_permanent" name="same_as_permanent" value="1" {{ old('same_as_permanent', $applicationform->same_as_permanent) ? 'checked' : '' }} onchange="toggleSameAsPermanent()">
                <label class="form-check-label" for="same_as_permanent">Same as Permanent Address</label>
            </div>

            <div id="mailing_fields">
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label for="mailing_province" class="form-label">Province <span class="text-danger">*</span></label>
                        <select name="mailing_province" id="mailing_province" class="form-select" required onchange="cascadeDistrict('mailing')">
                            <option value="">-- Select Province --</option>
                            @foreach(['Koshi','Madhesh','Bagmati','Gandaki','Lumbini','Karnali','Sudurpashchim'] as $province)
                                <option value="{{ $province }}" {{ old('mailing_province', $applicationform->mailing_province) == $province ? 'selected' : '' }}>{{ $province }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="mailing_district" class="form-label">District <span class="text-danger">*</span></label>
                        <select name="mailing_district" id="mailing_district" class="form-select" required onchange="cascadeMunicipality('mailing')" disabled>
                            <option value="">-- Select District --</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="mailing_municipality" class="form-label">Municipality <span class="text-danger">*</span></label>
                        <select name="mailing_municipality" id="mailing_municipality" class="form-select" required disabled>
                            <option value="">-- Select Municipality --</option>
                        </select>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label for="mailing_ward" class="form-label">Ward No. <span class="text-danger">*</span></label>
                        <input type="text" name="mailing_ward" id="mailing_ward" class="form-control" value="{{ old('mailing_ward', $applicationform->mailing_ward) }}" required>
                    </div>
                    <div class="col-md-4">
                        <label for="mailing_tole" class="form-label">Tole</label>
                        <input type="text" name="mailing_tole" id="mailing_tole" class="form-control" value="{{ old('mailing_tole', $applicationform->mailing_tole) }}">
                    </div>
                    <div class="col-md-4">
                        <label for="mailing_house_number" class="form-label">House Number</label>
                        <input type="text" name="mailing_house_number" id="mailing_house_number" class="form-control" value="{{ old('mailing_house_number', $applicationform->mailing_house_number) }}">
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-between">
                <button type="button" class="btn btn-secondary prev-btn">Back</button>
                <button type="button" class="btn btn-light next-btn">Next</button>
            </div>
        </div>

<!-- STEP 4: Educational Background -->
                <div class="step d-none" id="step4">
                    <h5 class="mb-4 text-dark">Step 4 — Educational Background</h5>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="education_level" class="form-label">Highest Education Level <span class="text-danger">*</span></label>
                            <select name="education_level" id="education_level" class="form-select" required>
                                <option value="">-- Select --</option>
                                <option value="Under SLC" {{ old('education_level', $applicationform->education_level) == 'Under SLC' ? 'selected' : '' }}>Under SLC</option>
                                <option value="SLC/SEE" {{ old('education_level', $applicationform->education_level) == 'SLC/SEE' ? 'selected' : '' }}>SLC/SEE</option>
                                <option value="+2/Intermediate" {{ old('education_level', $applicationform->education_level) == '+2/Intermediate' ? 'selected' : '' }}>+2/Intermediate</option>
                                <option value="Bachelor" {{ old('education_level', $applicationform->education_level) == 'Bachelor' ? 'selected' : '' }}>Bachelor</option>
                                <option value="Master" {{ old('education_level', $applicationform->education_level) == 'Master' ? 'selected' : '' }}>Master</option>
                                <option value="PhD" {{ old('education_level', $applicationform->education_level) == 'PhD' ? 'selected' : '' }}>PhD</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="field_of_study" class="form-label">Field of Study<span class="text-danger">*</span></label>
                            <input type="text" name="field_of_study" id="field_of_study" class="form-control" value="{{ old('field_of_study', $applicationform->field_of_study) }}" required>
                        </div>
                    </div>
                <div class="row mb-3 align-items-end">
                    <div class="col-md-6">
                        <label for="institution_name" class="form-label">Institution Name<span class="text-danger">*</span></label>
                        <input type="text"
                            name="institution_name"
                            id="institution_name"
                            class="form-control"
                            value="{{ old('institution_name', $applicationform->institution_name) }}"
                            required>
<<<<<<< HEAD
=======
                    </div>

                <div class="col-md-3">
                    <label for="graduation_year" class="form-label">Passed Year in BS <span class="text-danger">*</span></label>
                    <input type="text"
                        name="graduation_year"
                        id="graduation_year"
                        class="form-control"
                        placeholder="YYYY"
                        inputmode="numeric"
                        maxlength="4"
                        autocomplete="off"
                        value="{{ old('graduation_year', $applicationform->graduation_year) }}"
                        required>
                </div>

                <div class="col-md-3">
                    <label for="graduation_year_english" class="form-label">Passed Year in AD <span class="text-danger">*</span></label>
                    <input type="text"
                        name="graduation_year_english"
                        id="graduation_year_english"
                        class="form-control"
                        placeholder="YYYY"
                        inputmode="numeric"
                        maxlength="4"
                        autocomplete="off"
                        value="{{ old('graduation_year_english', $applicationform->graduation_year_english) }}"
                        required>
                </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="university" class="form-label">University Name<span class="text-danger">*</span></label>
                            <input type="text" name="university" id="university" class="form-control" value="{{ old('university', $applicationform->university) }}" required>
                        </div>
                        <div class="col-md-6">
                            <label for="transcript" class="form-label">Transcript Certificate<span class="text-danger">*</span></label>
                            @if($applicationform->transcript)
                                <div class="input-group" id="transcript_current">
                                    <a href="{{ asset('storage/' . $applicationform->transcript) }}"
                                    target="_blank"
                                    class="form-control text-primary text-decoration-none bg-white">
                                        📄 View Current File
                                    </a>
                                    <button type="button" class="btn btn-outline-secondary"
                                            onclick="document.getElementById('transcript_wrapper').classList.remove('d-none'); document.getElementById('transcript_current').classList.add('d-none')">
                                        Change
                                    </button>
                                </div>
                                <div id="transcript_wrapper" class="d-none mt-1">
                                    <input type="file" name="transcript" id="transcript"
                                        class="form-control" accept="image/*,application/pdf">
                                </div>
                            @else
                                <input type="file" name="transcript" id="transcript" class="form-control" accept="image/*,application/pdf" required>
                            @endif
                            <small class="text-muted d-block">Max Size: 700KB</small>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="character" class="form-label">Character Certificate <span class="text-danger">*</span></label>
                            @if($applicationform->character)
                                <div class="input-group" id="character_current">
                                    <a href="{{ asset('storage/' . $applicationform->character) }}"
                                    target="_blank"
                                    class="form-control text-primary text-decoration-none bg-white">
                                        📄 View Current File
                                    </a>
                                    <button type="button" class="btn btn-outline-secondary"
                                            onclick="document.getElementById('character_wrapper').classList.remove('d-none'); document.getElementById('character_current').classList.add('d-none')">
                                        Change
                                    </button>
                                </div>
                                <div id="character_wrapper" class="d-none mt-1">
                                    <input type="file" name="character" id="character"
                                        class="form-control" accept="image/*,application/pdf">
                                </div>
                            @else
                                <input type="file" name="character" id="character" class="form-control" accept="image/*,application/pdf" required>
                            @endif
                            <small class="text-muted d-block">Max Size: 700KB</small>
                        </div>
                        <div class="col-md-6">
                            <label for="equivalent" class="form-label">Equivalency Certificate (If your degree is out of Nepal)</label>
                            @if($applicationform->equivalent)
                                <div class="input-group" id="equivalent_current">
                                    <a href="{{ asset('storage/' . $applicationform->equivalent) }}"
                                    target="_blank"
                                    class="form-control text-primary text-decoration-none bg-white">
                                        📄 View Current File
                                    </a>
                                    <button type="button" class="btn btn-outline-secondary"
                                            onclick="document.getElementById('equivalent_wrapper').classList.remove('d-none'); document.getElementById('equivalent_current').classList.add('d-none')">
                                        Change
                                    </button>
                                </div>
                                <div id="equivalent_wrapper" class="d-none mt-1">
                                    <input type="file" name="equivalent" id="equivalent"
                                        class="form-control" accept="image/*,application/pdf">
                                </div>
                            @else
                                <input type="file" name="equivalent" id="equivalent" class="form-control" accept="image/*,application/pdf">
                            @endif
                            <small class="text-muted d-block">Max Size: 700KB</small>
                        </div>
>>>>>>> efe7d213166d7eb2c3aef5455d337ce01292fe6f
                    </div>
                    <div class="col-md-3">
                        <label for="graduation_year" class="form-label">Passed Year in BS <span class="text-danger">*</span></label>
                        <input type="text"
                            name="graduation_year"
                            id="graduation_year"
                            class="form-control"
                            placeholder="YYYY"
                            inputmode="numeric"
                            maxlength="4"
                            autocomplete="off"
                            value="{{ old('graduation_year', $applicationform->graduation_year) }}"
                            required>
                    </div>
                    <div class="col-md-3">
                        <label for="graduation_year_english" class="form-label">Passed Year in AD <span class="text-danger">*</span></label>
                        <input type="text"
                            name="graduation_year_english"
                            id="graduation_year_english"
                            class="form-control"
                            placeholder="YYYY"
                            inputmode="numeric"
                            maxlength="4"
                            autocomplete="off"
                            value="{{ old('graduation_year_english', $applicationform->graduation_year_english) }}"
                            required>
                    </div>
                </div>
<<<<<<< HEAD
=======

<!-- STEP 5: Work Experience -->
                <div class="step d-none" id="step5">
                    <h5 class="mb-4 text-dark">Step 5 — Work Experience</h5>
>>>>>>> efe7d213166d7eb2c3aef5455d337ce01292fe6f
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="university" class="form-label">University Name<span class="text-danger">*</span></label>
                            <input type="text" name="university" id="university" class="form-control" value="{{ old('university', $applicationform->university) }}" required>
                        </div>
                        <div class="col-md-6">
<<<<<<< HEAD
                            <label for="transcript" class="form-label">Transcript Certificate<span class="text-danger">*</span></label>
                            @if($applicationform->transcript)
                                <div class="input-group" id="transcript_current">
                                    <a href="{{ asset('storage/' . $applicationform->transcript) }}"
                                    target="_blank"
                                    class="form-control text-primary text-decoration-none bg-white">
                                        📄 View Current File
                                    </a>
                                    <button type="button" class="btn btn-outline-secondary"
                                            onclick="document.getElementById('transcript_wrapper').classList.remove('d-none'); document.getElementById('transcript_current').classList.add('d-none')">
                                        Change
                                    </button>
                                </div>
                                <div id="transcript_wrapper" class="d-none mt-1">
                                    <input type="file" name="transcript" id="transcript"
                                        class="form-control" accept="image/*,application/pdf">
                                </div>
                            @else
                                <input type="file" name="transcript" id="transcript" class="form-control" accept="image/*,application/pdf" required>
                            @endif
                            <small class="text-muted d-block">Max Size: 700KB</small>
=======
                            <label for="years_of_experience" class="form-label">Years of Experience</label>
                            <input type="number" name="years_of_experience" id="years_of_experience" class="form-control" min="0" step="0.5" value="{{ old('years_of_experience', $applicationform->years_of_experience) }}">
>>>>>>> efe7d213166d7eb2c3aef5455d337ce01292fe6f
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
<<<<<<< HEAD
                            <label for="character" class="form-label">Character Certificate <span class="text-danger">*</span></label>
                            @if($applicationform->character)
                                <div class="input-group" id="character_current">
                                    <a href="{{ asset('storage/' . $applicationform->character) }}"
                                    target="_blank"
                                    class="form-control text-primary text-decoration-none bg-white">
                                        📄 View Current File
                                    </a>
                                    <button type="button" class="btn btn-outline-secondary"
                                            onclick="document.getElementById('character_wrapper').classList.remove('d-none'); document.getElementById('character_current').classList.add('d-none')">
                                        Change
                                    </button>
                                </div>
                                <div id="character_wrapper" class="d-none mt-1">
                                    <input type="file" name="character" id="character"
                                        class="form-control" accept="image/*,application/pdf">
                                </div>
                            @else
                                <input type="file" name="character" id="character" class="form-control" accept="image/*,application/pdf" required>
                            @endif
                            <small class="text-muted d-block">Max Size: 700KB</small>
                        </div>
                        <div class="col-md-6">
                            <label for="equivalent" class="form-label">Equivalency Certificate (If your degree is out of Nepal)</label>
                            @if($applicationform->equivalent)
                                <div class="input-group" id="equivalent_current">
                                    <a href="{{ asset('storage/' . $applicationform->equivalent) }}"
                                    target="_blank"
                                    class="form-control text-primary text-decoration-none bg-white">
                                        📄 View Current File
                                    </a>
                                    <button type="button" class="btn btn-outline-secondary"
                                            onclick="document.getElementById('equivalent_wrapper').classList.remove('d-none'); document.getElementById('equivalent_current').classList.add('d-none')">
                                        Change
                                    </button>
                                </div>
                                <div id="equivalent_wrapper" class="d-none mt-1">
                                    <input type="file" name="equivalent" id="equivalent"
                                        class="form-control" accept="image/*,application/pdf">
                                </div>
                            @else
                                <input type="file" name="equivalent" id="equivalent" class="form-control" accept="image/*,application/pdf">
                            @endif
                            <small class="text-muted d-block">Max Size: 700KB</small>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between">
                        <button type="button" class="btn btn-secondary prev-btn">Back</button>
                        <button type="button" class="btn btn-light next-btn">Next</button>
                    </div>
                </div>

<!-- STEP 5: Work Experience -->
<div class="step d-none" id="step5">
    <h5 class="mb-4 text-dark">Step 5 — Work Experience</h5>

    <div class="row mb-3">
        <div class="col-md-6">
            <label for="has_work_experience" class="form-label">
                Do you have work experience? <span class="text-danger">*</span>
            </label>
            <select name="has_work_experience" id="has_work_experience" class="form-select" required>
                <option value="">-- Select --</option>
                <option value="Yes" {{ old('has_work_experience', $applicationform->has_work_experience) == 'Yes' ? 'selected' : '' }}>Yes</option>
                <option value="No" {{ old('has_work_experience', $applicationform->has_work_experience) == 'No' ? 'selected' : '' }}>No</option>
            </select>
        </div>
    </div>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Organization</th>
                <th>Position</th>
                <th>Start Date (B.S)</th>
                <th>End Date (B.S)</th>
                <th>Years</th>
                <th>Document</th>
            </tr>
        </thead>
        <tbody>

            {{-- ================= EXP 1 ================= --}}
            <tr>
                <td>
                    <input type="text" name="exp1_organization" class="form-control"
                        value="{{ old('exp1_organization', $applicationform->exp1_organization) }}">
                </td>

                <td>
                    <input type="text" name="exp1_position" class="form-control"
                        value="{{ old('exp1_position', $applicationform->exp1_position) }}">
                </td>

                <td>
                    <input type="text" name="exp1_start_date_bs"
                        class="form-control nepali-date"
                        placeholder="YYYY-MM-DD"
                        value="{{ old('exp1_start_date_bs', $applicationform->exp1_start_date) }}">
                        

                </td>

                <td>
                    <input type="text" name="exp1_end_date_bs"
                        class="form-control nepali-date"
                        value="{{ old('exp1_end_date_bs', $applicationform->exp1_end_date) }}">
                </td>

                <td>
                    <input type="number" step="0.5" name="exp1_years" class="form-control"
                        value="{{ old('exp1_years', $applicationform->exp1_years) }}">
                </td>

                <td>
                    @if($applicationform->exp1_document)
                        <a href="{{ asset('storage/' . $applicationform->exp1_document) }}" target="_blank">
                            View
                        </a>
                    @endif
                    <input type="file" name="exp1_document" class="form-control"
    data-existing="{{ $applicationform->exp1_document ? asset('storage/'.$applicationform->exp1_document) : '' }}">
                </td>
            </tr>

            {{-- ================= EXP 2 ================= --}}
            <tr>
                <td>
                    <input type="text" name="exp2_organization" class="form-control"
                        value="{{ old('exp2_organization', $applicationform->exp2_organization) }}">
                </td>

                <td>
                    <input type="text" name="exp2_position" class="form-control"
                        value="{{ old('exp2_position', $applicationform->exp2_position) }}">
                </td>

                <td>
                    <input type="text" name="exp2_start_date_bs"
                        class="form-control nepali-date"
                        value="{{ old('exp2_start_date_bs', $applicationform->exp2_start_date) }}">
                </td>

                <td>
                    <input type="text" name="exp2_end_date_bs"
                        class="form-control nepali-date"
                        value="{{ old('exp2_end_date_bs', $applicationform->exp2_end_date) }}">
                </td>

                <td>
                    <input type="number" step="0.5" name="exp2_years" class="form-control"
                        value="{{ old('exp2_years', $applicationform->exp2_years) }}">
                </td>

                <td>
                    @if($applicationform->exp2_document)
                        <a href="{{ asset('storage/' . $applicationform->exp2_document) }}" target="_blank">
                            View
                        </a>
                    @endif
                    <input type="file" name="exp2_document" class="form-control"
    data-existing="{{ $applicationform->exp2_document ? asset('storage/'.$applicationform->exp2_document) : '' }}">
                </td>
            </tr>

            {{-- ================= EXP 3 ================= --}}
            <tr>
                <td>
                    <input type="text" name="exp3_organization" class="form-control"
                        value="{{ old('exp3_organization', $applicationform->exp3_organization) }}">
                </td>

                <td>
                    <input type="text" name="exp3_position" class="form-control"
                        value="{{ old('exp3_position', $applicationform->exp3_position) }}">
                </td>

                <td>
                    <input type="text" name="exp3_start_date_bs"
                        class="form-control nepali-date"
                        value="{{ old('exp3_start_date_bs', $applicationform->exp3_start_date) }}">
                </td>

                <td>
                    <input type="text" name="exp3_end_date_bs"
                        class="form-control nepali-date"
                        value="{{ old('exp3_end_date_bs', $applicationform->exp3_end_date) }}">
                </td>

                <td>
                    <input type="number" step="0.5" name="exp3_years" class="form-control"
                        value="{{ old('exp3_years', $applicationform->exp3_years) }}">
                </td>

                <td>
                    @if($applicationform->exp3_document)
                        <a href="{{ asset('storage/' . $applicationform->exp3_document) }}" target="_blank">
                            View
                        </a>
                    @endif
                    <input type="file" name="exp3_document" class="form-control"
    data-existing="{{ $applicationform->exp3_document ? asset('storage/'.$applicationform->exp3_document) : '' }}">
                </td>
            </tr>

        </tbody>
    </table>

    <div class="d-flex justify-content-between">
        <button type="button" class="btn btn-secondary prev-btn">Back</button>
        <button type="button" class="btn btn-light next-btn">Next</button>
    </div>
</div>

<!-- STEP 6: Upload Documents -->
                <div class="step d-none" id="step6">
                    <h5 class="mb-4 text-dark">Step 6 — Upload Documents</h5>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="passport_size_photo" class="form-label">Passport Size Photo <span class="text-danger">*</span></label>
                            @if($applicationform->passport_size_photo)
                                <div class="input-group" id="passport_size_photo_current">
                                    <a href="{{ asset('storage/' . $applicationform->passport_size_photo) }}"
=======
                            <label for="previous_organization" class="form-label">Previous Organization</label>
                            <input type="text" name="previous_organization" id="previous_organization" class="form-control" value="{{ old('previous_organization', $applicationform->previous_organization) }}">
                        </div>
                        <div class="col-md-6">
                            <label for="previous_position" class="form-label">Previous Position</label>
                            <input type="text" name="previous_position" id="previous_position" class="form-control" value="{{ old('previous_position', $applicationform->previous_position) }}">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="work_experience" class="form-label">Work Experience Document</label>
                            @if($applicationform->work_experience)
                                <div class="input-group" id="work_experience_current">
                                    <a href="{{ asset('storage/' . $applicationform->work_experience) }}"
>>>>>>> efe7d213166d7eb2c3aef5455d337ce01292fe6f
                                    target="_blank"
                                    class="form-control text-primary text-decoration-none bg-white">
                                        📄 View Current File
                                    </a>
                                    <button type="button" class="btn btn-outline-secondary"
<<<<<<< HEAD
                                            onclick="document.getElementById('passport_size_photo_wrapper').classList.remove('d-none'); document.getElementById('passport_size_photo_current').classList.add('d-none')">
                                        Change
                                    </button>
                                </div>
                                <div id="passport_size_photo_wrapper" class="d-none mt-1">
                                    <input type="file" name="passport_size_photo" id="passport_size_photo"
                                        class="form-control" accept="image/*">
                                </div>
                            @else
                                <input type="file" name="passport_size_photo" id="passport_size_photo"
                                    class="form-control" accept="image/*,application/pdf" required>
                            @endif
                            <small class="text-muted d-block">Max Size: 700KB</small>
                        </div>

                        <div class="col-md-6">
                            <label for="citizenship_id_document" class="form-label">Citizenship/ID Document<span class="text-danger"><small> (Please upload front and back in same page)</small>*</span></label>
                            @if($applicationform->citizenship_id_document)
                                <div class="input-group" id="citizenship_id_document_current">
                                    <a href="{{ asset('storage/' . $applicationform->citizenship_id_document) }}"
=======
                                            onclick="document.getElementById('work_experience_wrapper').classList.remove('d-none'); document.getElementById('work_experience_current').classList.add('d-none')">
                                        Change
                                    </button>
                                </div>
                                <div id="work_experience_wrapper" class="d-none mt-1">
                                    <input type="file" name="work_experience" id="work_experience"
                                        class="form-control" accept="image/*,application/pdf">
                                </div>
                            @else
                                <input type="file" name="work_experience" id="work_experience" class="form-control" accept="image/*,application/pdf">
                            @endif
                            <small class="text-muted d-block">Max Size: 700KB</small>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between">
                        <button type="button" class="btn btn-secondary prev-btn">Back</button>
                        <button type="button" class="btn btn-light next-btn">Next</button>
                    </div>
                </div>

<!-- STEP 6: Upload Documents -->
                <div class="step d-none" id="step6">
                    <h5 class="mb-4 text-dark">Step 6 — Upload Documents</h5>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="passport_size_photo" class="form-label">Passport Size Photo <span class="text-danger">*</span></label>
                            @if($applicationform->passport_size_photo)
                                <div class="input-group" id="passport_size_photo_current">
                                    <a href="{{ asset('storage/' . $applicationform->passport_size_photo) }}"
>>>>>>> efe7d213166d7eb2c3aef5455d337ce01292fe6f
                                    target="_blank"
                                    class="form-control text-primary text-decoration-none bg-white">
                                        📄 View Current File
                                    </a>
                                    <button type="button" class="btn btn-outline-secondary"
<<<<<<< HEAD
=======
                                            onclick="document.getElementById('passport_size_photo_wrapper').classList.remove('d-none'); document.getElementById('passport_size_photo_current').classList.add('d-none')">
                                        Change
                                    </button>
                                </div>
                                <div id="passport_size_photo_wrapper" class="d-none mt-1">
                                    <input type="file" name="passport_size_photo" id="passport_size_photo"
                                        class="form-control" accept="image/*">
                                </div>
                            @else
                                <input type="file" name="passport_size_photo" id="passport_size_photo"
                                    class="form-control" accept="image/*,application/pdf" required>
                            @endif
                            <small class="text-muted d-block">Max Size: 700KB</small>
                        </div>

                        <div class="col-md-6">
                            <label for="citizenship_id_document" class="form-label">Citizenship/ID Document<span class="text-danger"><small> (Please upload front and back in same page)</small>*</span></label>
                            @if($applicationform->citizenship_id_document)
                                <div class="input-group" id="citizenship_id_document_current">
                                    <a href="{{ asset('storage/' . $applicationform->citizenship_id_document) }}"
                                    target="_blank"
                                    class="form-control text-primary text-decoration-none bg-white">
                                        📄 View Current File
                                    </a>
                                    <button type="button" class="btn btn-outline-secondary"
>>>>>>> efe7d213166d7eb2c3aef5455d337ce01292fe6f
                                            onclick="document.getElementById('citizenship_id_document_wrapper').classList.remove('d-none'); document.getElementById('citizenship_id_document_current').classList.add('d-none')">
                                        Change
                                    </button>
                                </div>
                                <div id="citizenship_id_document_wrapper" class="d-none mt-1">
                                    <input type="file" name="citizenship_id_document" id="citizenship_id_document"
                                        class="form-control" accept="image/*,application/pdf">
                                </div>
                            @else
                                <input type="file" name="citizenship_id_document" id="citizenship_id_document"
                                    class="form-control" accept="image/*,application/pdf" required>
                            @endif
                            <small class="text-muted d-block">Max Size: 700KB</small>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="signature" class="form-label">Signature<span class="text-danger">*</span></label>
                            @if($applicationform->signature)
                                <div class="input-group" id="signature_current">
                                    <a href="{{ asset('storage/' . $applicationform->signature) }}"
                                    target="_blank"
                                    class="form-control text-primary text-decoration-none bg-white">
                                        📄 View Current File
                                    </a>
                                    <button type="button" class="btn btn-outline-secondary"
                                            onclick="document.getElementById('signature_wrapper').classList.remove('d-none'); document.getElementById('signature_current').classList.add('d-none')">
                                        Change
                                    </button>
                                </div>
                                <div id="signature_wrapper" class="d-none mt-1">
                                    <input type="file" name="signature" id="signature"
                                        class="form-control" accept="image/*">
                                </div>
                            @else
                                <input type="file" name="signature" id="signature"
                                    class="form-control" accept="image/*,application/pdf" required>
                            @endif
                            <small class="text-muted d-block">Max Size: 700KB</small>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between">
                        <button type="button" class="btn btn-secondary prev-btn">Back</button>
                        <button type="button" class="btn btn-light next-btn">Next</button>
                    </div>
                </div>

<!-- STEP 7: Preview Application Before Payment -->
                <div class="step d-none" id="step7">
                    <h5 class="mb-4 text-dark">Step 7 — Preview Application Before Payment</h5>

                    <div class="alert alert-info">
                        Please review all your details carefully before proceeding to payment.
                    </div>

                    <div id="previewContainer">

                        <h6 class="text-secondary mt-3">Personal Information</h6>
                        <table class="table table-bordered">
                            <tr><th width="30%">Full Name (English)</th><td id="p_name_english"></td></tr>
                            <tr><th>Full Name (Nepali)</th><td id="p_name_nepali"></td></tr>
                            <tr><th>Email</th><td id="p_email"></td></tr>
                            <tr><th>Birth Date (AD)</th><td id="p_birth_date_ad"></td></tr>
                            <tr><th>Birth Date (BS)</th><td id="p_birth_date_bs"></td></tr>
                            <tr><th>Phone</th><td id="p_phone"></td></tr>
                            <tr><th>Advertisement Number</th><td id="p_advertisement_no"></td></tr>
                            <tr><th>Applying Position</th><td id="p_applying_position"></td></tr>
                            <tr><th>Department</th><td id="p_department"></td></tr>
                            <tr><th>Age</th><td id="p_age"></td></tr>
                            <tr><th>Alternate Phone Number</th><td id="p_alternate_phone_number"></td></tr>
                            <tr><th>Gender</th><td id="p_gender"></td></tr>
                            <tr><th>Marital Status</th><td id="p_marital_status"></td></tr>
                            <tr><th>Spouse Name (If Married)</th><td id="spouse_name_english"></td></tr>
                            <tr><th>Spouse Nationality (If Married)</th><td id="p_spouse_nationality"></td></tr>
                            <tr><th>Citizenship Number</th><td id="p_citizenship_number"></td></tr>
                            <tr><th>Citizenship Issue Date (B.S)</th><td id="p_citizenship_issue_date_bs"></td></tr>
                            <tr><th>Citizenship Issue District</th><td id="p_citizenship_issue_district"></td></tr>
                            <tr><th>Father Name (बुबाको नाम)</th><td id="p_father_name_english"></td></tr>
                            <tr><th>Mother Name (आमाको नाम)</th><td id="p_mother_name_english"></td></tr>
                            <tr><th>Grandfather Name (हजुरबुबाको नाम)</th><td id="p_grandfather_name_english"></td></tr>
                            <tr><th>Father's Qualification (बुबाको योग्यता)</th><td id="p_father_qualification"></td></tr>
                            <tr><th>Mother's Qualification (आमाको योग्यता)</th><td id="p_mother_qualification"></td></tr>
                            <tr><th>Parent's Occupation</th><td id="p_parent_occupation"></td></tr>
                            <tr><th>Blood Group</th><td id="p_blood_group"></td></tr>
                            <tr><th>Nationality</th><td id="p_nationality"></td></tr>
                            <tr><th>Are you NOC Employee?</th><td id="p_noc_employee"></td></tr>
                            <tr><th>NOC ID Card</th><td id="p_noc_id_card"></td></tr>
                        </table>

                        <h6 class="text-secondary mt-3">General Information</h6>
                        <table class="table table-bordered">
                            <tr><th width="30%">Religion</th><td id="p_religion"></td></tr>
                            <tr><th>Community</th><td id="p_community"></td></tr>
                            <tr><th>Ethnic Group</th><td id="p_ethnic_group"></td></tr>
                            <tr><th>Mother Tongue</th><td id="p_mother_tongue"></td></tr>
                            <tr><th>Employment Status</th><td id="p_employment_status"></td></tr>
                            <tr><th>Physical Disability</th><td id="p_physical_disability"></td></tr>
                            <tr><th>Ethnic Certificate</th><td id="p_ethnic_certificate"></td></tr>
                            <tr><th>Disability Certificate</th><td id="p_disability_certificate"></td></tr>
                        </table>

                        <h6 class="text-secondary mt-4">Address Information</h6>
                        <table class="table table-bordered">
                            <tr><th width="30%">Permanent Address</th><td id="p_permanent_address"></td></tr>
                            <tr><th>Mailing Address</th><td id="p_mailing_address"></td></tr>
                        </table>

                        <h6 class="text-secondary mt-4">Education</h6>
                        <table class="table table-bordered">
                            <tr><th width="30%">Education Level</th><td id="p_education_level"></td></tr>
                            <tr><th>Field of Study</th><td id="p_field_of_study"></td></tr>
                            <tr><th>Institution</th><td id="p_institution_name"></td></tr>
                            <tr><th>Passed Year</th><td id="p_graduation_year"></td></tr>
                        </table>

                        <h6 class="text-secondary mt-4">Work Experience</h6>
<<<<<<< HEAD
                            <table class="table table-bordered">
                                <tr>
                                    <th width="30%">Has Experience</th>
                                    <td id="p_has_work_experience"></td>
                                </tr>
                                <tr>
                                    <th>Experience Details</th>
                                    <td>
                                        <div id="experience_preview"></div>
                                    </td>
                                </tr>
                            </table>
=======
                        <table class="table table-bordered">
                            <tr><th width="30%">Has Experience</th><td id="p_has_work_experience"></td></tr>
                            <tr><th>Years of Experience</th><td id="p_years_of_experience"></td></tr>
                            <tr><th>Previous Organization</th><td id="p_previous_organization"></td></tr>
                            <tr><th>Previous Position</th><td id="p_previous_position"></td></tr>
                        </table>
>>>>>>> efe7d213166d7eb2c3aef5455d337ce01292fe6f

                        <h6 class="text-secondary mt-4">Uploaded Documents</h6>
                        <table class="table table-bordered">
                            <tr><th width="30%">Passport Size Photo</th><td id="p_photo"></td></tr>
                            <tr><th>Citizenship / ID Document</th><td id="p_citizenship"></td></tr>
                            <tr><th>Transcript</th><td id="p_transcript"></td></tr>
                            <tr><th>Character</th><td id="p_character"></td></tr>
                            <tr><th>Equivalent</th><td id="p_equivalent"></td></tr>
                            <tr><th>Signature</th><td id="p_signature"></td></tr>
                            <!-- <tr><th>Work Experience</th><td id="p_work_experience"></td></tr> -->
                        </table>

                        <div class="form-check mb-4">
                            <input type="checkbox" class="form-check-input" id="terms_agree" name="terms_agree" required>
                            <label class="form-check-label" for="terms_agree">
                                I hereby declare that all information provided is true and correct. <span class="text-danger">*</span>
                            </label>
                        </div>

                        <div class="d-flex justify-content-between mt-4">
                            <button type="button" class="btn btn-secondary prev-btn">Back</button>
                            <button type="button" class="btn btn-light next-btn">Next</button>
                        </div>
                    </div>
                </div>

<!-- STEP 8: Payment Method -->
                <div class="step d-none" id="step8">
                    <h5 class="mb-4 text-dark">Step 8 — Payment & Declaration</h5>

                    <div id="paymentSection">
                        @if(isset($payment) && $payment->status == 'paid')
                        <div class="alert alert-success mb-3">
                            ✓ Payment already completed via {{ strtoupper($payment->gateway) }}
                        </div>
                        @endif

                        <h6 class="mb-3">Choose Payment Gateway</h6>

                        <div class="row text-center">

                            <!-- eSewa -->
                            <div class="col-md-4 mb-3">
                                <div class="payment-box" onclick="{{ isset($payment) && $payment->status == 'paid' ? '' : "startPayment('esewa')" }}">
                                    <img src="/images/esewalogo.jpg" alt="eSewa" class="payment-logo">
                                    <div>Pay with eSewa</div>
                                </div>
                            </div>

                            <!-- Khalti -->
                            <div class="col-md-4 mb-3">
                                <div class="payment-box" onclick="{{ isset($payment) && $payment->status == 'paid' ? '' : "startPayment('khalti')" }}">
                                    <img src="/images/khaltilogo.jpg" alt="Khalti" class="payment-logo">
                                    <div>Pay with Khalti</div>
                                </div>
                            </div>

                            <!-- ConnectIPS -->
                            <div class="col-md-4 mb-3">
                                <div class="payment-box" onclick="{{ isset($payment) && $payment->status == 'paid' ? '' : "startPayment('connectips')" }}">
                                    <img src="/images/cipslogo.jpg" alt="ConnectIPS" class="payment-logo">
                                    <div>Pay with ConnectIPS</div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between mt-4">
                                <button type="button" class="btn btn-secondary prev-btn">Back</button>
                                <button type="submit" id="saveDraftBtn" class="btn btn-danger">Save Changes</button>
                            </div>

                        </div>
                    </div>
                </div>

            </form>
        </div>
    </div>
</div>

{{-- ══════════════════════════════════════════════════════
     Nepali Date Picker JS
     ══════════════════════════════════════════════════════ --}}
<script src="https://nepalidatepicker.sajanmaharjan.com.np/v5/nepali.datepicker/js/nepali.datepicker.v5.0.6.min.js"></script>

<script>
// ══════════════════════════════════════════════════════════════
// Nepali Date Picker Initialization
// ══════════════════════════════════════════════════════════════
document.addEventListener('DOMContentLoaded', function () {

    function initNDP(el, opts) {
        if (!el || typeof el.nepaliDatePicker !== 'function') return;
        el.nepaliDatePicker(Object.assign({
            ndpYear:      true,
            ndpMonth:     true,
            ndpYearCount: 100,
            onChange: function () {
                el.dispatchEvent(new Event('input', { bubbles: true }));
                el.dispatchEvent(new Event('change', { bubbles: true }));
            }
        }, opts || {}));
    }

    // Initialize date pickers
<<<<<<< HEAD
    
    document.querySelectorAll('.nepali-date').forEach(initNDP);
    // initNDP(document.getElementById('birth_date_bs'));
    // initNDP(document.getElementById('citizenship_issue_date_bs'));
=======
    initNDP(document.getElementById('birth_date_bs'));
    initNDP(document.getElementById('citizenship_issue_date_bs'));
>>>>>>> efe7d213166d7eb2c3aef5455d337ce01292fe6f
});
</script>

{{-- ══════════════════════════════════════════════════════
     BS ↔ AD Converter
     ══════════════════════════════════════════════════════ --}}
<script>
(function () {
    'use strict';

    const bsMonthData = {
        1975:[31,31,32,32,31,30,30,29,30,29,30,30],1976:[31,32,31,32,31,30,30,30,29,29,30,31],
        1977:[30,32,31,32,31,30,30,30,29,30,29,31],1978:[31,31,32,31,31,31,30,29,30,29,30,30],
        1979:[31,31,32,32,31,30,30,29,30,29,30,30],1980:[31,32,31,32,31,30,30,30,29,29,30,31],
        1981:[31,31,31,32,31,31,29,30,30,29,30,30],1982:[31,31,32,31,31,31,30,29,30,29,30,30],
        1983:[31,31,32,32,31,30,30,29,30,29,30,30],1984:[31,32,31,32,31,30,30,30,29,29,30,31],
        1985:[31,31,31,32,31,31,29,30,30,29,30,30],1986:[31,31,32,31,31,31,30,29,30,29,30,30],
        1987:[31,32,31,32,31,30,30,29,30,29,30,30],1988:[31,32,31,32,31,30,30,30,29,29,30,31],
        1989:[31,31,31,32,31,31,30,29,30,29,30,30],1990:[31,31,32,31,31,31,30,29,30,29,30,30],
        1991:[31,32,31,32,31,30,30,29,30,29,30,30],1992:[31,32,31,32,31,30,30,30,29,30,29,31],
        1993:[31,31,31,32,31,31,30,29,30,29,30,30],1994:[31,31,32,31,31,31,30,29,30,29,30,30],
        1995:[31,32,31,32,31,30,30,30,29,29,30,30],1996:[31,32,31,32,31,30,30,30,29,30,29,31],
        1997:[31,31,32,31,31,31,30,29,30,29,30,30],1998:[31,31,32,31,31,31,30,29,30,29,30,30],
        1999:[31,32,31,32,31,30,30,30,29,29,30,31],2000:[30,32,31,32,31,30,30,30,29,30,29,31],
        2001:[31,31,32,31,31,31,30,29,30,29,30,30],2002:[31,31,32,32,31,30,30,29,30,29,30,30],
        2003:[31,32,31,32,31,30,30,30,29,29,30,31],2004:[30,32,31,32,31,30,30,30,29,30,29,31],
        2005:[31,31,32,31,31,31,30,29,30,29,30,30],2006:[31,31,32,32,31,30,30,29,30,29,30,30],
        2007:[31,32,31,32,31,30,30,30,29,29,30,31],2008:[31,31,31,32,31,31,29,30,30,29,29,31],
        2009:[31,31,32,31,31,31,30,29,30,29,30,30],2010:[31,31,32,32,31,30,30,29,30,29,30,30],
        2011:[31,32,31,32,31,30,30,30,29,29,30,31],2012:[31,31,31,32,31,31,29,30,30,29,30,30],
        2013:[31,31,32,31,31,31,30,29,30,29,30,30],2014:[31,31,32,32,31,30,30,29,30,29,30,30],
        2015:[31,32,31,32,31,30,30,30,29,29,30,31],2016:[31,31,31,32,31,31,29,30,30,29,30,30],
        2017:[31,31,32,31,31,31,30,29,30,29,30,30],2018:[31,32,31,32,31,30,30,29,30,29,30,30],
        2019:[31,32,31,32,31,30,30,30,29,30,29,31],2020:[31,31,31,32,31,31,30,29,30,29,30,30],
        2021:[31,31,32,31,31,31,30,29,30,29,30,30],2022:[31,32,31,32,31,30,30,30,29,29,30,30],
        2023:[31,32,31,32,31,30,30,30,29,30,29,31],2024:[31,31,31,32,31,31,30,29,30,29,30,30],
        2025:[31,31,32,31,31,31,30,29,30,29,30,30],2026:[31,32,31,32,31,30,30,30,29,29,30,31],
        2027:[30,32,31,32,31,30,30,30,29,30,29,31],2028:[31,31,32,31,31,31,30,29,30,29,30,30],
        2029:[31,31,32,31,32,30,30,29,30,29,30,30],2030:[31,32,31,32,31,30,30,30,29,29,30,31],
        2031:[30,32,31,32,31,30,30,30,29,30,29,31],2032:[31,31,32,31,31,31,30,29,30,29,30,30],
        2033:[31,31,32,32,31,30,30,29,30,29,30,30],2034:[31,32,31,32,31,30,30,30,29,29,30,31],
        2035:[30,32,31,32,31,31,29,30,30,29,29,31],2036:[31,31,32,31,31,31,30,29,30,29,30,30],
        2037:[31,31,32,32,31,30,30,29,30,29,30,30],2038:[31,32,31,32,31,30,30,30,29,29,30,31],
        2039:[31,31,31,32,31,31,29,30,30,29,30,30],2040:[31,31,32,31,31,31,30,29,30,29,30,30],
        2041:[31,31,32,32,31,30,30,29,30,29,30,30],2042:[31,32,31,32,31,30,30,30,29,29,30,31],
        2043:[31,31,31,32,31,31,29,30,30,29,30,30],2044:[31,31,32,31,31,31,30,29,30,29,30,30],
        2045:[31,32,31,32,31,30,30,29,30,29,30,30],2046:[31,32,31,32,31,30,30,30,29,29,30,31],
        2047:[31,31,31,32,31,31,30,29,30,29,30,30],2048:[31,31,32,31,31,31,30,29,30,29,30,30],
        2049:[31,32,31,32,31,30,30,30,29,29,30,30],2050:[31,32,31,32,31,30,30,30,29,30,29,31],
        2051:[31,31,31,32,31,31,30,29,30,29,30,30],2052:[31,31,32,31,31,31,30,29,30,29,30,30],
        2053:[31,32,31,32,31,30,30,30,29,29,30,30],2054:[31,32,31,32,31,30,30,30,29,30,29,31],
        2055:[31,31,32,31,31,31,30,29,30,29,30,30],2056:[31,31,32,31,32,30,30,29,30,29,30,30],
        2057:[31,32,31,32,31,30,30,30,29,29,30,31],2058:[30,32,31,32,31,30,30,30,29,30,29,31],
        2059:[31,31,32,31,31,31,30,29,30,29,30,30],2060:[31,31,32,32,31,30,30,29,30,29,30,30],
        2061:[31,32,31,32,31,30,30,30,29,29,30,31],2062:[30,32,31,32,31,31,29,30,29,30,29,31],
        2063:[31,31,32,31,31,31,30,29,30,29,30,30],2064:[31,31,32,32,31,30,30,29,30,29,30,30],
        2065:[31,32,31,32,31,30,30,30,29,29,30,31],2066:[31,31,31,32,31,31,29,30,30,29,29,31],
        2067:[31,31,32,31,31,31,30,29,30,29,30,30],2068:[31,31,32,32,31,30,30,29,30,29,30,30],
        2069:[31,32,31,32,31,30,30,30,29,29,30,31],2070:[31,31,31,32,31,31,29,30,30,29,30,30],
        2071:[31,31,32,31,31,31,30,29,30,29,30,30],2072:[31,32,31,32,31,30,30,29,30,29,30,30],
        2073:[31,32,31,32,31,30,30,30,29,29,30,31],2074:[31,31,31,32,31,31,30,29,30,29,30,30],
        2075:[31,31,32,31,31,31,30,29,30,29,30,30],2076:[31,32,31,32,31,30,30,30,29,29,30,30],
        2077:[31,32,31,32,31,30,30,30,29,30,29,31],2078:[31,31,31,32,31,31,30,29,30,29,30,30],
        2079:[31,31,32,31,31,31,30,29,30,29,30,30],2080:[31,32,31,32,31,30,30,30,29,29,30,30],
        2081:[31,32,31,32,31,30,30,30,29,30,29,31],2082:[31,31,31,32,31,31,30,29,30,29,30,30],
        2083:[31,31,32,31,31,31,30,29,30,29,30,30],2084:[31,32,31,32,31,30,30,30,29,29,30,31],
        2085:[30,32,31,32,31,30,30,30,29,30,29,31],2086:[31,31,32,31,31,31,30,29,30,29,30,30],
        2087:[31,31,32,32,31,30,30,29,30,29,30,30],2088:[31,32,31,32,31,30,30,30,29,29,30,31],
        2089:[30,32,31,32,31,31,29,30,29,30,29,31],2090:[31,31,32,31,31,31,30,29,30,29,30,30],
        2091:[31,31,32,32,31,30,30,29,30,29,30,30],2092:[31,32,31,32,31,30,30,30,29,29,30,31],
        2093:[31,31,31,32,31,31,29,30,30,29,29,31],2094:[31,31,32,31,31,31,30,29,30,29,30,30],
        2095:[31,31,32,32,31,30,30,29,30,29,30,30],2096:[31,32,31,32,31,30,30,30,29,29,30,31],
        2097:[30,32,31,32,31,31,29,30,30,29,29,31],2098:[31,31,32,31,31,31,30,29,30,29,30,30],
        2099:[31,31,32,32,31,30,30,29,30,29,30,30]
    };

    const bsStartYear = 2000, bsStartMonth = 1, bsStartDay = 1;
    const adRefDate = new Date(1943, 3, 14);

    function daysInBsMonth(y, m) { return (bsMonthData[y] || [])[m - 1] || 30; }
    function totalDaysInBsYear(y) { if (!bsMonthData[y]) return 365; return bsMonthData[y].reduce((s, d) => s + d, 0); }

    function countBsDays(year, month, day) {
        let total = 0;
        for (let y = bsStartYear; y < year; y++) total += totalDaysInBsYear(y);
        for (let m = 1; m < month; m++) total += daysInBsMonth(year, m);
        return total + (day - bsStartDay);
    }

    window.bsToAD = function (bsDateStr) {
        try {
            const [y, m, d] = bsDateStr.split('-').map(Number);
            if (!y || !m || !d) return '';
            const ad = new Date(adRefDate);
            ad.setDate(ad.getDate() + countBsDays(y, m, d));
            return ad.getFullYear() + '-' + String(ad.getMonth() + 1).padStart(2, '0') + '-' + String(ad.getDate()).padStart(2, '0');
        } catch { return ''; }
    };

    window.adToBS = function (adDateStr) {
        try {
            const adDate = new Date(adDateStr);
            if (isNaN(adDate.getTime())) return '';
            let days = Math.floor((adDate - adRefDate) / 86400000);
            let y = bsStartYear, m = bsStartMonth, d = bsStartDay + days;
            while (d > daysInBsMonth(y, m)) {
                d -= daysInBsMonth(y, m);
                if (++m > 12) { m = 1; y++; }
            }
            while (d < 1) {
                if (--m < 1) { m = 12; y--; }
                d += daysInBsMonth(y, m);
            }
            return y + '-' + String(m).padStart(2, '0') + '-' + String(d).padStart(2, '0');
        } catch { return ''; }
    };

    window.nepaliLibrariesReady = true;
})();
</script>

{{-- ══════════════════════════════════════════════════════
     Nepal Address Cascade Data
     ══════════════════════════════════════════════════════ --}}
<script>
const NEPAL_DATA = {
    Koshi:{Bhojpur:["Bhojpur Municipality","Shadananda Municipality","Hatuwagadhi Rural Municipality","Arun Rural Municipality","Tyamke Maiyum Rural Municipality","Ramprasad Rai Rural Municipality","Pauwadungma Rural Municipality","Salpasilichho Rural Municipality"],Dhankuta:["Dhankuta Municipality","Pakhribas Municipality","Mahalaxmi Municipality","Chhathar Jorpati Rural Municipality","Sangurigadhi Rural Municipality","Sahidbhumi Rural Municipality","Khalsa Rural Municipality"],Ilam:["Ilam Municipality","Deumai Municipality","Mai Municipality","Suryodaya Municipality","Maijogmai Rural Municipality","Sandakpur Rural Municipality","Chulachuli Rural Municipality","Mangsebung Rural Municipality","Rong Rural Municipality","Phakphokthum Rural Municipality"],Jhapa:["Arjundhara Municipality","Bhadrapur Municipality","Birtamod Municipality","Damak Municipality","Kankai Municipality","Mechinagar Municipality","Shivasataxi Municipality","Gauradaha Municipality","Haldibari Municipality","Buddhashanti Rural Municipality","Barhadashi Rural Municipality","Kabeli Rural Municipality","Kachankawal Rural Municipality","Gaurigunj Rural Municipality"],Khotang:["Diktel Rupakot Majhuwagadhi Municipality","Halesi Tuwachung Municipality","Khotehang Rural Municipality","Barahpokhari Rural Municipality","Kepilasgadhi Rural Municipality","Ainselukhark Rural Municipality","Lamidanda Rural Municipality","Sakela Rural Municipality","Rawabesi Rural Municipality","Diprung Chuichumma Rural Municipality"],Morang:["Biratnagar Metropolitan City","Rangeli Municipality","Sundarharaicha Municipality","Letang Municipality","Belbari Municipality","Pathari Shanischare Municipality","Ratuwamai Municipality","Jahada Rural Municipality","Budhiganga Rural Municipality","Gramthan Rural Municipality","Katahari Rural Municipality","Kerabari Rural Municipality","Miklajung Rural Municipality","Sunawarshi Rural Municipality","Uralabari Rural Municipality"],Okhaldhunga:["Siddhicharan Municipality","Molung Rural Municipality","Champadevi Rural Municipality","Chisankhugadhi Rural Municipality","Khijidemba Rural Municipality","Likhu Rural Municipality","Manebhanjyang Rural Municipality","Sunkoshi Rural Municipality"],Panchthar:["Phidim Municipality","Falgunanda Rural Municipality","Hilihang Rural Municipality","Kummayak Rural Municipality","Miklajung Rural Municipality","Phalelung Rural Municipality","Tumbewa Rural Municipality","Yashokchhap Rural Municipality"],Sankhuwasabha:["Chainpur Municipality","Dharmadevi Municipality","Khandbari Municipality","Madi Municipality","Panchkhapan Municipality","Chichila Rural Municipality","Makalu Rural Municipality","Sabhapokhari Rural Municipality","Silichong Rural Municipality"],Solukhumbu:["Solududhkunda Municipality","Salleri Municipality","Thulung Dudhkoshi Rural Municipality","Sotang Rural Municipality","Mahakulung Rural Municipality","Khumbu Pasanglhamu Rural Municipality","Likhupike Rural Municipality","Nechasalyan Rural Municipality"],Sunsari:["Dharan Sub-Metropolitan City","Itahari Sub-Metropolitan City","Inaruwa Municipality","Duhabi Municipality","Barahakshetra Municipality","Ramdhuni Municipality","Harinagara Rural Municipality","Koshi Rural Municipality","Gadhi Rural Municipality","Barju Rural Municipality"],Taplejung:["Phungling Municipality","Sidingba Rural Municipality","Aathrai Tribeni Rural Municipality","Meringden Rural Municipality","Mikwakhola Rural Municipality","Pathibhara Yangwarak Rural Municipality","Sirijangha Rural Municipality","Phaktanglung Rural Municipality"],Terhathum:["Myanglung Municipality","Laligurans Municipality","Aathrai Rural Municipality","Chhathar Rural Municipality","Phedap Rural Municipality"]},
    Madhesh:{Bara:["Kalaiya Sub-Metropolitan City","Jitpur Simara Sub-Metropolitan City","Nijgadh Municipality","Mahagadhimai Municipality","Simraungadh Municipality","Pacharauta Municipality","Prasauni Rural Municipality","Bishrampur Rural Municipality","Devtal Rural Municipality","Pheta Rural Municipality","Kaudena Rural Municipality","Adarshkotwal Rural Municipality","Suwarna Rural Municipality","Baragadhi Rural Municipality","Kolhabi Rural Municipality"],Dhanusha:["Janakpur Sub-Metropolitan City","Mithila Municipality","Dhanusha Municipality","Sabaila Municipality","Kamala Municipality","Mithila Bihari Municipality","Dhanushadham Municipality","Bideha Municipality","Aurahi Rural Municipality","Bateshwar Rural Municipality","Chhireshwarnath Rural Municipality","Dhanauji Rural Municipality","Ganeshman Charnath Rural Municipality","Hansapur Rural Municipality","Hans Rupa Rural Municipality","Janaknandini Rural Municipality","Lakshminiya Rural Municipality","Mukhiyapatti Musaharmiya Rural Municipality","Nagarain Rural Municipality","Shankarpur Rural Municipality"],Mahottari:["Jaleshwar Municipality","Gaushala Municipality","Matihani Municipality","Bardibas Municipality","Bhangaha Municipality","Loharpatti Municipality","Manra Siswa Municipality","Samsi Municipality","Sonama Rural Municipality","Ekdara Rural Municipality","Mahottari Rural Municipality","Pipra Rural Municipality","Ramgopalpur Rural Municipality"],Parsa:["Birgunj Metropolitan City","Bahudarmai Municipality","Parsagadhi Municipality","Pokhariya Municipality","Bindabasini Rural Municipality","Chhipaharmai Rural Municipality","Dhobini Rural Municipality","Jirabhawani Rural Municipality","Kalikamai Rural Municipality","Pakaha Mainpur Rural Municipality","Paterwas Rural Municipality","Paterwa Sugauli Rural Municipality","Sakhuwa Prasauni Rural Municipality","Thori Rural Municipality"],Rautahat:["Chandrapur Municipality","Gaur Municipality","Baudha Rural Municipality","Garuda Rural Municipality","Gujara Rural Municipality","Katahariya Rural Municipality","Madhav Narayan Rural Municipality","Maulapur Rural Municipality","Paroha Rural Municipality","Phatuwa Bijayapur Rural Municipality","Rajdevi Rural Municipality","Rajpur Rural Municipality","Brindaban Rural Municipality","Dumarwana Rural Municipality","Ishanath Rural Municipality","Dewahi Gonahi Rural Municipality","Yamunamai Rural Municipality"],Saptari:["Rajbiraj Municipality","Kanchanrup Municipality","Surunga Municipality","Agnisair Krishna Savaran Rural Municipality","Balan-Bihul Rural Municipality","Bishnupur Rural Municipality","Chhinnamasta Rural Municipality","Dakneshwari Rural Municipality","Hanumannagar Kankalini Municipality","Khadak Rural Municipality","Mahadewa Rural Municipality","Rajgadh Rural Municipality","Rupani Rural Municipality","Shambhunath Municipality","Tirahut Rural Municipality","Saptakoshi Rural Municipality"],Sarlahi:["Lalbandi Municipality","Haripur Municipality","Hariwan Municipality","Barahathawa Municipality","Ishworpur Municipality","Malangawa Municipality","Bagmati Rural Municipality","Ballara Rural Municipality","Brahampuri Rural Municipality","Chandranagar Rural Municipality","Chakraghatta Rural Municipality","Dhankaul Rural Municipality","Godaita Municipality","Haripurwa Rural Municipality","Kabilasi Rural Municipality","Parsa Rural Municipality","Ramnagar Rural Municipality"],Siraha:["Lahan Municipality","Siraha Municipality","Golbazar Municipality","Mirchaiya Municipality","Kalyanpur Municipality","Sukhipur Municipality","Aurahi Rural Municipality","Bishnupur Rural Municipality","Bariyarpatti Rural Municipality","Dhangadhimai Municipality","Karjanha Rural Municipality","Lakshmipur Patari Rural Municipality","Nawarajpur Rural Municipality","Sakhuwanankarkatti Rural Municipality","Shyam Sundar Madi Rural Municipality"]},
    Bagmati:{Bhaktapur:["Bhaktapur Municipality","Changunarayan Municipality","Madhyapur Thimi Municipality","Suryabinayak Municipality"],Chitwan:["Bharatpur Metropolitan City","Ratnanagar Municipality","Ichchhakamana Rural Municipality","Kalika Municipality","Khairahani Municipality","Madi Municipality","Rapti Municipality","Rapti Sonari Rural Municipality"],Dhading:["Nilkantha Municipality","Benighat Rorang Rural Municipality","Gajuri Rural Municipality","Galchhi Rural Municipality","Gangajamuna Rural Municipality","Jwalamukhi Rural Municipality","Khaniyabas Rural Municipality","Netrawati Daijee Rural Municipality","Rubi Valley Rural Municipality","Siddhalek Rural Municipality","Thakre Rural Municipality","Tripura Sundari Rural Municipality"],Dolakha:["Bhimeshwar Municipality","Jiri Municipality","Bigu Rural Municipality","Baiteshwar Rural Municipality","Gaurishankar Rural Municipality","Kalinchok Rural Municipality","Melung Rural Municipality","Shailung Rural Municipality","Tamakoshi Rural Municipality"],Kathmandu:["Kathmandu Metropolitan City","Kirtipur Municipality","Budhanilkantha Municipality","Chandragiri Municipality","Dakshinkali Municipality","Gokarneshwar Municipality","Kageshwari Manohara Municipality","Nagarjun Municipality","Shankharapur Municipality","Tarakeshwar Municipality","Tokha Municipality"],Kavrepalanchok:["Banepa Municipality","Dhulikhel Municipality","Panauti Municipality","Namobuddha Municipality","Mandandeupur Municipality","Panchkhal Municipality","Bethanchok Rural Municipality","Bhumlu Rural Municipality","Chaurideurali Rural Municipality","Khanikhola Rural Municipality","Mahabharat Rural Municipality","Roshi Rural Municipality","Temal Rural Municipality"],Lalitpur:["Lalitpur Metropolitan City","Godawari Municipality","Mahalaxmi Municipality","Konjyosom Rural Municipality","Bagmati Rural Municipality"],Makwanpur:["Hetauda Sub-Metropolitan City","Thaha Municipality","Bagmati Rural Municipality","Bakaiya Rural Municipality","Bhimphedi Rural Municipality","Indrasarowar Rural Municipality","Kailash Rural Municipality","Makawanpurgadhi Rural Municipality","Manahari Rural Municipality","Raksirang Rural Municipality"],Nuwakot:["Bidur Municipality","Belkotgadhi Municipality","Kakani Rural Municipality","Dupcheshwar Rural Municipality","Meghang Rural Municipality","Myagang Rural Municipality","Panchakanya Rural Municipality","Shivapuri Rural Municipality","Suryagadhi Rural Municipality","Tadi Rural Municipality","Tarkeshwar Rural Municipality","Likhu Rural Municipality"],Ramechhap:["Manthali Municipality","Ramechhap Municipality","Doramba Rural Municipality","Gokulganga Rural Municipality","Khandadevi Rural Municipality","Likhu Tamakoshi Rural Municipality","Saipatithan Rural Municipality","Sunapati Rural Municipality"],Rasuwa:["Kalika Rural Municipality","Naukunda Rural Municipality","Gosaikunda Rural Municipality","Aamachhodingmo Rural Municipality","Uttargaya Rural Municipality"],Sindhuli:["Kamalamai Municipality","Dudhauli Municipality","Golanjor Rural Municipality","Ghyanglekh Rural Municipality","Hariharpurgadhi Rural Municipality","Marin Rural Municipality","Phikkal Rural Municipality","Sunkoshi Rural Municipality","Tinpatan Rural Municipality"],Sindhupalchok:["Chautara Sangachokgadhi Municipality","Melamchi Municipality","Balephi Rural Municipality","Barhabise Rural Municipality","Bhotekoshi Rural Municipality","Helambu Rural Municipality","Indrawati Rural Municipality","Jugal Rural Municipality","Lisankhu Pakhar Rural Municipality","Panchpokhari Thangpal Rural Municipality","Sunkoshi Rural Municipality","Tripurasundari Rural Municipality"]},
    Gandaki:{Baglung:["Baglung Municipality","Galkot Municipality","Dhorpatan Municipality","Taman Khola Rural Municipality","Nisikhola Rural Municipality","Jaimuni Municipality","Bareng Rural Municipality","Kanthekhola Rural Municipality","Tatopani Rural Municipality"],Gorkha:["Gorkha Municipality","Palungtar Municipality","Arughat Rural Municipality","Arpak Dudhapokhara Rural Municipality","Bhimsen Rural Municipality","Barpak Sulikot Rural Municipality","Dharche Rural Municipality","Gandaki Rural Municipality","Ajirkot Rural Municipality","Chum Nubri Rural Municipality","Sahid Lakhan Rural Municipality","Siranchok Rural Municipality","Tsum Nubri Rural Municipality"],Kaski:["Pokhara Metropolitan City","Annapurna Rural Municipality","Machhapuchchhre Rural Municipality","Madi Rural Municipality","Rupa Rural Municipality"],Lamjung:["Besisahar Municipality","Rainas Municipality","Sundarbazar Municipality","Dordi Rural Municipality","Dudhpokhari Rural Municipality","Kwholasothar Rural Municipality","Marsyangdi Rural Municipality","Madhya Nepal Rural Municipality","Chamje Rural Municipality"],Manang:["Chame Rural Municipality","Narphu Rural Municipality","Nasong Rural Municipality"],Mustang:["Gharpajhong Rural Municipality","Lomanthang Rural Municipality","Thasang Rural Municipality","Waragung Muktikhola Rural Municipality","Dalome Rural Municipality"],Myagdi:["Beni Municipality","Annapurna Rural Municipality","Dhaulagiri Rural Municipality","Mangala Rural Municipality","Malika Rural Municipality","Raghuganga Rural Municipality"],Nawalpur:["Kawasoti Municipality","Devchuli Municipality","Bardaghat Municipality","Gaindakot Municipality","Hupsekot Municipality","Binayi Tribeni Rural Municipality","Bulingtar Rural Municipality","Madhyabindu Municipality","Palhi Nandan Rural Municipality","Pratappur Rural Municipality","Rainas Rural Municipality","Sarawal Rural Municipality"],Parbat:["Kushma Municipality","Phalewas Municipality","Airawati Rural Municipality","Bihadi Rural Municipality","Jaljala Rural Municipality","Mahashila Rural Municipality","Modi Rural Municipality","Painyu Rural Municipality"],Syangja:["Waling Municipality","Putalibazar Municipality","Galyang Municipality","Bhirkot Municipality","Arjunchaupari Rural Municipality","Biruwa Rural Municipality","Aandhikhola Rural Municipality","Harinas Rural Municipality","Kaligandaki Rural Municipality","Phedikhola Rural Municipality"],Tanahun:["Damauli Municipality","Bhimad Municipality","Byas Municipality","Shuklagandaki Municipality","Bandipure Rural Municipality","Ghiring Rural Municipality","Myagde Rural Municipality","Rhishing Rural Municipality","Devghat Rural Municipality","Anbukhaireni Rural Municipality"]},
    Lumbini:{Arghakhanchi:["Sandhikharka Municipality","Sitganga Municipality","Chhatradev Rural Municipality","Bhumekasthan Rural Municipality","Malarani Rural Municipality","Panini Rural Municipality","Shivarajpur Rural Municipality"],Banke:["Nepalgunj Sub-Metropolitan City","Kohalpur Municipality","Narainapur Rural Municipality","Khajura Rural Municipality","Janaki Rural Municipality","Raptisonari Rural Municipality","Duduwa Rural Municipality"],Bardiya:["Gulariya Municipality","Rajapur Municipality","Madhuwan Municipality","Barbardiya Municipality","Thakurbaba Municipality","Badhaiyatal Rural Municipality","Bansgadhi Municipality","Geruwa Rural Municipality"],Dang:["Tulsipur Sub-Metropolitan City","Ghorahi Sub-Metropolitan City","Lamahi Municipality","Shantinagar Rural Municipality","Babai Rural Municipality","Bangalachuli Rural Municipality","Gadhawa Rural Municipality","Rajpur Rural Municipality","Rapti Rural Municipality","Dangisharan Rural Municipality"],Gulmi:["Musikot Municipality","Resunga Municipality","Isma Rural Municipality","Chatrakot Rural Municipality","Chandrakot Rural Municipality","Kaligandaki Rural Municipality","Madane Rural Municipality","Malika Rural Municipality","Ruru Rural Municipality","Satyawati Rural Municipality","Gulmi Durbar Rural Municipality"],Kapilvastu:["Banganga Municipality","Buddhabhumi Municipality","Kapilvastu Municipality","Krishnanagar Municipality","Maharajgunj Municipality","Shivaraj Municipality","Bijaynagar Rural Municipality","Motipur Rural Municipality","Suddhodhan Rural Municipality","Yashodhara Rural Municipality"],Palpa:["Tansen Municipality","Rampur Municipality","Rainadevi Chhahara Rural Municipality","Bagnaskali Rural Municipality","Mathagadhi Rural Municipality","Nisdi Rural Municipality","Purbakhola Rural Municipality","Rambha Rural Municipality","Ribdikot Rural Municipality","Tinau Rural Municipality"],Pyuthan:["Pyuthan Municipality","Swargadwari Municipality","Ayirawati Rural Municipality","Gaumukhi Rural Municipality","Jhimruk Rural Municipality","Lungri Rural Municipality","Mallarani Rural Municipality","Mandavi Rural Municipality","Naubahini Rural Municipality","Sarumarani Rural Municipality"],Rolpa:["Rolpa Municipality","Runtigadhi Rural Municipality","Sunchhahari Rural Municipality","Thawang Rural Municipality","Tribeni Rural Municipality","Madi Rural Municipality","Lungri Rural Municipality","Pariwartan Rural Municipality","Gangadev Rural Municipality"],Rupandehi:["Butwal Sub-Metropolitan City","Siddharthanagar Sub-Metropolitan City","Devdaha Municipality","Lumbini Sanskritik Municipality","Marchawar Municipality","Omsatiya Municipality","Saljhandi Rural Municipality","Sammarimai Rural Municipality","Rohini Rural Municipality","Kanchan Rural Municipality","Kotahimai Rural Municipality","Gaidahawa Rural Municipality","Sainamaina Municipality","Tillotama Municipality","Mayadevi Rural Municipality","Siyari Rural Municipality","Sudhdhodhan Rural Municipality"]},
    Karnali:{Dailekh:["Narayan Municipality","Chamunda Bindrasaini Municipality","Dullu Municipality","Aathabis Municipality","Bhairabi Municipality","Gurans Rural Municipality","Mahabu Rural Municipality","Naumule Rural Municipality","Dungeshwar Rural Municipality","Bhagawatimai Rural Municipality","Thatikandh Rural Municipality"],Dolpa:["Thuli Bheri Municipality","Tripurasundari Municipality","Dolpo Buddha Rural Municipality","Kaike Rural Municipality","Mudkechula Rural Municipality","She Phoksundo Rural Municipality","Jagadulla Rural Municipality","Chharka Tangsong Rural Municipality"],Humla:["Simkot Rural Municipality","Kharpunath Rural Municipality","Adanchuli Rural Municipality","Chankheli Rural Municipality","Namkha Rural Municipality","Sarkegad Rural Municipality","Tanjakot Rural Municipality"],Jajarkot:["Bheri Municipality","Chhedagad Municipality","Barekot Rural Municipality","Junichande Rural Municipality","Kuse Rural Municipality","Nalagad Municipality","Shiwalaya Rural Municipality"],Jumla:["Chandannath Municipality","Sinja Rural Municipality","Tatopani Rural Municipality","Guthichaur Rural Municipality","Kankasundari Rural Municipality","Patarasi Rural Municipality","Hima Rural Municipality"],Kalikot:["Manma Municipality","Sanni Triveni Rural Municipality","Raskot Municipality","Bajura Rural Municipality","Mahawai Rural Municipality","Palata Rural Municipality","Shubha Kalika Municipality","Pachaljharana Rural Municipality","Tilagufa Municipality","Khandachakra Municipality"],Mugu:["Chhayanath Rara Municipality","Mugum Karmarong Rural Municipality","Khatyad Rural Municipality","Soru Rural Municipality"],Salyan:["Sharada Municipality","Bangad Kupinde Municipality","Bagchaur Municipality","Kalimati Rural Municipality","Darma Rural Municipality","Kumakh Rural Municipality","Siddha Kumakh Rural Municipality","Triveni Rural Municipality"],Surkhet:["Birendranagar Municipality","Bheriganga Municipality","Gurbhakot Municipality","Lekbesi Municipality","Panchpuri Municipality","Barahtal Rural Municipality","Simta Rural Municipality","Chaukune Rural Municipality","Chingad Rural Municipality"]},
    Sudurpashchim:{Achham:["Mangalsen Municipality","Kamalbazar Municipality","Mellekh Rural Municipality","Bannigadhi Jayagadh Rural Municipality","Ramaroshan Rural Municipality","Sanphebagar Municipality","Dhakari Rural Municipality","Chaurpati Rural Municipality","Turmakhand Rural Municipality"],Baitadi:["Dasharathchand Municipality","Purnagiri Municipality","Sigas Rural Municipality","Dogadakedar Rural Municipality","Purchaudi Municipality","Dilasaini Rural Municipality","Melauli Rural Municipality","Surnaya Rural Municipality","Patan Rural Municipality","Shivnath Rural Municipality"],Bajhang:["Jaya Prithvi Municipality","Bungal Municipality","Talkot Municipality","Masta Rural Municipality","Kuldevmandu Rural Municipality","Saipal Rural Municipality","Khaptadchhanna Rural Municipality","Thalara Rural Municipality","Surma Rural Municipality","Chhededaha Rural Municipality","Bithadchir Rural Municipality","Durgathali Rural Municipality","Kanda Rural Municipality"],Bajura:["Badimalika Municipality","Budhiganga Municipality","Budhinanda Municipality","Gaumul Rural Municipality","Himali Rural Municipality","Jagannath Rural Municipality","Khaptad Chhanna Rural Municipality","Swami Kartik Rural Municipality","Triveni Rural Municipality"],Dadeldhura:["Amargadhi Municipality","Aalital Rural Municipality","Ajayameru Rural Municipality","Bhageshwar Rural Municipality","Ganyapadhura Rural Municipality","Nawadurga Rural Municipality","Parashuram Municipality"],Darchula:["Shailyashikhar Municipality","Malikarjun Rural Municipality","Apihimal Rural Municipality","Byash Rural Municipality","Naugad Rural Municipality","Duhu Rural Municipality","Lekam Rural Municipality","Marma Rural Municipality","Mahakali Municipality"],Dothi:["Shikhar Municipality","Dipayal Silgadhi Municipality","Badikedar Rural Municipality","Bogtan Phago Rural Municipality","Jorayal Rural Municipality","K.I.Singh Rural Municipality","Purbichauki Rural Municipality","Aadarsha Rural Municipality","Sayal Rural Municipality"],Kailali:["Dhangadhi Sub-Metropolitan City","Tikapur Municipality","Bhajani Municipality","Ghodaghodi Municipality","Godawari Municipality","Kailari Rural Municipality","Bardagoriya Rural Municipality","Chure Rural Municipality","Gauriganga Municipality","Joshipur Rural Municipality","Mohanyal Rural Municipality","Phatepur Rural Municipality","Janaki Rural Municipality","Lamkichuha Municipality"],Kanchanpur:["Bhimdatta Municipality","Belauri Municipality","Bedkot Municipality","Punarbas Municipality","Shuklaphanta Municipality","Beldandi Rural Municipality","Laljhadi Rural Municipality","Mahakali Municipality","Pipaladi Rural Municipality"]}
};

function populateSelect(sel, opts, placeholder) {
    sel.innerHTML = `<option value="">${placeholder}</option>`;
    opts.forEach(o => { const el = document.createElement('option'); el.value = el.textContent = o; sel.appendChild(el); });
}
function cascadeDistrict(prefix) {
    const prov = document.getElementById(prefix+'_province').value;
    const distSel = document.getElementById(prefix+'_district');
    const munSel  = document.getElementById(prefix+'_municipality');
    munSel.innerHTML  = '<option value="">-- Select Municipality --</option>'; munSel.disabled  = true;
    if (prov && NEPAL_DATA[prov]) {
        populateSelect(distSel, Object.keys(NEPAL_DATA[prov]).sort(), '-- Select District --');
        distSel.disabled = false;
    } else { distSel.innerHTML = '<option value="">-- Select District --</option>'; distSel.disabled = true; }
}
function cascadeMunicipality(prefix) {
    const prov = document.getElementById(prefix+'_province').value;
    const dist = document.getElementById(prefix+'_district').value;
    const munSel = document.getElementById(prefix+'_municipality');
    if (prov && dist && NEPAL_DATA[prov]?.[dist]) {
        populateSelect(munSel, NEPAL_DATA[prov][dist], '-- Select Municipality --'); munSel.disabled = false;
    } else { munSel.innerHTML = '<option value="">-- Select Municipality --</option>'; munSel.disabled = true; }
}
function toggleSameAsPermanent() {
    const checked = document.getElementById('same_as_permanent').checked;
    const mf = document.getElementById('mailing_fields');
    if (checked) {
        document.getElementById('mailing_province').value = document.getElementById('permanent_province').value;
        cascadeDistrict('mailing');
        setTimeout(() => {
            document.getElementById('mailing_district').value = document.getElementById('permanent_district').value;
            cascadeMunicipality('mailing');
            setTimeout(() => { document.getElementById('mailing_municipality').value = document.getElementById('permanent_municipality').value; }, 50);
        }, 50);
        ['ward','tole','house_number'].forEach(f => {
            document.getElementById('mailing_'+f).value = document.getElementById('permanent_'+f)?.value || '';
        });
        mf.style.opacity = '0.5'; mf.style.pointerEvents = 'none';
    } else { mf.style.opacity = '1'; mf.style.pointerEvents = ''; }
}

(function() {
    const op = '{{ old("permanent_province", $applicationform->permanent_province ?? "") }}';
    const od = '{{ old("permanent_district",  $applicationform->permanent_district  ?? "") }}';
    const om = '{{ old("permanent_municipality", $applicationform->permanent_municipality ?? "") }}';
    const mp = '{{ old("mailing_province",  $applicationform->mailing_province  ?? "") }}';
    const md = '{{ old("mailing_district",  $applicationform->mailing_district   ?? "") }}';
    const mm = '{{ old("mailing_municipality", $applicationform->mailing_municipality ?? "") }}';
    if (op) { cascadeDistrict('permanent'); if (od) { document.getElementById('permanent_district').value=od; cascadeMunicipality('permanent'); if (om) document.getElementById('permanent_municipality').value=om; } }
    if (mp) { cascadeDistrict('mailing');  if (md) { document.getElementById('mailing_district').value=md;  cascadeMunicipality('mailing');  if (mm) document.getElementById('mailing_municipality').value=mm; } }
})();
</script>

{{-- ══════════════════════════════════════════════════════
     Main Form Logic
     ══════════════════════════════════════════════════════ --}}
<script>
document.addEventListener('DOMContentLoaded', function () {

    // ── Conditional file requirements ──────────────────────────
    function conditionalFile(triggerEl, fileEl, labelEl, triggerValues) {
        if (!triggerEl || !fileEl) return;
        function update() {
            const match = triggerValues.includes(triggerEl.value);
            if (match) {
                fileEl.setAttribute('required', 'required');
                if (labelEl && !labelEl.querySelector('.text-danger')) labelEl.innerHTML += ' <span class="text-danger">*</span>';
            } else {
                fileEl.removeAttribute('required');
                const sp = labelEl?.querySelector('.text-danger'); if (sp) sp.remove();
            }
        }
        update(); triggerEl.addEventListener('change', update);
    }

    conditionalFile(
        document.getElementById('noc_employee'),
        document.getElementById('noc_id_card'),
        document.getElementById('noc_id_card')?.closest('.col-md-6')?.querySelector('label'),
        ['yes']
    );
    conditionalFile(
        document.getElementById('physical_disability'),
        document.getElementById('disability_certificate'),
        document.getElementById('disability_certificate')?.closest('.col-md-4')?.querySelector('label'),
        ['yes']
    );
    conditionalFile(
        document.getElementById('ethnic_group'),
        document.getElementById('ethnic_certificate'),
        document.getElementById('ethnic_certificate')?.closest('.col-md-6')?.querySelector('label'),
        ['Dalit','Janajati','Madhesi']
    );

    // ── Tabs & progress ───────────────────────────────────────
    let currentStep = 1;
    const totalSteps = 8;
    const hasErrors = {{ $errors->any() ? 'true' : 'false' }};
    const form = document.getElementById('applicationform');

    const autoSaveIndicator = document.createElement('div');
    autoSaveIndicator.id = 'autosave-indicator';
    autoSaveIndicator.style.cssText = 'position:fixed;top:80px;right:20px;padding:12px 24px;border-radius:8px;z-index:9999;display:none;font-weight:600;box-shadow:0 4px 12px rgba(0,0,0,.15);';
    document.body.appendChild(autoSaveIndicator);

    function showAutoSaveStatus(msg, type = 'info') {
        autoSaveIndicator.textContent = msg;
        autoSaveIndicator.className = `alert alert-${type} mb-0`;
        autoSaveIndicator.style.display = 'block';
        setTimeout(() => { autoSaveIndicator.style.display = 'none'; }, 3000);
    }

    function updateTabsAndProgress() {
        document.querySelectorAll('.tab-item').forEach((tab, i) => {
            tab.classList.remove('active', 'completed');
            if (i + 1 < currentStep) tab.classList.add('completed');
            else if (i + 1 === currentStep) tab.classList.add('active');
        });
        const line = document.querySelector('.progress-line');
        if (line) line.style.width = (((currentStep - 1) / (totalSteps - 1)) * 100) + '%';
    }

    function showStep(step) {
        document.querySelectorAll('.step').forEach(s => s.classList.add('d-none'));
        const el = document.getElementById('step' + step);
        if (el) { el.classList.remove('d-none'); el.classList.add('active'); }
        currentStep = step;
<<<<<<< HEAD
        if (step === 7) {
            populatePreview();
            populateExperiencePreview();
        }
=======
        if (step === 7) populatePreview();
>>>>>>> efe7d213166d7eb2c3aef5455d337ce01292fe6f
        updateTabsAndProgress();
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    function validateStep(step) {
        const stepEl = document.getElementById('step' + step);
        if (!stepEl) return true;
        const wasHidden = stepEl.classList.contains('d-none');
        if (wasHidden) { stepEl.classList.remove('d-none'); stepEl.style.visibility = 'hidden'; }
        stepEl.querySelectorAll('.is-invalid, .invalid-feedback').forEach(el => {
            el.classList.remove('is-invalid'); if (el.classList.contains('invalid-feedback')) el.remove();
        });
        let isValid = true, firstInvalid = null;
        stepEl.querySelectorAll('input[required],select[required],textarea[required]').forEach(field => {
<<<<<<< HEAD
            // Skip disabled fields (e.g. cascading district/municipality selects not yet populated)
            if (field.disabled) return;
            // Skip fields inside any hidden ancestor div (covers _wrapper divs and d-none parents)
            const hiddenParent = field.parentElement?.closest('.d-none');
            if (hiddenParent && hiddenParent !== stepEl) return;
            if (field.parentElement?.closest('.conditionally-hidden')) return;
            if (field.type === 'checkbox') {
                // Skip terms_agree when validating a hidden step (handled separately on submit)
                if (field.id === 'terms_agree' && wasHidden) return;
=======
            if (field.parentElement?.closest('.conditionally-hidden')) return;
            if (field.type === 'checkbox') {
>>>>>>> efe7d213166d7eb2c3aef5455d337ce01292fe6f
                if (!field.checked) { isValid = false; field.classList.add('is-invalid'); addErr(field, 'You must agree before continuing'); if (!firstInvalid) firstInvalid = field; } return;
            }
            if (field.type === 'file') {
                if (field.files.length === 0) { isValid = false; field.classList.add('is-invalid'); addErr(field, 'This file is required'); if (!firstInvalid) firstInvalid = field; } return;
            }
            if (!field.value.trim()) { isValid = false; field.classList.add('is-invalid'); addErr(field, 'This field is required'); if (!firstInvalid) firstInvalid = field; }
        });
        if (wasHidden) { stepEl.classList.add('d-none'); stepEl.style.visibility = ''; }
        if (!isValid && firstInvalid && !wasHidden) { firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' }); firstInvalid.focus(); showAutoSaveStatus('⚠ Please fill all required fields', 'warning'); }
        return isValid;
    }

    function addErr(field, msg) {
        const d = document.createElement('div'); d.className = 'invalid-feedback'; d.textContent = msg; field.parentNode.appendChild(d);
    }

    document.querySelectorAll('.tab-item').forEach(tab => {
        tab.addEventListener('click', e => {
            e.preventDefault(); e.stopPropagation();
            const target = parseInt(tab.dataset.step);
            if (target === currentStep) return;
            if (target < currentStep) { showStep(target); return; }
            let ok = true;
            for (let i = currentStep; i < target; i++) { if (!validateStep(i)) { ok = false; showAutoSaveStatus(`⚠ Please complete Step ${i} first`, 'danger'); break; } }
            if (ok) showStep(target);
        });
    });

    document.querySelectorAll('.next-btn').forEach(btn => {
        btn.addEventListener('click', e => {
            e.preventDefault(); e.stopPropagation();
            if (!validateStep(currentStep)) return;
            if (currentStep < totalSteps) showStep(currentStep + 1);
        });
    });

    document.querySelectorAll('.prev-btn').forEach(btn => {
        btn.addEventListener('click', e => {
            e.preventDefault(); e.stopPropagation();
            if (currentStep > 1) showStep(currentStep - 1);
        });
    });

    ['religion','community','ethnic_group'].forEach(id => {
        const sel = document.getElementById(id), other = document.getElementById(id + '_other');
        if (!sel || !other) return;
        const toggle = () => { const show = sel.value === 'Other'; other.classList.toggle('d-none', !show); show ? other.setAttribute('required','required') : (other.removeAttribute('required'), other.value=''); };
        sel.addEventListener('change', toggle); toggle();
    });

    if (hasErrors) {
        setTimeout(() => { const inv = document.querySelector('.is-invalid'); if (inv) { const se = inv.closest('.step'); if (se) { showStep(parseInt(se.id.replace('step',''))); return; } } showStep(1); }, 150);
    } else { showStep(1); }

    form.addEventListener('submit', e => {
<<<<<<< HEAD
        let termsChecked = document.getElementById('terms_agree')?.checked;
        for (let i = 1; i <= totalSteps; i++) { if (!validateStep(i)) { showStep(i); e.preventDefault(); showAutoSaveStatus('⚠ Please complete all required fields', 'danger'); return; } }
        // Re-verify terms_agree since validateStep on hidden step 7 may reset it
        if (!termsChecked) {
            showStep(7);
            e.preventDefault();
            const termsEl = document.getElementById('terms_agree');
            if (termsEl) { termsEl.classList.add('is-invalid'); }
            showAutoSaveStatus('⚠ Please agree to the declaration', 'danger');
            return;
        }
=======
        for (let i = 1; i <= totalSteps; i++) { if (!validateStep(i)) { showStep(i); e.preventDefault(); showAutoSaveStatus('⚠ Please complete all required fields', 'danger'); return; } }
>>>>>>> efe7d213166d7eb2c3aef5455d337ce01292fe6f
        showAutoSaveStatus('📤 Submitting...', 'light');
    });

    // ==================== PREVIEW ====================
    function populatePreview() {
        function val(id) { return document.getElementById(id)?.value || '-'; }
        function set(id, value) { const el = document.getElementById(id); if (el) el.textContent = value; }

        set('p_name_english', val('name_english'));
        set('p_name_nepali', val('name_nepali'));
        set('p_email', val('email'));
        set('p_birth_date_ad', val('birth_date_ad'));
        set('p_birth_date_bs', val('birth_date_bs'));
        set('p_phone', val('phone'));
        set('p_advertisement_no', val('advertisement_no'));
        set('p_applying_position', val('applying_position'));
        set('p_department', val('department'));
        set('p_age', val('age'));
        set('p_alternate_phone_number', val('alternate_phone_number'));
        set('p_gender', val('gender'));
        set('p_marital_status', val('marital_status'));
        set('spouse_name_english', val('spouse_name_english'));
        set('p_spouse_nationality', val('spouse_nationality'));
        set('p_citizenship_number', val('citizenship_number'));
        set('p_citizenship_issue_date_bs', val('citizenship_issue_date_bs'));
        set('p_citizenship_issue_district', val('citizenship_issue_district'));
        set('p_father_name_english', val('father_name_english'));
        set('p_mother_name_english', val('mother_name_english'));
        set('p_grandfather_name_english', val('grandfather_name_english'));
        set('p_father_qualification', val('father_qualification'));
        set('p_mother_qualification', val('mother_qualification'));
        set('p_parent_occupation', val('parent_occupation'));
        set('p_blood_group', val('blood_group'));
        set('p_nationality', val('nationality'));
        set('p_noc_employee', val('noc_employee'));
        set('p_religion', val('religion'));
        set('p_community', val('community'));
        set('p_ethnic_group', val('ethnic_group'));
        set('p_mother_tongue', val('mother_tongue'));
        set('p_employment_status', val('employment_status'));
        set('p_physical_disability', val('physical_disability'));

        set('p_permanent_address',
            val('permanent_province') + ', ' + val('permanent_district') + ', ' +
            val('permanent_municipality') + ' - ' + val('permanent_ward'));
        set('p_mailing_address',
            val('mailing_province') + ', ' + val('mailing_district') + ', ' +
            val('mailing_municipality') + ' - ' + val('mailing_ward'));

        set('p_education_level', val('education_level'));
        set('p_field_of_study', val('field_of_study'));
        set('p_institution_name', val('institution_name'));
        set('p_graduation_year', val('graduation_year'));
        set('p_has_work_experience', val('has_work_experience'));
        // set('p_years_of_experience', val('years_of_experience'));
        // set('p_previous_organization', val('previous_organization'));
        // set('p_previous_position', val('previous_position'));

        function previewFile(containerId, inputName) {
            const input = document.querySelector(`input[name="${inputName}"]`);
            const container = document.getElementById(containerId);
            if (!container) return;
            container.innerHTML = '';
            if (!input || !input.files || input.files.length === 0) {
                container.textContent = 'Not Uploaded';
                return;
            }
            const file = input.files[0];
            const fileURL = URL.createObjectURL(file);
            if (file.type.startsWith('image/')) {
                container.innerHTML = `<img src="${fileURL}" class="img-thumbnail" style="max-width:150px; max-height:150px;"><div class="mt-1 small text-muted">${file.name}</div>`;
            } else {
                container.innerHTML = `<a href="${fileURL}" target="_blank">${file.name}</a>`;
            }
        }

        // Show existing files or newly uploaded files
        @if($applicationform->passport_size_photo)
            document.getElementById('p_photo').innerHTML = `<a href="{{ asset('storage/'.$applicationform->passport_size_photo) }}" target="_blank">View Uploaded File</a>`;
        @else
            previewFile('p_photo', 'passport_size_photo');
        @endif

        @if($applicationform->citizenship_id_document)
            document.getElementById('p_citizenship').innerHTML = `<a href="{{ asset('storage/'.$applicationform->citizenship_id_document) }}" target="_blank">View Uploaded File</a>`;
        @else
            previewFile('p_citizenship', 'citizenship_id_document');
        @endif

        @if($applicationform->transcript)
            document.getElementById('p_transcript').innerHTML = `<a href="{{ asset('storage/'.$applicationform->transcript) }}" target="_blank">View Uploaded File</a>`;
        @else
            previewFile('p_transcript', 'transcript');
        @endif

        @if($applicationform->character)
            document.getElementById('p_character').innerHTML = `<a href="{{ asset('storage/'.$applicationform->character) }}" target="_blank">View Uploaded File</a>`;
        @else
            previewFile('p_character', 'character');
        @endif

        @if($applicationform->signature)
            document.getElementById('p_signature').innerHTML = `<a href="{{ asset('storage/'.$applicationform->signature) }}" target="_blank">View Uploaded File</a>`;
        @else
            previewFile('p_signature', 'signature');
        @endif

        @if($applicationform->equivalent)
            document.getElementById('p_equivalent').innerHTML = `<a href="{{ asset('storage/'.$applicationform->equivalent) }}" target="_blank">View Uploaded File</a>`;
        @else
            previewFile('p_equivalent', 'equivalent');
        @endif

        @if($applicationform->work_experience)
            document.getElementById('p_work_experience').innerHTML = `<a href="{{ asset('storage/'.$applicationform->work_experience) }}" target="_blank">View Uploaded File</a>`;
        @else
            previewFile('p_work_experience', 'work_experience');
        @endif

        @if($applicationform->noc_id_card)
            document.getElementById('p_noc_id_card').innerHTML = `<a href="{{ asset('storage/'.$applicationform->noc_id_card) }}" target="_blank">View Uploaded File</a>`;
        @else
            previewFile('p_noc_id_card', 'noc_id_card');
        @endif

        @if($applicationform->ethnic_certificate)
            document.getElementById('p_ethnic_certificate').innerHTML = `<a href="{{ asset('storage/'.$applicationform->ethnic_certificate) }}" target="_blank">View Uploaded File</a>`;
        @else
            previewFile('p_ethnic_certificate', 'ethnic_certificate');
        @endif

        @if($applicationform->disability_certificate)
            document.getElementById('p_disability_certificate').innerHTML = `<a href="{{ asset('storage/'.$applicationform->disability_certificate) }}" target="_blank">View Uploaded File</a>`;
        @else
            previewFile('p_disability_certificate', 'disability_certificate');
        @endif
    }

    // PAYMENT GATEWAYS
    window.startPayment = function(gateway) {
        const applicationId = "{{ $applicationform->id ?? '' }}";
        if (!applicationId) { alert("Application ID not found."); return; }
        let url = "";
        if (gateway === "esewa") url = "/candidate/payment/esewa/start/" + applicationId;
        else if (gateway === "khalti") url = "/candidate/payment/khalti/start/" + applicationId;
        else if (gateway === "connectips") url = "/candidate/payment/connectips/start/" + applicationId;
        window.location.href = url;
    };

    // Live preview update on input changes
    form.addEventListener('input', function(e) {
        if (e.target.type === 'file') return;
        populatePreview();
<<<<<<< HEAD
        populateExperiencePreview();
    });
    form.addEventListener('change', function(e) {
        populatePreview();
        populateExperiencePreview();
=======
    });
    form.addEventListener('change', function(e) {
        populatePreview();
>>>>>>> efe7d213166d7eb2c3aef5455d337ce01292fe6f
    });

    console.log('✓ Edit form initialized');
});

// ============================================================
// BS → AD date conversion + Age auto-fill
// ============================================================
(function () {
    function calculateAge(adDateStr) {
        if (!adDateStr) return '';
        const birth = new Date(adDateStr);
        if (isNaN(birth.getTime())) return '';
        const today = new Date();
        let age = today.getFullYear() - birth.getFullYear();
        const m = today.getMonth() - birth.getMonth();
        if (m < 0 || (m === 0 && today.getDate() < birth.getDate())) age--;
        return age > 0 ? age : '';
    }

    function applyFromBS(bsValue) {
        if (!bsValue || typeof window.bsToAD !== 'function') return;
        const adDate = window.bsToAD(bsValue.trim());
        if (!adDate) return;
        const adInput = document.getElementById('birth_date_ad');
        const ageInput = document.getElementById('age');
        if (adInput) adInput.value = adDate;
        const age = calculateAge(adDate);
        if (ageInput && age !== '') ageInput.value = age;
    }

    function applyFromAD(adValue) {
        const ageInput = document.getElementById('age');
        const age = calculateAge(adValue);
        if (ageInput && age !== '') ageInput.value = age;
    }

    function init() {
        const bsInput = document.getElementById('birth_date_bs');
        const adInput = document.getElementById('birth_date_ad');
        if (!bsInput) return;
        if (bsInput.value) applyFromBS(bsInput.value);
        else if (adInput && adInput.value) applyFromAD(adInput.value);
        bsInput.addEventListener('change', function () { applyFromBS(this.value); });
        bsInput.addEventListener('input', function () { applyFromBS(this.value); });
        if (adInput) adInput.addEventListener('change', function () { applyFromAD(this.value); });
    }

    function waitForConverter() {
        if (!window.nepaliLibrariesReady || typeof window.bsToAD !== 'function') {
            setTimeout(waitForConverter, 100); return;
        }
        if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', init);
        else init();
    }
    waitForConverter();
})();

// ============================================================
// Devanagari-only enforcement for name_nepali field
// ============================================================
(function () {
    const field = document.getElementById('name_nepali');
    if (!field) return;
    const allowed = /[\u0900-\u097F\s.\-]/;
    function clean(str) { return str.split('').filter(ch => allowed.test(ch)).join(''); }

    field.addEventListener('keydown', function (e) {
        if (e.ctrlKey || e.metaKey || e.altKey ||
            ['Backspace','Delete','Tab','Escape','Enter','ArrowLeft','ArrowRight','ArrowUp','ArrowDown','Home','End','Shift'].includes(e.key)) return;
        if (e.key.length === 1 && !allowed.test(e.key)) e.preventDefault();
    });

    field.addEventListener('input', function () {
        const pos = this.selectionStart;
        const cleaned = clean(this.value);
        if (cleaned !== this.value) {
            this.value = cleaned;
            this.setSelectionRange(Math.min(pos, cleaned.length), Math.min(pos, cleaned.length));
        }
    });

    field.addEventListener('paste', function (e) {
        e.preventDefault();
        const pasted = (e.clipboardData || window.clipboardData).getData('text');
        const cleaned = clean(pasted);
        if (!cleaned) return;
        const start = this.selectionStart; const end = this.selectionEnd;
        this.value = this.value.slice(0, start) + cleaned + this.value.slice(end);
        const newPos = start + cleaned.length;
        this.setSelectionRange(newPos, newPos);
        this.dispatchEvent(new Event('input', { bubbles: true }));
    });

    field.addEventListener('drop', function (e) {
        e.preventDefault();
        const dropped = e.dataTransfer.getData('text');
        const cleaned = clean(dropped);
        if (!cleaned) return;
        const pos = this.selectionStart;
        this.value = this.value.slice(0, pos) + cleaned + this.value.slice(pos);
        this.setSelectionRange(pos + cleaned.length, pos + cleaned.length);
        this.dispatchEvent(new Event('input', { bubbles: true }));
    });
<<<<<<< HEAD

   })(); // close Devanagari IIFE

// ── Work Experience Preview (global scope) ────────────────────
window.populateExperiencePreview = function () {
    let html = "";
    for (let i = 1; i <= 3; i++) {
        const org   = document.querySelector(`[name="exp${i}_organization"]`)?.value || '';
        const pos   = document.querySelector(`[name="exp${i}_position"]`)?.value || '';
        const start = document.querySelector(`[name="exp${i}_start_date_bs"]`)?.value || '';
        const end   = document.querySelector(`[name="exp${i}_end_date_bs"]`)?.value || '';
        const years = document.querySelector(`[name="exp${i}_years"]`)?.value || '';

        const fileInput    = document.querySelector(`[name="exp${i}_document"]`);
        const file         = fileInput?.files?.[0];
        const existingFile = fileInput?.getAttribute("data-existing") || '';

        // Skip completely empty rows
        if (!org && !pos && !start && !end && !years && !file && !existingFile) continue;

        html += `
        <div style="margin-bottom:16px; padding:12px; border:1px solid #ddd; border-radius:8px;">
            <h6 class="mb-2">Experience ${i}</h6>
            <table class="table table-sm table-borderless mb-1">
                <tr><td width="120"><b>Organization:</b></td><td>${org || '-'}</td></tr>
                <tr><td><b>Position:</b></td><td>${pos || '-'}</td></tr>
                <tr><td><b>Start Date:</b></td><td>${start || '-'}</td></tr>
                <tr><td><b>End Date:</b></td><td>${end || '-'}</td></tr>
                <tr><td><b>Years:</b></td><td>${years || '-'}</td></tr>
            </table>`;

        if (file) {
            const url = URL.createObjectURL(file);
            if (file.type.includes("pdf")) {
                html += `<iframe src="${url}" style="width:100%;height:250px;border:1px solid #ccc;"></iframe>`;
            } else if (file.type.startsWith("image/")) {
                html += `<img src="${url}" style="max-width:180px;border:1px solid #ccc;padding:3px;">`;
            }
        } else if (existingFile) {
            html += `<div class="mt-1"><a href="${existingFile}" target="_blank">📄 View Uploaded Document</a></div>`;
        } else {
            html += `<div class="text-muted small">No document uploaded</div>`;
        }

        html += `</div>`;
    }

    const container = document.getElementById("experience_preview");
    if (container) {
        container.innerHTML = html || "<span class='text-muted'>No experience added</span>";
    }

    const hasExp = document.querySelector('[name="has_work_experience"]')?.value;
    const hasExpEl = document.getElementById("p_has_work_experience");
    if (hasExpEl) hasExpEl.innerText = hasExp || '-';
};
=======
})();
>>>>>>> efe7d213166d7eb2c3aef5455d337ce01292fe6f
</script>
@endsection