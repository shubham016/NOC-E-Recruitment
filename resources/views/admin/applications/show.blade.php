@extends('layouts.dashboard')

@section('title', 'Application Details - NOC E-Recruitment')

@section('portal-name', 'Admin Portal')
@section('brand-icon', 'bi bi-shield-check')
@section('dashboard-route', route('admin.dashboard'))
@section('user-name', Auth::guard('admin')->user()->name)
@section('user-role', 'System Administrator')
@section('user-initial', strtoupper(substr(Auth::guard('admin')->user()->name, 0, 1)))
@section('logout-route', route('admin.logout'))

@section('sidebar-menu')
    @include('admin.partials.sidebar')
@endsection

@section('custom-styles')
<link rel="stylesheet" href="{{ asset('css/government-professional.css') }}">
@endsection

@section('content')
<div class="container-fluid px-4 py-4 gov-page-container">
    <!-- Back Link -->
    <a href="{{ route('admin.applications.index') }}" class="gov-back-link">
        <i class="bi bi-arrow-left-circle"></i>
        Back to Applications List
    </a>

    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 style="font-size: 2.25rem; font-weight: 700; color: #1f2937; margin-bottom: 0.5rem; letter-spacing: -0.025em;">
                Application Review & Details
            </h1>
            <p style="color: #6b7280; margin: 0; font-size: 1.0625rem;">Nepal Oil Corporation E-Recruitment Portal</p>
        </div>
        <div class="d-flex gap-2">
            <button type="button" class="gov-btn gov-btn-primary" data-bs-toggle="modal" data-bs-target="#statusModal">
                <i class="bi bi-pencil-square"></i> Update Status
            </button>
            <button type="button" class="gov-btn gov-btn-primary" data-bs-toggle="modal" data-bs-target="#assignModal">
                <i class="bi bi-person-plus"></i> Assign Reviewer
            </button>
            <button type="button" class="gov-btn gov-btn-secondary" onclick="confirmDelete()">
                <i class="bi bi-trash"></i> Delete
            </button>
        </div>
    </div>

    @if(session('success'))
        <div class="gov-alert gov-alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill"></i>
            <span>{{ session('success') }}</span>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Candidate Header Card -->
    <div class="gov-card gov-candidate-header mb-4">
        <div class="gov-card-body" style="padding: 2.5rem;">
            <div class="row align-items-center">
                <div class="col-auto">
                    @if($application->passport_photo)
                        <img src="{{ asset('storage/' . $application->passport_photo) }}"
                             alt="Candidate Photo"
                             class="gov-avatar gov-avatar-lg">
                    @else
                        <div class="gov-avatar-placeholder gov-avatar-lg">
                            {{ strtoupper(substr($application->candidate->name, 0, 1)) }}
                        </div>
                    @endif
                </div>
                <div class="col">
                    <h2 style="font-size: 2rem; font-weight: 700; margin-bottom: 1.25rem; color: white;">
                        {{ $application->candidate->name }}
                    </h2>
                    <div class="row g-4">
                        <div class="col-auto">
                            <i class="bi bi-envelope-fill me-2"></i>{{ $application->candidate->email }}
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-telephone-fill me-2"></i>{{ $application->phone }}
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-calendar-check-fill me-2"></i>Applied: {{ $application->created_at->format('M d, Y') }}
                        </div>
                    </div>
                </div>
                <div class="col-auto">
                    <span class="gov-badge
                        @if($application->status == 'pending') gov-badge-warning
                        @elseif($application->status == 'approved') gov-badge-success
                        @elseif($application->status == 'rejected') gov-badge-danger
                        @else gov-badge-secondary
                        @endif" style="font-size: 0.9375rem; padding: 0.625rem 1.25rem;">
                        {{ $application->status_label }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Main Content Area -->
        <div class="col-lg-8">
            <!-- Job Information -->
            <div class="gov-card">
                <div class="gov-card-header">
                    <i class="bi bi-briefcase"></i>
                    <span>Vacancy Information</span>
                </div>
                <div class="gov-card-body-no-padding">
                    <table class="gov-table gov-table-detail">
                        <tbody>
                            <tr>
                                <td>Vacancy Title</td>
                                <td>{{ $application->vacancy->title }}</td>
                            </tr>
                            <tr>
                                <td>Advertisement No.</td>
                                <td>{{ $application->vacancy->advertisement_no }}</td>
                            </tr>
                            <tr>
                                <td>Department</td>
                                <td>{{ $application->vacancy->department }}</td>
                            </tr>
                            <tr>
                                <td>Location</td>
                                <td>{{ $application->vacancy->location }}</td>
                            </tr>
                            <tr>
                                <td>Position Level</td>
                                <td>{{ $application->vacancy->position_level }}</td>
                            </tr>
                            <tr>
                                <td>Application Deadline</td>
                                <td>{{ \Carbon\Carbon::parse($application->vacancy->application_deadline)->format('F d, Y') }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Tabbed Content -->
            <div class="gov-card">
                <div class="gov-card-header" style="padding: 0; background: transparent; border: none;">
                    <ul class="nav nav-tabs gov-tabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" data-bs-toggle="tab" href="#personal">
                                <i class="bi bi-person-fill me-2"></i>Personal Info
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#family">
                                <i class="bi bi-people-fill me-2"></i>Family Info
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#address">
                                <i class="bi bi-geo-alt-fill me-2"></i>Address
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#experience">
                                <i class="bi bi-briefcase-fill me-2"></i>Experience
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#documents">
                                <i class="bi bi-file-earmark-text-fill me-2"></i>Documents
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="gov-card-body">
                    <div class="tab-content">
                        <!-- Personal Info Tab -->
                        <div class="tab-pane fade show active" id="personal">
                            <table class="gov-table gov-table-detail">
                                <tbody>
                                    <tr>
                                        <td>Birth Date (AD)</td>
                                        <td>{{ $application->birth_date_ad ? $application->birth_date_ad->format('F d, Y') : '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td>Birth Date (BS)</td>
                                        <td>{{ $application->birth_date_bs ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td>Age</td>
                                        <td>{{ $application->age }} years</td>
                                    </tr>
                                    <tr>
                                        <td>Gender</td>
                                        <td>{{ ucfirst($application->gender) }}</td>
                                    </tr>
                                    <tr>
                                        <td>Religion</td>
                                        <td>{{ $application->religion }}</td>
                                    </tr>
                                    <tr>
                                        <td>Marital Status</td>
                                        <td>{{ ucfirst($application->marital_status) }}</td>
                                    </tr>
                                    <tr>
                                        <td>Blood Group</td>
                                        <td>{{ $application->blood_group ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td>Mother Tongue</td>
                                        <td>{{ $application->mother_tongue }}</td>
                                    </tr>
                                    <tr>
                                        <td>Physical Disability</td>
                                        <td>{{ ucfirst($application->physical_disability) }}</td>
                                    </tr>
                                    <tr>
                                        <td>NOC Employee</td>
                                        <td>
                                            <span class="gov-badge {{ $application->noc_employee == 'yes' ? 'gov-badge-success' : 'gov-badge-secondary' }}">
                                                {{ ucfirst($application->noc_employee) }}
                                            </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Citizenship Number</td>
                                        <td>{{ $application->citizenship_number }}</td>
                                    </tr>
                                    <tr>
                                        <td>Citizenship Issue Date</td>
                                        <td>{{ $application->citizenship_issue_date_ad ? $application->citizenship_issue_date_ad->format('F d, Y') : '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td>Citizenship Issue District</td>
                                        <td>{{ $application->citizenship_issue_district }}</td>
                                    </tr>
                                    <tr>
                                        <td>Nationality</td>
                                        <td>{{ $application->nationality }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Family Info Tab -->
                        <div class="tab-pane fade" id="family">
                            <h6 class="gov-section-title">
                                <i class="bi bi-person"></i> Father's Information
                            </h6>
                            <table class="gov-table gov-table-detail mb-4">
                                <tbody>
                                    <tr>
                                        <td>Name (English)</td>
                                        <td>{{ $application->father_name_english }}</td>
                                    </tr>
                                    <tr>
                                        <td>Name (Nepali)</td>
                                        <td>{{ $application->father_name_nepali }}</td>
                                    </tr>
                                    <tr>
                                        <td>Qualification</td>
                                        <td>{{ $application->father_qualification ?? '-' }}</td>
                                    </tr>
                                </tbody>
                            </table>

                            <h6 class="gov-section-title">
                                <i class="bi bi-person"></i> Mother's Information
                            </h6>
                            <table class="gov-table gov-table-detail mb-4">
                                <tbody>
                                    <tr>
                                        <td>Name (English)</td>
                                        <td>{{ $application->mother_name_english }}</td>
                                    </tr>
                                    <tr>
                                        <td>Name (Nepali)</td>
                                        <td>{{ $application->mother_name_nepali }}</td>
                                    </tr>
                                    <tr>
                                        <td>Qualification</td>
                                        <td>{{ $application->mother_qualification ?? '-' }}</td>
                                    </tr>
                                </tbody>
                            </table>

                            <h6 class="gov-section-title">
                                <i class="bi bi-person"></i> Grandfather's Information
                            </h6>
                            <table class="gov-table gov-table-detail mb-4">
                                <tbody>
                                    <tr>
                                        <td>Name (English)</td>
                                        <td>{{ $application->grandfather_name_english }}</td>
                                    </tr>
                                    <tr>
                                        <td>Name (Nepali)</td>
                                        <td>{{ $application->grandfather_name_nepali }}</td>
                                    </tr>
                                </tbody>
                            </table>

                            @if($application->spouse_name_english)
                                <h6 class="gov-section-title">
                                    <i class="bi bi-people"></i> Spouse Information
                                </h6>
                                <table class="gov-table gov-table-detail">
                                    <tbody>
                                        <tr>
                                            <td>Name (English)</td>
                                            <td>{{ $application->spouse_name_english }}</td>
                                        </tr>
                                        <tr>
                                            <td>Name (Nepali)</td>
                                            <td>{{ $application->spouse_name_nepali ?? '-' }}</td>
                                        </tr>
                                        <tr>
                                            <td>Nationality</td>
                                            <td>{{ $application->spouse_nationality ?? '-' }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            @endif
                        </div>

                        <!-- Address Tab -->
                        <div class="tab-pane fade" id="address">
                            <h6 class="gov-section-title">
                                <i class="bi bi-geo-alt"></i> Permanent Address
                            </h6>
                            <div class="gov-info-box">
                                <p style="margin: 0; font-weight: 600; color: #1f2937; font-size: 1rem;">
                                    {{ $application->full_permanent_address }}
                                </p>
                            </div>

                            <h6 class="gov-section-title">
                                <i class="bi bi-envelope"></i> Mailing Address
                            </h6>
                            <div class="gov-info-box">
                                @if($application->same_as_permanent)
                                    <p style="margin: 0; color: #6b7280;">
                                        <i class="bi bi-check-circle-fill me-2" style="color: #059669;"></i>
                                        Same as Permanent Address
                                    </p>
                                @else
                                    <p style="margin: 0; font-weight: 600; color: #1f2937; font-size: 1rem;">
                                        {{ $application->full_mailing_address }}
                                    </p>
                                @endif
                            </div>
                        </div>

                        <!-- Experience Tab -->
                        <div class="tab-pane fade" id="experience">
                            <table class="gov-table gov-table-detail mb-4">
                                <tbody>
                                    <tr>
                                        <td>Years of Experience</td>
                                        <td>
                                            <span class="gov-badge gov-badge-primary" style="font-size: 0.9375rem;">
                                                {{ $application->years_of_experience }} Years
                                            </span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>

                            @if($application->relevant_experience)
                                <h6 class="gov-section-title">
                                    <i class="bi bi-briefcase"></i> Relevant Experience
                                </h6>
                                <div class="gov-info-box">
                                    <p style="white-space: pre-wrap; margin: 0; line-height: 1.7;">{{ $application->relevant_experience }}</p>
                                </div>
                            @endif

                            <h6 class="gov-section-title">
                                <i class="bi bi-file-text"></i> Cover Letter
                            </h6>
                            <div class="gov-info-box">
                                <p style="white-space: pre-wrap; margin: 0; line-height: 1.7;">{{ $application->cover_letter }}</p>
                            </div>
                        </div>

                        <!-- Documents Tab -->
                        <div class="tab-pane fade" id="documents">
                            <table class="gov-table">
                                <thead>
                                    <tr>
                                        <th style="width: 5%;">#</th>
                                        <th style="width: 45%;">Document Type</th>
                                        <th style="width: 20%;">Status</th>
                                        <th style="width: 30%;">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="text-center">1</td>
                                        <td><i class="bi bi-image text-warning me-2"></i>Passport Photo</td>
                                        <td>
                                            @if($application->passport_photo)
                                                <span class="gov-badge gov-badge-success">Uploaded</span>
                                            @else
                                                <span class="gov-badge gov-badge-danger">Missing</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($application->passport_photo)
                                                <a href="{{ asset('storage/' . $application->passport_photo) }}" target="_blank" class="gov-btn gov-btn-primary gov-btn-sm">
                                                    <i class="bi bi-eye"></i> View
                                                </a>
                                            @else
                                                <span style="color: #9ca3af;">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-center">2</td>
                                        <td><i class="bi bi-file-earmark-pdf text-danger me-2"></i>Resume/CV</td>
                                        <td>
                                            @if($application->resume)
                                                <span class="gov-badge gov-badge-success">Uploaded</span>
                                            @else
                                                <span class="gov-badge gov-badge-danger">Missing</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($application->resume)
                                                <a href="{{ asset('storage/' . $application->resume) }}" target="_blank" class="gov-btn gov-btn-primary gov-btn-sm">
                                                    <i class="bi bi-download"></i> Download
                                                </a>
                                            @else
                                                <span style="color: #9ca3af;">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-center">3</td>
                                        <td><i class="bi bi-file-earmark-text text-info me-2"></i>Citizenship Certificate</td>
                                        <td>
                                            @if($application->citizenship_certificate)
                                                <span class="gov-badge gov-badge-success">Uploaded</span>
                                            @else
                                                <span class="gov-badge gov-badge-danger">Missing</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($application->citizenship_certificate)
                                                <a href="{{ asset('storage/' . $application->citizenship_certificate) }}" target="_blank" class="gov-btn gov-btn-primary gov-btn-sm">
                                                    <i class="bi bi-download"></i> Download
                                                </a>
                                            @else
                                                <span style="color: #9ca3af;">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-center">4</td>
                                        <td><i class="bi bi-mortarboard text-success me-2"></i>Educational Certificates</td>
                                        <td>
                                            @if($application->educational_certificates)
                                                <span class="gov-badge gov-badge-success">Uploaded</span>
                                            @else
                                                <span class="gov-badge gov-badge-danger">Missing</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($application->educational_certificates)
                                                <a href="{{ asset('storage/' . $application->educational_certificates) }}" target="_blank" class="gov-btn gov-btn-primary gov-btn-sm">
                                                    <i class="bi bi-download"></i> Download
                                                </a>
                                            @else
                                                <span style="color: #9ca3af;">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @if($application->experience_certificates)
                                        <tr>
                                            <td class="text-center">5</td>
                                            <td><i class="bi bi-briefcase text-primary me-2"></i>Experience Certificates</td>
                                            <td><span class="gov-badge gov-badge-success">Uploaded</span></td>
                                            <td>
                                                <a href="{{ asset('storage/' . $application->experience_certificates) }}" target="_blank" class="gov-btn gov-btn-primary gov-btn-sm">
                                                    <i class="bi bi-download"></i> Download
                                                </a>
                                            </td>
                                        </tr>
                                    @endif
                                    @if($application->noc_id_card)
                                        <tr>
                                            <td class="text-center">6</td>
                                            <td><i class="bi bi-card-heading text-warning me-2"></i>NOC ID Card</td>
                                            <td><span class="gov-badge gov-badge-success">Uploaded</span></td>
                                            <td>
                                                <a href="{{ asset('storage/' . $application->noc_id_card) }}" target="_blank" class="gov-btn gov-btn-primary gov-btn-sm">
                                                    <i class="bi bi-download"></i> Download
                                                </a>
                                            </td>
                                        </tr>
                                    @endif
                                    @if($application->ethnic_certificate)
                                        <tr>
                                            <td class="text-center">7</td>
                                            <td><i class="bi bi-file-earmark text-secondary me-2"></i>Ethnic Certificate</td>
                                            <td><span class="gov-badge gov-badge-success">Uploaded</span></td>
                                            <td>
                                                <a href="{{ asset('storage/' . $application->ethnic_certificate) }}" target="_blank" class="gov-btn gov-btn-primary gov-btn-sm">
                                                    <i class="bi bi-download"></i> Download
                                                </a>
                                            </td>
                                        </tr>
                                    @endif
                                    @if($application->disability_certificate)
                                        <tr>
                                            <td class="text-center">8</td>
                                            <td><i class="bi bi-file-medical text-danger me-2"></i>Disability Certificate</td>
                                            <td><span class="gov-badge gov-badge-success">Uploaded</span></td>
                                            <td>
                                                <a href="{{ asset('storage/' . $application->disability_certificate) }}" target="_blank" class="gov-btn gov-btn-primary gov-btn-sm">
                                                    <i class="bi bi-download"></i> Download
                                                </a>
                                            </td>
                                        </tr>
                                    @endif
                                    @if($application->other_documents)
                                        <tr>
                                            <td class="text-center">9</td>
                                            <td><i class="bi bi-files text-info me-2"></i>Other Documents</td>
                                            <td><span class="gov-badge gov-badge-success">Uploaded</span></td>
                                            <td>
                                                <a href="{{ asset('storage/' . $application->other_documents) }}" target="_blank" class="gov-btn gov-btn-primary gov-btn-sm">
                                                    <i class="bi bi-download"></i> Download
                                                </a>
                                            </td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Notes Section -->
            @if($application->admin_notes || $application->reviewer_notes)
                <div class="gov-card">
                    <div class="gov-card-header" style="background: linear-gradient(to bottom, #fef3c7 0%, #fde68a 100%); color: #92400e; border-color: #fcd34d;">
                        <i class="bi bi-sticky"></i>
                        <span>Notes & Comments</span>
                    </div>
                    <div class="gov-card-body">
                        @if($application->admin_notes)
                            <h6 class="gov-section-title">
                                <i class="bi bi-shield-check"></i> Admin Notes
                            </h6>
                            <div class="gov-info-box">
                                <p style="white-space: pre-wrap; margin: 0; line-height: 1.7;">{{ $application->admin_notes }}</p>
                            </div>
                        @endif

                        @if($application->reviewer_notes)
                            <h6 class="gov-section-title">
                                <i class="bi bi-person-badge"></i> Reviewer Notes
                            </h6>
                            <div class="gov-info-box">
                                <p style="white-space: pre-wrap; margin: 0; line-height: 1.7;">{{ $application->reviewer_notes }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Status Card -->
            <div class="gov-card">
                <div class="gov-card-header" style="background: linear-gradient(to bottom, #dbeafe 0%, #bfdbfe 100%); color: #1e40af; border-color: #93c5fd;">
                    <i class="bi bi-info-circle"></i>
                    <span>Application Status</span>
                </div>
                <div class="gov-card-body-no-padding">
                    <table class="gov-table gov-table-detail">
                        <tbody>
                            <tr>
                                <td>Current Status</td>
                                <td>
                                    <span class="gov-badge
                                        @if($application->status == 'pending') gov-badge-warning
                                        @elseif($application->status == 'approved') gov-badge-success
                                        @elseif($application->status == 'rejected') gov-badge-danger
                                        @else gov-badge-secondary
                                        @endif">
                                        {{ $application->status_label }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td>Assigned Reviewer</td>
                                <td>
                                    @if($application->reviewer)
                                        <strong style="color: #1f2937;">{{ $application->reviewer->name }}</strong><br>
                                        <small style="color: #6b7280;">{{ $application->reviewer->email }}</small>
                                    @else
                                        <span style="color: #9ca3af;">Not assigned</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td>Application Date</td>
                                <td>
                                    <strong style="color: #1f2937;">{{ $application->created_at->format('F d, Y') }}</strong><br>
                                    <small style="color: #6b7280;">{{ $application->created_at->diffForHumans() }}</small>
                                </td>
                            </tr>
                            <tr>
                                <td>Last Updated</td>
                                <td>
                                    <strong style="color: #1f2937;">{{ $application->updated_at->format('F d, Y') }}</strong><br>
                                    <small style="color: #6b7280;">{{ $application->updated_at->diffForHumans() }}</small>
                                </td>
                            </tr>
                            @if($application->reviewed_at)
                                <tr>
                                    <td>Reviewed Date</td>
                                    <td>
                                        <strong style="color: #1f2937;">{{ $application->reviewed_at->format('F d, Y') }}</strong><br>
                                        <small style="color: #6b7280;">{{ $application->reviewed_at->diffForHumans() }}</small>
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="gov-card">
                <div class="gov-card-header" style="background: linear-gradient(to bottom, #d1fae5 0%, #a7f3d0 100%); color: #065f46; border-color: #6ee7b7;">
                    <i class="bi bi-lightning-charge"></i>
                    <span>Quick Actions</span>
                </div>
                <div class="gov-card-body">
                    <div class="d-grid gap-3">
                        <button type="button" class="gov-btn gov-btn-primary w-100" data-bs-toggle="modal" data-bs-target="#statusModal">
                            <i class="bi bi-pencil-square"></i> Change Status
                        </button>
                        <button type="button" class="gov-btn gov-btn-primary w-100" data-bs-toggle="modal" data-bs-target="#assignModal">
                            <i class="bi bi-person-plus"></i> Assign Reviewer
                        </button>
                        <a href="mailto:{{ $application->candidate->email }}" class="gov-btn gov-btn-secondary w-100">
                            <i class="bi bi-envelope"></i> Send Email
                        </a>
                        <button type="button" class="gov-btn gov-btn-secondary w-100" onclick="confirmDelete()">
                            <i class="bi bi-trash"></i> Delete Application
                        </button>
                    </div>
                </div>
            </div>

            <!-- Activity Timeline -->
            <div class="gov-card">
                <div class="gov-card-header" style="background: linear-gradient(to bottom, #f3f4f6 0%, #e5e7eb 100%); color: #4b5563; border-color: #d1d5db;">
                    <i class="bi bi-clock-history"></i>
                    <span>Activity Timeline</span>
                </div>
                <div class="gov-card-body">
                    <div class="gov-timeline-item">
                        <div class="gov-timeline-icon" style="background: linear-gradient(135deg, #3b82f6 0%, #1e40af 100%);">
                            <i class="bi bi-check-circle-fill" style="color: white;"></i>
                        </div>
                        <h6 class="gov-font-bold gov-text-md" style="color: #1f2937; margin-bottom: 0.375rem;">Application Submitted</h6>
                        <p class="gov-text-sm" style="color: #6b7280; margin-bottom: 0.5rem;">Candidate submitted their application</p>
                        <small class="gov-text-sm" style="color: #9ca3af;">
                            <i class="bi bi-calendar3 me-1"></i>
                            {{ $application->created_at->format('M d, Y • h:i A') }}
                        </small>
                    </div>

                    @if($application->reviewer)
                        <div class="gov-timeline-item">
                            <div class="gov-timeline-icon" style="background: linear-gradient(135deg, #34d399 0%, #10b981 100%);">
                                <i class="bi bi-person-plus-fill" style="color: white;"></i>
                            </div>
                            <h6 class="gov-font-bold gov-text-md" style="color: #1f2937; margin-bottom: 0.375rem;">Reviewer Assigned</h6>
                            <p class="gov-text-sm" style="color: #6b7280; margin-bottom: 0.5rem;">Assigned to {{ $application->reviewer->name }}</p>
                            <small class="gov-text-sm" style="color: #9ca3af;">
                                <i class="bi bi-calendar3 me-1"></i>
                                {{ $application->updated_at->format('M d, Y • h:i A') }}
                            </small>
                        </div>
                    @endif

                    @if($application->reviewed_at)
                        <div class="gov-timeline-item">
                            <div class="gov-timeline-icon" style="background: linear-gradient(135deg, #60a5fa 0%, #3b82f6 100%);">
                                <i class="bi bi-clipboard-check-fill" style="color: white;"></i>
                            </div>
                            <h6 class="gov-font-bold gov-text-md" style="color: #1f2937; margin-bottom: 0.375rem;">Application Reviewed</h6>
                            <p class="gov-text-sm" style="color: #6b7280; margin-bottom: 0.5rem;">Status: {{ $application->status_label }}</p>
                            <small class="gov-text-sm" style="color: #9ca3af;">
                                <i class="bi bi-calendar3 me-1"></i>
                                {{ $application->reviewed_at->format('M d, Y • h:i A') }}
                            </small>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Update Status Modal -->
<div class="modal fade" id="statusModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 14px; border: none; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);">
            <form action="{{ route('admin.applications.updateStatus', $application) }}" method="POST">
                @csrf
                <div class="modal-header" style="background: linear-gradient(to bottom, white 0%, #f9fafb 100%); border-bottom: 2px solid #e5e7eb; padding: 1.5rem;">
                    <h5 class="modal-title fw-bold" style="color: #1f2937;">
                        <i class="bi bi-pencil-square me-2" style="color: #1e40af;"></i>Update Application Status
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="gov-form-label">Select Status</label>
                        <select name="status" class="form-select gov-form-select" required style="height: 52px;">
                            @foreach($statuses as $status)
                                <option value="{{ $status }}" {{ $application->status == $status ? 'selected' : '' }}>
                                    {{ ucfirst(str_replace('_', ' ', $status)) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-0">
                        <label class="gov-form-label">Admin Notes</label>
                        <textarea name="admin_notes" class="form-control gov-form-control" rows="4" placeholder="Add notes about this decision...">{{ old('admin_notes', $application->admin_notes) }}</textarea>
                    </div>
                </div>
                <div class="modal-footer" style="background: linear-gradient(to top, #f9fafb 0%, white 100%); border-top: 2px solid #e5e7eb; padding: 1.25rem;">
                    <button type="button" class="gov-btn gov-btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="gov-btn gov-btn-primary">
                        <i class="bi bi-check-circle"></i> Update Status
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Assign Reviewer Modal -->
<div class="modal fade" id="assignModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 14px; border: none; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);">
            <form action="{{ route('admin.applications.assignReviewer', $application) }}" method="POST">
                @csrf
                <div class="modal-header" style="background: linear-gradient(to bottom, white 0%, #f9fafb 100%); border-bottom: 2px solid #e5e7eb; padding: 1.5rem;">
                    <h5 class="modal-title fw-bold" style="color: #1f2937;">
                        <i class="bi bi-person-plus me-2" style="color: #059669;"></i>Assign Reviewer
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="gov-form-label">Select Reviewer</label>
                        <select name="reviewer_id" class="form-select gov-form-select" required style="height: 52px;">
                            <option value="">-- Select Reviewer --</option>
                            @foreach($reviewers as $reviewer)
                                <option value="{{ $reviewer->id }}" {{ $application->reviewer_id == $reviewer->id ? 'selected' : '' }}>
                                    {{ $reviewer->name }} - {{ $reviewer->email }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="gov-alert gov-alert-info mb-0">
                        <i class="bi bi-info-circle"></i>
                        The application status will automatically change to "Approved" after assigning a reviewer.
                    </div>
                </div>
                <div class="modal-footer" style="background: linear-gradient(to top, #f9fafb 0%, white 100%); border-top: 2px solid #e5e7eb; padding: 1.25rem;">
                    <button type="button" class="gov-btn gov-btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="gov-btn gov-btn-primary">
                        <i class="bi bi-check-circle"></i> Assign Reviewer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Confirmation Form -->
<form id="deleteForm" action="{{ route('admin.applications.destroy', $application) }}" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>
@endsection

@section('scripts')
<script>
    function confirmDelete() {
        if (confirm('⚠️ Are you sure you want to delete this application?\n\nThis action cannot be undone and will permanently remove all application data.')) {
            document.getElementById('deleteForm').submit();
        }
    }

    // Auto-dismiss alerts
    setTimeout(() => {
        const alerts = document.querySelectorAll('.alert-dismissible');
        alerts.forEach(alert => {
            const bsAlert = bootstrap.Alert.getInstance(alert) || new bootstrap.Alert(alert);
            if (bsAlert) bsAlert.close();
        });
    }, 5000);
</script>
@endsection
