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
    {{-- <a href="{{ route('candidate.my-profile') }}" class="sidebar-menu-item">
        <i class="bi bi-person"></i>
        <span>My Profile</span>
    </a>
    --}}
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
        <div class="card-header bg-primary text-white text-center py-2">
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

               {{-- STEP 1: Personal Info --}}
                <div class="step active" id="step1">
                    <h5 class="mb-4 text-primary">Step 1 — Personal Information</h5>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="name_english" class="form-label">Full Name (English) <span class="text-danger">*</span> <small>(नाम)</small></label>
                            <input type="text" name="name_english" id="name_english" class="form-control" value="{{ old('name_english', $applicationform->name_english) }}" required>
                        </div>
                        <div class="col-md-6">
                            <label for="name_nepali" class="form-label">Full Name (Nepali) <span class="text-danger">*</span> <small>(नाम)</small></label>
                            <input type="text" name="name_nepali" id="name_nepali" class="form-control" value="{{ old('name_nepali', $applicationform->name_nepali) }}" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <label for="birth_date_ad" class="form-label">Birth Date (A.D) <span class="text-danger">*</span> <small>(जन्म मिति A.D)</small></label>
                            <input type="date" name="birth_date_ad" id="birth_date_ad" class="form-control" value="{{ old('birth_date_ad', $applicationform->birth_date_ad ? \Carbon\Carbon::parse($applicationform->birth_date_ad)->format('Y-m-d') : '') }}" required>
                        </div>
                        <div class="col-md-3">
                            <label for="birth_date_bs" class="form-label">Birth Date (B.S) <span class="text-danger">*</span> <small>(जन्म मिति B.S)</small></label>
                            <input type="text" name="birth_date_bs" id="birth_date_bs" class="form-control" placeholder="YYYY-MM-DD" value="{{ old('birth_date_bs', $applicationform->birth_date_bs) }}" required>
                        </div>
                        <div class="col-md-3">
                            <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="text" name="email" id="email" class="form-control" value="{{ old('Email Address', $applicationform->email) }}" required>
                        </div>
                        <div class="col-md-3">
                            <label for="phone" class="form-label">Phone <span class="text-danger">*</span> <small>(फोन नम्बर)</small></label>
                            <input type="text" name="phone" id="phone" class="form-control" value="{{ old('phone', $applicationform->phone) }}" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="advertisement_no" class="form-label">Advertisement Number <span class="text-danger">*</span></label>
                            <input type="text" name="advertisement_no" id="advertisement_no" class="form-control" value="{{ old('advertisement_no', $applicationform->advertisement_no ?? '') }}" required>
                        </div>
                        <div class="col-md-4">
                            <label for="applying_position" class="form-label">Applying Position <span class="text-danger">*</span></label>
                            <input type="text" name="applying_position" id="applying_position" class="form-control" value="{{ old('applying_position', $applicationform->applying_position ?? '') }}" required>
                        </div>
                        <div class="col-md-4">
                            <label for="department" class="form-label">Department <span class="text-danger">*</span></label>
                            <input type="text" name="department" id="department" class="form-control" value="{{ old('department', $applicationform->department ?? '') }}" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="age" class="form-label">Age <span class="text-danger">*</span> <small>(उमेर)</small></label>
                            <input type="number" name="age" id="age" class="form-control" min="0" value="{{ old('age', $applicationform->age) }}" required>
                        </div>
                        <div class="col-md-4">
                            <label for="alternate_phone_number" class="form-label">Alternate Phone Number <span class="text-danger">*</span> <small>(वैकल्पिक फोन नम्बर)</small></label>
                            <input type="text" name="alternate_phone_number" id="alternate_phone_number" class="form-control" value="{{ old('alternate_phone_number', $applicationform->alternate_phone_number) }}" required>
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
                            <input type="text" name="spouse_name_english" id="spouse_name_english" class="form-control" value="{{ old('spouse_name_english',$applicationform->spouse_name_english) }}">
                        </div>
                        <div class="col-md-4">
                            <label for="spouse_nationality" class="form-label">Spouse Nationality (If Married)</label>
                            <input type="text" name="spouse_nationality" id="spouse_nationality" class="form-control" value="{{ old('spouse_nationality',$applicationform->spouse_nationality) }}">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="citizenship_number" class="form-label">Citizenship Number <span class="text-danger">*</span></label>
                            <input type="text" name="citizenship_number" id="citizenship_number" class="form-control" value="{{ old('citizenship_number', $applicationform->citizenship_number) }}" required>
                        </div>
                        <div class="col-md-4">
                            <label for="citizenship_issue_date_bs" class="form-label">Citizenship Issue Date (B.S)<span class="text-danger">*</span></label>
                            <input type="text" name="citizenship_issue_date_bs" id="citizenship_issue_date_bs" class="form-control" value="{{ old('citizenship_issue_date_bs', $applicationform->citizenship_issue_date_bs) }}" required>
                        </div>
                        <div class="col-md-4">
                            <label for="citizenship_issue_district" class="form-label">Citizenship Issue District <span class="text-danger">*</span></label>
                            <input type="text" name="citizenship_issue_district" id="citizenship_issue_district" class="form-control" value="{{ old('citizenship_issue_district', $applicationform->citizenship_issue_district) }}" required>
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
                            <label for="father_qualification" class="form-label">Father's Qualification (बुबाको योग्यता) <span class="text-danger">*</span></label>
                            <input type="text" name="father_qualification" id="father_qualification" class="form-control" value="{{ old('father_qualification', $applicationform->father_qualification) }}" required>
                        </div>
                        <div class="col-md-4">
                            <label for="mother_qualification" class="form-label">Mother's Qualification (आमाको योग्यता) <span class="text-danger">*</span></label>
                            <input type="text" name="mother_qualification" id="mother_qualification" class="form-control" value="{{ old('mother_qualification', $applicationform->mother_qualification) }}" required>
                        </div>
                         <div class="col-md-4">
                            <label for="parent_occupation" class="form-label">Parents's Occupation<span class="text-danger">*</span></label>
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
                            <label for="noc_id_card" class="form-label">NOC ID Card (If Yes)</label>
                            <input type="file" name="noc_id_card" id="noc_id_card" class="form-control" accept="image/*,application/pdf">
                            @if($applicationform->noc_id_card)
                                <small class="text-muted">Current: <a href="{{ asset('storage/' . $applicationform->noc_id_card) }}" target="_blank">View File</a></small>
                            @endif
                            <small class="text-muted d-block">Max size: 2MB</small>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end">
                        <button type="button" class="btn btn-primary next-btn">Next</button>
                    </div>
                </div>

                {{-- STEP 2: General Info --}}
                <div class="step d-none" id="step2">
                    <h5 class="mb-4 text-primary">Step 2 — General Information</h5>

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
                            <input type="file" name="ethnic_certificate" id="ethnic_certificate" class="form-control" accept="image/*,application/pdf">
                            @if($applicationform->ethnic_certificate)
                                <small class="text-muted">Current: <a href="{{ asset('storage/' . $applicationform->ethnic_certificate) }}" target="_blank">View File</a></small>
                            @endif
                            <small class="text-muted d-block">Max size: 2MB</small>
                        </div>
                        <div class="col-md-4">
                            <label for="mother_tongue" class="form-label">Mother Tongue <span class="text-danger">*</span> <small>(मातृभाषा)</small></label>
                            <input type="text" name="mother_tongue" id="mother_tongue" class="form-control" value="{{ old('mother_tongue', $applicationform->mother_tongue) }}" required>
                        </div>
                        <div class="col-md-4">
                            <label for="employment_status" class="form-label">Employment Status <span class="text-danger">*</span> <small>(रोजगार स्थिति)</small></label>
                            <select name="employment_status" id="employment_status" class="form-select" required>
                                <option value="">-- Select --</option>
                                <option value="employed" {{ old('employment_status', $applicationform->employment_status) == 'employed' ? 'selected' : '' }}>Employed</option>
                                <option value="unemployed" {{ old('employment_status', $applicationform->employment_status) == 'unemployed' ? 'selected' : '' }}>Unemployed</option>
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="employment_other" class="form-label">Other Employment Details</label>
                            <input type="text" name="employment_other" id="employment_other" class="form-control" value="{{ old('employment_other', $applicationform->employment_other) }}">
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
                            <label for="disability_other" class="form-label">Disability Details</label>
                            <input type="text" name="disability_other" id="disability_other" class="form-control" value="{{ old('disability_other', $applicationform->disability_other) }}">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="disability_certificate" class="form-label">Disability Certificate (If Any)</label>
                            <input type="file" name="disability_certificate" id="disability_certificate" class="form-control" accept="image/*,application/pdf">
                            @if($applicationform->disability_certificate)
                                <small class="text-muted">Current: <a href="{{ asset('storage/' . $applicationform->disability_certificate) }}" target="_blank">View File</a></small>
                            @endif
                            <small class="text-muted d-block">Max size: 2MB</small>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between">
                        <button type="button" class="btn btn-secondary prev-btn">Back</button>
                        <button type="button" class="btn btn-primary next-btn">Next</button>
                    </div>
                </div>

                {{-- STEP 3: Permanent Address --}}
                <div class="step d-none" id="step3">
                    <h5 class="mb-4 text-primary">Step 3 — Permanent Address</h5>
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="permanent_province" class="form-label">Province <span class="text-danger">*</span></label>
                            <select name="permanent_province" id="permanent_province" class="form-select" required>
                                <option value="">-- Select --</option>
                                <option value="Koshi" {{ old('permanent_province', $applicationform->permanent_province) == 'Koshi' ? 'selected' : '' }}>Koshi</option>
                                <option value="Madhesh" {{ old('permanent_province', $applicationform->permanent_province) == 'Madhesh' ? 'selected' : '' }}>Madhesh</option>
                                <option value="Bagmati" {{ old('permanent_province', $applicationform->permanent_province) == 'Bagmati' ? 'selected' : '' }}>Bagmati</option>
                                <option value="Gandaki" {{ old('permanent_province', $applicationform->permanent_province) == 'Gandaki' ? 'selected' : '' }}>Gandaki</option>
                                <option value="Lumbini" {{ old('permanent_province', $applicationform->permanent_province) == 'Lumbini' ? 'selected' : '' }}>Lumbini</option>
                                <option value="Karnali" {{ old('permanent_province', $applicationform->permanent_province) == 'Karnali' ? 'selected' : '' }}>Karnali</option>
                                <option value="Sudurpashchim" {{ old('permanent_province', $applicationform->permanent_province) == 'Sudurpashchim' ? 'selected' : '' }}>Sudurpashchim</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="permanent_district" class="form-label">District <span class="text-danger">*</span></label>
                            <input type="text" name="permanent_district" id="permanent_district" class="form-control" value="{{ old('permanent_district', $applicationform->permanent_district) }}" required>
                        </div>
                        <div class="col-md-4">
                            <label for="permanent_municipality" class="form-label">Municipality <span class="text-danger">*</span></label>
                            <input type="text" name="permanent_municipality" id="permanent_municipality" class="form-control" value="{{ old('permanent_municipality', $applicationform->permanent_municipality) }}" required>
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

                    <h5 class="mb-4 text-primary"> Mailing/Current Address</h5>
                    <div class="form-check mb-4">
                        <input type="checkbox" class="form-check-input" id="same_as_permanent" name="same_as_permanent" value="1" {{ old('same_as_permanent', $applicationform->same_as_permanent) ? 'checked' : '' }}>
                        <label class="form-check-label" for="same_as_permanent">Same as Permanent Address</label>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="mailing_province" class="form-label">Province <span class="text-danger">*</span></label>
                            <select name="mailing_province" id="mailing_province" class="form-select" required>
                                <option value="">-- Select --</option>
                                <option value="Koshi" {{ old('mailing_province', $applicationform->mailing_province) == 'Koshi' ? 'selected' : '' }}>Koshi</option>
                                <option value="Madhesh" {{ old('mailing_province', $applicationform->mailing_province) == 'Madhesh' ? 'selected' : '' }}>Madhesh</option>
                                <option value="Bagmati" {{ old('mailing_province', $applicationform->mailing_province) == 'Bagmati' ? 'selected' : '' }}>Bagmati</option>
                                <option value="Gandaki" {{ old('mailing_province', $applicationform->mailing_province) == 'Gandaki' ? 'selected' : '' }}>Gandaki</option>
                                <option value="Lumbini" {{ old('mailing_province', $applicationform->mailing_province) == 'Lumbini' ? 'selected' : '' }}>Lumbini</option>
                                <option value="Karnali" {{ old('mailing_province', $applicationform->mailing_province) == 'Karnali' ? 'selected' : '' }}>Karnali</option>
                                <option value="Sudurpashchim" {{ old('mailing_province', $applicationform->mailing_province) == 'Sudurpashchim' ? 'selected' : '' }}>Sudurpashchim</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="mailing_district" class="form-label">District <span class="text-danger">*</span></label>
                            <input type="text" name="mailing_district" id="mailing_district" class="form-control" value="{{ old('mailing_district', $applicationform->mailing_district) }}" required>
                        </div>
                        <div class="col-md-4">
                            <label for="mailing_municipality" class="form-label">Municipality <span class="text-danger">*</span></label>
                            <input type="text" name="mailing_municipality" id="mailing_municipality" class="form-control" value="{{ old('mailing_municipality', $applicationform->mailing_municipality) }}" required>
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
                    <div class="d-flex justify-content-between">
                        <button type="button" class="btn btn-secondary prev-btn">Back</button>
                        <button type="button" class="btn btn-primary next-btn">Next</button>
                    </div>
                </div>

                

                {{-- STEP 4: Educational Background --}}
                <div class="step d-none" id="step4">
                    <h5 class="mb-4 text-primary">Step 4 — Educational Background</h5>
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
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="institution_name" class="form-label">Institution Name<span class="text-danger">*</span></label>
                            <input type="text" name="institution_name" id="institution_name" class="form-control" value="{{ old('institution_name', $applicationform->institution_name) }}"required>
                        </div>
                        <div class="col-md-6">
                            <label for="graduation_year" class="form-label">Graduation Year<span class="text-danger">*</span></label>
                            <input type="number" name="graduation_year" id="graduation_year" class="form-control" min="1950" max="2030" value="{{ old('graduation_year', $applicationform->graduation_year) }}"required>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between">
                        <button type="button" class="btn btn-secondary prev-btn">Back</button>
                        <button type="button" class="btn btn-primary next-btn">Next</button>
                    </div>
                </div>

                {{-- STEP 5: Work Experience --}}
                <div class="step d-none" id="step5">
                    <h5 class="mb-4 text-primary">Step 5 — Work Experience</h5>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="has_work_experience" class="form-label">Do you have work experience? <span class="text-danger">*</span></label>
                            <select name="has_work_experience" id="has_work_experience" class="form-select" required>
                                <option value="">-- Select --</option>
                                <option value="Yes" {{ old('has_work_experience', $applicationform->has_work_experience) == 'Yes' ? 'selected' : '' }}>Yes</option>
                                <option value="No" {{ old('has_work_experience', $applicationform->has_work_experience) == 'No' ? 'selected' : '' }}>No</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="years_of_experience" class="form-label">Years of Experience</label>
                            <input type="number" name="years_of_experience" id="years_of_experience" class="form-control" min="0" step="0.5" value="{{ old('years_of_experience', $applicationform->years_of_experience) }}">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="previous_organization" class="form-label">Previous Organization</label>
                            <input type="text" name="previous_organization" id="previous_organization" class="form-control" value="{{ old('previous_organization', $applicationform->previous_organization) }}">
                        </div>
                        <div class="col-md-6">
                            <label for="previous_position" class="form-label">Previous Position</label>
                            <input type="text" name="previous_position" id="previous_position" class="form-control" value="{{ old('previous_position', $applicationform->previous_position) }}">
                        </div>
                    </div>
                    <div class="d-flex justify-content-between">
                        <button type="button" class="btn btn-secondary prev-btn">Back</button>
                        <button type="button" class="btn btn-primary next-btn">Next</button>
                    </div>
                </div>

                {{-- STEP 6: Upload Documents --}}
                <div class="step d-none" id="step6">
                    <h5 class="mb-4 text-primary">Step 6 — Upload Documents</h5>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="passport_size_photo" class="form-label">Passport Size Photo</label>
                            <input type="file" name="passport_size_photo" id="passport_size_photo" class="form-control" accept="image/*,application/pdf">
                            @if($applicationform->passport_size_photo)
                                <small class="text-muted">Current: <a href="{{ asset('storage/' . $applicationform->passport_size_photo) }}" target="_blank">View File</a></small>
                            @endif
                            <small class="text-muted d-block">Max size: 2MB</small>
                        </div>
                        <div class="col-md-6">
                            <label for="citizenship_id_document" class="form-label">Citizenship/ID Document<span class="text-danger"><small> (Please upload front and back in same page)</small>*</span></label>
                            <input type="file" name="citizenship_id_document" id="citizenship_id_document" class="form-control" accept="image/*,application/pdf">
                            @if($applicationform->citizenship_id_document)
                                <small class="text-muted">Current: <a href="{{ asset('storage/' . $applicationform->citizenship_id_document) }}" target="_blank">View File</a></small>
                            @endif
                            <small class="text-muted d-block">Max size: 2MB</small>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="resume_cv" class="form-label">Transcript Certificate</label>
                            <input type="file" name="resume_cv" id="resume_cv" class="form-control" accept="image/*,application/pdf">
                            @if($applicationform->resume_cv)
                                <small class="text-muted">Current: <a href="{{ asset('storage/' . $applicationform->resume_cv) }}" target="_blank">View File</a></small>
                            @endif
                            <small class="text-muted d-block">Max size: 2MB</small>
                        </div>

                        <div class="col-md-6">
                            <label for="educational_certificates" class="form-label">Character Certificate</label>
                            <input type="file"
                                name="educational_certificates[]"
                                class="form-control"
                                accept="image/*,application/pdf"
                                multiple>
                                                            @php
                                    $files = json_decode($applicationform->educational_certificates, true);

                                    // Handle double-encoded JSON
                                    if (is_string($files)) {
                                        $files = json_decode($files, true);
                                    }
                                @endphp

                                @if(is_array($files) && count($files))
                                    <small class="text-muted">{{ count($files) }} file(s) uploaded</small><br>

                                    @foreach($files as $file)
                                        <a href="{{ asset('storage/'.$file) }}" target="_blank">
                                            View File
                                        </a><br>
                                    @endforeach
                                @endif

                            <small class="text-muted d-block">Max size: 2MB</small>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="equivalent" class="form-label">Equivalency Certificate (If your degree is out of Nepal.)</label>
                            <input type="file" name="equivalent" id="equivalent" class="form-control" value="{{ old('equivalent', $applicationform->equivalent ?? '') }}" accept="image/*,application/pdf">
                            <small class="text-muted d-block">Max size: 2MB</small>
                        </div>

                        <div class="col-md-6">
                            <label for="work_experience" class="form-label">Work Experience Document</label>
                            <input type="file" name="work_experience" id="work_experience" class="form-control" value="{{ old('work_experience', $applicationform->work_experience ?? '') }}" accept="image/*,application/pdf">
                            <small class="text-muted d-block">Max size: 2MB</small>
                        </div>
                        
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="signature" class="form-label">Signature</label>
                            <input type="file" name="signature" id="signature" class="form-control" value="{{ old('signature', $applicationform->signature ?? '') }}" accept="image/*,application/pdf">
                            <small class="text-muted d-block">Max size: 2MB</small>
                        </div>
                        
                    </div>

                    <div class="d-flex justify-content-between">
                        <button type="button" class="btn btn-secondary prev-btn">Back</button>
                        <button type="button" class="btn btn-primary next-btn">Next</button>
                    </div>
</div>       
                    <!-- STEP 7: Review Application Before Payment -->
                <div class="step d-none" id="step7">
                    <h5 class="mb-4 text-primary">Step 7 — Preview Application Before Payment</h5>

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
                            <tr><th>Gender</th><td id="p_gender"></td></tr>
                            <tr><th>Marital Status</th><td id="p_marital_status"></td></tr>
                            <tr><th>Nationality</th><td id="p_nationality"></td></tr>
                            <tr><th>Blood Group</th><td id="p_blood_group"></td></tr>
                        </table>

                        <h6 class="text-secondary mt-4">Address Information</h6>
                        <table class="table table-bordered">
                            <tr><th width="30%">Permanent Address</th>
                                <td id="p_permanent_address"></td></tr>

                            <tr><th>Mailing Address</th>
                                <td id="p_mailing_address"></td></tr>
                        </table>

                        <h6 class="text-secondary mt-4">Education</h6>
                        <table class="table table-bordered">
                            <tr><th width="30%">Education Level</th><td id="p_education_level"></td></tr>
                            <tr><th>Field of Study</th><td id="p_field_of_study"></td></tr>
                            <tr><th>Institution</th><td id="p_institution_name"></td></tr>
                            <tr><th>Graduation Year</th><td id="p_graduation_year"></td></tr>
                        </table>

                        <h6 class="text-secondary mt-4">Work Experience</h6>
                        <table class="table table-bordered">
                            <tr><th width="30%">Has Experience</th><td id="p_has_work_experience"></td></tr>
                            <tr><th>Years of Experience</th><td id="p_years_of_experience"></td></tr>
                            <tr><th>Previous Organization</th><td id="p_previous_organization"></td></tr>
                            <tr><th>Previous Position</th><td id="p_previous_position"></td></tr>
                        </table>

                        <h6 class="text-secondary mt-4">Uploaded Documents</h6>
                        <table class="table table-bordered">
                            <tr>
                                <th width="30%">Passport Size Photo</th>
                                <td id="p_photo"></td>
                            </tr>
                            <tr>
                                <th>Citizenship / ID Document</th>
                                <td id="p_citizenship"></td>
                            </tr>
                            <tr>
                                <th>Transcript</th>
                                <td id="p_resume"></td>
                            </tr>
                            <tr>
                                <th>Character</th>
                                <td id="p_academic_certificate"></td>
                            </tr>
                            <tr>
                                <th>Equivalent</th>
                                <td id="p_equivalent"></td>
                            </tr>
                            <tr>
                                <th>Signature</th>
                                <td id="p_signature"></td>
                            </tr>
                            <tr>
                                <th>Work Experience</th>
                                <td id="p_work_experience"></td>
                            </tr>
                        </table>

                        <div class="d-flex justify-content-between mt-4">
                            <button type="button" class="btn btn-secondary prev-btn">Back</button>
                            <button type="button" class="btn btn-primary next-btn">Next</button>
                        </div>
                    </div>
                </div>
  

<!-- STEP 8: Payment Method -->
                <div class="step d-none" id="step8">
                    <h5 class="mb-4 text-primary">Step 8 — Payment & Declaration</h5>

                    <div id="paymentSection">

                        <h6 class="mb-3">Choose Payment Gateway</h6>

                        <div class="row text-center">

                            <!-- eSewa -->
                            <div class="col-md-4 mb-3">
                                <div class="payment-box" onclick="startPayment('esewa')">
                                    <img src="/images/esewalogo.jpg" alt="eSewa" class="payment-logo">
                                    <div>Pay with eSewa</div>
                                </div>
                            </div>

                            <!-- Khalti -->
                            <div class="col-md-4 mb-3">
                                <div class="payment-box" onclick="startPayment('khalti')">
                                    <img src="/images/khaltilogo.jpg" alt="Khalti" class="payment-logo">
                                    <div>Pay with Khalti</div>
                                </div>
                            </div>

                            <!-- ConnectIPS -->
                            <div class="col-md-4 mb-3">
                                <div class="payment-box" onclick="startPayment('connectips')">
                                    <img src="/images/cipslogo.jpg" alt="ConnectIPS" class="payment-logo">
                                    <div>Pay with ConnectIPS</div>
                                </div>
                            </div>

                        </div>

                        <div class="alert alert-warning mt-3">
                            Please complete payment first to enable final submission.
                        </div>
                        <!-- Save Draft Button -->
                        <div class="mt-3 d-flex justify-content-center">
                            <button type="button" class="btn btn-success" id="saveDraftBeforePayment">
                                Save Draft & Pay Later
                            </button>
                        </div>

                        

                    </div>

                    <!-- AFTER PAYMENT SECTION -->
                    <div id="postPaymentSection" style="display:none;">

                        <div class="alert alert-success">
                            ✓ Payment completed successfully.
                        </div>

                        <div class="form-check mb-4">
                            <input type="checkbox" class="form-check-input" id="terms_agree" name="terms_agree" required>
                            <label class="form-check-label" for="terms_agree">
                                I hereby declare that all information provided is true and correct. <span class="text-danger">*</span>
                            </label>
                        </div>

                        <div class="d-flex justify-content-between">
                            <button type="button" class="btn btn-secondary prev-btn">Back</button>
                            <button type="submit" class="btn btn-success">Submit Application</button>
                        </div>

                    </div>

                </div>

            </form>
        </div>
    </div>
</div>

@push('styles')
<style>

    /* Clickable Tabs Styling */
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
        background: #0d6efd;
        color: white;
    }
    .tab-item.active .tab-label,
    .tab-item.completed .tab-label {
        color: #0d6efd;
        font-weight: 600;
    }

    /* Hover */
    .tab-item:hover .tab-circle {
        background: #0d6efd;
        color: white;
    }
    .tab-item:hover .tab-label {
        color: #0d6efd;
    }

    /* Progress Line */
    .progress-line {
        position: absolute;
        bottom: -1px;
        left: 0;
        height: 4px;
        background: #0d6efd;
        width: 14.28%; /* Will be updated via JS */
        transition: width 0.4s ease;
        z-index: 1;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .tab-label { font-size: 0.8rem; }
        .tab-item { padding: 12px 4px; }
        .tab-circle { width: 35px; height: 35px; font-size: 1rem; }
    }

    /* Step Visibility */
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

    /* Validation Styling */
    .is-invalid { border-color: #dc3545 !important; }
    .invalid-feedback { color: #dc3545; font-size: 0.875rem; margin-top: 0.25rem; display: block; }

    /* PAYMENT CSS */
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

    .payment-box:hover {
        background: #f5f5f5;
    }

    .payment-logo {
        width: 150px;
        height: 60px;
        object-fit: contain;
        margin-bottom: 10px;
    }
</style>
@endpush

@push('scripts')
<script>
    
document.addEventListener('DOMContentLoaded', function () {
    let currentStep = 1;
    const totalSteps = 8;
    const hasErrors = {{ $errors->any() ? 'true' : 'false' }};

    // Update Tabs & Progress Line
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

        // Update progress line width
        const progressPercent = ((currentStep - 1) / (totalSteps - 1)) * 100;
        document.querySelector('.progress-line').style.width = progressPercent + '%';
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
        if (step === 7) {
        populatePreview();
        }
        updateTabsAndProgress();
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    // Validate Current Step
    function validateStep(step) {
        const stepEl = document.getElementById('step' + step);
        if (!stepEl) return false;

        // Clear previous errors
        stepEl.querySelectorAll('.is-invalid, .invalid-feedback').forEach(el => {
            el.classList.remove('is-invalid');
            if (el.classList.contains('invalid-feedback')) el.remove();
        });

        let isValid = true;
        let firstInvalid = null;

        stepEl.querySelectorAll('input[required], select[required], textarea[required]').forEach(field => {
            if (field.closest('.d-none')) return;
            if (!field.value.trim()) {
                isValid = false;
                field.classList.add('is-invalid');
                const err = document.createElement('div');
                err.className = 'invalid-feedback';
                err.textContent = 'This field is required';
                field.parentNode.appendChild(err);
                if (!firstInvalid) firstInvalid = field;
            }
        });

        if (!isValid && firstInvalid) {
            firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
            firstInvalid.focus();
        }
        return isValid;
    }

    // Clickable Tabs
    document.querySelectorAll('.tab-item').forEach(tab => {
        tab.addEventListener('click', () => {
            const targetStep = parseInt(tab.getAttribute('data-step'));
            
            // Allow jumping only to previous or current step (or next if valid)
            if (targetStep < currentStep || (targetStep === currentStep + 1 && validateStep(currentStep))) {
                showStep(targetStep);
            } else if (targetStep > currentStep) {
                // Try to go forward only if current is valid
                if (validateStep(currentStep)) {
                    showStep(targetStep);
                }
            }
        });
    });

    // Next Button
    document.querySelectorAll('.next-btn').forEach(btn => {
    btn.addEventListener('click', () => {

        if (!validateStep(currentStep)) {
            return;
        }

        // BEFORE going to Step 7 → generate preview
        if (currentStep === 6) {
            populatePreview();
        }

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

    // Same as Permanent Address Checkbox
    document.getElementById('same_as_permanent')?.addEventListener('change', function () {
        if (this.checked) {
            ['province', 'district', 'municipality', 'ward', 'tole', 'house_number'].forEach(field => {
                const perm = document.getElementById('permanent_' + field)?.value || '';
                document.getElementById('mailing_' + field).value = perm;
            });
        }
    });

    // Show "Other" fields
    ['religion', 'community', 'ethnic_group'].forEach(id => {
        const select = document.getElementById(id);
        const other = document.getElementById(id + '_other');
        if (select && other) {
            const toggle = () => other.classList.toggle('d-none', select.value !== 'Other');
            select.addEventListener('change', toggle);
            toggle();
        }
    });

    // On Load: Jump to errored step or start at 1
    if (hasErrors) {
        setTimeout(() => {
            const invalid = document.querySelector('.is-invalid');
            if (invalid) {
                const stepEl = invalid.closest('.step');
                if (stepEl) {
                    const stepNum = parseInt(stepEl.id.replace('step', ''));
                    showStep(stepNum);
                    return;
                }
            }
            showStep(1);
        }, 150);
    } else {
        showStep(1);
    }

});
    // ==================== PREVIEW FOR STEP 7 ====================
    
    function populatePreview() {

        function val(id) {
            return document.getElementById(id)?.value || '-';
        }

        function set(id, value) {
            const el = document.getElementById(id);
            if (el) el.textContent = value;
        }

        // Personal Info
        set('p_name_english', val('name_english'));
        set('p_name_nepali', val('name_nepali'));
        set('p_email', val('email'));
        set('p_birth_date_ad', val('birth_date_ad'));
        set('p_birth_date_bs', val('birth_date_bs'));
        set('p_phone', val('phone'));
        set('p_gender', val('gender'));
        set('p_marital_status', val('marital_status'));
        set('p_nationality', val('nationality'));
        set('p_blood_group', val('blood_group'));

        // Address Info
        const permanentAddress =
            val('permanent_province') + ', ' +
            val('permanent_district') + ', ' +
            val('permanent_municipality') + ' - ' +
            val('permanent_ward');

        set('p_permanent_address', permanentAddress);

        const mailingAddress =
            val('mailing_province') + ', ' +
            val('mailing_district') + ', ' +
            val('mailing_municipality') + ' - ' +
            val('mailing_ward');

        set('p_mailing_address', mailingAddress);

        // Education
        set('p_education_level', val('education_level'));
        set('p_field_of_study', val('field_of_study'));
        set('p_institution_name', val('institution_name'));
        set('p_graduation_year', val('graduation_year'));

        // Experience
        set('p_has_work_experience', val('has_work_experience'));
        set('p_years_of_experience', val('years_of_experience'));
        set('p_previous_organization', val('previous_organization'));
        set('p_previous_position', val('previous_position'));

        // ===== DOCUMENT PREVIEW WITH IMAGE DISPLAY =====

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
                container.innerHTML = `
                    <img src="${fileURL}" 
                         class="img-thumbnail" 
                         style="max-width:150px; max-height:150px;">
                    <div class="mt-1 small text-muted">${file.name}</div>
                `;
            } else {
                container.innerHTML = `
                    <a href="${fileURL}" target="_blank">${file.name}</a>
                `;
            }
        }

        // Single file previews

            @if($applicationform->passport_size_photo)
                document.getElementById('p_photo').innerHTML =
                    `<a href="{{ asset('storage/'.$applicationform->passport_size_photo) }}" target="_blank">View Uploaded File</a>`;
            @else
                previewFile('p_photo', 'passport_size_photo');
            @endif
            @if($applicationform->citizenship_id_document)
                document.getElementById('p_citizenship').innerHTML =
                    `<a href="{{ asset('storage/'.$applicationform->citizenship_id_document) }}" target="_blank">View Uploaded File</a>`;
            @else
                previewFile('p_citizenship', 'citizenship_id_document');
            @endif

            @if($applicationform->resume_cv)
                document.getElementById('p_resume').innerHTML =
                    `<a href="{{ asset('storage/'.$applicationform->resume_cv) }}" target="_blank">View Uploaded File</a>`;
            @else
                previewFile('p_resume', 'resume_cv');
            @endif

            @if($applicationform->signature)
                document.getElementById('p_signature').innerHTML =
                    `<a href="{{ asset('storage/'.$applicationform->signature) }}" target="_blank">View Uploaded File</a>`;
            @else
                previewFile('p_signature', 'signature');
            @endif

            @if($applicationform->equivalent)
                document.getElementById('p_equivalent').innerHTML =
                    `<a href="{{ asset('storage/'.$applicationform->equivalent) }}" target="_blank">View Uploaded File</a>`;
            @else
                previewFile('p_equivalent', 'equivalent');
            @endif

            @if($applicationform->work_experience)
                document.getElementById('p_work_experience').innerHTML =
                    `<a href="{{ asset('storage/'.$applicationform->work_experience) }}" target="_blank">View Uploaded File</a>`;
            @else
                previewFile('p_work_experience', 'work_experience');
            @endif




        // Multiple Educational Certificates Preview
        const eduContainer = document.getElementById('p_academic_certificate');

    @if($applicationform->educational_certificates)
        (function () {
            let files = @json($applicationform->educational_certificates);

            // handle double-encoded JSON
            if (typeof files === 'string') {
                try {
                    files = JSON.parse(files);
                } catch (e) {
                    files = [];
                }
            }

            if (!Array.isArray(files) || files.length === 0) {
                eduContainer.textContent = 'Not Uploaded';
                return;
            }

            let html = '';

            files.forEach(function (file, index) {
                html += `
                    <a href="{{ asset('storage') }}/${file}"
                       target="_blank"
                       class="d-block">
                        View File ${index + 1}
                    </a>
                `;
            });

            eduContainer.innerHTML = html;
        })();
    @else
        eduContainer.textContent = 'Not Uploaded';
    @endif
    }

    document.getElementById('saveDraftBeforePayment').addEventListener('click', function() {
        const formData = new FormData(document.querySelector('form')); // assumes this is inside a <form>
        formData.append('save_as_draft', true); // mark as draft

        fetch('/save-draft', { // replace with your actual endpoint
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if(data.success){
                alert('Draft saved successfully! You can pay later.');
            } else {
                alert('Failed to save draft. Please try again.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while saving the draft.');
        });
    });

    // PAYMENT GATEWAYS
    // window.startPayment = function(gateway) {

    //     const draftId = document.getElementById('draft_id')?.value;

    //     if (!draftId) {
    //         alert("Application draft not found. Please complete form properly.");
    //         return;
    //     }

    //     let url = "";

    //     if (gateway === "esewa") {
    //         url = "/payment/esewa/start/" + draftId;
    //     }
    //     else if (gateway === "khalti") {
    //         url = "/payment/khalti/start/" + draftId;
    //     }
    //     else if (gateway === "connectips") {
    //         url = "/payment/connectips/start/" + draftId;
    //     }

    //     window.location.href = url;
    // }

    // console.log('✓ Form initialized with strict validation, conditional file uploads, preview, and auto-save');
    // console.log('Draft ID on load:', draftIdInput ? draftIdInput.value : 'none');

</script>
@endpush
@endsection
