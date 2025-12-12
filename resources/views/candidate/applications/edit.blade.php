@extends('layouts.dashboard')

@section('title', 'Edit Application')

@section('portal-name', 'Candidate Portal')
@section('brand-icon', 'bi bi-briefcase')
@section('dashboard-route', route('candidate.dashboard'))
@section('user-name', Auth::guard('candidate')->user()->name)
@section('user-role', 'Job Seeker')
@section('user-initial', strtoupper(substr(Auth::guard('candidate')->user()->name, 0, 1)))
@section('logout-route', route('candidate.logout'))

@section('sidebar-menu')
    <a href="{{ route('candidate.dashboard') }}" class="sidebar-menu-item">
        <i class="bi bi-speedometer2"></i>
        <span>Dashboard</span>
    </a>
    <a href="{{ route('candidate.jobs.index') }}" class="sidebar-menu-item">
        <i class="bi bi-search"></i>
        <span>Browse Vacancy</span>
    </a>
    <a href="{{ route('candidate.applications.index') }}" class="sidebar-menu-item active">
        <i class="bi bi-file-earmark-text"></i>
        <span>My Applications</span>
    </a>
    <a href="#" class="sidebar-menu-item">
        <i class="bi bi-bookmark"></i>
        <span>Saved Jobs</span>
    </a>
    <a href="{{ route('candidate.profile.edit') }}" class="sidebar-menu-item">
        <i class="bi bi-person"></i>
        <span>My Profile</span>
    </a>
    <a href="{{ route('candidate.settings.index') }}" class="sidebar-menu-item">
        <i class="bi bi-gear"></i>
        <span>Settings</span>
    </a>
@endsection

@section('content')
    <div class="container-fluid my-4">
        <div class="card shadow-sm">
            <div class="card-header bg-warning text-white d-flex justify-content-between align-items-center">
                <h4 class="mb-0"><i class="fas fa-edit"></i> Edit Job Application</h4>
                <a href="{{ route('candidate.applications.index') }}" class="btn btn-light btn-sm">
                    <i class="fas fa-arrow-left"></i> Back to Applications
                </a>
            </div>

            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show">
                        <strong>Please fix the following errors:</strong>
                        <ul class="mb-0 mt-2">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <!-- Job Information -->
                <div class="alert alert-info">
                    <h5 class="alert-heading"><i class="fas fa-info-circle"></i> Application For</h5>
                    <h6 class="mb-1">{{ $application->job->title }}</h6>
                    <p class="mb-1">
                        <small>
                            <i class="fas fa-building"></i> {{ $application->job->department }} |
                            <i class="fas fa-map-marker-alt"></i> {{ $application->job->location }}
                        </small>
                    </p>
                    <p class="mb-0">
                        <small class="text-muted">
                            <i class="fas fa-calendar"></i> Applied on: {{ $application->created_at->format('F d, Y') }} |
                            <i class="fas fa-info-circle"></i> Status:
                            <span
                                class="badge bg-{{ $application->status === 'pending' ? 'warning' : ($application->status === 'approved' ? 'success' : 'danger') }}">
                                {{ ucfirst($application->status) }}
                            </span>
                        </small>
                    </p>
                </div>

                @if($application->status !== 'pending')
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle"></i>
                        <strong>Note:</strong> This application has been {{ $application->status }}. Changes may not affect the
                        review process.
                    </div>
                @endif

                <form action="{{ route('candidate.jobs.applications.update', [$application->job_id, $application->id]) }}"
                    method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <!-- General Information -->
                    <div class="card mb-3">
                        <div class="card-header bg-light">
                            <h5 class="mb-0"><i class="fas fa-info-circle"></i> General Information</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Religion <span class="text-danger">*</span></label>
                                    <select name="religion" class="form-select @error('religion') is-invalid @enderror"
                                        required>
                                        <option value="">Select Religion</option>
                                        <option value="Hindu" {{ old('religion', $application->religion) == 'Hindu' ? 'selected' : '' }}>Hindu</option>
                                        <option value="Buddhist" {{ old('religion', $application->religion) == 'Buddhist' ? 'selected' : '' }}>Buddhist</option>
                                        <option value="Muslim" {{ old('religion', $application->religion) == 'Muslim' ? 'selected' : '' }}>Muslim</option>
                                        <option value="Christian" {{ old('religion', $application->religion) == 'Christian' ? 'selected' : '' }}>Christian</option>
                                        <option value="Other" {{ old('religion', $application->religion) == 'Other' ? 'selected' : '' }}>Other</option>
                                    </select>
                                    @error('religion')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Religion (Other)</label>
                                    <input type="text" name="religion_other"
                                        class="form-control @error('religion_other') is-invalid @enderror"
                                        value="{{ old('religion_other', $application->religion_other) }}">
                                    @error('religion_other')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Community</label>
                                    <input type="text" name="community"
                                        class="form-control @error('community') is-invalid @enderror"
                                        value="{{ old('community', $application->community) }}">
                                    @error('community')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Ethnic Group</label>
                                    <input type="text" name="ethnic_group"
                                        class="form-control @error('ethnic_group') is-invalid @enderror"
                                        value="{{ old('ethnic_group', $application->ethnic_group) }}">
                                    @error('ethnic_group')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Marital Status <span class="text-danger">*</span></label>
                                    <select name="marital_status"
                                        class="form-select @error('marital_status') is-invalid @enderror" required>
                                        <option value="">Select Status</option>
                                        <option value="single" {{ old('marital_status', $application->marital_status) == 'single' ? 'selected' : '' }}>Single</option>
                                        <option value="married" {{ old('marital_status', $application->marital_status) == 'married' ? 'selected' : '' }}>Married</option>
                                        <option value="divorced" {{ old('marital_status', $application->marital_status) == 'divorced' ? 'selected' : '' }}>Divorced</option>
                                        <option value="widowed" {{ old('marital_status', $application->marital_status) == 'widowed' ? 'selected' : '' }}>Widowed</option>
                                    </select>
                                    @error('marital_status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Employment Status</label>
                                    <select name="employment_status"
                                        class="form-select @error('employment_status') is-invalid @enderror">
                                        <option value="">Select Status</option>
                                        <option value="employed" {{ old('employment_status', $application->employment_status) == 'employed' ? 'selected' : '' }}>Employed
                                        </option>
                                        <option value="unemployed" {{ old('employment_status', $application->employment_status) == 'unemployed' ? 'selected' : '' }}>Unemployed
                                        </option>
                                        <option value="self-employed" {{ old('employment_status', $application->employment_status) == 'self-employed' ? 'selected' : '' }}>
                                            Self-Employed</option>
                                        <option value="student" {{ old('employment_status', $application->employment_status) == 'student' ? 'selected' : '' }}>Student
                                        </option>
                                    </select>
                                    @error('employment_status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Physical Disability <span class="text-danger">*</span></label>
                                    <select name="physical_disability"
                                        class="form-select @error('physical_disability') is-invalid @enderror" required>
                                        <option value="">Select Option</option>
                                        <option value="no" {{ old('physical_disability', $application->physical_disability) == 'no' ? 'selected' : '' }}>No</option>
                                        <option value="yes" {{ old('physical_disability', $application->physical_disability) == 'yes' ? 'selected' : '' }}>Yes</option>
                                    </select>
                                    @error('physical_disability')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Mother Tongue <span class="text-danger">*</span></label>
                                    <input type="text" name="mother_tongue"
                                        class="form-control @error('mother_tongue') is-invalid @enderror"
                                        value="{{ old('mother_tongue', $application->mother_tongue) }}" required>
                                    @error('mother_tongue')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Blood Group</label>
                                    <select name="blood_group"
                                        class="form-select @error('blood_group') is-invalid @enderror">
                                        <option value="">Select Blood Group</option>
                                        <option value="A+" {{ old('blood_group', $application->blood_group) == 'A+' ? 'selected' : '' }}>A+</option>
                                        <option value="A-" {{ old('blood_group', $application->blood_group) == 'A-' ? 'selected' : '' }}>A-</option>
                                        <option value="B+" {{ old('blood_group', $application->blood_group) == 'B+' ? 'selected' : '' }}>B+</option>
                                        <option value="B-" {{ old('blood_group', $application->blood_group) == 'B-' ? 'selected' : '' }}>B-</option>
                                        <option value="AB+" {{ old('blood_group', $application->blood_group) == 'AB+' ? 'selected' : '' }}>AB+</option>
                                        <option value="AB-" {{ old('blood_group', $application->blood_group) == 'AB-' ? 'selected' : '' }}>AB-</option>
                                        <option value="O+" {{ old('blood_group', $application->blood_group) == 'O+' ? 'selected' : '' }}>O+</option>
                                        <option value="O-" {{ old('blood_group', $application->blood_group) == 'O-' ? 'selected' : '' }}>O-</option>
                                    </select>
                                    @error('blood_group')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">NOC Employee <span class="text-danger">*</span></label>
                                    <select name="noc_employee"
                                        class="form-select @error('noc_employee') is-invalid @enderror" required>
                                        <option value="">Select Option</option>
                                        <option value="yes" {{ old('noc_employee', $application->noc_employee) == 'yes' ? 'selected' : '' }}>Yes</option>
                                        <option value="no" {{ old('noc_employee', $application->noc_employee) == 'no' ? 'selected' : '' }}>No</option>
                                    </select>
                                    @error('noc_employee')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Personal Information -->
                    <div class="card mb-3">
                        <div class="card-header bg-light">
                            <h5 class="mb-0"><i class="fas fa-user"></i> Personal Information</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Birth Date (A.D) <span class="text-danger">*</span></label>
                                    <input type="date" name="birth_date_ad"
                                        class="form-control @error('birth_date_ad') is-invalid @enderror"
                                        value="{{ old('birth_date_ad', $application->birth_date_ad) }}" required>
                                    @error('birth_date_ad')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Birth Date (B.S)</label>
                                    <input type="text" name="birth_date_bs"
                                        class="form-control @error('birth_date_bs') is-invalid @enderror"
                                        value="{{ old('birth_date_bs', $application->birth_date_bs) }}"
                                        placeholder="YYYY-MM-DD">
                                    @error('birth_date_bs')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Age <span class="text-danger">*</span></label>
                                    <input type="number" name="age" class="form-control @error('age') is-invalid @enderror"
                                        value="{{ old('age', $application->age) }}" min="18" max="65" required>
                                    @error('age')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Phone Number <span class="text-danger">*</span></label>
                                    <input type="text" name="phone"
                                        class="form-control @error('phone') is-invalid @enderror"
                                        value="{{ old('phone', $application->phone) }}" required>
                                    @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Gender <span class="text-danger">*</span></label>
                                    <select name="gender" class="form-select @error('gender') is-invalid @enderror"
                                        required>
                                        <option value="">Select Gender</option>
                                        <option value="male" {{ old('gender', $application->gender) == 'male' ? 'selected' : '' }}>Male</option>
                                        <option value="female" {{ old('gender', $application->gender) == 'female' ? 'selected' : '' }}>Female</option>
                                        <option value="other" {{ old('gender', $application->gender) == 'other' ? 'selected' : '' }}>Other</option>
                                    </select>
                                    @error('gender')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Citizenship Information -->
                    <div class="card mb-3">
                        <div class="card-header bg-light">
                            <h5 class="mb-0"><i class="fas fa-id-card"></i> Citizenship Information</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Citizenship Number <span class="text-danger">*</span></label>
                                    <input type="text" name="citizenship_number"
                                        class="form-control @error('citizenship_number') is-invalid @enderror"
                                        value="{{ old('citizenship_number', $application->citizenship_number) }}" required>
                                    @error('citizenship_number')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Issue Date (A.D) <span class="text-danger">*</span></label>
                                    <input type="date" name="citizenship_issue_date_ad"
                                        class="form-control @error('citizenship_issue_date_ad') is-invalid @enderror"
                                        value="{{ old('citizenship_issue_date_ad', $application->citizenship_issue_date_ad) }}"
                                        required>
                                    @error('citizenship_issue_date_ad')<div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Issue Date (B.S)</label>
                                    <input type="text" name="citizenship_issue_date_bs"
                                        class="form-control @error('citizenship_issue_date_bs') is-invalid @enderror"
                                        value="{{ old('citizenship_issue_date_bs', $application->citizenship_issue_date_bs) }}"
                                        placeholder="YYYY-MM-DD">
                                    @error('citizenship_issue_date_bs')<div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Issue District <span class="text-danger">*</span></label>
                                    <input type="text" name="citizenship_issue_district"
                                        class="form-control @error('citizenship_issue_district') is-invalid @enderror"
                                        value="{{ old('citizenship_issue_district', $application->citizenship_issue_district) }}"
                                        required>
                                    @error('citizenship_issue_district')<div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Family Information -->
                    <div class="card mb-3">
                        <div class="card-header bg-light">
                            <h5 class="mb-0"><i class="fas fa-users"></i> Family Information</h5>
                        </div>
                        <div class="card-body">
                            <h6 class="mb-3">Father's Information</h6>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Father's Name (English) <span
                                            class="text-danger">*</span></label>
                                    <input type="text" name="father_name_english"
                                        class="form-control @error('father_name_english') is-invalid @enderror"
                                        value="{{ old('father_name_english', $application->father_name_english) }}"
                                        required>
                                    @error('father_name_english')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Father's Name (Nepali) <span
                                            class="text-danger">*</span></label>
                                    <input type="text" name="father_name_nepali"
                                        class="form-control @error('father_name_nepali') is-invalid @enderror"
                                        value="{{ old('father_name_nepali', $application->father_name_nepali) }}" required>
                                    @error('father_name_nepali')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Father's Qualification</label>
                                    <input type="text" name="father_qualification"
                                        class="form-control @error('father_qualification') is-invalid @enderror"
                                        value="{{ old('father_qualification', $application->father_qualification) }}">
                                    @error('father_qualification')<div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <hr>

                            <h6 class="mb-3">Mother's Information</h6>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Mother's Name (English) <span
                                            class="text-danger">*</span></label>
                                    <input type="text" name="mother_name_english"
                                        class="form-control @error('mother_name_english') is-invalid @enderror"
                                        value="{{ old('mother_name_english', $application->mother_name_english) }}"
                                        required>
                                    @error('mother_name_english')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Mother's Name (Nepali) <span
                                            class="text-danger">*</span></label>
                                    <input type="text" name="mother_name_nepali"
                                        class="form-control @error('mother_name_nepali') is-invalid @enderror"
                                        value="{{ old('mother_name_nepali', $application->mother_name_nepali) }}" required>
                                    @error('mother_name_nepali')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Mother's Qualification</label>
                                    <input type="text" name="mother_qualification"
                                        class="form-control @error('mother_qualification') is-invalid @enderror"
                                        value="{{ old('mother_qualification', $application->mother_qualification) }}">
                                    @error('mother_qualification')<div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Parent's Occupation</label>
                                    <input type="text" name="parent_occupation"
                                        class="form-control @error('parent_occupation') is-invalid @enderror"
                                        value="{{ old('parent_occupation', $application->parent_occupation) }}">
                                    @error('parent_occupation')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>

                            <hr>

                            <h6 class="mb-3">Grandfather's Information</h6>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Grandfather's Name (English) <span
                                            class="text-danger">*</span></label>
                                    <input type="text" name="grandfather_name_english"
                                        class="form-control @error('grandfather_name_english') is-invalid @enderror"
                                        value="{{ old('grandfather_name_english', $application->grandfather_name_english) }}"
                                        required>
                                    @error('grandfather_name_english')<div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Grandfather's Name (Nepali) <span
                                            class="text-danger">*</span></label>
                                    <input type="text" name="grandfather_name_nepali"
                                        class="form-control @error('grandfather_name_nepali') is-invalid @enderror"
                                        value="{{ old('grandfather_name_nepali', $application->grandfather_name_nepali) }}"
                                        required>
                                    @error('grandfather_name_nepali')<div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Nationality <span class="text-danger">*</span></label>
                                    <input type="text" name="nationality"
                                        class="form-control @error('nationality') is-invalid @enderror"
                                        value="{{ old('nationality', $application->nationality ?? 'Nepali') }}" required>
                                    @error('nationality')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>

                            <hr>

                            <h6 class="mb-3">Spouse Information (If Married)</h6>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Spouse Name (English)</label>
                                    <input type="text" name="spouse_name_english"
                                        class="form-control @error('spouse_name_english') is-invalid @enderror"
                                        value="{{ old('spouse_name_english', $application->spouse_name_english) }}">
                                    @error('spouse_name_english')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Spouse Name (Nepali)</label>
                                    <input type="text" name="spouse_name_nepali"
                                        class="form-control @error('spouse_name_nepali') is-invalid @enderror"
                                        value="{{ old('spouse_name_nepali', $application->spouse_name_nepali) }}">
                                    @error('spouse_name_nepali')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Spouse Nationality</label>
                                    <input type="text" name="spouse_nationality"
                                        class="form-control @error('spouse_nationality') is-invalid @enderror"
                                        value="{{ old('spouse_nationality', $application->spouse_nationality) }}">
                                    @error('spouse_nationality')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Permanent Address -->
                    <div class="card mb-3">
                        <div class="card-header bg-light">
                            <h5 class="mb-0"><i class="fas fa-map-marker-alt"></i> Permanent Address</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Province <span class="text-danger">*</span></label>
                                    <select name="permanent_province"
                                        class="form-select @error('permanent_province') is-invalid @enderror" required>
                                        <option value="">Select Province</option>
                                        <option value="Province 1" {{ old('permanent_province', $application->permanent_province) == 'Province 1' ? 'selected' : '' }}>Province 1
                                        </option>
                                        <option value="Madhesh Province" {{ old('permanent_province', $application->permanent_province) == 'Madhesh Province' ? 'selected' : '' }}>
                                            Madhesh Province</option>
                                        <option value="Bagmati Province" {{ old('permanent_province', $application->permanent_province) == 'Bagmati Province' ? 'selected' : '' }}>
                                            Bagmati Province</option>
                                        <option value="Gandaki Province" {{ old('permanent_province', $application->permanent_province) == 'Gandaki Province' ? 'selected' : '' }}>
                                            Gandaki Province</option>
                                        <option value="Lumbini Province" {{ old('permanent_province', $application->permanent_province) == 'Lumbini Province' ? 'selected' : '' }}>
                                            Lumbini Province</option>
                                        <option value="Karnali Province" {{ old('permanent_province', $application->permanent_province) == 'Karnali Province' ? 'selected' : '' }}>
                                            Karnali Province</option>
                                        <option value="Sudurpashchim Province" {{ old('permanent_province', $application->permanent_province) == 'Sudurpashchim Province' ? 'selected' : '' }}>Sudurpashchim Province</option>
                                    </select>
                                    @error('permanent_province')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">District <span class="text-danger">*</span></label>
                                    <input type="text" name="permanent_district"
                                        class="form-control @error('permanent_district') is-invalid @enderror"
                                        value="{{ old('permanent_district', $application->permanent_district) }}" required>
                                    @error('permanent_district')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Municipality/Rural Municipality <span
                                            class="text-danger">*</span></label>
                                    <input type="text" name="permanent_municipality"
                                        class="form-control @error('permanent_municipality') is-invalid @enderror"
                                        value="{{ old('permanent_municipality', $application->permanent_municipality) }}"
                                        required>
                                    @error('permanent_municipality')<div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Ward Number <span class="text-danger">*</span></label>
                                    <input type="text" name="permanent_ward"
                                        class="form-control @error('permanent_ward') is-invalid @enderror"
                                        value="{{ old('permanent_ward', $application->permanent_ward) }}" required>
                                    @error('permanent_ward')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Tole/Village</label>
                                    <input type="text" name="permanent_tole"
                                        class="form-control @error('permanent_tole') is-invalid @enderror"
                                        value="{{ old('permanent_tole', $application->permanent_tole) }}">
                                    @error('permanent_tole')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">House Number</label>
                                    <input type="text" name="permanent_house_number"
                                        class="form-control @error('permanent_house_number') is-invalid @enderror"
                                        value="{{ old('permanent_house_number', $application->permanent_house_number) }}">
                                    @error('permanent_house_number')<div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Mailing Address -->
                    <div class="card mb-3">
                        <div class="card-header bg-light">
                            <h5 class="mb-0"><i class="fas fa-envelope"></i> Mailing Address</h5>
                        </div>
                        <div class="card-body">
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" name="same_as_permanent"
                                    id="same_as_permanent" value="1" {{ old('same_as_permanent', $application->same_as_permanent) ? 'checked' : '' }}>
                                <label class="form-check-label" for="same_as_permanent">
                                    Same as Permanent Address
                                </label>
                            </div>

                            <div id="mailing-address-fields">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Province</label>
                                        <select name="mailing_province"
                                            class="form-select @error('mailing_province') is-invalid @enderror">
                                            <option value="">Select Province</option>
                                            <option value="Province 1" {{ old('mailing_province', $application->mailing_province) == 'Province 1' ? 'selected' : '' }}>Province
                                                1</option>
                                            <option value="Madhesh Province" {{ old('mailing_province', $application->mailing_province) == 'Madhesh Province' ? 'selected' : '' }}>
                                                Madhesh Province</option>
                                            <option value="Bagmati Province" {{ old('mailing_province', $application->mailing_province) == 'Bagmati Province' ? 'selected' : '' }}>
                                                Bagmati Province</option>
                                            <option value="Gandaki Province" {{ old('mailing_province', $application->mailing_province) == 'Gandaki Province' ? 'selected' : '' }}>
                                                Gandaki Province</option>
                                            <option value="Lumbini Province" {{ old('mailing_province', $application->mailing_province) == 'Lumbini Province' ? 'selected' : '' }}>
                                                Lumbini Province</option>
                                            <option value="Karnali Province" {{ old('mailing_province', $application->mailing_province) == 'Karnali Province' ? 'selected' : '' }}>
                                                Karnali Province</option>
                                            <option value="Sudurpashchim Province" {{ old('mailing_province', $application->mailing_province) == 'Sudurpashchim Province' ? 'selected' : '' }}>Sudurpashchim Province</option>
                                        </select>
                                        @error('mailing_province')<div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">District</label>
                                        <input type="text" name="mailing_district"
                                            class="form-control @error('mailing_district') is-invalid @enderror"
                                            value="{{ old('mailing_district', $application->mailing_district) }}">
                                        @error('mailing_district')<div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Municipality/Rural Municipality</label>
                                        <input type="text" name="mailing_municipality"
                                            class="form-control @error('mailing_municipality') is-invalid @enderror"
                                            value="{{ old('mailing_municipality', $application->mailing_municipality) }}">
                                        @error('mailing_municipality')<div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Ward Number</label>
                                        <input type="text" name="mailing_ward"
                                            class="form-control @error('mailing_ward') is-invalid @enderror"
                                            value="{{ old('mailing_ward', $application->mailing_ward) }}">
                                        @error('mailing_ward')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Tole/Village</label>
                                        <input type="text" name="mailing_tole"
                                            class="form-control @error('mailing_tole') is-invalid @enderror"
                                            value="{{ old('mailing_tole', $application->mailing_tole) }}">
                                        @error('mailing_tole')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">House Number</label>
                                        <input type="text" name="mailing_house_number"
                                            class="form-control @error('mailing_house_number') is-invalid @enderror"
                                            value="{{ old('mailing_house_number', $application->mailing_house_number) }}">
                                        @error('mailing_house_number')<div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Work Experience -->
                    <div class="card mb-3">
                        <div class="card-header bg-light">
                            <h5 class="mb-0"><i class="fas fa-briefcase"></i> Work Experience</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Years of Experience <span class="text-danger">*</span></label>
                                    <input type="number" name="years_of_experience"
                                        class="form-control @error('years_of_experience') is-invalid @enderror"
                                        value="{{ old('years_of_experience', $application->years_of_experience ?? 0) }}"
                                        min="0" required>
                                    @error('years_of_experience')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Current Salary</label>
                                    <input type="text" name="current_salary"
                                        class="form-control @error('current_salary') is-invalid @enderror"
                                        value="{{ old('current_salary', $application->current_salary) }}"
                                        placeholder="e.g., Rs. 50,000">
                                    @error('current_salary')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Expected Salary</label>
                                    <input type="text" name="expected_salary"
                                        class="form-control @error('expected_salary') is-invalid @enderror"
                                        value="{{ old('expected_salary', $application->expected_salary) }}"
                                        placeholder="e.g., Rs. 60,000">
                                    @error('expected_salary')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Available From</label>
                                    <input type="date" name="available_from"
                                        class="form-control @error('available_from') is-invalid @enderror"
                                        value="{{ old('available_from', $application->available_from) }}">
                                    @error('available_from')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>

                                <div class="col-12 mb-3">
                                    <label class="form-label">Relevant Experience</label>
                                    <textarea name="relevant_experience"
                                        class="form-control @error('relevant_experience') is-invalid @enderror" rows="4"
                                        placeholder="Describe your relevant work experience...">{{ old('relevant_experience', $application->relevant_experience) }}</textarea>
                                    @error('relevant_experience')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Cover Letter -->
                    <div class="card mb-3">
                        <div class="card-header bg-light">
                            <h5 class="mb-0"><i class="fas fa-file-alt"></i> Cover Letter</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label">Cover Letter <span class="text-danger">*</span></label>
                                <textarea name="cover_letter"
                                    class="form-control @error('cover_letter') is-invalid @enderror" rows="8" required
                                    placeholder="Write your cover letter here (minimum 100 characters)...">{{ old('cover_letter', $application->cover_letter) }}</textarea>
                                @error('cover_letter')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                <small class="form-text text-muted">Minimum 100 characters required</small>
                            </div>
                        </div>
                    </div>

                    <!-- Document Uploads -->
                    <div class="card mb-3">
                        <div class="card-header bg-light">
                            <h5 class="mb-0"><i class="fas fa-upload"></i> Document Uploads</h5>
                        </div>
                        <div class="card-body">
                            <div class="alert alert-info">
                                <strong><i class="fas fa-info-circle"></i> File Upload Guidelines:</strong>
                                <ul class="mb-0 mt-2">
                                    <li>Leave file fields empty to keep existing documents</li>
                                    <li>Upload new files only if you want to replace existing ones</li>
                                    <li>Maximum file size: 2MB for most documents, 10MB for certificates</li>
                                    <li>Accepted formats: PDF, JPG, PNG, DOC, DOCX, ZIP</li>
                                </ul>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Passport Photo</label>
                                    @if($application->passport_photo)
                                        <div class="mb-2">
                                            <small class="text-success">
                                                <i class="fas fa-check-circle"></i> Current:
                                                {{ basename($application->passport_photo) }}
                                            </small>
                                        </div>
                                    @endif
                                    <input type="file" name="passport_photo"
                                        class="form-control @error('passport_photo') is-invalid @enderror" accept="image/*">
                                    @error('passport_photo')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    <small class="form-text text-muted">JPG, PNG (Max: 1MB) - Upload new to replace</small>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Resume/CV</label>
                                    @if($application->resume)
                                        <div class="mb-2">
                                            <small class="text-success">
                                                <i class="fas fa-check-circle"></i> Current:
                                                {{ basename($application->resume) }}
                                            </small>
                                        </div>
                                    @endif
                                    <input type="file" name="resume"
                                        class="form-control @error('resume') is-invalid @enderror" accept=".pdf,.doc,.docx">
                                    @error('resume')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    <small class="form-text text-muted">PDF, DOC, DOCX (Max: 2MB) - Upload new to
                                        replace</small>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Cover Letter File</label>
                                    @if($application->cover_letter_file)
                                        <div class="mb-2">
                                            <small class="text-success">
                                                <i class="fas fa-check-circle"></i> Current:
                                                {{ basename($application->cover_letter_file) }}
                                            </small>
                                        </div>
                                    @endif
                                    <input type="file" name="cover_letter_file"
                                        class="form-control @error('cover_letter_file') is-invalid @enderror"
                                        accept=".pdf,.doc,.docx">
                                    @error('cover_letter_file')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    <small class="form-text text-muted">PDF, DOC, DOCX (Max: 2MB) - Optional</small>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Citizenship Certificate</label>
                                    @if($application->citizenship_certificate)
                                        <div class="mb-2">
                                            <small class="text-success">
                                                <i class="fas fa-check-circle"></i> Current:
                                                {{ basename($application->citizenship_certificate) }}
                                            </small>
                                        </div>
                                    @endif
                                    <input type="file" name="citizenship_certificate"
                                        class="form-control @error('citizenship_certificate') is-invalid @enderror"
                                        accept=".pdf,image/*">
                                    @error('citizenship_certificate')<div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">PDF, JPG, PNG (Max: 2MB) - Upload new to
                                        replace</small>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Educational Certificates</label>
                                    @if($application->educational_certificates)
                                        <div class="mb-2">
                                            <small class="text-success">
                                                <i class="fas fa-check-circle"></i> Current:
                                                {{ basename($application->educational_certificates) }}
                                            </small>
                                        </div>
                                    @endif
                                    <input type="file" name="educational_certificates"
                                        class="form-control @error('educational_certificates') is-invalid @enderror"
                                        accept=".pdf,.zip">
                                    @error('educational_certificates')<div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">PDF, ZIP (Max: 10MB) - Upload new to replace</small>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Experience Certificates</label>
                                    @if($application->experience_certificates)
                                        <div class="mb-2">
                                            <small class="text-success">
                                                <i class="fas fa-check-circle"></i> Current:
                                                {{ basename($application->experience_certificates) }}
                                            </small>
                                        </div>
                                    @endif
                                    <input type="file" name="experience_certificates"
                                        class="form-control @error('experience_certificates') is-invalid @enderror"
                                        accept=".pdf,.zip">
                                    @error('experience_certificates')<div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">PDF, ZIP (Max: 10MB) - Optional</small>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">NOC ID Card</label>
                                    @if($application->noc_id_card)
                                        <div class="mb-2">
                                            <small class="text-success">
                                                <i class="fas fa-check-circle"></i> Current:
                                                {{ basename($application->noc_id_card) }}
                                            </small>
                                        </div>
                                    @endif
                                    <input type="file" name="noc_id_card"
                                        class="form-control @error('noc_id_card') is-invalid @enderror"
                                        accept=".pdf,image/*">
                                    @error('noc_id_card')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    <small class="form-text text-muted">PDF, JPG, PNG (Max: 2MB) - Optional</small>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Ethnic Certificate</label>
                                    @if($application->ethnic_certificate)
                                        <div class="mb-2">
                                            <small class="text-success">
                                                <i class="fas fa-check-circle"></i> Current:
                                                {{ basename($application->ethnic_certificate) }}
                                            </small>
                                        </div>
                                    @endif
                                    <input type="file" name="ethnic_certificate"
                                        class="form-control @error('ethnic_certificate') is-invalid @enderror"
                                        accept=".pdf,image/*">
                                    @error('ethnic_certificate')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    <small class="form-text text-muted">PDF, JPG, PNG (Max: 2MB) - Optional</small>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Disability Certificate</label>
                                    @if($application->disability_certificate)
                                        <div class="mb-2">
                                            <small class="text-success">
                                                <i class="fas fa-check-circle"></i> Current:
                                                {{ basename($application->disability_certificate) }}
                                            </small>
                                        </div>
                                    @endif
                                    <input type="file" name="disability_certificate"
                                        class="form-control @error('disability_certificate') is-invalid @enderror"
                                        accept=".pdf,image/*">
                                    @error('disability_certificate')<div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">PDF, JPG, PNG (Max: 2MB) - Optional</small>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Other Documents</label>
                                    @if($application->other_documents)
                                        <div class="mb-2">
                                            <small class="text-success">
                                                <i class="fas fa-check-circle"></i> Current:
                                                {{ basename($application->other_documents) }}
                                            </small>
                                        </div>
                                    @endif
                                    <input type="file" name="other_documents"
                                        class="form-control @error('other_documents') is-invalid @enderror"
                                        accept=".pdf,.zip">
                                    @error('other_documents')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    <small class="form-text text-muted">PDF, ZIP (Max: 10MB) - Optional</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Buttons -->
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-warning text-white">
                            <i class="fas fa-save"></i> Update Application
                        </button>
                        <a href="{{ route('candidate.applications.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Cancel
                        </a>
                        <a href="{{ route('candidate.applications.show', $application->id) }}" class="btn btn-info">
                            <i class="fas fa-eye"></i> View Application
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const checkbox = document.getElementById('same_as_permanent');
            const mailingFields = document.getElementById('mailing-address-fields');

            function toggleMailingAddress() {
                if (checkbox.checked) {
                    mailingFields.style.display = 'none';
                    // Clear required attributes
                    mailingFields.querySelectorAll('input, select').forEach(field => {
                        field.removeAttribute('required');
                    });
                } else {
                    mailingFields.style.display = 'block';
                }
            }

            checkbox.addEventListener('change', toggleMailingAddress);
            toggleMailingAddress(); // Set initial state
        });
    </script>
@endsection