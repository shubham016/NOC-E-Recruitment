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
    <a href="{{ route('reviewer.applications.index') }}" class="sidebar-menu-item active">
        <i class="bi bi-inbox"></i>
        <span>Assigned to Me</span>
    </a>
    <a href="{{ route('reviewer.myprofile') }}" class="sidebar-menu-item">
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
        background: #f8fafc;
        border-radius: 8px;
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

    .document-icon i {
        font-size: 1.5rem;
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
        background: #ff0000;
        color: white;
        text-decoration: none;
        border-radius: 6px;
        font-size: 0.9rem;
        transition: all 0.2s;
    }

    .btn-view-doc:hover {
        background: #cc0000;
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
        background: linear-gradient(135deg, #fedbdb 0%, #febfbf 100%);
        border-left: 4px solid #fa0000;
        padding: 1rem;
        border-radius: 8px;
        margin-bottom: 1rem;
    }

    .section-divider {
        height: 2px;
        background: linear-gradient(to right, #e5e7eb 0%, #ff0000 50%, #e5e7eb 100%);
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
                    <i class="bi bi-arrow-left me-2"></i>Back to Applications
                </a>
                <h2 class="mb-1 fw-bold">Application Review</h2>
            </div>
            <div class="text-end">
                @php
                    $statusColors = [
                        'pending' => 'bg-warning text-dark',
                        'assigned' => 'bg-danger text-white',
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

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <!-- Main Content -->
<<<<<<< HEAD
        <div class="col-lg-12">
=======
        <div class="col-lg-8">
>>>>>>> efe7d213166d7eb2c3aef5455d337ce01292fe6f

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
<<<<<<< HEAD
=======
                </div>
            </div>

            <!-- Vacancy Information -->
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
                            @php $deadlineBS = $application->jobPosting->deadline_bs ?: adToBS($application->jobPosting->deadline->format('Y-m-d')); @endphp
                            <span class="text-danger fw-bold d-block">{{ $deadlineBS }} (BS)</span>
                            <span class="text-muted">{{ $application->jobPosting->deadline->format('F d, Y') }} (AD)</span>
                        @else
                            N/A
                        @endif
                    </div>
>>>>>>> efe7d213166d7eb2c3aef5455d337ce01292fe6f
                </div>
            </div>

            <!-- Vacancy Information -->
<div class="info-card">
    <h5><i class=""></i>Vacancy Information</h5>

    <div class="row">
        <!-- Left Column -->
        <div class="col-md-6">
            <div class="info-row">
                <div class="info-label">Position Applied:</div>
                <div class="info-value">
                    <strong>{{ $application->jobPosting->title ?? $application->applying_position ?? 'N/A' }}</strong>
                </div>
            </div>

            <div class="info-row">
                <div class="info-label">Advertisement No:</div>
                <div class="info-value">{{ $application->advertisement_no ?? 'N/A' }}</div>
            </div>

            <div class="info-row">
                <div class="info-label">Department:</div>
                <div class="info-value">
                    {{ $application->jobPosting->department ?? $application->department ?? 'N/A' }}
                </div>
            </div>
        </div>

        <!-- Right Column -->
        <div class="col-md-6">
            <div class="info-row">
                <div class="info-label">Category:</div>
                <div class="info-value">
                    <span class="badge bg-info">
                        {{ ucfirst($application->jobPosting->category ?? 'N/A') }}
                    </span>
                </div>
            </div>

            <div class="info-row">
                <div class="info-label">Application Deadline:</div>
                <div class="info-value">
                    @if($application->jobPosting->deadline)
                        @php 
                            $deadlineBS = $application->jobPosting->deadline_bs 
                                ?: adToBS($application->jobPosting->deadline->format('Y-m-d')); 
                        @endphp
                        <span class="text-danger fw-bold d-block">{{ $deadlineBS }} (BS)</span>
                        <span class="text-muted">
                            {{ $application->jobPosting->deadline->format('F d, Y') }} (AD)
                        </span>
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
    <h5><i class=""></i>Personal Information</h5>

    <div class="row">
        <!-- Left Column -->
        <div class="col-md-6">
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
                <div class="info-label">Email:</div>
                <div class="info-value">{{ $application->email ?? 'N/A' }}</div>
            </div>

            <div class="info-row">
                <div class="info-label">Age:</div>
                <div class="info-value">{{ $application->age ?? 'N/A' }} years</div>
            </div>

            <div class="info-row">
                <div class="info-label">Alternate Phone Number:</div>
                <div class="info-value">{{ $application->alternate_phone_number ?? 'N/A' }}</div>
            </div>
        </div>

        <!-- Right Column -->
        <div class="col-md-6">
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
<<<<<<< HEAD
            </div>
        </div>
    </div>
</div>
            <!-- Citizenship Information -->
<div class="info-card">
    <h5><i class=""></i>Citizenship Information</h5>

    <div class="row">
        <!-- Left Column -->
        <div class="col-md-6">
            <div class="info-row">
                <div class="info-label">Citizenship Number:</div>
                <div class="info-value">
                    <strong>{{ $application->citizenship_number ?? 'N/A' }}</strong>
                </div>
            </div>

            <div class="info-row">
                <div class="info-label">Issue Date (AD):</div>
                <div class="info-value">
                    {{ $application->citizenship_issue_date_ad 
                        ? \Carbon\Carbon::parse($application->citizenship_issue_date_ad)->format('Y-m-d') 
                        : 'N/A' }}
=======
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
                    <div class="info-value">{{ $application->citizenship_issue_date_ad ? \Carbon\Carbon::parse($application->citizenship_issue_date_ad)->format('Y-m-d') : 'N/A' }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Issue Date (BS):</div>
                    <div class="info-value">{{ $application->citizenship_issue_date_bs ?? 'N/A' }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Issue District:</div>
                    <div class="info-value">{{ $application->citizenship_issue_district ?? 'N/A' }}</div>
>>>>>>> efe7d213166d7eb2c3aef5455d337ce01292fe6f
                </div>
            </div>
        </div>

        <!-- Right Column -->
        <div class="col-md-6">
            <div class="info-row">
                <div class="info-label">Issue Date (BS):</div>
                <div class="info-value">
                    {{ $application->citizenship_issue_date_bs ?? 'N/A' }}
                </div>
            </div>

            <div class="info-row">
                <div class="info-label">Issue District:</div>
                <div class="info-value">
                    {{ $application->citizenship_issue_district ?? 'N/A' }}
                </div>
            </div>
        </div>
    </div>
</div>

            <!-- Community & Ethnic Information -->
<<<<<<< HEAD
<div class="info-card">
    <h5><i class=""></i>Community & Ethnic Information</h5>
=======
            <div class="info-card">
                <h5><i class=""></i>Community & Ethnic Information</h5>
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
                <div class="info-row">
                    <div class="info-label">Mother Tongue:</div>
                    <div class="info-value">{{ $application->mother_tongue ?? 'N/A' }}</div>
                </div>
            </div>
>>>>>>> efe7d213166d7eb2c3aef5455d337ce01292fe6f

    <div class="row">
        <!-- Left Column -->
        <div class="col-md-6">
            <div class="info-row">
                <div class="info-label">Religion:</div>
                <div class="info-value">
                    {{ $application->religion == 'other' ? $application->religion_other : ucfirst($application->religion ?? 'N/A') }}
                </div>
            </div>

<<<<<<< HEAD
            <div class="info-row">
                <div class="info-label">Community:</div>
                <div class="info-value">
                    {{ $application->community == 'other' ? $application->community_other : ucfirst($application->community ?? 'N/A') }}
                </div>
=======
            <div class="section-divider"></div>

            <!-- Family Information -->
            <div class="info-card">
                <h5><i class=""></i>Family Information</h5>

                <!-- Grandfather's Information -->
                <div class="info-row">
                    <div class="info-label">Grandfather Name:</div>
                    <div class="info-value">{{ $application->grandfather_name_english ?? 'N/A' }}</div>
                </div>

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

                <!-- Parent Occupation -->
                <div class="info-row">
                    <div class="info-label">Parent's Occupation:</div>
                    <div class="info-value">
                        {{ $application->parent_occupation == 'other' ? $application->parent_occupation_other : ucfirst($application->parent_occupation ?? 'N/A') }}
                    </div>
                </div>

                <!-- Spouse Information -->
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
>>>>>>> efe7d213166d7eb2c3aef5455d337ce01292fe6f
            </div>

            <div class="info-row">
                <div class="info-label">Ethnic Group:</div>
                <div class="info-value">
                    {{ $application->ethnic_group == 'other' ? $application->ethnic_group_other : ucfirst($application->ethnic_group ?? 'N/A') }}
                </div>
            </div>
        </div>

        <!-- Right Column -->
        <div class="col-md-6">
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

            <div class="info-row">
                <div class="info-label">Mother Tongue:</div>
                <div class="info-value">{{ $application->mother_tongue ?? 'N/A' }}</div>
            </div>
        </div>
    </div>
</div>

           <!-- Disability & Employment Information -->
<div class="info-card">
    <h5><i class=""></i>Employment & Disability Status</h5>

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
    </div>
</div>

         

           <!-- Family Information -->
<div class="info-card">
    <h5><i class=""></i>Family Information</h5>

    <div class="row">
        <!-- Left Column -->
        <div class="col-md-6">
            <!-- Grandfather -->
            <div class="info-row">
                <div class="info-label">Grandfather Name:</div>
                <div class="info-value">{{ $application->grandfather_name_english ?? 'N/A' }}</div>
            </div>

            <!-- Father -->
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
        </div>

        <!-- Right Column -->
        <div class="col-md-6">
            <!-- Mother -->
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

            <!-- Parent Occupation -->
            <div class="info-row">
                <div class="info-label">Parent's Occupation:</div>
                <div class="info-value">
                    {{ $application->parent_occupation == 'other' ? $application->parent_occupation_other : ucfirst($application->parent_occupation ?? 'N/A') }}
                </div>
            </div>

            <!-- Spouse -->
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
    </div>
</div>
            <!-- Address Information -->
<div class="info-card">
    <h5><i class=""></i>Address Information</h5>

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

        <!-- Right Column: Mailing Address -->
        <div class="col-md-6">
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
    </div>
</div>


<<<<<<< HEAD

           <!-- Educational Background -->
<div class="info-card">
    <h5><i class=""></i>Educational Background</h5>

    <div class="row">
        <!-- Left Column -->
        <div class="col-md-6">
            <div class="info-row">
                <div class="info-label">Education Level:</div>
                <div class="info-value">
                    <strong>{{ $application->education_level ?? 'N/A' }}</strong>
                </div>
            </div>

            <div class="info-row">
                <div class="info-label">Field of Study:</div>
                <div class="info-value">{{ $application->field_of_study ?? 'N/A' }}</div>
=======
            <!-- Educational Background -->
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
                @if(strtolower($application->has_work_experience ?? '') == 'yes')
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
>>>>>>> efe7d213166d7eb2c3aef5455d337ce01292fe6f
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
                

<<<<<<< HEAD
                <div class="document-info">
                    <p class="document-name">Citizenship Id</p>
                    <p class="document-size">Citizenship Id</p>

                    <img src="{{ Storage::url($application->citizenship_id_document) }}"
                    style="width:100%; max-height:520px; object-fit:contain; border:1px solid #ddd; border-radius:8px; margin-top:8px;">
                </div>
=======
            <!-- Uploaded Documents -->
            <div class="info-card">
                <h5><i class=""></i>Uploaded Documents</h5>

                @if($application->passport_size_photo)
                <div class="document-item">
                    <div class="document-icon"><i class=""></i></div>
                    <div class="document-info">
                        <p class="document-name">Passport Size Photo</p>
                        <p class="document-size">Candidate's passport photograph</p>
                    </div>
                    <a href="{{ Storage::url($application->passport_size_photo) }}" target="_blank" class="btn-view-doc">
                        <i class="me-1"></i>View
                    </a>
                </div>
                @endif

                @if($application->resume)
                <div class="document-item">
                    <div class="document-icon"><i class=""></i></div>
                    <div class="document-info">
                        <p class="document-name">Resume / CV</p>
                        <p class="document-size">Detailed curriculum vitae</p>
                    </div>
                    <a href="{{ Storage::url($application->resume) }}" target="_blank" class="btn-view-doc">
                        <i class="me-1"></i>Download
                    </a>
                </div>
                @endif

                @if($application->work_experience)
                <div class="document-item">
                    <div class="document-icon"><i class=""></i></div>
                    <div class="document-info">
                        <p class="document-name">Work Experience</p>
                        <p class="document-size">Work experience verification documents</p>
                    </div>
                    <a href="{{ Storage::url($application->work_experience) }}" target="_blank" class="btn-view-doc">
                        <i class="me-1"></i>Download
                    </a>
                </div>
                @endif

                @if($application->citizenship_certificate || $application->citizenship_id_document)
                <div class="document-item">
                    <div class="document-icon"><i class=""></i></div>
                    <div class="document-info">
                        <p class="document-name">Citizenship Certificate</p>
                        <p class="document-size">Nepali citizenship document</p>
                    </div>
                    <a href="{{ Storage::url($application->citizenship_certificate ?? $application->citizenship_id_document) }}" target="_blank" class="btn-view-doc">
                        <i class=" me-1"></i>View
                    </a>
                </div>
                @endif

                @if($application->educational_certificates || $application->transcript)
                <div class="document-item">
                    <div class="document-icon"><i class=""></i></div>
                    <div class="document-info">
                        <p class="document-name">Educational Certificates</p>
                        <p class="document-size">Academic transcripts and degrees</p>
                    </div>
                    <a href="{{ Storage::url($application->educational_certificates ?? $application->transcript) }}" target="_blank" class="btn-view-doc">
                        <i class="me-1"></i>Download
                    </a>
                </div>
                @endif

                @if($application->experience_certificates)
                <div class="document-item">
                    <div class="document-icon"><i class=""></i></div>
                    <div class="document-info">
                        <p class="document-name">Experience Certificates</p>
                        <p class="document-size">Work experience verification documents</p>
                    </div>
                    <a href="{{ Storage::url($application->experience_certificates) }}" target="_blank" class="btn-view-doc">
                        <i class="me-1"></i>Download
                    </a>
                </div>
                @endif

                @if($application->character_certificate || $application->character)
                <div class="document-item">
                    <div class="document-icon"><i class=""></i></div>
                    <div class="document-info">
                        <p class="document-name">Character Certificate</p>
                        <p class="document-size">Good character verification</p>
                    </div>
                    <a href="{{ Storage::url($application->character_certificate ?? $application->character) }}" target="_blank" class="btn-view-doc">
                        <i class="me-1"></i>View
                    </a>
                </div>
                @endif

                @if($application->equivalency_certificate || $application->equivalent)
                <div class="document-item">
                    <div class="document-icon"><i class=""></i></div>
                    <div class="document-info">
                        <p class="document-name">Equivalency Certificate</p>
                        <p class="document-size">Educational equivalency document</p>
                    </div>
                    <a href="{{ Storage::url($application->equivalency_certificate ?? $application->equivalent) }}" target="_blank" class="btn-view-doc">
                        <i class=" me-1"></i>View
                    </a>
                </div>
                @endif

                @if($application->ethnic_certificate)
                <div class="document-item">
                    <div class="document-icon"><i class=""></i></div>
                    <div class="document-info">
                        <p class="document-name">Ethnic Certificate</p>
                        <p class="document-size">Candidate's ethnicity proof</p>
                    </div>
                    <a href="{{ Storage::url($application->ethnic_certificate) }}" target="_blank" class="btn-view-doc">
                        <i class="me-1"></i>View
                    </a>
                </div>
                @endif

                @if($application->disability_certificate)
                <div class="document-item">
                    <div class="document-icon"><i class=""></i></div>
                    <div class="document-info">
                        <p class="document-name">Disability Certificate</p>
                        <p class="document-size">Candidate's proof of disability</p>
                    </div>
                    <a href="{{ Storage::url($application->disability_certificate) }}" target="_blank" class="btn-view-doc">
                        <i class="me-1"></i>View
                    </a>
                </div>
                @endif

                @if($application->noc_id_card)
                <div class="document-item">
                    <div class="document-icon"><i class=""></i></div>
                    <div class="document-info">
                        <p class="document-name">NOC Employee ID Card</p>
                        <p class="document-size">Candidate's NOC ID Card</p>
                    </div>
                    <a href="{{ Storage::url($application->noc_id_card) }}" target="_blank" class="btn-view-doc">
                        <i class="me-1"></i>View
                    </a>
                </div>
                @endif

                @if($application->cover_letter_file)
                <div class="document-item">
                    <div class="document-icon"><i class=""></i></div>
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
                    <div class="document-icon"><i class=""></i></div>
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
                    <div class="document-icon"><i class=""></i></div>
                    <div class="document-info">
                        <p class="document-name">Other Documents</p>
                        <p class="document-size">Additional supporting documents</p>
                    </div>
                    <a href="{{ Storage::url($application->other_documents) }}" target="_blank" class="btn-view-doc">
                        <i class="me-1"></i>Download
                    </a>
                </div>
                @endif

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
                    <i class="me-2"></i>No documents uploaded
                </div>
                @endif
>>>>>>> efe7d213166d7eb2c3aef5455d337ce01292fe6f
            </div>
            @endif

<<<<<<< HEAD
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

=======
>>>>>>> efe7d213166d7eb2c3aef5455d337ce01292fe6f
            <!-- Admin Notes -->
            @if($application->admin_notes)
            <div class="info-card">
                <h5>Admin Notes</h5>
                <div class="alert alert-info-custom">
                    <p class="mb-0"><strong>Admin's Note:</strong></p>
                    <p class="mb-0 mt-2">{{ $application->admin_notes }}</p>
                </div>
            </div>
            @endif

            <!-- Priority Note -->
            @if($application->priority_note)
            <div class="info-card">
                <h5>Priority Note</h5>
                <div class="alert" style="background: #fef3c7; border-left: 4px solid #f59e0b;">
                    <p class="mb-0">{{ $application->priority_note }}</p>
                </div>
            </div>
            @endif

<<<<<<< HEAD
        </div>
<!-- Payment -->
@php
    $payment = \App\Models\Payment::where('draft_id', $application->id)->first();
@endphp

@if($payment)
<div class="info-card mt-3">
    <h5>Payment Information</h5>

    <div class="row">
        <!-- Left Column -->
        <div class="col-md-6">
            <div class="info-row">
                <div class="info-label">Amount:</div>
                <div class="info-value">
                    <strong>NPR {{ number_format($payment->amount, 2) }}</strong>
                </div>
            </div>

            <div class="info-row">
                <div class="info-label">Status:</div>
                <div class="info-value">
                    {{ ucfirst($payment->status) }}
                </div>
            </div>
=======
>>>>>>> efe7d213166d7eb2c3aef5455d337ce01292fe6f
        </div>

        <!-- Right Column -->
        <div class="col-md-6">
            <div class="info-row">
                <div class="info-label">Payment Gateway:</div>
                <div class="info-value">
                    {{ ucfirst($payment->gateway) }}
                </div>
            </div>

            <div class="info-row">
                <div class="info-label">Transaction ID:</div>
                <div class="info-value">
                    {{ ucfirst($payment->transaction_id) }}
                </div>
            </div>
        </div>
    </div>
</div>
@endif
      <div class="container-fluid">
    <div class="row">


        

        <!-- ========================= -->
        <!-- RIGHT SIDE: SIDEBAR -->
        <!-- ========================= -->
        <div class="col-lg-12">
            <div class="review-actions">

<<<<<<< HEAD
                <!-- Sidebar - Review Actions & Payment -->
=======
                <!-- Review Status Form -->
>>>>>>> efe7d213166d7eb2c3aef5455d337ce01292fe6f
                <div class="info-card no-print">
                    <h5>Review Action</h5>

                    <form action="{{ route('reviewer.applications.updateStatus', $application->id) }}" method="POST" id="reviewForm">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label fw-bold">Action <span class="text-danger">*</span></label>
                            <select name="status" class="form-select" id="reviewStatus" required>
                                <option value="">Select Action...</option>
                                <option value="reviewed" {{ $application->status == 'reviewed' ? 'selected' : '' }}>Mark as Reviewed</option>
                                <option value="edit" {{ $application->status == 'edit' ? 'selected' : '' }}>Send Back for Edit</option>
                                <option value="rejected" {{ $application->status == 'rejected' ? 'selected' : '' }}>Reject Application</option>
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

                        <div id="smsPreview" style="display: none;" class="mb-3">
                            <div class="alert alert-warning" style="background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%); border-left: 4px solid #f59e0b;">
                                <strong>SMS Notification Preview:</strong>
                                <p class="mt-2 mb-0 small" style="font-family: monospace; background: white; padding: 0.75rem; border-radius: 6px;">
                                    <strong>Nepal Oil Corporation</strong><br>
                                    Your application (ID: {{ $application->id }}) has been rejected.<br><br>
                                    <strong>Reason:</strong> <span id="smsReasonPreview">[Your rejection reason will appear here]</span><br><br>
                                    Please review and reapply if eligible.<br>
                                    - NOC E-Recruitment
                                </p>
<<<<<<< HEAD
                                <small class="text-muted mt-2 d-block">This SMS will be sent to: <strong>{{ $application->phone ?? 'N/A' }}</strong></small>
=======
                                <small class="text-muted mt-2 d-block">This SMS will be sent to: <strong>{{ $application->phone ?? 'N/A' }}</strong> (via Sparrow SMS - upcoming)</small>
>>>>>>> efe7d213166d7eb2c3aef5455d337ce01292fe6f
                            </div>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-lg" style="background: #64748b; color: white;">
                                Submit Action
                            </button>
                            <button type="button" class="btn btn-outline-secondary" onclick="window.print()">
                                Print Application
                            </button>
                            <a href="{{ route('reviewer.applications.index') }}" class="btn btn-outline-secondary">
                                Back to List
                            </a>
                        </div>
                    </form>
                </div>

<<<<<<< HEAD
               
=======
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
                                        'esewa'      => ['icon' => 'bi-wallet2',              'color' => '#60bb46', 'name' => 'eSewa'],
                                        'khalti'     => ['icon' => 'bi-credit-card-2-front',  'color' => '#5c2d91', 'name' => 'Khalti'],
                                        'connectips' => ['icon' => 'bi-bank',                 'color' => '#0066cc', 'name' => 'ConnectIPS'],
                                        'fonepay'    => ['icon' => 'bi-phone',                'color' => '#ff0000', 'name' => 'FonePay'],
                                        'imepay'     => ['icon' => 'bi-credit-card',          'color' => '#ff0000', 'name' => 'IME Pay'],
                                    ];
                                    $gateway     = strtolower($payment->gateway);
                                    $gatewayInfo = $gatewayIcons[$gateway] ?? ['icon' => 'bi-credit-card', 'color' => '#64748b', 'name' => ucfirst($payment->gateway)];
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
>>>>>>> efe7d213166d7eb2c3aef5455d337ce01292fe6f

                <!-- Timeline -->
                <!-- <div class="info-card mt-3">
                    <h5>Timeline</h5>

<<<<<<< HEAD
=======
                <!-- Application Timeline -->
                <div class="info-card mt-3">
                    <h5>
                        <i class="bi bi-clock-history text-primary me-2"></i>Timeline
                    </h5>
                    <div class="timeline">
                        <div class="mb-3">
                            <div class="small text-muted">Applied</div>
                            <div class="fw-semibold">
                                {{ $application->created_at->format('M d, Y h:i A') }}
                                <small class="text-muted d-block">{{ adToBS($application->created_at) }} (BS)</small>
                            </div>
                        </div>
                        @if($application->reviewed_at)
                        <div class="mb-3">
                            <div class="small text-muted">Reviewed</div>
                            <div class="fw-semibold">
                                {{ $application->reviewed_at->format('M d, Y h:i A') }}
                                <small class="text-muted d-block">
                                    {{ adToBS($application->reviewed_at->format('Y-m-d')) }} (BS)
                                </small>
                            </div>
                        </div>
                        @endif
                        @if($application->approved_at)
                        <div class="mb-3">
                            <div class="small text-muted">Approved</div>
                            <div class="fw-semibold">
                                {{ \Carbon\Carbon::parse($application->approved_at)->format('M d, Y h:i A') }}
                                <small class="text-muted d-block">
                                    {{ adToBS(\Carbon\Carbon::parse($application->approved_at)->format('Y-m-d')) }} (BS)
                                </small>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Exam Information (if scheduled) -->
                @if($application->exam_date)
                <div class="info-card mt-3">
                    <h5>Exam Schedule</h5>
>>>>>>> efe7d213166d7eb2c3aef5455d337ce01292fe6f
                    <div class="info-row">
                        <div class="info-label">Applied:</div>
                        <div class="info-value">{{ $application->created_at }}</div>
                    </div>
                </div> -->

                <!-- Quick Stats -->
                <!-- <div class="info-card mt-3">
                    <h5>Quick Stats</h5>

                    <div class="info-row">
                        <div class="info-label">Application ID:</div>
                        <div class="info-value"><strong>{{ $application->id }}</strong></div>
                    </div>
<<<<<<< HEAD
                </div> -->
=======
                    <div class="info-row">
                        <div class="info-label">Submitted At:</div>
                        <div class="info-value">
                            @php $submittedDate = $application->submitted_at ?: $application->created_at; @endphp
                            {{ $submittedDate ? adToBS($submittedDate) . ' BS, ' . \Carbon\Carbon::parse($submittedDate)->format('h:i A') : 'N/A' }}
                        </div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Reviewed At:</div>
                        <div class="info-value">
                            @if($application->reviewed_at)
                                @php
                                    $reviewDays = $application->submitted_at ? (int)$application->submitted_at->diffInDays($application->reviewed_at, false) : 0;
                                @endphp
                                <span class="badge bg-success">Reviewed</span>
                                <span class="text-muted ms-2">({{ $reviewDays }} {{ $reviewDays == 1 ? 'day' : 'days' }})</span>
                            @elseif($application->submitted_at)
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
>>>>>>> efe7d213166d7eb2c3aef5455d337ce01292fe6f

            </div>
        </div>

    </div>
</div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.getElementById('reviewStatus').addEventListener('change', function() {
    const status = this.value;
    const notesLabel = document.getElementById('notesLabel');
    const notesHelp = document.getElementById('notesHelp');
    const reviewerNotes = document.getElementById('reviewerNotes');
    const submitBtn = document.getElementById('submitBtn');
    const submitIcon = document.getElementById('submitIcon');
    const submitText = document.getElementById('submitText');
    const smsPreview = document.getElementById('smsPreview');
    const approverSelect = document.getElementById('approverSelect');
    const approverRequired = document.getElementById('approverRequired');
    const approverHelpText = document.getElementById('approverHelpText');

    if (status === 'reviewed') {
        notesLabel.textContent = 'Review Comments';
        notesHelp.textContent = 'Add your assessment and recommendations for the Approver.';
        reviewerNotes.placeholder = 'Candidate\'s qualifications, strengths, weaknesses, and overall assessment...';
        submitBtn.className = 'btn btn-success btn-lg';
        submitBtn.style.background = '';
        submitIcon.className = ' me-2';
        submitText.textContent = 'Submit Review';
        smsPreview.style.display = 'none';
        approverSelect.required = true;
        approverRequired.style.display = 'inline';
        approverHelpText.classList.remove('text-muted');
        approverHelpText.classList.add('text-danger');
        approverHelpText.innerHTML = '<i class="bi bi-exclamation-triangle me-1"></i><strong>Required:</strong> Select the approver who will make the final decision';
    } else if (status === 'rejected') {
        notesLabel.textContent = 'Rejection Reason';
        notesHelp.innerHTML = '<i class="me-1"></i><strong>Important:</strong> Clearly explain what is missing or incorrect. This will be sent to the candidate via SMS.';
        reviewerNotes.placeholder = 'Example: "Missing citizenship certificate copy" or "Educational certificates are not clear/readable"...';
        submitBtn.className = 'btn btn-danger btn-lg';
        submitBtn.style.background = '';
        submitIcon.className = ' me-2';
        submitText.textContent = 'Reject Application';
        smsPreview.style.display = 'block';
        approverSelect.required = false;
        approverRequired.style.display = 'none';
        approverHelpText.classList.remove('text-danger');
        approverHelpText.classList.add('text-muted');
        approverHelpText.innerHTML = 'Select the approver who will make the final decision (required when marking as reviewed)';
    } else {
        submitBtn.className = 'btn btn-lg';
        submitBtn.style.background = '#64748b';
        submitBtn.style.color = 'white';
        submitIcon.className = 'me-2';
        submitText.textContent = 'Submit Action';
        smsPreview.style.display = 'none';
        approverSelect.required = false;
        approverRequired.style.display = 'none';
        approverHelpText.classList.remove('text-danger');
        approverHelpText.classList.add('text-muted');
        approverHelpText.innerHTML = 'Select the approver who will make the final decision (required when marking as reviewed)';
    }
});

// Initialize approver selection on page load
document.addEventListener('DOMContentLoaded', function() {
    const status = document.getElementById('reviewStatus').value;
    const approverSelect = document.getElementById('approverSelect');
    const approverRequired = document.getElementById('approverRequired');
    const approverHelpText = document.getElementById('approverHelpText');

    if (status === 'reviewed') {
        approverSelect.required = true;
        approverRequired.style.display = 'inline';
        approverHelpText.classList.remove('text-muted');
        approverHelpText.classList.add('text-danger');
        approverHelpText.innerHTML = '<i class="bi bi-exclamation-triangle me-1"></i><strong>Required:</strong> Select the approver who will make the final decision';
    }
});

document.getElementById('reviewerNotes').addEventListener('input', function() {
    const status = document.getElementById('reviewStatus').value;
    if (status === 'rejected') {
        const reason = this.value.trim() || '[Your rejection reason will appear here]';
        document.getElementById('smsReasonPreview').textContent = reason;
    }
});

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

    // Check if approver is selected when status is 'reviewed'
    if (status === 'reviewed') {
        const approverId = formData.get('approver_id');
        if (!approverId || approverId === '') {
            alert('⚠️ Please select an approver before marking as reviewed');
            return;
        }
    }

    let confirmMessage = '';
    if (status === 'reviewed') {
        const approverSelect = document.getElementById('approverSelect');
        const approverName = approverSelect.options[approverSelect.selectedIndex].text;
        confirmMessage = 'Are you sure you want to mark this application as REVIEWED?\n\n✓ Application will be assigned to: ' + approverName + '\n✓ Application will be sent to the Approver Portal for final decision.\n✓ Your review notes will be forwarded to the Approver.\n\nThis action will be recorded in the system.';
    } else if (status === 'rejected') {
        confirmMessage = 'Are you sure you want to REJECT this application?\n\n✓ Candidate will be notified via SMS (when Sparrow SMS is integrated).\n✓ Your rejection reason will be sent to: {{ $application->phone ?? "N/A" }}\n\n⚠️ Make sure your rejection reason is clear and helpful.\n\nThis action will be recorded in the system.';
    } else {
        confirmMessage = 'Are you sure you want to submit this action?';
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
                location.reload();
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