@extends('layouts.dashboard')

@section('title', 'My Profile - Nepal Oil Nigam')

@section('portal-name', 'Candidate Portal')
@section('brand-icon', 'bi bi-briefcase')
@section('dashboard-route', route('candidate.dashboard'))
@section('user-name', Auth::guard('candidate')->user()->name)
{{-- @section('user-role', 'Job Seeker') --}}
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
    <a href="{{ route('candidate.applications.index') }}" class="sidebar-menu-item">
        <i class="bi bi-file-earmark-text"></i>
        <span>My Applications</span>
    </a>
    <a href="#" class="sidebar-menu-item">
        <i class="bi bi-bookmark"></i>
        <span>Saved Vacancies</span>
    </a>
    <a href="{{ route('candidate.profile.show') }}" class="sidebar-menu-item active">
        <i class="bi bi-person"></i>
        <span>My Profile</span>
    </a>
    <a href="{{ route('candidate.settings.index') }}" class="sidebar-menu-item">
        <i class="bi bi-gear"></i>
        <span>Settings</span>
    </a>
@endsection

@section('custom-styles')
    <style>
        body {
            background: #f5f7fa;
        }

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

        .stats-section {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
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

        .stat-box.blue .stat-number { color: #667eea; }
        .stat-box.orange .stat-number { color: #f6ad55; }
        .stat-box.green .stat-number { color: #48bb78; }
        .stat-box.red .stat-number { color: #f56565; }
        .stat-box.purple .stat-number { color: #9f7aea; }

        .stat-label {
            font-size: 14px;
            color: #718096;
            font-weight: 600;
        }

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
            margin: 0;
        }

        .card-title i {
            color: #667eea;
            font-size: 22px;
        }

        .card-body {
            padding: 0;
        }

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

        .btn-edit {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 12px 24px;
            background: #667eea;
            color: white;
            border-radius: 6px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.2s;
        }

        .btn-edit:hover {
            background: #5a67d8;
            color: white;
            transform: translateY(-1px);
        }

        .recent-apps {
            padding: 24px;
        }

        .app-item {
            padding: 16px;
            background: #f7fafc;
            border-radius: 8px;
            margin-bottom: 12px;
        }

        .app-item:last-child {
            margin-bottom: 0;
        }

        .app-title {
            font-size: 16px;
            font-weight: 700;
            color: #1a202c;
            margin-bottom: 8px;
        }

        .app-meta {
            font-size: 14px;
            color: #718096;
        }

        @media (max-width: 992px) {
            .profile-info-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .stats-section {
                grid-template-columns: repeat(2, 1fr);
            }

            .content-row {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 768px) {
            .profile-top {
                flex-direction: column;
                text-align: center;
            }

            .profile-info-grid {
                grid-template-columns: 1fr;
            }

            .stats-section {
                grid-template-columns: 1fr;
            }
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid">
        <!-- Alerts -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i>
                <strong>Success!</strong> {{ session('success') }}
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

                    <a href="{{ route('candidate.profile.edit') }}" class="btn-edit">
                        <i class="bi bi-pencil-square"></i>
                        Edit Profile
                    </a>
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
                        <div class="info-label">Member Since</div>
                        <div class="info-value">{{ $candidate->created_at->format('M Y') }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Application Statistics -->
        <div class="stats-section">
            <div class="stat-box blue">
                <div class="stat-number">{{ $applicationStats['total'] }}</div>
                <div class="stat-label">Total Applications</div>
            </div>

            <div class="stat-box orange">
                <div class="stat-number">{{ $applicationStats['pending'] }}</div>
                <div class="stat-label">Pending</div>
            </div>

            <div class="stat-box purple">
                <div class="stat-number">{{ $applicationStats['under_review'] }}</div>
                <div class="stat-label">Under Review</div>
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
                            <td class="value">{{ $candidate->email }}</td>
                        </tr>
                        <tr>
                            <td class="label">Mobile</td>
                            <td class="value">{{ $candidate->mobile_number }}</td>
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

        <!-- Recent Applications -->
        @if($recentApplications->count() > 0)
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="bi bi-clock-history"></i>
                    Recent Applications
                </h3>
            </div>
            <div class="recent-apps">
                @foreach($recentApplications as $application)
                <div class="app-item">
                    <div class="app-title">{{ $application->jobPosting->title }}</div>
                    <div class="app-meta">
                        Applied: {{ $application->created_at->format('M d, Y') }} •
                        Status: <strong>{{ ucfirst(str_replace('_', ' ', $application->status)) }}</strong>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
@endsection