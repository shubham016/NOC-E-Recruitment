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
    <a href="{{ route('admin.dashboard') }}" class="sidebar-menu-item">
        <i class="bi bi-speedometer2"></i>
        <span>Dashboard</span>
    </a>
    <a href="{{ route('admin.jobs.create') }}" class="sidebar-menu-item">
        <i class="bi bi-briefcase"></i>
        <span>Post Vacancy</span>
    </a>
    <a href="{{ route('admin.applications.index') }}" class="sidebar-menu-item active">
        <i class="bi bi-file-earmark-text"></i>
        <span>Applications</span>
    </a>
    <a href="{{ route('admin.candidates.index') }}" class="sidebar-menu-item">
        <i class="bi bi-people"></i>
        <span>Candidates</span>
    </a>
    <a href="{{ route('admin.reviewers.index') }}" class="sidebar-menu-item">
        <i class="bi bi-person-badge"></i>
        <span>Reviewers</span>
    </a>
    <a href="#" class="sidebar-menu-item">
        <i class="bi bi-bar-chart"></i>
        <span>Reports</span>
    </a>
    <a href="#" class="sidebar-menu-item">
        <i class="bi bi-gear"></i>
        <span>Settings</span>
    </a>
@endsection

@section('content')
<div class="container-fluid px-4 py-4">
    <!-- Page Header with Actions -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <a href="{{ route('admin.applications.index') }}" class="text-decoration-none text-muted mb-2 d-inline-block">
                        <i class="bi bi-arrow-left-circle me-2"></i>Back to Applications
                    </a>
                    <h2 class="mb-0 fw-bold">Application Review & Details</h2>
                    <p class="text-muted mb-0">Nepal Oil Corporation E-Recruitment Portal</p>
                </div>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#statusModal">
                        <i class="bi bi-pencil-square me-1"></i>Update Status
                    </button>
                    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#assignModal">
                        <i class="bi bi-person-plus me-1"></i>Assign Reviewer
                    </button>
                    <button type="button" class="btn btn-danger" onclick="confirmDelete()">
                        <i class="bi bi-trash me-1"></i>Delete
                    </button>
                </div>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
        </div>
    </div>

    <!-- Candidate Header Card -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-2 shadow-sm">
                <div class="card-body p-4" style="background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);">
                    <div class="row align-items-center text-white">
                        <div class="col-auto">
                            <div class="candidate-photo-wrapper">
                                @if($application->passport_photo)
                                    <img src="{{ asset('storage/' . $application->passport_photo) }}" 
                                         alt="Candidate Photo"
                                         class="rounded-circle border border-4 border-white"
                                         style="width: 100px; height: 100px; object-fit: cover;">
                                @else
                                    <div class="rounded-circle border border-4 border-white bg-light d-flex align-items-center justify-content-center"
                                         style="width: 100px; height: 100px;">
                                        <span class="fs-1 fw-bold text-primary">
                                            {{ strtoupper(substr($application->candidate->name, 0, 1)) }}
                                        </span>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="col">
                            <h3 class="fw-bold mb-2">{{ $application->candidate->name }}</h3>
                            <div class="row g-3">
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
                            <span class="badge px-4 py-2 fs-6
                                @if($application->status == 'pending') bg-warning text-dark
                                @elseif($application->status == 'under_review') bg-info
                                @elseif($application->status == 'shortlisted') bg-success
                                @elseif($application->status == 'rejected') bg-danger
                                @else bg-secondary
                                @endif">
                                {{ $application->status_label }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Main Content Area -->
        <div class="col-lg-8">
            <!-- Job Information -->
            <div class="card border-2 shadow-sm mb-4">
                <div class="card-header bg-primary text-white border-bottom-0">
                    <h5 class="mb-0 fw-bold">
                        <i class="bi bi-briefcase-fill me-2"></i>Job Information
                    </h5>
                </div>
                <div class="card-body p-0">
                    <table class="table table-bordered table-hover mb-0">
                        <tbody>
                            <tr>
                                <td class="bg-light fw-bold" style="width: 35%;">Job Title</td>
                                <td>{{ $application->jobPosting->title }}</td>
                            </tr>
                            <tr>
                                <td class="bg-light fw-bold">Advertisement No.</td>
                                <td>{{ $application->jobPosting->advertisement_no }}</td>
                            </tr>
                            <tr>
                                <td class="bg-light fw-bold">Department</td>
                                <td>{{ $application->jobPosting->department }}</td>
                            </tr>
                            <tr>
                                <td class="bg-light fw-bold">Location</td>
                                <td>{{ $application->jobPosting->location }}</td>
                            </tr>
                            <tr>
                                <td class="bg-light fw-bold">Position Level</td>
                                <td>{{ $application->jobPosting->position_level }}</td>
                            </tr>
                            <tr>
                                <td class="bg-light fw-bold">Application Deadline</td>
                                <td>{{ \Carbon\Carbon::parse($application->jobPosting->application_deadline)->format('F d, Y') }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Tabbed Content -->
            <div class="card border-2 shadow-sm mb-4">
                <div class="card-header border-bottom-0 bg-white p-0">
                    <ul class="nav nav-tabs border-0" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active border-0 border-bottom border-3 border-primary fw-bold" 
                               data-bs-toggle="tab" href="#personal">
                                <i class="bi bi-person-fill me-1"></i>Personal Info
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link border-0 border-bottom border-3 fw-bold" 
                               data-bs-toggle="tab" href="#family">
                                <i class="bi bi-people-fill me-1"></i>Family Info
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link border-0 border-bottom border-3 fw-bold" 
                               data-bs-toggle="tab" href="#address">
                                <i class="bi bi-geo-alt-fill me-1"></i>Address
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link border-0 border-bottom border-3 fw-bold" 
                               data-bs-toggle="tab" href="#experience">
                                <i class="bi bi-briefcase-fill me-1"></i>Experience
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link border-0 border-bottom border-3 fw-bold" 
                               data-bs-toggle="tab" href="#documents">
                                <i class="bi bi-file-earmark-text-fill me-1"></i>Documents
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="card-body p-4">
                    <div class="tab-content">
                        <!-- Personal Info Tab -->
                        <div class="tab-pane fade show active" id="personal">
                            <table class="table table-bordered table-hover">
                                <tbody>
                                    <tr>
                                        <td class="bg-light fw-bold" style="width: 40%;">Birth Date (AD)</td>
                                        <td>{{ $application->birth_date_ad ? $application->birth_date_ad->format('F d, Y') : '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="bg-light fw-bold">Birth Date (BS)</td>
                                        <td>{{ $application->birth_date_bs ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="bg-light fw-bold">Age</td>
                                        <td>{{ $application->age }} years</td>
                                    </tr>
                                    <tr>
                                        <td class="bg-light fw-bold">Gender</td>
                                        <td>{{ ucfirst($application->gender) }}</td>
                                    </tr>
                                    <tr>
                                        <td class="bg-light fw-bold">Religion</td>
                                        <td>{{ $application->religion }}</td>
                                    </tr>
                                    <tr>
                                        <td class="bg-light fw-bold">Marital Status</td>
                                        <td>{{ ucfirst($application->marital_status) }}</td>
                                    </tr>
                                    <tr>
                                        <td class="bg-light fw-bold">Blood Group</td>
                                        <td>{{ $application->blood_group ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="bg-light fw-bold">Mother Tongue</td>
                                        <td>{{ $application->mother_tongue }}</td>
                                    </tr>
                                    <tr>
                                        <td class="bg-light fw-bold">Physical Disability</td>
                                        <td>{{ ucfirst($application->physical_disability) }}</td>
                                    </tr>
                                    <tr>
                                        <td class="bg-light fw-bold">NOC Employee</td>
                                        <td>
                                            <span class="badge bg-{{ $application->noc_employee == 'yes' ? 'success' : 'secondary' }}">
                                                {{ ucfirst($application->noc_employee) }}
                                            </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="bg-light fw-bold">Citizenship Number</td>
                                        <td>{{ $application->citizenship_number }}</td>
                                    </tr>
                                    <tr>
                                        <td class="bg-light fw-bold">Citizenship Issue Date</td>
                                        <td>{{ $application->citizenship_issue_date_ad ? $application->citizenship_issue_date_ad->format('F d, Y') : '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="bg-light fw-bold">Citizenship Issue District</td>
                                        <td>{{ $application->citizenship_issue_district }}</td>
                                    </tr>
                                    <tr>
                                        <td class="bg-light fw-bold">Nationality</td>
                                        <td>{{ $application->nationality }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Family Info Tab -->
                        <div class="tab-pane fade" id="family">
                            <h6 class="border-bottom pb-2 mb-3 fw-bold text-primary">Father's Information</h6>
                            <table class="table table-bordered table-hover mb-4">
                                <tbody>
                                    <tr>
                                        <td class="bg-light fw-bold" style="width: 40%;">Name (English)</td>
                                        <td>{{ $application->father_name_english }}</td>
                                    </tr>
                                    <tr>
                                        <td class="bg-light fw-bold">Name (Nepali)</td>
                                        <td>{{ $application->father_name_nepali }}</td>
                                    </tr>
                                    <tr>
                                        <td class="bg-light fw-bold">Qualification</td>
                                        <td>{{ $application->father_qualification ?? '-' }}</td>
                                    </tr>
                                </tbody>
                            </table>

                            <h6 class="border-bottom pb-2 mb-3 fw-bold text-primary">Mother's Information</h6>
                            <table class="table table-bordered table-hover mb-4">
                                <tbody>
                                    <tr>
                                        <td class="bg-light fw-bold" style="width: 40%;">Name (English)</td>
                                        <td>{{ $application->mother_name_english }}</td>
                                    </tr>
                                    <tr>
                                        <td class="bg-light fw-bold">Name (Nepali)</td>
                                        <td>{{ $application->mother_name_nepali }}</td>
                                    </tr>
                                    <tr>
                                        <td class="bg-light fw-bold">Qualification</td>
                                        <td>{{ $application->mother_qualification ?? '-' }}</td>
                                    </tr>
                                </tbody>
                            </table>

                            <h6 class="border-bottom pb-2 mb-3 fw-bold text-primary">Grandfather's Information</h6>
                            <table class="table table-bordered table-hover mb-4">
                                <tbody>
                                    <tr>
                                        <td class="bg-light fw-bold" style="width: 40%;">Name (English)</td>
                                        <td>{{ $application->grandfather_name_english }}</td>
                                    </tr>
                                    <tr>
                                        <td class="bg-light fw-bold">Name (Nepali)</td>
                                        <td>{{ $application->grandfather_name_nepali }}</td>
                                    </tr>
                                </tbody>
                            </table>

                            @if($application->spouse_name_english)
                                <h6 class="border-bottom pb-2 mb-3 fw-bold text-primary">Spouse Information</h6>
                                <table class="table table-bordered table-hover">
                                    <tbody>
                                        <tr>
                                            <td class="bg-light fw-bold" style="width: 40%;">Name (English)</td>
                                            <td>{{ $application->spouse_name_english }}</td>
                                        </tr>
                                        <tr>
                                            <td class="bg-light fw-bold">Name (Nepali)</td>
                                            <td>{{ $application->spouse_name_nepali ?? '-' }}</td>
                                        </tr>
                                        <tr>
                                            <td class="bg-light fw-bold">Nationality</td>
                                            <td>{{ $application->spouse_nationality ?? '-' }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            @endif
                        </div>

                        <!-- Address Tab -->
                        <div class="tab-pane fade" id="address">
                            <h6 class="border-bottom pb-2 mb-3 fw-bold text-primary">Permanent Address</h6>
                            <div class="alert alert-light border">
                                <p class="mb-0 fw-semibold">{{ $application->full_permanent_address }}</p>
                            </div>

                            <h6 class="border-bottom pb-2 mb-3 fw-bold text-primary">Mailing Address</h6>
                            <div class="alert alert-light border">
                                @if($application->same_as_permanent)
                                    <p class="mb-0 text-muted">
                                        <i class="bi bi-check-circle-fill text-success me-2"></i>
                                        Same as Permanent Address
                                    </p>
                                @else
                                    <p class="mb-0 fw-semibold">{{ $application->full_mailing_address }}</p>
                                @endif
                            </div>
                        </div>

                        <!-- Experience Tab -->
                        <div class="tab-pane fade" id="experience">
                            <table class="table table-bordered table-hover mb-4">
                                <tbody>
                                    <tr>
                                        <td class="bg-light fw-bold" style="width: 40%;">Years of Experience</td>
                                        <td><span class="badge bg-primary fs-6">{{ $application->years_of_experience }} years</span></td>
                                    </tr>
                                </tbody>
                            </table>

                            @if($application->relevant_experience)
                                <h6 class="border-bottom pb-2 mb-3 fw-bold text-primary">Relevant Experience</h6>
                                <div class="alert alert-light border">
                                    <p style="white-space: pre-wrap;" class="mb-0">{{ $application->relevant_experience }}</p>
                                </div>
                            @endif

                            <h6 class="border-bottom pb-2 mb-3 fw-bold text-primary">Cover Letter</h6>
                            <div class="alert alert-light border">
                                <p style="white-space: pre-wrap;" class="mb-0">{{ $application->cover_letter }}</p>
                            </div>
                        </div>

                        <!-- Documents Tab -->
                        <div class="tab-pane fade" id="documents">
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover">
                                    <thead class="table-primary">
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
                                                    <span class="badge bg-success">Uploaded</span>
                                                @else
                                                    <span class="badge bg-danger">Missing</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($application->passport_photo)
                                                    <a href="{{ asset('storage/' . $application->passport_photo) }}" target="_blank" class="btn btn-sm btn-primary">
                                                        <i class="bi bi-eye me-1"></i>View
                                                    </a>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-center">2</td>
                                            <td><i class="bi bi-file-earmark-pdf text-danger me-2"></i>Resume/CV</td>
                                            <td>
                                                @if($application->resume)
                                                    <span class="badge bg-success">Uploaded</span>
                                                @else
                                                    <span class="badge bg-danger">Missing</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($application->resume)
                                                    <a href="{{ asset('storage/' . $application->resume) }}" target="_blank" class="btn btn-sm btn-primary">
                                                        <i class="bi bi-download me-1"></i>Download
                                                    </a>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-center">3</td>
                                            <td><i class="bi bi-file-earmark-text text-info me-2"></i>Citizenship Certificate</td>
                                            <td>
                                                @if($application->citizenship_certificate)
                                                    <span class="badge bg-success">Uploaded</span>
                                                @else
                                                    <span class="badge bg-danger">Missing</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($application->citizenship_certificate)
                                                    <a href="{{ asset('storage/' . $application->citizenship_certificate) }}" target="_blank" class="btn btn-sm btn-primary">
                                                        <i class="bi bi-download me-1"></i>Download
                                                    </a>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-center">4</td>
                                            <td><i class="bi bi-mortarboard text-success me-2"></i>Educational Certificates</td>
                                            <td>
                                                @if($application->educational_certificates)
                                                    <span class="badge bg-success">Uploaded</span>
                                                @else
                                                    <span class="badge bg-danger">Missing</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($application->educational_certificates)
                                                    <a href="{{ asset('storage/' . $application->educational_certificates) }}" target="_blank" class="btn btn-sm btn-primary">
                                                        <i class="bi bi-download me-1"></i>Download
                                                    </a>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                        </tr>
                                        @if($application->experience_certificates)
                                            <tr>
                                                <td class="text-center">5</td>
                                                <td><i class="bi bi-briefcase text-primary me-2"></i>Experience Certificates</td>
                                                <td><span class="badge bg-success">Uploaded</span></td>
                                                <td>
                                                    <a href="{{ asset('storage/' . $application->experience_certificates) }}" target="_blank" class="btn btn-sm btn-primary">
                                                        <i class="bi bi-download me-1"></i>Download
                                                    </a>
                                                </td>
                                            </tr>
                                        @endif
                                        @if($application->noc_id_card)
                                            <tr>
                                                <td class="text-center">6</td>
                                                <td><i class="bi bi-card-heading text-warning me-2"></i>NOC ID Card</td>
                                                <td><span class="badge bg-success">Uploaded</span></td>
                                                <td>
                                                    <a href="{{ asset('storage/' . $application->noc_id_card) }}" target="_blank" class="btn btn-sm btn-primary">
                                                        <i class="bi bi-download me-1"></i>Download
                                                    </a>
                                                </td>
                                            </tr>
                                        @endif
                                        @if($application->ethnic_certificate)
                                            <tr>
                                                <td class="text-center">7</td>
                                                <td><i class="bi bi-file-earmark text-secondary me-2"></i>Ethnic Certificate</td>
                                                <td><span class="badge bg-success">Uploaded</span></td>
                                                <td>
                                                    <a href="{{ asset('storage/' . $application->ethnic_certificate) }}" target="_blank" class="btn btn-sm btn-primary">
                                                        <i class="bi bi-download me-1"></i>Download
                                                    </a>
                                                </td>
                                            </tr>
                                        @endif
                                        @if($application->disability_certificate)
                                            <tr>
                                                <td class="text-center">8</td>
                                                <td><i class="bi bi-file-medical text-danger me-2"></i>Disability Certificate</td>
                                                <td><span class="badge bg-success">Uploaded</span></td>
                                                <td>
                                                    <a href="{{ asset('storage/' . $application->disability_certificate) }}" target="_blank" class="btn btn-sm btn-primary">
                                                        <i class="bi bi-download me-1"></i>Download
                                                    </a>
                                                </td>
                                            </tr>
                                        @endif
                                        @if($application->other_documents)
                                            <tr>
                                                <td class="text-center">9</td>
                                                <td><i class="bi bi-files text-info me-2"></i>Other Documents</td>
                                                <td><span class="badge bg-success">Uploaded</span></td>
                                                <td>
                                                    <a href="{{ asset('storage/' . $application->other_documents) }}" target="_blank" class="btn btn-sm btn-primary">
                                                        <i class="bi bi-download me-1"></i>Download
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
            </div>

            <!-- Notes Section -->
            @if($application->admin_notes || $application->reviewer_notes)
                <div class="card border-2 shadow-sm mb-4">
                    <div class="card-header bg-warning text-dark border-bottom-0">
                        <h5 class="mb-0 fw-bold">
                            <i class="bi bi-sticky-fill me-2"></i>Notes & Comments
                        </h5>
                    </div>
                    <div class="card-body">
                        @if($application->admin_notes)
                            <div class="mb-3">
                                <h6 class="fw-bold text-primary border-bottom pb-2">
                                    <i class="bi bi-shield-check me-2"></i>Admin Notes
                                </h6>
                                <div class="alert alert-light border">
                                    <p style="white-space: pre-wrap;" class="mb-0">{{ $application->admin_notes }}</p>
                                </div>
                            </div>
                        @endif

                        @if($application->reviewer_notes)
                            <div>
                                <h6 class="fw-bold text-primary border-bottom pb-2">
                                    <i class="bi bi-person-badge me-2"></i>Reviewer Notes
                                </h6>
                                <div class="alert alert-light border">
                                    <p style="white-space: pre-wrap;" class="mb-0">{{ $application->reviewer_notes }}</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Status Card -->
            <div class="card border-2 shadow-sm mb-4">
                <div class="card-header bg-info text-white border-bottom-0">
                    <h5 class="mb-0 fw-bold">
                        <i class="bi bi-info-circle-fill me-2"></i>Application Status
                    </h5>
                </div>
                <div class="card-body">
                    <table class="table table-bordered mb-0">
                        <tbody>
                            <tr>
                                <td class="bg-light fw-bold">Current Status</td>
                                <td>
                                    <span class="badge px-3 py-2
                                        @if($application->status == 'pending') bg-warning text-dark
                                        @elseif($application->status == 'under_review') bg-info
                                        @elseif($application->status == 'shortlisted') bg-success
                                        @elseif($application->status == 'rejected') bg-danger
                                        @else bg-secondary
                                        @endif">
                                        {{ $application->status_label }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td class="bg-light fw-bold">Assigned Reviewer</td>
                                <td>
                                    @if($application->reviewer)
                                        <strong>{{ $application->reviewer->name }}</strong><br>
                                        <small class="text-muted">{{ $application->reviewer->email }}</small>
                                    @else
                                        <span class="text-muted">Not assigned</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td class="bg-light fw-bold">Application Date</td>
                                <td>
                                    {{ $application->created_at->format('F d, Y') }}<br>
                                    <small class="text-muted">{{ $application->created_at->diffForHumans() }}</small>
                                </td>
                            </tr>
                            <tr>
                                <td class="bg-light fw-bold">Last Updated</td>
                                <td>
                                    {{ $application->updated_at->format('F d, Y') }}<br>
                                    <small class="text-muted">{{ $application->updated_at->diffForHumans() }}</small>
                                </td>
                            </tr>
                            @if($application->reviewed_at)
                                <tr>
                                    <td class="bg-light fw-bold">Reviewed Date</td>
                                    <td>
                                        {{ $application->reviewed_at->format('F d, Y') }}<br>
                                        <small class="text-muted">{{ $application->reviewed_at->diffForHumans() }}</small>
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card border-2 shadow-sm mb-4">
                <div class="card-header bg-success text-white border-bottom-0">
                    <h5 class="mb-0 fw-bold">
                        <i class="bi bi-lightning-charge-fill me-2"></i>Quick Actions
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#statusModal">
                            <i class="bi bi-pencil-square me-2"></i>Change Status
                        </button>
                        <button type="button" class="btn btn-outline-success" data-bs-toggle="modal" data-bs-target="#assignModal">
                            <i class="bi bi-person-plus me-2"></i>Assign Reviewer
                        </button>
                        <a href="mailto:{{ $application->candidate->email }}" class="btn btn-outline-info">
                            <i class="bi bi-envelope me-2"></i>Send Email
                        </a>
                        <button type="button" class="btn btn-outline-danger" onclick="confirmDelete()">
                            <i class="bi bi-trash me-2"></i>Delete Application
                        </button>
                    </div>
                </div>
            </div>

            <!-- Activity Timeline -->
            <div class="card border-2 shadow-sm mb-4">
                <div class="card-header bg-secondary text-white border-bottom-0">
                    <h5 class="mb-0 fw-bold">
                        <i class="bi bi-clock-history me-2"></i>Activity Timeline
                    </h5>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        <div class="timeline-item mb-3 pb-3 border-bottom">
                            <div class="d-flex">
                                <div class="timeline-icon bg-primary text-white rounded-circle p-2 me-3" style="width: 40px; height: 40px;">
                                    <i class="bi bi-check-circle-fill"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="fw-bold mb-1">Application Submitted</h6>
                                    <p class="text-muted mb-1 small">Candidate submitted their application</p>
                                    <small class="text-muted">
                                        <i class="bi bi-calendar3 me-1"></i>
                                        {{ $application->created_at->format('M d, Y • h:i A') }}
                                    </small>
                                </div>
                            </div>
                        </div>

                        @if($application->reviewer)
                            <div class="timeline-item mb-3 pb-3 border-bottom">
                                <div class="d-flex">
                                    <div class="timeline-icon bg-success text-white rounded-circle p-2 me-3" style="width: 40px; height: 40px;">
                                        <i class="bi bi-person-plus-fill"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="fw-bold mb-1">Reviewer Assigned</h6>
                                        <p class="text-muted mb-1 small">Assigned to {{ $application->reviewer->name }}</p>
                                        <small class="text-muted">
                                            <i class="bi bi-calendar3 me-1"></i>
                                            {{ $application->updated_at->format('M d, Y • h:i A') }}
                                        </small>
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if($application->reviewed_at)
                            <div class="timeline-item">
                                <div class="d-flex">
                                    <div class="timeline-icon bg-info text-white rounded-circle p-2 me-3" style="width: 40px; height: 40px;">
                                        <i class="bi bi-clipboard-check-fill"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="fw-bold mb-1">Application Reviewed</h6>
                                        <p class="text-muted mb-1 small">Status: {{ $application->status_label }}</p>
                                        <small class="text-muted">
                                            <i class="bi bi-calendar3 me-1"></i>
                                            {{ $application->reviewed_at->format('M d, Y • h:i A') }}
                                        </small>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Update Status Modal -->
<div class="modal fade" id="statusModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <form action="{{ route('admin.applications.updateStatus', $application) }}" method="POST">
                @csrf
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title fw-bold">
                        <i class="bi bi-pencil-square me-2"></i>Update Application Status
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Select Status</label>
                        <select name="status" class="form-select form-select-lg" required>
                            @foreach($statuses as $status)
                                <option value="{{ $status }}" {{ $application->status == $status ? 'selected' : '' }}>
                                    {{ ucfirst(str_replace('_', ' ', $status)) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-0">
                        <label class="form-label fw-bold">Admin Notes</label>
                        <textarea name="admin_notes" class="form-control" rows="4" placeholder="Add notes about this decision...">{{ old('admin_notes', $application->admin_notes) }}</textarea>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle me-1"></i>Update Status
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Assign Reviewer Modal -->
<div class="modal fade" id="assignModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <form action="{{ route('admin.applications.assignReviewer', $application) }}" method="POST">
                @csrf
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title fw-bold">
                        <i class="bi bi-person-plus me-2"></i>Assign Reviewer
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Select Reviewer</label>
                        <select name="reviewer_id" class="form-select form-select-lg" required>
                            <option value="">-- Select Reviewer --</option>
                            @foreach($reviewers as $reviewer)
                                <option value="{{ $reviewer->id }}" {{ $application->reviewer_id == $reviewer->id ? 'selected' : '' }}>
                                    {{ $reviewer->name }} - {{ $reviewer->email }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="alert alert-warning mb-0">
                        <i class="bi bi-info-circle me-2"></i>
                        The application status will automatically change to "Under Review" after assigning a reviewer.
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-check-circle me-1"></i>Assign Reviewer
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
</script>
@endsection