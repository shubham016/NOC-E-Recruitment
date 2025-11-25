@extends('layouts.dashboard')

@section('title', 'Candidate Dashboard')

@section('portal-name', 'Candidate Portal')
@section('brand-icon', 'bi bi-briefcase')
@section('dashboard-route', route('candidate.dashboard'))
@section('user-name', Auth::guard('candidate')->user()->name)
@section('user-role', 'Job Seeker')
@section('user-initial', strtoupper(substr(Auth::guard('candidate')->user()->name, 0, 1)))
@section('logout-route', route('candidate.logout'))

@section('sidebar-menu')
    <a href="{{ route('candidate.dashboard') }}" class="sidebar-menu-item active">
        <i class="bi bi-speedometer2"></i>
        <span>Dashboard</span>
    </a>
    <a href="#" class="sidebar-menu-item">
        <i class="bi bi-search"></i>
        <span>Browse Jobs</span>
    </a>
    <a href="#" class="sidebar-menu-item">
        <i class="bi bi-file-earmark-text"></i>
        <span>My Applications</span>
    </a>
    <a href="#" class="sidebar-menu-item">
        <i class="bi bi-bookmark"></i>
        <span>Saved Jobs</span>
    </a>
    <a href="#" class="sidebar-menu-item">
        <i class="bi bi-person"></i>
        <span>My Profile</span>
    </a>
    <a href="#" class="sidebar-menu-item">
        <i class="bi bi-file-earmark-pdf"></i>
        <span>Resume</span>
    </a>
    <a href="#" class="sidebar-menu-item">
        <i class="bi bi-bell"></i>
        <span>Notifications</span>
    </a>
    <a href="#" class="sidebar-menu-item">
        <i class="bi bi-gear"></i>
        <span>Settings</span>
    </a>
@endsection

@section('custom-styles')
    <style>
        .job-card {
            transition: all 0.3s ease;
            cursor: pointer;
            border-left: 4px solid transparent;
        }

        .job-card:hover {
            border-left-color: #10b981;
            transform: translateX(5px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1) !important;
        }

        .company-logo {
            width: 50px;
            height: 50px;
            border-radius: 8px;
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 700;
            font-size: 1.25rem;
            flex-shrink: 0;
        }

        .progress-circle {
            position: relative;
            display: inline-block;
        }

        .profile-checklist .list-group-item {
            border: none;
            padding: 0.75rem 0;
        }

        .btn-apply {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            border: none;
            transition: all 0.3s ease;
        }

        .btn-apply:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(16, 185, 129, 0.3);
        }

        .tip-card {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
        }

        .badge-custom {
            padding: 0.35rem 0.65rem;
            font-weight: 500;
            font-size: 0.75rem;
        }
    </style>
@endsection

@section('content')
    <!-- Page Header -->
    <div class="page-header mb-4">
        <h1 class="page-title">Welcome back, {{ Auth::guard('candidate')->user()->name }}! 🎯</h1>
        <p class="page-subtitle">Track your applications and discover new opportunities that match your skills.</p>
    </div>

    <!-- Stats Cards -->
    <div class="row g-4 mb-4">
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card stat-card h-100">
                <div class="stat-icon blue">
                    <i class="bi bi-file-earmark-text-fill"></i>
                </div>
                <h3 class="h2 fw-bold mb-1">8</h3>
                <p class="text-muted mb-2">Total Applications</p>
                <small class="text-info">
                    <i class="bi bi-info-circle me-1"></i>All time
                </small>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card stat-card h-100">
                <div class="stat-icon orange">
                    <i class="bi bi-hourglass-split"></i>
                </div>
                <h3 class="h2 fw-bold mb-1">3</h3>
                <p class="text-muted mb-2">Pending Review</p>
                <small class="text-warning">
                    <i class="bi bi-clock me-1"></i>In progress
                </small>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card stat-card h-100">
                <div class="stat-icon emerald">
                    <i class="bi bi-star-fill"></i>
                </div>
                <h3 class="h2 fw-bold mb-1">2</h3>
                <p class="text-muted mb-2">Shortlisted</p>
                <small class="text-success">
                    <i class="bi bi-check-circle me-1"></i>Great news!
                </small>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card stat-card h-100">
                <div class="stat-icon slate">
                    <i class="bi bi-bookmark-fill"></i>
                </div>
                <h3 class="h2 fw-bold mb-1">12</h3>
                <p class="text-muted mb-2">Saved Jobs</p>
                <small class="text-muted">
                    <i class="bi bi-heart me-1"></i>For later
                </small>
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="row g-4">
        <!-- Left Column - Job Recommendations & Applications -->
        <div class="col-12 col-xl-8">
            <!-- My Applications -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-0 py-3">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <h5 class="mb-0 fw-bold">
                            <i class="bi bi-file-earmark-text text-primary me-2"></i>My Recent Applications
                        </h5>
                        <a href="#" class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-arrow-right me-1"></i>View All
                        </a>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="border-0 ps-4">Position</th>
                                    <th class="border-0">Company</th>
                                    <th class="border-0">Applied Date</th>
                                    <th class="border-0">Status</th>
                                    <th class="border-0 pe-4">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="ps-4">
                                        <div>
                                            <div class="fw-semibold text-dark">Senior Frontend Developer</div>
                                            <small class="text-muted">
                                                <i class="bi bi-clock-fill me-1"></i>Full-time
                                                <span class="mx-1">•</span>
                                                <i class="bi bi-geo-alt-fill me-1"></i>Remote
                                            </small>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="fw-medium">TechCorp Inc.</div>
                                    </td>
                                    <td>
                                        <span class="text-muted">Nov 15, 2024</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-success badge-custom">
                                            <i class="bi bi-check-circle me-1"></i>Shortlisted
                                        </span>
                                    </td>
                                    <td class="pe-4">
                                        <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal"
                                            data-bs-target="#applicationModal">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="ps-4">
                                        <div>
                                            <div class="fw-semibold text-dark">Full Stack Developer</div>
                                            <small class="text-muted">
                                                <i class="bi bi-clock-fill me-1"></i>Full-time
                                                <span class="mx-1">•</span>
                                                <i class="bi bi-geo-alt-fill me-1"></i>Hybrid
                                            </small>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="fw-medium">StartUp XYZ</div>
                                    </td>
                                    <td>
                                        <span class="text-muted">Nov 18, 2024</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-info text-white badge-custom">
                                            <i class="bi bi-arrow-repeat me-1"></i>Under Review
                                        </span>
                                    </td>
                                    <td class="pe-4">
                                        <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal"
                                            data-bs-target="#applicationModal">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="ps-4">
                                        <div>
                                            <div class="fw-semibold text-dark">React Developer</div>
                                            <small class="text-muted">
                                                <i class="bi bi-clock-fill me-1"></i>Contract
                                                <span class="mx-1">•</span>
                                                <i class="bi bi-geo-alt-fill me-1"></i>Remote
                                            </small>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="fw-medium">Digital Agency</div>
                                    </td>
                                    <td>
                                        <span class="text-muted">Nov 20, 2024</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-warning text-dark badge-custom">
                                            <i class="bi bi-hourglass-split me-1"></i>Pending
                                        </span>
                                    </td>
                                    <td class="pe-4">
                                        <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal"
                                            data-bs-target="#applicationModal">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Recommended Jobs -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0 fw-bold">
                        <i class="bi bi-lightbulb text-warning me-2"></i>Recommended Jobs for You
                    </h5>
                </div>
                <div class="card-body">
                    <!-- Job Card 1 -->
                    <div class="card job-card border shadow-sm mb-3">
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div class="d-flex gap-3 flex-grow-1">
                                    <div class="company-logo">TC</div>
                                    <div class="flex-grow-1">
                                        <h6 class="fw-bold mb-1">Senior Laravel Developer</h6>
                                        <p class="text-muted mb-2">TechCorp Solutions</p>
                                        <div class="d-flex gap-2 flex-wrap">
                                            <span class="badge bg-light text-dark border">
                                                <i class="bi bi-geo-alt me-1"></i>Remote
                                            </span>
                                            <span class="badge bg-light text-dark border">
                                                <i class="bi bi-clock me-1"></i>Full-time
                                            </span>
                                            <span class="badge bg-light text-dark border">
                                                <i class="bi bi-currency-dollar me-1"></i>$80k-$120k
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <button class="btn btn-sm btn-outline-secondary border-0">
                                    <i class="bi bi-bookmark fs-5"></i>
                                </button>
                            </div>

                            <p class="text-muted mb-3 small">
                                We're looking for an experienced Laravel developer to join our growing team. You'll work on
                                exciting projects using modern PHP frameworks and technologies.
                            </p>

                            <div class="d-flex flex-wrap gap-1 mb-3">
                                <span class="badge bg-primary bg-opacity-10 text-primary">Laravel</span>
                                <span class="badge bg-primary bg-opacity-10 text-primary">PHP</span>
                                <span class="badge bg-primary bg-opacity-10 text-primary">MySQL</span>
                                <span class="badge bg-primary bg-opacity-10 text-primary">Vue.js</span>
                            </div>

                            <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                                <small class="text-muted">
                                    <i class="bi bi-clock-history me-1"></i>Posted 2 days ago
                                </small>
                                <button class="btn btn-primary btn-apply px-4">
                                    <i class="bi bi-send me-2"></i>Apply Now
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Job Card 2 -->
                    <div class="card job-card border shadow-sm mb-3">
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div class="d-flex gap-3 flex-grow-1">
                                    <div class="company-logo">DS</div>
                                    <div class="flex-grow-1">
                                        <h6 class="fw-bold mb-1">Backend Engineer</h6>
                                        <p class="text-muted mb-2">DevSquad LLC</p>
                                        <div class="d-flex gap-2 flex-wrap">
                                            <span class="badge bg-light text-dark border">
                                                <i class="bi bi-geo-alt me-1"></i>New York, NY
                                            </span>
                                            <span class="badge bg-light text-dark border">
                                                <i class="bi bi-clock me-1"></i>Full-time
                                            </span>
                                            <span class="badge bg-light text-dark border">
                                                <i class="bi bi-currency-dollar me-1"></i>$90k-$130k
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <button class="btn btn-sm btn-outline-secondary border-0">
                                    <i class="bi bi-bookmark fs-5"></i>
                                </button>
                            </div>

                            <p class="text-muted mb-3 small">
                                Join our innovative team building scalable backend systems. Work with cutting-edge
                                technologies and contribute to high-impact projects.
                            </p>

                            <div class="d-flex flex-wrap gap-1 mb-3">
                                <span class="badge bg-primary bg-opacity-10 text-primary">Node.js</span>
                                <span class="badge bg-primary bg-opacity-10 text-primary">Python</span>
                                <span class="badge bg-primary bg-opacity-10 text-primary">AWS</span>
                                <span class="badge bg-primary bg-opacity-10 text-primary">Docker</span>
                            </div>

                            <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                                <small class="text-muted">
                                    <i class="bi bi-clock-history me-1"></i>Posted 5 days ago
                                </small>
                                <button class="btn btn-primary btn-apply px-4">
                                    <i class="bi bi-send me-2"></i>Apply Now
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Job Card 3 -->
                    <div class="card job-card border shadow-sm">
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div class="d-flex gap-3 flex-grow-1">
                                    <div class="company-logo">WD</div>
                                    <div class="flex-grow-1">
                                        <h6 class="fw-bold mb-1">Full Stack PHP Developer</h6>
                                        <p class="text-muted mb-2">WebDev Pro</p>
                                        <div class="d-flex gap-2 flex-wrap">
                                            <span class="badge bg-light text-dark border">
                                                <i class="bi bi-geo-alt me-1"></i>San Francisco, CA
                                            </span>
                                            <span class="badge bg-light text-dark border">
                                                <i class="bi bi-clock me-1"></i>Contract
                                            </span>
                                            <span class="badge bg-light text-dark border">
                                                <i class="bi bi-currency-dollar me-1"></i>$70k-$100k
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <button class="btn btn-sm btn-outline-secondary border-0">
                                    <i class="bi bi-bookmark fs-5"></i>
                                </button>
                            </div>

                            <p class="text-muted mb-3 small">
                                Looking for a skilled PHP developer for a 6-month contract project. Great opportunity to
                                work with a talented team on exciting web applications.
                            </p>

                            <div class="d-flex flex-wrap gap-1 mb-3">
                                <span class="badge bg-primary bg-opacity-10 text-primary">PHP</span>
                                <span class="badge bg-primary bg-opacity-10 text-primary">JavaScript</span>
                                <span class="badge bg-primary bg-opacity-10 text-primary">MySQL</span>
                                <span class="badge bg-primary bg-opacity-10 text-primary">Git</span>
                            </div>

                            <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                                <small class="text-muted">
                                    <i class="bi bi-clock-history me-1"></i>Posted 1 week ago
                                </small>
                                <button class="btn btn-primary btn-apply px-4">
                                    <i class="bi bi-send me-2"></i>Apply Now
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- View More Button -->
                    <div class="text-center mt-4">
                        <a href="#" class="btn btn-outline-primary px-5">
                            <i class="bi bi-arrow-right-circle me-2"></i>View More Jobs
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column - Profile & Quick Actions -->
        <div class="col-12 col-xl-4">
            <!-- Profile Completion -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0 fw-bold">
                        <i class="bi bi-person-circle text-success me-2"></i>Profile Completion
                    </h5>
                </div>
                <div class="card-body text-center">
                    <div class="position-relative d-inline-block mb-3">
                        <svg width="140" height="140">
                            <circle cx="70" cy="70" r="60" fill="none" stroke="#e2e8f0" stroke-width="10" />
                            <circle cx="70" cy="70" r="60" fill="none" stroke="#10b981" stroke-width="10"
                                stroke-dasharray="376.99" stroke-dashoffset="75.398" transform="rotate(-90 70 70)"
                                stroke-linecap="round" />
                        </svg>
                        <div class="position-absolute top-50 start-50 translate-middle">
                            <h2 class="fw-bold mb-0 text-success">80%</h2>
                            <small class="text-muted">Complete</small>
                        </div>
                    </div>

                    <div class="alert alert-success border-0 bg-success bg-opacity-10 text-success mb-3" role="alert">
                        <i class="bi bi-info-circle-fill me-2"></i>
                        <small>Complete your profile to increase visibility by 60%!</small>
                    </div>

                    <div class="profile-checklist text-start">
                        <div class="list-group list-group-flush">
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <i class="bi bi-check-circle-fill text-success me-2"></i>
                                    <span class="small">Basic Information</span>
                                </div>
                                <i class="bi bi-check-lg text-success"></i>
                            </div>
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <i class="bi bi-check-circle-fill text-success me-2"></i>
                                    <span class="small">Upload Resume</span>
                                </div>
                                <i class="bi bi-check-lg text-success"></i>
                            </div>
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <i class="bi bi-circle text-muted me-2"></i>
                                    <span class="small">Add Skills</span>
                                </div>
                                <button class="btn btn-sm btn-primary">Add</button>
                            </div>
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <i class="bi bi-circle text-muted me-2"></i>
                                    <span class="small">Work Experience</span>
                                </div>
                                <button class="btn btn-sm btn-primary">Add</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0 fw-bold">
                        <i class="bi bi-lightning-charge text-warning me-2"></i>Quick Actions
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-3">
                        <button class="btn btn-primary btn-lg">
                            <i class="bi bi-search me-2"></i>Browse All Jobs
                        </button>
                        <button class="btn btn-outline-primary btn-lg">
                            <i class="bi bi-upload me-2"></i>Update Resume
                        </button>
                        <button class="btn btn-outline-secondary btn-lg">
                            <i class="bi bi-person-gear me-2"></i>Edit Profile
                        </button>
                    </div>

                    <hr class="my-4">

                    <h6 class="fw-bold mb-3">
                        <i class="bi bi-bar-chart me-2"></i>Application Stats
                    </h6>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="text-muted small">Response Rate</span>
                        <span class="fw-bold text-success">75%</span>
                    </div>
                    <div class="progress mb-3" style="height: 8px;">
                        <div class="progress-bar bg-success" role="progressbar" style="width: 75%"></div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-muted small">Profile Views (This Week)</span>
                        <span class="badge bg-primary">24</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-muted small">Saved by Recruiters</span>
                        <span class="badge bg-info">5</span>
                    </div>
                </div>
            </div>

            <!-- Application Tips -->
            <div class="card border-0 shadow-sm">
                <div class="card-header tip-card py-3">
                    <h6 class="mb-0 fw-bold">
                        <i class="bi bi-lightbulb-fill me-2"></i>Pro Tips for Success
                    </h6>
                </div>
                <div class="card-body">
                    <div class="mb-4">
                        <div class="d-flex gap-2 mb-2">
                            <div class="text-primary">
                                <i class="bi bi-check-circle-fill"></i>
                            </div>
                            <div>
                                <h6 class="fw-semibold mb-1">Tailor Your Resume</h6>
                                <small class="text-muted">Customize your resume for each job application to highlight
                                    relevant skills and experience.</small>
                            </div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <div class="d-flex gap-2 mb-2">
                            <div class="text-success">
                                <i class="bi bi-check-circle-fill"></i>
                            </div>
                            <div>
                                <h6 class="fw-semibold mb-1">Follow Up Professionally</h6>
                                <small class="text-muted">Check your application status regularly and send a polite
                                    follow-up email after one week.</small>
                            </div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <div class="d-flex gap-2 mb-2">
                            <div class="text-warning">
                                <i class="bi bi-check-circle-fill"></i>
                            </div>
                            <div>
                                <h6 class="fw-semibold mb-1">Research the Company</h6>
                                <small class="text-muted">Learn about the company culture, values, and recent news before
                                    applying or interviewing.</small>
                            </div>
                        </div>
                    </div>

                    <div>
                        <div class="d-flex gap-2 mb-2">
                            <div class="text-info">
                                <i class="bi bi-check-circle-fill"></i>
                            </div>
                            <div>
                                <h6 class="fw-semibold mb-1">Practice Interview Skills</h6>
                                <small class="text-muted">Prepare answers to common interview questions and practice with a
                                    friend or mentor.</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Application Details Modal -->
    <div class="modal fade" id="applicationModal" tabindex="-1" aria-labelledby="applicationModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-light border-0">
                    <h5 class="modal-title fw-bold" id="applicationModalLabel">
                        <i class="bi bi-file-earmark-text text-primary me-2"></i>Application Details
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="d-flex gap-3 mb-4">
                        <div class="company-logo">TC</div>
                        <div>
                            <h5 class="fw-bold mb-1">Senior Frontend Developer</h5>
                            <p class="text-muted mb-2">TechCorp Inc. • Remote • Full-time</p>
                            <span class="badge bg-success">Shortlisted</span>
                        </div>
                    </div>

                    <hr>

                    <h6 class="fw-bold mb-3">Application Timeline</h6>
                    <div class="timeline">
                        <div class="d-flex gap-3 mb-3">
                            <div>
                                <div class="bg-success rounded-circle p-2"
                                    style="width: 36px; height: 36px; display: flex; align-items: center; justify-content: center;">
                                    <i class="bi bi-check text-white"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="fw-semibold mb-1">Application Submitted</h6>
                                <p class="text-muted small mb-0">November 15, 2024 at 10:30 AM</p>
                                <p class="text-muted small">Your application has been successfully submitted.</p>
                            </div>
                        </div>

                        <div class="d-flex gap-3 mb-3">
                            <div>
                                <div class="bg-info rounded-circle p-2"
                                    style="width: 36px; height: 36px; display: flex; align-items: center; justify-content: center;">
                                    <i class="bi bi-arrow-repeat text-white"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="fw-semibold mb-1">Under Review</h6>
                                <p class="text-muted small mb-0">November 16, 2024 at 2:15 PM</p>
                                <p class="text-muted small">Your application is being reviewed by our recruitment team.</p>
                            </div>
                        </div>

                        <div class="d-flex gap-3">
                            <div>
                                <div class="bg-success rounded-circle p-2"
                                    style="width: 36px; height: 36px; display: flex; align-items: center; justify-content: center;">
                                    <i class="bi bi-star text-white"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="fw-semibold mb-1">Shortlisted</h6>
                                <p class="text-muted small mb-0">November 18, 2024 at 9:00 AM</p>
                                <p class="text-muted small mb-0">Congratulations! You've been shortlisted for this position.
                                </p>
                                <div class="alert alert-success border-0 bg-success bg-opacity-10 mt-2 mb-0" role="alert">
                                    <i class="bi bi-info-circle-fill me-2"></i>
                                    <small>Expect an interview invitation within 3-5 business days.</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <h6 class="fw-bold mb-3">Application Details</h6>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="border rounded p-3">
                                <small class="text-muted d-block mb-1">Applied Date</small>
                                <div class="fw-semibold">November 15, 2024</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="border rounded p-3">
                                <small class="text-muted d-block mb-1">Application ID</small>
                                <div class="fw-semibold">#APP-2024-001</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="border rounded p-3">
                                <small class="text-muted d-block mb-1">Resume</small>
                                <div class="fw-semibold">
                                    <i class="bi bi-file-earmark-pdf text-danger me-1"></i>resume.pdf
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="border rounded p-3">
                                <small class="text-muted d-block mb-1">Cover Letter</small>
                                <div class="fw-semibold">
                                    <i class="bi bi-file-earmark-text text-primary me-1"></i>cover_letter.pdf
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div class="bg-light rounded p-3">
                        <h6 class="fw-bold mb-2">
                            <i class="bi bi-envelope text-primary me-2"></i>Contact Information
                        </h6>
                        <p class="text-muted small mb-2">If you have any questions, feel free to reach out:</p>
                        <div class="d-flex gap-3 flex-wrap">
                            <small>
                                <i class="bi bi-envelope me-1"></i>
                                <a href="mailto:hr@techcorp.com" class="text-decoration-none">hr@techcorp.com</a>
                            </small>
                            <small>
                                <i class="bi bi-telephone me-1"></i>
                                <a href="tel:+1234567890" class="text-decoration-none">+1 (234) 567-890</a>
                            </small>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 bg-light">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle me-1"></i>Close
                    </button>
                    <button type="button" class="btn btn-primary">
                        <i class="bi bi-download me-1"></i>Download Details
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection
</div>