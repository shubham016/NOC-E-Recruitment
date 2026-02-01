@extends('layouts.app')
@section('title', 'Create Application Form')
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
            <h3 class="mb-0 fw-bold">NOC | New Application Form</h3>
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
                        <span class="tab-label d-none d-md-inline">Address 1</span>
                    </div>
                    <div class="tab-item" data-step="4">
                        <span class="tab-circle">4</span>
                        <span class="tab-label d-none d-md-inline">Address 2</span>
                    </div>
                    <div class="tab-item" data-step="5">
                        <span class="tab-circle">5</span>
                        <span class="tab-label d-none d-md-inline">Education</span>
                    </div>
                    <div class="tab-item" data-step="6">
                        <span class="tab-circle">6</span>
                        <span class="tab-label d-none d-md-inline">Experience</span>
                    </div>
                    <div class="tab-item" data-step="7">
                        <span class="tab-circle">7</span>
                        <span class="tab-label d-none d-md-inline">Documents</span>
                    </div>
                </div>
            </div>

            <form action="{{ route('candidate.applications.store') }}" method="POST" enctype="multipart/form-data" id="applicationform">
                @csrf
                <input type="hidden" name="draft_id" id="draft_id" value="{{ $draftApplication->id ?? '' }}">
                @if($job)
                <input type="hidden" name="job_posting_id" value="{{ $job->id }}">
                @endif

                {{-- STEP 1: Personal Info --}}
                <div class="step" id="step1">
                    <h5 class="mb-4 text-primary">Step 1 — Personal Information</h5>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="name_english" class="form-label">Full Name (English) <span class="text-danger">*</span> <small>(पुरा नाम अंग्रेजी)</small></label>
                            <input type="text" name="name_english" id="name_english" class="form-control" value="{{ old('name_english', $draftApplication->name_english ?? $candidate->name) }}" required>
                        </div>
                        <div class="col-md-6">
                            <label for="name_nepali" class="form-label">Full Name (Nepali) <span class="text-danger">*</span> <small>(पुरा नाम नेपाली)</small></label>
                            <input type="text" name="name_nepali" id="name_nepali" class="form-control" value="{{ old('name_nepali', $draftApplication->name_nepali ?? '') }}" required>
                        </div>
                        </div>
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <label for="birth_date_ad" class="form-label">Birth Date (A.D) <span class="text-danger">*</span> <small>(जन्म मिति A.D)</small></label>
                            <input type="date" name="birth_date_ad" id="birth_date_ad" class="form-control" 
                                value="{{ old('birth_date_ad', $draftApplication->birth_date_ad ?? '') }}" required>
                        </div>
                        <div class="col-md-3">
                            <label for="birth_date_bs" class="form-label">Birth Date (B.S) <span class="text-danger">*</span> <small>(जन्म मिति B.S)</small></label>
                            <input type="text" name="birth_date_bs" id="birth_date_bs" class="form-control" placeholder="YYYY-MM-DD" 
                                value="{{ old('birth_date_bs', $draftApplication->birth_date_bs ?? $candidate->date_of_birth_bs) }}" required>
                        </div>
                        <div class="col-md-3">
                            <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="text" name="email" id="email" class="form-control"
                                value="{{ old('email', $draftApplication->email ?? $candidate->email) }}" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="advertisement_no" class="form-label">Advertisement Number <span class="text-danger">*</span></label>
                            <input type="text" name="advertisement_no" id="advertisement_no" class="form-control" value="{{ old('advertisement_no', $draftApplication->advertisement_no ?? '') }}" required>
                        </div>
                        <div class="col-md-4">
                            <label for="applying_position" class="form-label">Applying Position <span class="text-danger">*</span></label>
                            <input type="text" name="applying_position" id="applying_position" class="form-control" value="{{ old('applying_position', $draftApplication->applying_position ?? '') }}" required>
                        </div>
                        <div class="col-md-3">
                            <label for="department" class="form-label">Department <span class="text-danger">*</span></label>
                            <input type="text" name="department" id="department" class="form-control" 
                                value="{{ old('department', $draftApplication->department ?? '') }}" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="age" class="form-label">Age <span class="text-danger">*</span> <small>(उमेर)</small></label>
                            <input type="number" name="age" id="age" class="form-control" min="0" 
                                value="{{ old('age', $draftApplication->age ?? '') }}" required>
                        </div>
                        <div class="col-md-4">
                            <label for="phone" class="form-label">Phone Number <span class="text-danger">*</span> <small>(फोन नम्बर)</small></label>
                            <input type="text" name="phone" id="phone" class="form-control" value="{{ old('phone', $draftApplication->phone ?? '') }}" required>
                        </div>
                        <div class="col-md-4">
                            <label for="gender" class="form-label">Gender <span class="text-danger">*</span> <small>(लिङ्ग)</small></label>
                            <select name="gender" id="gender" class="form-select" required>
                                <option value="">-- Select / छान्नुहोस् --</option>
                                <option value="Male" {{ old('gender', $draftApplication->gender ?? $candidate->gender) == 'Male' ? 'selected' : '' }}>Male / पुरुष</option>
                                <option value="Female" {{ old('gender', $draftApplication->gender ?? $candidate->gender) == 'Female' ? 'selected' : '' }}>Female / महिला</option>
                                <option value="Other" {{ old('gender', $draftApplication->gender ?? $candidate->gender) == 'Other' ? 'selected' : '' }}>Other / अन्य</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="marital_status" class="form-label">Marital Status <span class="text-danger">*</span></label>
                            <select name="marital_status" id="marital_status" class="form-select" required>
                                <option value="">-- Select --</option>
                                <option value="Single" {{ old('marital_status', $draftApplication->marital_status ?? '') == 'Single' ? 'selected' : '' }}>Single</option>
                                <option value="Married" {{ old('marital_status', $draftApplication->marital_status ?? '') == 'Married' ? 'selected' : '' }}>Married</option>
                                <option value="Divorced" {{ old('marital_status', $draftApplication->marital_status ?? '') == 'Divorced' ? 'selected' : '' }}>Divorced</option>
                                <option value="Widowed" {{ old('marital_status', $draftApplication->marital_status ?? '') == 'Widowed' ? 'selected' : '' }}>Widowed</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="spouse_name_english" class="form-label">Spouse Name (If Married)</label>
                            <input type="text" name="spouse_name_english" id="spouse_name_english" class="form-control" value="{{ old('spouse_name_english', $draftApplication->spouse_name_english ?? '') }}">
                        </div>
                        <div class="col-md-4">
                            <label for="spouse_nationality" class="form-label">Spouse Nationality (If Married)</label>
                            <input type="text" name="spouse_nationality" id="spouse_nationality" class="form-control" value="{{ old('spouse_nationality', $draftApplication->spouse_nationality ?? '') }}">
                        </div>
                        </div>
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="citizenship_number" class="form-label">Citizenship Number <span class="text-danger">*</span></label>
                            <input type="text" name="citizenship_number" id="citizenship_number" class="form-control" 
                                value="{{ old('citizenship_number', $draftApplication->citizenship_number ?? $candidate->citizenship_number) }}" required>
                        </div>
                        <div class="col-md-4">
                            <label for="citizenship_issue_date_bs" class="form-label">Citizenship Issue Date (B.S)<span class="text-danger">*</span></label>
                            <input type="text" name="citizenship_issue_date_bs" id="citizenship_issue_date_bs" class="form-control" 
                                value="{{ old('citizenship_issue_date_bs', $draftApplication->citizenship_issue_date_bs ?? $candidate->citizenship_issue_date_bs) }}" required>
                        </div>
                        <div class="col-md-4">
                            <label for="citizenship_issue_district" class="form-label">Citizenship Issue District <span class="text-danger">*</span></label>
                            <input type="text" name="citizenship_issue_district" id="citizenship_issue_district" class="form-control" 
                                value="{{ old('citizenship_issue_district', $draftApplication->citizenship_issue_district ?? $candidate->citizenship_issue_distric) }}" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="father_name_english" class="form-label">Father Name (बुबाको नाम) <span class="text-danger">*</span></label>
                            <input type="text" name="father_name_english" id="father_name_english" class="form-control" value="{{ old('father_name_english', $draftApplication->father_name_english ?? '') }}" required>
                        </div>
                        <div class="col-md-4">
                            <label for="mother_name_english" class="form-label">Mother Name (आमाको नाम) <span class="text-danger">*</span></label>
                            <input type="text" name="mother_name_english" id="mother_name_english" class="form-control" value="{{ old('mother_name_english', $draftApplication->mother_name_english ?? '') }}" required>
                        </div>
                        <div class="col-md-4">
                            <label for="grandfather_name_english" class="form-label">Grandfather Name (हजुरबुबाको नाम) <span class="text-danger">*</span></label>
                            <input type="text" name="grandfather_name_english" id="grandfather_name_english" class="form-control" value="{{ old('grandfather_name_english', $draftApplication->grandfather_name_english ?? '') }}" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="father_qualification" class="form-label">Father's Qualification (बुबाको योग्यता) <span class="text-danger">*</span></label>
                            <input type="text" name="father_qualification" id="father_qualification" class="form-control" value="{{ old("father_qualification", $draftApplication->father_qualification ?? '') }}" required>
                        </div>
                        <div class="col-md-4">
                            <label for="mother_qualification" class="form-label">Mother's Qualification (आमाको योग्यता) <span class="text-danger">*</span></label>
                            <input type="text" name="mother_qualification" id="mother_qualification" class="form-control" value="{{ old("mother_qualification", $draftApplication->mother_qualification ?? '') }}" required>
                        </div>
                        <div class="col-md-4">
                            <label for="parent_occupation" class="form-label">Parent's Occupation <span class="text-danger">*</span></label>
                            <input type="text" name="parent_occupation" id="parent_occupation" class="form-control" value="{{ old('parent_occupation', $draftApplication->parent_occupation ?? '') }}" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="blood_group" class="form-label">Blood Group <span class="text-danger">*</span></label>
                            <input type="text" name="blood_group" id="blood_group" class="form-control" value="{{ old('blood_group', $draftApplication->blood_group ?? '') }}" required>
                        </div>
                    
                        <div class="col-md-4">
                            <label for="nationality" class="form-label">Nationality <span class="text-danger">*</span></label>
                            <input type="text" name="nationality" id="nationality" class="form-control" value="{{ old('nationality', $draftApplication->nationality ?? '') }}" required>
                        </div>
                        <div class="col-md-4">
                            <label for="noc_employee" class="form-label">Are you NOC Employee? <span class="text-danger">*</span></label>
                            <select name="noc_employee" id="noc_employee" class="form-select" required>
                                <option value="">-- Select --</option>
                                <option value="yes" {{ old('noc_employee', $draftApplication->noc_employee ?? '') == 'yes' ? 'selected' : '' }}>Yes</option>
                                <option value="no" {{ old('noc_employee', $draftApplication->noc_employee ?? '') == 'no' ? 'selected' : '' }}>No</option>
                            </select>
                        </div>
                        </div>

                        <div class="col-md-6">
                            <label for="noc_id_card" class="form-label">NOC ID Card</label>
                            <input type="file" name="noc_id_card" id="noc_id_card"
                                class="form-control" value="{{ old('noc_id_card', $draftApplication->noc_id_card ?? '') }}" accept="image/*,application/pdf">

                            <small class="text-muted d-block">Max size: 2MB</small>
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
                                <option value="Hindu" {{ old('religion', $draftApplication->religion ?? '') == 'Hindu' ? 'selected' : '' }}>Hindu / हिन्दू</option>
                                <option value="Buddhist" {{ old('religion', $draftApplication->religion ?? '') == 'Buddhist' ? 'selected' : '' }}>Buddhist / बौद्ध</option>
                                <option value="Christian" {{ old('religion', $draftApplication->religion ?? '') == 'Christian' ? 'selected' : '' }}>Christian / ख्रीष्टिय</option>
                                <option value="Muslim" {{ old('religion', $draftApplication->religion ?? '') == 'Muslim' ? 'selected' : '' }}>Muslim / मुस्लिम</option>
                                <option value="Other" {{ old('religion', $draftApplication->religion ?? '') == 'Other' ? 'selected' : '' }}>Other / अन्य</option>
                            </select>
                            <input type="text" name="religion_other" id="religion_other" class="form-control mt-2 d-none" placeholder="If other, specify" value="{{ old('religion_other') }}">
                        </div>
                        <div class="col-md-4">
                            <label for="community" class="form-label">Community <span class="text-danger">*</span> <small>(तपाई आफैलाई के बोलाउन रुचाउनुहुन्छ)</small></label>
                            <select name="community" id="community" class="form-select" required>
                                <option value="">-- Select --</option>
                                <option value="Male" {{ old('community', $draftApplication->community ?? '') == 'Male' ? 'selected' : '' }}>पुरुष</option>
                                <option value="Female" {{ old('community', $draftApplication->community ?? '') == 'Female' ? 'selected' : '' }}>महिला</option>
                                <option value="LGBTQ" {{ old('community', $draftApplication->community ?? '') == 'LGBTQ' ? 'selected' : '' }}>LGBTQ+</option>
                                <option value="Other" {{ old('community', $draftApplication->community ?? '') == 'Other' ? 'selected' : '' }}>Other / अन्य</option>
                            </select>
                            <input type="text" name="community_other" id="community_other" class="form-control mt-2 d-none" placeholder="If other, specify" value="{{ old('community_other') }}">
                        </div>
                        <div class="col-md-4">
                            <label for="ethnic_group" class="form-label">Ethnic Group <span class="text-danger">*</span> <small>(जातीय समूह)</small></label>
                            <select name="ethnic_group" id="ethnic_group" class="form-select" required>
                                <option value="">-- Select --</option>
                                <option value="Dalit" {{ old('ethnic_group', $draftApplication->ethnic_group ?? '') == 'Dalit' ? 'selected' : '' }}>Dalit</option>
                                <option value="Janajati" {{ old('ethnic_group', $draftApplication->ethnic_group ?? '') == 'Janajati' ? 'selected' : '' }}>Janajati</option>
                                <option value="Madhesi" {{ old('ethnic_group', $draftApplication->ethnic_group ?? '') == 'Madhesi' ? 'selected' : '' }}>Madhesi</option>
                                <option value="Brahmin/Chhetri" {{ old('ethnic_group', $draftApplication->ethnic_group ?? '') == 'Brahmin/Chhetri' ? 'selected' : '' }}>Brahmin / Chhetri</option>
                                <option value="Other" {{ old('ethnic_group', $draftApplication->ethnic_group ?? '') == 'Other' ? 'selected' : '' }}>Other</option>
                            </select>
                            <input type="text" name="ethnic_group_other" id="ethnic_group_other" class="form-control mt-2 d-none" placeholder="If other, specify" value="{{ old('ethnic_group_other') }}">
                        </div>
                         <div class="col-md-6">
                            <label for="ethnic_certificate" class="form-label">Ethnic Certificate</label>
                            <input type="file" name="ethnic_certificate" id="ethnic_certificate" class="form-control" value="{{ old('ethnic_certificate', $draftApplication->ethnic_certificate ?? '') }}" accept="image/*,application/pdf" multiple>
                            <small class="text-muted">Max size: 2MB</small>
                        </div>
                        <div class="col-md-6">
                            <label for="mother_tongue" class="form-label">Mother Tongue <span class="text-danger">*</span> <small>(मातृभाषा)</small></label>
                            <input type="text" name="mother_tongue" id="mother_tongue" class="form-control" value="{{ old('mother_tongue', $draftApplication->mother_tongue ?? '') }}" required>
                        </div>
                        <div class="col-md-4">
                            <label for="employment_status" class="form-label">Employment Status <span class="text-danger">*</span> <small>(रोजगार स्थिति)</small></label>
                            <select name="employment_status" id="employment_status" class="form-select" required>
                                <option value="">-- Select --</option>
                                <option value="employed" {{ old('employment_status', $draftApplication->employment_status ?? '') == 'Employed' ? 'selected' : '' }}>Employed</option>
                                <option value="unemployed" {{ old('employment_status', $draftApplication->employment_status ?? '') == 'Unemployed' ? 'selected' : '' }}>Unemployed</option>
                            </select>
                        </div>
                         <div class="col-md-4">
                            <label for="physical_disability" class="form-label">Physical Disability <span class="text-danger">*</span> <small>(कुनै पनि असक्षमता?)</small></label>
                            <select name="physical_disability" id="physical_disability" class="form-select" required>
                                <option value="">-- Select --</option>
                                <option value="yes" {{ old('physical_disability', $draftApplication->physical_disability ?? '') == 'yes' ? 'selected' : '' }}>Yes</option>
                                <option value="no" {{ old('physical_disability', $draftApplication->physical_disability ?? '') == 'no' ? 'selected' : '' }}>No</option>
                            </select>
                        </div>
                         <div class="col-md-6">
                            <label for="disability_certificate" class="form-label">Disability Certificate (If Any)</label>
                            <input type="file" name="disability_certificate" id="disability_certificate"
                                class="form-control" accept="image/*,application/pdf">

                            <small class="text-muted d-block">Max size: 2MB</small>
                        </div>

                    </div>

                    <div class="d-flex justify-content-between">
                        <button type="button" class="btn btn-secondary prev-btn">Back</button>
                        <button type="button" class="btn btn-primary next-btn">Next</button>
                    </div>
                </div>

                <!-- STEP 3: Permanent Address -->
                <div class="step d-none" id="step3">
                    <h5 class="mb-4 text-primary">Step 3 — Permanent Address</h5>
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="permanent_province" class="form-label">Province <span class="text-danger">*</span></label>
                            <select name="permanent_province" id="permanent_province" class="form-select" required>
                                <option value="">-- Select --</option>
                                <option value="Koshi" {{ old('permanent_province', $draftApplication->permanent_province ?? '') == 'Koshi' ? 'selected' : '' }}>Koshi</option>
                                <option value="Madhesh" {{ old('permanent_province', $draftApplication->permanent_province ?? '') == 'Madhesh' ? 'selected' : '' }}>Madhesh</option>
                                <option value="Bagmati" {{ old('permanent_province', $draftApplication->permanent_province ?? '') == 'Bagmati' ? 'selected' : '' }}>Bagmati</option>
                                <option value="Gandaki" {{ old('permanent_province', $draftApplication->permanent_province ?? '') == 'Gandaki' ? 'selected' : '' }}>Gandaki</option>
                                <option value="Lumbini" {{ old('permanent_province', $draftApplication->permanent_province ?? '') == 'Lumbini' ? 'selected' : '' }}>Lumbini</option>
                                <option value="Karnali" {{ old('permanent_province', $draftApplication->permanent_province ?? '') == 'Karnali' ? 'selected' : '' }}>Karnali</option>
                                <option value="Sudurpashchim" {{ old('permanent_province', $draftApplication->permanent_province ?? '') == 'Sudurpashchim' ? 'selected' : '' }}>Sudurpashchim</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="permanent_district" class="form-label">District <span class="text-danger">*</span></label>
                            <input type="text" name="permanent_district" id="permanent_district" class="form-control" value="{{ old('permanent_district', $draftApplication->permanent_district ?? '') }}" required>
                        </div>
                        <div class="col-md-4">
                            <label for="permanent_municipality" class="form-label">Municipality <span class="text-danger">*</span></label>
                            <input type="text" name="permanent_municipality" id="permanent_municipality" class="form-control" value="{{ old('permanent_municipality', $draftApplication->permanent_municipality ?? '') }}" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="permanent_ward" class="form-label">Ward No. <span class="text-danger">*</span></label>
                            <input type="text" name="permanent_ward" id="permanent_ward" class="form-control" value="{{ old('permanent_ward', $draftApplication->permanent_ward ?? '') }}" required>
                        </div>
                        <div class="col-md-4">
                            <label for="permanent_tole" class="form-label">Tole</label>
                            <input type="text" name="permanent_tole" id="permanent_tole" class="form-control" value="{{ old('permanent_tole', $draftApplication->permanent_tole ?? '') }}">
                        </div>
                        <div class="col-md-4">
                            <label for="permanent_house_number" class="form-label">House Number</label>
                            <input type="text" name="permanent_house_number" id="permanent_house_number" class="form-control" value="{{ old('permanent_house_number', $draftApplication->permanent_house_number ?? '') }}">
                        </div>
                    </div>
                    <div class="d-flex justify-content-between">
                        <button type="button" class="btn btn-secondary prev-btn">Back</button>
                        <button type="button" class="btn btn-primary next-btn">Next</button>
                    </div>
                </div>

                <!-- STEP 4: Mailing Address -->
                <div class="step d-none" id="step4">
                    <h5 class="mb-4 text-primary">Step 4 — Mailing/Current Address</h5>
                    <div class="form-check mb-4">
                        <input type="checkbox" class="form-check-input" id="same_as_permanent" name="same_as_permanent" value="1" {{ old('same_as_permanent') ? 'checked' : '' }}>
                        <label class="form-check-label" for="same_as_permanent">Same as Permanent Address</label>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="mailing_province" class="form-label">Province <span class="text-danger">*</span></label>
                            <select name="mailing_province" id="mailing_province" class="form-select" required>
                                <option value="">-- Select --</option>
                                <option value="Koshi" {{ old('mailing_province', $draftApplication->mailing_province ?? '') == 'Koshi' ? 'selected' : '' }}>Koshi</option>
                                <option value="Madhesh" {{ old('mailing_province', $draftApplication->mailing_province ?? '') == 'Madhesh' ? 'selected' : '' }}>Madhesh</option>
                                <option value="Bagmati" {{ old('mailing_province', $draftApplication->mailing_province ?? '') == 'Bagmati' ? 'selected' : '' }}>Bagmati</option>
                                <option value="Gandaki" {{ old('mailing_province', $draftApplication->mailing_province ?? '') == 'Gandaki' ? 'selected' : '' }}>Gandaki</option>
                                <option value="Lumbini" {{ old('mailing_province', $draftApplication->mailing_province ?? '') == 'Lumbini' ? 'selected' : '' }}>Lumbini</option>
                                <option value="Karnali" {{ old('mailing_province', $draftApplication->mailing_province ?? '') == 'Karnali' ? 'selected' : '' }}>Karnali</option>
                                <option value="Sudurpashchim" {{ old('mailing_province', $draftApplication->mailing_province ?? '') == 'Sudurpashchim' ? 'selected' : '' }}>Sudurpashchim</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="mailing_district" class="form-label">District <span class="text-danger">*</span></label>
                            <input type="text" name="mailing_district" id="mailing_district" class="form-control" value="{{ old('mailing_district', $draftApplication->mailing_district ?? '') }}" required>
                        </div>
                        <div class="col-md-4">
                            <label for="mailing_municipality" class="form-label">Municipality <span class="text-danger">*</span></label>
                            <input type="text" name="mailing_municipality" id="mailing_municipality" class="form-control" value="{{ old('mailing_municipality', $draftApplication->mailing_municipality ?? '') }}" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="mailing_ward" class="form-label">Ward No. <span class="text-danger">*</span></label>
                            <input type="text" name="mailing_ward" id="mailing_ward" class="form-control" value="{{ old('mailing_ward', $draftApplication->mailing_ward ?? '') }}" required>
                        </div>
                        <div class="col-md-4">
                            <label for="mailing_tole" class="form-label">Tole</label>
                            <input type="text" name="mailing_tole" id="mailing_tole" class="form-control" value="{{ old('mailing_tole', $draftApplication->mailing_tole ?? '') }}">
                        </div>
                        <div class="col-md-4">
                            <label for="mailing_house_number" class="form-label">House Number</label>
                            <input type="text" name="mailing_house_number" id="mailing_house_number" class="form-control" value="{{ old('mailing_house_number', $draftApplication->mailing_house_number ?? '') }}">
                        </div>
                    </div>
                    <div class="d-flex justify-content-between">
                        <button type="button" class="btn btn-secondary prev-btn">Back</button>
                        <button type="button" class="btn btn-primary next-btn">Next</button>
                    </div>
                </div>

                <!-- STEP 5, 6, 7 -->
                <div class="step d-none" id="step5">
                    <h5 class="mb-4 text-primary">Step 5 — Educational Background</h5>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="education_level" class="form-label">Highest Education Level <span class="text-danger">*</span></label>
                            <select name="education_level" id="education_level" class="form-select" required>
                                <option value="">-- Select --</option>
                                <option value="Under SLC" {{ old('education_level', $draftApplication->education_level ?? '') == 'Under SLC' ? 'selected' : '' }}>Under SLC</option>
                                <option value="SLC/SEE" {{ old('education_level', $draftApplication->education_level ?? '') == 'SLC/SEE' ? 'selected' : '' }}>SLC/SEE</option>
                                <option value="+2/Intermediate" {{ old('education_level', $draftApplication->education_level ?? '') == '+2/Intermediate' ? 'selected' : '' }}>+2/Intermediate</option>
                                <option value="Bachelor" {{ old('education_level', $draftApplication->education_level ?? '') == 'Bachelor' ? 'selected' : '' }}>Bachelor</option>
                                <option value="Master" {{ old('education_level', $draftApplication->education_level ?? '') == 'Master' ? 'selected' : '' }}>Master</option>
                                <option value="PhD" {{ old('education_level', $draftApplication->education_level ?? '') == 'PhD' ? 'selected' : '' }}>PhD</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="field_of_study" class="form-label">Field of Study<span class="text-danger">*</span></label>
                            <input type="text" name="field_of_study" id="field_of_study" class="form-control" value="{{ old('field_of_study', $draftApplication->field_of_study ?? '') }}"required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="institution_name" class="form-label">Institution Name<span class="text-danger">*</span></label>
                            <input type="text" name="institution_name" id="institution_name" class="form-control" value="{{ old('institution_name', $draftApplication->institution_name ?? '') }}"required>
                        </div>
                        <div class="col-md-6">
                            <label for="graduation_year" class="form-label">Graduation Year<span class="text-danger">*</span></label>
                            <input type="number" name="graduation_year" id="graduation_year" class="form-control" min="1950" max="2030" value="{{ old('graduation_year', $draftApplication->graduation_year ?? '') }}"required>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between">
                        <button type="button" class="btn btn-secondary prev-btn">Back</button>
                        <button type="button" class="btn btn-primary next-btn">Next</button>
                    </div>
                </div>

                <div class="step d-none" id="step6">
                    <h5 class="mb-4 text-primary">Step 6 — Work Experience</h5>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="has_work_experience" class="form-label">Do you have work experience? <span class="text-danger">*</span></label>
                            <select name="has_work_experience" id="has_work_experience" class="form-select" required>
                                <option value="">-- Select --</option>
                                <option value="Yes" {{ old('has_work_experience', $draftApplication->has_work_experience ?? '') == 'Yes' ? 'selected' : '' }}>Yes</option>
                                <option value="No" {{ old('has_work_experience', $draftApplication->has_work_experience ?? '') == 'No' ? 'selected' : '' }}>No</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="years_of_experience" class="form-label">Years of Experience<span class="text-danger">*</span></label>
                            <input type="number" name="years_of_experience" id="years_of_experience" class="form-control" min="0" step="0.5" value="{{ old('years_of_experience', $draftApplication->years_of_experience ?? '') }}"required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="previous_organization" class="form-label">Previous Organization<span class="text-danger">*</span></label>
                            <input type="text" name="previous_organization" id="previous_organization" class="form-control" value="{{ old('previous_organization', $draftApplication->previous_organization ?? '') }}"required>
                        </div>
                        <div class="col-md-6">
                            <label for="previous_position" class="form-label">Previous Position<span class="text-danger">*</span></label>
                            <input type="text" name="previous_position" id="previous_position" class="form-control" value="{{ old('previous_position', $draftApplication->previous_position ?? '') }}"required>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between">
                        <button type="button" class="btn btn-secondary prev-btn">Back</button>
                        <button type="button" class="btn btn-primary next-btn">Next</button>
                    </div>
                </div>

                <div class="step d-none" id="step7">
                    <h5 class="mb-4 text-primary">Step 7 — Upload Documents</h5>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="passport_size_photo" class="form-label">Passport Size Photo <span class="text-danger">*</span></label>
                            <input type="file" name="passport_size_photo" id="passport_size_photo" class="form-control" value="{{ old('passport_size_photo', $draftApplication->passport_size_photo ?? '') }}" accept="image/*,application/pdf" required>
                            <small class="text-muted d-block">Max size: 2MB</small>
                        </div>

                        <div class="col-md-6">
                            <label for="citizenship_id_document" class="form-label">Citizenship/ID Document<span class="text-danger">*</span></label>
                            <input type="file" name="citizenship_id_document" id="citizenship_id_document" class="form-control" value="{{ old('citizenship_id_document', $draftApplication->citizenship_id_document ?? '') }}" accept="image/*,application/pdf" required>
                            <small class="text-muted d-block">Max size: 2MB</small>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="resume_cv" class="form-label">Resume/CV<span class="text-danger">*</span></label>
                            <input type="file" name="resume_cv" id="resume_cv" class="form-control" value="{{ old('resume_cv', $draftApplication->resume_cv ?? '') }}" accept="image/*,application/pdf" required>
                            <small class="text-muted d-block">Max size: 2MB</small>
                        </div>
                        <div class="col-md-6">
                            <label for="educational_certificates" class="form-label">
                                Educational Certificates <span class="text-danger">*</span>
                            </label>

                            <input type="file"
                                name="educational_certificates[]"
                                id="educational_certificates"
                                class="form-control"
                                value="{{ old('educational_certificates', $draftApplication->educational_certificates ?? '') }}"
                                accept="image/*,application/pdf"
                                multiple
                                required>

                            <small class="text-muted d-block">Max size: 2MB (multiple allowed)</small>
                        </div>

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
</style>
@endpush

<script>
document.addEventListener('DOMContentLoaded', function () {
    let currentStep = 1;
    const totalSteps = 7;
    const hasErrors = {{ $errors->any() ? 'true' : 'false' }};
    const form = document.getElementById('applicationform');
    const draftIdInput = document.getElementById('draft_id');
    let autoSaveTimeout;
    let isSaving = false;

    // ==================== AUTO-SAVE FUNCTIONALITY ====================
    
    // Create auto-save indicator
    const autoSaveIndicator = document.createElement('div');
    autoSaveIndicator.id = 'autosave-indicator';
    autoSaveIndicator.style.cssText = 'position: fixed; top: 80px; right: 20px; padding: 12px 24px; border-radius: 8px; z-index: 9999; display: none; font-weight: 600; box-shadow: 0 4px 12px rgba(0,0,0,0.15);';
    document.body.appendChild(autoSaveIndicator);

    function showAutoSaveStatus(message, type = 'info') {
        autoSaveIndicator.textContent = message;
        autoSaveIndicator.className = `alert alert-${type} mb-0`;
        autoSaveIndicator.style.display = 'block';
        
        setTimeout(() => {
            autoSaveIndicator.style.display = 'none';
        }, 3000);
    }

    function autoSave() {
        if (isSaving) {
            console.log('Already saving, skipping...');
            return;
        }
        
        isSaving = true;
        console.log('Starting auto-save...');
        showAutoSaveStatus('💾 Saving draft...', 'info');

        const formData = new FormData(form);
        
        // Add draft_id if exists
        if (draftIdInput && draftIdInput.value) {
            formData.set('draft_id', draftIdInput.value);
            console.log('Draft ID:', draftIdInput.value);
        }

        // Remove file inputs from auto-save (files should only be uploaded on final submit)
        const fileInputs = form.querySelectorAll('input[type="file"]');
        fileInputs.forEach(input => {
            formData.delete(input.name);
            formData.delete(input.name + '[]'); // For array inputs
        });

        // Log what we're sending
        console.log('Form data being sent:');
        for (let pair of formData.entries()) {
            console.log(pair[0] + ': ' + pair[1]);
        }

        fetch('{{ route("candidate.applications.saveDraft") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => {
            console.log('Response status:', response.status);
            
            // Check if response is ok
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            
            return response.json();
        })
        .then(data => {
            console.log('Server response:', data);
            
            if (data.success) {
                showAutoSaveStatus('✓ Draft saved', 'success');
                
                // Update draft_id if this was first save
                if (data.draft_id && (!draftIdInput.value || draftIdInput.value === '')) {
                    draftIdInput.value = data.draft_id;
                    console.log('Draft ID updated to:', data.draft_id);
                }
            } else {
                console.error('Save failed:', data.message);
                showAutoSaveStatus('⚠ ' + (data.message || 'Failed to save'), 'warning');
            }
        })
        .catch(error => {
            console.error('Auto-save error:', error);
            showAutoSaveStatus('✕ Save failed: ' + error.message, 'danger');
        })
        .finally(() => {
            isSaving = false;
            console.log('Auto-save complete');
        });
    }

    // Trigger auto-save on input changes (debounced)
    form.addEventListener('input', function(e) {
        // Skip file inputs
        if (e.target.type === 'file') return;
        
        console.log('Input changed:', e.target.name);
        clearTimeout(autoSaveTimeout);
        autoSaveTimeout = setTimeout(autoSave, 2000); // Save 2 seconds after typing stops
    });

    form.addEventListener('change', function(e) {
        // Save on select/radio/checkbox changes
        if (e.target.tagName === 'SELECT' || e.target.type === 'checkbox' || e.target.type === 'radio') {
            console.log('Select/checkbox changed:', e.target.name);
            clearTimeout(autoSaveTimeout);
            autoSaveTimeout = setTimeout(autoSave, 1000);
        }
    });

    // ==================== STEP NAVIGATION ====================

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

        const progressPercent = ((currentStep - 1) / (totalSteps - 1)) * 100;
        const progressLine = document.querySelector('.progress-line');
        if (progressLine) {
            progressLine.style.width = progressPercent + '%';
        }
    }

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

    function validateStep(step) {
        const stepEl = document.getElementById('step' + step);
        if (!stepEl) return false;

        // Clear previous validation errors
        stepEl.querySelectorAll('.is-invalid, .invalid-feedback').forEach(el => {
            el.classList.remove('is-invalid');
            if (el.classList.contains('invalid-feedback')) el.remove();
        });

        let isValid = true;
        let firstInvalid = null;

        // Validate all required fields in this step
        stepEl.querySelectorAll('input[required], select[required], textarea[required]').forEach(field => {
            // Skip if field is hidden
            if (field.closest('.d-none')) return;
            
            // Check if field is empty
            const value = field.value.trim();
            if (!value || value === '') {
                isValid = false;
                field.classList.add('is-invalid');
                
                // Create error message
                const err = document.createElement('div');
                err.className = 'invalid-feedback';
                err.textContent = 'This field is required';
                field.parentNode.appendChild(err);
                
                if (!firstInvalid) firstInvalid = field;
            }
        });

        // If validation failed, scroll to first invalid field and show alert
        if (!isValid && firstInvalid) {
            firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
            firstInvalid.focus();
            
            // Show alert
            showAutoSaveStatus('⚠ Please fill all required fields', 'warning');
        }
        
        return isValid;
    }

    // Clickable Tabs - STRICT VALIDATION
    document.querySelectorAll('.tab-item').forEach(tab => {
        tab.addEventListener('click', () => {
            const targetStep = parseInt(tab.getAttribute('data-step'));
            
            // Allow clicking on previous steps (backward navigation)
            if (targetStep < currentStep) {
                showStep(targetStep);
                return;
            }
            
            // For forward navigation, validate ALL steps between current and target
            if (targetStep > currentStep) {
                let canProceed = true;
                
                // Validate all steps from current to target-1
                for (let i = currentStep; i < targetStep; i++) {
                    if (!validateStep(i)) {
                        canProceed = false;
                        showAutoSaveStatus(`⚠ Please complete Step ${i} first`, 'danger');
                        break;
                    }
                }
                
                if (canProceed) {
                    showStep(targetStep);
                }
            } else if (targetStep === currentStep) {
                // Already on this step, do nothing
                return;
            }
        });
    });

    // Next Button - STRICT VALIDATION
    document.querySelectorAll('.next-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            // Validate current step before moving
            if (!validateStep(currentStep)) {
                return; // Stop if validation fails
            }
            
            if (currentStep < totalSteps) {
                clearTimeout(autoSaveTimeout);
                autoSave();
                setTimeout(() => {
                    showStep(currentStep + 1);
                }, 500);
            }
        });
    });

    // Previous Button - No validation needed for going back
    document.querySelectorAll('.prev-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            if (currentStep > 1) {
                clearTimeout(autoSaveTimeout);
                autoSave();
                setTimeout(() => {
                    showStep(currentStep - 1);
                }, 500);
            }
        });
    });

    // Same as Permanent Address Checkbox
    const sameAsPermanentCheckbox = document.getElementById('same_as_permanent');
    if (sameAsPermanentCheckbox) {
        sameAsPermanentCheckbox.addEventListener('change', function () {
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
    }

    // Show "Other" fields
    ['religion', 'community', 'ethnic_group'].forEach(id => {
        const select = document.getElementById(id);
        const other = document.getElementById(id + '_other');
        if (select && other) {
            const toggle = () => {
                if (select.value === 'Other') {
                    other.classList.remove('d-none');
                    other.setAttribute('required', 'required');
                } else {
                    other.classList.add('d-none');
                    other.removeAttribute('required');
                    other.value = ''; // Clear value when hidden
                }
            };
            select.addEventListener('change', toggle);
            toggle();
        }
    });

    // Initialize
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

    // Final Submit - Validate ALL steps
    form.addEventListener('submit', function(e) {
        // Validate all steps before submission
        let allValid = true;
        for (let i = 1; i <= totalSteps; i++) {
            if (!validateStep(i)) {
                allValid = false;
                showStep(i); // Jump to first invalid step
                e.preventDefault();
                showAutoSaveStatus('⚠ Please complete all required fields', 'danger');
                return false;
            }
        }
        
        if (allValid) {
            clearTimeout(autoSaveTimeout);
            showAutoSaveStatus('📤 Submitting...', 'primary');
        }
    });

    console.log('✓ Form initialized with strict validation and auto-save');
    console.log('Draft ID on load:', draftIdInput ? draftIdInput.value : 'none');
});
</script>
@endsection