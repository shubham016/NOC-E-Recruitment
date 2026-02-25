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

            <form action="{{ route('candidate.applications.update', $application->id) }}" method="POST" enctype="multipart/form-data" id="applicationform">
                @csrf
                @method('PUT')

               {{-- STEP 1: Personal Info --}}
                <div class="step active" id="step1">
                    <h5 class="mb-4 text-primary">Step 1 — Personal Information</h5>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="name_english" class="form-label">Full Name (English) <span class="text-danger">*</span> <small>(नाम)</small></label>
                            <input type="text" name="name_english" id="name_english" class="form-control" value="{{ old('name_english', $application->name_english) }}" required>
                        </div>
                        <div class="col-md-6">
                            <label for="name_nepali" class="form-label">Full Name (Nepali) <span class="text-danger">*</span> <small>(नाम)</small></label>
                            <input type="text" name="name_nepali" id="name_nepali" class="form-control" value="{{ old('name_nepali', $application->name_nepali) }}" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <label for="birth_date_bs" class="form-label">
                                Birth Date (B.S) <span class="text-danger">*</span>
                                <small class="text-primary">(जन्म मिति B.S)</small>
                            </label>
                            <input type="text"
                                class="form-control"
                                id="birth_date_bs"
                                name="birth_date_bs"
                                placeholder="YYYY-MM-DD"
                                autocomplete="off"
                                value="{{ old('birth_date_bs', $application->birth_date_bs) }}">
                            <small class="form-text text-primary">
                                <i class="bi bi-info-circle me-1"></i>Click to open Nepali calendar
                            </small>
                        </div>
                        <div class="col-md-3">
                            <label for="birth_date_ad" class="form-label">
                                Birth Date (A.D) <span class="text-danger">*</span>
                                <small>(जन्म मिति A.D)</small>
                            </label>
                            <input type="text"
                                class="form-control"
                                id="birth_date_ad"
                                name="birth_date_ad"
                                placeholder="YYYY-MM-DD"
                                value="{{ old('birth_date_ad', $application->birth_date_ad ? \Carbon\Carbon::parse($application->birth_date_ad)->format('Y-m-d') : '') }}"
                                required
                                readonly>
                            <small class="form-text">
                                <i class="bi bi-info-circle me-1"></i>Auto-synced from Nepali date
                            </small>
                        </div>
                        <div class="col-md-3">
                            <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" name="email" id="email" class="form-control" value="{{ old('email', $application->email) }}" required>
                        </div>
                        <div class="col-md-3">
                            <label for="phone" class="form-label">Phone <span class="text-danger">*</span> <small>(फोन नम्बर)</small></label>
                            <input type="text" name="phone" id="phone" class="form-control" value="{{ old('phone', $application->phone) }}" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="advertisement_no" class="form-label">Advertisement Number <span class="text-danger">*</span></label>
                            <input type="text" name="advertisement_no" id="advertisement_no" class="form-control" value="{{ old('advertisement_no', $application->advertisement_no ?? '') }}" required>
                        </div>
                        <div class="col-md-4">
                            <label for="applying_position" class="form-label">Applying Position <span class="text-danger">*</span></label>
                            <input type="text" name="applying_position" id="applying_position" class="form-control" value="{{ old('applying_position', $application->applying_position ?? '') }}" required>
                        </div>
                        <div class="col-md-4">
                            <label for="department" class="form-label">Department <span class="text-danger">*</span></label>
                            <input type="text" name="department" id="department" class="form-control" value="{{ old('department', $application->department ?? '') }}" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="age" class="form-label">Age <span class="text-danger">*</span> <small>(उमेर)</small></label>
                            <input type="number" name="age" id="age" class="form-control" min="0" value="{{ old('age', $application->age) }}" required>
                        </div>
                        <div class="col-md-4">
                            <label for="alternate_phone_number" class="form-label">Alternate Phone Number <span class="text-danger">*</span> <small>(वैकल्पिक फोन नम्बर)</small></label>
                            <input type="text" name="alternate_phone_number" id="alternate_phone_number" class="form-control" value="{{ old('alternate_phone_number', $application->alternate_phone_number) }}" required>
                        </div>
                        <div class="col-md-4">
                            <label for="gender" class="form-label">Gender <span class="text-danger">*</span> <small>(लिङ्ग)</small></label>
                            <select name="gender" id="gender" class="form-select" required>
                                <option value="">-- Select / छान्नुहोस् --</option>
                                <option value="Male" {{ old('gender', $application->gender) == 'Male' ? 'selected' : '' }}>Male / पुरुष</option>
                                <option value="Female" {{ old('gender', $application->gender) == 'Female' ? 'selected' : '' }}>Female / महिला</option>
                                <option value="Other" {{ old('gender', $application->gender) == 'Other' ? 'selected' : '' }}>Other / अन्य</option>
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="marital_status" class="form-label">Marital Status <span class="text-danger">*</span></label>
                            <select name="marital_status" id="marital_status" class="form-select" required>
                                <option value="">-- Select --</option>
                                <option value="Single" {{ old('marital_status', $application->marital_status) == 'Single' ? 'selected' : '' }}>Single</option>
                                <option value="Married" {{ old('marital_status', $application->marital_status) == 'Married' ? 'selected' : '' }}>Married</option>
                                <option value="Divorced" {{ old('marital_status', $application->marital_status) == 'Divorced' ? 'selected' : '' }}>Divorced</option>
                                <option value="Widowed" {{ old('marital_status', $application->marital_status) == 'Widowed' ? 'selected' : '' }}>Widowed</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="spouse_name_english" class="form-label">Spouse Name (If Married)</label>
                            <input type="text" name="spouse_name_english" id="spouse_name_english" class="form-control" value="{{ old('spouse_name_english',$application->spouse_name_english) }}">
                        </div>
                        <div class="col-md-4">
                            <label for="spouse_nationality" class="form-label">Spouse Nationality (If Married)</label>
                            <input type="text" name="spouse_nationality" id="spouse_nationality" class="form-control" value="{{ old('spouse_nationality',$application->spouse_nationality) }}">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="citizenship_number" class="form-label">Citizenship Number <span class="text-danger">*</span></label>
                            <input type="text" name="citizenship_number" id="citizenship_number" class="form-control" value="{{ old('citizenship_number', $application->citizenship_number) }}" required>
                        </div>
                        <div class="col-md-4">
                            <label for="citizenship_issue_date_bs" class="form-label">
                                Citizenship Issue Date (B.S) <span class="text-danger">*</span>
                                <small class="text-primary">(जारी मिति B.S)</small>
                            </label>
                            <input type="text"
                                class="form-control"
                                id="citizenship_issue_date_bs"
                                name="citizenship_issue_date_bs"
                                placeholder="YYYY-MM-DD"
                                autocomplete="off"
                                value="{{ old('citizenship_issue_date_bs', $application->citizenship_issue_date_bs) }}">
                            <small class="form-text text-primary">
                                <i class="bi bi-info-circle me-1"></i>Click to open Nepali calendar
                            </small>
                        </div>
                        <div class="col-md-4">
                            <label for="citizenship_issue_date_ad" class="form-label">
                                Citizenship Issue Date (A.D) <span class="text-danger">*</span>
                                <small>(जारी मिति A.D)</small>
                            </label>
                            <input type="text"
                                class="form-control"
                                id="citizenship_issue_date_ad"
                                name="citizenship_issue_date_ad"
                                placeholder="YYYY-MM-DD"
                                value="{{ old('citizenship_issue_date_ad', $application->citizenship_issue_date_ad ? \Carbon\Carbon::parse($application->citizenship_issue_date_ad)->format('Y-m-d') : '') }}"
                                required
                                readonly>
                            <small class="form-text">
                                <i class="bi bi-info-circle me-1"></i>Auto-synced from Nepali date
                            </small>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="citizenship_issue_district" class="form-label">Citizenship Issue District <span class="text-danger">*</span></label>
                            <input type="text" name="citizenship_issue_district" id="citizenship_issue_district" class="form-control" value="{{ old('citizenship_issue_district', $application->citizenship_issue_district) }}" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                    <div class="col-md-4">
                            <label for="father_name_english" class="form-label">Father Name (बुबाको नाम) <span class="text-danger">*</span></label>
                            <input type="text" name="father_name_english" id="father_name_english" class="form-control" value="{{ old('father_name_english', $application->father_name_english) }}" required>
                        </div>    
                    <div class="col-md-4">
                            <label for="mother_name_english" class="form-label">Mother Name (आमाको नाम) <span class="text-danger">*</span></label>
                            <input type="text" name="mother_name_english" id="mother_name_english" class="form-control" value="{{ old('mother_name_english', $application->mother_name_english) }}" required>
                        </div>
                        <div class="col-md-4">
                            <label for="grandfather_name_english" class="form-label">Grandfather Name (हजुरबुबाको नाम) <span class="text-danger">*</span></label>
                            <input type="text" name="grandfather_name_english" id="grandfather_name_english" class="form-control" value="{{ old('grandfather_name_english', $application->grandfather_name_english) }}" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="father_qualification" class="form-label">Father's Qualification (बुबाको योग्यता) <span class="text-danger">*</span></label>
                            <input type="text" name="father_qualification" id="father_qualification" class="form-control" value="{{ old('father_qualification', $application->father_qualification) }}" required>
                        </div>
                        <div class="col-md-4">
                            <label for="mother_qualification" class="form-label">Mother's Qualification (आमाको योग्यता) <span class="text-danger">*</span></label>
                            <input type="text" name="mother_qualification" id="mother_qualification" class="form-control" value="{{ old('mother_qualification', $application->mother_qualification) }}" required>
                        </div>
                         <div class="col-md-4">
                            <label for="parent_occupation" class="form-label">Parents's Occupation<span class="text-danger">*</span></label>
                            <input type="text" name="parent_occupation" id="parent_occupation" class="form-control" value="{{ old('parent_occupation', $application->parent_occupation) }}" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="blood_group" class="form-label">Blood Group <span class="text-danger">*</span></label>
                            <input type="text" name="blood_group" id="blood_group" class="form-control" value="{{ old('blood_group', $application->blood_group) }}" required>
                        </div>
                        <div class="col-md-4">
                            <label for="nationality" class="form-label">Nationality <span class="text-danger">*</span></label>
                            <input type="text" name="nationality" id="nationality" class="form-control" value="{{ old('nationality', $application->nationality) }}" required>
                        </div>
                        <div class="col-md-4">
                            <label for="noc_employee" class="form-label">Are you NOC Employee? <span class="text-danger">*</span></label>
                            <select name="noc_employee" id="noc_employee" class="form-select" required>
                                <option value="">-- Select --</option>
                                <option value="yes" {{ old('noc_employee', $application->noc_employee) == 'yes' ? 'selected' : '' }}>Yes</option>
                                <option value="no" {{ old('noc_employee', $application->noc_employee) == 'no' ? 'selected' : '' }}>No</option>
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="noc_id_card" class="form-label">NOC ID Card (If Yes)</label>
                            <input type="file" name="noc_id_card" id="noc_id_card" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                            @if($application->noc_id_card)
                                <small class="text-muted">Current: <a href="{{ asset('storage/' . $application->noc_id_card) }}" target="_blank">View File</a></small>
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
                                <option value="Hindu" {{ old('religion', $application->religion) == 'Hindu' ? 'selected' : '' }}>Hindu / हिन्दू</option>
                                <option value="Buddhist" {{ old('religion', $application->religion) == 'Buddhist' ? 'selected' : '' }}>Buddhist / बौद्ध</option>
                                <option value="Christian" {{ old('religion', $application->religion) == 'Christian' ? 'selected' : '' }}>Christian / ख्रीष्टिय</option>
                                <option value="Muslim" {{ old('religion', $application->religion) == 'Muslim' ? 'selected' : '' }}>Muslim / मुस्लिम</option>
                                <option value="Other" {{ old('religion', $application->religion) == 'Other' ? 'selected' : '' }}>Other / अन्य</option>
                            </select>
                            <input type="text" name="religion_other" id="religion_other" class="form-control mt-2 d-none" placeholder="If other, specify" value="{{ old('religion_other', $application->religion_other) }}">
                        </div>
                        <div class="col-md-4">
                            <label for="community" class="form-label">Community <span class="text-danger">*</span> <small>(तपाई आफैलाई के बोलाउन रुचाउनुहुन्छ)</small></label>
                            <select name="community" id="community" class="form-select" required>
                                <option value="">-- Select --</option>
                                <option value="Male" {{ old('community', $application->community) == 'Male' ? 'selected' : '' }}>पुरुष</option>
                                <option value="Female" {{ old('community', $application->community) == 'Female' ? 'selected' : '' }}>महिला</option>
                                <option value="LGBTQ" {{ old('community', $application->community) == 'LGBTQ' ? 'selected' : '' }}>LGBTQ+</option>
                                <option value="Other" {{ old('community', $application->community) == 'Other' ? 'selected' : '' }}>Other / अन्य</option>
                            </select>
                            <input type="text" name="community_other" id="community_other" class="form-control mt-2 d-none" placeholder="If other, specify" value="{{ old('community_other', $application->community_other) }}">
                        </div>
                        <div class="col-md-4">
                            <label for="ethnic_group" class="form-label">Ethnic Group <span class="text-danger">*</span> <small>(जातीय समूह)</small></label>
                            <select name="ethnic_group" id="ethnic_group" class="form-select" required>
                                <option value="">-- Select --</option>
                                <option value="Dalit" {{ old('ethnic_group', $application->ethnic_group) == 'Dalit' ? 'selected' : '' }}>Dalit</option>
                                <option value="Janajati" {{ old('ethnic_group', $application->ethnic_group) == 'Janajati' ? 'selected' : '' }}>Janajati</option>
                                <option value="Madhesi" {{ old('ethnic_group', $application->ethnic_group) == 'Madhesi' ? 'selected' : '' }}>Madhesi</option>
                                <option value="Brahmin/Chhetri" {{ old('ethnic_group', $application->ethnic_group) == 'Brahmin/Chhetri' ? 'selected' : '' }}>Brahmin / Chhetri</option>
                                <option value="Other" {{ old('ethnic_group', $application->ethnic_group) == 'Other' ? 'selected' : '' }}>Other</option>
                            </select>
                            <input type="text" name="ethnic_group_other" id="ethnic_group_other" class="form-control mt-2 d-none" placeholder="If other, specify" value="{{ old('ethnic_group_other', $application->ethnic_group_other) }}">
                        </div>
                    </div>

                    <div class="row mb-3">
                       <div class="col-md-6">
                            <label for="ethnic_certificate" class="form-label">Ethnic Certificate</label>
                            <input type="file" name="ethnic_certificate" id="ethnic_certificate" class="form-control" accept="image/*,application/pdf">
                            @if($application->ethnic_certificate)
                                <small class="text-muted">Current: <a href="{{ asset('storage/' . $application->ethnic_certificate) }}" target="_blank">View File</a></small>
                            @endif
                            <small class="text-muted d-block">Max size: 2MB</small>
                        </div>
                        <div class="col-md-6">
                            <label for="mother_tongue" class="form-label">Mother Tongue <span class="text-danger">*</span> <small>(मातृभाषा)</small></label>
                            <input type="text" name="mother_tongue" id="mother_tongue" class="form-control" value="{{ old('mother_tongue', $application->mother_tongue) }}" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="employment_status" class="form-label">Employment Status <span class="text-danger">*</span> <small>(रोजगार स्थिति)</small></label>
                            <select name="employment_status" id="employment_status" class="form-select" required>
                                <option value="">-- Select --</option>
                                <option value="employed" {{ old('employment_status', $application->employment_status) == 'employed' ? 'selected' : '' }}>Employed</option>
                                <option value="unemployed" {{ old('employment_status', $application->employment_status) == 'unemployed' ? 'selected' : '' }}>Unemployed</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="physical_disability" class="form-label">Physical Disability <span class="text-danger">*</span> <small>(कुनै पनि असक्षमता?)</small></label>
                            <select name="physical_disability" id="physical_disability" class="form-select" required>
                                <option value="">-- Select --</option>
                                <option value="yes" {{ old('physical_disability', $application->physical_disability) == 'yes' ? 'selected' : '' }}>Yes</option>
                                <option value="no" {{ old('physical_disability', $application->physical_disability) == 'no' ? 'selected' : '' }}>No</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="disability_certificate" class="form-label">Disability Certificate (If Any)</label>
                            <input type="file" name="disability_certificate" id="disability_certificate" class="form-control" accept="image/*,application/pdf">
                            @if($application->disability_certificate)
                                <small class="text-muted">Current: <a href="{{ asset('storage/' . $application->disability_certificate) }}" target="_blank">View File</a></small>
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
                                <option value="Koshi" {{ old('permanent_province', $application->permanent_province) == 'Koshi' ? 'selected' : '' }}>Koshi</option>
                                <option value="Madhesh" {{ old('permanent_province', $application->permanent_province) == 'Madhesh' ? 'selected' : '' }}>Madhesh</option>
                                <option value="Bagmati" {{ old('permanent_province', $application->permanent_province) == 'Bagmati' ? 'selected' : '' }}>Bagmati</option>
                                <option value="Gandaki" {{ old('permanent_province', $application->permanent_province) == 'Gandaki' ? 'selected' : '' }}>Gandaki</option>
                                <option value="Lumbini" {{ old('permanent_province', $application->permanent_province) == 'Lumbini' ? 'selected' : '' }}>Lumbini</option>
                                <option value="Karnali" {{ old('permanent_province', $application->permanent_province) == 'Karnali' ? 'selected' : '' }}>Karnali</option>
                                <option value="Sudurpashchim" {{ old('permanent_province', $application->permanent_province) == 'Sudurpashchim' ? 'selected' : '' }}>Sudurpashchim</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="permanent_district" class="form-label">District <span class="text-danger">*</span></label>
                            <input type="text" name="permanent_district" id="permanent_district" class="form-control" value="{{ old('permanent_district', $application->permanent_district) }}" required>
                        </div>
                        <div class="col-md-4">
                            <label for="permanent_municipality" class="form-label">Municipality <span class="text-danger">*</span></label>
                            <input type="text" name="permanent_municipality" id="permanent_municipality" class="form-control" value="{{ old('permanent_municipality', $application->permanent_municipality) }}" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="permanent_ward" class="form-label">Ward No. <span class="text-danger">*</span></label>
                            <input type="text" name="permanent_ward" id="permanent_ward" class="form-control" value="{{ old('permanent_ward', $application->permanent_ward) }}" required>
                        </div>
                        <div class="col-md-4">
                            <label for="permanent_tole" class="form-label">Tole</label>
                            <input type="text" name="permanent_tole" id="permanent_tole" class="form-control" value="{{ old('permanent_tole', $application->permanent_tole) }}">
                        </div>
                        <div class="col-md-4">
                            <label for="permanent_house_number" class="form-label">House Number</label>
                            <input type="text" name="permanent_house_number" id="permanent_house_number" class="form-control" value="{{ old('permanent_house_number', $application->permanent_house_number) }}">
                        </div>
                    </div>

                    <h5 class="mb-4 text-primary mt-4">Mailing/Current Address</h5>
                    <div class="form-check mb-4">
                        <input type="checkbox" class="form-check-input" id="same_as_permanent" name="same_as_permanent" value="1" {{ old('same_as_permanent', $application->same_as_permanent) ? 'checked' : '' }}>
                        <label class="form-check-label" for="same_as_permanent">Same as Permanent Address</label>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="mailing_province" class="form-label">Province <span class="text-danger">*</span></label>
                            <select name="mailing_province" id="mailing_province" class="form-select" required>
                                <option value="">-- Select --</option>
                                <option value="Koshi" {{ old('mailing_province', $application->mailing_province) == 'Koshi' ? 'selected' : '' }}>Koshi</option>
                                <option value="Madhesh" {{ old('mailing_province', $application->mailing_province) == 'Madhesh' ? 'selected' : '' }}>Madhesh</option>
                                <option value="Bagmati" {{ old('mailing_province', $application->mailing_province) == 'Bagmati' ? 'selected' : '' }}>Bagmati</option>
                                <option value="Gandaki" {{ old('mailing_province', $application->mailing_province) == 'Gandaki' ? 'selected' : '' }}>Gandaki</option>
                                <option value="Lumbini" {{ old('mailing_province', $application->mailing_province) == 'Lumbini' ? 'selected' : '' }}>Lumbini</option>
                                <option value="Karnali" {{ old('mailing_province', $application->mailing_province) == 'Karnali' ? 'selected' : '' }}>Karnali</option>
                                <option value="Sudurpashchim" {{ old('mailing_province', $application->mailing_province) == 'Sudurpashchim' ? 'selected' : '' }}>Sudurpashchim</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="mailing_district" class="form-label">District <span class="text-danger">*</span></label>
                            <input type="text" name="mailing_district" id="mailing_district" class="form-control" value="{{ old('mailing_district', $application->mailing_district) }}" required>
                        </div>
                        <div class="col-md-4">
                            <label for="mailing_municipality" class="form-label">Municipality <span class="text-danger">*</span></label>
                            <input type="text" name="mailing_municipality" id="mailing_municipality" class="form-control" value="{{ old('mailing_municipality', $application->mailing_municipality) }}" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="mailing_ward" class="form-label">Ward No. <span class="text-danger">*</span></label>
                            <input type="text" name="mailing_ward" id="mailing_ward" class="form-control" value="{{ old('mailing_ward', $application->mailing_ward) }}" required>
                        </div>
                        <div class="col-md-4">
                            <label for="mailing_tole" class="form-label">Tole</label>
                            <input type="text" name="mailing_tole" id="mailing_tole" class="form-control" value="{{ old('mailing_tole', $application->mailing_tole) }}">
                        </div>
                        <div class="col-md-4">
                            <label for="mailing_house_number" class="form-label">House Number</label>
                            <input type="text" name="mailing_house_number" id="mailing_house_number" class="form-control" value="{{ old('mailing_house_number', $application->mailing_house_number) }}">
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
                                <option value="Under SLC" {{ old('education_level', $application->education_level) == 'Under SLC' ? 'selected' : '' }}>Under SLC</option>
                                <option value="SLC/SEE" {{ old('education_level', $application->education_level) == 'SLC/SEE' ? 'selected' : '' }}>SLC/SEE</option>
                                <option value="+2/Intermediate" {{ old('education_level', $application->education_level) == '+2/Intermediate' ? 'selected' : '' }}>+2/Intermediate</option>
                                <option value="Bachelor" {{ old('education_level', $application->education_level) == 'Bachelor' ? 'selected' : '' }}>Bachelor</option>
                                <option value="Master" {{ old('education_level', $application->education_level) == 'Master' ? 'selected' : '' }}>Master</option>
                                <option value="PhD" {{ old('education_level', $application->education_level) == 'PhD' ? 'selected' : '' }}>PhD</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="field_of_study" class="form-label">Field of Study<span class="text-danger">*</span></label>
                            <input type="text" name="field_of_study" id="field_of_study" class="form-control" value="{{ old('field_of_study', $application->field_of_study) }}" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="institution_name" class="form-label">Institution Name<span class="text-danger">*</span></label>
                            <input type="text" name="institution_name" id="institution_name" class="form-control" value="{{ old('institution_name', $application->institution_name) }}" required>
                        </div>
                        <div class="col-md-6">
                            <label for="graduation_year" class="form-label">Graduation Year<span class="text-danger">*</span></label>
                            <input type="number" name="graduation_year" id="graduation_year" class="form-control" min="1950" max="2030" value="{{ old('graduation_year', $application->graduation_year) }}" required>
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
                                <option value="Yes" {{ old('has_work_experience', $application->has_work_experience) == 'Yes' ? 'selected' : '' }}>Yes</option>
                                <option value="No" {{ old('has_work_experience', $application->has_work_experience) == 'No' ? 'selected' : '' }}>No</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="years_of_experience" class="form-label">Years of Experience</label>
                            <input type="number" name="years_of_experience" id="years_of_experience" class="form-control" min="0" step="0.5" value="{{ old('years_of_experience', $application->years_of_experience) }}">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="previous_organization" class="form-label">Previous Organization</label>
                            <input type="text" name="previous_organization" id="previous_organization" class="form-control" value="{{ old('previous_organization', $application->previous_organization) }}">
                        </div>
                        <div class="col-md-6">
                            <label for="previous_position" class="form-label">Previous Position</label>
                            <input type="text" name="previous_position" id="previous_position" class="form-control" value="{{ old('previous_position', $application->previous_position) }}">
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
                            <label for="passport_photo" class="form-label">Passport Size Photo</label>
                            <input type="file" name="passport_photo" id="passport_photo" class="form-control" accept=".jpg,.jpeg,.png">
                            @if($application->passport_photo)
                                <small class="text-muted">Current: <a href="{{ asset('storage/' . $application->passport_photo) }}" target="_blank">View File</a></small>
                            @endif
                            <small class="text-muted d-block">Max size: 2MB</small>
                        </div>
                        <div class="col-md-6">
                            <label for="citizenship_certificate" class="form-label">Citizenship/ID Document<span class="text-danger"><small> (Please upload front and back in same page)</small></span></label>
                            <input type="file" name="citizenship_certificate" id="citizenship_certificate" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                            @if($application->citizenship_certificate)
                                <small class="text-muted">Current: <a href="{{ asset('storage/' . $application->citizenship_certificate) }}" target="_blank">View File</a></small>
                            @endif
                            <small class="text-muted d-block">Max size: 2MB</small>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="educational_certificates" class="form-label">Transcript Certificate</label>
                            <input type="file" name="educational_certificates" id="educational_certificates" class="form-control" accept=".pdf,.zip">
                            @if($application->educational_certificates)
                                <small class="text-muted">Current: <a href="{{ asset('storage/' . $application->educational_certificates) }}" target="_blank">View File</a></small>
                            <small class="text-muted d-block">Max size: 10MB (PDF or ZIP only)</small>
                            @endif
                            <small class="text-muted d-block">Max size: 2MB</small>
                        </div>

                       <div class="col-md-6">
                            <label for="character_certificate" class="form-label">Character Certificate</label>
                            {{-- FIXED: Added closing > tag --}}
                            <input type="file" name="character_certificate" id="character_certificate" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                            @if($application->character_certificate)
                                <small class="text-muted">Current: <a href="{{ asset('storage/' . $application->character_certificate) }}" target="_blank">View File</a></small>
                            @endif
                            <small class="text-muted d-block">Max size: 2MB (PDF/JPG/PNG)</small>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="equivalency_certificate" class="form-label">Equivalency Certificate (If your degree is out of Nepal.)</label>
                            {{-- FIXED: Removed value attribute from file input --}}
                            <input type="file" name="equivalency_certificate" id="equivalency_certificate" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                            @if($application->equivalency_certificate)
                                <small class="text-muted">Current: <a href="{{ asset('storage/' . $application->equivalency_certificate) }}" target="_blank">View File</a></small>
                            @endif
                            <small class="text-muted d-block">Max size: 2MB (PDF/JPG/PNG)</small>
                        </div>

                        <div class="col-md-6">
                            <label for="experience_certificates" class="form-label">Work Experience Document</label>
                            {{-- FIXED: Removed value attribute from file input --}}
                            <input type="file" name="experience_certificates" id="experience_certificates" class="form-control" accept=".pdf,.zip">
                            @if($application->experience_certificates)
                                <small class="text-muted">Current: <a href="{{ asset('storage/' . $application->experience_certificates) }}" target="_blank">View File</a></small>
                            @endif
                            <small class="text-muted d-block">Max size: 10MB (PDF/ZIP only)</small>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="signature" class="form-label">Signature</label>
                            {{-- FIXED: Removed value attribute from file input --}}
                            <input type="file" name="signature" id="signature" class="form-control" accept=".jpg,.jpeg,.png">
                            @if($application->signature)
                                <small class="text-muted">Current: <a href="{{ asset('storage/' . $application->signature) }}" target="_blank">View File</a></small>
                            @endif
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
                                <td id="p_transcript"></td>
                            </tr>
                            <tr>
                                <th>Character</th>
                                <td id="p_character"></td>
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

                        <div class="form-check mb-4">
                                <input type="checkbox" class="form-check-input" id="terms_agree" name="terms_agree" required>
                                <label class="form-check-label" for="terms_agree">
                                    I hereby declare that all information provided is true and correct. <span class="text-danger">*</span>
                                </label>
                            </div>

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
                            <a href="{{ route('candidate.applications.index') }}" class="btn btn-primary">
                                Save Your Application
                            </a>
                            </div>

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

    // ============================================
    // CRITICAL: Numeral conversion functions for Nepali Date Picker
    // ============================================

    // Convert Nepali numerals to English
    function nepaliToEnglish(str) {
        if (!str) return str;
        const map = {'०':'0', '१':'1', '२':'2', '३':'3', '४':'4', '५':'5', '६':'6', '७':'7', '८':'8', '९':'9'};
        return str.replace(/[०-९]/g, d => map[d]);
    }

    // Convert English numerals to Nepali for display
    function englishToNepali(str) {
        if (!str) return str;
        const map = {'0':'०', '1':'१', '2':'२', '3':'३', '4':'४', '5':'५', '6':'६', '7':'७', '8':'८', '9':'९'};
        return str.replace(/[0-9]/g, d => map[d]);
    }

    // ============================================
    // Birth Date Nepali Date Picker Initialization
    // ============================================
    function initializeBirthDatePicker() {
        const birthDateBS = document.getElementById('birth_date_bs');
        const birthDateAD = document.getElementById('birth_date_ad');

        if (!birthDateBS || !birthDateAD) {
            console.error('❌ Birth date elements not found!');
            return;
        }

        // Initialize Nepali Date Picker
        $('#birth_date_bs').nepaliDatePicker({
            dateFormat: 'YYYY-MM-DD',
            closeOnDateSelect: true,
            unicodeDate: true,
            ndpYear: true,
            ndpMonth: true,
            ndpYearCount: 50
        });

        console.log('✅ Birth Date Nepali Date Picker initialized');

        // ============================================
        // WORKING SOLUTION: Use polling to detect changes
        // ============================================
        let lastBSValue = $('#birth_date_bs').val() || '';

        const pollInterval = setInterval(function() {
            const currentBSValue = $('#birth_date_bs').val();

            // Check if value changed and is valid
            if (currentBSValue &&
                currentBSValue !== lastBSValue &&
                currentBSValue !== 'YYYY-MM-DD' &&
                currentBSValue.length >= 10) {

                console.log('📅 BS Birth Date changed (polling detected):', currentBSValue);
                lastBSValue = currentBSValue;

                // Convert Nepali numerals to English for calculation
                const bsValueEnglish = nepaliToEnglish(currentBSValue);
                console.log('🔢 After numeral conversion:', bsValueEnglish);

                // Convert BS to AD
                const adValue = window.bsToAD(bsValueEnglish);
                console.log('✅ AD Result:', adValue);

                if (adValue) {
                    // Update the English date field (this goes to database)
                    birthDateAD.value = adValue;
                    console.log('✅ Birth date AD field updated:', adValue);
                }
            }
        }, 200); // Check every 200ms

        // Initialize on page load
        setTimeout(function() {
            const existingBSValue = $('#birth_date_bs').val();

            // If BS field already has a value (from database), convert English numerals to Nepali
            if (existingBSValue && existingBSValue.match(/[0-9]/)) {
                console.log('📅 Converting existing Birth Date BS to Nepali numerals:', existingBSValue);
                const bsNepali = englishToNepali(existingBSValue);
                $('#birth_date_bs').val(bsNepali);
                lastBSValue = bsNepali;
                console.log('✅ Birth Date BS converted to Nepali:', bsNepali);

                // Also update AD field if empty
                if (!birthDateAD.value) {
                    const adValue = window.bsToAD(existingBSValue);
                    if (adValue) {
                        birthDateAD.value = adValue;
                    }
                }
            }
            // If only AD value exists, convert to BS
            else if (birthDateAD.value && !existingBSValue) {
                console.log('📅 Initializing Birth Date BS from existing AD date:', birthDateAD.value);
                const bsValue = window.adToBS(birthDateAD.value);
                console.log('✅ Initial BS (English numerals):', bsValue);

                if (bsValue) {
                    const bsNepali = englishToNepali(bsValue);
                    $('#birth_date_bs').val(bsNepali);
                    lastBSValue = bsNepali;
                    console.log('✅ Birth Date BS initialized:', bsNepali);
                }
            }
        }, 500);
    }

    // ============================================
    // Citizenship Issue Date Nepali Date Picker Initialization
    // ============================================
    function initializeCitizenshipIssueDatePicker() {
        const citizenshipIssueDateBS = document.getElementById('citizenship_issue_date_bs');
        const citizenshipIssueDateAD = document.getElementById('citizenship_issue_date_ad');

        if (!citizenshipIssueDateBS || !citizenshipIssueDateAD) {
            console.error('❌ Citizenship issue date elements not found!');
            return;
        }

        // Initialize Nepali Date Picker
        $('#citizenship_issue_date_bs').nepaliDatePicker({
            dateFormat: 'YYYY-MM-DD',
            closeOnDateSelect: true,
            unicodeDate: true,
            ndpYear: true,
            ndpMonth: true,
            ndpYearCount: 50
        });

        console.log('✅ Citizenship Issue Date Nepali Date Picker initialized');

        // ============================================
        // WORKING SOLUTION: Use polling to detect changes
        // ============================================
        let lastBSValue = $('#citizenship_issue_date_bs').val() || '';

        const pollInterval = setInterval(function() {
            const currentBSValue = $('#citizenship_issue_date_bs').val();

            // Check if value changed and is valid
            if (currentBSValue &&
                currentBSValue !== lastBSValue &&
                currentBSValue !== 'YYYY-MM-DD' &&
                currentBSValue.length >= 10) {

                console.log('📅 BS Citizenship Issue Date changed (polling detected):', currentBSValue);
                lastBSValue = currentBSValue;

                // Convert Nepali numerals to English for calculation
                const bsValueEnglish = nepaliToEnglish(currentBSValue);
                console.log('🔢 After numeral conversion:', bsValueEnglish);

                // Convert BS to AD
                const adValue = window.bsToAD(bsValueEnglish);
                console.log('✅ AD Result:', adValue);

                if (adValue) {
                    // Update the English date field (this goes to database)
                    citizenshipIssueDateAD.value = adValue;
                    console.log('✅ Citizenship issue date AD field updated:', adValue);
                }
            }
        }, 200); // Check every 200ms

        // Initialize on page load
        setTimeout(function() {
            const existingBSValue = $('#citizenship_issue_date_bs').val();

            // If BS field already has a value (from database), convert English numerals to Nepali
            if (existingBSValue && existingBSValue.match(/[0-9]/)) {
                console.log('📅 Converting existing Citizenship Issue Date BS to Nepali numerals:', existingBSValue);
                const bsNepali = englishToNepali(existingBSValue);
                $('#citizenship_issue_date_bs').val(bsNepali);
                lastBSValue = bsNepali;
                console.log('✅ Citizenship Issue Date BS converted to Nepali:', bsNepali);

                // Also update AD field if empty
                if (!citizenshipIssueDateAD.value) {
                    const adValue = window.bsToAD(existingBSValue);
                    if (adValue) {
                        citizenshipIssueDateAD.value = adValue;
                    }
                }
            }
            // If only AD value exists, convert to BS
            else if (citizenshipIssueDateAD.value && !existingBSValue) {
                console.log('📅 Initializing Citizenship Issue Date BS from existing AD date:', citizenshipIssueDateAD.value);
                const bsValue = window.adToBS(citizenshipIssueDateAD.value);
                console.log('✅ Initial BS (English numerals):', bsValue);

                if (bsValue) {
                    const bsNepali = englishToNepali(bsValue);
                    $('#citizenship_issue_date_bs').val(bsNepali);
                    lastBSValue = bsNepali;
                    console.log('✅ Citizenship Issue Date BS initialized:', bsNepali);
                }
            }
        }, 500);
    }

    // Wait for converter functions to be ready from dashboard.blade.php
    function waitForConverter() {
        if (typeof window.bsToAD !== 'function' || typeof window.adToBS !== 'function') {
            console.log('⏳ Waiting for date converter...');
            setTimeout(waitForConverter, 100);
            return;
        }

        console.log('✅ Date converter ready!');
        initializeBirthDatePicker();
        initializeCitizenshipIssueDatePicker();
    }

    // Start waiting for converter
    waitForConverter();

    let currentStep = 1;
    const totalSteps = 8; // Changed from 8 to 7 since no payment step
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
        const progressLine = document.querySelector('.progress-line');
        if (progressLine) {
            progressLine.style.width = progressPercent + '%';
        }
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
        if (!stepEl) return true;

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
            
            if (targetStep < currentStep || (targetStep === currentStep + 1 && validateStep(currentStep))) {
                showStep(targetStep);
            } else if (targetStep > currentStep) {
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
                const mailingField = document.getElementById('mailing_' + field);
                if (mailingField) {
                    mailingField.value = perm;
                }
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

    // Preview Population Function
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

        // Document Preview Function
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

        // File previews with existing files check
        @if($application->passport_photo)
            document.getElementById('p_photo').innerHTML =
                `<a href="{{ asset('storage/'.$application->passport_photo) }}" target="_blank">View Uploaded File</a>`;
        @else
            previewFile('p_photo', 'passport_photo');
        @endif

        @if($application->citizenship_certificate)
            document.getElementById('p_citizenship').innerHTML =
                `<a href="{{ asset('storage/'.$application->citizenship_certificate) }}" target="_blank">View Uploaded File</a>`;
        @else
            previewFile('p_citizenship', 'citizenship_certificate');
        @endif

        @if($application->educational_certificates)
            document.getElementById('p_transcript').innerHTML =
                `<a href="{{ asset('storage/'.$application->educational_certificates) }}" target="_blank">View Uploaded File</a>`;
        @else
            previewFile('p_transcript', 'transcript');
        @endif

        @if($application->character_certificate)
            document.getElementById('p_character').innerHTML =
                `<a href="{{ asset('storage/'.$application->character_certificate) }}" target="_blank">View Uploaded File</a>`;
        @else
            previewFile('p_character', 'character');
        @endif

        @if($application->signature)
            document.getElementById('p_signature').innerHTML =
                `<a href="{{ asset('storage/'.$application->signature) }}" target="_blank">View Uploaded File</a>`;
        @else
            previewFile('p_signature', 'signature');
        @endif

        @if($application->equivalency_certificate)
            document.getElementById('p_equivalent').innerHTML =
                `<a href="{{ asset('storage/'.$application->equivalency_certificate) }}" target="_blank">View Uploaded File</a>`;
        @else
            previewFile('p_equivalent', 'equivalent');
        @endif

        @if($application->experience_certificates)
            document.getElementById('p_work_experience').innerHTML =
                `<a href="{{ asset('storage/'.$application->experience_certificates) }}" target="_blank">View Uploaded File</a>`;
        @else
            previewFile('p_work_experience', 'experience_certificates');
        @endif
    }

    // PAYMENT GATEWAYS
    window.startPayment = function(gateway) {

    const applicationId = "{{ $application->id ?? '' }}";

    if (!applicationId) {
        alert("Application ID not found. Please save the form first.");
        return;
    }

    let url = "";

    if (gateway === "esewa") {
        url = "/candidate/payment/esewa/start/" + applicationId;
    }
    else if (gateway === "khalti") {
        url = "/candidate/payment/khalti/start/" + applicationId;
    }
    else if (gateway === "connectips") {
        url = "/candidate/payment/connectips/start/" + applicationId;
    }

    window.location.href = url;
}


    console.log('✓ Form initialized with strict validation, conditional file uploads, preview, and auto-save');
    console.log('Application ID on load:', applicationId);

});
</script>
@endpush
@endsection