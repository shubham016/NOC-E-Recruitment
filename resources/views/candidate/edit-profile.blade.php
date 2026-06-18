@extends('layouts.app')

@section('title', __('candidate.edit_profile'))

@section('sidebar-menu')
    <a href="{{ route('candidate.dashboard') }}" class="sidebar-menu-item">
        <i class="bi bi-speedometer2"></i><span>{{ __('candidate.dashboard') }}</span>
    </a>
    <a href="{{ route('candidate.my-profile') }}" class="sidebar-menu-item active">
        <i class="bi bi-person"></i><span>{{ __('candidate.my_profile') }}</span>
    </a>
    <a href="{{ route('candidate.jobs.index') }}" class="sidebar-menu-item">
        <i class="bi bi-search"></i><span>{{ __('candidate.vacancy') }}</span>
    </a>
    <a href="{{ route('candidate.applications.index') }}" class="sidebar-menu-item">
        <i class="bi bi-file-earmark-text"></i><span>{{ __('candidate.my_applications') }}</span>
    </a>
    <a href="{{ route('candidate.viewresult') }}" class="sidebar-menu-item">
        <i class="bi bi-file-earmark-check"></i><span>{{ __('candidate.view_result') }}</span>
    </a>
    <a href="{{ route('candidate.admit-card') }}" class="sidebar-menu-item">
        <i class="bi bi-box-arrow-down"></i><span>{{ __('candidate.download_admit_card') }}</span>
    </a>
    <a href="{{ route('candidate.change-password') }}" class="sidebar-menu-item">
        <i class="bi bi-lock"></i><span>{{ __('candidate.change_password') }}</span>
    </a>
@endsection

@push('styles')
<link rel="stylesheet" href="https://nepalidatepicker.sajanmaharjan.com.np/v5/nepali.datepicker/css/nepali.datepicker.v5.0.6.min.css">
<style>
    .step-tabs { position: relative; margin-bottom: 2.5rem; }
    .step-tabs .d-flex { gap: 10px; padding-bottom: 10px; }
    .tab-item {
        flex: 1; text-align: center; padding: 15px 8px; cursor: pointer; transition: all 0.3s;
        position: relative; min-width: 120px; user-select: none;
    }
    .tab-circle {
        display: inline-flex; align-items: center; justify-content: center;
        width: 40px; height: 40px; background: #e9ecef; color: #6c757d;
        border-radius: 50%; font-weight: bold; font-size: 1.1rem; transition: all 0.3s; margin-bottom: 8px;
    }
    .tab-label { font-size: 0.9rem; color: #6c757d; display: block; transition: color 0.3s; }
    .tab-item.active .tab-circle, .tab-item.completed .tab-circle { background: #000; color: #fff; }
    .tab-item.active .tab-label, .tab-item.completed .tab-label { color: #000; font-weight: 600; }
    .tab-item:hover .tab-circle { background: #000; color: #fff; }
    .tab-item:hover .tab-label { color: #000; }
    .progress-line { display: none; }
    .step { transition: opacity 0.4s; }
    .step.active { opacity: 1; }
    .step.d-none { opacity: 0; position: absolute; top: 0; left: 0; width: 100%; pointer-events: none; visibility: hidden; }
    .is-invalid { border-color: #dc3545 !important; }
    .invalid-feedback { color: #dc3545; font-size: 0.875rem; margin-top: 0.25rem; display: block; }
    .ndp-wrapper { position: relative; }
    .ndp-wrapper input { padding-right: 2.25rem; }
    .ndp-icon { position: absolute; right: 0.65rem; top: 50%; transform: translateY(-50%); color: #bbb; font-size: 0.9rem; pointer-events: none; z-index: 2; }
    .ndp-wrapper:focus-within .ndp-icon { color: #1a2a4a; }
    .exp-block { background: #f8f9fa; }
    #birth_date_bs, #citizenship_issue_date_bs { height: auto !important; min-height: calc(1.5em + 0.75rem + 2px); }
    @media (max-width: 768px) {
        .tab-label { font-size: 0.8rem; }
        .tab-item { padding: 12px 4px; }
        .tab-circle { width: 35px; height: 35px; font-size: 1rem; }
    }
</style>
@endpush

@section('content')
<div class="container my-2">
    <div class="card shadow-lg border-0">
        <div class="card-header bg-light text-dark d-flex justify-content-between align-items-center py-2">
            <h3 class="mb-0 fw-bold">{{ __('candidate.edit_my_profile') }}</h3>
            <a href="{{ route('candidate.my-profile') }}" class="btn btn-secondary btn-sm">
                <i class="bi bi-eye me-1"></i> {{ __('candidate.view_profile') }}
            </a>
        </div>

        <div class="card-body px-5 pt-3 pb-5">
            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show mb-4">
                    <strong>{{ __('candidate.please_fix_errors') }}:</strong>
                    <ul class="mb-0 mt-2">
                        @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show mb-4">
                    <i class="bi bi-check-circle me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="step-tabs mb-5">
                <div class="d-flex justify-content-evenly border-bottom position-relative">
                    <div class="tab-item active" data-step="1"><span class="tab-circle">1</span><span class="tab-label d-none d-md-inline">{{ __('candidate.personal') }}</span></div>
                    <div class="tab-item" data-step="2"><span class="tab-circle">2</span><span class="tab-label d-none d-md-inline">{{ __('candidate.general') }}</span></div>
                    <div class="tab-item" data-step="3"><span class="tab-circle">3</span><span class="tab-label d-none d-md-inline">{{ __('candidate.address') }}</span></div>
                    <div class="tab-item" data-step="4"><span class="tab-circle">4</span><span class="tab-label d-none d-md-inline">{{ __('candidate.education') }}</span></div>
                    <div class="tab-item" data-step="5"><span class="tab-circle">5</span><span class="tab-label d-none d-md-inline">{{ __('candidate.experience') }}</span></div>
                    <div class="tab-item" data-step="6"><span class="tab-circle">6</span><span class="tab-label d-none d-md-inline">{{ __('candidate.documents') }}</span></div>
                    <!-- <div class="tab-item" data-step="7"><span class="tab-circle">7</span><span class="tab-label d-none d-md-inline">Review</span></div> -->
                    <div class="progress-line"></div>
                </div>
            </div>

            <form action="{{ route('candidate.my-profile.update') }}" method="POST" enctype="multipart/form-data" id="editProfileForm" novalidate>
                @csrf
                @method('PUT')

                {{-- STEP 1: Personal Information --}}
                <div class="step active" id="step1">
                    <h5 class="mb-4 text-dark">Step 1 - {{ __('candidate.personal_information') }}</h5>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">{{ __('candidate.full_name_english') }} <span class="text-danger">*</span></label>
                            <input type="text" name="name_english" id="name_english" class="form-control @error('name_english') is-invalid @enderror"
                                value="{{ old('name_english', $candidate->name_english ?? '') }}" required>
                            @error('name_english')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">{{ __('candidate.full_name_nepali') }} <span class="text-danger">*</span></label>
                            <input type="text" name="name_nepali" id="name_nepali"
                                class="form-control nepali-only @error('name_nepali') is-invalid @enderror"
                                placeholder="नेपालीमा नाम लेख्नुहोस्"
                                value="{{ old('name_nepali', $candidate->name_nepali ?? '') }}"
                                required autocomplete="off" inputmode="text" style="ime-mode:active;">
                            <small class="text-muted">Only Devanagari (नेपाली) characters allowed</small>
                            @error('name_nepali')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-3">
                            <label class="form-label">{{ __('candidate.birth_date_bs') }} <span class="text-danger">*</span></label>
                            <div class="ndp-wrapper">
                                <input type="text" name="birth_date_bs" id="birth_date_bs" class="form-control @error('birth_date_bs') is-invalid @enderror"
                                    placeholder="YYYY-MM-DD" autocomplete="off"
                                    value="{{ old('birth_date_bs', $candidate->birth_date_bs ?? '') }}" required>
                                <span class="ndp-icon"><i class="bi bi-calendar-event"></i></span>
                            </div>
                            @error('birth_date_bs')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">{{ __('candidate.birth_date_ad') }} <small>(auto-filled)</small></label>
                            <input type="text" id="birth_date_ad_display" class="form-control bg-light" placeholder="YYYY-MMM-DD"
                                value="{{ old('birth_date_ad', $candidate->birth_date_ad ? \Carbon\Carbon::parse($candidate->birth_date_ad)->format('Y-M-d') : '') }}" readonly>
                            <input type="hidden" name="birth_date_ad" id="birth_date_ad"
                                value="{{ old('birth_date_ad', $candidate->birth_date_ad ? \Carbon\Carbon::parse($candidate->birth_date_ad)->format('Y-m-d') : '') }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">{{ __('candidate.email') }} <span class="text-danger">*</span></label>
                            <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror"
                                value="{{ old('email', $candidate->email ?? '') }}" required>
                            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">{{ __('candidate.phone_number') }} <span class="text-danger">*</span></label>
                            <input type="text" name="phone" id="phone" class="form-control @error('phone') is-invalid @enderror"
                                value="{{ old('phone', $candidate->phone ?? '') }}" required>
                            @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label class="form-label">{{ __('candidate.age') }}</label>
                            <input type="text" name="age" id="age" class="form-control" readonly
                                value="{{ old('age', $candidate->age ?? '') }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">{{ __('candidate.alternate_phone_number') }}</label>
                            <input type="text" name="alternate_phone_number" id="alternate_phone_number" class="form-control @error('alternate_phone_number') is-invalid @enderror"
                                value="{{ old('alternate_phone_number', $candidate->alternate_phone_number ?? '') }}">
                            @error('alternate_phone_number')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">{{ __('candidate.gender') }} <span class="text-danger">*</span></label>
                            <select name="gender" id="gender" class="form-select @error('gender') is-invalid @enderror" required>
                                <option value="">-- Select --</option>
                                <option value="Male"   {{ old('gender', $candidate->gender ?? '') == 'Male'   ? 'selected' : '' }}>Male / पुरुष</option>
                                <option value="Female" {{ old('gender', $candidate->gender ?? '') == 'Female' ? 'selected' : '' }}>Female / महिला</option>
                                <option value="Other"  {{ old('gender', $candidate->gender ?? '') == 'Other'  ? 'selected' : '' }}>Other / अन्य</option>
                            </select>
                            @error('gender')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label class="form-label">{{ __('candidate.marital_status') }} <span class="text-danger">*</span></label>
                            <select name="marital_status" id="marital_status" class="form-select @error('marital_status') is-invalid @enderror" required>
                                <option value="">-- Select --</option>
                                <option value="Single"   {{ old('marital_status', $candidate->marital_status ?? '') == 'Single'   ? 'selected' : '' }}>Single</option>
                                <option value="Married"  {{ old('marital_status', $candidate->marital_status ?? '') == 'Married'  ? 'selected' : '' }}>Married</option>
                                <option value="Divorced" {{ old('marital_status', $candidate->marital_status ?? '') == 'Divorced' ? 'selected' : '' }}>Divorced</option>
                                <option value="Widowed"  {{ old('marital_status', $candidate->marital_status ?? '') == 'Widowed'  ? 'selected' : '' }}>Widowed</option>
                            </select>
                            @error('marital_status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">{{ __('candidate.spouse_name_if_married') }}</label>
                            <input type="text" name="spouse_name_english" id="spouse_name_english" class="form-control @error('spouse_name_english') is-invalid @enderror"
                                value="{{ old('spouse_name_english', $candidate->spouse_name_english ?? '') }}">
                            @error('spouse_name_english')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">{{ __('candidate.spouse_nationality_if_married') }}</label>
                            <input type="text" name="spouse_nationality" id="spouse_nationality" class="form-control @error('spouse_nationality') is-invalid @enderror"
                                value="{{ old('spouse_nationality', $candidate->spouse_nationality ?? '') }}">
                            @error('spouse_nationality')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label class="form-label">{{ __('candidate.citizenship_number') }} <span class="text-danger">*</span></label>
                            <input type="text" name="citizenship_number" id="citizenship_number" class="form-control @error('citizenship_number') is-invalid @enderror"
                                value="{{ old('citizenship_number', $candidate->citizenship_number ?? '') }}" required>
                            @error('citizenship_number')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">{{ __('candidate.citizenship_issue_date_bs') }} <span class="text-danger">*</span></label>
                            <div class="ndp-wrapper">
                                <input type="text" name="citizenship_issue_date_bs" id="citizenship_issue_date_bs"
                                    class="form-control @error('citizenship_issue_date_bs') is-invalid @enderror"
                                    placeholder="YYYY-MM-DD" autocomplete="off"
                                    value="{{ old('citizenship_issue_date_bs', $candidate->citizenship_issue_date_bs ?? '') }}" required>
                                <span class="ndp-icon"><i class="bi bi-calendar-check"></i></span>
                            </div>
                            @error('citizenship_issue_date_bs')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">{{ __('candidate.citizenship_issue_district') }} <span class="text-danger">*</span></label>
                            <input type="text" name="citizenship_issue_district" id="citizenship_issue_district"
                                class="form-control @error('citizenship_issue_district') is-invalid @enderror"
                                value="{{ old('citizenship_issue_district', $candidate->citizenship_issue_district ?? $candidate->citizenship_issue_distric ?? '') }}" required>
                            @error('citizenship_issue_district')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label class="form-label">{{ __('candidate.father_name_english') }} <span class="text-danger">*</span></label>
                            <input type="text" name="father_name_english" id="father_name_english" class="form-control @error('father_name_english') is-invalid @enderror"
                                value="{{ old('father_name_english', $candidate->father_name_english ?? '') }}" required>
                            @error('father_name_english')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">{{ __('candidate.mother_name_english') }} <span class="text-danger">*</span></label>
                            <input type="text" name="mother_name_english" id="mother_name_english" class="form-control @error('mother_name_english') is-invalid @enderror"
                                value="{{ old('mother_name_english', $candidate->mother_name_english ?? '') }}" required>
                            @error('mother_name_english')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">{{ __('candidate.grandfather_name_english') }} <span class="text-danger">*</span></label>
                            <input type="text" name="grandfather_name_english" id="grandfather_name_english" class="form-control @error('grandfather_name_english') is-invalid @enderror"
                                value="{{ old('grandfather_name_english', $candidate->grandfather_name_english ?? '') }}" required>
                            @error('grandfather_name_english')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label class="form-label">{{ __('candidate.father_name_nepali') }} <span class="text-danger">*</span></label>
                            <input type="text" name="father_name_nepali" id="father_name_nepali" class="form-control @error('father_name_nepali') is-invalid @enderror"
                                value="{{ old('father_name_nepali', $candidate->father_name_nepali ?? '') }}" required>
                           <small class="text-muted">Only Devanagari (नेपाली) characters allowed</small>
                                @error('father_name_nepali')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">{{ __('candidate.mother_name_nepali') }} <span class="text-danger">*</span></label>
                            <input type="text" name="mother_name_nepali" id="mother_name_nepali" class="form-control @error('mother_name_nepali') is-invalid @enderror"
                            value="{{ old('mother_name_nepali', $candidate->mother_name_nepali ?? '') }}" required>
                            <small class="text-muted">Only Devanagari (नेपाली) characters allowed</small>
                            @error('mother_name_nepali')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">{{ __('candidate.grandfather_name_nepali') }} <span class="text-danger">*</span></label>
                            <input type="text" name="grandfather_name_nepali" id="grandfather_name_nepali" class="form-control @error('grandfather_name_nepali') is-invalid @enderror"
                            value="{{ old('grandfather_name_nepali', $candidate->grandfather_name_nepali ?? '') }}" required>
                            <small class="text-muted">Only Devanagari (नेपlी) characters allowed</small>
                            @error('grandfather_name_nepali')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label class="form-label">{{ __('candidate.blood_group') }} <span class="text-danger">*</span></label>
                            <select name="blood_group" id="blood_group" class="form-select @error('blood_group') is-invalid @enderror" required>
                                <option value="">-- Select --</option>
                                @foreach(['A+','A-','B+','B-','AB+','AB-','O+','O-'] as $bg)
                                    <option value="{{ $bg }}" {{ old('blood_group', $candidate->blood_group ?? '') == $bg ? 'selected' : '' }}>{{ $bg }}</option>
                                @endforeach
                            </select>
                            @error('blood_group')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">{{ __('candidate.nationality') }} <span class="text-danger">*</span></label>
                            <input type="text" name="nationality" id="nationality" class="form-control @error('nationality') is-invalid @enderror"
                                value="{{ old('nationality', $candidate->nationality ?? '') }}" required>
                            @error('nationality')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">{{ __('candidate.are_you_noc_employee') }} <span class="text-danger">*</span></label>
                            <select name="noc_employee" id="noc_employee" class="form-select @error('noc_employee') is-invalid @enderror" required>
                                <option value="">-- Select --</option>
                                <option value="yes" {{ old('noc_employee', $candidate->noc_employee ?? '') == 'yes' ? 'selected' : '' }}>Yes</option>
                                <option value="no"  {{ old('noc_employee', $candidate->noc_employee ?? '') == 'no'  ? 'selected' : '' }}>No</option>
                            </select>
                            @error('noc_employee')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label" id="noc_id_card_label">{{ __('candidate.noc_id_card') }}</label>
                            @if(!empty($candidate->noc_id_card))
                                <div class="mb-2">{!! showDoc($candidate->noc_id_card) !!}</div>
                            @endif
                            <input type="file" name="noc_id_card" id="noc_id_card" class="form-control @error('noc_id_card') is-invalid @enderror"
                                accept="image/*,application/pdf"
                                {{ !empty($candidate->noc_id_card) ? 'data-existing-file="1"' : '' }}>
                            <small class="text-muted d-block">Max Size: 700KB. Leave blank to keep existing.</small>
                            @error('noc_id_card')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">{{ __('candidate.physical_disability') }} <span class="text-danger">*</span></label>
                            <select name="physical_disability" id="physical_disability" class="form-select @error('physical_disability') is-invalid @enderror" required>
                                <option value="">-- Select --</option>
                                <option value="yes" {{ old('physical_disability', $candidate->physical_disability ?? '') == 'yes' ? 'selected' : '' }}>Yes</option>
                                <option value="no"  {{ old('physical_disability', $candidate->physical_disability ?? '') == 'no'  ? 'selected' : '' }}>No</option>
                            </select>
                            @error('physical_disability')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="row mb-3" id="disabilityCertWrapper"
                         style="{{ old('physical_disability', $candidate->physical_disability ?? null) == 'yes' ? '' : 'display:none;' }}">
                        <div class="col-md-6">
                            <label class="form-label" id="disability_certificate_label">{{ __('candidate.disability_certificate') }}</label>
                            @if(!empty($candidate->disability_certificate))
                                <div class="mb-2">{!! showDoc($candidate->disability_certificate) !!}</div>
                            @endif
                            <input type="file" name="disability_certificate" id="disability_certificate"
                                class="form-control @error('disability_certificate') is-invalid @enderror"
                                accept="image/*,application/pdf"
                                {{ !empty($candidate->disability_certificate) ? 'data-existing-file="1"' : '' }}>
                            <small class="text-muted d-block">Max Size: 700KB</small>
                            @error('disability_certificate')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                            <label class="form-label">{{ __('candidate.parents_occupation') }} <span class="text-danger">*</span></label>
                            <input type="text" name="parents_occupation" id="parents_occupation" class="form-control @error('parents_occupation') is-invalid @enderror"
                                value="{{ old('parents_occupation', $candidate->parents_occupation ?? '') }}" required>
                            @error('parents_occupation')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                    <div class="d-flex justify-content-end">
                        <button type="button" class="btn btn-light next-btn">Next</button>
                    </div>
                </div>

                {{-- STEP 2: General Information --}}
                <div class="step d-none" id="step2">
                    <h5 class="mb-4 text-dark">Step 2 - {{ __('candidate.general_information') }}</h5>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label class="form-label">{{ __('candidate.religion') }} <span class="text-danger">*</span></label>
                            <select name="religion" id="religion" class="form-select @error('religion') is-invalid @enderror" required>
                                <option value="">-- Select --</option>
                                <option value="Hindu"     {{ old('religion', $candidate->religion ?? '') == 'Hindu'     ? 'selected' : '' }}>Hindu / हिन्दू</option>
                                <option value="Buddhist"  {{ old('religion', $candidate->religion ?? '') == 'Buddhist'  ? 'selected' : '' }}>Buddhist / बौद्ध</option>
                                <option value="Christian" {{ old('religion', $candidate->religion ?? '') == 'Christian' ? 'selected' : '' }}>Christian / ख्रीष्टिय</option>
                                <option value="Muslim"    {{ old('religion', $candidate->religion ?? '') == 'Muslim'    ? 'selected' : '' }}>Muslim / मुस्लिम</option>
                                <option value="Kirat"     {{ old('religion', $candidate->religion ?? '') == 'Kirat'     ? 'selected' : '' }}>Kirat / किरात</option>
                                <option value="Other"     {{ old('religion', $candidate->religion ?? '') == 'Other'     ? 'selected' : '' }}>Other / अन्य</option>
                            </select>
                            <input type="text" name="religion_other" id="religion_other"
                                class="form-control mt-2 {{ old('religion', $candidate->religion ?? '') == 'Other' ? '' : 'd-none' }}"
                                placeholder="If other, specify"
                                value="{{ old('religion_other', $candidate->religion_other ?? '') }}">
                            @error('religion')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">{{ __('candidate.community') }} <span class="text-danger">*</span></label>
                            <select name="community" id="community" class="form-select @error('community') is-invalid @enderror" required>
                                <option value="">-- Select --</option>
                                <option value="Male"   {{ old('community', $candidate->community ?? '') == 'Male'   ? 'selected' : '' }}>पुरुष</option>
                                <option value="Female" {{ old('community', $candidate->community ?? '') == 'Female' ? 'selected' : '' }}>महिला</option>
                                <option value="LGBTQ"  {{ old('community', $candidate->community ?? '') == 'LGBTQ'  ? 'selected' : '' }}>LGBTQ+</option>
                                <option value="Other"  {{ old('community', $candidate->community ?? '') == 'Other'  ? 'selected' : '' }}>Other / अन्य</option>
                            </select>
                            <input type="text" name="community_other" id="community_other"
                                class="form-control mt-2 {{ old('community', $candidate->community ?? '') == 'Other' ? '' : 'd-none' }}"
                                placeholder="If other, specify"
                                value="{{ old('community_other', $candidate->community_other ?? '') }}">
                            @error('community')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">{{ __('candidate.ethnic_group') }} <span class="text-danger">*</span></label>
                            <select name="ethnic_group" id="ethnic_group" class="form-select @error('ethnic_group') is-invalid @enderror" required>
                                <option value="">-- Select --</option>
                                <option value="Dalit"           {{ old('ethnic_group', $candidate->ethnic_group ?? '') == 'Dalit'           ? 'selected' : '' }}>Dalit</option>
                                <option value="Janajati"        {{ old('ethnic_group', $candidate->ethnic_group ?? '') == 'Janajati'        ? 'selected' : '' }}>Janajati</option>
                                <option value="Madhesi"         {{ old('ethnic_group', $candidate->ethnic_group ?? '') == 'Madhesi'         ? 'selected' : '' }}>Madhesi</option>
                                <option value="Brahmin/Chhetri" {{ old('ethnic_group', $candidate->ethnic_group ?? '') == 'Brahmin/Chhetri' ? 'selected' : '' }}>Brahmin / Chhetri</option>
                                <option value="Other"           {{ old('ethnic_group', $candidate->ethnic_group ?? '') == 'Other'           ? 'selected' : '' }}>Other</option>
                            </select>
                            <input type="text" name="ethnic_group_other" id="ethnic_group_other"
                                class="form-control mt-2 {{ old('ethnic_group', $candidate->ethnic_group ?? '') == 'Other' ? '' : 'd-none' }}"
                                placeholder="If other, specify"
                                value="{{ old('ethnic_group_other', $candidate->ethnic_group_other ?? '') }}">
                            @error('ethnic_group')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label" id="ethnic_certificate_label">{{ __('candidate.ethnic_certificate') }}</label>
                            @if(!empty($candidate->ethnic_certificate))
                                <div class="mb-2">{!! showDoc($candidate->ethnic_certificate) !!}</div>
                            @endif
                            <input type="file" name="ethnic_certificate" id="ethnic_certificate"
                                class="form-control @error('ethnic_certificate') is-invalid @enderror"
                                accept="image/*,application/pdf"
                                {{ !empty($candidate->ethnic_certificate) ? 'data-existing-file="1"' : '' }}>
                            <small class="text-muted">Max Size: 700KB</small>
                            @error('ethnic_certificate')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">{{ __('candidate.mother_tongue') }} <span class="text-danger">*</span></label>
                            <input type="text" name="mother_tongue" id="mother_tongue" class="form-control @error('mother_tongue') is-invalid @enderror"
                                value="{{ old('mother_tongue', $candidate->mother_tongue ?? '') }}" required>
                            @error('mother_tongue')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">{{ __('candidate.employment_status') }} <span class="text-danger">*</span></label>
                            <select name="employment_status" id="employment_status" class="form-select @error('employment_status') is-invalid @enderror" required>
                                <option value="">-- Select --</option>
                                <option value="employed"      {{ old('employment_status', $candidate->employment_status ?? '') == 'employed'      ? 'selected' : '' }}>Employed</option>
                                <option value="unemployed"    {{ old('employment_status', $candidate->employment_status ?? '') == 'unemployed'    ? 'selected' : '' }}>Unemployed</option>
                                <option value="self-employed" {{ old('employment_status', $candidate->employment_status ?? '') == 'self-employed' ? 'selected' : '' }}>Self Employed</option>
                                <option value="student"       {{ old('employment_status', $candidate->employment_status ?? '') == 'student'       ? 'selected' : '' }}>Student</option>
                            </select>
                            @error('employment_status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="d-flex justify-content-between">
                        <button type="button" class="btn btn-secondary prev-btn">Back</button>
                        <button type="button" class="btn btn-light next-btn">Next</button>
                    </div>
                </div>

                {{-- STEP 3: Address --}}
                <div class="step d-none" id="step3">
                    <h5 class="mb-4 text-dark">Step 3 - {{ __('candidate.permanent_address') }}</h5>
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label class="form-label">{{ __('candidate.province') }} <span class="text-danger">*</span></label>
                            <select name="permanent_province" id="permanent_province" class="form-select @error('permanent_province') is-invalid @enderror" required onchange="cascadeDistrict('permanent')">
                                <option value="">-- Select Province --</option>
                                @foreach(['Koshi','Madhesh','Bagmati','Gandaki','Lumbini','Karnali','Sudurpashchim'] as $p)
                                    <option value="{{ $p }}" {{ old('permanent_province', $candidate->permanent_province ?? '') == $p ? 'selected' : '' }}>{{ $p }}</option>
                                @endforeach
                            </select>
                            @error('permanent_province')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">{{ __('candidate.district') }} <span class="text-danger">*</span></label>
                            <select name="permanent_district" id="permanent_district" class="form-select @error('permanent_district') is-invalid @enderror" required onchange="cascadeMunicipality('permanent')" disabled>
                                <option value="">-- Select District --</option>
                            </select>
                            @error('permanent_district')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">{{ __('candidate.municipality') }} <span class="text-danger">*</span></label>
                            <select name="permanent_municipality" id="permanent_municipality" class="form-select @error('permanent_municipality') is-invalid @enderror" required disabled>
                                <option value="">-- Select Municipality --</option>
                            </select>
                            @error('permanent_municipality')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label class="form-label">{{ __('candidate.ward_no') }} <span class="text-danger">*</span></label>
                            <input type="text" name="permanent_ward" id="permanent_ward" class="form-control @error('permanent_ward') is-invalid @enderror"
                                value="{{ old('permanent_ward', $candidate->permanent_ward ?? '') }}" required>
                            @error('permanent_ward')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">{{ __('candidate.tole') }}</label>
                            <input type="text" name="permanent_tole" id="permanent_tole" class="form-control"
                                value="{{ old('permanent_tole', $candidate->permanent_tole ?? '') }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">{{ __('candidate.house_number') }}</label>
                            <input type="text" name="permanent_house_number" id="permanent_house_number" class="form-control"
                                value="{{ old('permanent_house_number', $candidate->permanent_house_number ?? '') }}">
                        </div>
                    </div>

                    <h5 class="mb-3 mt-4 text-dark">{{ __('candidate.mailing_current_address') }}</h5>
                    <div class="form-check mb-3">
                        <input type="checkbox" class="form-check-input" id="same_as_permanent" name="same_as_permanent" value="1"
                            {{ old('same_as_permanent', $candidate->same_as_permanent ?? false) ? 'checked' : '' }} onchange="toggleSameAsPermanent()">
                        <label class="form-check-label" for="same_as_permanent">{{ __('candidate.same_as_permanent_address') }}</label>
                    </div>

                    <div id="mailing_fields">
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label class="form-label">{{ __('candidate.province') }} <span class="text-danger">*</span></label>
                                <select name="mailing_province" id="mailing_province" class="form-select @error('mailing_province') is-invalid @enderror" required onchange="cascadeDistrict('mailing')">
                                    <option value="">-- Select Province --</option>
                                    @foreach(['Koshi','Madhesh','Bagmati','Gandaki','Lumbini','Karnali','Sudurpashchim'] as $p)
                                        <option value="{{ $p }}" {{ old('mailing_province', $candidate->mailing_province ?? '') == $p ? 'selected' : '' }}>{{ $p }}</option>
                                    @endforeach
                                </select>
                                @error('mailing_province')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">{{ __('candidate.district') }} <span class="text-danger">*</span></label>
                                <select name="mailing_district" id="mailing_district" class="form-select @error('mailing_district') is-invalid @enderror" required onchange="cascadeMunicipality('mailing')" disabled>
                                    <option value="">-- Select District --</option>
                                </select>
                                @error('mailing_district')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">{{ __('candidate.municipality') }} <span class="text-danger">*</span></label>
                                <select name="mailing_municipality" id="mailing_municipality" class="form-select @error('mailing_municipality') is-invalid @enderror" required disabled>
                                    <option value="">-- Select Municipality --</option>
                                </select>
                                @error('mailing_municipality')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label class="form-label">{{ __('candidate.ward_no') }} <span class="text-danger">*</span></label>
                                <input type="text" name="mailing_ward" id="mailing_ward" class="form-control @error('mailing_ward') is-invalid @enderror"
                                    value="{{ old('mailing_ward', $candidate->mailing_ward ?? '') }}" required>
                                @error('mailing_ward')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">{{ __('candidate.tole') }}</label>
                                <input type="text" name="mailing_tole" id="mailing_tole" class="form-control"
                                    value="{{ old('mailing_tole', $candidate->mailing_tole ?? '') }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">{{ __('candidate.house_number') }}</label>
                                <input type="text" name="mailing_house_number" id="mailing_house_number" class="form-control"
                                    value="{{ old('mailing_house_number', $candidate->mailing_house_number ?? '') }}">
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between">
                        <button type="button" class="btn btn-secondary prev-btn">Back</button>
                        <button type="button" class="btn btn-light next-btn">Next</button>
                    </div>
                </div>

                {{-- STEP 4: Education --}}
                <div class="step d-none" id="step4">
                    <h5 class="mb-4 text-dark">Step 4 - {{ __('candidate.educational_background') }}</h5>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">{{ __('candidate.highest_education_level') }} <span class="text-danger">*</span></label>
                            <select name="education_level" id="education_level" class="form-select @error('education_level') is-invalid @enderror" required>
                                <option value="">-- Select --</option>
                                @foreach(['Under SLC','SLC/SEE','+2/Intermediate','Bachelor','Master','PhD','Other'] as $el)
                                    <option value="{{ $el }}" {{ old('education_level', $candidate->education_level ?? '') == $el ? 'selected' : '' }}>{{ $el }}</option>
                                @endforeach
                            </select>
                            @error('education_level')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">{{ __('candidate.field_of_study') }} <span class="text-danger">*</span></label>
                            <input type="text" name="field_of_study" id="field_of_study" class="form-control @error('field_of_study') is-invalid @enderror"
                                value="{{ old('field_of_study', $candidate->field_of_study ?? '') }}" required>
                            @error('field_of_study')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">{{ __('candidate.institution_name') }} <span class="text-danger">*</span></label>
                            <input type="text" name="institution_name" id="institution_name" class="form-control @error('institution_name') is-invalid @enderror"
                                value="{{ old('institution_name', $candidate->institution_name ?? '') }}" required>
                            @error('institution_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">{{ __('candidate.passed_year_bs') }} <span class="text-danger">*</span></label>
                            <input type="text" name="graduation_year" id="graduation_year" class="form-control @error('graduation_year') is-invalid @enderror"
                                placeholder="YYYY" maxlength="4" inputmode="numeric"
                                value="{{ old('graduation_year', $candidate->graduation_year ?? '') }}" required>
                            @error('graduation_year')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">{{ __('candidate.passed_year_ad') }}</label>
                            <input type="text" name="graduation_year_english" id="graduation_year_english" class="form-control @error('graduation_year_english') is-invalid @enderror"
                                placeholder="YYYY" maxlength="4" inputmode="numeric"
                                value="{{ old('graduation_year_english', $candidate->graduation_year_english ?? '') }}">
                            @error('graduation_year_english')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">{{ __('candidate.university_name') }} <span class="text-danger">*</span></label>
                            <input type="text" name="university" id="university" class="form-control @error('university') is-invalid @enderror"
                                value="{{ old('university', $candidate->university ?? '') }}" required>
                            @error('university')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">{{ __('candidate.transcript_certificate') }} <span class="text-danger">*</span></label>
                            @if(!empty($candidate->transcript))
                                <div class="mb-2">{!! showDoc($candidate->transcript) !!}</div>
                            @endif
                            <input type="file" name="transcript" id="transcript" class="form-control @error('transcript') is-invalid @enderror"
                                accept="image/*,application/pdf"
                                {{ !empty($candidate->transcript) ? 'data-existing-file="1"' : 'required' }}>
                            <small class="text-muted d-block">Max Size: 700KB</small>
                            @error('transcript')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">{{ __('candidate.character_certificate') }} <span class="text-danger">*</span></label>
                            @if(!empty($candidate->character_certificate ?? $candidate->character))
                                <div class="mb-2">{!! showDoc($candidate->character_certificate ?? $candidate->character) !!}</div>
                            @endif
                            <input type="file" name="character_certificate" id="character_certificate" class="form-control @error('character_certificate') is-invalid @enderror"
                                accept="image/*,application/pdf"
                                {{ !empty($candidate->character_certificate ?? $candidate->character) ? 'data-existing-file="1"' : 'required' }}>
                            <small class="text-muted d-block">Max Size: 700KB</small>
                            @error('character_certificate')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">{{ __('candidate.equivalency_certificate') }} <small>(If degree is from outside Nepal)</small></label>
                            @if(!empty($candidate->equivalency_certificate ?? $candidate->equivalent))
                                <div class="mb-2">{!! showDoc($candidate->equivalency_certificate ?? $candidate->equivalent) !!}</div>
                            @endif
                            <input type="file" name="equivalency_certificate" id="equivalency_certificate" class="form-control @error('equivalency_certificate') is-invalid @enderror"
                                accept="image/*,application/pdf"
                                {{ !empty($candidate->equivalency_certificate ?? $candidate->equivalent) ? 'data-existing-file="1"' : '' }}>
                            <small class="text-muted d-block">Max Size: 700KB</small>
                            @error('equivalency_certificate')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="d-flex justify-content-between">
                        <button type="button" class="btn btn-secondary prev-btn">Back</button>
                        <button type="button" class="btn btn-light next-btn">Next</button>
                    </div>
                </div>

                {{-- STEP 5: Work Experience --}}
                <div class="step d-none" id="step5">
                    <h5 class="mb-4 text-dark">Step 5 - {{ __('candidate.work_experience') }}</h5>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">{{ __('candidate.has_work_experience') }} <span class="text-danger">*</span></label>
                            <select name="has_work_experience" id="has_work_experience" class="form-select @error('has_work_experience') is-invalid @enderror" required>
                                <option value="">-- Select --</option>
                                <option value="Yes" {{ old('has_work_experience', $candidate->has_work_experience ?? '') == 'Yes' ? 'selected' : '' }}>Yes</option>
                                <option value="No"  {{ old('has_work_experience', $candidate->has_work_experience ?? '') == 'No'  ? 'selected' : '' }}>No</option>
                            </select>
                            @error('has_work_experience')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div id="experience_table_wrapper" style="display:none;">
                        <div id="experience_rows">
                            {{-- Row 1 --}}
                            <div class="experience-row exp-block border rounded p-3 mb-3" data-row="1">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <strong class="text-muted" style="font-size:.9rem;">Experience #<span class="row-number">1</span></strong>
                                    <button type="button" class="btn btn-sm btn-outline-danger remove-exp-row" style="display:none;">
                                        <i class="bi bi-trash"></i> Remove
                                    </button>
                                </div>
                                <div class="row g-2">
                                    <div class="col-md-4">
                                        <label class="form-label small">{{ __('candidate.organization') }}</label>
                                        <input type="text" name="exp1_organization" class="form-control form-control-sm"
                                            value="{{ old('exp1_organization', $candidate->exp1_organization ?? '') }}">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label small">{{ __('candidate.position') }}</label>
                                        <input type="text" name="exp1_position" class="form-control form-control-sm"
                                            value="{{ old('exp1_position', $candidate->exp1_position ?? '') }}">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label small">{{ __('candidate.start_date_bs') }}</label>
                                        <input type="text" name="exp1_start_date_bs" class="form-control form-control-sm exp-nepali-date"
                                            placeholder="YYYY-MM-DD" data-target="exp1_start_date" autocomplete="off"
                                            value="{{ old('exp1_start_date_bs', $candidate->exp1_start_date_bs ?? '') }}">
                                        <input type="hidden" name="exp1_start_date" value="{{ $candidate->exp1_start_date ?? '' }}">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label small">{{ __('candidate.end_date_bs') }}</label>
                                        <input type="text" name="exp1_end_date_bs" class="form-control form-control-sm exp-nepali-date"
                                            placeholder="YYYY-MM-DD" data-target="exp1_end_date" autocomplete="off"
                                            value="{{ old('exp1_end_date_bs', $candidate->exp1_end_date_bs ?? '') }}">
                                        <input type="hidden" name="exp1_end_date" value="{{ $candidate->exp1_end_date ?? '' }}">
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label small">{{ __('candidate.years') }}</label>
                                        <input type="number" step="0.5" name="exp1_years" class="form-control form-control-sm"
                                            value="{{ old('exp1_years', $candidate->exp1_years ?? '') }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label small">{{ __('candidate.document') }}</label>
                                        @if(!empty($candidate->exp1_document))
                                            <div class="mb-1">{!! showDoc($candidate->exp1_document) !!}</div>
                                        @endif
                                        <input type="file" name="exp1_document" class="form-control form-control-sm" accept="image/*,application/pdf">
                                    </div>
                                </div>
                            </div>

                            {{-- Row 2 --}}
                            @if(!empty($candidate->exp2_organization) || !empty($candidate->exp2_position))
                            <div class="experience-row exp-block border rounded p-3 mb-3" data-row="2">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <strong class="text-muted" style="font-size:.9rem;">Experience #<span class="row-number">2</span></strong>
                                    <button type="button" class="btn btn-sm btn-outline-danger remove-exp-row">
                                        <i class="bi bi-trash"></i> Remove
                                    </button>
                                </div>
                                <div class="row g-2">
                                    <div class="col-md-4"><label class="form-label small">{{ __('candidate.organization') }}</label><input type="text" name="exp2_organization" class="form-control form-control-sm" value="{{ old('exp2_organization', $candidate->exp2_organization ?? '') }}"></div>
                                    <div class="col-md-4"><label class="form-label small">{{ __('candidate.position') }}</label><input type="text" name="exp2_position" class="form-control form-control-sm" value="{{ old('exp2_position', $candidate->exp2_position ?? '') }}"></div>
                                    <div class="col-md-4"><label class="form-label small">{{ __('candidate.start_date_bs') }}</label><input type="text" name="exp2_start_date_bs" class="form-control form-control-sm exp-nepali-date" placeholder="YYYY-MM-DD" data-target="exp2_start_date" autocomplete="off" value="{{ old('exp2_start_date_bs', $candidate->exp2_start_date_bs ?? '') }}"><input type="hidden" name="exp2_start_date" value="{{ $candidate->exp2_start_date ?? '' }}"></div>
                                    <div class="col-md-4"><label class="form-label small">{{ __('candidate.end_date_bs') }}</label><input type="text" name="exp2_end_date_bs" class="form-control form-control-sm exp-nepali-date" placeholder="YYYY-MM-DD" data-target="exp2_end_date" autocomplete="off" value="{{ old('exp2_end_date_bs', $candidate->exp2_end_date_bs ?? '') }}"><input type="hidden" name="exp2_end_date" value="{{ $candidate->exp2_end_date ?? '' }}"></div>
                                    <div class="col-md-2"><label class="form-label small">{{ __('candidate.years') }}</label><input type="number" step="0.5" name="exp2_years" class="form-control form-control-sm" value="{{ old('exp2_years', $candidate->exp2_years ?? '') }}"></div>
                                    <div class="col-md-6"><label class="form-label small">{{ __('candidate.document') }}</label>@if(!empty($candidate->exp2_document))<div class="mb-1">{!! showDoc($candidate->exp2_document) !!}</div>@endif<input type="file" name="exp2_document" class="form-control form-control-sm" accept="image/*,application/pdf"></div>
                                </div>
                            </div>
                            @endif

                            {{-- Row 3 --}}
                            @if(!empty($candidate->exp3_organization) || !empty($candidate->exp3_position))
                            <div class="experience-row exp-block border rounded p-3 mb-3" data-row="3">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <strong class="text-muted" style="font-size:.9rem;">Experience #<span class="row-number">3</span></strong>
                                    <button type="button" class="btn btn-sm btn-outline-danger remove-exp-row">
                                        <i class="bi bi-trash"></i> Remove
                                    </button>
                                </div>
                                <div class="row g-2">
                                    <div class="col-md-4"><label class="form-label small">{{ __('candidate.organization') }}</label><input type="text" name="exp3_organization" class="form-control form-control-sm" value="{{ old('exp3_organization', $candidate->exp3_organization ?? '') }}"></div>
                                    <div class="col-md-4"><label class="form-label small">{{ __('candidate.position') }}</label><input type="text" name="exp3_position" class="form-control form-control-sm" value="{{ old('exp3_position', $candidate->exp3_position ?? '') }}"></div>
                                    <div class="col-md-4"><label class="form-label small">{{ __('candidate.start_date_bs') }}</label><input type="text" name="exp3_start_date_bs" class="form-control form-control-sm exp-nepali-date" placeholder="YYYY-MM-DD" data-target="exp3_start_date" autocomplete="off" value="{{ old('exp3_start_date_bs', $candidate->exp3_start_date_bs ?? '') }}"><input type="hidden" name="exp3_start_date" value="{{ $candidate->exp3_start_date ?? '' }}"></div>
                                    <div class="col-md-4"><label class="form-label small">{{ __('candidate.end_date_bs') }}</label><input type="text" name="exp3_end_date_bs" class="form-control form-control-sm exp-nepali-date" placeholder="YYYY-MM-DD" data-target="exp3_end_date" autocomplete="off" value="{{ old('exp3_end_date_bs', $candidate->exp3_end_date_bs ?? '') }}"><input type="hidden" name="exp3_end_date" value="{{ $candidate->exp3_end_date ?? '' }}"></div>
                                    <div class="col-md-2"><label class="form-label small">{{ __('candidate.years') }}</label><input type="number" step="0.5" name="exp3_years" class="form-control form-control-sm" value="{{ old('exp3_years', $candidate->exp3_years ?? '') }}"></div>
                                    <div class="col-md-6"><label class="form-label small">{{ __('candidate.document') }}</label>@if(!empty($candidate->exp3_document))<div class="mb-1">{!! showDoc($candidate->exp3_document) !!}</div>@endif<input type="file" name="exp3_document" class="form-control form-control-sm" accept="image/*,application/pdf"></div>
                                </div>
                            </div>
                            @endif

                            {{-- Row 4 --}}
                            @if(!empty($candidate->exp4_organization) || !empty($candidate->exp4_position))
                            <div class="experience-row exp-block border rounded p-3 mb-3" data-row="4">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <strong class="text-muted" style="font-size:.9rem;">Experience #<span class="row-number">4</span></strong>
                                    <button type="button" class="btn btn-sm btn-outline-danger remove-exp-row">
                                        <i class="bi bi-trash"></i> Remove
                                    </button>
                                </div>
                                <div class="row g-2">
                                    <div class="col-md-4"><label class="form-label small">{{ __('candidate.organization') }}</label><input type="text" name="exp4_organization" class="form-control form-control-sm" value="{{ old('exp4_organization', $candidate->exp4_organization ?? '') }}"></div>
                                    <div class="col-md-4"><label class="form-label small">{{ __('candidate.position') }}</label><input type="text" name="exp4_position" class="form-control form-control-sm" value="{{ old('exp4_position', $candidate->exp4_position ?? '') }}"></div>
                                    <div class="col-md-4"><label class="form-label small">{{ __('candidate.start_date_bs') }}</label><input type="text" name="exp4_start_date_bs" class="form-control form-control-sm exp-nepali-date" placeholder="YYYY-MM-DD" data-target="exp4_start_date" autocomplete="off" value="{{ old('exp4_start_date_bs', $candidate->exp4_start_date_bs ?? '') }}"><input type="hidden" name="exp4_start_date" value="{{ $candidate->exp4_start_date ?? '' }}"></div>
                                    <div class="col-md-4"><label class="form-label small">{{ __('candidate.end_date_bs') }}</label><input type="text" name="exp4_end_date_bs" class="form-control form-control-sm exp-nepali-date" placeholder="YYYY-MM-DD" data-target="exp4_end_date" autocomplete="off" value="{{ old('exp4_end_date_bs', $candidate->exp4_end_date_bs ?? '') }}"><input type="hidden" name="exp4_end_date" value="{{ $candidate->exp4_end_date ?? '' }}"></div>
                                    <div class="col-md-2"><label class="form-label small">{{ __('candidate.years') }}</label><input type="number" step="0.5" name="exp4_years" class="form-control form-control-sm" value="{{ old('exp4_years', $candidate->exp4_years ?? '') }}"></div>
                                    <div class="col-md-6"><label class="form-label small">{{ __('candidate.document') }}</label>@if(!empty($candidate->exp4_document))<div class="mb-1">{!! showDoc($candidate->exp4_document) !!}</div>@endif<input type="file" name="exp4_document" class="form-control form-control-sm" accept="image/*,application/pdf"></div>
                                </div>
                            </div>
                            @endif

                            {{-- Row 5 --}}
                            @if(!empty($candidate->exp2_organization) || !empty($candidate->exp5_position))
                            <div class="experience-row exp-block border rounded p-3 mb-3" data-row="2">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <strong class="text-muted" style="font-size:.9rem;">Experience #<span class="row-number">5</span></strong>
                                    <button type="button" class="btn btn-sm btn-outline-danger remove-exp-row">
                                        <i class="bi bi-trash"></i> Remove
                                    </button>
                                </div>
                                <div class="row g-2">
                                    <div class="col-md-4"><label class="form-label small">{{ __('candidate.organization') }}</label><input type="text" name="exp5_organization" class="form-control form-control-sm" value="{{ old('exp5_organization', $candidate->exp5_organization ?? '') }}"></div>
                                    <div class="col-md-4"><label class="form-label small">{{ __('candidate.position') }}</label><input type="text" name="exp5_position" class="form-control form-control-sm" value="{{ old('exp5_position', $candidate->exp5_position ?? '') }}"></div>
                                    <div class="col-md-4"><label class="form-label small">{{ __('candidate.start_date_bs') }}</label><input type="text" name="exp5_start_date_bs" class="form-control form-control-sm exp-nepali-date" placeholder="YYYY-MM-DD" data-target="exp5_start_date" autocomplete="off" value="{{ old('exp5_start_date_bs', $candidate->exp5_start_date_bs ?? '') }}"><input type="hidden" name="exp5_start_date" value="{{ $candidate->exp5_start_date ?? '' }}"></div>
                                    <div class="col-md-4"><label class="form-label small">{{ __('candidate.end_date_bs') }}</label><input type="text" name="exp5_end_date_bs" class="form-control form-control-sm exp-nepali-date" placeholder="YYYY-MM-DD" data-target="exp5_end_date" autocomplete="off" value="{{ old('exp5_end_date_bs', $candidate->exp5_end_date_bs ?? '') }}"><input type="hidden" name="exp5_end_date" value="{{ $candidate->exp5_end_date ?? '' }}"></div>
                                    <div class="col-md-2"><label class="form-label small">{{ __('candidate.years') }}</label><input type="number" step="0.5" name="exp5_years" class="form-control form-control-sm" value="{{ old('exp5_years', $candidate->exp5_years ?? '') }}"></div>
                                    <div class="col-md-6"><label class="form-label small">{{ __('candidate.document') }}</label>@if(!empty($candidate->exp5_document))<div class="mb-1">{!! showDoc($candidate->exp5_document) !!}</div>@endif<input type="file" name="exp5_document" class="form-control form-control-sm" accept="image/*,application/pdf"></div>
                                </div>
                            </div>
                            @endif

                            {{-- Row 6 --}}
                            @if(!empty($candidate->exp6_organization) || !empty($candidate->exp6_position))
                            <div class="experience-row exp-block border rounded p-3 mb-3" data-row="2">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <strong class="text-muted" style="font-size:.9rem;">Experience #<span class="row-number">6</span></strong>
                                    <button type="button" class="btn btn-sm btn-outline-danger remove-exp-row">
                                        <i class="bi bi-trash"></i> Remove
                                    </button>
                                </div>
                                <div class="row g-2">
                                    <div class="col-md-4"><label class="form-label small">{{ __('candidate.organization') }}</label><input type="text" name="exp6_organization" class="form-control form-control-sm" value="{{ old('exp6_organization', $candidate->exp6_organization ?? '') }}"></div>
                                    <div class="col-md-4"><label class="form-label small">{{ __('candidate.position') }}</label><input type="text" name="exp6_position" class="form-control form-control-sm" value="{{ old('exp6_position', $candidate->exp6_position ?? '') }}"></div>
                                    <div class="col-md-4"><label class="form-label small">{{ __('candidate.start_date_bs') }}</label><input type="text" name="exp6_start_date_bs" class="form-control form-control-sm exp-nepali-date" placeholder="YYYY-MM-DD" data-target="exp6_start_date" autocomplete="off" value="{{ old('exp6_start_date_bs', $candidate->exp6_start_date_bs ?? '') }}"><input type="hidden" name="exp6_start_date" value="{{ $candidate->exp6_start_date ?? '' }}"></div>
                                    <div class="col-md-4"><label class="form-label small">{{ __('candidate.end_date_bs') }}</label><input type="text" name="exp6_end_date_bs" class="form-control form-control-sm exp-nepali-date" placeholder="YYYY-MM-DD" data-target="exp6_end_date" autocomplete="off" value="{{ old('exp6_end_date_bs', $candidate->exp6_end_date_bs ?? '') }}"><input type="hidden" name="exp6_end_date" value="{{ $candidate->exp6_end_date ?? '' }}"></div>
                                    <div class="col-md-2"><label class="form-label small">{{ __('candidate.years') }}</label><input type="number" step="0.5" name="exp6_years" class="form-control form-control-sm" value="{{ old('exp6_years', $candidate->exp6_years ?? '') }}"></div>
                                    <div class="col-md-6"><label class="form-label small">{{ __('candidate.document') }}</label>@if(!empty($candidate->exp6_document))<div class="mb-1">{!! showDoc($candidate->exp6_document) !!}</div>@endif<input type="file" name="exp6_document" class="form-control form-control-sm" accept="image/*,application/pdf"></div>
                                </div>
                            </div>
                            @endif

                            {{-- Row 7 --}}
                            @if(!empty($candidate->exp7_organization) || !empty($candidate->exp7_position))
                            <div class="experience-row exp-block border rounded p-3 mb-3" data-row="2">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <strong class="text-muted" style="font-size:.9rem;">Experience #<span class="row-number">7</span></strong>
                                    <button type="button" class="btn btn-sm btn-outline-danger remove-exp-row">
                                        <i class="bi bi-trash"></i> Remove
                                    </button>
                                </div>
                                <div class="row g-2">
                                    <div class="col-md-4"><label class="form-label small">{{ __('candidate.organization') }}</label><input type="text" name="exp7_organization" class="form-control form-control-sm" value="{{ old('exp7_organization', $candidate->exp7_organization ?? '') }}"></div>
                                    <div class="col-md-4"><label class="form-label small">{{ __('candidate.position') }}</label><input type="text" name="exp7_position" class="form-control form-control-sm" value="{{ old('exp7_position', $candidate->exp7_position ?? '') }}"></div>
                                    <div class="col-md-4"><label class="form-label small">{{ __('candidate.start_date_bs') }}</label><input type="text" name="exp7_start_date_bs" class="form-control form-control-sm exp-nepali-date" placeholder="YYYY-MM-DD" data-target="exp7_start_date" autocomplete="off" value="{{ old('exp7_start_date_bs', $candidate->exp7_start_date_bs ?? '') }}"><input type="hidden" name="exp7_start_date" value="{{ $candidate->exp7_start_date ?? '' }}"></div>
                                    <div class="col-md-4"><label class="form-label small">{{ __('candidate.end_date_bs') }}</label><input type="text" name="exp7_end_date_bs" class="form-control form-control-sm exp-nepali-date" placeholder="YYYY-MM-DD" data-target="exp7_end_date" autocomplete="off" value="{{ old('exp7_end_date_bs', $candidate->exp7_end_date_bs ?? '') }}"><input type="hidden" name="exp7_end_date" value="{{ $candidate->exp7_end_date ?? '' }}"></div>
                                    <div class="col-md-2"><label class="form-label small">{{ __('candidate.years') }}</label><input type="number" step="0.5" name="exp7_years" class="form-control form-control-sm" value="{{ old('exp7_years', $candidate->exp7_years ?? '') }}"></div>
                                    <div class="col-md-6"><label class="form-label small">{{ __('candidate.document') }}</label>@if(!empty($candidate->exp7_document))<div class="mb-1">{!! showDoc($candidate->exp7_document) !!}</div>@endif<input type="file" name="exp7_document" class="form-control form-control-sm" accept="image/*,application/pdf"></div>
                                </div>
                            </div>
                            @endif

                            {{-- Row 8 --}}
                            @if(!empty($candidate->exp8_organization) || !empty($candidate->exp8_position))
                            <div class="experience-row exp-block border rounded p-3 mb-3" data-row="2">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <strong class="text-muted" style="font-size:.9rem;">Experience #<span class="row-number">8</span></strong>
                                    <button type="button" class="btn btn-sm btn-outline-danger remove-exp-row">
                                        <i class="bi bi-trash"></i> Remove
                                    </button>
                                </div>
                                <div class="row g-2">
                                    <div class="col-md-4"><label class="form-label small">{{ __('candidate.organization') }}</label><input type="text" name="exp8_organization" class="form-control form-control-sm" value="{{ old('exp8_organization', $candidate->exp8_organization ?? '') }}"></div>
                                    <div class="col-md-4"><label class="form-label small">{{ __('candidate.position') }}</label><input type="text" name="exp8_position" class="form-control form-control-sm" value="{{ old('exp8_position', $candidate->exp8_position ?? '') }}"></div>
                                    <div class="col-md-4"><label class="form-label small">{{ __('candidate.start_date_bs') }}</label><input type="text" name="exp8_start_date_bs" class="form-control form-control-sm exp-nepali-date" placeholder="YYYY-MM-DD" data-target="exp8_start_date" autocomplete="off" value="{{ old('exp8_start_date_bs', $candidate->exp8_start_date_bs ?? '') }}"><input type="hidden" name="exp8_start_date" value="{{ $candidate->exp8_start_date ?? '' }}"></div>
                                    <div class="col-md-4"><label class="form-label small">{{ __('candidate.end_date_bs') }}</label><input type="text" name="exp8_end_date_bs" class="form-control form-control-sm exp-nepali-date" placeholder="YYYY-MM-DD" data-target="exp8_end_date" autocomplete="off" value="{{ old('exp8_end_date_bs', $candidate->exp8_end_date_bs ?? '') }}"><input type="hidden" name="exp8_end_date" value="{{ $candidate->exp8_end_date ?? '' }}"></div>
                                    <div class="col-md-2"><label class="form-label small">{{ __('candidate.years') }}</label><input type="number" step="0.5" name="exp8_years" class="form-control form-control-sm" value="{{ old('exp8_years', $candidate->exp8_years ?? '') }}"></div>
                                    <div class="col-md-6"><label class="form-label small">{{ __('candidate.document') }}</label>@if(!empty($candidate->exp8_document))<div class="mb-1">{!! showDoc($candidate->exp8_document) !!}</div>@endif<input type="file" name="exp8_document" class="form-control form-control-sm" accept="image/*,application/pdf"></div>
                                </div>
                            </div>
                            @endif

                            {{-- Row 9 --}}
                            @if(!empty($candidate->exp9_organization) || !empty($candidate->exp9_position))
                            <div class="experience-row exp-block border rounded p-3 mb-3" data-row="2">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <strong class="text-muted" style="font-size:.9rem;">Experience #<span class="row-number">9</span></strong>
                                    <button type="button" class="btn btn-sm btn-outline-danger remove-exp-row">
                                        <i class="bi bi-trash"></i> Remove
                                    </button>
                                </div>
                                <div class="row g-2">
                                    <div class="col-md-4"><label class="form-label small">{{ __('candidate.organization') }}</label><input type="text" name="exp9_organization" class="form-control form-control-sm" value="{{ old('exp9_organization', $candidate->exp9_organization ?? '') }}"></div>
                                    <div class="col-md-4"><label class="form-label small">{{ __('candidate.position') }}</label><input type="text" name="exp9_position" class="form-control form-control-sm" value="{{ old('exp9_position', $candidate->exp9_position ?? '') }}"></div>
                                    <div class="col-md-4"><label class="form-label small">{{ __('candidate.start_date_bs') }}</label><input type="text" name="exp9_start_date_bs" class="form-control form-control-sm exp-nepali-date" placeholder="YYYY-MM-DD" data-target="exp9_start_date" autocomplete="off" value="{{ old('exp9_start_date_bs', $candidate->exp9_start_date_bs ?? '') }}"><input type="hidden" name="exp9_start_date" value="{{ $candidate->exp9_start_date ?? '' }}"></div>
                                    <div class="col-md-4"><label class="form-label small">{{ __('candidate.end_date_bs') }}</label><input type="text" name="exp9_end_date_bs" class="form-control form-control-sm exp-nepali-date" placeholder="YYYY-MM-DD" data-target="exp9_end_date" autocomplete="off" value="{{ old('exp9_end_date_bs', $candidate->exp9_end_date_bs ?? '') }}"><input type="hidden" name="exp9_end_date" value="{{ $candidate->exp9_end_date ?? '' }}"></div>
                                    <div class="col-md-2"><label class="form-label small">{{ __('candidate.years') }}</label><input type="number" step="0.5" name="exp9_years" class="form-control form-control-sm" value="{{ old('exp9_years', $candidate->exp9_years ?? '') }}"></div>
                                    <div class="col-md-6"><label class="form-label small">{{ __('candidate.document') }}</label>@if(!empty($candidate->exp9_document))<div class="mb-1">{!! showDoc($candidate->exp9_document) !!}</div>@endif<input type="file" name="exp9_document" class="form-control form-control-sm" accept="image/*,application/pdf"></div>
                                </div>
                            </div>
                            @endif

                            {{-- Row 10 --}}
                            @if(!empty($candidate->exp10_organization) || !empty($candidate->exp10_position))
                            <div class="experience-row exp-block border rounded p-3 mb-3" data-row="2">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <strong class="text-muted" style="font-size:.9rem;">Experience #<span class="row-number">10</span></strong>
                                    <button type="button" class="btn btn-sm btn-outline-danger remove-exp-row">
                                        <i class="bi bi-trash"></i> Remove
                                    </button>
                                </div>
                                <div class="row g-2">
                                    <div class="col-md-4"><label class="form-label small">{{ __('candidate.organization') }}</label><input type="text" name="exp10_organization" class="form-control form-control-sm" value="{{ old('exp10_organization', $candidate->exp10_organization ?? '') }}"></div>
                                    <div class="col-md-4"><label class="form-label small">{{ __('candidate.position') }}</label><input type="text" name="exp10_position" class="form-control form-control-sm" value="{{ old('exp10_position', $candidate->exp10_position ?? '') }}"></div>
                                    <div class="col-md-4"><label class="form-label small">{{ __('candidate.start_date_bs') }}</label><input type="text" name="exp10_start_date_bs" class="form-control form-control-sm exp-nepali-date" placeholder="YYYY-MM-DD" data-target="exp10_start_date" autocomplete="off" value="{{ old('exp10_start_date_bs', $candidate->exp10_start_date_bs ?? '') }}"><input type="hidden" name="exp10_start_date" value="{{ $candidate->exp10_start_date ?? '' }}"></div>
                                    <div class="col-md-4"><label class="form-label small">{{ __('candidate.end_date_bs') }}</label><input type="text" name="exp10_end_date_bs" class="form-control form-control-sm exp-nepali-date" placeholder="YYYY-MM-DD" data-target="exp10_end_date" autocomplete="off" value="{{ old('exp10_end_date_bs', $candidate->exp10_end_date_bs ?? '') }}"><input type="hidden" name="exp10_end_date" value="{{ $candidate->exp10_end_date ?? '' }}"></div>
                                    <div class="col-md-2"><label class="form-label small">{{ __('candidate.years') }}</label><input type="number" step="0.5" name="exp10_years" class="form-control form-control-sm" value="{{ old('exp10_years', $candidate->exp10_years ?? '') }}"></div>
                                    <div class="col-md-6"><label class="form-label small">{{ __('candidate.document') }}</label>@if(!empty($candidate->exp10_document))<div class="mb-1">{!! showDoc($candidate->exp10_document) !!}</div>@endif<input type="file" name="exp10_document" class="form-control form-control-sm" accept="image/*,application/pdf"></div>
                                </div>
                            </div>
                            @endif
                        </div>

                        <div class="d-flex align-items-center gap-3 mt-2 mb-3">
                            <button type="button" id="addExpRow" class="btn btn-sm btn-outline-dark">
                                <i class="bi bi-plus-circle"></i> Add Experience
                            </button>
                            <span class="text-muted small" id="expRowCount">— / 10 entries</span>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between mt-3">
                        <button type="button" class="btn btn-secondary prev-btn">Back</button>
                        <button type="button" class="btn btn-light next-btn">Next</button>
                    </div>
                </div>

                {{-- STEP 6: Documents --}}
                <div class="step d-none" id="step6">
                    <h5 class="mb-4 text-dark">Step 6 - {{ __('candidate.uploaded_documents') }}</h5>
                    <p class="text-muted mb-4">{{ __('candidate.leave_blank_keep_existing_file') }}</p>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">{{ __('candidate.passport_size_photo') }} <span class="text-danger">*</span></label>
                            @if(!empty($candidate->passport_size_photo))
                                <div class="mb-2">{!! showDoc($candidate->passport_size_photo) !!}</div>
                            @endif
                            <input type="file" name="passport_size_photo" id="passport_size_photo" class="form-control @error('passport_size_photo') is-invalid @enderror"
                                accept="image/*,application/pdf"
                                {{ !empty($candidate->passport_size_photo) ? 'data-existing-file="1"' : 'required' }}>
                            <small class="text-muted d-block">Max Size: 700KB</small>
                            @error('passport_size_photo')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">{{ __('candidate.citizenship_id_document') }} <span class="text-danger">*</span></label>
                            @if(!empty($candidate->citizenship_id_document))
                                <div class="mb-2">{!! showDoc($candidate->citizenship_id_document) !!}</div>
                            @endif
                            <input type="file" name="citizenship_id_document" id="citizenship_id_document" class="form-control @error('citizenship_id_document') is-invalid @enderror"
                                accept="image/*,application/pdf"
                                {{ !empty($candidate->citizenship_id_document) ? 'data-existing-file="1"' : 'required' }}>
                            <small class="text-muted d-block">{{ __('candidate.max_size_700kb') }}</small>
                            @error('citizenship_id_document')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">{{ __('candidate.signature') }} <span class="text-danger">*</span></label>
                            @if(!empty($candidate->signature))
                                <div class="mb-2">{!! showDoc($candidate->signature) !!}</div>
                            @endif
                            <input type="file" name="signature" id="signature" class="form-control @error('signature') is-invalid @enderror"
                                accept="image/*,application/pdf"
                                {{ !empty($candidate->signature) ? 'data-existing-file="1"' : 'required' }}>
                            <small class="text-muted d-block">Max Size: 700KB</small>
                            @error('signature')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    <div class="form-check mb-4">
                        <input type="checkbox" class="form-check-input" id="terms_agree" name="terms_agree" required>
                        <label class="form-check-label" for="terms_agree">
                            I hereby declare that all information provided is true and correct. <span class="text-danger">*</span>
                        </label>
                    </div>


                    <div class="d-flex justify-content-between align-items-center">
                        <button type="button" class="btn btn-secondary prev-btn">Back</button>
                        <div class="d-flex gap-2">
                            <a href="{{ route('candidate.my-profile') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-x-circle me-1"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-danger px-4">
                                <i class="bi bi-check-circle me-1"></i> Save Profile
                            </button>
                        </div>
                    </div>
                </div>

                <!-- {{-- STEP 7: Review & Submit --}}
                <div class="step d-none" id="step7">
                    <h5 class="mb-4 text-dark">Step 7 — Review & Save</h5>
                    <div class="alert alert-info">Please review your details before saving.</div>
                    

                    <div class="form-check mb-4">
                        <input type="checkbox" class="form-check-input" id="terms_agree" name="terms_agree" required>
                        <label class="form-check-label" for="terms_agree">
                            I hereby declare that all information provided is true and correct. <span class="text-danger">*</span>
                        </label>
                    </div>

                    <div class="d-flex justify-content-between align-items-center">
                        <button type="button" class="btn btn-secondary prev-btn">Back</button>
                        <div class="d-flex gap-2">
                            <a href="{{ route('candidate.my-profile') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-x-circle me-1"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-danger px-4">
                                <i class="bi bi-check-circle me-1"></i> Save Profile
                            </button>
                        </div>
                    </div>
                </div> -->

            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://nepalidatepicker.sajanmaharjan.com.np/v5/nepali.datepicker/js/nepali.datepicker.v5.0.6.min.js"></script>
<script>
(function () {
    'use strict';
    const bsMonthData = {1975:[31,31,32,32,31,30,30,29,30,29,30,30],1976:[31,32,31,32,31,30,30,30,29,29,30,31],1977:[30,32,31,32,31,30,30,30,29,30,29,31],1978:[31,31,32,31,31,31,30,29,30,29,30,30],1979:[31,31,32,32,31,30,30,29,30,29,30,30],1980:[31,32,31,32,31,30,30,30,29,29,30,31],1981:[31,31,31,32,31,31,29,30,30,29,30,30],1982:[31,31,32,31,31,31,30,29,30,29,30,30],1983:[31,31,32,32,31,30,30,29,30,29,30,30],1984:[31,32,31,32,31,30,30,30,29,29,30,31],1985:[31,31,31,32,31,31,29,30,30,29,30,30],1986:[31,31,32,31,31,31,30,29,30,29,30,30],1987:[31,32,31,32,31,30,30,29,30,29,30,30],1988:[31,32,31,32,31,30,30,30,29,29,30,31],1989:[31,31,31,32,31,31,30,29,30,29,30,30],1990:[31,31,32,31,31,31,30,29,30,29,30,30],1991:[31,32,31,32,31,30,30,29,30,29,30,30],1992:[31,32,31,32,31,30,30,30,29,30,29,31],1993:[31,31,31,32,31,31,30,29,30,29,30,30],1994:[31,31,32,31,31,31,30,29,30,29,30,30],1995:[31,32,31,32,31,30,30,30,29,29,30,30],1996:[31,32,31,32,31,30,30,30,29,30,29,31],1997:[31,31,32,31,31,31,30,29,30,29,30,30],1998:[31,31,32,31,31,31,30,29,30,29,30,30],1999:[31,32,31,32,31,30,30,30,29,29,30,31],2000:[30,32,31,32,31,30,30,30,29,30,29,31],2001:[31,31,32,31,31,31,30,29,30,29,30,30],2002:[31,31,32,32,31,30,30,29,30,29,30,30],2003:[31,32,31,32,31,30,30,30,29,29,30,31],2004:[30,32,31,32,31,30,30,30,29,30,29,31],2005:[31,31,32,31,31,31,30,29,30,29,30,30],2006:[31,31,32,32,31,30,30,29,30,29,30,30],2007:[31,32,31,32,31,30,30,30,29,29,30,31],2008:[31,31,31,32,31,31,29,30,30,29,29,31],2009:[31,31,32,31,31,31,30,29,30,29,30,30],2010:[31,31,32,32,31,30,30,29,30,29,30,30],2011:[31,32,31,32,31,30,30,30,29,29,30,31],2012:[31,31,31,32,31,31,29,30,30,29,30,30],2013:[31,31,32,31,31,31,30,29,30,29,30,30],2014:[31,31,32,32,31,30,30,29,30,29,30,30],2015:[31,32,31,32,31,30,30,30,29,29,30,31],2016:[31,31,31,32,31,31,29,30,30,29,30,30],2017:[31,31,32,31,31,31,30,29,30,29,30,30],2018:[31,32,31,32,31,30,30,29,30,29,30,30],2019:[31,32,31,32,31,30,30,30,29,30,29,31],2020:[31,31,31,32,31,31,30,29,30,29,30,30],2021:[31,31,32,31,31,31,30,29,30,29,30,30],2022:[31,32,31,32,31,30,30,30,29,29,30,30],2023:[31,32,31,32,31,30,30,30,29,30,29,31],2024:[31,31,31,32,31,31,30,29,30,29,30,30],2025:[31,31,32,31,31,31,30,29,30,29,30,30],2026:[31,32,31,32,31,30,30,30,29,29,30,31],2027:[30,32,31,32,31,30,30,30,29,30,29,31],2028:[31,31,32,31,31,31,30,29,30,29,30,30],2029:[31,31,32,31,32,30,30,29,30,29,30,30],2030:[31,32,31,32,31,30,30,30,29,29,30,31],2031:[30,32,31,32,31,30,30,30,29,30,29,31],2032:[31,31,32,31,31,31,30,29,30,29,30,30],2033:[31,31,32,32,31,30,30,29,30,29,30,30],2034:[31,32,31,32,31,30,30,30,29,29,30,31],2035:[30,32,31,32,31,31,29,30,30,29,29,31],2036:[31,31,32,31,31,31,30,29,30,29,30,30],2037:[31,31,32,32,31,30,30,29,30,29,30,30],2038:[31,32,31,32,31,30,30,30,29,29,30,31],2039:[31,31,31,32,31,31,29,30,30,29,30,30],2040:[31,31,32,31,31,31,30,29,30,29,30,30],2041:[31,31,32,32,31,30,30,29,30,29,30,30],2042:[31,32,31,32,31,30,30,30,29,29,30,31],2043:[31,31,31,32,31,31,29,30,30,29,30,30],2044:[31,31,32,31,31,31,30,29,30,29,30,30],2045:[31,32,31,32,31,30,30,29,30,29,30,30],2046:[31,32,31,32,31,30,30,30,29,29,30,31],2047:[31,31,31,32,31,31,30,29,30,29,30,30],2048:[31,31,32,31,31,31,30,29,30,29,30,30],2049:[31,32,31,32,31,30,30,30,29,29,30,30],2050:[31,32,31,32,31,30,30,30,29,30,29,31],2051:[31,31,31,32,31,31,30,29,30,29,30,30],2052:[31,31,32,31,31,31,30,29,30,29,30,30],2053:[31,32,31,32,31,30,30,30,29,29,30,30],2054:[31,32,31,32,31,30,30,30,29,30,29,31],2055:[31,31,32,31,31,31,30,29,30,29,30,30],2056:[31,31,32,31,32,30,30,29,30,29,30,30],2057:[31,32,31,32,31,30,30,30,29,29,30,31],2058:[30,32,31,32,31,30,30,30,29,30,29,31],2059:[31,31,32,31,31,31,30,29,30,29,30,30],2060:[31,31,32,32,31,30,30,29,30,29,30,30],2061:[31,32,31,32,31,30,30,30,29,29,30,31],2062:[30,32,31,32,31,31,29,30,29,30,29,31],2063:[31,31,32,31,31,31,30,29,30,29,30,30],2064:[31,31,32,32,31,30,30,29,30,29,30,30],2065:[31,32,31,32,31,30,30,30,29,29,30,31],2066:[31,31,31,32,31,31,29,30,30,29,29,31],2067:[31,31,32,31,31,31,30,29,30,29,30,30],2068:[31,31,32,32,31,30,30,29,30,29,30,30],2069:[31,32,31,32,31,30,30,30,29,29,30,31],2070:[31,31,31,32,31,31,29,30,30,29,30,30],2071:[31,31,32,31,31,31,30,29,30,29,30,30],2072:[31,32,31,32,31,30,30,29,30,29,30,30],2073:[31,32,31,32,31,30,30,30,29,29,30,31],2074:[31,31,31,32,31,31,30,29,30,29,30,30],2075:[31,31,32,31,31,31,30,29,30,29,30,30],2076:[31,32,31,32,31,30,30,30,29,29,30,30],2077:[31,32,31,32,31,30,30,30,29,30,29,31],2078:[31,31,31,32,31,31,30,29,30,29,30,30],2079:[31,31,32,31,31,31,30,29,30,29,30,30],2080:[31,32,31,32,31,30,30,30,29,29,30,30],2081:[31,32,31,32,31,30,30,30,29,30,29,30],2082:[31,31,31,32,31,31,30,29,30,29,30,31],2083:[31,31,32,31,31,31,30,29,30,29,30,30],2084:[31,32,31,32,31,30,30,30,29,29,30,31],2085:[30,32,31,32,31,30,30,30,29,30,29,31],2086:[31,31,32,31,31,31,30,29,30,29,30,30],2087:[31,31,32,32,31,30,30,29,30,29,30,30],2088:[31,32,31,32,31,30,30,30,29,29,30,31],2089:[30,32,31,32,31,31,29,30,29,30,29,31],2090:[31,31,32,31,31,31,30,29,30,29,30,30],2091:[31,31,32,32,31,30,30,29,30,29,30,30],2092:[31,32,31,32,31,30,30,30,29,29,30,31],2093:[31,31,31,32,31,31,29,30,30,29,29,31],2094:[31,31,32,31,31,31,30,29,30,29,30,30],2095:[31,31,32,32,31,30,30,29,30,29,30,30],2096:[31,32,31,32,31,30,30,30,29,29,30,31],2097:[30,32,31,32,31,31,29,30,30,29,29,31],2098:[31,31,32,31,31,31,30,29,30,29,30,30],2099:[31,31,32,32,31,30,30,29,30,29,30,30]};
    const adRef = new Date(1943, 3, 14);
    function daysInYear(y)  { return bsMonthData[y] ? bsMonthData[y].reduce((s,d)=>s+d,0) : 365; }
    function daysInMonth(y,m){ return (bsMonthData[y]||[])[m-1]||30; }
    window.bsToAD = function(str) {
        try { const [y,m,d] = str.split('-').map(Number); if (!y||!m||!d) return ''; let t=0; for(let i=2000;i<y;i++) t+=daysInYear(i); for(let i=1;i<m;i++) t+=daysInMonth(y,i); t+=(d-1); const ad=new Date(adRef); ad.setDate(ad.getDate()+t); return ad.getFullYear()+'-'+String(ad.getMonth()+1).padStart(2,'0')+'-'+String(ad.getDate()).padStart(2,'0'); } catch(e){ return ''; }
    };
    window.formatADDisplay = function(s) { if(!s) return ''; const months=['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec']; const d=new Date(s); if(isNaN(d.getTime())) return s; return d.getFullYear()+'-'+months[d.getMonth()]+'-'+('0'+d.getDate()).slice(-2); };
})();
</script>
<script>
const NEPAL_DATA = {Koshi:{Bhojpur:["Bhojpur Municipality","Shadananda Municipality","Hatuwagadhi Rural Municipality","Arun Rural Municipality","Tyamke Maiyum Rural Municipality","Ramprasad Rai Rural Municipality","Pauwadungma Rural Municipality","Salpasilichho Rural Municipality"],Dhankuta:["Dhankuta Municipality","Pakhribas Municipality","Mahalaxmi Municipality","Chhathar Jorpati Rural Municipality","Sangurigadhi Rural Municipality","Sahidbhumi Rural Municipality","Khalsa Rural Municipality"],Ilam:["Ilam Municipality","Deumai Municipality","Mai Municipality","Suryodaya Municipality","Maijogmai Rural Municipality","Sandakpur Rural Municipality","Chulachuli Rural Municipality","Mangsebung Rural Municipality","Rong Rural Municipality","Phakphokthum Rural Municipality"],Jhapa:["Arjundhara Municipality","Bhadrapur Municipality","Birtamod Municipality","Damak Municipality","Kankai Municipality","Mechinagar Municipality","Shivasataxi Municipality","Gauradaha Municipality","Haldibari Municipality","Buddhashanti Rural Municipality","Barhadashi Rural Municipality","Kabeli Rural Municipality","Kachankawal Rural Municipality","Gaurigunj Rural Municipality"],Khotang:["Diktel Rupakot Majhuwagadhi Municipality","Halesi Tuwachung Municipality","Khotehang Rural Municipality","Barahpokhari Rural Municipality","Kepilasgadhi Rural Municipality","Ainselukhark Rural Municipality","Lamidanda Rural Municipality","Sakela Rural Municipality","Rawabesi Rural Municipality","Diprung Chuichumma Rural Municipality"],Morang:["Biratnagar Metropolitan City","Rangeli Municipality","Sundarharaicha Municipality","Letang Municipality","Belbari Municipality","Pathari Shanischare Municipality","Ratuwamai Municipality","Jahada Rural Municipality","Budhiganga Rural Municipality","Gramthan Rural Municipality","Katahari Rural Municipality","Kerabari Rural Municipality","Miklajung Rural Municipality","Sunawarshi Rural Municipality","Uralabari Rural Municipality"],Okhaldhunga:["Siddhicharan Municipality","Molung Rural Municipality","Champadevi Rural Municipality","Chisankhugadhi Rural Municipality","Khijidemba Rural Municipality","Likhu Rural Municipality","Manebhanjyang Rural Municipality","Sunkoshi Rural Municipality"],Panchthar:["Phidim Municipality","Falgunanda Rural Municipality","Hilihang Rural Municipality","Kummayak Rural Municipality","Miklajung Rural Municipality","Phalelung Rural Municipality","Tumbewa Rural Municipality","Yashokchhap Rural Municipality"],Sankhuwasabha:["Chainpur Municipality","Dharmadevi Municipality","Khandbari Municipality","Madi Municipality","Panchkhapan Municipality","Chichila Rural Municipality","Makalu Rural Municipality","Sabhapokhari Rural Municipality","Silichong Rural Municipality"],Solukhumbu:["Solududhkunda Municipality","Salleri Municipality","Thulung Dudhkoshi Rural Municipality","Sotang Rural Municipality","Mahakulung Rural Municipality","Khumbu Pasanglhamu Rural Municipality","Likhupike Rural Municipality","Nechasalyan Rural Municipality"],Sunsari:["Dharan Sub-Metropolitan City","Itahari Sub-Metropolitan City","Inaruwa Municipality","Duhabi Municipality","Barahakshetra Municipality","Ramdhuni Municipality","Harinagara Rural Municipality","Koshi Rural Municipality","Gadhi Rural Municipality","Barju Rural Municipality"],Taplejung:["Phungling Municipality","Sidingba Rural Municipality","Aathrai Tribeni Rural Municipality","Meringden Rural Municipality","Mikwakhola Rural Municipality","Pathibhara Yangwarak Rural Municipality","Sirijangha Rural Municipality","Phaktanglung Rural Municipality"],Terhathum:["Myanglung Municipality","Laligurans Municipality","Aathrai Rural Municipality","Chhathar Rural Municipality","Phedap Rural Municipality"]},Madhesh:{Bara:["Kalaiya Sub-Metropolitan City","Jitpur Simara Sub-Metropolitan City","Nijgadh Municipality","Mahagadhimai Municipality","Simraungadh Municipality","Pacharauta Municipality","Prasauni Rural Municipality","Bishrampur Rural Municipality","Devtal Rural Municipality","Pheta Rural Municipality","Kaudena Rural Municipality","Adarshkotwal Rural Municipality","Suwarna Rural Municipality","Baragadhi Rural Municipality","Kolhabi Rural Municipality"],Dhanusha:["Janakpur Sub-Metropolitan City","Mithila Municipality","Dhanusha Municipality","Sabaila Municipality","Kamala Municipality","Mithila Bihari Municipality","Dhanushadham Municipality","Bideha Municipality","Aurahi Rural Municipality","Bateshwar Rural Municipality","Chhireshwarnath Rural Municipality","Dhanauji Rural Municipality","Ganeshman Charnath Rural Municipality","Hansapur Rural Municipality","Hans Rupa Rural Municipality","Janaknandini Rural Municipality","Lakshminiya Rural Municipality","Mukhiyapatti Musaharmiya Rural Municipality","Nagarain Rural Municipality","Shankarpur Rural Municipality"],Mahottari:["Jaleshwar Municipality","Gaushala Municipality","Matihani Municipality","Bardibas Municipality","Bhangaha Municipality","Loharpatti Municipality","Manra Siswa Municipality","Samsi Municipality","Sonama Rural Municipality","Ekdara Rural Municipality","Mahottari Rural Municipality","Pipra Rural Municipality","Ramgopalpur Rural Municipality"],Parsa:["Birgunj Metropolitan City","Bahudarmai Municipality","Parsagadhi Municipality","Pokhariya Municipality","Bindabasini Rural Municipality","Chhipaharmai Rural Municipality","Dhobini Rural Municipality","Jirabhawani Rural Municipality","Kalikamai Rural Municipality","Pakaha Mainpur Rural Municipality","Paterwas Rural Municipality","Paterwa Sugauli Rural Municipality","Sakhuwa Prasauni Rural Municipality","Thori Rural Municipality"],Rautahat:["Chandrapur Municipality","Gaur Municipality","Baudha Rural Municipality","Garuda Rural Municipality","Gujara Rural Municipality","Katahariya Rural Municipality","Madhav Narayan Rural Municipality","Maulapur Rural Municipality","Paroha Rural Municipality","Phatuwa Bijayapur Rural Municipality","Rajdevi Rural Municipality","Rajpur Rural Municipality","Brindaban Rural Municipality","Dumarwana Rural Municipality","Ishanath Rural Municipality","Dewahi Gonahi Rural Municipality","Yamunamai Rural Municipality"],Saptari:["Rajbiraj Municipality","Kanchanrup Municipality","Surunga Municipality","Agnisair Krishna Savaran Rural Municipality","Balan-Bihul Rural Municipality","Bishnupur Rural Municipality","Chhinnamasta Rural Municipality","Dakneshwari Rural Municipality","Hanumannagar Kankalini Municipality","Khadak Rural Municipality","Mahadewa Rural Municipality","Rajgadh Rural Municipality","Rupani Rural Municipality","Shambhunath Municipality","Tirahut Rural Municipality","Saptakoshi Rural Municipality"],Sarlahi:["Lalbandi Municipality","Haripur Municipality","Hariwan Municipality","Barahathawa Municipality","Ishworpur Municipality","Malangawa Municipality","Bagmati Rural Municipality","Ballara Rural Municipality","Brahampuri Rural Municipality","Chandranagar Rural Municipality","Chakraghatta Rural Municipality","Dhankaul Rural Municipality","Godaita Municipality","Haripurwa Rural Municipality","Kabilasi Rural Municipality","Parsa Rural Municipality","Ramnagar Rural Municipality"],Siraha:["Lahan Municipality","Siraha Municipality","Golbazar Municipality","Mirchaiya Municipality","Kalyanpur Municipality","Sukhipur Municipality","Aurahi Rural Municipality","Bishnupur Rural Municipality","Bariyarpatti Rural Municipality","Dhangadhimai Municipality","Karjanha Rural Municipality","Lakshmipur Patari Rural Municipality","Nawarajpur Rural Municipality","Sakhuwanankarkatti Rural Municipality","Shyam Sundar Madi Rural Municipality"]},Bagmati:{Bhaktapur:["Bhaktapur Municipality","Changunarayan Municipality","Madhyapur Thimi Municipality","Suryabinayak Municipality"],Chitwan:["Bharatpur Metropolitan City","Ratnanagar Municipality","Ichchhakamana Rural Municipality","Kalika Municipality","Khairahani Municipality","Madi Municipality","Rapti Municipality","Rapti Sonari Rural Municipality"],Dhading:["Nilkantha Municipality","Benighat Rorang Rural Municipality","Gajuri Rural Municipality","Galchhi Rural Municipality","Gangajamuna Rural Municipality","Jwalamukhi Rural Municipality","Khaniyabas Rural Municipality","Netrawati Daijee Rural Municipality","Rubi Valley Rural Municipality","Siddhalek Rural Municipality","Thakre Rural Municipality","Tripura Sundari Rural Municipality"],Dolakha:["Bhimeshwar Municipality","Jiri Municipality","Bigu Rural Municipality","Baiteshwar Rural Municipality","Gaurishankar Rural Municipality","Kalinchok Rural Municipality","Melung Rural Municipality","Shailung Rural Municipality","Tamakoshi Rural Municipality"],Kathmandu:["Kathmandu Metropolitan City","Kirtipur Municipality","Budhanilkantha Municipality","Chandragiri Municipality","Dakshinkali Municipality","Gokarneshwar Municipality","Kageshwari Manohara Municipality","Nagarjun Municipality","Shankharapur Municipality","Tarakeshwar Municipality","Tokha Municipality"],Kavrepalanchok:["Banepa Municipality","Dhulikhel Municipality","Panauti Municipality","Namobuddha Municipality","Mandandeupur Municipality","Panchkhal Municipality","Bethanchok Rural Municipality","Bhumlu Rural Municipality","Chaurideurali Rural Municipality","Khanikhola Rural Municipality","Mahabharat Rural Municipality","Roshi Rural Municipality","Temal Rural Municipality"],Lalitpur:["Lalitpur Metropolitan City","Godawari Municipality","Mahalaxmi Municipality","Konjyosom Rural Municipality","Bagmati Rural Municipality"],Makwanpur:["Hetauda Sub-Metropolitan City","Thaha Municipality","Bagmati Rural Municipality","Bakaiya Rural Municipality","Bhimphedi Rural Municipality","Indrasarowar Rural Municipality","Kailash Rural Municipality","Makawanpurgadhi Rural Municipality","Manahari Rural Municipality","Raksirang Rural Municipality"],Nuwakot:["Bidur Municipality","Belkotgadhi Municipality","Kakani Rural Municipality","Dupcheshwar Rural Municipality","Meghang Rural Municipality","Myagang Rural Municipality","Panchakanya Rural Municipality","Shivapuri Rural Municipality","Suryagadhi Rural Municipality","Tadi Rural Municipality","Tarkeshwar Rural Municipality","Likhu Rural Municipality"],Ramechhap:["Manthali Municipality","Ramechhap Municipality","Doramba Rural Municipality","Gokulganga Rural Municipality","Khandadevi Rural Municipality","Likhu Tamakoshi Rural Municipality","Saipatithan Rural Municipality","Sunapati Rural Municipality"],Rasuwa:["Kalika Rural Municipality","Naukunda Rural Municipality","Gosaikunda Rural Municipality","Aamachhodingmo Rural Municipality","Uttargaya Rural Municipality"],Sindhuli:["Kamalamai Municipality","Dudhauli Municipality","Golanjor Rural Municipality","Ghyanglekh Rural Municipality","Hariharpurgadhi Rural Municipality","Marin Rural Municipality","Phikkal Rural Municipality","Sunkoshi Rural Municipality","Tinpatan Rural Municipality"],Sindhupalchok:["Chautara Sangachokgadhi Municipality","Melamchi Municipality","Balephi Rural Municipality","Barhabise Rural Municipality","Bhotekoshi Rural Municipality","Helambu Rural Municipality","Indrawati Rural Municipality","Jugal Rural Municipality","Lisankhu Pakhar Rural Municipality","Panchpokhari Thangpal Rural Municipality","Sunkoshi Rural Municipality","Tripurasundari Rural Municipality"]},Gandaki:{Baglung:["Baglung Municipality","Galkot Municipality","Dhorpatan Municipality","Taman Khola Rural Municipality","Nisikhola Rural Municipality","Jaimuni Municipality","Bareng Rural Municipality","Kanthekhola Rural Municipality","Tatopani Rural Municipality"],Gorkha:["Gorkha Municipality","Palungtar Municipality","Arughat Rural Municipality","Arpak Dudhapokhara Rural Municipality","Bhimsen Rural Municipality","Barpak Sulikot Rural Municipality","Dharche Rural Municipality","Gandaki Rural Municipality","Ajirkot Rural Municipality","Chum Nubri Rural Municipality","Sahid Lakhan Rural Municipality","Siranchok Rural Municipality","Tsum Nubri Rural Municipality"],Kaski:["Pokhara Metropolitan City","Annapurna Rural Municipality","Machhapuchchhre Rural Municipality","Madi Rural Municipality","Rupa Rural Municipality"],Lamjung:["Besisahar Municipality","Rainas Municipality","Sundarbazar Municipality","Dordi Rural Municipality","Dudhpokhari Rural Municipality","Kwholasothar Rural Municipality","Marsyangdi Rural Municipality","Madhya Nepal Rural Municipality","Chamje Rural Municipality"],Manang:["Chame Rural Municipality","Narphu Rural Municipality","Nasong Rural Municipality"],Mustang:["Gharpajhong Rural Municipality","Lomanthang Rural Municipality","Thasang Rural Municipality","Waragung Muktikhola Rural Municipality","Dalome Rural Municipality"],Myagdi:["Beni Municipality","Annapurna Rural Municipality","Dhaulagiri Rural Municipality","Mangala Rural Municipality","Malika Rural Municipality","Raghuganga Rural Municipality"],Nawalpur:["Kawasoti Municipality","Devchuli Municipality","Bardaghat Municipality","Gaindakot Municipality","Hupsekot Municipality","Binayi Tribeni Rural Municipality","Bulingtar Rural Municipality","Madhyabindu Municipality","Palhi Nandan Rural Municipality","Pratappur Rural Municipality","Rainas Rural Municipality","Sarawal Rural Municipality"],Parbat:["Kushma Municipality","Phalewas Municipality","Airawati Rural Municipality","Bihadi Rural Municipality","Jaljala Rural Municipality","Mahashila Rural Municipality","Modi Rural Municipality","Painyu Rural Municipality"],Syangja:["Waling Municipality","Putalibazar Municipality","Galyang Municipality","Bhirkot Municipality","Arjunchaupari Rural Municipality","Biruwa Rural Municipality","Aandhikhola Rural Municipality","Harinas Rural Municipality","Kaligandaki Rural Municipality","Phedikhola Rural Municipality"],Tanahun:["Damauli Municipality","Bhimad Municipality","Byas Municipality","Shuklagandaki Municipality","Bandipure Rural Municipality","Ghiring Rural Municipality","Myagde Rural Municipality","Rhishing Rural Municipality","Devghat Rural Municipality","Anbukhaireni Rural Municipality"]},Lumbini:{Arghakhanchi:["Sandhikharka Municipality","Sitganga Municipality","Chhatradev Rural Municipality","Bhumekasthan Rural Municipality","Malarani Rural Municipality","Panini Rural Municipality","Shivarajpur Rural Municipality"],Banke:["Nepalgunj Sub-Metropolitan City","Kohalpur Municipality","Narainapur Rural Municipality","Khajura Rural Municipality","Janaki Rural Municipality","Raptisonari Rural Municipality","Duduwa Rural Municipality"],Bardiya:["Gulariya Municipality","Rajapur Municipality","Madhuwan Municipality","Barbardiya Municipality","Thakurbaba Municipality","Badhaiyatal Rural Municipality","Bansgadhi Municipality","Geruwa Rural Municipality"],Dang:["Tulsipur Sub-Metropolitan City","Ghorahi Sub-Metropolitan City","Lamahi Municipality","Shantinagar Rural Municipality","Babai Rural Municipality","Bangalachuli Rural Municipality","Gadhawa Rural Municipality","Rajpur Rural Municipality","Rapti Rural Municipality","Dangisharan Rural Municipality"],Gulmi:["Musikot Municipality","Resunga Municipality","Isma Rural Municipality","Chatrakot Rural Municipality","Chandrakot Rural Municipality","Kaligandaki Rural Municipality","Madane Rural Municipality","Malika Rural Municipality","Ruru Rural Municipality","Satyawati Rural Municipality","Gulmi Durbar Rural Municipality"],Kapilvastu:["Banganga Municipality","Buddhabhumi Municipality","Kapilvastu Municipality","Krishnanagar Municipality","Maharajgunj Municipality","Shivaraj Municipality","Bijaynagar Rural Municipality","Motipur Rural Municipality","Suddhodhan Rural Municipality","Yashodhara Rural Municipality"],Palpa:["Tansen Municipality","Rampur Municipality","Rainadevi Chhahara Rural Municipality","Bagnaskali Rural Municipality","Mathagadhi Rural Municipality","Nisdi Rural Municipality","Purbakhola Rural Municipality","Rambha Rural Municipality","Ribdikot Rural Municipality","Tinau Rural Municipality"],Pyuthan:["Pyuthan Municipality","Swargadwari Municipality","Ayirawati Rural Municipality","Gaumukhi Rural Municipality","Jhimruk Rural Municipality","Lungri Rural Municipality","Mallarani Rural Municipality","Mandavi Rural Municipality","Naubahini Rural Municipality","Sarumarani Rural Municipality"],Rolpa:["Rolpa Municipality","Runtigadhi Rural Municipality","Sunchhahari Rural Municipality","Thawang Rural Municipality","Tribeni Rural Municipality","Madi Rural Municipality","Lungri Rural Municipality","Pariwartan Rural Municipality","Gangadev Rural Municipality"],Rupandehi:["Butwal Sub-Metropolitan City","Siddharthanagar Sub-Metropolitan City","Devdaha Municipality","Lumbini Sanskritik Municipality","Marchawar Municipality","Omsatiya Municipality","Saljhandi Rural Municipality","Sammarimai Rural Municipality","Rohini Rural Municipality","Kanchan Rural Municipality","Kotahimai Rural Municipality","Gaidahawa Rural Municipality","Sainamaina Municipality","Tillotama Municipality","Mayadevi Rural Municipality","Siyari Rural Municipality","Sudhdhodhan Rural Municipality"]},Karnali:{Dailekh:["Narayan Municipality","Chamunda Bindrasaini Municipality","Dullu Municipality","Aathabis Municipality","Bhairabi Municipality","Gurans Rural Municipality","Mahabu Rural Municipality","Naumule Rural Municipality","Dungeshwar Rural Municipality","Bhagawatimai Rural Municipality","Thatikandh Rural Municipality"],Dolpa:["Thuli Bheri Municipality","Tripurasundari Municipality","Dolpo Buddha Rural Municipality","Kaike Rural Municipality","Mudkechula Rural Municipality","She Phoksundo Rural Municipality","Jagadulla Rural Municipality","Chharka Tangsong Rural Municipality"],Humla:["Simkot Rural Municipality","Kharpunath Rural Municipality","Adanchuli Rural Municipality","Chankheli Rural Municipality","Namkha Rural Municipality","Sarkegad Rural Municipality","Tanjakot Rural Municipality"],Jajarkot:["Bheri Municipality","Chhedagad Municipality","Barekot Rural Municipality","Junichande Rural Municipality","Kuse Rural Municipality","Nalagad Municipality","Shiwalaya Rural Municipality"],Jumla:["Chandannath Municipality","Sinja Rural Municipality","Tatopani Rural Municipality","Guthichaur Rural Municipality","Kankasundari Rural Municipality","Patarasi Rural Municipality","Hima Rural Municipality"],Kalikot:["Manma Municipality","Sanni Triveni Rural Municipality","Raskot Municipality","Bajura Rural Municipality","Mahawai Rural Municipality","Palata Rural Municipality","Shubha Kalika Municipality","Pachaljharana Rural Municipality","Tilagufa Municipality","Khandachakra Municipality"],Mugu:["Chhayanath Rara Municipality","Mugum Karmarong Rural Municipality","Khatyad Rural Municipality","Soru Rural Municipality"],Salyan:["Sharada Municipality","Bangad Kupinde Municipality","Bagchaur Municipality","Kalimati Rural Municipality","Darma Rural Municipality","Kumakh Rural Municipality","Siddha Kumakh Rural Municipality","Triveni Rural Municipality"],Surkhet:["Birendranagar Municipality","Bheriganga Municipality","Gurbhakot Municipality","Lekbesi Municipality","Panchpuri Municipality","Barahtal Rural Municipality","Simta Rural Municipality","Chaukune Rural Municipality","Chingad Rural Municipality"]},Sudurpashchim:{Achham:["Mangalsen Municipality","Kamalbazar Municipality","Mellekh Rural Municipality","Bannigadhi Jayagadh Rural Municipality","Ramaroshan Rural Municipality","Sanphebagar Municipality","Dhakari Rural Municipality","Chaurpati Rural Municipality","Turmakhand Rural Municipality"],Baitadi:["Dasharathchand Municipality","Purnagiri Municipality","Sigas Rural Municipality","Dogadakedar Rural Municipality","Purchaudi Municipality","Dilasaini Rural Municipality","Melauli Rural Municipality","Surnaya Rural Municipality","Patan Rural Municipality","Shivnath Rural Municipality"],Bajhang:["Jaya Prithvi Municipality","Bungal Municipality","Talkot Municipality","Masta Rural Municipality","Kuldevmandu Rural Municipality","Saipal Rural Municipality","Khaptadchhanna Rural Municipality","Thalara Rural Municipality","Surma Rural Municipality","Chhededaha Rural Municipality","Bithadchir Rural Municipality","Durgathali Rural Municipality","Kanda Rural Municipality"],Bajura:["Badimalika Municipality","Budhiganga Municipality","Budhinanda Municipality","Gaumul Rural Municipality","Himali Rural Municipality","Jagannath Rural Municipality","Khaptad Chhanna Rural Municipality","Swami Kartik Rural Municipality","Triveni Rural Municipality"],Dadeldhura:["Amargadhi Municipality","Aalital Rural Municipality","Ajayameru Rural Municipality","Bhageshwar Rural Municipality","Ganyapadhura Rural Municipality","Nawadurga Rural Municipality","Parashuram Municipality"],Darchula:["Shailyashikhar Municipality","Malikarjun Rural Municipality","Apihimal Rural Municipality","Byash Rural Municipality","Naugad Rural Municipality","Duhu Rural Municipality","Lekam Rural Municipality","Marma Rural Municipality","Mahakali Municipality"],Dothi:["Shikhar Municipality","Dipayal Silgadhi Municipality","Badikedar Rural Municipality","Bogtan Phago Rural Municipality","Jorayal Rural Municipality","K.I.Singh Rural Municipality","Purbichauki Rural Municipality","Aadarsha Rural Municipality","Sayal Rural Municipality"],Kailali:["Dhangadhi Sub-Metropolitan City","Tikapur Municipality","Bhajani Municipality","Ghodaghodi Municipality","Godawari Municipality","Kailari Rural Municipality","Bardagoriya Rural Municipality","Chure Rural Municipality","Gauriganga Municipality","Joshipur Rural Municipality","Mohanyal Rural Municipality","Phatepur Rural Municipality","Janaki Rural Municipality","Lamkichuha Municipality"],Kanchanpur:["Bhimdatta Municipality","Belauri Municipality","Bedkot Municipality","Punarbas Municipality","Shuklaphanta Municipality","Beldandi Rural Municipality","Laljhadi Rural Municipality","Mahakali Municipality","Pipaladi Rural Municipality"]}};

function populateSelect(sel, opts, placeholder) { sel.innerHTML = `<option value="">${placeholder}</option>`; opts.forEach(o => { const el = document.createElement('option'); el.value = el.textContent = o; sel.appendChild(el); }); }
function cascadeDistrict(prefix) { const prov = document.getElementById(prefix+'_province').value; const distSel = document.getElementById(prefix+'_district'); const munSel  = document.getElementById(prefix+'_municipality'); munSel.innerHTML  = '<option value="">-- Select Municipality --</option>'; munSel.disabled = true; if (prov && NEPAL_DATA[prov]) { populateSelect(distSel, Object.keys(NEPAL_DATA[prov]).sort(), '-- Select District --'); distSel.disabled = false; } else { distSel.innerHTML = '<option value="">-- Select District --</option>'; distSel.disabled = true; } }
function cascadeMunicipality(prefix) { const prov = document.getElementById(prefix+'_province').value; const dist = document.getElementById(prefix+'_district').value; const munSel = document.getElementById(prefix+'_municipality'); if (prov && dist && NEPAL_DATA[prov]?.[dist]) { populateSelect(munSel, NEPAL_DATA[prov][dist], '-- Select Municipality --'); munSel.disabled = false; } else { munSel.innerHTML = '<option value="">-- Select Municipality --</option>'; munSel.disabled = true; } }
function toggleSameAsPermanent() { const checked = document.getElementById('same_as_permanent').checked; const mf = document.getElementById('mailing_fields'); if (checked) { document.getElementById('mailing_province').value = document.getElementById('permanent_province').value; cascadeDistrict('mailing'); setTimeout(() => { document.getElementById('mailing_district').value = document.getElementById('permanent_district').value; cascadeMunicipality('mailing'); setTimeout(() => { document.getElementById('mailing_municipality').value = document.getElementById('permanent_municipality').value; }, 50); }, 50); ['ward','tole','house_number'].forEach(f => { const mEl = document.getElementById('mailing_'+f), pEl = document.getElementById('permanent_'+f); if (mEl && pEl) mEl.value = pEl.value; }); mf.style.opacity = '0.5'; mf.style.pointerEvents = 'none'; } else { mf.style.opacity = '1'; mf.style.pointerEvents = ''; } }

(function() {
    const op = @json(old('permanent_province', $candidate->permanent_province ?? ''));
    const od = @json(old('permanent_district',  $candidate->permanent_district  ?? ''));
    const om = @json(old('permanent_municipality', $candidate->permanent_municipality ?? ''));
    const mp = @json(old('mailing_province',  $candidate->mailing_province  ?? ''));
    const md = @json(old('mailing_district',  $candidate->mailing_district   ?? ''));
    const mm = @json(old('mailing_municipality', $candidate->mailing_municipality ?? ''));
    if (op) { cascadeDistrict('permanent'); if (od) { document.getElementById('permanent_district').value=od; cascadeMunicipality('permanent'); if (om) document.getElementById('permanent_municipality').value=om; } }
    if (mp) { cascadeDistrict('mailing');  if (md) { document.getElementById('mailing_district').value=md;  cascadeMunicipality('mailing');  if (mm) document.getElementById('mailing_municipality').value=mm; } }
    if (@json(old('same_as_permanent', $candidate->same_as_permanent ?? false))) toggleSameAsPermanent();
})();
</script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    let currentStep = 1;
    const totalSteps = 7;
    const hasErrors = {{ $errors->any() ? 'true' : 'false' }};
    const form = document.getElementById('editProfileForm');

    function updateTabs() {
        document.querySelectorAll('.tab-item').forEach((tab, i) => {
            tab.classList.remove('active','completed');
            if (i+1 < currentStep)       tab.classList.add('completed');
            else if (i+1 === currentStep) tab.classList.add('active');
        });
    }

    function showStep(step) {
        document.querySelectorAll('.step').forEach(s => s.classList.add('d-none'));
        const el = document.getElementById('step'+step);
        if (el) { el.classList.remove('d-none'); el.classList.add('active'); }
        currentStep = step;
        updateTabs();
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    document.querySelectorAll('.tab-item').forEach(tab => {
        tab.addEventListener('click', e => {
            e.preventDefault(); e.stopPropagation();
            const targetStep = parseInt(tab.getAttribute('data-step'));
            if (targetStep === currentStep) return;
            if (targetStep < currentStep) { showStep(targetStep); return; }
            showStep(targetStep);
        });
    });

    document.querySelectorAll('.next-btn').forEach(btn => {
        btn.addEventListener('click', e => {
            e.preventDefault(); e.stopPropagation();
            if (currentStep < totalSteps) showStep(currentStep+1);
        });
    });

    document.querySelectorAll('.prev-btn').forEach(btn => {
        btn.addEventListener('click', e => {
            e.preventDefault(); e.stopPropagation();
            if (currentStep > 1) showStep(currentStep-1);
        });
    });

    ['religion','community','ethnic_group'].forEach(id => {
        const sel = document.getElementById(id);
        const other = document.getElementById(id+'_other');
        if (!sel||!other) return;
        const toggle = () => {
            const show = sel.value==='Other';
            other.classList.toggle('d-none', !show);
            show ? other.setAttribute('required','required') : (other.removeAttribute('required'), other.value='');
        };
        sel.addEventListener('change', toggle); toggle();
    });
    const ethnicSel = document.getElementById('ethnic_group');
const ethnicFile = document.getElementById('ethnic_certificate');
const ethnicLabel = document.getElementById('ethnic_certificate_label');

if (ethnicSel && ethnicFile) {
    const toggleEthnicCertificate = () => {
        if (['Dalit', 'Madhesi', 'Janajati'].includes(ethnicSel.value)) {
            if (!ethnicFile.dataset.existingFile) {
                ethnicFile.setAttribute('required', 'required');
            }
            if (ethnicLabel && !ethnicLabel.querySelector('.text-danger')) {
                ethnicLabel.innerHTML += ' <span class="text-danger">*</span>';
            }
        } else {
            ethnicFile.removeAttribute('required');
            const sp = ethnicLabel?.querySelector('.text-danger');
            if (sp) sp.remove();
        }
    };

    toggleEthnicCertificate();
    ethnicSel.addEventListener('change', toggleEthnicCertificate);
}
    const nocSel = document.getElementById('noc_employee');
    const nocFile = document.getElementById('noc_id_card');
    const nocLabel = document.getElementById('noc_id_card_label');
    if (nocSel && nocFile) {
        const toggleNoc = () => {
            if (nocSel.value==='yes') {
                if (!nocFile.dataset.existingFile) nocFile.setAttribute('required','required');
                if (nocLabel && !nocLabel.querySelector('.text-danger')) nocLabel.innerHTML += ' <span class="text-danger">*</span>';
            } else {
                nocFile.removeAttribute('required');
                const sp = nocLabel?.querySelector('.text-danger'); if(sp) sp.remove();
            }
        };
        toggleNoc(); nocSel.addEventListener('change', toggleNoc);
    }

    const disSel = document.getElementById('physical_disability');
    const disCertWrapper = document.getElementById('disabilityCertWrapper');
    const disFile = document.getElementById('disability_certificate');
    const disLabel = document.getElementById('disability_certificate_label');
    if (disSel && disCertWrapper) {
        const toggleDis = () => {
            const show = disSel.value==='yes';
            disCertWrapper.style.display = show ? '' : 'none';
            if (show) {
                if (!disFile?.dataset.existingFile) disFile?.setAttribute('required','required');
                if (disLabel && !disLabel.querySelector('.text-danger')) disLabel.innerHTML += ' <span class="text-danger">*</span>';
            } else {
                disFile?.removeAttribute('required');
                const sp = disLabel?.querySelector('.text-danger'); if(sp) sp.remove();
            }
        };
        toggleDis(); disSel.addEventListener('change', toggleDis);
    }
    
    const weSel = document.getElementById('has_work_experience');
    const weWrapper = document.getElementById('experience_table_wrapper');
    if (weSel && weWrapper) {
        const toggleWe = () => { weWrapper.style.display = weSel.value==='Yes' ? 'block' : 'none'; };
        toggleWe(); weSel.addEventListener('change', toggleWe);
    }

    function initNDP(el) {
        if (!el || typeof el.nepaliDatePicker !== 'function') return;
        el.nepaliDatePicker({ ndpYear:true, ndpMonth:true, ndpYearCount:100,
            onChange: function() { el.dispatchEvent(new Event('input',{bubbles:true})); el.dispatchEvent(new Event('change',{bubbles:true})); }
        });
    }
    initNDP(document.getElementById('birth_date_bs'));
    initNDP(document.getElementById('citizenship_issue_date_bs'));

    const bsIn  = document.getElementById('birth_date_bs');
    const adHid = document.getElementById('birth_date_ad');
    const adDis = document.getElementById('birth_date_ad_display');
    function syncAD(val) {
        if (!val || typeof window.bsToAD !== 'function') return;
        const ad = window.bsToAD(val); if (!ad) return;
        if (adHid) adHid.value = ad;
        if (adDis && window.formatADDisplay) adDis.value = window.formatADDisplay(ad);
        adHid?.dispatchEvent(new Event('change',{bubbles:true}));
        adHid?.dispatchEvent(new Event('input', {bubbles:true}));
    }
    if (bsIn) {
        bsIn.addEventListener('change', () => syncAD(bsIn.value));
        syncAD(bsIn.value);
        let lastBs = bsIn.value;
        setInterval(() => {
            if (bsIn.value && bsIn.value !== lastBs && bsIn.value.length >= 10) { lastBs = bsIn.value; syncAD(bsIn.value); }
        }, 300);
    }

    function calculateExactAge(dateString) {
        if (!dateString) return '';
        const birth = new Date(dateString); if (isNaN(birth.getTime())) return '';
        const today = new Date();
        let years = today.getFullYear()-birth.getFullYear();
        let months = today.getMonth()-birth.getMonth();
        let days = today.getDate()-birth.getDate();
        if (days<0) { months--; const lm=new Date(today.getFullYear(),today.getMonth(),0); days+=lm.getDate(); }
        if (months<0) { years--; months+=12; }
        return `${years} years ${months} months ${days} days`;
    }
    function updateAge(val) {
        const f = document.getElementById('age'); if (f) f.value = calculateExactAge(val);
    }
    if (adHid) {
        adHid.addEventListener('change', () => updateAge(adHid.value));
        adHid.addEventListener('input',  () => updateAge(adHid.value));
        updateAge(adHid.value);
    }

    function initNDPOnRow(row) {
        row.querySelectorAll('.exp-nepali-date').forEach(el => {
            initNDP(el);
            el.addEventListener('change', function() {
                if (!this.value || typeof window.bsToAD !== 'function') return;
                const ad = window.bsToAD(this.value);
                const targetName = this.getAttribute('data-target');
                if (!targetName) return;
                const hidden = row.querySelector(`input[name="${targetName}"]`);
                if (hidden) hidden.value = ad;
            });
        });
    }
    document.querySelectorAll('.experience-row').forEach(row => initNDPOnRow(row));

    const MAX_ROWS = 10;
    const rowsWrap  = document.getElementById('experience_rows');
    const addBtn    = document.getElementById('addExpRow');
    const countSpan = document.getElementById('expRowCount');

    function updateCounter() {
        if (!rowsWrap) return;
        const rows = rowsWrap.querySelectorAll('.experience-row');
        const n = rows.length;
        if (countSpan) countSpan.textContent = `${n} / ${MAX_ROWS} entr${n===1?'y':'ies'}`;
        if (addBtn) addBtn.disabled = n >= MAX_ROWS;
        rows.forEach((row, i) => {
            row.dataset.row = i+1;
            const rn = row.querySelector('.row-number'); if(rn) rn.textContent = i+1;
            const rb = row.querySelector('.remove-exp-row'); if(rb) rb.style.display = n>1?'':'none';
        });
    }
    updateCounter();

    if (addBtn && rowsWrap) {
        addBtn.addEventListener('click', function() {
            const current = rowsWrap.querySelectorAll('.experience-row').length;
            if (current >= MAX_ROWS) return;
            const n = current+1;
            const div = document.createElement('div');
            div.className = 'experience-row exp-block border rounded p-3 mb-3';
            div.dataset.row = n;
            div.innerHTML = `<div class="d-flex justify-content-between align-items-center mb-2"><strong class="text-muted" style="font-size:.9rem;">Experience #<span class="row-number">${n}</span></strong><button type="button" class="btn btn-sm btn-outline-danger remove-exp-row"><i class="bi bi-trash"></i> Remove</button></div><div class="row g-2"><div class="col-md-4"><label class="form-label small">{{ __('candidate.organization') }}</label><input type="text" name="exp${n}_organization" class="form-control form-control-sm"></div><div class="col-md-4"><label class="form-label small">{{ __('candidate.position') }}</label><input type="text" name="exp${n}_position" class="form-control form-control-sm"></div><div class="col-md-4"><label class="form-label small">{{ __('candidate.start_date_bs') }}</label><input type="text" name="exp${n}_start_date_bs" class="form-control form-control-sm exp-nepali-date" placeholder="YYYY-MM-DD" data-target="exp${n}_start_date" autocomplete="off"><input type="hidden" name="exp${n}_start_date"></div><div class="col-md-4"><label class="form-label small">{{ __('candidate.end_date_bs') }}</label><input type="text" name="exp${n}_end_date_bs" class="form-control form-control-sm exp-nepali-date" placeholder="YYYY-MM-DD" data-target="exp${n}_end_date" autocomplete="off"><input type="hidden" name="exp${n}_end_date"></div><div class="col-md-2"><label class="form-label small">{{ __('candidate.years') }}</label><input type="number" step="0.5" name="exp${n}_years" class="form-control form-control-sm"></div><div class="col-md-6"><label class="form-label small">{{ __('candidate.document') }}</label><input type="file" name="exp${n}_document" class="form-control form-control-sm" accept="image/*,application/pdf"></div></div>`;
            rowsWrap.appendChild(div);
            initNDPOnRow(div);
            updateCounter();
            div.scrollIntoView({ behavior:'smooth', block:'nearest' });
        });

        rowsWrap.addEventListener('click', function(e) {
            const btn = e.target.closest('.remove-exp-row'); if (!btn) return;
            const row = btn.closest('.experience-row');
            if (rowsWrap.querySelectorAll('.experience-row').length <= 1) return;
            row.remove();
            rowsWrap.querySelectorAll('.experience-row').forEach((r, i) => {
                const idx = i+1; r.dataset.row = idx;
                const rn = r.querySelector('.row-number'); if(rn) rn.textContent = idx;
                r.querySelectorAll('input[name]').forEach(inp => {
                    inp.name = inp.name.replace(/^exp\d+_/, `exp${idx}_`);
                    const dt = inp.getAttribute('data-target');
                    if (dt) inp.setAttribute('data-target', dt.replace(/^exp\d+_/, `exp${idx}_`));
                });
            });
            updateCounter();
        });
    }

    document.querySelectorAll('.nepali-only').forEach(function(field) {
        const ok = /[\u0900-\u097F\s.\-]/;
        const clean = str => str.split('').filter(c => ok.test(c)).join('');
        field.addEventListener('keydown', e => {
            if (e.ctrlKey||e.metaKey||e.altKey||['Backspace','Delete','Tab','Escape','Enter','ArrowLeft','ArrowRight','ArrowUp','ArrowDown','Home','End','Shift'].includes(e.key)) return;
            if (e.key.length===1 && !ok.test(e.key)) e.preventDefault();
        });
        field.addEventListener('input', function() {
            const pos = this.selectionStart, cleaned = clean(this.value);
            if (cleaned !== this.value) { this.value = cleaned; this.setSelectionRange(Math.min(pos,cleaned.length), Math.min(pos,cleaned.length)); }
        });
        field.addEventListener('paste', e => {
            e.preventDefault();
            const cleaned = clean((e.clipboardData||window.clipboardData).getData('text'));
            if (!cleaned) return;
            const s=field.selectionStart, en=field.selectionEnd;
            field.value = field.value.slice(0,s)+cleaned+field.value.slice(en);
            field.setSelectionRange(s+cleaned.length, s+cleaned.length);
            field.dispatchEvent(new Event('input',{bubbles:true}));
        });
    });

    document.querySelectorAll('.exp-nepali-date').forEach(function(input) {
        input.addEventListener('change', function() {
            if (!this.value || typeof window.bsToAD !== 'function') return;
            const adDate = window.bsToAD(this.value);
            const targetName = this.getAttribute('data-target');
            if (!targetName) return;
            const row = this.closest('.experience-row');
            const hidden = row ? row.querySelector(`input[name="${targetName}"]`) : document.querySelector(`input[name="${targetName}"]`);
            if (hidden) hidden.value = adDate;
        });
    });

    if (hasErrors) {
        setTimeout(() => {
            const inv = document.querySelector('.is-invalid');
            if (inv) { const se = inv.closest('.step'); if (se) { showStep(parseInt(se.id.replace('step',''))); return; } }
            showStep(1);
        }, 150);
    } else { showStep(1); }

});
// ── Devanagari-only enforcement ───────────────────────────────
(function () {
    const field = document.getElementById('name_nepali');
    if (!field) return;
    const ok = /[\u0900-\u097F\s.\-]/;
    const clean = str => str.split('').filter(c => ok.test(c)).join('');
    field.addEventListener('keydown', e => {
        if (e.ctrlKey||e.metaKey||e.altKey||['Backspace','Delete','Tab','Escape','Enter','ArrowLeft','ArrowRight','ArrowUp','ArrowDown','Home','End','Shift'].includes(e.key)) return;
        if (e.key.length === 1 && !ok.test(e.key)) e.preventDefault();
    });
    field.addEventListener('input', function () {
        const pos = this.selectionStart, cleaned = clean(this.value);
        if (cleaned !== this.value) { this.value = cleaned; this.setSelectionRange(Math.min(pos, cleaned.length), Math.min(pos, cleaned.length)); }
    });
    field.addEventListener('paste', e => {
        e.preventDefault();
        const cleaned = clean((e.clipboardData||window.clipboardData).getData('text'));
        if (!cleaned) return;
        const s = field.selectionStart, en = field.selectionEnd;
        field.value = field.value.slice(0, s) + cleaned + field.value.slice(en);
        field.setSelectionRange(s + cleaned.length, s + cleaned.length);
        field.dispatchEvent(new Event('input', { bubbles: true }));
    });
    field.addEventListener('drop', e => {
        e.preventDefault();
        const cleaned = clean(e.dataTransfer.getData('text')); if (!cleaned) return;
        const pos = field.selectionStart;
        field.value = field.value.slice(0, pos) + cleaned + field.value.slice(pos);
        field.setSelectionRange(pos + cleaned.length, pos + cleaned.length);
        field.dispatchEvent(new Event('input', { bubbles: true }));
    });
})();
// ── Devanagari-only enforcement ───────────────────────────────
(function () {
    const field = document.getElementById('father_name_nepali');
    if (!field) return;
    const ok = /[\u0900-\u097F\s.\-]/;
    const clean = str => str.split('').filter(c => ok.test(c)).join('');
    field.addEventListener('keydown', e => {
        if (e.ctrlKey||e.metaKey||e.altKey||['Backspace','Delete','Tab','Escape','Enter','ArrowLeft','ArrowRight','ArrowUp','ArrowDown','Home','End','Shift'].includes(e.key)) return;
        if (e.key.length === 1 && !ok.test(e.key)) e.preventDefault();
    });
    field.addEventListener('input', function () {
        const pos = this.selectionStart, cleaned = clean(this.value);
        if (cleaned !== this.value) { this.value = cleaned; this.setSelectionRange(Math.min(pos, cleaned.length), Math.min(pos, cleaned.length)); }
    });
    field.addEventListener('paste', e => {
        e.preventDefault();
        const cleaned = clean((e.clipboardData||window.clipboardData).getData('text'));
        if (!cleaned) return;
        const s = field.selectionStart, en = field.selectionEnd;
        field.value = field.value.slice(0, s) + cleaned + field.value.slice(en);
        field.setSelectionRange(s + cleaned.length, s + cleaned.length);
        field.dispatchEvent(new Event('input', { bubbles: true }));
    });
    field.addEventListener('drop', e => {
        e.preventDefault();
        const cleaned = clean(e.dataTransfer.getData('text')); if (!cleaned) return;
        const pos = field.selectionStart;
        field.value = field.value.slice(0, pos) + cleaned + field.value.slice(pos);
        field.setSelectionRange(pos + cleaned.length, pos + cleaned.length);
        field.dispatchEvent(new Event('input', { bubbles: true }));
    });
})();
// ── Devanagari-only enforcement ───────────────────────────────
(function () {
    const field = document.getElementById('mother_name_nepali');
    if (!field) return;
    const ok = /[\u0900-\u097F\s.\-]/;
    const clean = str => str.split('').filter(c => ok.test(c)).join('');
    field.addEventListener('keydown', e => {
        if (e.ctrlKey||e.metaKey||e.altKey||['Backspace','Delete','Tab','Escape','Enter','ArrowLeft','ArrowRight','ArrowUp','ArrowDown','Home','End','Shift'].includes(e.key)) return;
        if (e.key.length === 1 && !ok.test(e.key)) e.preventDefault();
    });
    field.addEventListener('input', function () {
        const pos = this.selectionStart, cleaned = clean(this.value);
        if (cleaned !== this.value) { this.value = cleaned; this.setSelectionRange(Math.min(pos, cleaned.length), Math.min(pos, cleaned.length)); }
    });
    field.addEventListener('paste', e => {
        e.preventDefault();
        const cleaned = clean((e.clipboardData||window.clipboardData).getData('text'));
        if (!cleaned) return;
        const s = field.selectionStart, en = field.selectionEnd;
        field.value = field.value.slice(0, s) + cleaned + field.value.slice(en);
        field.setSelectionRange(s + cleaned.length, s + cleaned.length);
        field.dispatchEvent(new Event('input', { bubbles: true }));
    });
    field.addEventListener('drop', e => {
        e.preventDefault();
        const cleaned = clean(e.dataTransfer.getData('text')); if (!cleaned) return;
        const pos = field.selectionStart;
        field.value = field.value.slice(0, pos) + cleaned + field.value.slice(pos);
        field.setSelectionRange(pos + cleaned.length, pos + cleaned.length);
        field.dispatchEvent(new Event('input', { bubbles: true }));
    });
})();
// ── Devanagari-only enforcement ───────────────────────────────
(function () {
    const field = document.getElementById('grandfather_name_nepali');
    if (!field) return;
    const ok = /[\u0900-\u097F\s.\-]/;
    const clean = str => str.split('').filter(c => ok.test(c)).join('');
    field.addEventListener('keydown', e => {
        if (e.ctrlKey||e.metaKey||e.altKey||['Backspace','Delete','Tab','Escape','Enter','ArrowLeft','ArrowRight','ArrowUp','ArrowDown','Home','End','Shift'].includes(e.key)) return;
        if (e.key.length === 1 && !ok.test(e.key)) e.preventDefault();
    });
    field.addEventListener('input', function () {
        const pos = this.selectionStart, cleaned = clean(this.value);
        if (cleaned !== this.value) { this.value = cleaned; this.setSelectionRange(Math.min(pos, cleaned.length), Math.min(pos, cleaned.length)); }
    });
    field.addEventListener('paste', e => {
        e.preventDefault();
        const cleaned = clean((e.clipboardData||window.clipboardData).getData('text'));
        if (!cleaned) return;
        const s = field.selectionStart, en = field.selectionEnd;
        field.value = field.value.slice(0, s) + cleaned + field.value.slice(en);
        field.setSelectionRange(s + cleaned.length, s + cleaned.length);
        field.dispatchEvent(new Event('input', { bubbles: true }));
    });
    field.addEventListener('drop', e => {
        e.preventDefault();
        const cleaned = clean(e.dataTransfer.getData('text')); if (!cleaned) return;
        const pos = field.selectionStart;
        field.value = field.value.slice(0, pos) + cleaned + field.value.slice(pos);
        field.setSelectionRange(pos + cleaned.length, pos + cleaned.length);
        field.dispatchEvent(new Event('input', { bubbles: true }));
    });
})();
</script>
@endpush

