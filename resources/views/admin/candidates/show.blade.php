@extends('layouts.dashboard')

@section('title', 'Candidate Profile - Nepal Oil Nigam')

@section('portal-name', 'Admin Portal')
@section('brand-icon', 'bi bi-shield-lock')
@section('dashboard-route', route('admin.dashboard'))
@section('user-name', Auth::guard('admin')->user()->name)
@section('user-role', 'Administrator')
@section('user-initial', strtoupper(substr(Auth::guard('admin')->user()->name, 0, 1)))
@section('logout-route', route('admin.logout'))

@section('sidebar-menu')
    <a href="{{ route('admin.dashboard') }}" class="sidebar-menu-item">
        <i class="bi bi-speedometer2"></i>
        <span>Dashboard</span>
    </a>
    <a href="{{ route('admin.jobs.index') }}" class="sidebar-menu-item">
        <i class="bi bi-briefcase"></i>
        <span>Job Postings</span>
    </a>
    <a href="{{ route('admin.applications.index') }}" class="sidebar-menu-item">
        <i class="bi bi-file-earmark-text"></i>
        <span>Applications</span>
    </a>
    <a href="{{ route('admin.candidates.index') }}" class="sidebar-menu-item active">
        <i class="bi bi-people"></i>
        <span>Candidates</span>
    </a>
    <a href="{{ route('admin.reviewers.index') }}" class="sidebar-menu-item">
        <i class="bi bi-person-badge"></i>
        <span>Reviewers</span>
    </a>
@endsection

@section('custom-styles')
    <style>
        /* Clean Professional Design */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: #f5f7fa;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            color: #2c3e50;
            line-height: 1.6;
        }

        /* Navigation */
        .nav-back {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 20px;
            background: white;
            border: 1px solid #e1e8ed;
            border-radius: 6px;
            color: #2c3e50;
            text-decoration: none;
            font-weight: 500;
            margin-bottom: 24px;
            transition: all 0.2s;
        }

        .nav-back:hover {
            background: #f8fafc;
            border-color: #cbd5e0;
            color: #2c3e50;
        }

        /* Profile Header */
        .profile-header {
            background: white;
            border: 1px solid #e1e8ed;
            border-radius: 8px;
            padding: 32px;
            margin-bottom: 24px;
        }

        .profile-top {
            display: flex;
            gap: 24px;
            padding-bottom: 24px;
            border-bottom: 2px solid #e1e8ed;
            margin-bottom: 24px;
        }

        .profile-avatar {
            width: 120px;
            height: 120px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 48px;
            font-weight: 700;
            flex-shrink: 0;
        }

        .profile-main {
            flex: 1;
        }

        .profile-name {
            font-size: 32px;
            font-weight: 700;
            color: #1a202c;
            margin-bottom: 12px;
        }

        .profile-badges {
            display: flex;
            gap: 12px;
            margin-bottom: 16px;
        }

        .badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 14px;
            border-radius: 6px;
            font-size: 13px;
            font-weight: 600;
            text-transform: uppercase;
        }

        .badge-active {
            background: #d4edda;
            color: #155724;
        }

        .badge-inactive {
            background: #f8d7da;
            color: #721c24;
        }

        .badge-verified {
            background: #d1ecf1;
            color: #0c5460;
        }

        .badge-pending {
            background: #fff3cd;
            color: #856404;
        }

        .profile-id {
            background: #f8fafc;
            border: 1px solid #e1e8ed;
            border-radius: 8px;
            padding: 16px;
            text-align: center;
            min-width: 160px;
        }

        .profile-id-label {
            font-size: 11px;
            font-weight: 600;
            color: #718096;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 8px;
        }

        .profile-id-value {
            font-size: 24px;
            font-weight: 700;
            color: #667eea;
            font-family: 'Courier New', monospace;
        }

        .profile-info-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 24px;
        }

        .info-item {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .info-icon {
            width: 44px;
            height: 44px;
            background: #f7fafc;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #667eea;
            font-size: 20px;
            flex-shrink: 0;
        }

        .info-content {
            flex: 1;
        }

        .info-label {
            font-size: 12px;
            color: #718096;
            font-weight: 500;
            margin-bottom: 4px;
        }

        .info-value {
            font-size: 15px;
            color: #2d3748;
            font-weight: 600;
        }

        /* Stats Section */
        .stats-section {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            margin-bottom: 24px;
        }

        .stat-box {
            background: white;
            border: 1px solid #e1e8ed;
            border-radius: 8px;
            padding: 24px;
            text-align: center;
        }

        .stat-number {
            font-size: 40px;
            font-weight: 700;
            line-height: 1;
            margin-bottom: 8px;
        }

        .stat-box.blue .stat-number {
            color: #667eea;
        }

        .stat-box.orange .stat-number {
            color: #f6ad55;
        }

        .stat-box.green .stat-number {
            color: #48bb78;
        }

        .stat-box.red .stat-number {
            color: #f56565;
        }

        .stat-label {
            font-size: 14px;
            color: #718096;
            font-weight: 600;
        }

        /* Content Cards */
        .content-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 24px;
            margin-bottom: 24px;
        }

        .card {
            background: white;
            border: 1px solid #e1e8ed;
            border-radius: 8px;
            overflow: hidden;
        }

        .card-header {
            background: #f7fafc;
            border-bottom: 1px solid #e1e8ed;
            padding: 20px 24px;
        }

        .card-title {
            font-size: 18px;
            font-weight: 700;
            color: #1a202c;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .card-title i {
            color: #667eea;
            font-size: 22px;
        }

        .card-body {
            padding: 0;
        }

        /* Table */
        .info-table {
            width: 100%;
        }

        .info-table tr {
            border-bottom: 1px solid #f1f5f9;
        }

        .info-table tr:last-child {
            border-bottom: none;
        }

        .info-table td {
            padding: 16px 24px;
        }

        .info-table .label {
            width: 40%;
            font-size: 13px;
            font-weight: 600;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        .info-table .value {
            font-size: 15px;
            color: #1e293b;
        }

        .info-table .value a {
            color: #667eea;
            text-decoration: none;
        }

        .info-table .value a:hover {
            text-decoration: underline;
        }

        /* Actions */
        .actions-section {
            padding: 24px;
        }

        .actions-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
        }

        .btn {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 14px 24px;
            border: none;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            text-decoration: none;
        }

        .btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .btn-success {
            background: #48bb78;
            color: white;
        }

        .btn-warning {
            background: #f6ad55;
            color: white;
        }

        .btn-primary {
            background: #667eea;
            color: white;
        }

        .btn-danger {
            background: #f56565;
            color: white;
        }

        .btn-secondary {
            background: #e2e8f0;
            color: #4a5568;
        }

        /* Applications Timeline */
        .timeline {
            padding: 24px;
        }

        .timeline-item {
            position: relative;
            padding: 20px;
            margin-bottom: 16px;
            background: #f7fafc;
            border-left: 4px solid #667eea;
            border-radius: 4px;
        }

        .timeline-item.pending {
            border-left-color: #f6ad55;
        }

        .timeline-item.success {
            border-left-color: #48bb78;
        }

        .timeline-item.rejected {
            border-left-color: #f56565;
        }

        .timeline-header {
            display: flex;
            justify-content: space-between;
            align-items: start;
            margin-bottom: 12px;
        }

        .timeline-title {
            font-size: 18px;
            font-weight: 700;
            color: #1a202c;
            margin-bottom: 6px;
        }

        .timeline-meta {
            font-size: 14px;
            color: #718096;
        }

        .timeline-info {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 12px;
            margin: 16px 0;
            padding: 16px;
            background: white;
            border-radius: 6px;
        }

        .timeline-info-item {
            font-size: 14px;
        }

        .timeline-info-label {
            color: #718096;
            font-weight: 500;
            margin-bottom: 4px;
        }

        .timeline-info-value {
            color: #2d3748;
            font-weight: 600;
        }

        .btn-view {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 10px 20px;
            background: #667eea;
            color: white;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.2s;
        }

        .btn-view:hover {
            background: #5a67d8;
            color: white;
            transform: translateY(-1px);
        }

        /* Empty State */
        .empty-state {
            padding: 60px 20px;
            text-align: center;
        }

        .empty-state i {
            font-size: 64px;
            color: #cbd5e0;
            margin-bottom: 16px;
        }

        .empty-state h5 {
            font-size: 18px;
            color: #4a5568;
            margin-bottom: 8px;
        }

        .empty-state p {
            color: #718096;
        }

        /* Responsive */
        @media (max-width: 1200px) {
            .profile-info-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 992px) {
            .stats-section {
                grid-template-columns: repeat(2, 1fr);
            }

            .content-row {
                grid-template-columns: 1fr;
            }

            .profile-top {
                flex-direction: column;
                text-align: center;
            }

            .profile-info-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 768px) {
            .profile-name {
                font-size: 24px;
            }

            .profile-avatar {
                width: 100px;
                height: 100px;
                font-size: 40px;
            }

            .stats-section {
                grid-template-columns: 1fr;
            }

            .actions-grid {
                grid-template-columns: 1fr;
            }

            .timeline-info {
                grid-template-columns: 1fr;
            }
        }

        /* Print */
        @media print {

            .nav-back,
            .actions-section {
                display: none !important;
            }
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid">
        <!-- Back Button -->
        <a href="{{ route('admin.candidates.index') }}" class="nav-back">
            <i class="bi bi-arrow-left"></i>
            Back to Candidates
        </a>

        <!-- Alerts -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i>
                <strong>Success!</strong> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                <strong>Error!</strong> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Profile Header -->
        <div class="profile-header">
            <div class="profile-top">
                <div class="profile-avatar">
                    {{ strtoupper(substr($candidate->first_name, 0, 1)) }}{{ strtoupper(substr($candidate->last_name, 0, 1)) }}
                </div>

                <div class="profile-main">
                    <h1 class="profile-name">{{ $candidate->name }}</h1>

                    <div class="profile-badges">
                        @if($candidate->status === 'active')
                            <span class="badge badge-active">
                                <i class="bi bi-check-circle-fill"></i>
                                Active
                            </span>
                        @else
                            <span class="badge badge-inactive">
                                <i class="bi bi-x-circle-fill"></i>
                                Inactive
                            </span>
                        @endif

                        @if($candidate->email_verified_at)
                            <span class="badge badge-verified">
                                <i class="bi bi-patch-check-fill"></i>
                                Verified
                            </span>
                        @else
                            <span class="badge badge-pending">
                                <i class="bi bi-clock-fill"></i>
                                Unverified
                            </span>
                        @endif
                    </div>
                </div>

                <div class="profile-id">
                    <div class="profile-id-label">Candidate ID</div>
                    <div class="profile-id-value">NOC-{{ str_pad($candidate->id, 6, '0', STR_PAD_LEFT) }}</div>
                </div>
            </div>

            <div class="profile-info-grid">
                <div class="info-item">
                    <div class="info-icon">
                        <i class="bi bi-at"></i>
                    </div>
                    <div class="info-content">
                        <div class="info-label">Username</div>
                        <div class="info-value">{{ $candidate->username }}</div>
                    </div>
                </div>

                <div class="info-item">
                    <div class="info-icon">
                        <i class="bi bi-envelope"></i>
                    </div>
                    <div class="info-content">
                        <div class="info-label">Email</div>
                        <div class="info-value">{{ $candidate->email }}</div>
                    </div>
                </div>

                <div class="info-item">
                    <div class="info-icon">
                        <i class="bi bi-telephone"></i>
                    </div>
                    <div class="info-content">
                        <div class="info-label">Mobile</div>
                        <div class="info-value">{{ $candidate->mobile_number }}</div>
                    </div>
                </div>

                <div class="info-item">
                    <div class="info-icon">
                        <i class="bi bi-calendar"></i>
                    </div>
                    <div class="info-content">
                        <div class="info-label">Registered</div>
                        <div class="info-value">{{ $candidate->created_at->format('M d, Y') }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics -->
        <div class="stats-section">
            <div class="stat-box blue">
                <div class="stat-number">{{ $applicationStats['total'] }}</div>
                <div class="stat-label">Total Applications</div>
            </div>

            <div class="stat-box orange">
                <div class="stat-number">{{ $applicationStats['pending'] + $applicationStats['under_review'] }}</div>
                <div class="stat-label">Pending Review</div>
            </div>

            <div class="stat-box green">
                <div class="stat-number">{{ $applicationStats['shortlisted'] }}</div>
                <div class="stat-label">Shortlisted</div>
            </div>

            <div class="stat-box red">
                <div class="stat-number">{{ $applicationStats['rejected'] }}</div>
                <div class="stat-label">Rejected</div>
            </div>
        </div>

        <!-- Content -->
        <div class="content-row">
            <!-- Personal Information -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="bi bi-person-vcard"></i>
                        Personal Information
                    </h3>
                </div>
                <div class="card-body">
                    <table class="info-table">
                        <tr>
                            <td class="label">First Name</td>
                            <td class="value">{{ $candidate->first_name }}</td>
                        </tr>
                        @if($candidate->middle_name)
                            <tr>
                                <td class="label">Middle Name</td>
                                <td class="value">{{ $candidate->middle_name }}</td>
                            </tr>
                        @endif
                        <tr>
                            <td class="label">Last Name</td>
                            <td class="value">{{ $candidate->last_name }}</td>
                        </tr>
                        <tr>
                            <td class="label">Full Name</td>
                            <td class="value"><strong>{{ $candidate->name }}</strong></td>
                        </tr>
                        <tr>
                            <td class="label">Username</td>
                            <td class="value">{{ $candidate->username }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            <!-- Contact Information -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="bi bi-telephone"></i>
                        Contact Information
                    </h3>
                </div>
                <div class="card-body">
                    <table class="info-table">
                        <tr>
                            <td class="label">Email</td>
                            <td class="value">
                                <a href="mailto:{{ $candidate->email }}">{{ $candidate->email }}</a>
                            </td>
                        </tr>
                        <tr>
                            <td class="label">Mobile</td>
                            <td class="value">
                                <a href="tel:{{ $candidate->mobile_number }}">{{ $candidate->mobile_number }}</a>
                            </td>
                        </tr>
                        @if($candidate->city)
                            <tr>
                                <td class="label">City</td>
                                <td class="value">{{ $candidate->city }}</td>
                            </tr>
                        @endif
                        @if($candidate->state)
                            <tr>
                                <td class="label">State</td>
                                <td class="value">{{ $candidate->state }}</td>
                            </tr>
                        @endif
                        @if($candidate->country)
                            <tr>
                                <td class="label">Country</td>
                                <td class="value">{{ $candidate->country }}</td>
                            </tr>
                        @endif
                    </table>
                </div>
            </div>
        </div>

        <div class="content-row">
            <!-- Account Status -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="bi bi-shield-check"></i>
                        Account Status
                    </h3>
                </div>
                <div class="card-body">
                    <table class="info-table">
                        <tr>
                            <td class="label">Status</td>
                            <td class="value">
                                @if($candidate->status === 'active')
                                    <span class="badge badge-active">
                                        <i class="bi bi-check-circle-fill"></i>
                                        Active
                                    </span>
                                @else
                                    <span class="badge badge-inactive">
                                        <i class="bi bi-x-circle-fill"></i>
                                        Inactive
                                    </span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td class="label">Email Verification</td>
                            <td class="value">
                                @if($candidate->email_verified_at)
                                    <span class="badge badge-verified">
                                        <i class="bi bi-patch-check-fill"></i>
                                        Verified
                                    </span>
                                    <br>
                                    <small
                                        style="color: #718096;">{{ $candidate->email_verified_at->format('F d, Y h:i A') }}</small>
                                @else
                                    <span class="badge badge-pending">
                                        <i class="bi bi-clock-fill"></i>
                                        Not Verified
                                    </span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td class="label">Registered</td>
                            <td class="value">
                                <strong>{{ $candidate->created_at->format('F d, Y') }}</strong>
                                <br>
                                <small style="color: #718096;">{{ $candidate->created_at->diffForHumans() }}</small>
                            </td>
                        </tr>
                        <tr>
                            <td class="label">Last Updated</td>
                            <td class="value">{{ $candidate->updated_at->format('F d, Y h:i A') }}</td>
                        </tr>
                        <tr>
                            <td class="label">Applications</td>
                            <td class="value"><strong>{{ $candidate->applications_count }}</strong> submitted</td>
                        </tr>
                    </table>
                </div>
            </div>

            <!-- Actions -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="bi bi-gear"></i>
                        Administrative Actions
                    </h3>
                </div>
                <div class="actions-section">
                    <div class="actions-grid">
                        <!-- Edit Button -->
                        <a href="{{ route('admin.candidates.edit', $candidate->id) }}" class="btn btn-primary">
                            <i class="bi bi-pencil-square"></i>
                            Edit Profile
                        </a>

                        <!-- Status Toggle -->
                        <form method="POST" action="{{ route('admin.candidates.updateStatus', $candidate->id) }}">
                            @csrf
                            <input type="hidden" name="status"
                                value="{{ $candidate->status === 'active' ? 'inactive' : 'active' }}">
                            <button type="submit"
                                class="btn {{ $candidate->status === 'active' ? 'btn-warning' : 'btn-success' }}">
                                <i class="bi bi-{{ $candidate->status === 'active' ? 'pause-circle' : 'play-circle' }}"></i>
                                {{ $candidate->status === 'active' ? 'Deactivate' : 'Activate' }}
                            </button>
                        </form>

                        <!-- Print Button -->
                        <button onclick="window.print()" class="btn btn-secondary">
                            <i class="bi bi-printer"></i>
                            Print Profile
                        </button>

                        <!-- Delete Button -->
                        <form method="POST" action="{{ route('admin.candidates.destroy', $candidate->id) }}"
                            onsubmit="return confirm('Are you sure you want to delete this candidate? This action cannot be undone.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">
                                <i class="bi bi-trash"></i>
                                Delete Account
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Applications -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="bi bi-clock-history"></i>
                    Application History ({{ $applications->count() }})
                </h3>
            </div>
            <div class="card-body">
                @if($applications->count() > 0)
                    <div class="timeline">
                        @foreach($applications as $application)
                            <div
                                class="timeline-item {{ $application->status === 'shortlisted' ? 'success' : ($application->status === 'rejected' ? 'rejected' : 'pending') }}">
                                <div class="timeline-header">
                                    <div>
                                        <div class="timeline-title">{{ $application->jobPosting->title }}</div>
                                        <div class="timeline-meta">
                                            <i class="bi bi-building"></i> {{ $application->jobPosting->department }}
                                            &nbsp;&nbsp;
                                            <i class="bi bi-geo-alt"></i> {{ $application->jobPosting->location }}
                                        </div>
                                    </div>
                                    @php
                                        $badgeClass = match ($application->status) {
                                            'shortlisted' => 'badge-active',
                                            'rejected' => 'badge-inactive',
                                            'under_review' => 'badge-verified',
                                            default => 'badge-pending'
                                        };
                                    @endphp
                                    <span class="badge {{ $badgeClass }}">
                                        {{ strtoupper(str_replace('_', ' ', $application->status)) }}
                                    </span>
                                </div>

                                <div class="timeline-info">
                                    <div class="timeline-info-item">
                                        <div class="timeline-info-label">Applied</div>
                                        <div class="timeline-info-value">{{ $application->created_at->format('M d, Y') }}</div>
                                    </div>
                                    @if($application->reviewer)
                                        <div class="timeline-info-item">
                                            <div class="timeline-info-label">Reviewer</div>
                                            <div class="timeline-info-value">{{ $application->reviewer->name }}</div>
                                        </div>
                                    @endif
                                </div>

                                <a href="{{ route('admin.applications.show', $application->id) }}" class="btn-view">
                                    <i class="bi bi-eye"></i>
                                    View Application
                                </a>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="empty-state">
                        <i class="bi bi-inbox"></i>
                        <h5>No Applications</h5>
                        <p>This candidate hasn't submitted any applications yet.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection