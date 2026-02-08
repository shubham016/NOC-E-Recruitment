@extends('layouts.dashboard')

@section('title', 'Create HR Administrator')

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

        /* Password strength */
        .password-strength {
            display: none;
            margin-top: 0.5rem;
        }

        .password-strength.active {
            display: block;
        }

        .strength-bar {
            height: 4px;
            background: #e5e7eb;
            border-radius: 2px;
            overflow: hidden;
        }

        .strength-fill {
            height: 100%;
            width: 0;
            transition: all 0.3s;
        }

        .strength-text {
            font-size: 0.75rem;
            margin-top: 0.25rem;
        }

        /* Info box */
        .info-box {
            background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
            border: 1px solid #bfdbfe;
            border-radius: 10px;
            padding: 1.25rem;
            margin-top: 1.5rem;
        }

        .info-box-title {
            font-weight: 600;
            color: #1e40af;
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .info-box ul {
            margin: 0;
            padding-left: 1.25rem;
        }

        .info-box li {
            color: #1e40af;
            font-size: 0.875rem;
            margin-bottom: 0.25rem;
        }

        /* Custom button styles */
        .btn-custom {
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            border-radius: 8px;
            transition: all 0.2s;
        }

        .btn-primary-custom {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            border: none;
            color: white;
        }

        .btn-primary-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.4);
            color: white;
        }

        .btn-secondary-custom {
            background: #f3f4f6;
            border: 1px solid #d1d5db;
            color: #374151;
        }

        .btn-secondary-custom:hover {
            background: #e5e7eb;
            color: #1f2937;
        }
    </style>

    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1" style="color: #1e3a8a; font-weight: 700;">
                <i class="bi bi-person-plus-fill me-2"></i>Create New HR Administrator
            </h1>
            <p class="text-muted mb-0">Add a new HR Administrator to manage recruitment activities</p>
        </div>
        <a href="{{ $isAdmin ? route('admin.hr-administrators.index') : route('hr-administrator.dashboard') }}" class="btn btn-custom btn-secondary-custom">
            <i class="bi bi-arrow-left me-2"></i>Back
        </a>
    </div>

    <!-- Alert Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle me-2"></i>
            <strong>Please fix the following errors:</strong>
            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Create Form -->
    {{-- Only Admin can create HR Administrators --}}
    @if($isAdmin)
        <form action="{{ route('admin.hr-administrators.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="row">
                <!-- Left Column - Main Form -->
                <div class="col-lg-8">
                    <!-- Personal Information Section -->
                    <div class="form-section">
                        <div class="section-header">
                            <div class="section-icon">
                                <i class="bi bi-person"></i>
                            </div>
                            <h2 class="section-title">Personal Information</h2>
                        </div>

                        <div class="row g-4">
                            <!-- Full Name -->
                            <div class="col-md-6">
                                <label for="name" class="form-label">
                                    Full Name <span class="required-star">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text input-icon">
                                        <i class="bi bi-person"></i>
                                    </span>
                                    <input type="text" 
                                           class="form-control @error('name') is-invalid @enderror" 
                                           id="name" 
                                           name="name" 
                                           value="{{ old('name') }}" 
                                           placeholder="Enter full name"
                                           required>
                                </div>
                                @error('name')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Email -->
                            <div class="col-md-6">
                                <label for="email" class="form-label">
                                    Email Address <span class="required-star">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text input-icon">
                                        <i class="bi bi-envelope"></i>
                                    </span>
                                    <input type="email" 
                                           class="form-control @error('email') is-invalid @enderror" 
                                           id="email" 
                                           name="email" 
                                           value="{{ old('email') }}" 
                                           placeholder="admin@example.com"
                                           required>
                                </div>
                                <small class="helper-text">
                                    <i class="bi bi-info-circle me-1"></i>
                                    This email will be used for login and notifications
                                </small>
                                @error('email')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Phone -->
                            <div class="col-md-6">
                                <label for="phone" class="form-label">Phone Number</label>
                                <div class="input-group">
                                    <span class="input-group-text input-icon">
                                        <i class="bi bi-telephone"></i>
                                    </span>
                                    <input type="text" 
                                           class="form-control @error('phone') is-invalid @enderror" 
                                           id="phone" 
                                           name="phone" 
                                           value="{{ old('phone') }}" 
                                           placeholder="+977-9XXXXXXXXX">
                                </div>
                                @error('phone')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Status -->
                            <div class="col-md-6">
                                <label for="status" class="form-label">
                                    Account Status <span class="required-star">*</span>
                                </label>
                                <select class="form-select @error('status') is-invalid @enderror" 
                                        id="status" 
                                        name="status" 
                                        required>
                                    <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>
                                        Active - Can login immediately
                                    </option>
                                    <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>
                                        Inactive - Cannot login until activated
                                    </option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Security Section -->
                    <div class="form-section">
                        <div class="section-header">
                            <div class="section-icon">
                                <i class="bi bi-shield-lock"></i>
                            </div>
                            <h2 class="section-title">Security Credentials</h2>
                        </div>

                        <div class="row g-4">
                            <!-- Password -->
                            <div class="col-md-6">
                                <label for="password" class="form-label">
                                    Password <span class="required-star">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text input-icon">
                                        <i class="bi bi-lock"></i>
                                    </span>
                                    <input type="password" 
                                           class="form-control @error('password') is-invalid @enderror" 
                                           id="password" 
                                           name="password" 
                                           placeholder="Enter password"
                                           required>
                                    <button class="btn btn-outline-secondary" type="button" 
                                            onclick="togglePasswordVisibility('password', 'passwordIcon')">
                                        <i class="bi bi-eye" id="passwordIcon"></i>
                                    </button>
                                </div>

                                <!-- Password Strength Indicator -->
                                <div class="password-strength" id="passwordStrength">
                                    <div class="strength-bar">
                                        <div class="strength-fill" id="strengthFill"></div>
                                    </div>
                                    <span class="strength-text" id="strengthText"></span>
                                </div>

                                <small class="helper-text">
                                    <i class="bi bi-info-circle me-1"></i>
                                    Minimum 8 characters with letters and numbers
                                </small>
                                @error('password')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Confirm Password -->
                            <div class="col-md-6">
                                <label for="password_confirmation" class="form-label">
                                    Confirm Password <span class="required-star">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text input-icon">
                                        <i class="bi bi-lock-fill"></i>
                                    </span>
                                    <input type="password" 
                                           class="form-control" 
                                           id="password_confirmation" 
                                           name="password_confirmation" 
                                           placeholder="Confirm password"
                                           required>
                                    <button class="btn btn-outline-secondary" type="button" 
                                            onclick="togglePasswordVisibility('password_confirmation', 'confirmIcon')">
                                        <i class="bi bi-eye" id="confirmIcon"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column - Photo & Info -->
                <div class="col-lg-4">
                    <!-- Photo Upload Section -->
                    <div class="form-section">
                        <div class="section-header">
                            <div class="section-icon">
                                <i class="bi bi-camera"></i>
                            </div>
                            <h2 class="section-title">Profile Photo</h2>
                        </div>

                        <div class="photo-upload-container" id="photoUploadContainer">
                            <input type="file" 
                                   id="photoInput" 
                                   name="photo" 
                                   accept="image/jpeg,image/jpg,image/png" 
                                   class="d-none">

                            <div class="photo-preview-wrapper" id="photoPreviewWrapper">
                                <img src="" alt="Preview" class="photo-preview" id="photoPreview">
                            </div>

                            <div id="uploadPrompt">
                                <div class="upload-icon">
                                    <i class="bi bi-cloud-upload"></i>
                                </div>
                                <p class="upload-text">Upload Profile Photo</p>
                                <p class="upload-subtext">Drag and drop or click to browse</p>
                                <span class="upload-button">Choose File</span>
                            </div>

                            <button type="button" class="remove-photo-btn" id="removePhotoBtn">
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

                    <!-- Permissions Info Box -->
                    <div class="info-box">
                        <div class="info-box-title">
                            <i class="bi bi-shield-check"></i>
                            Default Permissions
                        </div>
                        <p class="small mb-2" style="color: #1e40af;">This HR Administrator will have access to:</p>
                        <ul class="mb-0">
                            <li>Create & manage job postings</li>
                            <li>View & process applications</li>
                            <li>Assign reviewers</li>
                            <li>Access candidate profiles</li>
                            <li>Generate reports</li>
                        </ul>
                    </div>

                </div>
            </div>

            <!-- Form Actions -->
            <div class="d-flex gap-3 justify-content-end mt-4">
                <a href="{{ route('admin.hr-administrators.index') }}" class="btn btn-custom btn-secondary-custom">
                    <i class="bi bi-x-circle me-2"></i>Cancel
                </a>
                <button type="submit" class="btn btn-custom btn-primary-custom">
                    <i class="bi bi-check-circle me-2"></i>Create Administrator
                </button>
            </div>
        </form>
    @else
        {{-- HR Administrators cannot create other HR Administrators --}}
        <div class="alert alert-warning">
            <i class="bi bi-exclamation-triangle me-2"></i>
            <strong>Access Denied:</strong> Only System Administrators can create HR Administrator accounts.
        </div>
    @endif
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

        if (photoUploadContainer) {
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
        }

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
        if (removePhotoBtn) {
            removePhotoBtn.addEventListener('click', function (e) {
                e.stopPropagation();
                photoInput.value = '';
                photoPreview.src = '';
                photoPreviewWrapper.classList.remove('active');
                uploadPrompt.style.display = 'block';
                removePhotoBtn.classList.remove('active');
            });
        }

        // Toggle password visibility
        function togglePasswordVisibility(fieldId, iconId) {
            const field = document.getElementById(fieldId);
            const icon = document.getElementById(iconId);

            if (field && icon) {
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
        }

        // Password strength checker
        const passwordInput = document.getElementById('password');
        const passwordStrength = document.getElementById('passwordStrength');
        const strengthText = document.getElementById('strengthText');
        const strengthFill = document.getElementById('strengthFill');

        if (passwordInput) {
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
        }

        // Form validation
        const form = document.querySelector('form');
        if (form) {
            form.addEventListener('submit', function (e) {
                const password = document.getElementById('password').value;
                const passwordConfirm = document.getElementById('password_confirmation').value;

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
            });
        }
    </script>
@endsection