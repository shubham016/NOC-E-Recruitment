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
        <span>Dashboard</span>
    </a>
    <a href="{{ route('approver.assignedtome') }}" class="sidebar-menu-item active">
        <i class="bi bi-inbox"></i>
        <span>Assigned to Me</span>
    </a>
    <a href="{{ route('approver.myprofile') }}" class="sidebar-menu-item">
        <i class="bi bi-person"></i>
        <span>My Profile</span>
    </a>
@endsection

@section('custom-styles')
<style>
    .review-header {
        background: linear-gradient(135deg, #a07828 0%, #a07828 100%);
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
    <div class="approver-header">
        <div class="d-flex justify-content-between align-items-start">
            <div>
                <a href="{{ route('approver.assignedtome') }}" 
                class="text-dark text-decoration-none mb-2 d-inline-block no-print">
                    <i class="bi bi-arrow-left me-2"></i>
                    Back to Applications
                </a>

                <h2 class="mb-1 fw-bold">Application Review</h2>
            </div>
            <div class="text-end">
                @php
                    $statusColors = [
                        'pending' => 'bg-warning text-dark',
                        'assigned' => 'bg-info text-white',
                        'reviewed' => 'bg-success text-white',
                        'approved' => 'bg-success text-white',
                        'rejected' => 'bg-danger text-white',
                    ];
                    $statusColor = $statusColors[$application->status] ?? 'bg-secondary text-white';

                @endphp
                <span class="status-badge {{ $statusColor }} fs-5 d-block mb-2">
                    <i class=" me-1"></i>{{ ucfirst($application->status) }}
                </span>
                @if($application->manual_priority)
                    <span class="priority-badge {{ $priorityColors[$application->manual_priority] ?? 'bg-secondary text-white' }}">
                        <i class=" me-1"></i>Priority: {{ ucfirst($application->manual_priority) }}
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
                @if($application->passport_size_photo)
                    <img src="{{ Storage::url($application->passport_size_photo) }}"
                         alt="Candidate Photo"
                         class="candidate-photo">
                @else
                    <div class="candidate-photo d-flex align-items-center justify-content-center bg-secondary text-white">
                        <i class="" style="font-size: 4rem;"></i>
                    </div>
                @endif
                <div class="candidate-basic-info">
                    <h3>{{ $application->name_english ?? 'N/A' }}</h3>
                    <p class="detail"><strong>{{ $application->name_nepali ?? '' }}</strong></p>
                    <p class="mb-1 opacity-90">Application ID: {{ $application->id }}</p>
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
                <h5>Vacancy Information</h5>
                <div class="row">
                    <!-- Left Column -->
                    <div class="col-md-6">
                        <div class="info-row">
                            <div class="info-label">Position Applied:</div>
                            <div class="info-value">{{ $application->jobPosting->title ?? $application->applying_position ?? 'N/A' }}</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Advertisement No:</div>
                            <div class="info-value">{{ $application->advertisement_no ?? 'N/A' }}</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Application Submmited:</div>
                            <div class="info-value">{{ $application->created_at ?? 'N/A' }}</div>
                        </div>
                    </div>

                    <!-- Right Column -->
                    <div class="col-md-6">
                        <div class="info-row">
                            <div class="info-label">Department:</div>
                            <div class="info-value">{{ $application->jobPosting->department ?? $application->department ?? 'N/A' }}</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Category:</div>
                            <div class="info-value">
                                <span>{{ ucfirst($application->jobPosting->category ?? 'N/A') }}</span>
                            </div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Application Deadline:</div>
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
                <h5>Personal Information</h5>
                <div class="row">
                    <!-- Left Column -->
                    <div class="col-md-6">
                        <div class="info-row">
                            <div class="info-label">Name (English):</div>
                            <div class="info-value">{{ $application->name_english ?? 'N/A' }}</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Name (Nepali):</div>
                            <div class="info-value">{{ $application->name_nepali ?? 'N/A' }}</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Birth Date (AD):</div>
                            <div class="info-value">{{ $application->birth_date_ad ? $application->birth_date_ad->format('Y-m-d') : 'N/A' }}</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Birth Date (BS):</div>
                            <div class="info-value">{{ $application->birth_date_bs ?? 'N/A' }}</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Email:</div>
                            <div class="info-value">{{ $application->email ?? 'N/A' }}</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Age:</div>
                            <div class="info-value">{{ $application->age ?? 'N/A' }} years</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Nationality:</div>
                            <div class="info-value">{{ $application->nationality ?? 'Nepali' }}</div>
                        </div>
                    </div>

                    <!-- Right Column -->
                    <div class="col-md-6">
                        <div class="info-row">
                            <div class="info-label">Alternate Phone Number:</div>
                            <div class="info-value">{{ $application->alternate_phone_number ?? 'N/A' }}</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Gender:</div>
                            <div class="info-value">{{ ucfirst($application->gender ?? 'N/A') }}</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Marital Status:</div>
                            <div class="info-value">{{ ucfirst($application->marital_status ?? 'N/A') }}</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Spouse Name:</div>
                            <div class="info-value">{{ $application->spouse_name_english ?? 'N/A' }}</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Spouse Nationality (If Married):</div>
                            <div class="info-value">{{ $application->spouse_nationality ?? 'N/A' }}</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Blood Group:</div>
                            <div class="info-value">{{ $application->blood_group ?? 'N/A' }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Citizenship Information -->
            <div class="info-card">
                <h5>Citizenship Information</h5>
                <div class="row">
                    <!-- Left Column -->
                    <div class="col-md-6">
                        <div class="info-row">
                            <div class="info-label">Citizenship Number:</div>
                            <div class="info-value">{{ $application->citizenship_number ?? 'N/A' }}</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Issue Date (AD):</div>
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
                            <div class="info-label">Issue Date (BS):</div>
                            <div class="info-value">{{ $application->citizenship_issue_date_bs ?? 'N/A' }}</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Issue District:</div>
                            <div class="info-value">{{ $application->citizenship_issue_district ?? 'N/A' }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Community & Ethnic Information -->
            <div class="info-card">
                <h5>Community & Ethnic Information</h5>
                <div class="row">
                    <!-- Left Column -->
                    <div class="col-md-6">
                        <div class="info-row">
                            <div class="info-label">Religion:</div>
                            <div class="info-value">
                                {{ $application->religion == 'other' ? $application->religion_other : ucfirst($application->religion ?? 'N/A') }}
                            </div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Community:</div>
                            <div class="info-value">
                                {{ $application->community == 'other' ? $application->community_other : ucfirst($application->community ?? 'N/A') }}
                            </div>
                        </div>
                    </div>

                    <!-- Right Column -->
                    <div class="col-md-6">
                        <div class="info-row">
                            <div class="info-label">Ethnic Group:</div>
                            <div class="info-value">
                                {{ $application->ethnic_group == 'other' ? $application->ethnic_group_other : ucfirst($application->ethnic_group ?? 'N/A') }}
                            </div>
                        </div>
                        @if($application->ethnic_certificate)
                        <div class="info-row">
                            <div class="info-label">Ethnic Certificate:</div>
                            <div class="info-value">
                                <a href="{{ Storage::url($application->ethnic_certificate) }}" target="_blank" class="btn btn-sm btn-outline-dark">
                                    View Certificate
                                </a>
                            </div>
                        </div>
                        @endif
                        <div class="info-row">
                            <div class="info-label">Mother Tongue:</div>
                            <div class="info-value">{{ $application->mother_tongue ?? 'N/A' }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Disability & Employment Information -->
            <div class="info-card">
                <h5>Employment & Disability Status</h5>
                <div class="row">
                    <!-- Left Column -->
                    <div class="col-md-6">
                        <div class="info-row">
                            <div class="info-label">Employment Status:</div>
                            <div class="info-value">
                                {{ $application->employment_status == 'other' ? $application->employment_other : ucfirst($application->employment_status ?? 'N/A') }}
                            </div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Physical Disability:</div>
                            <div class="info-value">
                                {{ $application->physical_disability == 'other' ? $application->disability_other : ucfirst($application->physical_disability ?? 'None') }}
                            </div>
                        </div>
                    </div>

                    <!-- Right Column -->
                    <div class="col-md-6">
                        @if($application->disability_certificate)
                        <div class="info-row">
                            <div class="info-label">Disability Certificate:</div>
                            <div class="info-value">
                                <a href="{{ Storage::url($application->disability_certificate) }}" target="_blank" class="btn btn-sm btn-outline-dark">
                                    View Certificate
                                </a>
                            </div>
                        </div>
                        @endif
                        <div class="info-row">
                            <div class="info-label">NOC Employee:</div>
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

           

            <!-- Address Information -->
          <div class="info-card">
                <h5>Address Information</h5>
                <div class="row">
                    <!-- Left Column: Permanent Address -->
                    <div class="col-md-6">
                        <h6 class="text-dark mt-2 mb-2">Permanent Address</h6>
                        <div class="info-row">
                            <div class="info-label">Province:</div>
                            <div class="info-value">{{ $application->permanent_province ?? 'N/A' }}</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">District:</div>
                            <div class="info-value">{{ $application->permanent_district ?? 'N/A' }}</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Municipality:</div>
                            <div class="info-value">{{ $application->permanent_municipality ?? 'N/A' }}</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Ward No:</div>
                            <div class="info-value">{{ $application->permanent_ward ?? 'N/A' }}</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Tole/Street:</div>
                            <div class="info-value">{{ $application->permanent_tole ?? 'N/A' }}</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">House Number:</div>
                            <div class="info-value">{{ $application->permanent_house_number ?? 'N/A' }}</div>
                        </div>
                    </div>

                    <!-- Right Column: Mailing/Temporary Address -->
                    <div class="col-md-6">
                        <h6 class="text-dark mt-2 mb-2">Mailing/Temporary Address</h6>
                        @if($application->same_as_permanent == 'yes')
                            <div class="alert alert-info-custom">
                                Same as Permanent Address
                            </div>
                        @else
                            <div class="info-row">
                                <div class="info-label">Province:</div>
                                <div class="info-value">{{ $application->mailing_province ?? 'N/A' }}</div>
                            </div>
                            <div class="info-row">
                                <div class="info-label">District:</div>
                                <div class="info-value">{{ $application->mailing_district ?? 'N/A' }}</div>
                            </div>
                            <div class="info-row">
                                <div class="info-label">Municipality:</div>
                                <div class="info-value">{{ $application->mailing_municipality ?? 'N/A' }}</div>
                            </div>
                            <div class="info-row">
                                <div class="info-label">Ward No:</div>
                                <div class="info-value">{{ $application->mailing_ward ?? 'N/A' }}</div>
                            </div>
                            <div class="info-row">
                                <div class="info-label">Tole/Street:</div>
                                <div class="info-value">{{ $application->mailing_tole ?? 'N/A' }}</div>
                            </div>
                            <div class="info-row">
                                <div class="info-label">House Number:</div>
                                <div class="info-value">{{ $application->mailing_house_number ?? 'N/A' }}</div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

             

            <!-- Education -->
            <div class="info-card">
                <h5>Educational Background</h5>
                <div class="row">
                    <!-- Left Column -->
                    <div class="col-md-6">
                        <div class="info-row">
                            <div class="info-label">Education Level:</div>
                            <div class="info-value">{{ $application->education_level ?? 'N/A' }}</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Field of Study:</div>
                            <div class="info-value">{{ $application->field_of_study ?? 'N/A' }}</div>
                        </div>
                    </div>

                    <!-- Right Column -->
                    <div class="col-md-6">
                        <div class="info-row">
                            <div class="info-label">Institution:</div>
                            <div class="info-value">{{ $application->institution_name ?? 'N/A' }}</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Graduation Year:</div>
                            <div class="info-value">{{ $application->graduation_year ?? 'N/A' }}</div>
                        </div>
                    </div>
                </div>
            </div>

             <!-- Work Experience -->
                <div class="info-card">
                    <h5>Work Experience</h5>

                    @if(strtolower($application->has_work_experience ?? '') == 'yes')

                        <div class="mb-3">
                            <strong>Has Work Experience:</strong>
                            <p class="mb-0">{{ ucfirst($application->has_work_experience ?? '-') }}</p>
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

                            @if(!empty($application->$org) || !empty($application->$pos))
                                <div class="border rounded p-3 mb-3">
                                    <h6 class="text-primary">Experience {{ $i }}</h6>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <strong>Organization:</strong>
                                            <p>{{ $application->$org ?? '-' }}</p>
                                        </div>

                                        <div class="col-md-6">
                                            <strong>Position:</strong>
                                            <p>{{ $application->$pos ?? '-' }}</p>
                                        </div>

                                        <div class="col-md-6">
                                            <strong>Start Date:</strong>
                                            <p>{{ $application->$start ?? '-' }}</p>
                                        </div>

                                        <div class="col-md-6">
                                            <strong>End Date:</strong>
                                            <p>{{ $application->$end ?? '-' }}</p>
                                        </div>

                                        <div class="col-md-6">
                                            <strong>Years:</strong>
                                            <p>{{ $application->$years ?? '-' }}</p>
                                        </div>

                                        
                                    </div>
                                    <div >
                                            <strong>Document:</strong>

                                            @if(!empty($application->$doc))
                                                <img src="{{ Storage::url($application->$doc) }}"
                                                    style="width:100%; max-height:520px; object-fit:contain; border:1px solid #ddd; border-radius:8px; margin-top:8px;">
                            
                                            @else
                                                <div class="alert alert-info-custom">
                                                    No document uploaded
                                                </div>
                                            @endif

                                        </div>
                                </div>
                            @endif
                        @endfor

                    @else
                        <div class="alert alert-info-custom">
                            No work experience declared
                        </div>
                    @endif
                </div>

            <!-- Payment Information -->
            <div class="info-card">
                <h5>Payment Information</h5>
                <div class="row">
                    <!-- Left Column -->
                    <div class="col-md-6">
                        <div class="info-row">
                            <div class="info-label">Payment Gateway:</div>
                            <div class="info-value">{{ $application->payment->gateway ?? 'N/A' }}</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Amount:</div>
                            <div class="info-value">{{ $application->payment->amount ?? 'N/A' }}</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Date & Time:</div>
                            <div class="info-value">{{ $application->payment->updated_at ?? 'N/A' }}</div>
                        </div>
                    </div>

                    <!-- Right Column -->
                    <div class="col-md-6">
                        <div class="info-row">
                            <div class="info-label">Status:</div>
                            <div class="info-value">{{ $application->payment->status ?? 'N/A' }}</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Transcation ID:</div>
                            <div class="info-value">{{ $application->payment->transaction_id ?? 'N/A' }}</div>
                        </div>
                    </div>
                </div>
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
            <h5><i class=""></i>Uploaded Documents</h5>


             @if($application->passport_size_photo)
            <div class="document-item">
                <div class="document-info">
                    <p class="document-name">Passport Size Photo</p>
                    <p class="document-size">Passport Size Photo</p>

                    <img src="{{ Storage::url($application->passport_size_photo) }}"
                    style="width:100%; max-height:520px; object-fit:contain; border:1px solid #ddd; border-radius:8px; margin-top:8px;">
                </div>
            </div>
            @endif

             @if($application->citizenship_id_document)
            <div class="document-item">
                

                <div class="document-info">
                    <p class="document-name">Citizenship Id</p>
                    <p class="document-size">Citizenship Id</p>

                    <img src="{{ Storage::url($application->citizenship_id_document) }}"
                    style="width:100%; max-height:520px; object-fit:contain; border:1px solid #ddd; border-radius:8px; margin-top:8px;">
                </div>
            </div>
            @endif

            @if($application->transcript)
            <div class="document-item">
                

                <div class="document-info">
                    <p class="document-name">Educational Certificates</p>
                    <p class="document-size">Academic transcripts and degrees</p>

                    <img src="{{ Storage::url($application->transcript) }}"
                    style="width:100%; max-height:520px; object-fit:contain; border:1px solid #ddd; border-radius:8px; margin-top:8px;">
                </div>
            </div>
            @endif


            @if($application->character)
            <div class="document-item">
                

                <div class="document-info">
                    <p class="document-name">Character Certificate</p>
                    <p class="document-size">character Certificate</p>

                    <img src="{{ Storage::url($application->character) }}"
                    style="width:100%; max-height:520px; object-fit:contain; border:1px solid #ddd; border-radius:8px; margin-top:8px;">
                </div>
            </div>
            @endif

            @if($application->equivalency_certificate)
            <div class="document-item">
                

                <div class="document-info">
                    <p class="document-name">Equivalency Certificate</p>
                    <p class="document-size">Equivalency Certificate</p>

                    <img src="{{ Storage::url($application->equivalency_certificate) }}"
                    style="width:100%; max-height:520px; object-fit:contain; border:1px solid #ddd; border-radius:8px; margin-top:8px;">
                </div>
            </div>
            @endif

            @if($application->signature)
            <div class="document-item">
                

                <div class="document-info">
                    <p class="document-name">Signature</p>
                    <p class="document-size">Signature</p>

                    <img src="{{ Storage::url($application->signature) }}"
                    style="width:100%; max-height:520px; object-fit:contain; border:1px solid #ddd; border-radius:8px; margin-top:8px;">
                </div>
            </div>
            @endif

            @if($application->ethnic_certificate)
            <div class="document-item">
                

                <div class="document-info">
                    <p class="document-name">Ethnic Certificate</p>
                    <p class="document-size">Ethnic Certificate</p>

                    <img src="{{ Storage::url($application->ethnic_certificate) }}"
                    style="width:100%; max-height:520px; object-fit:contain; border:1px solid #ddd; border-radius:8px; margin-top:8px;">
                </div>
            </div>
            @endif

             @if($application->disability_certificate)
            <div class="document-item">
                

                <div class="document-info">
                    <p class="document-name">Disability Certificate</p>
                    <p class="document-size">Disability Certificate</p>

                    <img src="{{ Storage::url($application->disability_certificate) }}"
                    style="width:100%; max-height:520px; object-fit:contain; border:1px solid #ddd; border-radius:8px; margin-top:8px;">
                </div>
            </div>
            @endif

            @if($application->noc_id_card)
            <div class="document-item">
                

                <div class="document-info">
                    <p class="document-name">NOC ID Card</p>
                    <p class="document-size">NOC ID Card</p>

                    <img src="{{ Storage::url($application->noc_id_card) }}"
                    style="width:100%; max-height:520px; object-fit:contain; border:1px solid #ddd; border-radius:8px; margin-top:8px;">
                </div>
            </div>
            @endif

        




            <!-- @if($application->cover_letter_file)
            <div class="document-item">
                

                <div class="document-info">
                    <p class="document-name">Cover Letter (File)</p>
                    <p class="document-size">Uploaded cover letter document</p>

                    <iframe src="{{ Storage::url($application->cover_letter_file) }}"
                            style="width:100%; height:200px; border:1px solid #ddd; border-radius:8px; margin-top:8px;">
                    </iframe>
                </div>
            </div>
            @endif -->


          


            <!-- @if($application->other_documents)
            <div class="document-item">
                

                <div class="document-info">
                    <p class="document-name">Other Documents</p>
                    <p class="document-size">Additional supporting documents</p>

                    <iframe src="{{ Storage::url($application->other_documents) }}"
                            style="width:100%; height:200px; border:1px solid #ddd; border-radius:8px; margin-top:8px;">
                    </iframe>
                </div>
            </div>
            @endif -->


            @if(
                !$application->passport_size_photo &&
                !$application->resume &&
                !$application->work_experience &&
                !$application->citizenship_certificate &&
                !$application->citizenship_id_document &&
                !$application->educational_certificates &&
                !$application->transcript &&
                !$application->experience_certificates &&
                !$application->character_certificate &&
                !$application->character &&
                !$application->equivalency_certificate &&
                !$application->equivalent &&
                !$application->ethnic_certificate &&
                !$application->disability_certificate &&
                !$application->noc_id_card &&
                !$application->cover_letter_file &&
                !$application->signature &&
                !$application->other_documents
            )
            <div class="alert alert-warning">
                No documents uploaded
            </div>
            @endif
        </div>
            <!-- Reviewed Details -->
            <div class="info-card">
                <h5>
                    <i class="text-primary me-2"></i>Reviewed Details
                </h5>
                <div class="timeline">
                    <div class="info-row">
                            <div class="info-label">Reviewer:</div>
                            <div class="info-value">{{ $application->reviewer->name ?? 'N/A' }}</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Reviewed Date:</div>
                            <div class="info-value">{{ $application->reviewed_at ?? 'N/A' }}</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Reviewer Note:</div>
                            <div class="info-value">{{ $application->reviewer_notes?? 'N/A' }}</div>
                        </div>
                    
                </div>
            </div>

            <!-- Admin Notes (if any) -->
            @if($application->admin_notes)
            <div class="info-card">
                <h5>Admin Notes</h5>
                <div class="alert alert-info-custom">
                    <p class="mb-0"><strong>Admin's Note:</strong></p>
                    <p class="mb-0 mt-2">{{ $application->admin_notes }}</p>
                </div>
            </div>
            @endif

            @if($application->priority_note)
            <div class="info-card">
                <h5>Priority Note</h5>
                <div class="alert" style="background: #fef3c7; border-left: 4px solid #f59e0b;">
                    <p class="mb-0">{{ $application->priority_note }}</p>
                </div>
            </div>
            @endif
        </div>

        <!-- Right Column -->
        <div class="col-12 mt-4">
            <!-- Actions -->
            @if($application->status !== 'approved' && $application->status !== 'rejected')
            <div class="info-card">
                <h5>
                    <i class="text-secondary me-2"></i>Actions
                </h5>
                <form action="{{ route('approver.applications.updateStatus', $application->id) }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Decision</label>
                        <select name="status" class="form-select" required>
                            <option value="">Select Decision</option>
                            <option value="approved">Approve</option>
                            <option value="edit" {{ $application->status == 'edit' ? 'selected' : '' }}>Send Back for Edit</option>
                            <option value="rejected">Reject</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Remarks <span class="text-danger">*</span></label>
                        <textarea name="approver_notes" class="form-control" rows="4" placeholder="Add your remarks here..." required></textarea>
                    </div>
                    <button type="submit" class="btn btn-gold w-100">
                        <i class="bi bi-check-circle me-1"></i>Submit Decision
                    </button>
                </form>
            </div>
            @else
            <div class="info-card">
                <h5>
                    <i class="bi bi-info-circle text-info me-2"></i>Status
                </h5>
                <div class="alert alert-{{ $application->status === 'approved' ? 'success' : 'danger' }} mb-0">
                    This application has been <strong>{{ $application->status }}</strong>.
                </div>
            </div>
            @endif

            
        </div>
    </div>
</div>
@endsection
