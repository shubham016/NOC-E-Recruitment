@extends('layouts.app')

@section('title', 'Application Details')

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
    <a href="{{ route('admin.jobs.index') }}" class="sidebar-menu-item">
        <i class="bi bi-briefcase"></i>
        <span>Vacancy Postings</span>
    </a>
    <a href="{{ route('admin.applications.index') }}" class="sidebar-menu-item active">
        <i class="bi bi-file-earmark-text"></i>
        <span>Applications</span>
    </a>
    <a href="#" class="sidebar-menu-item">
        <i class="bi bi-people"></i>
        <span>Candidates</span>
    </a>
    <a href="#" class="sidebar-menu-item">
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

@section('custom-styles')
    <style>
        .page-header {
            background: linear-gradient(135deg, #dc2626 0%, #991b1b 100%);
            border-radius: 12px;
            padding: 2rem;
            color: white;
            margin-bottom: 2rem;
            box-shadow: 0 4px 12px rgba(220, 38, 38, 0.3);
        }

        .govt-badge {
            background: rgba(255, 255, 255, 0.2);
            border: 2px solid rgba(255, 255, 255, 0.3);
            padding: 0.5rem 1rem;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            font-weight: 600;
            margin-bottom: 1rem;
        }

        .detail-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            border: 1px solid #e5e7eb;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .detail-header {
            border-bottom: 2px solid #e5e7eb;
            padding-bottom: 1rem;
            margin-bottom: 1.5rem;
            position: relative;
        }

        .detail-header::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 60px;
            height: 2px;
            background: #dc2626;
        }

        .detail-row {
            display: flex;
            padding: 0.75rem 0;
            border-bottom: 1px solid #f3f4f6;
        }

        .detail-row:last-child {
            border-bottom: none;
        }

        .detail-label {
            font-weight: 600;
            color: #6b7280;
            width: 200px;
            flex-shrink: 0;
        }

        .detail-value {
            color: #1f2937;
            font-weight: 500;
            flex: 1;
        }

        .status-badge {
            padding: 0.5rem 1rem;
            border-radius: 6px;
            font-size: 0.875rem;
            font-weight: 600;
            display: inline-block;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .candidate-profile {
            display: flex;
            align-items: center;
            gap: 1.5rem;
            padding: 1.5rem;
            background: linear-gradient(135deg, #fef2f2 0%, #fee2e2 100%);
            border-radius: 12px;
            margin-bottom: 1.5rem;
        }

        .candidate-avatar-large {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: linear-gradient(135deg, #dc2626 0%, #991b1b 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 700;
            font-size: 2rem;
            box-shadow: 0 4px 12px rgba(220, 38, 38, 0.3);
        }

        .candidate-info-large {
            flex: 1;
        }

        .candidate-name-large {
            font-size: 1.5rem;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 0.25rem;
        }

        .candidate-meta {
            display: flex;
            gap: 1.5rem;
            margin-top: 0.5rem;
        }

        .candidate-meta-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: #6b7280;
            font-size: 0.875rem;
        }

        .action-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            border: 1px solid #e5e7eb;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .action-btn {
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            border-radius: 8px;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .action-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .timeline-item {
            position: relative;
            padding-left: 2rem;
            padding-bottom: 1.5rem;
            border-left: 2px solid #e5e7eb;
        }

        .timeline-item:last-child {
            border-left: 2px solid transparent;
        }

        .timeline-dot {
            position: absolute;
            left: -7px;
            top: 0;
            width: 14px;
            height: 14px;
            border-radius: 50%;
            background: #dc2626;
            border: 3px solid white;
            box-shadow: 0 0 0 2px #dc2626;
        }

        .timeline-content {
            background: #f9fafb;
            padding: 1rem;
            border-radius: 8px;
            margin-top: -0.25rem;
        }

        .timeline-time {
            font-size: 0.875rem;
            color: #6b7280;
            margin-bottom: 0.5rem;
        }

        .timeline-title {
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 0.25rem;
        }

        .timeline-description {
            font-size: 0.875rem;
            color: #6b7280;
        }

        .document-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 1rem;
            background: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            margin-bottom: 0.75rem;
            transition: all 0.2s ease;
        }

        .document-item:hover {
            background: #fef2f2;
            border-color: #dc2626;
        }

        .document-info {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .document-icon {
            width: 40px;
            height: 40px;
            background: #dc2626;
            color: white;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
        }

        .score-input-group {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .score-slider {
            flex: 1;
        }

        .score-display {
            font-size: 2rem;
            font-weight: 700;
            color: #dc2626;
            min-width: 70px;
            text-align: center;
        }

        .cover-letter-box {
            background: #f9fafb;
            border-left: 4px solid #dc2626;
            padding: 1.5rem;
            border-radius: 6px;
            white-space: pre-wrap;
            line-height: 1.6;
        }

        .quick-action-buttons {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 0.75rem;
        }

        .stat-box {
            background: #f9fafb;
            padding: 1rem;
            border-radius: 8px;
            text-align: center;
        }

        .stat-value {
            font-size: 1.5rem;
            font-weight: 700;
            color: #dc2626;
        }

        .stat-label {
            font-size: 0.875rem;
            color: #6b7280;
            margin-top: 0.25rem;
        }

        .notes-box {
            background: #fffbeb;
            border: 1px solid #fcd34d;
            border-radius: 8px;
            padding: 1rem;
            margin-top: 1rem;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #dc2626;
            box-shadow: 0 0 0 0.2rem rgba(220, 38, 38, 0.15);
        }
    </style>
@endsection

@section('content')
    <!-- Page Header -->
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-start">
            <div>
                <div class="govt-badge">
                    <i class="bi bi-building-fill"></i>
                    <span>नेपाल सरकार | Government of Nepal</span>
                </div>
                <h3 class="fw-bold mb-2">
                    <i class="bi bi-file-text-fill me-2"></i>Application Details
                </h3>
                <p class="mb-0 opacity-90">Application ID: #{{ $application->id }}</p>
            </div>
            <a href="{{ route('admin.applications.index') }}" class="btn btn-light btn-lg">
                <i class="bi bi-arrow-left me-2"></i>Back to List
            </a>
        </div>
    </div>

    <!-- Success/Error Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Candidate Profile Header -->
    <div class="candidate-profile">
        <div class="candidate-avatar-large">
            {{ strtoupper(substr($application->candidate->user->name ?? 'N', 0, 1)) }}
        </div>
        <div class="candidate-info-large">
            <div class="candidate-name-large">{{ $application->candidate->user->name ?? 'N/A' }}</div>
            <div class="candidate-meta">
                <div class="candidate-meta-item">
                    <i class="bi bi-envelope-fill"></i>
                    <span>{{ $application->candidate->user->email ?? 'N/A' }}</span>
                </div>
                @if($application->candidate->phone)
                    <div class="candidate-meta-item">
                        <i class="bi bi-phone-fill"></i>
                        <span>{{ $application->candidate->phone }}</span>
                    </div>
                @endif
                <div class="candidate-meta-item">
                    <i class="bi bi-calendar-fill"></i>
                    <span>Applied {{ $application->created_at->diffForHumans() }}</span>
                </div>
            </div>
        </div>
        <div>
            <span class="status-badge {{ $application->getStatusBadgeClass() }}">
                {{ $application->getStatusLabel() }}
            </span>
        </div>
    </div>

    <div class="row g-4">
        <!-- Main Content Column -->
        <div class="col-lg-8">
            <!-- Job Details Card -->
            <div class="detail-card">
                <div class="detail-header">
                    <h5 class="fw-bold text-danger mb-0">
                        <i class="bi bi-briefcase-fill me-2"></i>Applied For
                    </h5>
                </div>

                <div class="detail-row">
                    <div class="detail-label">Advertisement No.</div>
                    <div class="detail-value">
                        <strong class="text-danger">{{ $application->jobPosting->advertisement_no }}</strong>
                    </div>
                </div>

                <div class="detail-row">
                    <div class="detail-label">Position / Level</div>
                    <div class="detail-value">{{ $application->jobPosting->position_level }}</div>
                </div>

                <div class="detail-row">
                    <div class="detail-label">Service / Group</div>
                    <div class="detail-value">{{ $application->jobPosting->service_group }}</div>
                </div>

                <div class="detail-row">
                    <div class="detail-label">Category</div>
                    <div class="detail-value">
                        @if($application->jobPosting->category == 'open')
                            <span class="badge bg-success">खुल्ला (Open)</span>
                        @else
                            <span class="badge bg-info">समावेशी (Inclusive) -
                                {{ $application->jobPosting->inclusive_type }}</span>
                        @endif
                    </div>
                </div>

                <div class="detail-row">
                    <div class="detail-label">Number of Posts</div>
                    <div class="detail-value">
                        <strong class="text-danger fs-5">{{ $application->jobPosting->number_of_posts }}</strong>
                    </div>
                </div>

                <div class="detail-row">
                    <div class="detail-label">Application Deadline</div>
                    <div class="detail-value">
                        <i class="bi bi-calendar-check-fill text-danger me-1"></i>
                        {{ $application->jobPosting->deadline->format('F d, Y') }}
                        <small class="text-muted ms-2">({{ $application->jobPosting->deadline->diffForHumans() }})</small>
                    </div>
                </div>
            </div>

            <!-- Cover Letter Card -->
            @if($application->cover_letter)
                <div class="detail-card">
                    <div class="detail-header">
                        <h5 class="fw-bold text-danger mb-0">
                            <i class="bi bi-file-text-fill me-2"></i>Cover Letter
                        </h5>
                    </div>
                    <div class="cover-letter-box">
                        {{ $application->cover_letter }}
                    </div>
                </div>
            @endif

            <!-- Documents Card -->
            <div class="detail-card">
                <div class="detail-header">
                    <h5 class="fw-bold text-danger mb-0">
                        <i class="bi bi-paperclip me-2"></i>Submitted Documents
                    </h5>
                </div>

                @if($application->resume_path)
                    <div class="document-item">
                        <div class="document-info">
                            <div class="document-icon">
                                <i class="bi bi-file-pdf-fill"></i>
                            </div>
                            <div>
                                <div class="fw-bold">Resume / CV</div>
                                <small class="text-muted">Uploaded {{ $application->created_at->format('M d, Y') }}</small>
                            </div>
                        </div>
                        <a href="{{ route('admin.applications.downloadResume', $application->id) }}"
                            class="btn btn-sm btn-outline-danger">
                            <i class="bi bi-download me-1"></i>Download
                        </a>
                    </div>
                @endif

                @if($application->additional_documents && is_array($application->additional_documents))
                    @foreach($application->additional_documents as $doc)
                        <div class="document-item">
                            <div class="document-info">
                                <div class="document-icon">
                                    <i class="bi bi-file-earmark-fill"></i>
                                </div>
                                <div>
                                    <div class="fw-bold">{{ $doc['name'] ?? 'Additional Document' }}</div>
                                    <small class="text-muted">{{ $doc['type'] ?? 'Document' }}</small>
                                </div>
                            </div>
                            <a href="{{ $doc['path'] ?? '#' }}" class="btn btn-sm btn-outline-danger">
                                <i class="bi bi-download me-1"></i>Download
                            </a>
                        </div>
                    @endforeach
                @endif

                @if(!$application->resume_path && (!$application->additional_documents || count($application->additional_documents) == 0))
                    <div class="text-center text-muted py-4">
                        <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                        No documents uploaded
                    </div>
                @endif
            </div>

            <!-- Reviewer Notes Card -->
            @if($application->reviewer_notes)
                <div class="detail-card">
                    <div class="detail-header">
                        <h5 class="fw-bold text-danger mb-0">
                            <i class="bi bi-chat-left-text-fill me-2"></i>Reviewer Notes
                        </h5>
                    </div>
                    <div class="notes-box">
                        <i class="bi bi-info-circle-fill text-warning me-2"></i>
                        {{ $application->reviewer_notes }}
                    </div>
                </div>
            @endif

            <!-- Rejection Reason Card -->
            @if($application->status == 'rejected' && $application->rejection_reason)
                <div class="detail-card">
                    <div class="detail-header">
                        <h5 class="fw-bold text-danger mb-0">
                            <i class="bi bi-x-circle-fill me-2"></i>Rejection Reason
                        </h5>
                    </div>
                    <div class="alert alert-danger mb-0">
                        <strong>Reason:</strong> {{ $application->rejection_reason }}
                        @if($application->rejected_at)
                            <br><small class="text-muted">Rejected on
                                {{ $application->rejected_at->format('M d, Y h:i A') }}</small>
                        @endif
                    </div>
                </div>
            @endif

            <!-- Timeline Card -->
            <div class="detail-card">
                <div class="detail-header">
                    <h5 class="fw-bold text-danger mb-0">
                        <i class="bi bi-clock-history me-2"></i>Application Timeline
                    </h5>
                </div>

                <div class="timeline-item">
                    <div class="timeline-dot"></div>
                    <div class="timeline-content">
                        <div class="timeline-time">{{ $application->created_at->format('M d, Y h:i A') }}</div>
                        <div class="timeline-title">Application Submitted</div>
                        <div class="timeline-description">Candidate submitted the application</div>
                    </div>
                </div>

                @if($application->reviewed_at)
                    <div class="timeline-item">
                        <div class="timeline-dot bg-info"></div>
                        <div class="timeline-content">
                            <div class="timeline-time">{{ $application->reviewed_at->format('M d, Y h:i A') }}</div>
                            <div class="timeline-title">Review Started</div>
                            <div class="timeline-description">
                                Application moved to under review
                                @if($application->reviewer)
                                    by {{ $application->reviewer->name }}
                                @endif
                            </div>
                        </div>
                    </div>
                @endif

                @if($application->shortlisted_at)
                    <div class="timeline-item">
                        <div class="timeline-dot bg-success"></div>
                        <div class="timeline-content">
                            <div class="timeline-time">{{ $application->shortlisted_at->format('M d, Y h:i A') }}</div>
                            <div class="timeline-title">Application Shortlisted</div>
                            <div class="timeline-description">Candidate has been shortlisted for next round</div>
                        </div>
                    </div>
                @endif

                @if($application->rejected_at)
                    <div class="timeline-item">
                        <div class="timeline-dot bg-danger"></div>
                        <div class="timeline-content">
                            <div class="timeline-time">{{ $application->rejected_at->format('M d, Y h:i A') }}</div>
                            <div class="timeline-title">Application Rejected</div>
                            <div class="timeline-description">Application was rejected</div>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Sidebar Column -->
        <div class="col-lg-4">
            <!-- Quick Actions Card -->
            <div class="action-card">
                <div class="detail-header">
                    <h6 class="fw-bold mb-0">
                        <i class="bi bi-lightning-fill text-danger me-2"></i>Quick Actions
                    </h6>
                </div>

                <div class="quick-action-buttons">
                    @if($application->status != 'shortlisted')
                        <form action="{{ route('admin.applications.updateStatus', $application->id) }}" method="POST">
                            @csrf
                            <input type="hidden" name="status" value="shortlisted">
                            <button type="submit" class="btn btn-success w-100 action-btn">
                                <i class="bi bi-check-circle"></i>
                                Shortlist
                            </button>
                        </form>
                    @endif

                    @if($application->status != 'rejected')
                        <button type="button" class="btn btn-danger w-100 action-btn" data-bs-toggle="modal"
                            data-bs-target="#rejectModal">
                            <i class="bi bi-x-circle"></i>
                            Reject
                        </button>
                    @endif

                    @if($application->status != 'under_review')
                        <form action="{{ route('admin.applications.updateStatus', $application->id) }}" method="POST">
                            @csrf
                            <input type="hidden" name="status" value="under_review">
                            <button type="submit" class="btn btn-info w-100 action-btn">
                                <i class="bi bi-eye"></i>
                                Move to Review
                            </button>
                        </form>
                    @endif

                    @if($application->resume_path)
                        <a href="{{ route('admin.applications.downloadResume', $application->id) }}"
                            class="btn btn-outline-danger w-100 action-btn">
                            <i class="bi bi-download"></i>
                            Download Resume
                        </a>
                    @endif
                </div>
            </div>

            <!-- Assign Reviewer Card -->
            <div class="action-card">
                <div class="detail-header">
                    <h6 class="fw-bold mb-0">
                        <i class="bi bi-person-badge text-danger me-2"></i>Assign Reviewer
                    </h6>
                </div>

                @if($application->reviewer)
                    <div class="alert alert-info mb-3">
                        <strong>Current Reviewer:</strong><br>
                        <i class="bi bi-person-fill me-1"></i>{{ $application->reviewer->name }}
                    </div>
                @endif

                <form action="{{ route('admin.applications.assignReviewer', $application->id) }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <select class="form-select" name="reviewer_id" required>
                            <option value="">Select Reviewer</option>
                            @foreach($reviewers as $reviewer)
                                <option value="{{ $reviewer->id }}" {{ $application->reviewer_id == $reviewer->id ? 'selected' : '' }}>
                                    {{ $reviewer->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn btn-danger w-100 action-btn">
                        <i class="bi bi-person-check"></i>
                        Assign Reviewer
                    </button>
                </form>
            </div>

            <!-- Application Score Card -->
            <div class="action-card">
                <div class="detail-header">
                    <h6 class="fw-bold mb-0">
                        <i class="bi bi-star-fill text-danger me-2"></i>Application Score
                    </h6>
                </div>

                <form action="{{ route('admin.applications.updateStatus', $application->id) }}" method="POST">
                    @csrf
                    <input type="hidden" name="status" value="{{ $application->status }}">

                    <div class="score-input-group mb-3">
                        <input type="range" class="form-range score-slider" name="application_score" id="scoreRange" min="0"
                            max="100" step="1"
                            value="{{ old('application_score', $application->application_score ?? 50) }}">
                        <div class="score-display" id="scoreDisplay">
                            {{ old('application_score', $application->application_score ?? 50) }}%
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold">Reviewer Notes</label>
                        <textarea class="form-control" name="reviewer_notes" rows="4"
                            placeholder="Add notes about this application...">{{ old('reviewer_notes', $application->reviewer_notes) }}</textarea>
                    </div>

                    <button type="submit" class="btn btn-danger w-100 action-btn">
                        <i class="bi bi-save"></i>
                        Save Score & Notes
                    </button>
                </form>
            </div>

            <!-- Application Stats Card -->
            <div class="action-card">
                <div class="detail-header">
                    <h6 class="fw-bold mb-0">
                        <i class="bi bi-graph-up text-danger me-2"></i>Statistics
                    </h6>
                </div>

                <div class="row g-3">
                    <div class="col-6">
                        <div class="stat-box">
                            <div class="stat-value">
                                {{ $application->created_at->diffInDays(now()) }}
                            </div>
                            <div class="stat-label">Days Since Applied</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="stat-box">
                            <div class="stat-value">
                                {{ $application->application_score ?? '-' }}
                            </div>
                            <div class="stat-label">Score</div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="stat-box">
                            <div class="stat-value">{{ ucfirst(str_replace('_', ' ', $application->status)) }}</div>
                            <div class="stat-label">Current Status</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Reject Modal -->
    <div class="modal fade" id="rejectModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('admin.applications.updateStatus', $application->id) }}" method="POST">
                    @csrf
                    <input type="hidden" name="status" value="rejected">

                    <div class="modal-header">
                        <h5 class="modal-title">
                            <i class="bi bi-x-circle-fill text-danger me-2"></i>Reject Application
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-warning">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i>
                            You are about to reject this application. Please provide a reason.
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Rejection Reason <span class="text-danger">*</span></label>
                            <textarea class="form-control" name="rejection_reason" rows="4"
                                placeholder="Please provide a clear reason for rejection..." required></textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Additional Notes (Optional)</label>
                            <textarea class="form-control" name="reviewer_notes" rows="3"
                                placeholder="Any additional notes..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger">
                            <i class="bi bi-x-circle me-1"></i>Reject Application
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        // Score slider
        const scoreRange = document.getElementById('scoreRange');
        const scoreDisplay = document.getElementById('scoreDisplay');

        if (scoreRange && scoreDisplay) {
            scoreRange.addEventListener('input', function () {
                scoreDisplay.textContent = this.value + '%';

                // Color coding
                const score = parseInt(this.value);
                if (score >= 70) {
                    scoreDisplay.style.color = '#059669'; // green
                } else if (score >= 50) {
                    scoreDisplay.style.color = '#d97706'; // yellow
                } else {
                    scoreDisplay.style.color = '#dc2626'; // red
                }
            });
        }
    </script>
@endsection