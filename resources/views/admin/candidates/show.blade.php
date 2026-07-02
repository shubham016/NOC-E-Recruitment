@extends('layouts.dashboard')

@section('title', __('admin.candidate_profile_title'))

@section('portal-name', __('admin.portal_name'))
@section('brand-icon', 'bi bi-shield-check')
@section('dashboard-route', route('admin.dashboard'))
@section('user-name', Auth::guard('admin')->user()->name)
@section('user-role', __('admin.system_administrator'))
@section('user-initial', strtoupper(substr(Auth::guard('admin')->user()->name, 0, 1)))
@section('logout-route', route('admin.logout'))

@section('sidebar-menu')
    @include('admin.partials.sidebar')
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
            background: linear-gradient(135deg, #1a3a6b 0%, #122a52 100%);
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
            color: #1a3a6b;
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
            background: #e8eef6;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #1a3a6b;
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
            color: #1a3a6b;
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
            color: #1a3a6b;
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
            color: #1a3a6b;
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
            background: linear-gradient(135deg, #2a5298 0%, #1a3a6b 100%);
            color: white;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #1f467f 0%, #122a52 100%);
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
            border-left: 4px solid #1a3a6b;
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
            background: #1a3a6b;
            color: white;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.2s;
        }

        .btn-view:hover {
            background: #122a52;
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
            {{ __('admin.back') }}
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
                <div class="profile-avatar" style="overflow: hidden;">
                    @if($candidate->photo)
                        <img src="{{ asset('storage/' . $candidate->photo) }}"
                             alt="{{ $candidate->name }}"
                             style="width: 100%; height: 100%; object-fit: cover;">
                    @else
                        {{ strtoupper(substr($candidate->first_name, 0, 1)) }}{{ strtoupper(substr($candidate->last_name, 0, 1)) }}
                    @endif
                </div>

                <div class="profile-main">
                    <h1 class="profile-name">{{ $candidate->name }}</h1>

                    <div class="profile-badges">
                        @if($candidate->status === 'active')
                            <span class="badge badge-active">
                                <i class="bi bi-check-circle-fill"></i>
                                {{ __('admin.active') }}
                            </span>
                        @else
                            <span class="badge badge-inactive">
                                <i class="bi bi-x-circle-fill"></i>
                                {{ __('admin.inactive') }}
                            </span>
                        @endif
                    </div>
                </div>

                <div class="profile-id">
                    <div class="profile-id-label">{{ __('admin.candidate_id') }}</div>
                    <div class="profile-id-value">{{ str_pad($candidate->id, 6, '0', STR_PAD_LEFT) }}</div>
                </div>
            </div>

            <div class="profile-info-grid">
                <div class="info-item">
                    <div class="info-icon">
                        <i class="bi bi-credit-card-2-front"></i>
                    </div>
                    <div class="info-content">
                        <div class="info-label">{{ __('admin.citizenship_number') }}</div>
                        <div class="info-value">{{ $candidate->username }}</div>
                    </div>
                </div>

                <div class="info-item">
                    <div class="info-icon">
                        <i class="bi bi-envelope"></i>
                    </div>
                    <div class="info-content">
                        <div class="info-label">{{ __('admin.email') }}</div>
                        <div class="info-value">{{ $candidate->email }}</div>
                    </div>
                </div>

                <div class="info-item">
                    <div class="info-icon">
                        <i class="bi bi-telephone"></i>
                    </div>
                    <div class="info-content">
                        <div class="info-label">{{ __('admin.mobile') }}</div>
                        <div class="info-value">{{ $candidate->mobile_number }}</div>
                    </div>
                </div>

                <div class="info-item">
                    <div class="info-icon">
                        <i class="bi bi-calendar"></i>
                    </div>
                    <div class="info-content">
                        <div class="info-label">{{ __('admin.registered') }}</div>
                        <div class="info-value">
                            <div class="nepali-date-bs" data-ad-date="{{ $candidate->created_at->format('Y-m-d') }}">
                                <i class="bi bi-hourglass-split"></i> {{ __('admin.converting') }}
                            </div>
                            <small style="color: #718096;">{{ $candidate->created_at->format('M d, Y') }}</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics -->
        <div class="stats-section">
            <div class="stat-box blue">
                <div class="stat-number">{{ $applicationStats['total'] }}</div>
                <div class="stat-label">{{ __('admin.total_applications') }}</div>
            </div>

            <div class="stat-box orange">
                <div class="stat-number">{{ $applicationStats['pending'] }}</div>
                <div class="stat-label">{{ __('admin.pending_review') }}</div>
            </div>

            <div class="stat-box green">
                <div class="stat-number">{{ $applicationStats['approved'] }}</div>
                <div class="stat-label">{{ __('admin.approved') }}</div>
            </div>

            <div class="stat-box red">
                <div class="stat-number">{{ $applicationStats['rejected'] }}</div>
                <div class="stat-label">{{ __('admin.rejected') }}</div>
            </div>
        </div>

        <!-- Content -->
        <div class="content-row">
            <!-- Personal Information -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="bi bi-person-vcard"></i>
                        {{ __('admin.personal_information') }}
                    </h3>
                </div>
                <div class="card-body">
                    <table class="info-table">
                        <tr>
                            <td class="label">{{ __('admin.first_name') }}</td>
                            <td class="value">{{ $candidate->first_name }}</td>
                        </tr>
                        @if($candidate->middle_name)
                            <tr>
                                <td class="label">{{ __('admin.middle_name') }}</td>
                                <td class="value">{{ $candidate->middle_name }}</td>
                            </tr>
                        @endif
                        <tr>
                            <td class="label">{{ __('admin.last_name') }}</td>
                            <td class="value">{{ $candidate->last_name }}</td>
                        </tr>
                        <tr>
                            <td class="label">{{ __('admin.full_name') }}</td>
                            <td class="value"><strong>{{ $candidate->name }}</strong></td>
                        </tr>
                    </table>
                </div>
            </div>

            <!-- Contact Information -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="bi bi-telephone"></i>
                        {{ __('admin.contact_information') }}
                    </h3>
                </div>
                <div class="card-body">
                    <table class="info-table">
                        <tr>
                            <td class="label">{{ __('admin.email') }}</td>
                            <td class="value">
                                <a href="mailto:{{ $candidate->email }}">{{ $candidate->email }}</a>
                            </td>
                        </tr>
                        <tr>
                            <td class="label">{{ __('admin.mobile') }}</td>
                            <td class="value">
                                <a href="tel:{{ $candidate->mobile_number }}">{{ $candidate->mobile_number }}</a>
                            </td>
                        </tr>
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
                        {{ __('admin.account_status') }}
                    </h3>
                </div>
                <div class="card-body">
                    <table class="info-table">
                        <tr>
                            <td class="label">{{ __('admin.status') }}</td>
                            <td class="value">
                                @if($candidate->status === 'active')
                                    <span class="badge badge-active">
                                        <i class="bi bi-check-circle-fill"></i>
                                        {{ __('admin.active') }}
                                    </span>
                                @else
                                    <span class="badge badge-inactive">
                                        <i class="bi bi-x-circle-fill"></i>
                                        {{ __('admin.inactive') }}
                                    </span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td class="label">{{ __('admin.registered') }}</td>
                            <td class="value">
                                <strong class="nepali-date-bs" data-ad-date="{{ $candidate->created_at->format('Y-m-d') }}">
                                    <i class="bi bi-hourglass-split"></i> {{ __('admin.converting') }}
                                </strong>
                                <br>
                                <small style="color: #718096;">{{ $candidate->created_at->format('F d, Y') }} ({{ $candidate->created_at->diffForHumans() }})</small>
                            </td>
                        </tr>
                        <tr>
                            <td class="label">{{ __('admin.last_updated') }}</td>
                            <td class="value">{{ $candidate->updated_at->format('F d, Y h:i A') }}</td>
                        </tr>
                        <tr>
                            <td class="label">{{ __('admin.total_applications') }}</td>
                            <td class="value"><strong>{{ $candidate->applications_count }}</strong> {{ __('admin.submitted') }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            <!-- Actions -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="bi bi-gear"></i>
                        {{ __('admin.administrative_actions') }}
                    </h3>
                </div>
                <div class="actions-section">
                    <div class="actions-grid">
                        <!-- Edit Button -->
                        <a href="{{ route('admin.candidates.edit', $candidate->id) }}" class="btn btn-primary">
                            <i class="bi bi-pencil-square"></i>
                            {{ __('admin.edit_profile') }}
                        </a>

                        <!-- Status Toggle -->
                        <form method="POST" action="{{ route('admin.candidates.updateStatus', $candidate->id) }}">
                            @csrf
                            <input type="hidden" name="status"
                                value="{{ $candidate->status === 'active' ? 'inactive' : 'active' }}">
                            <button type="submit"
                                class="btn {{ $candidate->status === 'active' ? 'btn-warning' : 'btn-success' }}">
                                <i class="bi bi-{{ $candidate->status === 'active' ? 'pause-circle' : 'play-circle' }}"></i>
                                {{ $candidate->status === 'active' ? __('admin.deactivate') : __('admin.activate') }}
                            </button>
                        </form>

                        <!-- Print Button -->
                        <button onclick="window.print()" class="btn btn-secondary">
                            <i class="bi bi-printer"></i>
                            {{ __('admin.print_profile') }}
                        </button>

                        <!-- Delete Button -->
                        <form method="POST" action="{{ route('admin.candidates.destroy', $candidate->id) }}"
                            onsubmit="return confirm('{{ __('admin.delete_candidate_confirm') }}')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">
                                <i class="bi bi-trash"></i>
                                {{ __('admin.delete_account') }}
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
                    {{ __('admin.application_history') }} ({{ $applications->count() }})
                </h3>
            </div>
            <div class="card-body">
                @if($applications->count() > 0)
                    <div class="timeline">
                        @foreach($applications as $application)
                            <div
                                class="timeline-item {{ $application->status === 'approved' ? 'success' : ($application->status === 'rejected' ? 'rejected' : 'pending') }}">
                                <div class="timeline-header">
                                    <div>
                                        <div class="timeline-title">{{ $application->vacancy->title }}</div>
                                        <div class="timeline-meta">
                                            <i class="bi bi-building"></i> {{ $application->vacancy->department }}
                                            &nbsp;&nbsp;
                                            <i class="bi bi-geo-alt"></i> {{ $application->vacancy->location }}
                                        </div>
                                    </div>
                                    @php
                                        $badgeClass = match ($application->status) {
                                            'approved' => 'badge-active',
                                            'rejected' => 'badge-inactive',
                                            'selected' => 'badge-verified',
                                            default => 'badge-pending'
                                        };
                                    @endphp
                                    <span class="badge {{ $badgeClass }}">
                                        {{ strtoupper(__('admin.' . $application->status)) }}
                                    </span>
                                </div>

                                <div class="timeline-info">
                                    <div class="timeline-info-item">
                                        <div class="timeline-info-label">{{ __('admin.applied') }}</div>
                                        <div class="timeline-info-value">{{ $application->created_at->format('M d, Y') }}</div>
                                    </div>
                                    @if($application->reviewer)
                                        <div class="timeline-info-item">
                                            <div class="timeline-info-label">{{ __('admin.reviewer') }}</div>
                                            <div class="timeline-info-value">{{ $application->reviewer->name }}</div>
                                        </div>
                                    @endif
                                </div>

                                <a href="{{ route('admin.applications.show', $application->id) }}" class="btn-view">
                                    {{ __('admin.view_application') }}
                                </a>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="empty-state">
                        <i class="bi bi-inbox"></i>
                        <h5>{{ __('admin.no_applications') }}</h5>
                        <p>{{ __('admin.candidate_no_apps') }}</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        // Convert English numerals to Nepali numerals
        function englishToNepali(str) {
            if (!str) return str;
            const map = { '0': '०', '1': '१', '2': '२', '3': '३', '4': '४', '5': '५', '6': '६', '7': '७', '8': '८', '9': '९' };
            return str.replace(/[0-9]/g, d => map[d]);
        }

        document.addEventListener('DOMContentLoaded', function () {
            console.log('🔧 Initializing Nepali date conversion...');

            // Wait for converter to be ready
            function waitForConverter() {
                if (!window.nepaliLibrariesReady || typeof window.adToBS !== 'function') {
                    setTimeout(waitForConverter, 100);
                    return;
                }

                console.log('✅ Converter ready, converting dates...');
                convertAllDates();
            }

            function convertAllDates() {
                // Find all elements with Nepali date class
                const dateElements = document.querySelectorAll('.nepali-date-bs');

                console.log(`📅 Found ${dateElements.length} dates to convert`);

                dateElements.forEach((element, index) => {
                    const adDate = element.getAttribute('data-ad-date');

                    if (adDate) {
                        try {
                            // Convert AD to BS (returns English numerals like 2082-11-05)
                            const bsDate = window.adToBS(adDate);

                            if (bsDate) {
                                // Convert to Nepali numerals (२०८२-११-०५)
                                const bsNepali = englishToNepali(bsDate);

                                // Update the element with Nepali numeral date
                                element.innerHTML = `${bsNepali}`;
                                console.log(`✅ Date ${index + 1}: ${adDate} → ${bsDate} → ${bsNepali}`);
                            } else {
                                element.innerHTML = '<i class="bi bi-exclamation-circle"></i> Error';
                                element.style.color = '#f56565';
                            }
                        } catch (error) {
                            console.error(`❌ Error converting date ${adDate}:`, error);
                            element.innerHTML = '<i class="bi bi-x-circle"></i> Error';
                            element.style.color = '#f56565';
                        }
                    }
                });

                console.log('✅ All dates converted successfully!');
            }

            // Start the conversion process
            waitForConverter();
        });
    </script>
@endsection
