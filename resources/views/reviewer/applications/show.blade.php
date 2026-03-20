@extends('layouts.apps')

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
        <span>Dashboard</span>
    </a>
    <a href="{{ route('reviewer.applications.index', ['status' => 'assigned']) }}" class="sidebar-menu-item">
        <i class="bi bi-inbox"></i>
        <span>Assigned to Me</span>
        <span class="badge bg-info ms-auto">{{ $stats['assigned'] }}</span>
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
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: white;
        border-radius: 6px;
        font-size: 1.2rem;
        color: #64748b;
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
                <a href="{{ route('reviewer.applications.index') }}" class="text-white text-decoration-none mb-2 d-inline-block opacity-75 no-print">
                    <i class="me-2"></i>Back to Applications
                </a>
                <h2 class="mb-1 fw-bold">Application Review</h2>
                <!-- <p style="color: #cbd5e1; margin: 0; font-size: 1.0625rem;">Nepal Oil Corporation E-Recruitment Portal</p> -->
               
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

                    $priorityColors = [
                        'critical' => 'bg-dark text-white',
                        'high' => 'bg-danger text-white',
                        'medium' => 'bg-warning text-dark',
                        'low' => 'bg-info text-white',
                        'normal' => 'bg-secondary text-white',
                    ];
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

    <div class="row">
        <!-- Main Content -->
        <div class="col-lg-8">
            <!-- Candidate Photo & Basic Info -->
            <div class="candidate-photo-section">
                @if($application->passport_photo)
                    <img src="{{ Storage::url($application->passport_photo) }}"
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
                    Submitted: {{ $application->submitted_at ? adToBS($application->submitted_at) . ' BS, ' . $application->submitted_at->format('h:i A') : 'N/A' }}
                </p>
                 
                </div>
                
            </div>

            <!-- Job Information -->
            <div class="info-card">
                <h5><i class=""></i>Vacancy Information</h5>
                <div class="info-row">
                    <div class="info-label">Position Applied:</div>
                    <div class="info-value"><strong>{{ $application->jobPosting->title ?? $application->applying_position ?? 'N/A' }}</strong></div>
                </div>
                <div class="info-row">
                    <div class="info-label">Advertisement No:</div>
                    <div class="info-value">{{ $application->advertisement_no ?? 'N/A' }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Department:</div>
                    <div class="info-value">{{ $application->jobPosting->department ?? $application->department ?? 'N/A' }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Category:</div>
                    <div class="info-value">
                        <span class="badge bg-info">{{ ucfirst($application->jobPosting->category ?? 'N/A') }}</span>
                    </div>
                </div>
                <div class="info-row">
                    <div class="info-label">Application Deadline:</div>
                    <div class="info-value">
                        @if($application->jobPosting->deadline)
                            <span class="text-danger fw-bold d-block">{{ $application->jobPosting->deadline->format('F d, Y') }} (AD)</span>
                            @if($application->jobPosting->deadline_bs)
                                <span class="text-muted">{{ $application->jobPosting->deadline_bs }} (BS)</span>
                            @endif
                        @else
                            N/A
                        @endif
                    </div>
                </div>
            </div>

            <div class="section-divider"></div>

            <!-- Personal Information -->
            <div class="info-card">
                <h5><i class=""></i>Personal Information</h5>
                <div class="info-row">
                    <div class="info-label">Name (English):</div>
                    <div class="info-value"><strong>{{ $application->name_english ?? 'N/A' }}</strong></div>
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
                    <div class="info-label">Age:</div>
                    <div class="info-value">{{ $application->age ?? 'N/A' }} years</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Gender:</div>
                    <div class="info-value">{{ ucfirst($application->gender ?? 'N/A') }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Blood Group:</div>
                    <div class="info-value">{{ $application->blood_group ?? 'N/A' }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Marital Status:</div>
                    <div class="info-value">{{ ucfirst($application->marital_status ?? 'N/A') }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Nationality:</div>
                    <div class="info-value">{{ $application->nationality ?? 'Nepali' }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Mother Tongue:</div>
                    <div class="info-value">{{ $application->mother_tongue ?? 'N/A' }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Religion:</div>
                    <div class="info-value">
                        {{ $application->religion == 'other' ? $application->religion_other : ucfirst($application->religion ?? 'N/A') }}
                    </div>
                </div>
            </div>

            <!-- Citizenship Information -->
            <div class="info-card">
                <h5><i class=""></i>Citizenship Information</h5>
                <div class="info-row">
                    <div class="info-label">Citizenship Number:</div>
                    <div class="info-value"><strong>{{ $application->citizenship_number ?? 'N/A' }}</strong></div>
                </div>
                <div class="info-row">
                    <div class="info-label">Issue Date (AD):</div>
                    <div class="info-value">{{ $application->citizenship_issue_date_ad ? $application->citizenship_issue_date_ad->format('Y-m-d') : 'N/A' }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Issue Date (BS):</div>
                    <div class="info-value">{{ $application->citizenship_issue_date_bs ?? 'N/A' }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Issue District:</div>
                    <div class="info-value">{{ $application->citizenship_issue_district ?? 'N/A' }}</div>
                </div>
            </div>

            <!-- Community & Ethnic Information -->
            <div class="info-card">
                <h5><i class=""></i>Community & Ethnic Information</h5>
                <div class="info-row">
                    <div class="info-label">Community:</div>
                    <div class="info-value">
                        {{ $application->community == 'other' ? $application->community_other : ucfirst($application->community ?? 'N/A') }}
                    </div>
                </div>
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
                            <i class=""></i> View Certificate
                        </a>
                    </div>
                </div>
                @endif
            </div>

            <!-- Disability & Employment Information -->
            <div class="info-card">
                <h5><i class=""></i>Employment & Disability Status</h5>
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
                @if($application->disability_certificate)
                <div class="info-row">
                    <div class="info-label">Disability Certificate:</div>
                    <div class="info-value">
                        <a href="{{ Storage::url($application->disability_certificate) }}" target="_blank" class="btn btn-sm btn-outline-dark">
                            <i class=""></i> View Certificate
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

            <div class="section-divider"></div>

            <!-- Family Information -->
            <div class="info-card">
                <h5><i class=""></i>Family Information</h5>

                <!-- Father's Information -->
                <h6 class="text-dark mt-3 mb-2"><i class=""></i>Father's Information</h6>
                <div class="info-row">
                    <div class="info-label">Name (English):</div>
                    <div class="info-value">{{ $application->father_name_english ?? 'N/A' }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Name (Nepali):</div>
                    <div class="info-value">{{ $application->father_name_nepali ?? 'N/A' }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Qualification:</div>
                    <div class="info-value">{{ $application->father_qualification ?? 'N/A' }}</div>
                </div>

                <!-- Mother's Information -->
                <h6 class="text-dark mt-3 mb-2"><i class=""></i>Mother's Information</h6>
                <div class="info-row">
                    <div class="info-label">Name (English):</div>
                    <div class="info-value">{{ $application->mother_name_english ?? 'N/A' }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Name (Nepali):</div>
                    <div class="info-value">{{ $application->mother_name_nepali ?? 'N/A' }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Qualification:</div>
                    <div class="info-value">{{ $application->mother_qualification ?? 'N/A' }}</div>
                </div>

                <!-- Grandfather's Information -->
                @if($application->grandfather_name_english || $application->grandfather_name_nepali)
                <h6 class="text-dark mt-3 mb-2"><i class=""></i>Grandfather's Information</h6>
                <div class="info-row">
                    <div class="info-label">Name (English):</div>
                    <div class="info-value">{{ $application->grandfather_name_english ?? 'N/A' }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Name (Nepali):</div>
                    <div class="info-value">{{ $application->grandfather_name_nepali ?? 'N/A' }}</div>
                </div>
                @endif

                <!-- Parent Occupation -->
                <div class="info-row">
                    <div class="info-label">Parent Occupation:</div>
                    <div class="info-value">
                        {{ $application->parent_occupation == 'other' ? $application->parent_occupation_other : ucfirst($application->parent_occupation ?? 'N/A') }}
                    </div>
                </div>

                <!-- Spouse Information (if married) -->
                @if($application->marital_status == 'married')
                <h6 class="text-dark mt-3 mb-2"><i class=""></i>Spouse Information</h6>
                <div class="info-row">
                    <div class="info-label">Name (English):</div>
                    <div class="info-value">{{ $application->spouse_name_english ?? 'N/A' }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Name (Nepali):</div>
                    <div class="info-value">{{ $application->spouse_name_nepali ?? 'N/A' }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Nationality:</div>
                    <div class="info-value">{{ $application->spouse_nationality ?? 'N/A' }}</div>
                </div>
                @endif
            </div>

            <!-- Address Information -->
            <div class="info-card">
                <h5><i class=""></i>Address Information</h5>

                <!-- Permanent Address -->
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

                <!-- Mailing Address -->
                <h6 class="text-dark mt-3 mb-2">Mailing/Temporary Address</h6>
                @if($application->same_as_permanent == 'yes')
                    <div class="alert alert-info-custom">
                        <i class=""></i>Same as Permanent Address
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

            <div class="section-divider"></div>

            <!-- Education -->
            <div class="info-card">
                <h5><i class=""></i>Educational Background</h5>
                <div class="info-row">
                    <div class="info-label">Education Level:</div>
                    <div class="info-value"><strong>{{ $application->education_level ?? 'N/A' }}</strong></div>
                </div>
                <div class="info-row">
                    <div class="info-label">Field of Study:</div>
                    <div class="info-value">{{ $application->field_of_study ?? 'N/A' }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Institution:</div>
                    <div class="info-value">{{ $application->institution_name ?? 'N/A' }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Graduation Year:</div>
                    <div class="info-value">{{ $application->graduation_year ?? 'N/A' }}</div>
                </div>
            </div>

            <!-- Work Experience -->
            <div class="info-card">
                <h5><i class=""></i>Work Experience</h5>
                @if($application->has_work_experience === 'yes')
                    <div class="info-row">
                        <div class="info-label">Previous Organization:</div>
                        <div class="info-value">{{ $application->previous_organization ?? 'N/A' }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Previous Position:</div>
                        <div class="info-value">{{ $application->previous_position ?? 'N/A' }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Total Years of Experience:</div>
                        <div class="info-value"><strong>{{ $application->years_of_experience ?? 'N/A' }} years</strong></div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Relevant Experience:</div>
                        <div class="info-value">{{ $application->relevant_experience ?? 'N/A' }}</div>
                    </div>
                @else
                    <div class="alert alert-info-custom">
                        <i class=" me-2"></i>No work experience declared
                    </div>
                @endif
            </div>

            <!-- Cover Letter -->
            @if($application->cover_letter)
            <div class="info-card">
                <h5><i class=""></i>Cover Letter</h5>
                <div class="p-3" style="background: #f8fafc; border-radius: 8px; border: 1px solid #e2e8f0;">
                    <p style="white-space: pre-wrap;">{{ $application->cover_letter }}</p>
                </div>
            </div>
            @endif

            <div class="section-divider"></div>

            <!-- All Documents -->
            <div class="info-card">
                <h5><i class=""></i>Uploaded Documents</h5>

                @if($application->passport_photo)
                <div class="document-item">
                    <div class="document-icon">
                        <i class=""></i>
                    </div>
                    <div class="document-info">
                        <p class="document-name">Passport Size Photo</p>
                        <p class="document-size">Candidate's passport photograph</p>
                    </div>
                    <a href="{{ Storage::url($application->passport_photo) }}" target="_blank" class="btn-view-doc">
                        <i class="me-1"></i>View
                    </a>
                </div>
                @endif

                @if($application->resume)
                <div class="document-item">
                    <div class="document-icon">
                        <i class=""></i>
                    </div>
                    <div class="document-info">
                        <p class="document-name">Resume / CV</p>
                        <p class="document-size">Detailed curriculum vitae</p>
                    </div>
                    <a href="{{ Storage::url($application->resume) }}" target="_blank" class="btn-view-doc">
                        <i class="me-1"></i>Download
                    </a>
                </div>
                @endif

                @if($application->citizenship_certificate)
                <div class="document-item">
                    <div class="document-icon">
                        <i class=""></i>
                    </div>
                    <div class="document-info">
                        <p class="document-name">Citizenship Certificate</p>
                        <p class="document-size">Nepali citizenship document</p>
                    </div>
                    <a href="{{ Storage::url($application->citizenship_certificate) }}" target="_blank" class="btn-view-doc">
                        <i class=" me-1"></i>View
                    </a>
                </div>
                @endif

                @if($application->educational_certificates)
                <div class="document-item">
                    <div class="document-icon">
                        <i class=""></i>
                    </div>
                    <div class="document-info">
                        <p class="document-name">Educational Certificates</p>
                        <p class="document-size">Academic transcripts and degrees</p>
                    </div>
                    <a href="{{ Storage::url($application->educational_certificates) }}" target="_blank" class="btn-view-doc">
                        <i class="me-1"></i>Download
                    </a>
                </div>
                @endif

                @if($application->experience_certificates)
                <div class="document-item">
                    <div class="document-icon">
                        <i class=""></i>
                    </div>
                    <div class="document-info">
                        <p class="document-name">Experience Certificates</p>
                        <p class="document-size">Work experience verification documents</p>
                    </div>
                    <a href="{{ Storage::url($application->experience_certificates) }}" target="_blank" class="btn-view-doc">
                        <i class="me-1"></i>Download
                    </a>
                </div>
                @endif

                @if($application->character_certificate)
                <div class="document-item">
                    <div class="document-icon">
                        <i class=""></i>
                    </div>
                    <div class="document-info">
                        <p class="document-name">Character Certificate</p>
                        <p class="document-size">Good character verification</p>
                    </div>
                    <a href="{{ Storage::url($application->character_certificate) }}" target="_blank" class="btn-view-doc">
                        <i class="me-1"></i>View
                    </a>
                </div>
                @endif

                @if($application->equivalency_certificate)
                <div class="document-item">
                    <div class="document-icon">
                        <i class=""></i>
                    </div>
                    <div class="document-info">
                        <p class="document-name">Equivalency Certificate</p>
                        <p class="document-size">Educational equivalency document</p>
                    </div>
                    <a href="{{ Storage::url($application->equivalency_certificate) }}" target="_blank" class="btn-view-doc">
                        <i class=" me-1"></i>View
                    </a>
                </div>
                @endif

                @if($application->cover_letter_file)
                <div class="document-item">
                    <div class="document-icon">
                        <i class=""></i>
                    </div>
                    <div class="document-info">
                        <p class="document-name">Cover Letter (File)</p>
                        <p class="document-size">Uploaded cover letter document</p>
                    </div>
                    <a href="{{ Storage::url($application->cover_letter_file) }}" target="_blank" class="btn-view-doc">
                        <i class="me-1"></i>Download
                    </a>
                </div>
                @endif

                @if($application->signature)
                <div class="document-item">
                    <div class="document-icon">
                        <i class=""></i>
                    </div>
                    <div class="document-info">
                        <p class="document-name">Signature</p>
                        <p class="document-size">Candidate's signature</p>
                    </div>
                    <a href="{{ Storage::url($application->signature) }}" target="_blank" class="btn-view-doc">
                        View
                    </a>
                </div>
                @endif

                @if($application->other_documents)
                <div class="document-item">
                    <div class="document-icon">
                    </div>
                    <div class="document-info">
                        <p class="document-name">Other Documents</p>
                        <p class="document-size">Additional supporting documents</p>
                    </div>
                    <a href="{{ Storage::url($application->other_documents) }}" target="_blank" class="btn-view-doc">
                        <i class="me-1"></i>Download
                    </a>
                </div>
                @endif

                @if(!$application->passport_photo && !$application->resume && !$application->citizenship_certificate &&
                    !$application->educational_certificates && !$application->experience_certificates &&
                    !$application->character_certificate && !$application->equivalency_certificate &&
                    !$application->cover_letter_file && !$application->signature && !$application->other_documents)
                <div class="alert alert-warning">
                    <i class="me-2"></i>No documents uploaded
                </div>
                @endif
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

        <!-- Sidebar - Review Actions & Payment -->
        <div class="col-lg-4">
            <div class="review-actions">
                <!-- Review Status Form -->
                <div class="info-card no-print">
                    <h5>Review Action</h5>

                    <!-- <div class="alert alert-info" style="background: linear-gradient(135deg, #e0f2fe 0%, #bae6fd 100%); border-left: 4px solid #0284c7; margin-bottom: 1rem;">
                        <small><i class="bi bi-info-circle me-1"></i><strong>Info:</strong> Reviewed applications go to Approver Portal for final decision. Rejected applications will notify candidates via SMS (upcoming feature).</small>
                    </div> -->

                    <form action="{{ route('reviewer.applications.updateStatus', $application->id) }}" method="POST" id="reviewForm">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label fw-bold">Action <span class="text-danger">*</span></label>
                            <select name="status" class="form-select" id="reviewStatus" required>
                                <option value="">Select Action...</option>
                                <option value="reviewed" {{ $application->status == 'reviewed' ? 'selected' : '' }}>Mark as Reviewed (Send to Approver)</option>
                                <option value="edit" {{ $application->status == 'edit' ? 'selected' : '' }}>Send Back for Edit</option>
                                <option value="rejected" {{ $application->status == 'rejected' ? 'selected' : '' }}>Reject Application (Missing Information)</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">
                                <span id="notesLabel">Comments/Notes</span> <span class="text-danger">*</span>
                            </label>
                            <textarea name="reviewer_notes" class="form-control" rows="6" id="reviewerNotes" placeholder="Add your comments..." required>{{ $application->reviewer_notes }}</textarea>
                            <small class="text-muted" id="notesHelp">
                                Please provide detailed feedback.
                            </small>
                        </div>

                        <!-- SMS Preview (for rejected applications) -->
                        <div id="smsPreview" style="display: none;" class="mb-3">
                            <div class="alert alert-warning" style="background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%); border-left: 4px solid #f59e0b;">
                                <strong> SMS Notification Preview:</strong>
                                <p class="mt-2 mb-0 small" style="font-family: monospace; background: white; padding: 0.75rem; border-radius: 6px;">
                                    <strong>Nepal Oil Corporation</strong><br>
                                    Your application (ID: {{ $application->id }}) has been rejected.<br><br>
                                    <strong>Reason:</strong> <span id="smsReasonPreview">[Your rejection reason will appear here]</span><br><br>
                                    Please review and reapply if eligible.<br>
                                    - NOC E-Recruitment
                                </p>
                                <small class="text-muted mt-2 d-block"> This SMS will be sent to: <strong>{{ $application->phone ?? 'N/A' }}</strong> (via Sparrow SMS - upcoming)</small>
                            </div>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-lg" id="submitBtn" style="background: #64748b; color: white;">
                                <i class=" me-2" id="submitIcon"></i>
                                <span id="submitText">Submit Action</span>
                            </button>
                            <button type="button" class="btn btn-outline-secondary" onclick="window.print()">
                                <i class=" me-2"></i>Print Application
                            </button>
                            <a href="{{ route('reviewer.applications.index') }}" class="btn btn-outline-secondary">
                                <i class=" me-2"></i>Back to List
                            </a>
                        </div>
                    </form>
                </div>

                <!-- Payment Information -->
                @php
                    $payment = \App\Models\Payment::where('draft_id', $application->id)->first();
                @endphp
                @if($payment)
                <div class="info-card mt-3">
                    <h5>Payment Information</h5>
                    <div class="payment-details {{ $payment->status != 'completed' ? 'pending' : '' }}">
                        <div class="text-center mb-2">
                            <span class="badge bg-{{ $payment->status == 'completed' ? 'success' : 'warning' }} px-3 py-2">
                                <i class="bi bi-{{ $payment->status == 'completed' ? 'check-circle-fill' : 'clock-fill' }} me-1"></i>
                                {{ ucfirst($payment->status) }}
                            </span>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Amount:</div>
                            <div class="info-value"><strong>NPR {{ number_format($payment->amount, 2) }}</strong></div>
                        </div>
                        @if($payment->transaction_id)
                        <div class="info-row">
                            <div class="info-label">Transaction ID:</div>
                            <div class="info-value"><small style="word-break: break-all;">{{ $payment->transaction_id }}</small></div>
                        </div>
                        @endif
                        @if($payment->gateway)
                        <div class="info-row">
                            <div class="info-label">Payment Gateway:</div>
                            <div class="info-value">
                                @php
                                    $gatewayIcons = [
                                        'esewa' => ['icon' => 'bi-wallet2', 'color' => '#60bb46', 'name' => 'eSewa'],
                                        'khalti' => ['icon' => 'bi-credit-card-2-front', 'color' => '#5c2d91', 'name' => 'Khalti'],
                                        'connectips' => ['icon' => 'bi-bank', 'color' => '#0066cc', 'name' => 'ConnectIPS'],
                                        'fonepay' => ['icon' => 'bi-phone', 'color' => '#ff0000', 'name' => 'FonePay'],
                                        'imepay' => ['icon' => 'bi-credit-card', 'color' => '#ff0000', 'name' => 'IME Pay'],
                                    ];
                                    $gateway = strtolower($payment->gateway);
                                    $gatewayInfo = $gatewayIcons[$gateway] ?? ['name' => ucfirst($payment->gateway)];
                                @endphp
                                <span class="gateway-badge" style="background-color: {{ $gatewayInfo['color'] }}; color: white;">
                                    <i class="bi {{ $gatewayInfo['icon'] }}"></i>{{ $gatewayInfo['name'] }}
                                </span>
                            </div>
                        </div>
                        @endif
                        
                        <div class="info-row">
                            <div class="info-label">Payment Date:</div>
                            <div class="info-value"><small>{{ $payment->created_at ? adToBS($payment->created_at) . ' BS, ' . $payment->created_at->format('h:i A') : 'N/A' }}</small></div>
                        </div>
                        @if($payment->payment_method)
                        <div class="info-row">
                            <div class="info-label">Method:</div>
                            <div class="info-value">{{ ucfirst($payment->payment_method) }}</div>
                        </div>
                        @endif
                        @if($payment->bank_name)
                        <div class="info-row">
                            <div class="info-label">Bank:</div>
                            <div class="info-value">{{ $payment->bank_name }}</div>
                        </div>
                        @endif
                    </div>
                </div>
                @else
                <div class="info-card mt-3">
                    <h5>Payment Status</h5>
                    <div class="alert alert-warning text-center">
                        No payment record found
                    </div>
                </div>
                @endif

                <!-- Review History -->
                @if($application->reviewed_at)
                <div class="info-card mt-3">
                    <h5>Review History</h5>
                    <div class="info-row">
                        <div class="info-label">Reviewed By:</div>
                        <div class="info-value">{{ $application->reviewer->name ?? 'N/A' }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Reviewed At:</div>
                        <div class="info-value">{{ $application->reviewed_at->format('M d, Y h:i A') }}</div>
                    </div>
                    @if($application->reviewer_notes)
                    <div class="mt-3 p-2" style="background: #f8fafc; border-radius: 6px; border: 1px solid #e2e8f0;">
                        <small class="text-muted d-block mb-1">Previous Notes:</small>
                        <p class="mb-0" style="font-size: 0.9rem;">{{ $application->reviewer_notes }}</p>
                    </div>
                    @endif
                </div>
                @endif

                <!-- Exam Information (if scheduled) -->
                @if($application->exam_date)
                <div class="info-card mt-3">
                    <h5>Exam Schedule</h5>
                    <div class="info-row">
                        <div class="info-label">Date:</div>
                        <div class="info-value"><strong>{{ $application->exam_date }}</strong></div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Time:</div>
                        <div class="info-value">{{ $application->exam_time ?? 'TBA' }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Venue:</div>
                        <div class="info-value">{{ $application->exam_venue ?? 'TBA' }}</div>
                    </div>
                    @if($application->roll_number)
                    <div class="info-row">
                        <div class="info-label">Roll Number:</div>
                        <div class="info-value"><span class="badge bg-light">{{ $application->roll_number }}</span></div>
                    </div>
                    @endif
                </div>
                @endif

                <!-- Quick Stats -->
                <div class="info-card mt-3">
                    <h5>Quick Stats</h5>
                    <div class="info-row">
                        <div class="info-label">Application ID:</div>
                        <div class="info-value"><strong>
                        {{ $application->id }}</strong></div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Submitted At:</div>
                        <div class="info-value">{{ $application->submitted_at ? adToBS($application->submitted_at) . ' BS, ' . $application->submitted_at->format('h:i A') : 'N/A' }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Reviewed At:</div>
                        <div class="info-value">
                            @if($application->reviewed_at)
                                {{-- Application has been reviewed --}}
                                @php
                                    $reviewDays = $application->submitted_at ? (int)$application->submitted_at->diffInDays($application->reviewed_at, false) : 0;
                                @endphp
                                <span class="badge bg-success">
                                    Reviewed
                                </span>
                                <span class="text-muted ms-2">({{ $reviewDays }} {{ $reviewDays == 1 ? 'day' : 'days' }})</span>
                            @elseif($application->submitted_at)
                                {{-- Application is pending review --}}
                                @php
                                    $daysPending = (int)$application->submitted_at->diffInDays(now(), false);
                                @endphp
                                <strong>{{ $daysPending }} {{ $daysPending == 1 ? 'day' : 'days' }}</strong>
                            @else
                                <span class="badge bg-secondary">Not Submitted</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
// Handle status change
document.getElementById('reviewStatus').addEventListener('change', function() {
    const status = this.value;
    const notesLabel = document.getElementById('notesLabel');
    const notesHelp = document.getElementById('notesHelp');
    const reviewerNotes = document.getElementById('reviewerNotes');
    const submitBtn = document.getElementById('submitBtn');
    const submitIcon = document.getElementById('submitIcon');
    const submitText = document.getElementById('submitText');
    const smsPreview = document.getElementById('smsPreview');

    if (status === 'reviewed') {
        // Reviewed - send to approver
        notesLabel.textContent = 'Review Comments';
        notesHelp.textContent = 'Add your assessment and recommendations for the Approver.';
        reviewerNotes.placeholder = 'Candidate\'s qualifications, strengths, weaknesses, and overall assessment...';
        submitBtn.className = 'btn btn-success btn-lg';
        submitBtn.style.background = '';
        submitIcon.className = ' me-2';
        submitText.textContent = 'Submit Review';
        smsPreview.style.display = 'none';
    } else if (status === 'rejected') {
        // Rejected - notify candidate
        notesLabel.textContent = 'Rejection Reason';
        notesHelp.innerHTML = '<i class="me-1"></i><strong>Important:</strong> Clearly explain what is missing or incorrect. This will be sent to the candidate via SMS.';
        reviewerNotes.placeholder = 'Example: "Missing citizenship certificate copy" or "Educational certificates are not clear/readable" or "Work experience documents not provided"...';
        submitBtn.className = 'btn btn-danger btn-lg';
        submitBtn.style.background = '';
        submitIcon.className = ' me-2';
        submitText.textContent = 'Reject Application';
        smsPreview.style.display = 'block';
    } else {
        submitBtn.className = 'btn btn-lg';
        submitBtn.style.background = '#64748b';
        submitBtn.style.color = 'white';
        submitIcon.className = 'me-2';
        submitText.textContent = 'Submit Action';
        smsPreview.style.display = 'none';
    }
});

// Update SMS preview as user types
document.getElementById('reviewerNotes').addEventListener('input', function() {
    const status = document.getElementById('reviewStatus').value;
    if (status === 'rejected') {
        const reason = this.value.trim() || '[Your rejection reason will appear here]';
        document.getElementById('smsReasonPreview').textContent = reason;
    }
});

// Form submission
document.getElementById('reviewForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const formData = new FormData(this);
    const status = formData.get('status');
    const notes = formData.get('reviewer_notes');

    if (!status) {
        alert('⚠️ Please select an action (Reviewed or Rejected)');
        return;
    }

    if (!notes || notes.trim() === '') {
        alert('⚠️ Please add comments/notes before submitting');
        return;
    }

    let confirmMessage = '';
    if (status === 'reviewed') {
        confirmMessage = 'Are you sure you want to mark this application as REVIEWED?\n\n✓ Application will be sent to the Approver Portal for final decision.\n✓ Your review notes will be forwarded to the Approver.\n\nThis action will be recorded in the system.';
    } else if (status === 'rejected') {
        confirmMessage = 'Are you sure you want to REJECT this application?\n\n✓ Candidate will be notified via SMS (when Sparrow SMS is integrated).\n✓ Your rejection reason will be sent to: {{ $application->phone ?? "N/A" }}\n\n⚠️ Make sure your rejection reason is clear and helpful.\n\nThis action will be recorded in the system.';
    }

    if (confirm(confirmMessage)) {
        fetch(this.action, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                'Accept': 'application/json',
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                status: status,
                reviewer_notes: notes
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('✅ ' + data.message);
                window.location.href = '{{ route("reviewer.applications.index") }}';
            } else {
                alert('❌ ' + (data.message || 'Error updating status'));
            }
        })
        .catch(error => {
            alert('❌ Error updating status. Please try again.');
            console.error('Error:', error);
        });
    }
});
</script>
@endsection
