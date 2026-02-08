@extends('layouts.dashboard')

@section('title', 'Edit HR Administrator')

@php
    // Detect which guard is authenticated
    $isAdmin = Auth::guard('admin')->check();
    $isHRAdmin = Auth::guard('hr_administrator')->check();
    $currentUser = $isAdmin ? Auth::guard('admin')->user() : Auth::guard('hr_administrator')->user();
    
    // Set portal configuration based on user type
    $portalName = $isAdmin ? 'Admin Portal' : 'HR Administrator Portal';
    $brandIcon = $isAdmin ? 'bi bi-shield-check' : 'bi bi-person-badge';
    $userRole = $isAdmin ? 'System Administrator' : 'HR Administrator';
    $dashboardRoute = $isAdmin ? route('admin.dashboard') : route('hr-administrator.dashboard');
    $logoutRoute = $isAdmin ? route('admin.logout') : route('hr-administrator.logout');
@endphp

@section('portal-name', $portalName)
@section('brand-icon', $brandIcon)
@section('dashboard-route', $dashboardRoute)
@section('user-name', $currentUser->name ?? 'Guest')
@section('user-role', $userRole)
@section('user-initial', $currentUser ? strtoupper(substr($currentUser->name, 0, 1)) : 'G')
@section('logout-route', $logoutRoute)

@section('sidebar-menu')
    @if($isAdmin)
        {{-- Super Admin Sidebar --}}
        <a href="{{ route('admin.dashboard') }}" class="sidebar-menu-item">
            <i class="bi bi-speedometer2"></i>
            <span>Dashboard</span>
        </a>
        <a href="{{ route('admin.jobs.index') }}" class="sidebar-menu-item">
            <i class="bi bi-briefcase"></i>
            <span>Vacancies</span>
        </a>
        <a href="{{ route('admin.applications.index') }}" class="sidebar-menu-item">
            <i class="bi bi-file-earmark-text"></i>
            <span>Applications</span>
        </a>
        <a href="{{ route('admin.candidates.index') }}" class="sidebar-menu-item">
            <i class="bi bi-people"></i>
            <span>Candidates</span>
        </a>
        <a href="{{ route('admin.hr-administrators.index') }}" class="sidebar-menu-item active">
            <i class="bi bi-person-badge"></i>
            <span>HR Administrators</span>
        </a>
        <a href="{{ route('admin.reviewers.index') }}" class="sidebar-menu-item">
            <i class="bi bi-person-check"></i>
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
    @else
        {{-- HR Administrator Sidebar --}}
        <a href="{{ route('hr-administrator.dashboard') }}" class="sidebar-menu-item">
            <i class="bi bi-speedometer2"></i>
            <span>Dashboard</span>
        </a>
        <a href="{{ route('hr-administrator.jobs.index') }}" class="sidebar-menu-item">
            <i class="bi bi-briefcase"></i>
            <span>Vacancies</span>
        </a>
        <a href="{{ route('hr-administrator.applications.index') }}" class="sidebar-menu-item">
            <i class="bi bi-file-earmark-text"></i>
            <span>Applications</span>
        </a>
        <a href="{{ route('hr-administrator.candidates.index') }}" class="sidebar-menu-item">
            <i class="bi bi-people"></i>
            <span>Candidates</span>
        </a>
        <a href="{{ route('hr-administrator.reviewers.index') }}" class="sidebar-menu-item">
            <i class="bi bi-person-check"></i>
            <span>Reviewers</span>
        </a>
        <a href="#" class="sidebar-menu-item">
            <i class="bi bi-bar-chart"></i>
            <span>Reports</span>
        </a>
        <a href="{{ route('hr-administrator.profile.show') }}" class="sidebar-menu-item">
            <i class="bi bi-gear"></i>
            <span>Settings</span>
        </a>
    @endif
@endsection

@section('content')
    <style>
        /* Photo Upload Styles */
        .photo-upload-container {
            background: white;
            border: 2px dashed #d1d5db;
            border-radius: 12px;
            padding: 2rem;
            text-align: center;
            transition: all 0.3s;
            cursor: pointer;
            position: relative;
        }

        .photo-upload-container:hover {
            border-color: #3b82f6;
            background: #f8fafc;
        }

        .photo-upload-container.dragover {
            border-color: #3b82f6;
            background: #eff6ff;
            transform: scale(1.02);
        }

        .photo-preview-wrapper {
            display: none;
            margin-bottom: 1rem;
        }

        .photo-preview-wrapper.active {
            display: block;
        }

        .photo-preview {
            width: 120px;
            height: 120px;
            border-radius: 12px;
            object-fit: cover;
            border: 3px solid #e5e7eb;
            margin: 0 auto;
            display: block;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .upload-icon {
            width: 64px;
            height: 64px;
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            color: white;
            font-size: 1.75rem;
        }

        .upload-text {
            font-size: 1rem;
            font-weight: 600;
            color: #1e3a8a;
            margin-bottom: 0.5rem;
        }

        .upload-subtext {
            font-size: 0.875rem;
            color: #6b7280;
            margin-bottom: 1rem;
        }

        .upload-button {
            display: inline-block;
            padding: 0.625rem 1.5rem;
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            color: white;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.875rem;
            transition: all 0.2s;
            border: none;
        }

        .upload-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.4);
        }

        .remove-photo-btn {
            display: none;
            margin-top: 1rem;
            padding: 0.5rem 1rem;
            background: #ef4444;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 0.875rem;
            cursor: pointer;
            transition: all 0.2s;
        }

        .remove-photo-btn.active {
            display: inline-block;
        }

        .remove-photo-btn:hover {
            background: #dc2626;
        }

        /* Form Section Styles */
        .form-section {
            background: white;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            padding: 2rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        }

        .section-header {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid #f3f4f6;
        }

        .section-icon {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.25rem;
        }

        .section-title {
            font-size: 1.125rem;
            font-weight: 700;
            color: #1e3a8a;
            margin: 0;
        }

        .form-label {
            font-weight: 600;
            color: #374151;
            margin-bottom: 0.5rem;
            font-size: 0.9375rem;
        }

        .required-star {
            color: #ef4444;
            margin-left: 2px;
        }

        .form-control,
        .form-select {
            border: 1px solid #d1d5db;
            border-radius: 8px;
            padding: 0.75rem 1rem;
            font-size: 0.9375rem;
            transition: all 0.2s;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
            outline: none;
        }

        .input-icon {
            background: #f3f4f6;
            border: 1px solid #d1d5db;
            border-right: none;
        }

        .helper-text {
            display: block;
            margin-top: 0.375rem;
            font-size: 0.8125rem;
            color: #6b7280;
        }

        .helper-text i {
            font-size: 0.75rem;
        }

        /* Password Strength */
        .password-strength {
            margin-top: 0.75rem;
            display: none;
        }

        .password-strength.active {
            display: block;
        }

        .strength-bar {
            height: 6px;
            border-radius: 3px;
            background: #e5e7eb;
            overflow: hidden;
            margin-top: 0.5rem;
        }

        .strength-fill {
            height: 100%;
            transition: all 0.3s;
            border-radius: 3px;
        }

        /* Info Box */
        .info-box {
            background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
            border: 1px solid #fcd34d;
            border-radius: 12px;
            padding: 1.5rem;
        }

        .info-box-title {
            font-weight: 700;
            color: #92400e;
            margin-bottom: 0.75rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .info-list {
            margin: 0;
            padding-left: 0;
            list-style: none;
        }

        .info-list li {
            color: #92400e;
            margin-bottom: 0.5rem;
            font-size: 0.9375rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        /* Buttons */
        .btn-custom {
            padding: 0.75rem 2rem;
            font-weight: 600;
            border-radius: 8px;
            font-size: 0.9375rem;
            transition: all 0.2s;
            border: none;
        }

        .btn-primary-custom {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            color: white;
        }

        .btn-primary-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.4);
            color: white;
        }

        .btn-secondary-custom {
            background: #f3f4f6;
            color: #374151;
        }

        .btn-secondary-custom:hover {
            background: #e5e7eb;
        }

        .alert-info-custom {
            background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
            border: 1px solid #93c5fd;
            border-radius: 10px;
            padding: 1rem;
            color: #1e40af;
        }
    </style>

    <div class="container-fluid px-4 py-4">

        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="mb-3">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ $dashboardRoute }}" style="color: #3b82f6;">Dashboard</a>
                </li>
                @if($isAdmin)
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.hr-administrators.index') }}" style="color: #3b82f6;">HR Administrators</a>
                    </li>
                @endif
                <li class="breadcrumb-item active">Edit Administrator</li>
            </ol>
        </nav>

        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold mb-2" style="color: #1e3a8a;">
                    <i class="bi bi-pencil-square me-2"></i>Edit HR Administrator
                </h2>
                <p class="text-muted mb-0">Update administrator information and settings</p>
            </div>
            @if($isAdmin)
                <a href="{{ route('admin.hr-administrators.show', $hrAdministrator->id) }}" class="btn btn-outline-primary">
                    <i class="bi bi-eye me-2"></i>View Profile
                </a>
            @endif
        </div>

        <form action="{{ $isAdmin ? route('admin.hr-administrators.update', $hrAdministrator->id) : route('hr-administrator.profile.update') }}" method="POST"
            enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="row g-4">
                <!-- Main Form Column -->
                <div class="col-lg-8">

                    <!-- Personal Information -->
                    <div class="form-section">
                        <div class="section-header">
                            <div class="section-icon">
                                <i class="bi bi-person-circle"></i>
                            </div>
                            <h3 class="section-title">Personal Information</h3>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">
                                    Full Name<span class="required-star">*</span>
                                </label>
                                <input type="text" name="name" id="name"
                                    class="form-control @error('name') is-invalid @enderror"
                                    value="{{ old('name', $hrAdministrator->name) }}" placeholder="Enter full name"
                                    required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Phone Number</label>
                                <div class="input-group">
                                    <span class="input-group-text input-icon">
                                        <i class="bi bi-telephone"></i>
                                    </span>
                                    <input type="text" name="phone"
                                        class="form-control @error('phone') is-invalid @enderror"
                                        value="{{ old('phone', $hrAdministrator->phone) }}" placeholder="+977-98********">
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Account Credentials -->
                    <div class="form-section">
                        <div class="section-header">
                            <div class="section-icon">
                                <i class="bi bi-shield-lock"></i>
                            </div>
                            <h3 class="section-title">Account Credentials</h3>
                        </div>

                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label">
                                    Email Address<span class="required-star">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text input-icon">
                                        <i class="bi bi-envelope"></i>
                                    </span>
                                    <input type="email" name="email"
                                        class="form-control @error('email') is-invalid @enderror"
                                        value="{{ old('email', $hrAdministrator->email) }}" placeholder="admin@noc.gov.np"
                                        required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <small class="helper-text">
                                    <i class="bi bi-info-circle"></i> This email will be used for login credentials
                                </small>
                            </div>
                        </div>
                    </div>

                    <!-- Change Password -->
                    <div class="form-section">
                        <div class="section-header">
                            <div class="section-icon">
                                <i class="bi bi-key"></i>
                            </div>
                            <h3 class="section-title">Change Password (Optional)</h3>
                        </div>

                        <div class="alert-info-custom mb-3">
                            <i class="bi bi-info-circle me-2"></i>
                            <small>Leave password fields empty if you don't want to change the password</small>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">New Password</label>
                                <div class="input-group">
                                    <span class="input-group-text input-icon">
                                        <i class="bi bi-lock"></i>
                                    </span>
                                    <input type="password" name="password" id="password"
                                        class="form-control @error('password') is-invalid @enderror">
                                    <button class="btn btn-outline-secondary" type="button"
                                        onclick="togglePasswordVisibility('password', 'passwordIcon')">
                                        <i class="bi bi-eye" id="passwordIcon"></i>
                                    </button>
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <small class="helper-text">
                                    <i class="bi bi-shield-check"></i> Minimum 8 characters with letters and numbers
                                </small>

                                <!-- Password Strength Indicator -->
                                <div class="password-strength" id="passwordStrength">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <small class="text-muted">Password Strength:</small>
                                        <small class="fw-bold" id="strengthText"></small>
                                    </div>
                                    <div class="strength-bar">
                                        <div class="strength-fill" id="strengthFill"></div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Confirm New Password</label>
                                <div class="input-group">
                                    <span class="input-group-text input-icon">
                                        <i class="bi bi-lock-fill"></i>
                                    </span>
                                    <input type="password" name="password_confirmation" id="password_confirmation"
                                        class="form-control">
                                    <button class="btn btn-outline-secondary" type="button"
                                        onclick="togglePasswordVisibility('password_confirmation', 'confirmIcon')">
                                        <i class="bi bi-eye" id="confirmIcon"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Account Status - Only visible to Admin -->
                    @if($isAdmin)
                        <div class="form-section">
                            <div class="section-header">
                                <div class="section-icon">
                                    <i class="bi bi-toggle-on"></i>
                                </div>
                                <h3 class="section-title">Account Status</h3>
                            </div>

                            <div class="row g-3">
                                <div class="col-12">
                                    <label class="form-label">
                                        Account Status<span class="required-star">*</span>
                                    </label>
                                    <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                                        <option value="active" {{ old('status', $hrAdministrator->status) == 'active' ? 'selected' : '' }}>
                                            ✓ Active - Can login immediately
                                        </option>
                                        <option value="inactive" {{ old('status', $hrAdministrator->status) == 'inactive' ? 'selected' : '' }}>
                                            ✗ Inactive - Cannot login until activated
                                        </option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    @endif

                </div>

                <!-- Sidebar Column -->
                <div class="col-lg-4">

                    <!-- Profile Photo Upload -->
                    <div class="form-section">
                        <div class="section-header">
                            <div class="section-icon">
                                <i class="bi bi-image"></i>
                            </div>
                            <h3 class="section-title">Profile Photo</h3>
                        </div>

                        <div class="photo-upload-container" id="photoUploadContainer">
                            <input type="file" name="photo" id="photoInput" accept="image/jpeg,image/jpg,image/png"
                                style="display: none;">

                            <!-- Preview Wrapper -->
                            <div class="photo-preview-wrapper {{ $hrAdministrator->photo ? 'active' : '' }}"
                                id="photoPreviewWrapper">
                                <img id="photoPreview" class="photo-preview"
                                    src="{{ $hrAdministrator->photo ? asset('storage/' . $hrAdministrator->photo) : '' }}"
                                    alt="Preview">
                            </div>

                            <!-- Upload Icon & Text -->
                            <div id="uploadPrompt" style="{{ $hrAdministrator->photo ? 'display: none;' : '' }}">
                                <div class="upload-icon">
                                    <i class="bi bi-cloud-arrow-up"></i>
                                </div>
                                <div class="upload-text">Upload Profile Photo</div>
                                <div class="upload-subtext">
                                    Drag and drop or click to browse
                                </div>
                                <button type="button" class="upload-button"
                                    onclick="document.getElementById('photoInput').click()">
                                    <i class="bi bi-folder2-open me-2"></i>Choose File
                                </button>
                            </div>

                            <button type="button" class="remove-photo-btn {{ $hrAdministrator->photo ? 'active' : '' }}"
                                id="removePhotoBtn">
                                <i class="bi bi-trash me-2"></i>Remove Photo
                            </button>
                        </div>

                        <div class="mt-3">
                            <small class="text-muted d-block mb-1">
                                <i class="bi bi-check-circle text-success me-1"></i>
                                Accepted: JPG, PNG
                            </small>
                            <small class="text-muted d-block">
                                <i class="bi bi-check-circle text-success me-1"></i>
                                Max size: 2MB
                            </small>
                        </div>

                        @error('photo')
                            <div class="alert alert-danger mt-3 mb-0">
                                <i class="bi bi-exclamation-triangle me-2"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Account Info -->
                    <div class="info-box">
                        <div class="info-box-title">
                            <i class="bi bi-clock-history"></i>
                            Account Information
                        </div>
                        <ul class="info-list">
                            <li>
                                <i class="bi bi-person-badge"></i>
                                <span><strong>ID:</strong> {{ $hrAdministrator->id }}</span>
                            </li>
                            <li>
                                <i class="bi bi-calendar-plus"></i>
                                <span><strong>Created:</strong> {{ $hrAdministrator->created_at->format('M d, Y') }}</span>
                            </li>
                            <li>
                                <i class="bi bi-calendar-check"></i>
                                <span><strong>Updated:</strong> {{ $hrAdministrator->updated_at->format('M d, Y') }}</span>
                            </li>
                            <li>
                                <i class="bi bi-clock"></i>
                                <span><strong>Member since:</strong>
                                    {{ $hrAdministrator->created_at->diffForHumans() }}</span>
                            </li>
                        </ul>
                    </div>

                </div>
            </div>

            <!-- Form Actions -->
            <div class="d-flex gap-3 justify-content-end mt-4">
                <a href="{{ $isAdmin ? route('admin.hr-administrators.index') : route('hr-administrator.dashboard') }}" class="btn btn-custom btn-secondary-custom">
                    <i class="bi bi-x-circle me-2"></i>Cancel
                </a>
                <button type="submit" class="btn btn-custom btn-primary-custom">
                    <i class="bi bi-check-circle me-2"></i>Update Administrator
                </button>
            </div>
        </form>
    </div>
@endsection

@section('scripts')
    <script>
        // Photo Upload Functionality
        const photoInput = document.getElementById('photoInput');
        const photoUploadContainer = document.getElementById('photoUploadContainer');
        const photoPreviewWrapper = document.getElementById('photoPreviewWrapper');
        const photoPreview = document.getElementById('photoPreview');
        const uploadPrompt = document.getElementById('uploadPrompt');
        const removePhotoBtn = document.getElementById('removePhotoBtn');

        // Click to upload
        photoUploadContainer.addEventListener('click', function (e) {
            if (e.target !== removePhotoBtn && !removePhotoBtn.contains(e.target)) {
                photoInput.click();
            }
        });

        // File input change
        photoInput.addEventListener('change', handleFileSelect);

        // Drag and drop
        photoUploadContainer.addEventListener('dragover', function (e) {
            e.preventDefault();
            photoUploadContainer.classList.add('dragover');
        });

        photoUploadContainer.addEventListener('dragleave', function (e) {
            e.preventDefault();
            photoUploadContainer.classList.remove('dragover');
        });

        photoUploadContainer.addEventListener('drop', function (e) {
            e.preventDefault();
            photoUploadContainer.classList.remove('dragover');

            const files = e.dataTransfer.files;
            if (files.length > 0) {
                photoInput.files = files;
                handleFileSelect();
            }
        });

        function handleFileSelect() {
            const file = photoInput.files[0];

            if (!file) return;

            // Validate file type
            const validTypes = ['image/jpeg', 'image/jpg', 'image/png'];
            if (!validTypes.includes(file.type)) {
                alert('Please select a valid image file (JPG or PNG)');
                photoInput.value = '';
                return;
            }

            // Validate file size (2MB)
            if (file.size > 2 * 1024 * 1024) {
                alert('File size must be less than 2MB');
                photoInput.value = '';
                return;
            }

            // Show preview
            const reader = new FileReader();
            reader.onload = function (e) {
                photoPreview.src = e.target.result;
                photoPreviewWrapper.classList.add('active');
                uploadPrompt.style.display = 'none';
                removePhotoBtn.classList.add('active');
            };
            reader.readAsDataURL(file);
        }

        // Remove photo
        removePhotoBtn.addEventListener('click', function (e) {
            e.stopPropagation();
            photoInput.value = '';
            photoPreview.src = '';
            photoPreviewWrapper.classList.remove('active');
            uploadPrompt.style.display = 'block';
            removePhotoBtn.classList.remove('active');
        });

        // Toggle password visibility
        function togglePasswordVisibility(fieldId, iconId) {
            const field = document.getElementById(fieldId);
            const icon = document.getElementById(iconId);

            if (field.type === 'password') {
                field.type = 'text';
                icon.classList.remove('bi-eye');
                icon.classList.add('bi-eye-slash');
            } else {
                field.type = 'password';
                icon.classList.remove('bi-eye-slash');
                icon.classList.add('bi-eye');
            }
        }

        // Password strength checker
        const passwordInput = document.getElementById('password');
        const passwordStrength = document.getElementById('passwordStrength');
        const strengthText = document.getElementById('strengthText');
        const strengthFill = document.getElementById('strengthFill');

        passwordInput.addEventListener('input', function () {
            const password = this.value;

            if (password.length === 0) {
                passwordStrength.classList.remove('active');
                return;
            }

            passwordStrength.classList.add('active');

            let strength = 0;

            // Length
            if (password.length >= 8) strength += 25;
            if (password.length >= 12) strength += 15;

            // Contains lowercase
            if (/[a-z]/.test(password)) strength += 15;

            // Contains uppercase
            if (/[A-Z]/.test(password)) strength += 15;

            // Contains numbers
            if (/\d/.test(password)) strength += 15;

            // Contains special characters
            if (/[^A-Za-z0-9]/.test(password)) strength += 15;

            // Update UI
            let text = '';
            let color = '';

            if (strength < 40) {
                text = 'Weak';
                color = '#ef4444';
            } else if (strength < 70) {
                text = 'Medium';
                color = '#f59e0b';
            } else {
                text = 'Strong';
                color = '#10b981';
            }

            strengthText.textContent = text;
            strengthText.style.color = color;
            strengthFill.style.width = strength + '%';
            strengthFill.style.background = color;
        });

        // Form validation
        document.querySelector('form').addEventListener('submit', function (e) {
            const password = document.getElementById('password').value;
            const passwordConfirm = document.getElementById('password_confirmation').value;

            // Only validate if password field is filled
            if (password) {
                if (password !== passwordConfirm) {
                    e.preventDefault();
                    alert('Passwords do not match!');
                    return false;
                }

                if (password.length < 8) {
                    e.preventDefault();
                    alert('Password must be at least 8 characters long!');
                    return false;
                }

                if (!/[a-zA-Z]/.test(password) || !/\d/.test(password)) {
                    e.preventDefault();
                    alert('Password must contain both letters and numbers!');
                    return false;
                }
            }
        });
    </script>
@endsection