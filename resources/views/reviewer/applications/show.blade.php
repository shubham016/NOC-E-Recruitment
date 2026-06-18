@extends('layouts.reviewer')

@section('title', 'Review Application')

@section('portal-name', 'Reviewer Portal')
@section('brand-icon', 'bi bi-clipboard-check')
@section('dashboard-route', route('reviewer.dashboard'))
@section('user-name', Auth::guard('reviewer')->user()->name)
@section('user-role', 'Application Reviewer')
@section('user-initial', strtoupper(substr(Auth::guard('reviewer')->user()->name, 0, 1)))
@section('logout-route', route('reviewer.logout'))

@section('sidebar-menu')
<a href="{{ route('reviewer.dashboard') }}" class="sidebar-menu-item">
    <i class="bi bi-speedometer2"></i>
    <span>{{ __('reviewer.dashboard') }}</span>
</a>
<a href="{{ route('reviewer.applications.index') }}" class="sidebar-menu-item active">
    <i class="bi bi-inbox"></i>
    <span>{{ __('reviewer.assigned_to_me') }}</span>
</a>
<a href="{{ route('reviewer.myprofile') }}" class="sidebar-menu-item">
    <i class="bi bi-person"></i>
    <span>{{ __('reviewer.my_profile') }}</span>
</a>
<a href="{{ route('reviewer.notifications.index') }}" class="sidebar-menu-item">
    <i class="bi bi-bell"></i>
    <span>{{ __('reviewer.notifications') }}</span>
</a>
@endsection

@section('custom-styles')
<style>
    .review-header {
        background: linear-gradient(135deg, #16315c 0%, #1a3a6b 100%);
        border-radius: 12px;
        padding: 2rem 2.5rem;
        color: white;
        margin-bottom: 2rem;
        box-shadow: 0 4px 12px rgba(100, 116, 139, 0.3);
    }

    .candidate-photo-section {
        display: flex;
        align-items: center;
        gap: 1.5rem;
        margin-bottom: 1.5rem;
        padding: 1.5rem;
        background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
        border-radius: 12px;
        border: 2px solid #cbd5e1;
    }

    .candidate-photo {
        width: 150px;
        height: 150px;
        object-fit: cover;
        border-radius: 10px;
        border: 3px solid white;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    .candidate-basic-info h3 {
        margin: 0;
        color: #1e293b;
        font-weight: 700;
    }

    .candidate-basic-info .detail {
        margin: 0.3rem 0;
        color: #475569;
    }

    .info-card {
        background: white;
        border-radius: 10px;
        padding: 1.5rem;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        border: 1px solid #e5e7eb;
        margin-bottom: 1.5rem;
    }

    .info-card h5 {
        color: #64748b;
        font-weight: 700;
        border-bottom: 2px solid #e5e7eb;
        padding-bottom: 0.75rem;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .info-row {
        display: flex;
        padding: 0.75rem 0;
        border-bottom: 1px solid #f3f4f6;
    }

    .info-row:last-child {
        border-bottom: none;
    }

    .info-label {
        font-weight: 600;
        color: #6b7280;
        min-width: 180px;
        flex-shrink: 0;
    }

    .info-value {
        color: #1f2937;
        flex: 1;
    }

    .review-actions {
        position: sticky;
        top: 20px;
    }

    .status-badge {
        background: rgba(255, 255, 255, 0.15);
        padding: 0.3rem 1rem;
        border-radius: 6px;
        font-weight: 600;
        display: inline-block;
    }

    .priority-badge {
        padding: 0.4rem 0.8rem;
        border-radius: 6px;
        font-size: 0.85rem;
        font-weight: 600;
    }

    .document-item {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 1rem;
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        border-radius: 8px;
        border: 1px solid #e2e8f0;
        margin-bottom: 0.75rem;
        transition: all 0.2s;
    }

    .document-item:hover {
        background: linear-gradient(135deg, #e2e8f0 0%, #cbd5e1 100%);
        transform: translateX(5px);
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .document-info {
        flex: 1;
    }

    .document-name {
        font-weight: 600;
        color: #1e293b;
        margin: 0;
    }

    .document-size {
        font-size: 0.85rem;
        color: #64748b;
        margin: 0;
    }

    .alert-info-custom {
        background: linear-gradient(135deg, #fedbdb 0%, #febfbf 100%);
        border-left: 4px solid #fa0000;
        padding: 1rem;
        border-radius: 8px;
        margin-bottom: 1rem;
    }

    .section-divider {
        height: 2px;
        background: linear-gradient(to right, #e5e7eb 0%, #1a3a6b 50%, #e5e7eb 100%);
        margin: 2rem 0;
    }

    @media print {

        .review-actions,
        .no-print {
            display: none !important;
        }
    }
</style>
@endsection

@section('content')
<div class="container-fluid px-4 py-4">

    {{-- ═══════════════════════════════════════════
         PAGE HEADER
    ═══════════════════════════════════════════ --}}
    <div class="review-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <a href="{{ route('reviewer.applications.index') }}"
                    class="text-white text-decoration-none mb-2 d-inline-block opacity-75 no-print">
                    <i class="bi bi-arrow-left me-2"></i>{{ __('reviewer.back_to_applications') }}
                </a>
                <h2 class="mb-0 fw-bold">{{ __('reviewer.application_review') }}</h2>
            </div>
            <div class="text-end">
                <span class="status-badge fs-5 mb-2 d-inline-block">
                    {{ ucfirst($application->status) }}
                </span>
                @if($application->manual_priority)
                <span class="priority-badge d-block mt-1 {{ $priorityColors[$application->manual_priority] ?? 'bg-secondary text-white' }}">
                    Priority: {{ ucfirst($application->manual_priority) }}
                </span>
                @endif
            </div>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════
         MAIN CONTENT COLUMN
    ═══════════════════════════════════════════ --}}
    <div class="row">
        <div class="col-lg-12">

            {{-- Candidate Photo & Basic Info --}}
            <div class="candidate-photo-section">
                 @php
        $photo = $application->passport_size_photo
            ?? optional($application->candidateRegistration)->passport_size_photo;
    @endphp

    @if($photo)
        <img src="{{ Storage::url($photo) }}"
             alt="Candidate Photo"
             class="candidate-photo">
    @else
        <div class="candidate-photo d-flex align-items-center justify-content-center bg-secondary text-white">
            <i class="bi bi-person" style="font-size: 4rem;"></i>
        </div>
    @endif
                <div class="candidate-basic-info">
                    <h3>{{ $application->name_english ?? 'N/A' }}</h3>
                    <p class="detail"><strong>{{ $application->name_nepali ?? '' }}</strong></p>
                    <p class="mb-1 opacity-90">{{ __('reviewer.application_id') }}: {{ $application->id }}</p>
                    <p class="detail">{{ $application->email ?? 'N/A' }}</p>
                    <p class="detail">
                        {{ $application->phone ?? 'N/A' }}
                        @if($application->alternate_phone_number)
                        | {{ $application->alternate_phone_number }}
                        @endif
                    </p>
                    <p class="detail">{{ $application->permanent_municipality }}, {{ $application->permanent_district }}</p>
                    <p class="mb-0 opacity-75">
                        @php $submittedDate = $application->submitted_at ?: $application->created_at; @endphp
                        Submitted: {{ $submittedDate ? adToBS($submittedDate) . ' BS, ' . \Carbon\Carbon::parse($submittedDate)->format('h:i A') : 'N/A' }}
                    </p>
                </div>
            </div>
            {{-- /Candidate Photo --}}

            {{-- ── Vacancy Information ── --}}
            <div class="info-card">
                <h5>{{ __('reviewer.vacancy_information') }}</h5>
                <div class="row">
                    <div class="col-md-6">
                        <div class="info-row">
                            <div class="info-label">{{ __('reviewer.position_applied') }}:</div>
                            <div class="info-value">{{ $application->jobPosting->title ?? $application->applying_position ?? 'N/A' }}</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">{{ __('reviewer.advertisement_no') }}:</div>
                            <div class="info-value">{{ $application->advertisement_no ?? 'N/A' }}</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">{{ __('reviewer.department') }}:</div>
                            <div class="info-value">{{ $application->jobPosting->department ?? $application->department ?? 'N/A' }}</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-row">
                            <div class="info-label">{{ __('reviewer.category') }}:</div>
                            <div class="info-value">
                                @if(!empty($application->applied_category))
                                {{ implode(', ', $application->applied_category) }}
                                @else
                                N/A
                                @endif
                            </div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">{{ __('reviewer.application_deadline') }}:</div>
                            <div class="info-value">
                                @if($application->jobPosting->deadline)
                                @php
                                $deadlineBS = $application->jobPosting->deadline_bs
                                ?: adToBS($application->jobPosting->deadline->format('Y-m-d'));
                                @endphp
                                <span class="d-block">{{ $deadlineBS }} (BS)</span>
                                <span class="text-muted d-block">{{ $application->jobPosting->deadline->format('F d, Y') }} (AD)</span>
                                @else
                                N/A
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {{-- /Vacancy Information --}}

            {{-- ── Personal Information ── --}}
            <div class="info-card">
                <h5>{{ __('reviewer.personal_information') }}</h5>
                <div class="row">
                    <div class="col-md-6">
                        <div class="info-row">
                            <div class="info-label">{{ __('reviewer.name_english') }}:</div>
                            <div class="info-value">{{ $application->name_english ?? 'N/A' }}</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">{{ __('reviewer.name_nepali') }}:</div>
                            <div class="info-value">{{ $application->name_nepali ?? 'N/A' }}</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">{{ __('reviewer.birth_date_ad') }}:</div>
                            <div class="info-value">{{ $application->birth_date_ad ? $application->birth_date_ad->format('Y-m-d') : 'N/A' }}</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">{{ __('reviewer.birth_date_bs') }}:</div>
                            <div class="info-value">{{ $application->birth_date_bs ?? 'N/A' }}</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">{{ __('reviewer.email') }}:</div>
                            <div class="info-value">{{ $application->email ?? 'N/A' }}</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">{{ __('reviewer.age') }}:</div>
                            <div class="info-value">{{ $application->age ?? 'N/A' }} years</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">{{ __('reviewer.alternate_phone_number') }}:</div>
                            <div class="info-value">{{ $application->alternate_phone_number ?? 'N/A' }}</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-row">
                            <div class="info-label">{{ __('reviewer.gender') }}:</div>
                            <div class="info-value">{{ ucfirst($application->gender ?? 'N/A') }}</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">{{ __('reviewer.marital_status') }}:</div>
                            <div class="info-value">{{ ucfirst($application->marital_status ?? 'N/A') }}</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">{{ __('reviewer.spouse_name') }}:</div>
                            <div class="info-value">{{ $application->spouse_name_english ?? 'N/A' }}</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">{{ __('reviewer.spouse_nationality') }}:</div>
                            <div class="info-value">{{ $application->spouse_nationality ?? 'N/A' }}</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">{{ __('reviewer.blood_group') }}:</div>
                            <div class="info-value">{{ $application->blood_group ?? 'N/A' }}</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">{{ __('reviewer.nationality') }}:</div>
                            <div class="info-value">{{ $application->nationality ?? 'Nepali' }}</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">{{ __('reviewer.mother_tongue') }}:</div>
                            <div class="info-value">{{ $application->mother_tongue ?? 'N/A' }}</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">{{ __('reviewer.religion') }}:</div>
                            <div class="info-value">
                                {{ $application->religion == 'other' ? $application->religion_other : ucfirst($application->religion ?? 'N/A') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {{-- /Personal Information --}}

            {{-- ── Citizenship Information ── --}}
            <div class="info-card">
                <h5>{{ __('reviewer.citizenship_information') }}</h5>
                <div class="row">
                    <div class="col-md-6">
                        <div class="info-row">
                            <div class="info-label">{{ __('reviewer.citizenship_number') }}:</div>
                            <div class="info-value">{{ $application->citizenship_number ?? 'N/A' }}</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">{{ __('reviewer.citizenship_issue_district') }}:</div>
                            <div class="info-value">{{ $application->citizenship_issue_district ?? 'N/A' }}</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-row">
                            <div class="info-label">{{ __('reviewer.citizenship_issue_date_bs') }}:</div>
                            <div class="info-value">{{ $application->citizenship_issue_date_bs ?? 'N/A' }}</div>
                        </div>
                    </div>
                </div>
                @if($application->citizenship_id_document)
                <div class="document-item">
                    <div class="document-info">
                        <p class="document-name">{{ __('reviewer.citizenship_id') }}</p>
                        <img src="{{ Storage::url($application->citizenship_id_document) }}"
                            style="width:100%; max-height:520px; object-fit:contain; border:1px solid #ddd; border-radius:8px; margin-top:8px;">
                    </div>
                </div>
                @endif
            </div>
            {{-- /Citizenship Information --}}

            {{-- ── Community & Ethnic Information ── --}}
            <div class="info-card">
                <h5>{{ __('reviewer.community_ethnic_information') }}</h5>
                <div class="row">
                    <div class="col-md-6">
                        <div class="info-row">
                            <div class="info-label">{{ __('reviewer.religion') }}:</div>
                            <div class="info-value">
                                {{ $application->religion == 'other' ? $application->religion_other : ucfirst($application->religion ?? 'N/A') }}
                            </div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">{{ __('reviewer.community') }}:</div>
                            <div class="info-value">
                                {{ $application->community == 'other' ? $application->community_other : ucfirst($application->community ?? 'N/A') }}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        @if($application->ethnic_certificate)
                        <div class="info-row">
                            <div class="info-label">{{ __('reviewer.ethnic_certificate') }}:</div>
                            <div class="info-value">
                                <a href="{{ Storage::url($application->ethnic_certificate) }}" target="_blank" class="btn btn-sm btn-outline-dark">
                                    {{ __('reviewer.view_certificate') }}
                                </a>
                            </div>
                        </div>
                        @endif
                        <div class="info-row">
                            <div class="info-label">{{ __('reviewer.mother_tongue') }}:</div>
                            <div class="info-value">{{ $application->mother_tongue ?? 'N/A' }}</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">{{ __('reviewer.ethnic_group') }}:</div>
                            <div class="info-value">
                                {{ $application->ethnic_group == 'other' ? $application->ethnic_group_other : ucfirst($application->ethnic_group ?? 'N/A') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {{-- /Community & Ethnic --}}

            {{-- ── Disability & Employment ── --}}
            <div class="info-card">
                <h5>{{ __('reviewer.employment_disability_status') }}</h5>
                <div class="row">
                    <div class="col-md-6">
                        <div class="info-row">
                            <div class="info-label">{{ __('reviewer.employment_status') }}:</div>
                            <div class="info-value">
                                {{ $application->employment_status == 'other' ? $application->employment_other : ucfirst($application->employment_status ?? 'N/A') }}
                            </div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">{{ __('reviewer.physical_disability') }}:</div>
                            <div class="info-value">
                                {{ $application->physical_disability == 'other' ? $application->disability_other : ucfirst($application->physical_disability ?? 'None') }}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        @if($application->disability_certificate)
                        <div class="info-row">
                            <div class="info-label">{{ __('reviewer.disability_certificate') }}:</div>
                            <div class="info-value">
                                <a href="{{ Storage::url($application->disability_certificate) }}" target="_blank" class="btn btn-sm btn-outline-dark">
                                    {{ __('reviewer.view_certificate') }}
                                </a>
                            </div>
                        </div>
                        @endif
                        <div class="info-row">
                            <div class="info-label">{{ __('reviewer.noc_employee') }}:</div>
                            <div class="info-value">
                                {{ $application->noc_employee == 'yes' ? 'Yes' : 'No' }}
                                @if($application->noc_employee == 'yes' && $application->noc_id_card)
                                <span class="badge bg-info ms-2">ID: {{ $application->noc_id_card }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {{-- /Disability & Employment --}}

            {{-- ── Family Information ── --}}
            <div class="info-card">
                <h5>{{ __('reviewer.family_information') }}</h5>
                <div class="row">
                    <div class="col-md-6">
                        <div class="info-row">
                            <div class="info-label text-dark">{{ __('reviewer.grandfather_name') }}:</div>
                            <div class="info-value">{{ $application->grandfather_name_english ?? 'N/A' }}</div>
                        </div>
                        <h6 class="text-dark mt-3 mb-2">{{ __('reviewer.father_information') }}</h6>
                        <div class="info-row">
                            <div class="info-label">{{ __('reviewer.father_name_english') }}:</div>
                            <div class="info-value">{{ $application->father_name_english ?? 'N/A' }}</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">{{ __('reviewer.father_name_nepali') }}:</div>
                            <div class="info-value">{{ $application->father_name_nepali ?? 'N/A' }}</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">{{ __('reviewer.father_qualification') }}:</div>
                            <div class="info-value">{{ $application->father_qualification ?? 'N/A' }}</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-dark mt-3 mb-2">{{ __('reviewer.mother_information') }}</h6>
                        <div class="info-row">
                            <div class="info-label">{{ __('reviewer.mother_name_english') }}:</div>
                            <div class="info-value">{{ $application->mother_name_english ?? 'N/A' }}</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">{{ __('reviewer.mother_name_nepali') }}:</div>
                            <div class="info-value">{{ $application->mother_name_nepali ?? 'N/A' }}</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">{{ __('reviewer.mother_qualification') }}:</div>
                            <div class="info-value">{{ $application->mother_qualification ?? 'N/A' }}</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">{{ __('reviewer.parent_occupation') }}:</div>
                            <div class="info-value">
                                {{ $application->parent_occupation == 'other' ? $application->parent_occupation_other : ucfirst($application->parent_occupation ?? 'N/A') }}
                            </div>
                        </div>
                        @if($application->marital_status == 'married')
                        <h6 class="text-dark mt-3 mb-2">{{ __('reviewer.spouse_information') }}</h6>
                        <div class="info-row">
                            <div class="info-label">{{ __('reviewer.spouse_name_english') }}:</div>
                            <div class="info-value">{{ $application->spouse_name_english ?? 'N/A' }}</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">{{ __('reviewer.spouse_name_nepali') }}:</div>
                            <div class="info-value">{{ $application->spouse_name_nepali ?? 'N/A' }}</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">{{ __('reviewer.spouse_nationality') }}:</div>
                            <div class="info-value">{{ $application->spouse_nationality ?? 'N/A' }}</div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            {{-- /Family Information --}}

            {{-- ── Address Information ── --}}
            <div class="info-card">
                <h5>{{ __('reviewer.address_information') }}</h5>
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="text-dark mt-2 mb-2">{{ __('reviewer.permanent_address') }}</h6>
                        <div class="info-row">
                            <div class="info-label">{{ __('reviewer.province') }}:</div>
                            <div class="info-value">{{ $application->permanent_province ?? 'N/A' }}</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">{{ __('reviewer.district') }}:</div>
                            <div class="info-value">{{ $application->permanent_district ?? 'N/A' }}</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">{{ __('reviewer.municipality') }}:</div>
                            <div class="info-value">{{ $application->permanent_municipality ?? 'N/A' }}</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">{{ __('reviewer.ward_no') }}:</div>
                            <div class="info-value">{{ $application->permanent_ward ?? 'N/A' }}</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">{{ __('reviewer.tole_street') }}:</div>
                            <div class="info-value">{{ $application->permanent_tole ?? 'N/A' }}</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">{{ __('reviewer.house_number') }}:</div>
                            <div class="info-value">{{ $application->permanent_house_number ?? 'N/A' }}</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-dark mt-3 mb-2">{{ __('reviewer.mailing_temporary_address') }}</h6>
                        @if($application->same_as_permanent == 'yes')
                        <div class="alert alert-primary">{{ __('reviewer.same_as_permanent') }}</div>
                        @else
                        <div class="info-row">
                            <div class="info-label">{{ __('reviewer.province') }}:</div>
                            <div class="info-value">{{ $application->mailing_province ?? 'N/A' }}</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">{{ __('reviewer.district') }}:</div>
                            <div class="info-value">{{ $application->mailing_district ?? 'N/A' }}</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">{{ __('reviewer.municipality') }}:</div>
                            <div class="info-value">{{ $application->mailing_municipality ?? 'N/A' }}</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">{{ __('reviewer.ward_no') }}:</div>
                            <div class="info-value">{{ $application->mailing_ward ?? 'N/A' }}</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">{{ __('reviewer.tole_street') }}:</div>
                            <div class="info-value">{{ $application->mailing_tole ?? 'N/A' }}</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">{{ __('reviewer.house_number') }}:</div>
                            <div class="info-value">{{ $application->mailing_house_number ?? 'N/A' }}</div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            {{-- /Address Information --}}

            {{-- ── Educational Background ── --}}
            <div class="info-card">
                <h5>{{ __('reviewer.educational_background') }}</h5>
                <div class="row">
                    <div class="col-md-6">
                        <div class="info-row">
                            <div class="info-label">{{ __('reviewer.education_level') }}:</div>
                            <div class="info-value">{{ $application->education_level ?? 'N/A' }}</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">{{ __('reviewer.field_of_study') }}:</div>
                            <div class="info-value">{{ $application->field_of_study ?? 'N/A' }}</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-row">
                            <div class="info-label">{{ __('reviewer.institution') }}:</div>
                            <div class="info-value">{{ $application->institution_name ?? 'N/A' }}</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">{{ __('reviewer.graduation_year') }}:</div>
                            <div class="info-value">{{ $application->graduation_year ?? 'N/A' }}</div>
                        </div>
                    </div>
                </div>
                @if($application->transcript)
                <div class="document-item mt-3">
                    <div class="document-info">
                        <p class="document-name">{{ __('reviewer.educational_certificates') }}</p>
                        <p class="document-size">{{ __('reviewer.academic_transcripts') }}</p>
                        <img src="{{ Storage::url($application->transcript) }}"
                            style="width:100%; max-height:520px; object-fit:contain; border:1px solid #ddd; border-radius:8px; margin-top:8px;">
                    </div>
                </div>
                @endif
                @if($application->character)
                <div class="document-item">
                    <div class="document-info">
                        <p class="document-name">{{ __('reviewer.character_certificate') }}</p>
                        <img src="{{ Storage::url($application->character) }}"
                            style="width:100%; max-height:520px; object-fit:contain; border:1px solid #ddd; border-radius:8px; margin-top:8px;">
                    </div>
                </div>
                @endif
                @if($application->equivalency_certificate)
                <div class="document-item">
                    <div class="document-info">
                        <p class="document-name">{{ __('reviewer.equivalency_certificate') }}</p>
                        <p class="document-size">{{ __('reviewer.equivalency_certificate_description') }}</p>
                        <img src="{{ Storage::url($application->equivalency_certificate) }}"
                            style="width:100%; max-height:520px; object-fit:contain; border:1px solid #ddd; border-radius:8px; margin-top:8px;">
                    </div>
                </div>
                @endif
            </div>
            {{-- /Educational Background --}}

            {{-- ── Work Experience ── --}}
            <div class="info-card">
                <h5>{{ __('reviewer.work_experience') }}</h5>
                @if(strtolower($application->has_work_experience ?? '') == 'yes')
                <div class="mb-3">
                    <strong>{{ __('reviewer.has_work_experience') }}:</strong>
                    <p class="mb-0">{{ ucfirst($application->has_work_experience ?? '-') }}</p>
                </div>
                @forelse($application->experiences as $exp)
                <div class="border rounded p-3 mb-3">
                    <h6 class="text-primary">Experience {{ $exp->exp_number }}</h6>
                    <div class="row">
                        <div class="col-md-6">
                            <strong>{{ __('reviewer.organization') }}:</strong>
                            <p>{{ $exp->organization ?? '-' }}</p>
                        </div>
                        <div class="col-md-6">
                            <strong>{{ __('reviewer.position') }}:</strong>
                            <p>{{ $exp->position ?? '-' }}</p>
                        </div>
                        <div class="col-md-6">
                            <strong>{{ __('reviewer.start_date') }} (B.S):</strong>
                            <p>{{ $exp->start_date_bs ?? '-' }}</p>
                        </div>
                        <div class="col-md-6">
                            <strong>{{ __('reviewer.end_date') }} (B.S):</strong>
                            <p>{{ $exp->end_date_bs ?? '-' }}</p>
                        </div>
                        <div class="col-md-6">
                            <strong>{{ __('reviewer.years') }}:</strong>
                            <p>{{ $exp->years ?? '-' }}</p>
                        </div>
                    </div>
                    <div>
                        <strong>{{ __('reviewer.document') }}:</strong>
                        @if($exp->document)
                        @php $ext = strtolower(pathinfo($exp->document, PATHINFO_EXTENSION)); @endphp
                        @if(in_array($ext, ['jpg','jpeg','png','webp']))
                        <img src="{{ Storage::url($exp->document) }}"
                            style="width:100%; max-height:520px; object-fit:contain; border:1px solid #ddd; border-radius:8px; margin-top:8px;">
                        @else
                        <a href="{{ Storage::url($exp->document) }}" target="_blank" class="btn btn-sm btn-outline-secondary mt-2">View Document</a>
                        @endif
                        @else
                        <div class="alert alert-primary mt-2">{{ __('reviewer.no_document_uploaded') }}</div>
                        @endif
                    </div>
                </div>
                @empty
                <div class="alert alert-success">{{ __('reviewer.no_experience_records') }}</div>
                @endforelse
                @else
                <div class="alert alert-primary">{{ __('reviewer.no_work_experience') }}</div>
                @endif
            </div>
            {{-- /Work Experience --}}

            {{-- ── Uploaded Documents ── --}}
            <div class="info-card">
                <h5>{{ __('reviewer.uploaded_documents') }}</h5>
                @if($application->passport_size_photo)
                <div class="document-item">
                    <div class="document-info">
                        <p class="document-name">{{ __('reviewer.passport_size_photo') }}</p>
                        <img src="{{ Storage::url($application->passport_size_photo) }}"
                            style="width:100%; max-height:520px; object-fit:contain; border:1px solid #ddd; border-radius:8px; margin-top:8px;">
                    </div>
                </div>
                @endif
                @if($application->signature)
                <div class="document-item">
                    <div class="document-info">
                        <p class="document-name">{{ __('reviewer.signature') }}</p>
                        <img src="{{ Storage::url($application->signature) }}"
                            style="width:100%; max-height:520px; object-fit:contain; border:1px solid #ddd; border-radius:8px; margin-top:8px;">
                    </div>
                </div>
                @endif
                @if($application->ethnic_certificate)
                <div class="document-item">
                    <div class="document-info">
                        <p class="document-name">{{ __('reviewer.ethnic_certificate') }}</p>
                        <img src="{{ Storage::url($application->ethnic_certificate) }}"
                            style="width:100%; max-height:520px; object-fit:contain; border:1px solid #ddd; border-radius:8px; margin-top:8px;">
                    </div>
                </div>
                @endif
                @if($application->disability_certificate)
                <div class="document-item">
                    <div class="document-info">
                        <p class="document-name">{{ __('reviewer.disability_certificate') }}</p>
                        <img src="{{ Storage::url($application->disability_certificate) }}"
                            style="width:100%; max-height:520px; object-fit:contain; border:1px solid #ddd; border-radius:8px; margin-top:8px;">
                    </div>
                </div>
                @endif
                @if($application->noc_id_card)
                <div class="document-item">
                    <div class="document-info">
                        <p class="document-name">{{ __('reviewer.noc_id_card') }}</p>
                        <img src="{{ Storage::url($application->noc_id_card) }}"
                            style="width:100%; max-height:520px; object-fit:contain; border:1px solid #ddd; border-radius:8px; margin-top:8px;">
                    </div>
                </div>
                @endif
                @if(
                !$application->passport_size_photo && !$application->resume &&
                !$application->work_experience && !$application->citizenship_certificate &&
                !$application->citizenship_id_document && !$application->educational_certificates &&
                !$application->transcript && !$application->experience_certificates &&
                !$application->character_certificate && !$application->character &&
                !$application->equivalency_certificate && !$application->equivalent &&
                !$application->ethnic_certificate && !$application->disability_certificate &&
                !$application->noc_id_card && !$application->cover_letter_file &&
                !$application->signature && !$application->other_documents
                )
                <div class="alert alert-warning">{{ __('reviewer.no_documents_uploaded') }}</div>
                @endif
            </div>
            {{-- /Uploaded Documents --}}

            {{-- ── Admin Notes ── --}}
            @if($application->admin_notes)
            <div class="info-card">
                <h5>{{ __('reviewer.admin_notes') }}</h5>
                <div class="alert alert-info-custom">
                    <p class="mb-0"><strong>{{ __('reviewer.admins_note') }}:</strong></p>
                    <p class="mb-0 mt-2">{{ $application->admin_notes }}</p>
                </div>
            </div>
            @endif

            {{-- ── Priority Note ── --}}
            @if($application->priority_note)
            <div class="info-card">
                <h5>{{ __('reviewer.priority_note') }}</h5>
                <div class="alert" style="background: #fef3c7; border-left: 4px solid #f59e0b;">
                    <p class="mb-0">{{ $application->priority_note }}</p>
                </div>
            </div>
            @endif

            {{-- ── Payment ── --}}
            @php $payment = \App\Models\Payment::where('draft_id', $application->id)->first(); @endphp
            @if($payment)
            <div class="info-card">
                <h5>{{ __('reviewer.payment_information') }}</h5>
                <div class="row">
                    <div class="col-md-6">
                        <div class="info-row">
                            <div class="info-label">{{ __('reviewer.amount') }}:</div>
                            <div class="info-value">NPR {{ number_format($payment->amount, 2) }}</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">{{ __('reviewer.status') }}:</div>
                            <div class="info-value">{{ ucfirst($payment->status) }}</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-row">
                            <div class="info-label">{{ __('reviewer.payment_gateway') }}:</div>
                            <div class="info-value">{{ ucfirst($payment->gateway) }}</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">{{ __('reviewer.transaction_id') }}:</div>
                            <div class="info-value">{{ $payment->transaction_id }}</div>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            {{-- ── Review Action Form ── --}}
            <div class="info-card no-print">
                <h5>{{ __('reviewer.review_action') }}</h5>
                <form action="{{ route('reviewer.applications.updateStatus', $application->id) }}" method="POST" id="reviewForm">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-bold">{{ __('reviewer.action') }} <span class="text-danger">*</span></label>
                        <select name="status" class="form-select" id="reviewStatus" required>
                            <option value="">{{ __('reviewer.select_action') }}</option>
                            <option value="reviewed" {{ $application->status == 'reviewed' ? 'selected' : '' }}>{{ __('reviewer.mark_as_reviewed') }}</option>
                            <option value="edit" {{ $application->status == 'edit' ? 'selected' : '' }}>{{ __('reviewer.send_back_for_edit') }}</option>
                            <option value="rejected" {{ $application->status == 'rejected' ? 'selected' : '' }}>{{ __('reviewer.reject_application') }}</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">
                            <span id="notesLabel">{{ __('reviewer.comments_notes') }}</span>
                            <span class="text-danger">*</span>
                        </label>
                        <textarea name="reviewer_notes" class="form-control" rows="6"
                            id="reviewerNotes"
                            placeholder="{{ __('reviewer.remarks_placeholder') }}"
                            required>{{ $application->reviewer_notes }}</textarea>
                        <small class="text-muted" id="notesHelp">{{ __('reviewer.please_provide_detailed_feedback') }}</small>
                    </div>
                    <div id="smsPreview" style="display:none;" class="mb-3">
                        <div class="alert alert-warning" style="background:linear-gradient(135deg,#fef3c7 0%,#fde68a 100%);border-left:4px solid #f59e0b;">
                            <strong>{{ __('reviewer.sms_notification_preview') }}:</strong>
                            <p class="mt-2 mb-0 small" style="font-family:monospace;background:white;padding:0.75rem;border-radius:6px;">
                                <strong>{{ __('reviewer.nepal_oil_corporation') }}</strong><br>
                                {{ __('reviewer.your_application_has_been_rejected') }}<br><br>
                                <strong>{{ __('reviewer.reason') }}:</strong>
                                <span id="smsReasonPreview">[Your rejection reason will appear here]</span><br><br>
                                {{ __('reviewer.please_review_and_reapply') }}<br>
                                - NOC E-Recruitment
                            </p>
                            <small class="text-muted mt-2 d-block">{{ __('reviewer.sms_will_be_sent_to') }} <strong>{{ $application->phone ?? 'N/A' }}</strong></small>
                        </div>
                    </div>
                    <div class="d-grid gap-2">
                        <button type="submit" id="submitBtn" class="btn btn-lg" style="background:#64748b;color:white;">
                            {{ __('reviewer.submit_action') }}
                        </button>
                        <button type="button" class="btn btn-outline-secondary" onclick="window.print()">
                            {{ __('reviewer.print_application') }}
                        </button>
                        <a href="{{ route('reviewer.applications.index') }}" class="btn btn-outline-secondary">
                            {{ __('reviewer.back_to_list') }}
                        </a>
                    </div>
                </form>
            </div>

            {{-- ── Status History ── --}}
            <div class="info-card">
                <h5>{{ __('reviewer.application_status_history') }}</h5>
                @php $histories = $application->statusHistories; @endphp
                @if($histories->isEmpty())
                <div class="alert alert-info-custom">{{ __('reviewer.no_history_available') }}</div>
                @else
                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th style="width:50px">S.N</th>
                                <th>{{ __('reviewer.stage_name') }}</th>
                                <th>{{ __('reviewer.done_by') }}</th>
                                <th>{{ __('reviewer.date_time') }}</th>
                                <th>{{ __('reviewer.remarks') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($histories as $index => $history)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $history->stage_name }}</td>
                                <td>
                                    {{ $history->done_by }}
                                    <small class="d-block text-muted">{{ ucfirst($history->done_by_type) }}</small>
                                </td>
                                <td>{{ $history->created_at->format('F d, Y') }}</td>
                                <td>{{ $history->remarks ?: '—' }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endif
            </div>
            {{-- /Status History --}}

        </div>{{-- /col-lg-12 --}}
    </div>{{-- /row --}}

</div>{{-- /container-fluid --}}
@endsection

@section('scripts')
<script>
    document.getElementById('reviewStatus').addEventListener('change', function() {
        const status = this.value;
        const notesLabel = document.getElementById('notesLabel');
        const notesHelp = document.getElementById('notesHelp');
        const reviewerNotes = document.getElementById('reviewerNotes');
        const submitBtn = document.getElementById('submitBtn');
        const smsPreview = document.getElementById('smsPreview');

        if (status === 'reviewed') {
            notesLabel.textContent = 'Review Comments';
            notesHelp.textContent = 'Add your assessment and recommendations for the Approver.';
            reviewerNotes.placeholder = "Candidate's qualifications, strengths, weaknesses, and overall assessment...";
            submitBtn.className = 'btn btn-success btn-lg';
            submitBtn.style.background = '';
            submitBtn.textContent = '{{ __("reviewer.submit_action") }}';
            smsPreview.style.display = 'none';
        } else if (status === 'rejected') {
            notesLabel.textContent = 'Rejection Reason';
            notesHelp.innerHTML = '<strong>Important:</strong> Clearly explain what is missing or incorrect. This will be sent to the candidate via SMS.';
            reviewerNotes.placeholder = 'Example: "Missing citizenship certificate copy"...';
            submitBtn.className = 'btn btn-danger btn-lg';
            submitBtn.style.background = '';
            submitBtn.textContent = 'Reject Application';
            smsPreview.style.display = 'block';
        } else {
            submitBtn.className = 'btn btn-lg';
            submitBtn.style.background = '#64748b';
            submitBtn.style.color = 'white';
            smsPreview.style.display = 'none';
        }
    });

    document.getElementById('reviewerNotes').addEventListener('input', function() {
        if (document.getElementById('reviewStatus').value === 'rejected') {
            document.getElementById('smsReasonPreview').textContent =
                this.value.trim() || '[Your rejection reason will appear here]';
        }
    });

    document.getElementById('reviewForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const status = document.getElementById('reviewStatus').value;
        const notes = document.getElementById('reviewerNotes').value;

        if (!status) {
            alert('⚠️ Please select an action');
            return;
        }
        if (!notes.trim()) {
            alert('⚠️ Please add comments/notes before submitting');
            return;
        }

        const confirmMessage = status === 'rejected' ?
            'Are you sure you want to REJECT this application?\n\nThe candidate will be notified. This action will be recorded.' :
            'Are you sure you want to submit this action? This will be recorded in the system.';

        if (confirm(confirmMessage)) {
            fetch(this.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        status,
                        reviewer_notes: notes
                    })
                })
                .then(r => r.json())
                .then(data => {
                    if (data.success) {
                        alert('✅ ' + data.message);
                        location.reload();
                    } else {
                        alert('❌ ' + (data.message || 'Error updating status'));
                    }
                })
                .catch(() => alert('❌ Error updating status. Please try again.'));
        }
    });
</script>
@endsection