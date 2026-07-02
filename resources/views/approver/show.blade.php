@extends('layouts.approver')

@section('title', 'Application Details')
@section('title', 'Approver Dashboard')
@section('portal-name', 'Approver Portal')
@section('brand-icon', 'bi bi-person-check')
@section('dashboard-route', route('approver.dashboard'))
@section('user-name', Auth::guard('approver')->user()->name)
@section('user-role', 'Application Approver')
@section('user-initial', strtoupper(substr(Auth::guard('approver')->user()->name, 0, 1)))
@section('logout-route', route('approver.logout'))


@section('sidebar-menu')
    <a href="{{ route('approver.dashboard') }}" class="sidebar-menu-item">
        <i class="bi bi-speedometer2"></i>
        <span>{{ __('approver.dashboard') }}</span>
    </a>
    <a href="{{ route('approver.assignedtome') }}" class="sidebar-menu-item active">
        <i class="bi bi-inbox"></i>
        <span>{{ __('approver.assigned_to_me') }}</span>
    </a>
    <a href="{{ route('approver.myprofile') }}" class="sidebar-menu-item">
        <i class="bi bi-person"></i>
        <span>{{ __('approver.my_profile') }}</span>
    </a>
    <a href="{{ route('approver.notifications.index') }}" class="sidebar-menu-item">
        <i class="bi bi-bell"></i>
        <span>{{ __('approver.notifications') }}</span>
    </a>
@endsection

@section('custom-styles')
<style>
    .review-header {
        background: linear-gradient(135deg, #16325e 0%, #16325e 100%);
        border-radius: 12px;
        padding: 2rem;
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

    .document-icon {
        width: 60px;
        height: 60px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: white;
        border-radius: 6px;
        font-size: 1.2rem;
        color: #64748b;
        overflow: hidden;
        border: 2px solid #e5e7eb;
    }

    .document-icon img {
        width: 100%;
        height: 100%;
        object-fit: cover;
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

    .btn-view-doc {
        padding: 0.4rem 1rem;
        background: #3b82f6;
        color: white;
        text-decoration: none;
        border-radius: 6px;
        font-size: 0.9rem;
        transition: all 0.2s;
    }

    .btn-view-doc:hover {
        background: #2563eb;
        color: white;
        transform: scale(1.05);
    }

    .payment-details {
        background: linear-gradient(135deg, #ecfdf5 0%, #d1fae5 100%);
        border: 2px solid #10b981;
        border-radius: 10px;
        padding: 1rem;
    }

    .payment-details.pending {
        background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
        border-color: #f59e0b;
    }

    .payment-details .info-row {
        padding: 0.5rem 0;
        margin: 0;
    }

    .payment-details .info-label {
        min-width: 120px;
    }

    .payment-details .info-value {
        font-size: 0.95rem;
    }

    .gateway-badge {
        display: inline-flex;
        align-items: center;
        padding: 0.20rem 0.6rem;
        margin-left: 5px;
        border-radius: 4px;
        font-weight: 600;
        font-size: 0.875rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.12);
        transition: all 0.2s ease;
        vertical-align: middle;
    }

    .gateway-badge:hover {
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.18);
    }

    .gateway-badge i {
        font-size: 0.9rem;
        margin-right: 0.35rem;
    }

    .alert-info-custom {
        background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
        border-left: 4px solid #3b82f6;
        padding: 1rem;
        border-radius: 8px;
        margin-bottom: 1rem;
    }

    .section-divider {
        height: 2px;
        background: linear-gradient(to right, #e5e7eb 0%, #3b82f6 50%, #e5e7eb 100%);
        margin: 2rem 0;
    }

    .align-items-start {
        align-items: flex-start !important;
        margin-bottom: 10px;
    }

    @media print {
        .review-actions, .no-print {
            display: none !important;
        }
    }
</style>
@endsection

@section('content')
<div class="container-fluid px-4 py-4">
    <!-- Page Header -->
    <div class="review-header">
        <div class="d-flex justify-content-between align-items-start">
            <div>
                <a href="{{ route('approver.assignedtome') }}"  class="text-white text-decoration-none mb-2 d-inline-block opacity-75 no-print">
                    <i class="bi bi-arrow-left me-2"></i>{{ __('approver.back_to_applications') }}
                </a>
                <h2 class="mb-1 fw-bold">{{ __('approver.application_review') }}</h2>
            </div>
            <div class="text-end">
                
                <span class="status-badge  fs-5 d-block mb-2">
                    <i class=" me-1"></i>{{ ucfirst($application->status) }}
                </span>
                @if($application->manual_priority)
                    <span class="priority-badge {{ $priorityColors[$application->manual_priority] ?? 'bg-secondary text-white' }}">
                        <i class=" me-1"></i>{{ __('approver.priority') }}: {{ ucfirst($application->manual_priority) }}
                    </span>
                @endif
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <!-- Main Content -->
        <div class="col-12 mt-4">
            <!-- Candidate Photo & Basic Info -->
                    <div class="candidate-photo-section">
                @php
                    $photo = $application->passport_size_photo
                        ?? $application->candidateRegistration?->passport_size_photo;
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
                    <p class="mb-1 opacity-90">{{ __('approver.app_id') }}: {{ $application->id }}</p>
                    <p class="detail">
                        <i class=""></i>{{ $application->email ?? 'N/A' }}
                    </p>
                    <p class="detail">
                        <i class=""></i>{{ $application->phone ?? 'N/A' }}
                        @if($application->alternate_phone_number)
                            | {{ $application->alternate_phone_number }}
                        @endif
                    </p>
                    <p class="detail">
                        <i class=""></i>
                        {{ $application->permanent_municipality }}, {{ $application->permanent_district }}
                    </p>
                    <p class="mb-0 opacity-75">
                        <i class=""></i>
                        @php $submittedDate = $application->submitted_at ?: $application->created_at; @endphp
                        Submitted: {{ $submittedDate ? adToBS($submittedDate) . ' BS, ' . \Carbon\Carbon::parse($submittedDate)->format('h:i A') : 'N/A' }}
                    </p>
                </div>
            </div>

            <!-- Vacancy Information -->
            <div class="info-card">
                <h5>{{ __('approver.vacancy_information') }}</h5>
                <div class="row">
                    <!-- Left Column -->
                    <div class="col-md-6">
                        <div class="info-row">
                            <div class="info-label">{{ __('approver.position_applied') }}:</div>
                            <div class="info-value">{{ $application->jobPosting->title ?? $application->applying_position ?? 'N/A' }}</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">{{ __('approver.advertisement_no') }}:</div>
                            <div class="info-value">{{ $application->advertisement_no ?? 'N/A' }}</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">{{ __('approver.application_submitted') }}:</div>
                            <div class="info-value">{{ $application->created_at ?? 'N/A' }}</div>
                        </div>
                    </div>

                    <!-- Right Column -->
                    <div class="col-md-6">
                        <div class="info-row">
                            <div class="info-label">{{ __('approver.department') }}:</div>
                            <div class="info-value">{{ $application->jobPosting->department ?? $application->department ?? 'N/A' }}</div>
                        </div>
                        <div class="info-row">
                <div class="info-label">{{ __('approver.category') }}:</div>
                <div class="info-value">
                    @if(!empty($application->applied_category))
                        {{ implode(', ', $application->applied_category) }}
                    @else
                        N/A
                    @endif
                </div>
            </div>
                        <div class="info-row">
                            <div class="info-label">{{ __('approver.application_deadline') }}:</div>
                            <div class="info-value">
                                @if($application->jobPosting->deadline)
                                    @php 
                                        $deadlineBS = $application->jobPosting->deadline_bs ?: adToBS($application->jobPosting->deadline->format('Y-m-d')); 
                                    @endphp
                                    <span class="d-block">{{ $deadlineBS }} (BS)</span>
                                    <span class="text-muted">{{ $application->jobPosting->deadline->format('F d, Y') }} (AD)</span>
                                @else
                                    N/A
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

             

            <!-- Personal Information -->
           <div class="info-card">
                <h5>{{ __('approver.personal_information') }}</h5>
                <div class="row">
                    <!-- Left Column -->
                    <div class="col-md-6">
                        <div class="info-row">
                            <div class="info-label">{{ __('approver.name_english') }}:</div>
                            <div class="info-value">{{ $application->name_english ?? 'N/A' }}</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">{{ __('approver.name_nepali') }}:</div>
                            <div class="info-value">{{ $application->name_nepali ?? 'N/A' }}</div>
                        </div>
                       
                        <div class="info-row">
                            <div class="info-label">{{ __('approver.birth_date_bs') }}:</div>
                            <div class="info-value">{{ $application->birth_date_bs ?? 'N/A' }}</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">{{ __('approver.marital_status') }}:</div>
                            <div class="info-value">{{ ucfirst($application->marital_status ?? 'N/A') }}</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">{{ __('approver.spouse_name') }}:</div>
                            <div class="info-value">{{ $application->spouse_name_english ?? 'N/A' }}</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">{{ __('approver.age') }}:</div>
                            <div class="info-value">{{ $application->age ?? 'N/A' }} years</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">{{ __('approver.nationality') }}:</div>
                            <div class="info-value">{{ $application->nationality ?? 'Nepali' }}</div>
                        </div>
                    </div>

                    <!-- Right Column -->
                    <div class="col-md-6">
                        <div class="info-row">
                            <div class="info-label">{{ __('approver.alternate_phone') }}:</div>
                            <div class="info-value">{{ $application->alternate_phone_number ?? 'N/A' }}</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">{{ __('approver.gender') }}:</div>
                            <div class="info-value">{{ ucfirst($application->gender ?? 'N/A') }}</div>
                        </div>
                         <div class="info-row">
                            <div class="info-label">{{ __('approver.birth_date_ad') }}:</div>
                            <div class="info-value">{{ $application->birth_date_ad ? $application->birth_date_ad->format('Y-m-d') : 'N/A' }}</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">{{ __('approver.email') }}:</div>
                            <div class="info-value">{{ $application->email ?? 'N/A' }}</div>
                        </div>
                        
                        <div class="info-row">
                            <div class="info-label">{{ __('approver.spouse_nationality_married') }}:</div>
                            <div class="info-value">{{ $application->spouse_nationality ?? 'N/A' }}</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">{{ __('approver.blood_group') }}:</div>
                            <div class="info-value">{{ $application->blood_group ?? 'N/A' }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Citizenship Information -->
            <div class="info-card">
                <h5>{{ __('approver.citizenship_information') }}</h5>
                <div class="row">
                    <!-- Left Column -->
                    <div class="col-md-6">
                        <div class="info-row">
                            <div class="info-label">{{ __('approver.citizenship_number') }}:</div>
                            <div class="info-value">{{ $application->citizenship_number ?? 'N/A' }}</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">{{ __('approver.issue_date_ad') }}:</div>
                            <div class="info-value">
                                {{ $application->citizenship_issue_date_ad 
                                    ? \Carbon\Carbon::parse($application->citizenship_issue_date_ad)->format('Y-m-d') 
                                    : ($application->citizenship_issue_date_bs ? bsToAD($application->citizenship_issue_date_bs) : 'N/A') 
                                }}
                            </div>
                        </div>
                    </div>

                    <!-- Right Column -->
                    <div class="col-md-6">
                        <div class="info-row">
                            <div class="info-label">{{ __('approver.issue_date_bs') }}:</div>
                            <div class="info-value">{{ $application->citizenship_issue_date_bs ?? 'N/A' }}</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">{{ __('approver.issue_district') }}:</div>
                            <div class="info-value">{{ $application->citizenship_issue_district ?? 'N/A' }}</div>
                        </div>
                    </div>
                </div>

                @if($application->citizenship_id_document)
            <div class="document-item">
                

                <div class="document-info">
                    <p class="document-name">{{ __('approver.citizenship_id') }}</p>
                    <p class="document-size">{{ __('approver.citizenship_id') }}</p>

                    <img src="{{ Storage::url($application->citizenship_id_document) }}"
                    style="width:100%; max-height:520px; object-fit:contain; border:1px solid #ddd; border-radius:8px; margin-top:8px;">
                </div>
            </div>
            @endif
            </div>

            <!-- Community & Ethnic Information -->
            <div class="info-card">
                <h5>{{ __('approver.community_ethnic_information') }}</h5>
                <div class="row">
                    <!-- Left Column -->
                    <div class="col-md-6">
                        <div class="info-row">
                            <div class="info-label">{{ __('approver.religion') }}:</div>
                            <div class="info-value">
                                {{ $application->religion == 'other' ? $application->religion_other : ucfirst($application->religion ?? 'N/A') }}
                            </div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">{{ __('approver.community') }}:</div>
                            <div class="info-value">
                                {{ $application->community == 'other' ? $application->community_other : ucfirst($application->community ?? 'N/A') }}
                            </div>
                        </div>
                    </div>

                    <!-- Right Column -->
                    <div class="col-md-6">
                        <div class="info-row">
                            <div class="info-label">{{ __('approver.ethnic_group') }}:</div>
                            <div class="info-value">
                                {{ $application->ethnic_group == 'other' ? $application->ethnic_group_other : ucfirst($application->ethnic_group ?? 'N/A') }}
                            </div>
                        </div>
                        @if($application->ethnic_certificate)
                        <div class="info-row">
                            <div class="info-label">{{ __('approver.ethnic_certificate') }}:</div>
                            <div class="info-value">
                                <a href="{{ Storage::url($application->ethnic_certificate) }}" target="_blank" class="btn btn-sm btn-outline-dark">
                                    View Certificate
                                </a>
                            </div>
                        </div>
                        @endif
                        <div class="info-row">
                            <div class="info-label">{{ __('approver.mother_tongue') }}:</div>
                            <div class="info-value">{{ $application->mother_tongue ?? 'N/A' }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Disability & Employment Information -->
            <div class="info-card">
                <h5>{{ __('approver.employment_disability_status') }}</h5>
                <div class="row">
                    <!-- Left Column -->
                    <div class="col-md-6">
                        <div class="info-row">
                            <div class="info-label">{{ __('approver.employment_status') }}:</div>
                            <div class="info-value">
                                {{ $application->employment_status == 'other' ? $application->employment_other : ucfirst($application->employment_status ?? 'N/A') }}
                            </div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">{{ __('approver.physical_disability') }}:</div>
                            <div class="info-value">
                                {{ $application->physical_disability == 'other' ? $application->disability_other : ucfirst($application->physical_disability ?? 'None') }}
                            </div>
                        </div>
                    </div>

                    <!-- Right Column -->
                    <div class="col-md-6">
                        @if($application->disability_certificate)
                        <div class="info-row">
                            <div class="info-label">{{ __('approver.disability_certificate') }}:</div>
                            <div class="info-value">
                                <a href="{{ Storage::url($application->disability_certificate) }}" target="_blank" class="btn btn-sm btn-outline-dark">
                                    {{ __('approver.view_certificate') }}
                                </a>
                            </div>
                        </div>
                        @endif
                        <div class="info-row">
                            <div class="info-label">{{ __('approver.noc_employee') }}:</div>
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

            <!-- Family Information -->
<div class="info-card">
    <h5><i class=""></i>{{ __('approver.family_information') }}</h5>

    <div class="row">
        <!-- Left Column -->
        <div class="col-md-6">
            <!-- Grandfather -->
            <div class="info-row">
                <div class="info-label text-dark">{{ __('approver.grandfather_name') }}:</div>
                <div class="info-value">{{ $application->grandfather_name_english ?? 'N/A' }}</div>
            </div>

            <!-- Father -->
            <h6 class="text-dark mt-3 mb-2"><i class=""></i>{{ __('approver.fathers_information') }}</h6>

            <div class="info-row">
                <div class="info-label">{{ __('approver.father_name_english') }}:</div>
                <div class="info-value">{{ $application->father_name_english ?? 'N/A' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">{{ __('approver.father_name_nepali') }}:</div>
                <div class="info-value">{{ $application->father_name_nepali ?? 'N/A' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">{{ __('approver.father_qualification') }}:</div>
                <div class="info-value">{{ $application->father_qualification ?? 'N/A' }}</div>
            </div>
        </div>

        <!-- Right Column -->
        <div class="col-md-6">
            <!-- Mother -->
            <h6 class="text-dark mt-3 mb-2"><i class=""></i>{{ __('approver.mothers_information') }}</h6>
            <div class="info-row">
                <div class="info-label">{{ __('approver.mother_name_english') }}:</div>
                <div class="info-value">{{ $application->mother_name_english ?? 'N/A' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">{{ __('approver.mother_name_nepali') }}:</div>
                <div class="info-value">{{ $application->mother_name_nepali ?? 'N/A' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">{{ __('approver.mother_qualification') }}:</div>
                <div class="info-value">{{ $application->mother_qualification ?? 'N/A' }}</div>
            </div>

            <!-- Parent Occupation -->
            <div class="info-row">
                <div class="info-label">{{ __('approver.parent_occupation') }}:</div>
                <div class="info-value">
                    {{ $application->parent_occupation == 'other' ? $application->parent_occupation_other : ucfirst($application->parent_occupation ?? 'N/A') }}
                </div>
            </div>

            <!-- Spouse -->
            @if($application->marital_status == 'married')
            <h6 class="text-dark mt-3 mb-2"><i class=""></i>{{ __('approver.spouse_information') }}</h6>
            <div class="info-row">
                <div class="info-label">{{ __('approver.spouse_name_english') }}:</div>
                <div class="info-value">{{ $application->spouse_name_english ?? 'N/A' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">{{ __('approver.spouse_name_nepali') }}:</div>
                <div class="info-value">{{ $application->spouse_name_nepali ?? 'N/A' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">{{ __('approver.spouse_nationality') }}:</div>
                <div class="info-value">{{ $application->spouse_nationality ?? 'N/A' }}</div>
            </div>
            @endif
        </div>
    </div>
</div>

            <!-- Address Information -->
          <div class="info-card">
                <h5>{{ __('approver.address_information') }}</h5>
                <div class="row">
                    <!-- Left Column: Permanent Address -->
                    <div class="col-md-6">
                        <h6 class="text-dark mt-2 mb-2">{{ __('approver.permanent_address') }}</h6>
                        <div class="info-row">
                            <div class="info-label">{{ __('approver.province') }}:</div>
                            <div class="info-value">{{ $application->permanent_province ?? 'N/A' }}</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">{{ __('approver.district') }}:</div>
                            <div class="info-value">{{ $application->permanent_district ?? 'N/A' }}</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">{{ __('approver.municipality') }}:</div>
                            <div class="info-value">{{ $application->permanent_municipality ?? 'N/A' }}</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">{{ __('approver.ward_no') }}:</div>
                            <div class="info-value">{{ $application->permanent_ward ?? 'N/A' }}</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">{{ __('approver.tole_street') }}:</div>
                            <div class="info-value">{{ $application->permanent_tole ?? 'N/A' }}</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">{{ __('approver.house_number') }}:</div>
                            <div class="info-value">{{ $application->permanent_house_number ?? 'N/A' }}</div>
                        </div>
                    </div>

                    <!-- Right Column: Mailing/Temporary Address -->
                    <div class="col-md-6">
                        <h6 class="text-dark mt-2 mb-2">{{ __('approver.mailing_temporary_address') }}</h6>
                        @if($application->same_as_permanent == 'yes')
                            <div class="alert alert-primary">
                                {{ __('approver.same_as_permanent') }}
                            </div>
                        @else
                            <div class="info-row">
                                <div class="info-label">{{ __('approver.province') }}:</div>
                                <div class="info-value">{{ $application->mailing_province ?? 'N/A' }}</div>
                            </div>
                            <div class="info-row">
                                <div class="info-label">{{ __('approver.district') }}:</div>
                                <div class="info-value">{{ $application->mailing_district ?? 'N/A' }}</div>
                            </div>
                            <div class="info-row">
                                <div class="info-label">{{ __('approver.municipality') }}:</div>
                                <div class="info-value">{{ $application->mailing_municipality ?? 'N/A' }}</div>
                            </div>
                            <div class="info-row">
                                <div class="info-label">{{ __('approver.ward_no') }}:</div>
                                <div class="info-value">{{ $application->mailing_ward ?? 'N/A' }}</div>
                            </div>
                            <div class="info-row">
                                <div class="info-label">{{ __('approver.tole_street') }}:</div>
                                <div class="info-value">{{ $application->mailing_tole ?? 'N/A' }}</div>
                            </div>
                            <div class="info-row">
                                <div class="info-label">{{ __('approver.house_number') }}:</div>
                                <div class="info-value">{{ $application->mailing_house_number ?? 'N/A' }}</div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

             

            <!-- Education -->
            <div class="info-card">
                <h5>{{ __('approver.educational_background') }}</h5>
                <div class="row">
                    <!-- Left Column -->
                    <div class="col-md-6">
                        <div class="info-row">
                            <div class="info-label">{{ __('approver.education_level') }}:</div>
                            <div class="info-value">{{ $application->education_level ?? 'N/A' }}</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">{{ __('approver.field_of_study') }}:</div>
                            <div class="info-value">{{ $application->field_of_study ?? 'N/A' }}</div>
                        </div>
                    </div>

                    <!-- Right Column -->
                    <div class="col-md-6">
                        <div class="info-row">
                            <div class="info-label">{{ __('approver.institution') }}:</div>
                            <div class="info-value">{{ $application->institution_name ?? 'N/A' }}</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">{{ __('approver.graduation_year') }}:</div>
                            <div class="info-value">{{ $application->graduation_year ?? 'N/A' }}</div>
                        </div>
                    </div>
                </div>

                 @if($application->transcript)
            <div class="document-item">
                

                <div class="document-info">
                    <p class="document-name">{{ __('approver.educational_certificates') }}</p>
                    <p class="document-size">{{ __('approver.academic_transcripts_and_degrees') }}</p>

                    <img src="{{ Storage::url($application->transcript) }}"
                    style="width:100%; max-height:520px; object-fit:contain; border:1px solid #ddd; border-radius:8px; margin-top:8px;">
                </div>
            </div>
            @endif


            @if($application->character)
            <div class="document-item">
                

                <div class="document-info">
                    <p class="document-name">{{ __('approver.character_certificate') }}</p>
                    <p class="document-size">{{ __('approver.character_certificate_description') }}</p>

                    <img src="{{ Storage::url($application->character) }}"
                    style="width:100%; max-height:520px; object-fit:contain; border:1px solid #ddd; border-radius:8px; margin-top:8px;">
                </div>
            </div>
            @endif

            @if($application->equivalency_certificate)
            <div class="document-item">
                

                <div class="document-info">
                    <p class="document-name">{{ __('approver.equivalency_certificate') }}</p>
                    <p class="document-size">{{ __('approver.equivalency_certificate_description') }}</p>

                    <img src="{{ Storage::url($application->equivalency_certificate) }}"
                    style="width:100%; max-height:520px; object-fit:contain; border:1px solid #ddd; border-radius:8px; margin-top:8px;">
                </div>
            </div>
            @endif
            </div>

           <!-- Work Experience -->
<div class="info-card">
    <h5>{{ __('approver.work_experience') }}</h5>

    @php $hasExperiences = $application->experiences->isNotEmpty(); @endphp

    @if(strtolower($application->has_work_experience ?? '') == 'yes' || $hasExperiences)

        <div class="mb-3">
            <strong>{{ __('approver.has_work_experience') }}:</strong>
            <p class="mb-0">{{ $hasExperiences ? 'Yes' : ucfirst($application->has_work_experience) }}</p>
        </div>

        @forelse($application->experiences as $exp)
            <div class="border rounded p-3 mb-3">
                <h6 class="text-primary">{{ __('approver.experience_label') }} {{ $exp->exp_number }}</h6>

                <div class="row">
                    <div class="col-md-6">
                        <strong>{{ __('approver.organization') }}:</strong>
                        <p>{{ $exp->organization ?? '-' }}</p>
                    </div>
                    <div class="col-md-6">
                        <strong>{{ __('approver.position') }}:</strong>
                        <p>{{ $exp->position ?? '-' }}</p>
                    </div>
                    <div class="col-md-6">
                        <strong>{{ __('approver.start_date_bs') }}</strong>
                        <p>{{ $exp->start_date_bs ?? '-' }}</p>
                    </div>
                    <div class="col-md-6">
                        <strong>{{ __('approver.end_date_bs') }} </strong>
                        <p>{{ $exp->end_date_bs ?? '-' }}</p>
                    </div>
                    <div class="col-md-6">
                        <strong>{{ __('approver.years') }}:</strong>
                        <p>{{ $exp->years ?? '-' }}</p>
                    </div>
                </div>

                @php
                    $docCol   = 'exp' . $exp->exp_number . '_document';
                    $document = $exp->document
                        ?? ($application->candidateRegistration?->{$docCol} ?? null);
                @endphp

                <div>
                    <strong>{{ __('approver.document') }}:</strong>
                    @if($document)
                        @php $ext = strtolower(pathinfo($document, PATHINFO_EXTENSION)); @endphp
                        @if(in_array($ext, ['jpg','jpeg','png','webp']))
                            <img src="{{ Storage::url($document) }}"
                                 style="width:100%; max-height:520px; object-fit:contain; border:1px solid #ddd; border-radius:8px; margin-top:8px;">
                        @elseif($ext === 'pdf')
                            <a href="{{ Storage::url($document) }}" target="_blank"
                               class="btn btn-sm btn-outline-danger mt-2">
                                <i class="fas fa-file-pdf me-1"></i> View PDF
                            </a>
                        @else
                            <a href="{{ Storage::url($document) }}" target="_blank"
                               class="btn btn-sm btn-outline-secondary mt-2">View Document</a>
                        @endif
                    @else
                        <div class="alert alert-primary mt-2">{{ __('approver.no_document_uploaded') }}</div>
                    @endif
                </div>
            </div>
        @empty
            <div class="alert alert-primary">{{ __('approver.no_work_experience') }}</div>
        @endforelse

    @else
        <div class="alert alert-primary">{{ __('approver.no_work_experience') }}</div>
    @endif
</div>

            

            <!-- Cover Letter -->
            <!-- @if($application->cover_letter)
            <div class="info-card">
                <h5><i class=""></i>Cover Letter</h5>
                <div class="p-3" style="background: #f8fafc; border-radius: 8px; border: 1px solid #e2e8f0;">
                    <p style="white-space: pre-wrap;">{{ $application->cover_letter }}</p>
                </div>
            </div>
            @endif -->

             

            <!-- Uploaded Documents -->
<div class="info-card">
    <h5><i class=""></i>{{ __('approver.uploaded_documents') }}</h5>

    @php
        $cr = $application->candidateRegistration;

        $docs = [
            'passport_size_photo'     => ['label' => __('approver.passport_size_photo'),     'file' => $application->passport_size_photo     ?? $cr?->passport_size_photo],
            'signature'               => ['label' => __('approver.signature'),               'file' => $application->signature               ?? $cr?->signature],
            'citizenship_id_document' => ['label' => __('approver.citizenship_id'), 'file' => $application->citizenship_id_document  ?? $cr?->citizenship_id_document],
            'noc_id_card'             => ['label' => __('approver.noc_id_card'),             'file' => $application->noc_id_card             ?? $cr?->noc_id_card],
            'ethnic_certificate'      => ['label' => __('approver.ethnic_certificate'),      'file' => $application->ethnic_certificate       ?? $cr?->ethnic_certificate],
            'disability_certificate'  => ['label' => __('approver.disability_certificate'),  'file' => $application->disability_certificate   ?? $cr?->disability_certificate],
            'transcript'              => ['label' => __('approver.academic_transcripts'),              'file' => $application->transcript              ?? $cr?->transcript],
            'character'               => ['label' => __('approver.character_certificate'),   'file' => $application->character               ?? $cr?->character_certificate],
            'equivalent'              => ['label' => __('approver.equivalency_certificate'), 'file' => $application->equivalent              ?? $cr?->equivalency_certificate],
            'work_experience'         => ['label' => __('approver.work_experience_doc'),     'file' => $application->work_experience],
            'additional_documents'    => ['label' => __('approver.additional_documents'),    'file' => $application->additional_documents],
        ];

        $hasAny = collect($docs)->contains(fn($d) => !empty($d['file']));
    @endphp

    @if($hasAny)
        @foreach($docs as $doc)
            @if(!empty($doc['file']))
                <div class="document-item mb-3">
                    <div class="document-info">
                        <p class="document-name"><strong>{{ $doc['label'] }}</strong></p>
                        @php $ext = strtolower(pathinfo($doc['file'], PATHINFO_EXTENSION)); @endphp
                        @if(in_array($ext, ['jpg','jpeg','png','webp']))
                            <img src="{{ Storage::url($doc['file']) }}"
                                 style="width:100%; max-height:520px; object-fit:contain; border:1px solid #ddd; border-radius:8px; margin-top:8px;">
                        @elseif($ext === 'pdf')
                            <a href="{{ Storage::url($doc['file']) }}" target="_blank"
                               class="btn btn-sm btn-outline-danger mt-2">
                                <i class="fas fa-file-pdf me-1"></i> View PDF
                            </a>
                        @else
                            <a href="{{ Storage::url($doc['file']) }}" target="_blank"
                               class="btn btn-sm btn-outline-secondary mt-2">
                                <i class="fas fa-file me-1"></i> View Document
                            </a>
                        @endif
                    </div>
                </div>
            @endif
        @endforeach
    @else
        <div class="alert alert-warning">{{ __('approver.no_documents_uploaded') }}</div>
    @endif
</div>

        <!-- Payment Information -->
            <div class="info-card">
                <h5>{{ __('approver.payment_information') }}</h5>
                <div class="row">
                    <!-- Left Column -->
                    <div class="col-md-6">
                        <div class="info-row">
                            <div class="info-label">{{ __('approver.payment_gateway') }}:</div>
                            <div class="info-value">{{ $application->payment->gateway ?? 'N/A' }}</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">{{ __('approver.amount') }}:</div>
                            <div class="info-value">{{ $application->payment->amount ?? 'N/A' }}</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">{{ __('approver.date_time') }}:</div>
                            <div class="info-value">{{ $application->payment->updated_at ?? 'N/A' }}</div>
                        </div>
                    </div>

                    <!-- Right Column -->
                    <div class="col-md-6">
                        <div class="info-row">
                            <div class="info-label">{{ __('approver.status') }}:</div>
                            <div class="info-value">{{ $application->payment->status ?? 'N/A' }}</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">{{ __('approver.transaction_id') }}:</div>
                            <div class="info-value">{{ $application->payment->transaction_id ?? 'N/A' }}</div>
                        </div>
                    </div>
                </div>
            </div>

        <!-- Right Column -->
        <div class="col-12 mt-4">
            <!-- Actions -->
            @if($application->status !== 'approved' && $application->status !== 'rejected')
            <div class="info-card">
                <h5>
                    <i class="text-secondary me-2"></i>{{ __('approver.actions') }}
                </h5>
                <form action="{{ route('approver.applications.updateStatus', $application->id) }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-semibold">{{ __('approver.decision') }}</label>
                        <select name="status" class="form-select" required>
                            <option value="">{{ __('approver.select_decision') }}</option>
                            <option value="approved">{{ __('approver.decision_approve') }}</option>
                            <option value="edit" {{ $application->status == 'edit' ? 'selected' : '' }}>{{ __('approver.decision_edit') }}</option>
                            <option value="rejected" {{ $application->status == 'rejected' ? 'selected' : '' }}>{{ __('approver.decision_reject') }}</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">{{ __('approver.remarks') }} <span class="text-danger">*</span></label>
                        <textarea name="approver_notes" class="form-control" rows="4" placeholder="{{ __('approver.remarks_placeholder') }}" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-gold w-100">
                        <i class="bi bi-check-circle me-1"></i>{{ __('approver.submit_decision') }}
                    </button>
                </form>
            </div>
            @else
            <div class="info-card">
                <h5>
                    <i class="bi bi-info-circle text-info me-2"></i>{{ __('approver.status') }}
                </h5>
                <div class="alert alert-{{ $application->status === 'approved' ? 'success' : 'danger' }} mb-0">
                    This application has been {{ $application->status }}.
                </div>
            </div>
            @endif

            
        </div>

         <div class="info-card mt-3">
    <h5>
        <!-- <i class="bi bi-clock-history me-2 text-secondary"></i> -->
        {{ __('approver.application_status_history') }}
    </h5>

    @php $histories = $application->statusHistories; @endphp

    @if($histories->isEmpty())
        <div class="alert alert-info-custom">{{ __('approver.no_history_available') }}</div>
    @else
         <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle mb-0 text-center">
                <thead class="table-light">
                    <tr>
                        <th style="width:50px">S.N</th>
                        <th>{{ __('approver.stage_name') }}</th>
                        <th>{{ __('approver.done_by') }}</th>
                        <th>{{ __('approver.date_time') }}</th>
                        <th>{{ __('approver.remarks') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($histories as $index => $history)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>
                                <span>
                                    {{ $history->stage_name }}
                                </span>
                            </td>
                            <td>
                                {{ $history->done_by }}
                                <small class="d-block text-muted">
                                    {{ ucfirst($history->done_by_type) }}
                                </small>
                            </td>
                            <td>
                                {{ adToBS($history->created_at->format('Y-m-d')) }} BS,
                                {{ $history->created_at->format('h:i A') }}
                            </td>
                            <td>{{ $history->remarks ?: '—' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
    </div>
</div>
@endsection
