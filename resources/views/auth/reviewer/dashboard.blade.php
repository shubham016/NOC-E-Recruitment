@section('content')
    <!-- Header -->
    <div class="reviewer-header">
        <div class="hero-content">
            <div class="row align-items-center g-2">
                <div class="col-lg-7">
                    <div class="d-flex align-items-start gap-2">
                        <div class="bg-white bg-opacity-20 rounded-2 p-2">
                            <i class="bi bi-clipboard-check fs-4"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold mb-1">
                                Good {{ date('H') < 12 ? 'Morning' : (date('H') < 18 ? 'Afternoon' : 'Evening') }},
                                {{ Auth::guard('reviewer')->user()->name }}! 👋
                            </h6>
                            <p class="mb-0 opacity-90" style="font-size: 0.85rem;">
                                You have <strong>32 applications</strong> waiting for review
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-5">
                    <div class="daily-target-box">
                        <div class="d-flex justify-content-center align-items-center gap-2 mb-2">
                            <i class="bi bi-target"></i>
                            <span class="fw-bold small">Daily Target</span>
                        </div>
                        <div class="text-center fw-bold mb-2" style="font-size: 1.75rem; line-height: 1;">
                            12<span class="opacity-75" style="font-size: 1rem;">/15</span>
                        </div>
                        <div class="progress mb-2"
                            style="height: 5px; background: rgba(255,255,255,0.25); border-radius: 10px;">
                            <div class="progress-bar bg-white" style="width: 80%; border-radius: 10px;"></div>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="opacity-90" style="font-size: 0.75rem;">80% Complete</small>
                            <span class="badge bg-white text-success px-2 py-1" style="font-size: 0.7rem;">
                                <i class="bi bi-fire text-danger"></i> On Track
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row g-2 mb-3">
        <div class="col-6 col-lg-3">
            <div class="stat-mini-card">
                <div class="d-flex align-items-center gap-2">
                    <div class="reviewer-stat-icon bg-warning bg-opacity-10 text-warning">
                        <i class="bi bi-hourglass-split"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-0">32</h5>
                        <small class="text-muted" style="font-size: 0.75rem;">Pending</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="stat-mini-card">
                <div class="d-flex align-items-center gap-2">
                    <div class="reviewer-stat-icon bg-success bg-opacity-10 text-success">
                        <i class="bi bi-check-circle-fill"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-0">156</h5>
                        <small class="text-muted" style="font-size: 0.75rem;">Reviewed</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="stat-mini-card">
                <div class="d-flex align-items-center gap-2">
                    <div class="reviewer-stat-icon bg-primary bg-opacity-10 text-primary">
                        <i class="bi bi-star-fill"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-0">45</h5>
                        <small class="text-muted" style="font-size: 0.75rem;">Shortlisted</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="stat-mini-card">
                <div class="d-flex align-items-center gap-2">
                    <div class="reviewer-stat-icon bg-info bg-opacity-10 text-info">
                        <i class="bi bi-graph-up-arrow"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-0">92%</h5>
                        <small class="text-muted" style="font-size: 0.75rem;">Rate</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="row g-3">
        <!-- Task List -->
        <div class="col-12 col-xl-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-2">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <div>
                            <h6 class="mb-0 fw-bold">
                                <i class="bi bi-list-task text-warning me-2"></i>Applications To Review
                            </h6>
                            <small class="text-muted" style="font-size: 0.75rem;">Prioritized by deadline</small>
                        </div>
                        <div class="btn-group btn-group-sm" role="group">
                            <button type="button" class="btn btn-outline-secondary active" style="font-size: 0.75rem;">
                                All <span class="badge bg-secondary ms-1">32</span>
                            </button>
                            <button type="button" class="btn btn-outline-secondary" style="font-size: 0.75rem;">
                                High <span class="badge bg-danger ms-1">8</span>
                            </button>
                            <button type="button" class="btn btn-outline-secondary" style="font-size: 0.75rem;">
                                Recent
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
                    <!-- High Priority -->
                    <div class="task-card high-priority border-bottom">
                        <div class="application-content">
                            <div class="application-details">
                                <div class="d-flex align-items-start gap-2">
                                    <div class="candidate-avatar-icon bg-danger bg-opacity-10 text-danger">
                                        <i class="bi bi-exclamation-triangle-fill"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="fw-bold mb-0" style="font-size: 0.875rem;">Jessica Smith</h6>
                                        <p class="text-muted mb-1" style="font-size: 0.75rem;">
                                            <i class="bi bi-briefcase me-1"></i>Senior Frontend Developer
                                        </p>
                                        <div class="d-flex gap-1 flex-wrap">
                                            <span class="badge bg-light text-dark" style="font-size: 0.65rem;">5+
                                                years</span>
                                            <span class="badge bg-light text-dark" style="font-size: 0.65rem;">Remote</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="application-meta">
                                <div class="text-end mb-2">
                                    <small class="text-muted d-block" style="font-size: 0.7rem;">Applied</small>
                                    <span class="fw-semibold" style="font-size: 0.8rem;">Nov 21, 2024</span>
                                    <br>
                                    <small class="text-danger" style="font-size: 0.7rem;">
                                        <i class="bi bi-alarm-fill me-1"></i>2 days
                                    </small>
                                </div>
                                <div class="text-end">
                                    <span class="priority-badge bg-danger text-white d-inline-block mb-2">
                                        <i class="bi bi-star-fill"></i>High
                                    </span>
                                    <br>
                                    <button class="btn btn-outline-secondary btn-sm review-btn" data-app-id="1">
                                        <i class="bi bi-eye me-1"></i>Review
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Medium Priority -->
                    <div class="task-card medium-priority border-bottom">
                        <div class="application-content">
                            <div class="application-details">
                                <div class="d-flex align-items-start gap-2">
                                    <div class="candidate-avatar-icon bg-warning bg-opacity-10 text-warning">
                                        <i class="bi bi-person-fill"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="fw-bold mb-0" style="font-size: 0.875rem;">Michael Brown</h6>
                                        <p class="text-muted mb-1" style="font-size: 0.75rem;">
                                            <i class="bi bi-briefcase me-1"></i>UX/UI Designer
                                        </p>
                                        <div class="d-flex gap-1 flex-wrap">
                                            <span class="badge bg-light text-dark" style="font-size: 0.65rem;">3
                                                years</span>
                                            <span class="badge bg-light text-dark" style="font-size: 0.65rem;">Hybrid</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="application-meta">
                                <div class="text-end mb-2">
                                    <small class="text-muted d-block" style="font-size: 0.7rem;">Applied</small>
                                    <span class="fw-semibold" style="font-size: 0.8rem;">Nov 20, 2024</span>
                                    <br>
                                    <small class="text-warning" style="font-size: 0.7rem;">
                                        <i class="bi bi-clock-fill me-1"></i>4 days
                                    </small>
                                </div>
                                <div class="text-end">
                                    <span class="priority-badge bg-warning text-dark d-inline-block mb-2">
                                        <i class="bi bi-dash-circle-fill"></i>Medium
                                    </span>
                                    <br>
                                    <button class="btn btn-outline-secondary btn-sm review-btn" data-app-id="2"
                                        style="font-size: 0.8rem; padding: 0.45rem 1.1rem;">
                                        <i class="bi bi-eye me-1"></i>Review
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Low Priority -->
                    <div class="task-card low-priority border-bottom">
                        <div class="application-content">
                            <div class="application-details">
                                <div class="d-flex align-items-start gap-2">
                                    <div class="candidate-avatar-icon bg-success bg-opacity-10 text-success">
                                        <i class="bi bi-person-check-fill"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="fw-bold mb-0" style="font-size: 0.875rem;">Emily Davis</h6>
                                        <p class="text-muted mb-1" style="font-size: 0.75rem;">
                                            <i class="bi bi-briefcase me-1"></i>Product Manager
                                        </p>
                                        <div class="d-flex gap-1 flex-wrap">
                                            <span class="badge bg-light text-dark" style="font-size: 0.65rem;">7+
                                                years</span>
                                            <span class="badge bg-light text-dark"
                                                style="font-size: 0.65rem;">On-site</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="application-meta">
                                <div class="text-end mb-2">
                                    <small class="text-muted d-block" style="font-size: 0.7rem;">Applied</small>
                                    <span class="fw-semibold" style="font-size: 0.8rem;">Nov 19, 2024</span>
                                    <br>
                                    <small class="text-success" style="font-size: 0.7rem;">
                                        <i class="bi bi-check-circle-fill me-1"></i>7 days
                                    </small>
                                </div>
                                <div class="text-end">
                                    <span class="priority-badge bg-success text-white d-inline-block mb-2">
                                        <i class="bi bi-check-circle-fill"></i>Low
                                    </span>
                                    <br>
                                    <button class="btn btn-outline-secondary btn-sm review-btn" data-app-id="3"
                                        style="font-size: 0.8rem; padding: 0.45rem 1.1rem;">
                                        <i class="bi bi-eye me-1"></i>Review
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Normal Priority -->
                    <div class="task-card normal-priority">
                        <div class="application-content">
                            <div class="application-details">
                                <div class="d-flex align-items-start gap-2">
                                    <div class="candidate-avatar-icon bg-info bg-opacity-10 text-info">
                                        <i class="bi bi-file-earmark-person-fill"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="fw-bold mb-0" style="font-size: 0.875rem;">Robert Wilson</h6>
                                        <p class="text-muted mb-1" style="font-size: 0.75rem;">
                                            <i class="bi bi-briefcase me-1"></i>DevOps Engineer
                                        </p>
                                        <div class="d-flex gap-1 flex-wrap">
                                            <span class="badge bg-light text-dark" style="font-size: 0.65rem;">4
                                                years</span>
                                            <span class="badge bg-light text-dark" style="font-size: 0.65rem;">Remote</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="application-meta">
                                <div class="text-end mb-2">
                                    <small class="text-muted d-block" style="font-size: 0.7rem;">Applied</small>
                                    <span class="fw-semibold" style="font-size: 0.8rem;">Nov 18, 2024</span>
                                    <br>
                                    <small class="text-muted" style="font-size: 0.7rem;">
                                        <i class="bi bi-clock-fill me-1"></i>5 days
                                    </small>
                                </div>
                                <div class="text-end">
                                    <span class="priority-badge bg-secondary text-white d-inline-block mb-2">
                                        <i class="bi bi-circle-fill"></i>Normal
                                    </span>
                                    <br>
                                    <button class="btn btn-outline-secondary btn-sm review-btn" data-app-id="4"
                                        style="font-size: 0.8rem; padding: 0.45rem 1.1rem;">
                                        <i class="bi bi-eye me-1"></i>Review
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-light text-center py-2">
                    <small class="text-muted d-block mb-2" style="font-size: 0.75rem;">Showing 4 of 32 applications</small>
                    <button class="btn btn-outline-primary btn-sm px-3" style="font-size: 0.75rem;">
                        <i class="bi bi-arrow-down-circle me-1"></i>Load More <span class="badge bg-primary ms-1">28</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Sidebar (keep existing code) -->
        <div class="col-12 col-xl-4">
            <!-- Progress -->
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header text-white py-2 border-0"
                    style="background: linear-gradient(135deg, #64748b 0%, #475569 100%);">
                    <h6 class="mb-0 fw-bold" style="font-size: 0.875rem;">
                        <i class="bi bi-bar-chart-line me-1"></i>Today's Progress
                        <span class="badge bg-white text-dark float-end" style="font-size: 0.65rem;">Live</span>
                    </h6>
                </div>
                <div class="card-body text-center py-3">
                    <div class="position-relative d-inline-block mb-3">
                        <svg width="100" height="100">
                            <circle cx="50" cy="50" r="42" fill="none" stroke="#e2e8f0" stroke-width="10" />
                            <circle cx="50" cy="50" r="42" fill="none" stroke="#64748b" stroke-width="10"
                                stroke-dasharray="263.89" stroke-dashoffset="52.778" transform="rotate(-90 50 50)"
                                stroke-linecap="round" class="progress-circle" />
                        </svg>
                        <div class="position-absolute top-50 start-50 translate-middle">
                            <h3 class="fw-bold mb-0" style="color: #64748b;">80%</h3>
                            <small class="text-muted" style="font-size: 0.7rem;">12/15</small>
                        </div>
                    </div>

                    <div class="row g-2">
                        <div class="col-6">
                            <div class="progress-breakdown-card bg-success bg-opacity-10">
                                <i class="bi bi-check-circle-fill text-success fs-5"></i>
                                <div class="fw-bold fs-6">8</div>
                                <small class="text-muted" style="font-size: 0.7rem;">Approved</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="progress-breakdown-card bg-danger bg-opacity-10">
                                <i class="bi bi-x-circle-fill text-danger fs-5"></i>
                                <div class="fw-bold fs-6">3</div>
                                <small class="text-muted" style="font-size: 0.7rem;">Rejected</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="progress-breakdown-card bg-warning bg-opacity-10">
                                <i class="bi bi-pause-circle-fill text-warning fs-5"></i>
                                <div class="fw-bold fs-6">1</div>
                                <small class="text-muted" style="font-size: 0.7rem;">On Hold</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="progress-breakdown-card bg-info bg-opacity-10">
                                <i class="bi bi-hourglass-split text-info fs-5"></i>
                                <div class="fw-bold fs-6">3</div>
                                <small class="text-muted" style="font-size: 0.7rem;">Remaining</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Activity -->
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header bg-white border-0 py-2">
                    <h6 class="mb-0 fw-bold" style="font-size: 0.875rem;">
                        <i class="bi bi-clock-history text-secondary me-1"></i>Recent Activity
                    </h6>
                </div>
                <div class="card-body py-2">
                    <div class="action-timeline">
                        <!-- Activity Item 1 -->
                        <div class="timeline-item">
                            <div class="timeline-dot" style="border-color: #10b981;"></div>
                            <div class="ms-3">
                                <p class="fw-semibold mb-0" style="font-size: 0.8rem;">Shortlisted Candidate</p>
                                <p class="text-muted mb-1" style="font-size: 0.7rem;">Amanda Lee - Backend Dev</p>
                                <small class="text-muted" style="font-size: 0.65rem;">2h ago</small>
                            </div>
                        </div>

                        <!-- Activity Item 2 -->
                        <div class="timeline-item">
                            <div class="timeline-dot" style="border-color: #3b82f6;"></div>
                            <div class="ms-3">
                                <p class="fw-semibold mb-0" style="font-size: 0.8rem;">Reviewed Application</p>
                                <p class="text-muted mb-1" style="font-size: 0.7rem;">David Martinez - Data Analyst</p>
                                <small class="text-muted" style="font-size: 0.65rem;">4h ago</small>
                            </div>
                        </div>

                        <!-- Activity Item 3 -->
                        <div class="timeline-item">
                            <div class="timeline-dot" style="border-color: #ef4444;"></div>
                            <div class="ms-3">
                                <p class="fw-semibold mb-0" style="font-size: 0.8rem;">Rejected Application</p>
                                <p class="text-muted mb-1" style="font-size: 0.7rem;">Sarah Taylor - Marketing</p>
                                <small class="text-muted" style="font-size: 0.65rem;">5h ago</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Achievement -->
            <div class="achievement-badge">
                <div class="achievement-content">
                    <i class="bi bi-award-fill" style="font-size: 2.5rem;"></i>
                    <h6 class="mt-2 fw-bold mb-2" style="font-size: 0.9rem;">🌟 Top Reviewer!</h6>
                    <p class="mb-2 small opacity-90" style="font-size: 0.75rem;">You're in the top 10% this month!</p>
                    <button class="btn btn-light btn-sm fw-semibold w-100 mb-2" style="font-size: 0.75rem;">
                        <i class="bi bi-graph-up-arrow me-1"></i>View Stats
                    </button>
                    <button class="btn btn-outline-light btn-sm w-100" style="font-size: 0.75rem;">
                        <i class="bi bi-share-fill me-1"></i>Share
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Review Modal -->
    <div class="modal fade" id="reviewModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-gradient text-white border-0"
                    style="background: linear-gradient(135deg, #64748b 0%, #475569 100%);">
                    <h5 class="modal-title fw-bold">
                        <i class="bi bi-file-earmark-person me-2"></i>Application Review
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-0">
                    <!-- Loading Spinner -->
                    <div id="modalLoading" class="text-center py-5">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mt-3 text-muted">Loading application details...</p>
                    </div>

                    <!-- Application Content -->
                    <div id="modalContent" style="display: none;">
                        <div class="row g-0">
                            <!-- Left: Candidate Info -->
                            <div class="col-lg-8 border-end">
                                <div class="p-4">
                                    <h6 class="fw-bold mb-3">
                                        <i class="bi bi-person-circle text-primary me-2"></i>Candidate Information
                                    </h6>
                                    <div class="row g-3 mb-4">
                                        <div class="col-md-6">
                                            <label class="small text-muted">Full Name</label>
                                            <p class="fw-semibold mb-0" id="candidateName">-</p>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="small text-muted">Email</label>
                                            <p class="mb-0" id="candidateEmail">-</p>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="small text-muted">Phone</label>
                                            <p class="mb-0" id="candidatePhone">-</p>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="small text-muted">Address</label>
                                            <p class="mb-0" id="candidateAddress">-</p>
                                        </div>
                                    </div>

                                    <hr>

                                    <h6 class="fw-bold mb-3">
                                        <i class="bi bi-briefcase text-warning me-2"></i>Position Details
                                    </h6>
                                    <div class="row g-3 mb-4">
                                        <div class="col-md-12">
                                            <label class="small text-muted">Job Title</label>
                                            <p class="fw-semibold mb-0" id="jobTitle">-</p>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="small text-muted">Department</label>
                                            <p class="mb-0" id="jobDepartment">-</p>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="small text-muted">Location</label>
                                            <p class="mb-0" id="jobLocation">-</p>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="small text-muted">Type</label>
                                            <p class="mb-0" id="jobType">-</p>
                                        </div>
                                        <div class="col-md-12">
                                            <label class="small text-muted">Salary Range</label>
                                            <p class="mb-0" id="salaryRange">-</p>
                                        </div>
                                    </div>

                                    <hr>

                                    <h6 class="fw-bold mb-3">
                                        <i class="bi bi-file-text text-success me-2"></i>Cover Letter
                                    </h6>
                                    <div class="bg-light rounded p-3 mb-4">
                                        <p class="mb-0 small" id="coverLetter">-</p>
                                    </div>

                                    <h6 class="fw-bold mb-3">
                                        <i class="bi bi-file-earmark-pdf text-danger me-2"></i>Resume
                                    </h6>
                                    <div id="resumeSection">
                                        <button class="btn btn-outline-primary btn-sm">
                                            <i class="bi bi-download me-1"></i>Download Resume
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Right: Review Actions -->
                            <div class="col-lg-4">
                                <div class="p-4 bg-light h-100">
                                    <h6 class="fw-bold mb-3">
                                        <i class="bi bi-pencil-square text-info me-2"></i>Review Actions
                                    </h6>

                                    <div class="mb-4">
                                        <label class="small text-muted d-block mb-2">Application Status</label>
                                        <span class="badge bg-warning text-dark" id="currentStatus">Pending</span>
                                    </div>

                                    <div class="mb-4">
                                        <label class="small text-muted d-block mb-2">Applied Date</label>
                                        <p class="mb-0 small" id="appliedDate">-</p>
                                    </div>

                                    <hr>

                                    <form id="reviewForm">
                                        <input type="hidden" id="applicationId">

                                        <div class="mb-3">
                                            <label class="form-label fw-semibold">Reviewer Notes</label>
                                            <textarea class="form-control" id="reviewerNotes" rows="4"
                                                placeholder="Add your notes here..."></textarea>
                                        </div>

                                        <div class="d-grid gap-2">
                                            <button type="button" class="btn btn-success btn-action"
                                                data-status="shortlisted">
                                                <i class="bi bi-check-circle me-2"></i>Shortlist
                                            </button>
                                            <button type="button" class="btn btn-danger btn-action" data-status="rejected">
                                                <i class="bi bi-x-circle me-2"></i>Reject
                                            </button>
                                            <button type="button" class="btn btn-info btn-action"
                                                data-status="under_review">
                                                <i class="bi bi-arrow-repeat me-2"></i>Under Review
                                            </button>
                                        </div>
                                    </form>
                                    <div id="reviewedInfo" style="display: none;" class="mt-4 p-3 bg-white rounded border">
                                        <h6 class="fw-bold mb-2 small">Previous Review</h6>
                                        <p class="mb-1 small"><strong>Reviewed by:</strong> <span id="reviewedBy">-</span>
                                        </p>
                                        <p class="mb-1 small"><strong>Date:</strong> <span id="reviewedAt">-</span></p>
                                        <p class="mb-0 small"><strong>Notes:</strong> <span id="previousNotes">-</span></p>
                                    </div>
                                </div>
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
        // Review Modal Handling
        document.addEventListener('DOMContentLoaded', function () {
            const reviewButtons = document.querySelectorAll('.review-btn');
            const reviewModal = new bootstrap.Modal(document.getElementById('reviewModal'));
            const modalLoading = document.getElementById('modalLoading');
            const modalContent = document.getElementById('modalContent');

            // Open modal and load application data
            reviewButtons.forEach(button => {
                button.addEventListener('click', function () {
                    const appId = this.getAttribute('data-app-id');
                    loadApplicationData(appId);
                    reviewModal.show();
                });
            });

            // Load application data via AJAX
            function loadApplicationData(appId) {
                modalLoading.style.display = 'block';
                modalContent.style.display = 'none';

                // Simulate AJAX call (replace with actual API call)
                setTimeout(() => {
                    // Mock data - replace with actual fetch
                    const mockData = {
                        id: appId,
                        candidate_name: 'Jessica Smith',
                        candidate_email: 'jessica.smith@email.com',
                        candidate_phone: '555-1234-567',
                        candidate_address: '456 Demo Ave, San Francisco, CA',
                        job_title: 'Senior Frontend Developer',
                        job_department: 'Engineering',
                        job_location: 'Remote',
                        job_type: 'Full-time',
                        salary_range: '$80,000 - $120,000',
                        cover_letter: 'I am very excited to apply for this position. With over 5 years of experience in React and Vue.js, I believe I would be a great fit for your team. I have worked on several large-scale applications and am passionate about creating excellent user experiences.',
                        status: 'pending',
                        applied_at: 'Nov 21, 2024 10:30 AM',
                        resume: 'resume.pdf'
                    };

                    populateModal(mockData);
                    modalLoading.style.display = 'none';
                    modalContent.style.display = 'block';
                }, 500);
            }

            // Populate modal with data
            function populateModal(data) {
                document.getElementById('applicationId').value = data.id;
                document.getElementById('candidateName').textContent = data.candidate_name;
                document.getElementById('candidateEmail').textContent = data.candidate_email;
                document.getElementById('candidatePhone').textContent = data.candidate_phone;
                document.getElementById('candidateAddress').textContent = data.candidate_address;
                document.getElementById('jobTitle').textContent = data.job_title;
                document.getElementById('jobDepartment').textContent = data.job_department;
                document.getElementById('jobLocation').textContent = data.job_location;
                document.getElementById('jobType').textContent = data.job_type;
                document.getElementById('salaryRange').textContent = data.salary_range;
                document.getElementById('coverLetter').textContent = data.cover_letter;
                document.getElementById('currentStatus').textContent = data.status.charAt(0).toUpperCase() + data.status.slice(1);
                document.getElementById('appliedDate').textContent = data.applied_at;
            }

            // Handle review actions
            const actionButtons = document.querySelectorAll('.btn-action');
            actionButtons.forEach(button => {
                button.addEventListener('click', function () {
                    const status = this.getAttribute('data-status');
                    const appId = document.getElementById('applicationId').value;
                    const notes = document.getElementById('reviewerNotes').value;

                    updateApplicationStatus(appId, status, notes);
                });
            });

            // Update application status
            function updateApplicationStatus(appId, status, notes) {
                // Show loading state
                const button = event.target.closest('.btn-action');
                const originalText = button.innerHTML;
                button.disabled = true;
                button.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Processing...';

                // Simulate AJAX call
                setTimeout(() => {
                    button.disabled = false;
                    button.innerHTML = originalText;

                    // Show success message
                    alert('Application status updated successfully!');
                    reviewModal.hide();

                    // Reload page or update UI
                    location.reload();
                }, 1000);
            }
        });
    </script>
@endsection