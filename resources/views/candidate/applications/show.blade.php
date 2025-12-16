@extends('layouts.dashboard')

@section('title', 'Application Details')

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
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h4 class="mb-0"><i class="fas fa-file-alt"></i> Application Details</h4>
                <div>
                    @if($application->canEdit())
                        <a href="{{ route('candidate.jobs.applications.edit', [$application->job_posting_id, $application->id]) }}"
                            class="btn btn-light btn-sm me-2">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                    @endif
                    <a href="{{ route('candidate.applications.index') }}" class="btn btn-light btn-sm">
                        <i class="fas fa-arrow-left"></i> Back
                    </a>
                </div>
            </div>

            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                <!-- Job & Status Information -->
                <div class="row mb-4">
                    <div class="col-md-8">
                        <h5 class="text-primary">{{ $application->jobPosting->title }}</h5>
                        <p class="text-muted mb-1">
                            <i class="fas fa-building"></i> {{ $application->jobPosting->department }} |
                            <i class="fas fa-map-marker-alt"></i> {{ $application->jobPosting->location }}
                        </p>
                        <p class="text-muted">
                            <i class="fas fa-calendar"></i> Applied: {{ $application->created_at->format('F d, Y h:i A') }}
                        </p>
                    </div>
                    <div class="col-md-4 text-end">
                        <h6>Application Status</h6>
                        <span class="badge bg-{{ $application->status_color }} fs-6 px-3 py-2">
                            {{ $application->status_label }}
                        </span>
                        @if($application->reviewed_at)
                            <p class="text-muted small mt-2 mb-0">
                                Reviewed: {{ $application->reviewed_at->format('M d, Y') }}
                            </p>
                        @endif
                    </div>
                </div>

                <hr>

                <!-- Personal Information -->
                <h5 class="mb-3"><i class="fas fa-user"></i> Personal Information</h5>
                <div class="row mb-4">
                    <div class="col-md-2 text-center">
                        @if($application->passport_photo)
                            <img src="{{ asset('storage/' . $application->passport_photo) }}" class="img-thumbnail"
                                style="width: 150px; height: 150px; object-fit: cover;">
                        @else
                            <div class="bg-secondary" style="width: 150px; height: 150px;"></div>
                        @endif
                    </div>
                    <div class="col-md-10">
                        <div class="row">
                            <div class="col-md-6 mb-2">
                                <strong>Birth Date (AD):</strong>
                                {{ $application->birth_date_ad ? $application->birth_date_ad->format('Y-m-d') : '-' }}
                            </div>
                            <div class="col-md-6 mb-2">
                                <strong>Birth Date (BS):</strong> {{ $application->birth_date_bs ?? '-' }}
                            </div>
                            <div class="col-md-6 mb-2">
                                <strong>Age:</strong> {{ $application->age }} years
                            </div>
                            <div class="col-md-6 mb-2">
                                <strong>Gender:</strong> {{ ucfirst($application->gender) }}
                            </div>
                            <div class="col-md-6 mb-2">
                                <strong>Phone:</strong> {{ $application->phone }}
                            </div>
                            <div class="col-md-6 mb-2">
                                <strong>Religion:</strong> {{ $application->religion }}
                            </div>
                            <div class="col-md-6 mb-2">
                                <strong>Marital Status:</strong> {{ ucfirst($application->marital_status) }}
                            </div>
                            <div class="col-md-6 mb-2">
                                <strong>Blood Group:</strong> {{ $application->blood_group ?? '-' }}
                            </div>
                            <div class="col-md-6 mb-2">
                                <strong>Mother Tongue:</strong> {{ $application->mother_tongue }}
                            </div>
                            <div class="col-md-6 mb-2">
                                <strong>Physical Disability:</strong> {{ ucfirst($application->physical_disability) }}
                            </div>
                        </div>
                    </div>
                </div>

                <hr>

                <!-- Citizenship Information -->
                <h5 class="mb-3"><i class="fas fa-id-card"></i> Citizenship Information</h5>
                <div class="row mb-4">
                    <div class="col-md-6 mb-2">
                        <strong>Citizenship Number:</strong> {{ $application->citizenship_number }}
                    </div>
                    <div class="col-md-6 mb-2">
                        <strong>Issue Date (AD):</strong>
                        {{ $application->citizenship_issue_date_ad ? $application->citizenship_issue_date_ad->format('Y-m-d') : '-' }}
                    </div>
                    <div class="col-md-6 mb-2">
                        <strong>Issue Date (BS):</strong> {{ $application->citizenship_issue_date_bs ?? '-' }}
                    </div>
                    <div class="col-md-6 mb-2">
                        <strong>Issue District:</strong> {{ $application->citizenship_issue_district }}
                    </div>
                    <div class="col-md-6 mb-2">
                        <strong>Nationality:</strong> {{ $application->nationality }}
                    </div>
                </div>

                <hr>

                <!-- Family Information -->
                <h5 class="mb-3"><i class="fas fa-users"></i> Family Information</h5>
                <div class="row mb-4">
                    <div class="col-md-6 mb-3">
                        <h6>Father's Information</h6>
                        <p class="mb-1"><strong>Name (English):</strong> {{ $application->father_name_english }}</p>
                        <p class="mb-1"><strong>Name (Nepali):</strong> {{ $application->father_name_nepali }}</p>
                        <p class="mb-0"><strong>Qualification:</strong> {{ $application->father_qualification ?? '-' }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <h6>Mother's Information</h6>
                        <p class="mb-1"><strong>Name (English):</strong> {{ $application->mother_name_english }}</p>
                        <p class="mb-1"><strong>Name (Nepali):</strong> {{ $application->mother_name_nepali }}</p>
                        <p class="mb-0"><strong>Qualification:</strong> {{ $application->mother_qualification ?? '-' }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <h6>Grandfather's Information</h6>
                        <p class="mb-1"><strong>Name (English):</strong> {{ $application->grandfather_name_english }}</p>
                        <p class="mb-0"><strong>Name (Nepali):</strong> {{ $application->grandfather_name_nepali }}</p>
                    </div>
                    @if($application->spouse_name_english)
                        <div class="col-md-6 mb-3">
                            <h6>Spouse Information</h6>
                            <p class="mb-1"><strong>Name (English):</strong> {{ $application->spouse_name_english }}</p>
                            <p class="mb-1"><strong>Name (Nepali):</strong> {{ $application->spouse_name_nepali ?? '-' }}</p>
                            <p class="mb-0"><strong>Nationality:</strong> {{ $application->spouse_nationality ?? '-' }}</p>
                        </div>
                    @endif
                </div>

                <hr>

                <!-- Address Information -->
                <h5 class="mb-3"><i class="fas fa-map-marker-alt"></i> Address Information</h5>
                <div class="row mb-4">
                    <div class="col-md-6 mb-3">
                        <h6>Permanent Address</h6>
                        <p class="mb-0">{{ $application->full_permanent_address }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <h6>Mailing Address</h6>
                        @if($application->same_as_permanent)
                            <p class="mb-0 text-muted">Same as Permanent Address</p>
                        @else
                            <p class="mb-0">{{ $application->full_mailing_address }}</p>
                        @endif
                    </div>
                </div>

                <hr>

                <!-- Work Experience -->
                <h5 class="mb-3"><i class="fas fa-briefcase"></i> Work Experience</h5>
                <div class="row mb-4">
                    <div class="col-md-6 mb-2">
                        <strong>Years of Experience:</strong> {{ $application->years_of_experience }} years
                    </div>
                    {{-- <div class="col-md-6 mb-2">
                        <strong>Current Salary:</strong> {{ $application->current_salary ?? 'Not Disclosed' }}
                    </div>
                    <div class="col-md-6 mb-2">
                        <strong>Expected Salary:</strong> {{ $application->expected_salary ?? 'Not Disclosed' }}
                    </div>
                    <div class="col-md-6 mb-2">
                        <strong>Available From:</strong>
                        {{ $application->available_from ? $application->available_from->format('Y-m-d') : 'Immediately' }}
                    </div> --}}
                    @if($application->relevant_experience)
                        <div class="col-12 mt-2">
                            <strong>Relevant Experience:</strong>
                            <p class="mt-1">{{ $application->relevant_experience }}</p>
                        </div>
                    @endif
                </div>

                <hr>

                <!-- Cover Letter -->
                <h5 class="mb-3"><i class="fas fa-file-alt"></i> Cover Letter</h5>
                <div class="mb-4">
                    <p style="white-space: pre-wrap;">{{ $application->cover_letter }}</p>
                </div>

                <hr>

                <!-- Uploaded Documents -->
                <h5 class="mb-3"><i class="fas fa-paperclip"></i> Uploaded Documents
                    ({{ $application->uploaded_documents_count }})</h5>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th>Document Type</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Passport Photo</td>
                                <td>
                                    @if($application->passport_photo)
                                        <span class="badge bg-success">Uploaded</span>
                                    @else
                                        <span class="badge bg-danger">Missing</span>
                                    @endif
                                </td>
                                <td>
                                    @if($application->passport_photo)
                                        <a href="{{ asset('storage/' . $application->passport_photo) }}" target="_blank"
                                            class="btn btn-sm btn-primary">
                                            <i class="fas fa-eye"></i> View
                                        </a>
                                    @else
                                        -
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td>Resume/CV</td>
                                <td>
                                    @if($application->resume)
                                        <span class="badge bg-success">Uploaded</span>
                                    @else
                                        <span class="badge bg-danger">Missing</span>
                                    @endif
                                </td>
                                <td>
                                    @if($application->resume)
                                        <a href="{{ asset('storage/' . $application->resume) }}" target="_blank"
                                            class="btn btn-sm btn-primary">
                                            <i class="fas fa-download"></i> Download
                                        </a>
                                    @else
                                        -
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td>Cover Letter File</td>
                                <td>
                                    @if($application->cover_letter_file)
                                        <span class="badge bg-success">Uploaded</span>
                                    @else
                                        <span class="badge bg-secondary">Optional</span>
                                    @endif
                                </td>
                                <td>
                                    @if($application->cover_letter_file)
                                        <a href="{{ asset('storage/' . $application->cover_letter_file) }}" target="_blank"
                                            class="btn btn-sm btn-primary">
                                            <i class="fas fa-download"></i> Download
                                        </a>
                                    @else
                                        -
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td>Citizenship Certificate</td>
                                <td>
                                    @if($application->citizenship_certificate)
                                        <span class="badge bg-success">Uploaded</span>
                                    @else
                                        <span class="badge bg-danger">Missing</span>
                                    @endif
                                </td>
                                <td>
                                    @if($application->citizenship_certificate)
                                        <a href="{{ asset('storage/' . $application->citizenship_certificate) }}"
                                            target="_blank" class="btn btn-sm btn-primary">
                                            <i class="fas fa-download"></i> Download
                                        </a>
                                    @else
                                        -
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td>Educational Certificates</td>
                                <td>
                                    @if($application->educational_certificates)
                                        <span class="badge bg-success">Uploaded</span>
                                    @else
                                        <span class="badge bg-danger">Missing</span>
                                    @endif
                                </td>
                                <td>
                                    @if($application->educational_certificates)
                                        <a href="{{ asset('storage/' . $application->educational_certificates) }}"
                                            target="_blank" class="btn btn-sm btn-primary">
                                            <i class="fas fa-download"></i> Download
                                        </a>
                                    @else
                                        -
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td>Experience Certificates</td>
                                <td>
                                    @if($application->experience_certificates)
                                        <span class="badge bg-success">Uploaded</span>
                                    @else
                                        <span class="badge bg-secondary">Optional</span>
                                    @endif
                                </td>
                                <td>
                                    @if($application->experience_certificates)
                                        <a href="{{ asset('storage/' . $application->experience_certificates) }}"
                                            target="_blank" class="btn btn-sm btn-primary">
                                            <i class="fas fa-download"></i> Download
                                        </a>
                                    @else
                                        -
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td>NOC ID Card</td>
                                <td>
                                    @if($application->noc_id_card)
                                        <span class="badge bg-success">Uploaded</span>
                                    @else
                                        <span class="badge bg-secondary">Optional</span>
                                    @endif
                                </td>
                                <td>
                                    @if($application->noc_id_card)
                                        <a href="{{ asset('storage/' . $application->noc_id_card) }}" target="_blank"
                                            class="btn btn-sm btn-primary">
                                            <i class="fas fa-download"></i> Download
                                        </a>
                                    @else
                                        -
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td>Ethnic Certificate</td>
                                <td>
                                    @if($application->ethnic_certificate)
                                        <span class="badge bg-success">Uploaded</span>
                                    @else
                                        <span class="badge bg-secondary">Optional</span>
                                    @endif
                                </td>
                                <td>
                                    @if($application->ethnic_certificate)
                                        <a href="{{ asset('storage/' . $application->ethnic_certificate) }}" target="_blank"
                                            class="btn btn-sm btn-primary">
                                            <i class="fas fa-download"></i> Download
                                        </a>
                                    @else
                                        -
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td>Disability Certificate</td>
                                <td>
                                    @if($application->disability_certificate)
                                        <span class="badge bg-success">Uploaded</span>
                                    @else
                                        <span class="badge bg-secondary">Optional</span>
                                    @endif
                                </td>
                                <td>
                                    @if($application->disability_certificate)
                                        <a href="{{ asset('storage/' . $application->disability_certificate) }}" target="_blank"
                                            class="btn btn-sm btn-primary">
                                            <i class="fas fa-download"></i> Download
                                        </a>
                                    @else
                                        -
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td>Other Documents</td>
                                <td>
                                    @if($application->other_documents)
                                        <span class="badge bg-success">Uploaded</span>
                                    @else
                                        <span class="badge bg-secondary">Optional</span>
                                    @endif
                                </td>
                                <td>
                                    @if($application->other_documents)
                                        <a href="{{ asset('storage/' . $application->other_documents) }}" target="_blank"
                                            class="btn btn-sm btn-primary">
                                            <i class="fas fa-download"></i> Download
                                        </a>
                                    @else
                                        -
                                    @endif
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Action Buttons -->
                <div class="mt-4">
                    <div class="d-flex gap-2">
                        @if($application->canEdit())
                            <a href="{{ route('candidate.jobs.applications.edit', [$application->job_posting_id, $application->id]) }}" class="btn btn-warning">
                                <i class="fas fa-edit"></i> Edit Application
                            </a>
                        @endif

                        @if($application->canWithdraw())
                            <form action="{{ route('candidate.applications.destroy', $application->id) }}" method="POST"
                                class="d-inline"
                                onsubmit="return confirm('Are you sure you want to withdraw this application?');">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger">
                                    <i class="fas fa-times"></i> Withdraw Application
                                </button>
                            </form>
                        @endif

                        <a href="{{ route('candidate.applications.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back to List
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection