@extends('layouts.dashboard')

@section('title', 'Settings - Nepal Oil Nigam')

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
    <a href="{{ route('candidate.applications.index') }}" class="sidebar-menu-item">
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
    <a href="{{ route('candidate.settings.index') }}" class="sidebar-menu-item active">
        <i class="bi bi-gear"></i>
        <span>Settings</span>
    </a>
@endsection

@section('custom-styles')
    <style>
        body {
            background: #f5f7fa;
        }

        .page-header {
            background: white;
            border: 1px solid #e1e8ed;
            border-radius: 8px;
            padding: 24px 32px;
            margin-bottom: 24px;
        }

        .page-title {
            font-size: 28px;
            font-weight: 700;
            color: #1a202c;
            margin: 0 0 8px 0;
        }

        .page-subtitle {
            color: #718096;
            margin: 0;
        }

        /* Tabs */
        .settings-tabs {
            display: flex;
            gap: 8px;
            background: white;
            border: 1px solid #e1e8ed;
            border-radius: 8px;
            padding: 8px;
            margin-bottom: 24px;
            overflow-x: auto;
        }

        .tab-btn {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 12px 20px;
            background: transparent;
            border: none;
            border-radius: 6px;
            color: #4a5568;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            white-space: nowrap;
        }

        .tab-btn:hover {
            background: #f7fafc;
            color: #2d3748;
        }

        .tab-btn.active {
            background: #667eea;
            color: white;
        }

        .tab-btn i {
            font-size: 18px;
        }

        /* Tab Content */
        .tab-content {
            display: none;
        }

        .tab-content.active {
            display: block;
        }

        /* Cards */
        .settings-card {
            background: white;
            border: 1px solid #e1e8ed;
            border-radius: 8px;
            margin-bottom: 24px;
            overflow: hidden;
        }

        .settings-card-header {
            background: #f7fafc;
            border-bottom: 1px solid #e1e8ed;
            padding: 20px 24px;
        }

        .settings-card-title {
            font-size: 18px;
            font-weight: 700;
            color: #1a202c;
            display: flex;
            align-items: center;
            gap: 10px;
            margin: 0 0 4px 0;
        }

        .settings-card-title i {
            color: #667eea;
            font-size: 22px;
        }

        .settings-card-description {
            color: #718096;
            font-size: 14px;
            margin: 0;
        }

        .settings-card-body {
            padding: 24px;
        }

        /* Form Elements */
        .form-group {
            margin-bottom: 24px;
        }

        .form-label {
            display: block;
            font-size: 14px;
            font-weight: 600;
            color: #2d3748;
            margin-bottom: 8px;
        }

        .form-help {
            font-size: 13px;
            color: #718096;
            margin-top: 6px;
        }

        .required {
            color: #f56565;
        }

        .form-control {
            width: 100%;
            padding: 12px 16px;
            border: 1px solid #e1e8ed;
            border-radius: 6px;
            font-size: 15px;
            color: #2d3748;
            transition: all 0.2s;
        }

        .form-control:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .form-select {
            width: 100%;
            padding: 12px 16px;
            border: 1px solid #e1e8ed;
            border-radius: 6px;
            font-size: 15px;
            color: #2d3748;
            background: white;
            cursor: pointer;
        }

        /* Toggle Switch */
        .toggle-switch {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 16px 0;
            border-bottom: 1px solid #f1f5f9;
        }

        .toggle-switch:last-child {
            border-bottom: none;
        }

        .toggle-info {
            flex: 1;
        }

        .toggle-label {
            font-weight: 600;
            color: #2d3748;
            margin-bottom: 4px;
        }

        .toggle-description {
            font-size: 13px;
            color: #718096;
        }

        .switch {
            position: relative;
            display: inline-block;
            width: 52px;
            height: 28px;
        }

        .switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #cbd5e0;
            transition: 0.3s;
            border-radius: 28px;
        }

        .slider:before {
            position: absolute;
            content: "";
            height: 20px;
            width: 20px;
            left: 4px;
            bottom: 4px;
            background-color: white;
            transition: 0.3s;
            border-radius: 50%;
        }

        input:checked + .slider {
            background-color: #667eea;
        }

        input:checked + .slider:before {
            transform: translateX(24px);
        }

        /* Buttons */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 12px 24px;
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

        /* Alert Box */
        .alert-box {
            background: #fff5f5;
            border: 1px solid #feb2b2;
            border-left: 4px solid #f56565;
            border-radius: 6px;
            padding: 16px;
            margin-bottom: 24px;
        }

        .alert-box-title {
            font-weight: 700;
            color: #c53030;
            margin-bottom: 8px;
        }

        .alert-box-text {
            color: #742a2a;
            font-size: 14px;
            margin: 0;
        }

        .invalid-feedback {
            display: block;
            font-size: 13px;
            color: #f56565;
            margin-top: 6px;
        }

        .is-invalid {
            border-color: #f56565;
        }

        @media (max-width: 768px) {
            .settings-tabs {
                overflow-x: scroll;
            }

            .tab-btn {
                font-size: 13px;
                padding: 10px 16px;
            }
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-title">Settings</h1>
            <p class="page-subtitle">Manage your account settings and preferences</p>
        </div>

        <!-- Alerts -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i>
                <strong>Success!</strong> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                <strong>Error!</strong> Please fix the following errors:
                <ul class="mb-0 mt-2">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Tabs -->
        <div class="settings-tabs">
            <button class="tab-btn active" data-tab="account">
                <i class="bi bi-person-circle"></i>
                Account
            </button>
            <button class="tab-btn" data-tab="security">
                <i class="bi bi-shield-lock"></i>
                Security
            </button>
            <button class="tab-btn" data-tab="notifications">
                <i class="bi bi-bell"></i>
                Notifications
            </button>
            <button class="tab-btn" data-tab="privacy">
                <i class="bi bi-eye"></i>
                Privacy
            </button>
            <button class="tab-btn" data-tab="danger">
                <i class="bi bi-exclamation-triangle"></i>
                Danger Zone
            </button>
        </div>

        <!-- Account Tab -->
        <div class="tab-content active" id="account">
            <form method="POST" action="{{ route('candidate.settings.account.update') }}">
                @csrf
                @method('PUT')

                <div class="settings-card">
                    <div class="settings-card-header">
                        <h3 class="settings-card-title">
                            <i class="bi bi-person-vcard"></i>
                            Account Information
                        </h3>
                        <p class="settings-card-description">Update your personal account information</p>
                    </div>
                    <div class="settings-card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">
                                        First Name <span class="required">*</span>
                                    </label>
                                    <input type="text" name="first_name" 
                                           class="form-control @error('first_name') is-invalid @enderror"
                                           value="{{ old('first_name', $candidate->first_name) }}" required>
                                    @error('first_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Middle Name</label>
                                    <input type="text" name="middle_name" 
                                           class="form-control @error('middle_name') is-invalid @enderror"
                                           value="{{ old('middle_name', $candidate->middle_name) }}">
                                    @error('middle_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">
                                        Last Name <span class="required">*</span>
                                    </label>
                                    <input type="text" name="last_name" 
                                           class="form-control @error('last_name') is-invalid @enderror"
                                           value="{{ old('last_name', $candidate->last_name) }}" required>
                                    @error('last_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">
                                        Email Address <span class="required">*</span>
                                    </label>
                                    <input type="email" name="email" 
                                           class="form-control @error('email') is-invalid @enderror"
                                           value="{{ old('email', $candidate->email) }}" required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">
                                        Mobile Number <span class="required">*</span>
                                    </label>
                                    <input type="text" name="mobile_number" 
                                           class="form-control @error('mobile_number') is-invalid @enderror"
                                           value="{{ old('mobile_number', $candidate->mobile_number) }}"
                                           maxlength="10" required>
                                    @error('mobile_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-help">10-digit mobile number</small>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Username</label>
                                    <input type="text" class="form-control" 
                                           value="{{ $candidate->username }}" disabled>
                                    <small class="form-help">Username cannot be changed</small>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle"></i>
                            Save Changes
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Security Tab -->
        <div class="tab-content" id="security">
            <form method="POST" action="{{ route('candidate.settings.password.update') }}">
                @csrf
                @method('PUT')

                <div class="settings-card">
                    <div class="settings-card-header">
                        <h3 class="settings-card-title">
                            <i class="bi bi-shield-lock"></i>
                            Change Password
                        </h3>
                        <p class="settings-card-description">Update your password to keep your account secure</p>
                    </div>
                    <div class="settings-card-body">
                        <div class="form-group">
                            <label class="form-label">
                                Current Password <span class="required">*</span>
                            </label>
                            <input type="password" name="current_password" 
                                   class="form-control @error('current_password') is-invalid @enderror" required>
                            @error('current_password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">
                                        New Password <span class="required">*</span>
                                    </label>
                                    <input type="password" name="new_password" 
                                           class="form-control @error('new_password') is-invalid @enderror"
                                           minlength="8" required>
                                    @error('new_password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-help">Minimum 8 characters</small>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">
                                        Confirm New Password <span class="required">*</span>
                                    </label>
                                    <input type="password" name="new_password_confirmation" 
                                           class="form-control" minlength="8" required>
                                    <small class="form-help">Re-enter your new password</small>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-shield-check"></i>
                            Update Password
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Notifications Tab -->
        <div class="tab-content" id="notifications">
            <form method="POST" action="{{ route('candidate.settings.notifications.update') }}">
                @csrf
                @method('PUT')

                <div class="settings-card">
                    <div class="settings-card-header">
                        <h3 class="settings-card-title">
                            <i class="bi bi-bell"></i>
                            Email Notifications
                        </h3>
                        <p class="settings-card-description">Choose what email notifications you want to receive</p>
                    </div>
                    <div class="settings-card-body">
                        @php
                            $notificationSettings = json_decode($candidate->notification_settings ?? '{}', true);
                        @endphp

                        <div class="toggle-switch">
                            <div class="toggle-info">
                                <div class="toggle-label">Job Alerts</div>
                                <div class="toggle-description">Receive emails about new job openings matching your profile</div>
                            </div>
                            <label class="switch">
                                <input type="checkbox" name="email_job_alerts" 
                                       {{ ($notificationSettings['email_job_alerts'] ?? true) ? 'checked' : '' }}>
                                <span class="slider"></span>
                            </label>
                        </div>

                        <div class="toggle-switch">
                            <div class="toggle-info">
                                <div class="toggle-label">Application Updates</div>
                                <div class="toggle-description">Get notified when your application status changes</div>
                            </div>
                            <label class="switch">
                                <input type="checkbox" name="email_application_updates" 
                                       {{ ($notificationSettings['email_application_updates'] ?? true) ? 'checked' : '' }}>
                                <span class="slider"></span>
                            </label>
                        </div>

                        <div class="toggle-switch">
                            <div class="toggle-info">
                                <div class="toggle-label">Interview Reminders</div>
                                <div class="toggle-description">Receive reminders about upcoming interviews</div>
                            </div>
                            <label class="switch">
                                <input type="checkbox" name="email_interview_reminders" 
                                       {{ ($notificationSettings['email_interview_reminders'] ?? true) ? 'checked' : '' }}>
                                <span class="slider"></span>
                            </label>
                        </div>

                        <div class="toggle-switch">
                            <div class="toggle-info">
                                <div class="toggle-label">Marketing Emails</div>
                                <div class="toggle-description">Receive tips, news, and updates from Nepal Oil Nigam</div>
                            </div>
                            <label class="switch">
                                <input type="checkbox" name="email_marketing" 
                                       {{ ($notificationSettings['email_marketing'] ?? false) ? 'checked' : '' }}>
                                <span class="slider"></span>
                            </label>
                        </div>

                        <button type="submit" class="btn btn-primary mt-3">
                            <i class="bi bi-check-circle"></i>
                            Save Preferences
                        </button>
                    </div>
                </div>

                <div class="settings-card">
                    <div class="settings-card-header">
                        <h3 class="settings-card-title">
                            <i class="bi bi-phone"></i>
                            SMS Notifications
                        </h3>
                        <p class="settings-card-description">Manage SMS notification preferences</p>
                    </div>
                    <div class="settings-card-body">
                        <div class="toggle-switch">
                            <div class="toggle-info">
                                <div class="toggle-label">SMS Notifications</div>
                                <div class="toggle-description">Receive important updates via SMS</div>
                            </div>
                            <label class="switch">
                                <input type="checkbox" name="sms_notifications" 
                                       {{ ($notificationSettings['sms_notifications'] ?? false) ? 'checked' : '' }}>
                                <span class="slider"></span>
                            </label>
                        </div>

                        <button type="submit" class="btn btn-primary mt-3">
                            <i class="bi bi-check-circle"></i>
                            Save Preferences
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Privacy Tab -->
        <div class="tab-content" id="privacy">
            <form method="POST" action="{{ route('candidate.settings.privacy.update') }}">
                @csrf
                @method('PUT')

                <div class="settings-card">
                    <div class="settings-card-header">
                        <h3 class="settings-card-title">
                            <i class="bi bi-eye"></i>
                            Privacy Settings
                        </h3>
                        <p class="settings-card-description">Control who can see your information</p>
                    </div>
                    <div class="settings-card-body">
                        @php
                            $privacySettings = json_decode($candidate->privacy_settings ?? '{}', true);
                        @endphp

                        <div class="form-group">
                            <label class="form-label">Profile Visibility</label>
                            <select name="profile_visibility" class="form-select">
                                <option value="public" {{ ($privacySettings['profile_visibility'] ?? 'public') === 'public' ? 'selected' : '' }}>
                                    Public - Anyone can view your profile
                                </option>
                                <option value="recruiters" {{ ($privacySettings['profile_visibility'] ?? 'public') === 'recruiters' ? 'selected' : '' }}>
                                    Recruiters Only - Only registered recruiters can view
                                </option>
                                <option value="private" {{ ($privacySettings['profile_visibility'] ?? 'public') === 'private' ? 'selected' : '' }}>
                                    Private - Only you can view your profile
                                </option>
                            </select>
                            <small class="form-help">Choose who can see your profile information</small>
                        </div>

                        <div class="toggle-switch">
                            <div class="toggle-info">
                                <div class="toggle-label">Show Email Address</div>
                                <div class="toggle-description">Allow recruiters to see your email address</div>
                            </div>
                            <label class="switch">
                                <input type="checkbox" name="show_email" 
                                       {{ ($privacySettings['show_email'] ?? true) ? 'checked' : '' }}>
                                <span class="slider"></span>
                            </label>
                        </div>

                        <div class="toggle-switch">
                            <div class="toggle-info">
                                <div class="toggle-label">Show Phone Number</div>
                                <div class="toggle-description">Allow recruiters to see your phone number</div>
                            </div>
                            <label class="switch">
                                <input type="checkbox" name="show_phone" 
                                       {{ ($privacySettings['show_phone'] ?? true) ? 'checked' : '' }}>
                                <span class="slider"></span>
                            </label>
                        </div>

                        <div class="toggle-switch">
                            <div class="toggle-info">
                                <div class="toggle-label">Searchable Profile</div>
                                <div class="toggle-description">Allow your profile to appear in recruiter searches</div>
                            </div>
                            <label class="switch">
                                <input type="checkbox" name="allow_search" 
                                       {{ ($privacySettings['allow_search'] ?? true) ? 'checked' : '' }}>
                                <span class="slider"></span>
                            </label>
                        </div>

                        <button type="submit" class="btn btn-primary mt-3">
                            <i class="bi bi-check-circle"></i>
                            Save Settings
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Danger Zone Tab -->
        <div class="tab-content" id="danger">
            <div class="settings-card">
                <div class="settings-card-header">
                    <h3 class="settings-card-title">
                        <i class="bi bi-exclamation-triangle"></i>
                        Delete Account
                    </h3>
                    <p class="settings-card-description">Permanently delete your account and all associated data</p>
                </div>
                <div class="settings-card-body">
                    <div class="alert-box">
                        <div class="alert-box-title">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i>
                            Warning: This action cannot be undone!
                        </div>
                        <p class="alert-box-text">
                            Deleting your account will permanently remove all your data including:
                        </p>
                        <ul class="alert-box-text">
                            <li>Your profile information</li>
                            <li>All job applications</li>
                            <li>Saved jobs and preferences</li>
                            <li>Communication history</li>
                        </ul>
                    </div>

                    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteAccountModal">
                        <i class="bi bi-trash"></i>
                        Delete My Account
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Account Modal -->
    <div class="modal fade" id="deleteAccountModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirm Account Deletion</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="{{ route('candidate.settings.account.delete') }}">
                    @csrf
                    @method('DELETE')
                    <div class="modal-body">
                        <p><strong>This action cannot be undone.</strong> All your data will be permanently deleted.</p>
                        
                        <div class="form-group">
                            <label class="form-label">
                                Enter your password to confirm <span class="required">*</span>
                            </label>
                            <input type="password" name="password" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label class="form-label">
                                Type <strong>DELETE</strong> to confirm <span class="required">*</span>
                            </label>
                            <input type="text" name="confirmation" class="form-control" required>
                            <small class="form-help">Type the word DELETE in capital letters</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger">Delete My Account</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        // Tab Switching
        document.querySelectorAll('.tab-btn').forEach(button => {
            button.addEventListener('click', () => {
                // Remove active class from all tabs and buttons
                document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));
                document.querySelectorAll('.tab-content').forEach(content => content.classList.remove('active'));

                // Add active class to clicked button and corresponding content
                button.classList.add('active');
                const tabId = button.getAttribute('data-tab');
                document.getElementById(tabId).classList.add('active');
            });
        });
    </script>
@endsection