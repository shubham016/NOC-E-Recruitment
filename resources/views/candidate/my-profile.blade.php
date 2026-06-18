@extends('layouts.app')

@section('title', __('candidate.my_profile'))

@section('sidebar-menu')
    <a href="{{ route('candidate.dashboard') }}" class="sidebar-menu-item">
        <i class="bi bi-speedometer2"></i>
        <span>{{ __('candidate.dashboard') }}</span>
    </a>
    <a href="{{ route('candidate.my-profile') }}" class="sidebar-menu-item active">
        <i class="bi bi-person"></i>
        <span>{{ __('candidate.my_profile') }}</span>
    </a>
    <a href="{{ route('candidate.jobs.index') }}" class="sidebar-menu-item">
        <i class="bi bi-search"></i>
        <span>{{ __('candidate.vacancy') }}</span>
    </a>
    <a href="{{ route('candidate.applications.index') }}" class="sidebar-menu-item">
        <i class="bi bi-file-earmark-text"></i>
        <span>{{ __('candidate.my_applications') }}</span>
    </a>
    <a href="{{ route('candidate.viewresult') }}" class="sidebar-menu-item">
        <i class="bi bi-file-earmark-check"></i>
        <span>{{ __('candidate.view_result') }}</span>
    </a>
    <a href="{{ route('candidate.admit-card') }}" class="sidebar-menu-item">
        <i class="bi bi-box-arrow-down"></i>
        <span>{{ __('candidate.download_admit_card') }}</span>
    </a>
    <a href="{{ route('candidate.change-password') }}" class="sidebar-menu-item">
        <i class="bi bi-lock"></i>
        <span>{{ __('candidate.change_password') }}</span>
    </a>
@endsection

@section('content')
<div class="container my-2">
    <div class="card shadow-lg border-0">
        <div class="card-header bg-light text-dark d-flex justify-content-between align-items-center py-2">
            <h3 class="mb-0 fw-bold">{{ __('candidate.view_my_profile') }}</h3>
            <a href="{{ route('candidate.edit-profile') }}" class="btn btn-danger btn-sm">
                <i class="bi bi-pencil me-1"></i> {{ __('candidate.edit_profile') }}
            </a>
        </div>

        <div class="card-body px-5 pt-3 pb-5">

            {{-- Clickable Tabs Navigation --}}
            <div class="step-tabs mb-5">
                <div class="d-flex flex-wrap justify-content-between border-bottom position-relative">
                    <div class="tab-item active" data-step="1">
                        <span class="tab-circle">1</span>
                        <span class="tab-label d-none d-md-inline">{{ __('candidate.personal') }}</span>
                    </div>
                    <div class="tab-item" data-step="2">
                        <span class="tab-circle">2</span>
                        <span class="tab-label d-none d-md-inline">{{ __('candidate.general') }}</span>
                    </div>
                    <div class="tab-item" data-step="3">
                        <span class="tab-circle">3</span>
                        <span class="tab-label d-none d-md-inline">{{ __('candidate.address') }}</span>
                    </div>
                    <div class="tab-item" data-step="4">
                        <span class="tab-circle">4</span>
                        <span class="tab-label d-none d-md-inline">{{ __('candidate.education') }}</span>
                    </div>
                    <div class="tab-item" data-step="5">
                        <span class="tab-circle">5</span>
                        <span class="tab-label d-none d-md-inline">{{ __('candidate.experience') }}</span>
                    </div>
                    <div class="tab-item" data-step="6">
                        <span class="tab-circle">6</span>
                        <span class="tab-label d-none d-md-inline">{{ __('candidate.documents') }}</span>
                    </div>
                    <!-- <div class="tab-item" data-step="7">
                        <span class="tab-circle">7</span>
                        <span class="tab-label d-none d-md-inline">Payment</span>
                    </div> -->
                </div>
            </div>

            {{-- STEP 1: Personal --}}
            <div class="step active" id="step1">
                <h5 class="mb-4 text-dark">Step 1 - {{ __('candidate.personal_information') }}</h5>

                <div class="row mb-3">
                    <div class="col-md-6 mb-3">
                        <strong>{{ __('candidate.full_name_english') }}:</strong>
                        <p class="mb-0">{{ $candidate->name_english ?? '-' }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>{{ __('candidate.full_name_nepali') }}:</strong>
                        <p class="mb-0">{{ $candidate->name_nepali ?? '-' }}</p>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-3 mb-3">
                        <strong>{{ __('candidate.birth_date_ad') }}:</strong>
                        <p class="mb-0">
                            @if(!empty($candidate?->birth_date_ad))
                                {{ is_string($candidate->birth_date_ad) ? \Carbon\Carbon::parse($candidate->birth_date_ad)->format('F d, Y') : $candidate->birth_date_ad->format('F d, Y') }}
                            @else
                                -
                            @endif
                        </p>
                    </div>
                    <div class="col-md-3 mb-3">
                        <strong>{{ __('candidate.birth_date_bs') }}:</strong>
                        <p class="mb-0">{{ $candidate->birth_date_bs ?? '-' }}</p>
                    </div>
                    <div class="col-md-3 mb-3">
                        <strong>{{ __('candidate.email') }}:</strong>
                        <p class="mb-0">{{ $candidate->email ?? '-' }}</p>
                    </div>
                    <div class="col-md-3 mb-3">
                        <strong>{{ __('candidate.phone_number') }}:</strong>
                        <p class="mb-0">{{ $candidate->phone ?? '-' }}</p>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4 mb-3">
                        <strong>{{ __('candidate.age') }}:</strong>
                        <p class="mb-0">{{ $candidate->age ?? '-' }} {{ $candidate->age ? __('candidate.years') : '' }}</p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <strong>{{ __('candidate.alternate_phone_number') }}:</strong>
                        <p class="mb-0">{{ $candidate->alternate_phone_number ?? '-' }}</p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <strong>{{ __('candidate.gender') }}:</strong>
                        <p class="mb-0">{{ ucfirst($candidate->gender ?? '-') }}</p>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4 mb-3">
                        <strong>{{ __('candidate.marital_status') }}:</strong>
                        <p class="mb-0">{{ ucfirst($candidate->marital_status ?? '-') }}</p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <strong>{{ __('candidate.spouse_name_english') }}:</strong>
                        <p class="mb-0">{{ $candidate->spouse_name_english ?? '-' }}</p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <strong>{{ __('candidate.spouse_nationality') }}:</strong>
                        <p class="mb-0">{{ $candidate->spouse_nationality ?? '-' }}</p>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4 mb-3">
                        <strong>{{ __('candidate.citizenship_number') }}:</strong>
                        <p class="mb-0">{{ $candidate->citizenship_number ?? '-' }}</p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <strong>{{ __('candidate.citizenship_issue_date_bs') }}:</strong>
                        <p class="mb-0">{{ $candidate->citizenship_issue_date_bs ?? '-' }}</p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <strong>{{ __('candidate.citizenship_issue_district') }}:</strong>
                        <p class="mb-0">{{ $candidate->citizenship_issue_district ?? '-' }}</p>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4 mb-3">
                        <strong>{{ __('candidate.father_name_english') }}:</strong>
                        <p class="mb-0">{{ $candidate->father_name_english ?? '-' }}</p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <strong>{{ __('candidate.mother_name_english') }}:</strong>
                        <p class="mb-0">{{ $candidate->mother_name_english ?? '-' }}</p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <strong>{{ __('candidate.grandfather_name_english') }}:</strong>
                        <p class="mb-0">{{ $candidate->grandfather_name_english ?? '-' }}</p>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4 mb-3">
                        <strong>{{ __('candidate.father_name_nepali') }}:</strong>
                        <p class="mb-0">{{ $candidate->father_name_nepali ?? '-' }}</p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <strong>{{ __('candidate.mother_name_nepali') }}:</strong>
                        <p class="mb-0">{{ $candidate->mother_name_nepali ?? '-' }}</p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <strong>{{ __('candidate.grandfather_name_nepali') }}:</strong>
                        <p class="mb-0">{{ $candidate->grandfather_name_nepali ?? '-' }}</p>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4 mb-3">
                        <strong>{{ __('candidate.blood_group') }}:</strong>
                        <p class="mb-0">{{ $candidate->blood_group ?? '-' }}</p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <strong>{{ __('candidate.nationality') }}:</strong>
                        <p class="mb-0">{{ $candidate->nationality ?? '-' }}</p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <strong>{{ __('candidate.are_you_noc_employee') }}:</strong>
                        <p class="mb-0">{{ ucfirst($candidate->noc_employee ?? '-') }}</p>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6 mb-3">
                        <strong>{{ __('candidate.noc_id_card') }}:</strong>
                        <p class="mb-0">{!! showDoc($candidate->noc_id_card ?? null) !!}</p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <strong>{{ __('candidate.parents_occupation') }}:</strong>
                        <p class="mb-0">{{ $candidate->parents_occupation ?? '-' }}</p>
                    </div>
                </div>

                <div class="d-flex justify-content-end">
                    <button type="button" class="btn btn-light next-btn">Next</button>
                </div>
            </div>

            {{-- STEP 2: General --}}
            <div class="step d-none" id="step2">
                <h5 class="mb-4 text-dark">Step 2 - {{ __('candidate.general_information') }}</h5>

                <div class="row mb-3">
                    <div class="col-md-4 mb-3">
                        <strong>{{ __('candidate.religion') }}:</strong>
                        <p class="mb-0">
                            {{ $candidate->religion ?? '-' }}
                            @if(($candidate->religion ?? null) === 'Other' && !empty($candidate->religion_other))
                                ({{ $candidate->religion_other }})
                            @endif
                        </p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <strong>{{ __('candidate.community') }}:</strong>
                        <p class="mb-0">
                            {{ $candidate->community ?? '-' }}
                            @if(($candidate->community ?? null) === 'Other' && !empty($candidate->community_other))
                                ({{ $candidate->community_other }})
                            @endif
                        </p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <strong>{{ __('candidate.ethnic_group') }}:</strong>
                        <p class="mb-0">
                            {{ $candidate->ethnic_group ?? '-' }}
                            @if(($candidate->ethnic_group ?? null) === 'Other' && !empty($candidate->ethnic_group_other))
                                ({{ $candidate->ethnic_group_other }})
                            @endif
                        </p>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6 mb-3">
                        <strong>{{ __('candidate.mother_tongue') }}:</strong>
                        <p class="mb-0">{{ $candidate->mother_tongue ?? '-' }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>{{ __('candidate.employment_status') }}:</strong>
                        <p class="mb-0">{{ ucfirst($candidate->employment_status ?? '-') }}</p>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4 mb-3">
                        <strong>{{ __('candidate.physical_disability') }}:</strong>
                        <p class="mb-0">{{ ucfirst($candidate->physical_disability ?? '-') }}</p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <strong>{{ __('candidate.disability_details') }}:</strong>
                        <p class="mb-0">{{ $candidate->disability_other ?? '-' }}</p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <strong>{{ __('candidate.ethnic_certificate') }}:</strong>
                        <p class="mb-0">{!! showDoc($candidate->ethnic_certificate ?? null) !!}</p>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6 mb-3">
                        <strong>{{ __('candidate.disability_certificate') }}:</strong>
                        <p class="mb-0">{!! showDoc($candidate->disability_certificate ?? null) !!}</p>
                    </div>
                </div>

                <div class="d-flex justify-content-between">
                    <button type="button" class="btn btn-secondary prev-btn">Back</button>
                    <button type="button" class="btn btn-light next-btn">Next</button>
                </div>
            </div>

            {{-- STEP 3: Address --}}
            <div class="step d-none" id="step3">
                <h5 class="mb-4 text-dark">Step 3 - {{ __('candidate.address_information') }}</h5>

                <h6 class="mb-3 text-secondary">{{ __('candidate.permanent_address') }}</h6>
                <div class="row mb-3">
                    <div class="col-md-4 mb-3">
                        <strong>{{ __('candidate.province') }}:</strong>
                        <p class="mb-0">{{ $candidate->permanent_province ?? '-' }}</p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <strong>{{ __('candidate.district') }}:</strong>
                        <p class="mb-0">{{ $candidate->permanent_district ?? '-' }}</p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <strong>{{ __('candidate.municipality') }}:</strong>
                        <p class="mb-0">{{ $candidate->permanent_municipality ?? '-' }}</p>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4 mb-3">
                        <strong>{{ __('candidate.ward_no') }}:</strong>
                        <p class="mb-0">{{ $candidate->permanent_ward ?? '-' }}</p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <strong>{{ __('candidate.tole') }}:</strong>
                        <p class="mb-0">{{ $candidate->permanent_tole ?? '-' }}</p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <strong>{{ __('candidate.house_number') }}:</strong>
                        <p class="mb-0">{{ $candidate->permanent_house_number ?? '-' }}</p>
                    </div>
                </div>

                <h6 class="mb-3 text-secondary mt-4">{{ __('candidate.mailing_current_address') }}</h6>
                @if(!empty($candidate?->same_as_permanent))
                    <div class="alert alert-info mb-3">
                        <i class="fas fa-info-circle"></i> {{ __('candidate.same_as_permanent_address') }}
                    </div>
                @endif

                <div class="row mb-3">
                    <div class="col-md-4 mb-3">
                        <strong>{{ __('candidate.province') }}:</strong>
                        <p class="mb-0">{{ $candidate->mailing_province ?? '-' }}</p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <strong>{{ __('candidate.district') }}:</strong>
                        <p class="mb-0">{{ $candidate->mailing_district ?? '-' }}</p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <strong>{{ __('candidate.municipality') }}:</strong>
                        <p class="mb-0">{{ $candidate->mailing_municipality ?? '-' }}</p>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4 mb-3">
                        <strong>{{ __('candidate.ward_no') }}:</strong>
                        <p class="mb-0">{{ $candidate->mailing_ward ?? '-' }}</p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <strong>{{ __('candidate.tole') }}:</strong>
                        <p class="mb-0">{{ $candidate->mailing_tole ?? '-' }}</p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <strong>{{ __('candidate.house_number') }}:</strong>
                        <p class="mb-0">{{ $candidate->mailing_house_number ?? '-' }}</p>
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
                    <div class="col-md-6 mb-3">
                        <strong>{{ __('candidate.highest_education_level') }}:</strong>
                        <p class="mb-0">{{ $candidate->education_level ?? '-' }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>{{ __('candidate.field_of_study') }}:</strong>
                        <p class="mb-0">{{ $candidate->field_of_study ?? '-' }}</p>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6 mb-3">
                        <strong>{{ __('candidate.institution_name') }}:</strong>
                        <p class="mb-0">{{ $candidate->institution_name ?? '-' }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>{{ __('candidate.graduation_year') }}:</strong>
                        <p class="mb-0">{{ $candidate->graduation_year ?? '-' }}</p>
                    </div>
                </div>

                <div class="d-flex justify-content-between">
                    <button type="button" class="btn btn-secondary prev-btn">Back</button>
                    <button type="button" class="btn btn-light next-btn">Next</button>
                </div>
            </div>

            {{-- STEP 5: Experience --}}
            <div class="step d-none" id="step5">
                <h5 class="mb-4 text-dark">Step 5 - {{ __('candidate.work_experience') }}</h5>

                <div class="mb-3">
                    <strong>{{ __('candidate.has_work_experience') }}:</strong>
                    <p class="mb-0">{{ ucfirst($candidate->has_work_experience ?? '-') }}</p>
                </div>

                {{-- Since myprofile uses exp1/exp2/exp3 fields, render them if present --}}
                @php
                    $exps = [
                        [
                            'title' => 'Experience 1',
                            'org' => $candidate->exp1_organization ?? null,
                            'pos' => $candidate->exp1_position ?? null,
                            'start' => $candidate->exp1_start_date_bs ?? null,
                            'end' => $candidate->exp1_end_date_bs ?? null,
                            'years' => $candidate->exp1_years ?? null,
                            'doc' => $candidate->exp1_document ?? null,
                        ],
                        [
                            'title' => 'Experience 2',
                            'org' => $candidate->exp2_organization ?? null,
                            'pos' => $candidate->exp2_position ?? null,
                            'start' => $candidate->exp2_start_date_bs ?? null,
                            'end' => $candidate->exp2_end_date_bs  ?? null,
                            'years' => $candidate->exp2_years ?? null,
                            'doc' => $candidate->exp2_document ?? null,
                        ],
                        [
                            'title' => 'Experience 3',
                            'org' => $candidate->exp3_organization ?? null,
                            'pos' => $candidate->exp3_position ?? null,
                            'start' => $candidate->exp3_start_date_bs  ?? null,
                            'end' => $candidate->exp3_end_date_bs  ?? null,
                            'years' => $candidate->exp3_years ?? null,
                            'doc' => $candidate->exp3_document ?? null,
                        ],
                        [
                            'title' => 'Experience 4',
                            'org' => $candidate->exp4_organization ?? null,
                            'pos' => $candidate->exp4_position ?? null,
                            'start' => $candidate->exp4_start_date_bs  ?? null,
                            'end' => $candidate->exp4_end_date_bs  ?? null,
                            'years' => $candidate->exp4_years ?? null,
                            'doc' => $candidate->exp4_document ?? null,
                        ],
                        [
                            'title' => 'Experience 5',
                            'org' => $candidate->exp5_organization ?? null,
                            'pos' => $candidate->exp5_position ?? null,
                            'start' => $candidate->exp5_start_date_bs ?? null,
                            'end' => $candidate->exp5_end_date_bs ?? null,
                            'years' => $candidate->exp5_years ?? null,
                            'doc' => $candidate->exp5_document ?? null,
                        ],
                        [
                            'title' => 'Experience 6',
                            'org' => $candidate->exp6_organization ?? null,
                            'pos' => $candidate->exp6_position ?? null,
                            'start' => $candidate->exp6_start_date_bs ?? null,
                            'end' => $candidate->exp6_end_date_bs ?? null,
                            'years' => $candidate->exp6_years ?? null,
                            'doc' => $candidate->exp6_document ?? null,
                        ],
                        [
                            'title' => 'Experience 7',
                            'org' => $candidate->exp7_organization ?? null,
                            'pos' => $candidate->exp7_position ?? null,
                            'start' => $candidate->exp7_start_date_bs ?? null,
                            'end' => $candidate->exp7_end_date_bs ?? null,
                            'years' => $candidate->exp7_years ?? null,
                            'doc' => $candidate->exp7_document ?? null,
                        ],
                        [
                            'title' => 'Experience 8',
                            'org' => $candidate->exp8_organization ?? null,
                            'pos' => $candidate->exp8_position ?? null,
                            'start' => $candidate->exp8_start_date_bs ?? null,
                            'end' => $candidate->exp8_end_date_bs ?? null,
                            'years' => $candidate->exp8_years ?? null,
                            'doc' => $candidate->exp8_document ?? null,
                        ],
                        [
                            'title' => 'Experience 9',
                            'org' => $candidate->exp9_organization ?? null,
                            'pos' => $candidate->exp9_position ?? null,
                            'start' => $candidate->exp9_start_date_bs ?? null,
                            'end' => $candidate->exp9_end_date_bs ?? null,
                            'years' => $candidate->exp9_years ?? null,
                            'doc' => $candidate->exp9_document ?? null,
                        ],
                        
                        [
                            'title' => 'Experience 10',
                            'org' => $candidate->exp10_organization ?? null,
                            'pos' => $candidate->exp10_position ?? null,
                            'start' => $candidate->exp10_start_date_bs ?? null,
                            'end' => $candidate->exp10_end_date_bs ?? null,
                            'years' => $candidate->exp10_years ?? null,
                            'doc' => $candidate->exp10_document ?? null,
                        ],
                    ];

                    $hasAnyExp = collect($exps)->contains(function ($e) {
                        return !empty($e['org']) || !empty($e['pos']) || !empty($e['start']) || !empty($e['end']) || !empty($e['years']) || !empty($e['doc']);
                    });
                @endphp

                @if($hasAnyExp)
                    @foreach($exps as $idx => $exp)
                        @php
                            $empty = empty($exp['org']) && empty($exp['pos']) && empty($exp['start']) && empty($exp['end']) && empty($exp['years']) && empty($exp['doc']);
                        @endphp
                        @continue($empty)

                        <div class="border rounded p-3 mb-3">
                            <h6 class="text-primary">{{ $exp['title'] }}</h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <strong>{{ __('candidate.organization') }}:</strong>
                                    <p>{{ $exp['org'] ?? '-' }}</p>
                                </div>
                                <div class="col-md-6">
                                    <strong>{{ __('candidate.position') }}:</strong>
                                    <p>{{ $exp['pos'] ?? '-' }}</p>
                                </div>
                                <div class="col-md-6">
                                    <strong>{{ __('candidate.start_date') }}:</strong>
                                    <p>{{ $exp['start'] ?? '-' }}</p>
                                </div>
                                <div class="col-md-6">
                                    <strong>{{ __('candidate.end_date') }}:</strong>
                                    <p>{{ $exp['end'] ?? '-' }}</p>
                                </div>
                                <div class="col-md-6">
                                    <strong>{{ __('candidate.years') }}:</strong>
                                    <p>{{ $exp['years'] ?? '-' }}</p>
                                </div>
                                <div class="col-md-6">
                                    <strong>{{ __('candidate.document') }}:</strong>
                                    <p>{!! showDoc($exp['doc']) !!}</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <p class="text-muted">{{ __('candidate.no_work_experience_records') }}</p>
                @endif

                <div class="d-flex justify-content-between">
                    <button type="button" class="btn btn-secondary prev-btn">Back</button>
                    <button type="button" class="btn btn-light next-btn">Next</button>
                </div>
            </div>

            {{-- STEP 6: Documents --}}
            <div class="step d-none" id="step6">
                <h5 class="mb-4 text-dark">Step 6 - {{ __('candidate.uploaded_documents') }}</h5>

                <div class="row mb-3">
                    <div class="col-md-6 mb-3">
                        <strong>{{ __('candidate.passport_size_photo') }}:</strong>
                        <p class="mb-0">{!! showDoc($candidate->passport_size_photo ?? null) !!}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>{{ __('candidate.citizenship_id_document') }}:</strong>
                        <p class="mb-0">{!! showDoc($candidate->citizenship_id_document ?? null) !!}</p>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6 mb-3">
                        <strong>{{ __('candidate.character_certificate') }}:</strong>
                        <p class="mb-0">{!! showDoc($candidate->character_certificate ?? ($candidate->character ?? null)) !!}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>{{ __('candidate.equivalency_certificate') }}:</strong>
                        <p class="mb-0">{!! showDoc($candidate->equivalency_certificate ?? ($candidate->equivalent ?? null)) !!}</p>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6 mb-3">
                        <strong>{{ __('candidate.signature') }}:</strong>
                        <p class="mb-0">{!! showDoc($candidate->signature ?? null) !!}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>{{ __('candidate.transcript_certificate') }}:</strong>
                        <p class="mb-0">{!! showDoc($candidate->transcript ?? null) !!}</p>
                    </div>
                </div>

                <div class="row mb-3">
                    
                    <div class="col-md-6 mb-3">
                        <strong>{{ __('candidate.noc_id_card') }}:</strong>
                        <p class="mb-0">{!! showDoc($candidate->noc_id_card ?? null) !!}</p>
                    </div>
                </div>

                <div class="d-flex justify-content-between">
                    <button type="button" class="btn btn-secondary prev-btn">Back</button>
                    <a href="{{ route('candidate.dashboard') }}" class="btn btn-danger">
                        <i class="fas fa-arrow-left"></i> Back to Dashboard
                    </a>
                </div>
            </div>

            {{-- STEP 7: Payment (kept for UI consistency) --}}
            <!-- <div class="step d-none" id="step7">
                <h5 class="mb-4 text-dark">Step 7 — Payment Details</h5>

                <div class="alert alert-info py-2">
                    {{ __('candidate.payment_not_part_profile') }}
                    Please check “My Applications” to view payment details for a submitted application.
                </div>

                <hr class="my-4">

                <h6 class="mb-3 text-secondary">Profile Status</h6>
                <div class="row mb-3">
                    <div class="col-md-6 mb-3">
                        <strong>{{ __('candidate.profile_id') }}:</strong>
                        <p class="mb-0">{{ $candidate->id ?? '-' }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>{{ __('candidate.updated_at') }}:</strong>
                        <p class="mb-0">
                            @if(!empty($candidate?->updated_at))
                                {{ is_string($candidate->updated_at) ? \Carbon\Carbon::parse($candidate->updated_at)->format('F d, Y h:i A') : $candidate->updated_at->format('F d, Y h:i A') }}
                            @else
                                -
                            @endif
                        </p>
                    </div>
                </div>

                <div class="d-flex justify-content-between">
                    <button type="button" class="btn btn-secondary prev-btn">Back</button>
                    <a href="{{ route('candidate.dashboard') }}" class="btn btn-danger">
                        <i class="fas fa-arrow-left"></i> Back to Dashboard
                    </a>
                </div>
            </div> -->

        </div>
    </div>
</div>

@push('styles')
<style>
    /* ===== CLICKABLE TABS STYLING ===== */
    .step-tabs { position: relative; margin-bottom: 2.5rem; }
    .step-tabs .d-flex { gap: 10px; overflow-x: auto; padding-bottom: 10px; }

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
    .tab-item.completed .tab-circle { background: #000000; color: white; }

    .tab-item.active .tab-label,
    .tab-item.completed .tab-label { color: #000000; font-weight: 600; }

    .tab-item:hover .tab-circle { background: #000000; color: white; }
    .tab-item:hover .tab-label { color: #000000; }

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

    @media (max-width: 768px) {
        .tab-label { font-size: 0.8rem; }
        .tab-item { padding: 12px 4px; }
        .tab-circle { width: 35px; height: 35px; font-size: 1rem; }
    }

    .card-body strong { color: #495057; font-weight: 600; }

    .card-body p {
        color: #212529;
        padding: 0.5rem;
        background-color: #f8f9fa;
        border-radius: 0.25rem;
        margin-bottom: 0 !important;
    }

    .border-bottom { border-bottom: 2px solid #dee2e6 !important; }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    let currentStep = 1;
    const totalSteps = 6;

    function updateTabsAndProgress() {
        document.querySelectorAll('.tab-item').forEach((tab, index) => {
            const stepNum = index + 1;
            tab.classList.remove('active', 'completed');
            if (stepNum < currentStep) tab.classList.add('completed');
            else if (stepNum === currentStep) tab.classList.add('active');
        });
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

    document.querySelectorAll('.tab-item').forEach(tab => {
        tab.addEventListener('click', () => {
            const targetStep = parseInt(tab.getAttribute('data-step'));
            showStep(targetStep);
        });
    });

    document.querySelectorAll('.next-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            if (currentStep < totalSteps) showStep(currentStep + 1);
        });
    });

    document.querySelectorAll('.prev-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            if (currentStep > 1) showStep(currentStep - 1);
        });
    });

    showStep(1);
});
// Age and date 
(function () {

    function calculateExactAge(dateString) {

        if (!dateString) return '';

        const birthDate = new Date(dateString);

        if (isNaN(birthDate.getTime())) return '';

        const today = new Date();

        let years = today.getFullYear() - birthDate.getFullYear();
        let months = today.getMonth() - birthDate.getMonth();
        let days = today.getDate() - birthDate.getDate();

        if (days < 0) {

            months--;

            const lastMonth = new Date(
                today.getFullYear(),
                today.getMonth(),
                0
            );

            days += lastMonth.getDate();
        }

        if (months < 0) {
            years--;
            months += 12;
        }

        return `${years} years ${months} months ${days} days`;
    }

    function updateAge(dateValue) {

        const ageField = document.getElementById('age');

        if (!ageField) return;

        ageField.value = calculateExactAge(dateValue);
    }

    document.addEventListener('DOMContentLoaded', function () {

        const adField = document.getElementById('birth_date_ad');
        const bsField = document.getElementById('birth_date_bs');

        if (adField) {

            adField.addEventListener('change', function () {
                updateAge(this.value);
            });

            adField.addEventListener('input', function () {
                updateAge(this.value);
            });
        }

        if (bsField) {

            bsField.addEventListener('change', function () {

                if (typeof window.bsToAD === 'function') {

                    const adDate = window.bsToAD(this.value);

                    if (adDate) {

                        if (adField) {
                            adField.value = adDate;
                            const adDisp = document.getElementById('birth_date_ad_display');
                            if (adDisp && window.formatADDisplay) adDisp.value = window.formatADDisplay(adDate);
                        }

                        updateAge(adDate);
                    }
                }
            });
        }

        // On page load: if BS already has a value but AD is empty, auto-convert
        if (bsField && bsField.value && adField && !adField.value) {
            if (typeof window.bsToAD === 'function') {
                const adDate = window.bsToAD(bsField.value);
                if (adDate) {
                    adField.value = adDate;
                    const adDisp = document.getElementById('birth_date_ad_display');
                    if (adDisp && window.formatADDisplay) adDisp.value = window.formatADDisplay(adDate);
                    updateAge(adDate);
                }
            }
        }

        // Initial age calculation if AD already has a value
        if (adField && adField.value) {
            updateAge(adField.value);
        }
    });

})();
</script>
@endpush
@endsection
