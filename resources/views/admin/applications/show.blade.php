@extends('layouts.dashboard')

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
    <a href="{{ route('admin.jobs.create') }}" class="sidebar-menu-item">
        <i class="bi bi-briefcase"></i>
        <span>Post Vacancy</span>
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
        :root {
            --primary: #6366f1;
            --primary-dark: #4f46e5;
            --success: #10b981;
            --warning: #f59e0b;
            --info: #3b82f6;
            --danger: #ef4444;
            --gray-50: #f9fafb;
            --gray-100: #f3f4f6;
            --gray-200: #e5e7eb;
            --gray-500: #64748b;
            --gray-600: #475569;
            --gray-700: #334155;
            --gray-900: #0f172a;
            --white: #ffffff;
            --border: 1px solid #e5e7eb;
            --radius: 12px;
            --shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
        }

        /* Page Header */
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
        }

        .header-left {
            flex: 1;
        }

        .back-link {
            font-size: 14px;
            color: var(--gray-600);
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            margin-bottom: 8px;
        }

        .back-link:hover {
            color: var(--primary);
        }

        .page-title {
            font-size: 28px;
            font-weight: 700;
            color: var(--gray-900);
            margin: 0;
        }

        .header-actions {
            display: flex;
            gap: 12px;
        }

        .btn {
            padding: 10px 20px;
            font-size: 14px;
            font-weight: 500;
            border-radius: 8px;
            border: none;
            cursor: pointer;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
        }

        .btn-primary {
            background: var(--primary);
            color: var(--white);
        }

        .btn-primary:hover {
            background: var(--primary-dark);
            color: var(--white);
        }

        .btn-success {
            background: var(--success);
            color: var(--white);
        }

        .btn-success:hover {
            background: #059669;
            color: var(--white);
        }

        .btn-danger {
            background: var(--danger);
            color: var(--white);
        }

        .btn-danger:hover {
            background: #dc2626;
            color: var(--white);
        }

        .btn-secondary {
            background: var(--white);
            color: var(--gray-700);
            border: var(--border);
        }

        .btn-secondary:hover {
            background: var(--gray-50);
        }

        /* Layout */
        .content-layout {
            display: grid;
            grid-template-columns: 1fr 360px;
            gap: 24px;
        }

        /* Card */
        .card {
            background: var(--white);
            border: var(--border);
            border-radius: var(--radius);
            margin-bottom: 24px;
            overflow: hidden;
        }

        .card-header {
            padding: 20px 24px;
            border-bottom: var(--border);
            background: var(--gray-50);
        }

        .card-title {
            font-size: 18px;
            font-weight: 600;
            color: var(--gray-900);
            margin: 0;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .card-body {
            padding: 24px;
        }

        /* Status Badge */
        .status-badge {
            padding: 8px 16px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            text-transform: capitalize;
            display: inline-block;
        }

        .status-pending {
            background: #fef3c7;
            color: #92400e;
        }

        .status-under_review {
            background: #dbeafe;
            color: #1e40af;
        }

        .status-shortlisted {
            background: #d1fae5;
            color: #065f46;
        }

        .status-rejected {
            background: #fee2e2;
            color: #991b1b;
        }

        /* Candidate Profile */
        .candidate-profile {
            display: flex;
            align-items: center;
            gap: 20px;
            padding: 24px;
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: var(--white);
            border-radius: var(--radius);
            margin-bottom: 24px;
        }

        .candidate-avatar-large {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 32px;
            font-weight: 700;
            border: 4px solid rgba(255, 255, 255, 0.3);
        }

        .candidate-details h2 {
            font-size: 24px;
            font-weight: 700;
            margin: 0 0 6px 0;
        }

        .candidate-details p {
            font-size: 14px;
            opacity: 0.9;
            margin: 0;
        }

        /* Info Grid */
        .info-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
        }

        .info-item {
            padding: 16px;
            background: var(--gray-50);
            border-radius: 8px;
        }

        .info-label {
            font-size: 12px;
            font-weight: 600;
            color: var(--gray-600);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 6px;
        }

        .info-value {
            font-size: 15px;
            font-weight: 600;
            color: var(--gray-900);
        }

        /* Section */
        .section {
            margin-bottom: 24px;
        }

        .section-title {
            font-size: 16px;
            font-weight: 600;
            color: var(--gray-900);
            margin: 0 0 12px 0;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .section-content {
            font-size: 14px;
            color: var(--gray-700);
            line-height: 1.6;
        }

        /* Documents List */
        .documents-list {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .document-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 12px 16px;
            background: var(--gray-50);
            border-radius: 8px;
            border: var(--border);
        }

        .document-info {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .document-icon {
            width: 40px;
            height: 40px;
            border-radius: 8px;
            background: var(--white);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            color: var(--primary);
        }

        .document-details h4 {
            font-size: 14px;
            font-weight: 600;
            color: var(--gray-900);
            margin: 0 0 2px 0;
        }

        .document-details p {
            font-size: 12px;
            color: var(--gray-500);
            margin: 0;
        }

        .document-actions a {
            padding: 6px 12px;
            font-size: 13px;
            color: var(--primary);
            text-decoration: none;
            border: var(--border);
            border-radius: 6px;
            transition: all 0.2s ease;
        }

        .document-actions a:hover {
            background: var(--gray-50);
        }

        /* Timeline */
        .timeline {
            position: relative;
            padding-left: 30px;
        }

        .timeline::before {
            content: '';
            position: absolute;
            left: 8px;
            top: 0;
            bottom: 0;
            width: 2px;
            background: var(--gray-200);
        }

        .timeline-item {
            position: relative;
            padding-bottom: 24px;
        }

        .timeline-item:last-child {
            padding-bottom: 0;
        }

        .timeline-dot {
            position: absolute;
            left: -26px;
            top: 4px;
            width: 16px;
            height: 16px;
            border-radius: 50%;
            background: var(--white);
            border: 3px solid var(--primary);
        }

        .timeline-content {
            background: var(--gray-50);
            padding: 12px 16px;
            border-radius: 8px;
        }

        .timeline-title {
            font-size: 14px;
            font-weight: 600;
            color: var(--gray-900);
            margin: 0 0 4px 0;
        }

        .timeline-text {
            font-size: 13px;
            color: var(--gray-600);
            margin: 0 0 6px 0;
        }

        .timeline-date {
            font-size: 12px;
            color: var(--gray-500);
        }

        /* Form Group */
        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            font-size: 14px;
            font-weight: 600;
            color: var(--gray-700);
            margin-bottom: 8px;
        }

        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 10px 12px;
            border: var(--border);
            border-radius: 8px;
            font-size: 14px;
        }

        .form-group select:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
        }

        /* Alert */
        .alert {
            padding: 16px 20px;
            border-radius: 8px;
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .alert-success {
            background: #d1fae5;
            color: #065f46;
            border: 1px solid #a7f3d0;
        }

        .alert-warning {
            background: #fef3c7;
            color: #92400e;
            border: 1px solid #fde68a;
        }

        /* Responsive */
        @media (max-width: 1200px) {
            .content-layout {
                grid-template-columns: 1fr;
            }

            .info-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 768px) {
            .page-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 16px;
            }

            .header-actions {
                width: 100%;
            }

            .header-actions .btn {
                flex: 1;
                justify-content: center;
            }

            .candidate-profile {
                flex-direction: column;
                text-align: center;
            }
        }
    </style>
@endsection

@section('content')
    <!-- Page Header -->
    <div class="page-header">
        <div class="header-left">
            <a href="{{ route('admin.applications.index') }}" class="back-link">
                <i class="bi bi-arrow-left"></i>
                Back to Applications
            </a>
            <h1 class="page-title">Application Details</h1>
        </div>
        <div class="header-actions">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#statusModal">
                <i class="bi bi-pencil"></i>
                Update Status
            </button>
            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#assignModal">
                <i class="bi bi-person-plus"></i>
                Assign Reviewer
            </button>
            <button type="button" class="btn btn-danger" onclick="confirmDelete()">
                <i class="bi bi-trash"></i>
                Delete
            </button>
        </div>
    </div>

    <!-- Success Message -->
    @if(session('success'))
        <div class="alert alert-success">
            <i class="bi bi-check-circle-fill"></i>
            {{ session('success') }}
        </div>
    @endif

    <!-- Candidate Profile Banner -->
    <div class="candidate-profile">
        <div class="candidate-avatar-large">
            {{ strtoupper(substr($application->candidate->user->name ?? 'U', 0, 1)) }}
        </div>
        <div class="candidate-details">
            <h2>{{ $application->candidate->user->name ?? 'Unknown Candidate' }}</h2>
            <p>
                <i class="bi bi-envelope me-2"></i>
                {{ $application->candidate->user->email ?? 'N/A' }}
            </p>
            <p>
                <i class="bi bi-telephone me-2"></i>
                {{ $application->candidate->phone ?? 'N/A' }}
            </p>
        </div>
        <div class="ms-auto">
            <span class="status-badge status-{{ $application->status }}">
                {{ ucfirst(str_replace('_', ' ', $application->status)) }}
            </span>
        </div>
    </div>

    <!-- Content Layout -->
    <div class="content-layout">
        <!-- Main Content -->
        <div>
            <!-- Job Information -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="bi bi-briefcase-fill text-primary"></i>
                        Job Information
                    </h3>
                </div>
                <div class="card-body">
                    <div class="info-grid">
                        <div class="info-item">
                            <div class="info-label">Job Title</div>
                            <div class="info-value">{{ $application->jobPosting->title }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Advertisement No.</div>
                            <div class="info-value">{{ $application->jobPosting->advertisement_no }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Department</div>
                            <div class="info-value">{{ $application->jobPosting->department }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Location</div>
                            <div class="info-value">{{ $application->jobPosting->location }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Candidate Information -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="bi bi-person-fill text-info"></i>
                        Candidate Information
                    </h3>
                </div>
                <div class="card-body">
                    <div class="info-grid">
                        <div class="info-item">
                            <div class="info-label">Full Name</div>
                            <div class="info-value">{{ $application->candidate->user->name ?? 'N/A' }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Email</div>
                            <div class="info-value">{{ $application->candidate->user->email ?? 'N/A' }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Phone</div>
                            <div class="info-value">{{ $application->candidate->phone ?? 'N/A' }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Address</div>
                            <div class="info-value">{{ $application->candidate->address ?? 'N/A' }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Date of Birth</div>
                            <div class="info-value">
                                {{ $application->candidate->date_of_birth ? \Carbon\Carbon::parse($application->candidate->date_of_birth)->format('M d, Y') : 'N/A' }}
                            </div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Education Level</div>
                            <div class="info-value">{{ $application->candidate->education_level ?? 'N/A' }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Cover Letter -->
            @if($application->cover_letter)
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="bi bi-file-text-fill text-success"></i>
                            Cover Letter
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="section-content">
                            {{ $application->cover_letter }}
                        </div>
                    </div>
                </div>
            @endif

            <!-- Documents -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="bi bi-paperclip text-warning"></i>
                        Uploaded Documents
                    </h3>
                </div>
                <div class="card-body">
                    @if($application->documents && $application->documents->count() > 0)
                        <div class="documents-list">
                            @foreach($application->documents as $document)
                                <div class="document-item">
                                    <div class="document-info">
                                        <div class="document-icon">
                                            <i class="bi bi-file-earmark-pdf"></i>
                                        </div>
                                        <div class="document-details">
                                            <h4>{{ $document->document_type }}</h4>
                                            <p>{{ $document->file_name }} • {{ $document->file_size }}</p>
                                        </div>
                                    </div>
                                    <div class="document-actions">
                                        <a href="{{ Storage::url($document->file_path) }}" target="_blank">
                                            <i class="bi bi-download me-1"></i>
                                            Download
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted text-center py-4">
                            <i class="bi bi-inbox fs-2 d-block mb-2"></i>
                            No documents uploaded
                        </p>
                    @endif
                </div>
            </div>

            <!-- Admin Notes -->
            @if($application->admin_notes)
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="bi bi-sticky-fill text-info"></i>
                            Admin Notes
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="section-content">
                            {{ $application->admin_notes }}
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div>
            <!-- Application Status -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="bi bi-info-circle-fill"></i>
                        Application Status
                    </h3>
                </div>
                <div class="card-body">
                    <div class="section">
                        <div class="section-title">Current Status</div>
                        <span class="status-badge status-{{ $application->status }}">
                            {{ ucfirst(str_replace('_', ' ', $application->status)) }}
                        </span>
                    </div>

                    <div class="section">
                        <div class="section-title">Assigned Reviewer</div>
                        <div class="section-content">
                            @if($application->reviewer)
                                <strong>{{ $application->reviewer->name }}</strong><br>
                                <small class="text-muted">{{ $application->reviewer->email }}</small>
                            @else
                                <span class="text-muted">Not assigned yet</span>
                            @endif
                        </div>
                    </div>

                    <div class="section">
                        <div class="section-title">Application Date</div>
                        <div class="section-content">
                            {{ $application->created_at->format('F d, Y') }}<br>
                            <small class="text-muted">{{ $application->created_at->diffForHumans() }}</small>
                        </div>
                    </div>

                    <div class="section">
                        <div class="section-title">Last Updated</div>
                        <div class="section-content">
                            {{ $application->updated_at->format('F d, Y') }}<br>
                            <small class="text-muted">{{ $application->updated_at->diffForHumans() }}</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="bi bi-lightning-fill"></i>
                        Quick Actions
                    </h3>
                </div>
                <div class="card-body">
                    <button type="button" class="btn btn-secondary w-100 mb-2" data-bs-toggle="modal"
                        data-bs-target="#statusModal">
                        <i class="bi bi-pencil"></i>
                        Change Status
                    </button>
                    <button type="button" class="btn btn-secondary w-100 mb-2" data-bs-toggle="modal"
                        data-bs-target="#assignModal">
                        <i class="bi bi-person-plus"></i>
                        Assign Reviewer
                    </button>
                    <button type="button" class="btn btn-secondary w-100" onclick="alert('Email feature coming soon!')">
                        <i class="bi bi-envelope"></i>
                        Send Email
                    </button>
                </div>
            </div>

            <!-- Activity Timeline -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="bi bi-clock-history"></i>
                        Activity Timeline
                    </h3>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        <div class="timeline-item">
                            <div class="timeline-dot"></div>
                            <div class="timeline-content">
                                <div class="timeline-title">Application Submitted</div>
                                <div class="timeline-text">Candidate submitted the application</div>
                                <div class="timeline-date">{{ $application->created_at->format('M d, Y - h:i A') }}</div>
                            </div>
                        </div>

                        @if($application->reviewer)
                            <div class="timeline-item">
                                <div class="timeline-dot"></div>
                                <div class="timeline-content">
                                    <div class="timeline-title">Reviewer Assigned</div>
                                    <div class="timeline-text">Assigned to {{ $application->reviewer->name }}</div>
                                    <div class="timeline-date">{{ $application->updated_at->format('M d, Y - h:i A') }}</div>
                                </div>
                            </div>
                        @endif

                        <div class="timeline-item">
                            <div class="timeline-dot"></div>
                            <div class="timeline-content">
                                <div class="timeline-title">Status:
                                    {{ ucfirst(str_replace('_', ' ', $application->status)) }}</div>
                                <div class="timeline-text">Application status updated</div>
                                <div class="timeline-date">{{ $application->updated_at->format('M d, Y - h:i A') }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Update Status Modal -->
    <div class="modal fade" id="statusModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('admin.applications.updateStatus', $application->id) }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Update Application Status</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Select Status</label>
                            <select name="status" class="form-control" required>
                                @foreach($statuses as $status)
                                    <option value="{{ $status }}" {{ $application->status == $status ? 'selected' : '' }}>
                                        {{ ucfirst(str_replace('_', ' ', $status)) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Notes (Optional)</label>
                            <textarea name="notes" class="form-control" rows="4"
                                placeholder="Add any notes or comments...">{{ $application->admin_notes }}</textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Update Status</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Assign Reviewer Modal -->
    <div class="modal fade" id="assignModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('admin.applications.assignReviewer', $application->id) }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Assign Reviewer</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Select Reviewer</label>
                            <select name="reviewer_id" class="form-control" required>
                                <option value="">-- Select Reviewer --</option>
                                @foreach($reviewers as $reviewer)
                                    <option value="{{ $reviewer->id }}" {{ $application->reviewer_id == $reviewer->id ? 'selected' : '' }}>
                                        {{ $reviewer->name }} - {{ $reviewer->email }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="alert alert-warning">
                            <i class="bi bi-info-circle"></i>
                            The application status will be automatically changed to "Under Review" after assigning a
                            reviewer.
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success">Assign Reviewer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Form -->
    <form id="deleteForm" action="{{ route('admin.applications.destroy', $application->id) }}" method="POST"
        style="display: none;">
        @csrf
        @method('DELETE')
    </form>
@endsection

@section('scripts')
    <script>
        function confirmDelete() {
            if (confirm('Are you sure you want to delete this application? This action cannot be undone.')) {
                document.getElementById('deleteForm').submit();
            }
        }
    </script>
@endsection