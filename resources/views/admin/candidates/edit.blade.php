@extends('layouts.dashboard')

@section('title', 'Edit Candidate - Nepal Oil Nigam')

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
        body {
            background: #f5f7fa;
        }

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

        .form-card {
            background: white;
            border: 1px solid #e1e8ed;
            border-radius: 8px;
            overflow: hidden;
            margin-bottom: 24px;
        }

        .form-card-header {
            background: #f7fafc;
            border-bottom: 1px solid #e1e8ed;
            padding: 20px 24px;
        }

        .form-card-title {
            font-size: 18px;
            font-weight: 700;
            color: #1a202c;
            display: flex;
            align-items: center;
            gap: 10px;
            margin: 0;
        }

        .form-card-title i {
            color: #667eea;
            font-size: 22px;
        }

        .form-card-body {
            padding: 32px;
        }

        .form-row {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 24px;
            margin-bottom: 24px;
        }

        .form-group {
            margin-bottom: 0;
        }

        .form-label {
            display: block;
            font-size: 14px;
            font-weight: 600;
            color: #2d3748;
            margin-bottom: 8px;
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

        .form-control:disabled {
            background: #f7fafc;
            color: #a0aec0;
            cursor: not-allowed;
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

        .form-text {
            font-size: 13px;
            color: #718096;
            margin-top: 6px;
        }

        .form-actions {
            display: flex;
            gap: 12px;
            padding-top: 24px;
            border-top: 1px solid #e1e8ed;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 14px 28px;
            border: none;
            border-radius: 6px;
            font-size: 15px;
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

        .btn-secondary {
            background: #e2e8f0;
            color: #4a5568;
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
            .form-row {
                grid-template-columns: 1fr;
            }

            .form-actions {
                flex-direction: column;
            }

            .btn {
                width: 100%;
            }
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid">
        <!-- Back Button -->
        <a href="{{ route('admin.candidates.show', $candidate->id) }}" class="nav-back">
            <i class="bi bi-arrow-left"></i>
            Back to Profile
        </a>

        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-title">Edit Candidate Profile</h1>
            <p class="page-subtitle">Update candidate information and account settings</p>
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

        <!-- Edit Form -->
        <form method="POST" action="{{ route('admin.candidates.update', $candidate->id) }}">
            @csrf
            @method('PUT')

            <!-- Personal Information -->
            <div class="form-card">
                <div class="form-card-header">
                    <h3 class="form-card-title">
                        <i class="bi bi-person-vcard"></i>
                        Personal Information
                    </h3>
                </div>
                <div class="form-card-body">
                    <div class="form-row">
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

                    <div class="form-row">
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

                        <div class="form-group">
                            <label class="form-label">
                                Username <span class="required">*</span>
                            </label>
                            <input type="text" name="username" class="form-control @error('username') is-invalid @enderror"
                                value="{{ old('username', $candidate->username) }}" required>
                            @error('username')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contact Information -->
            <div class="form-card">
                <div class="form-card-header">
                    <h3 class="form-card-title">
                        <i class="bi bi-telephone"></i>
                        Contact Information
                    </h3>
                </div>
                <div class="form-card-body">
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">
                                Email Address <span class="required">*</span>
                            </label>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                value="{{ old('email', $candidate->email) }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label">
                                Mobile Number <span class="required">*</span>
                            </label>
                            <input type="text" name="mobile_number"
                                class="form-control @error('mobile_number') is-invalid @enderror"
                                value="{{ old('mobile_number', $candidate->mobile_number) }}" maxlength="10" required>
                            @error('mobile_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text">10-digit mobile number</small>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">City</label>
                            <input type="text" name="city" class="form-control @error('city') is-invalid @enderror"
                                value="{{ old('city', $candidate->city) }}">
                            @error('city')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label">State/Province</label>
                            <input type="text" name="state" class="form-control @error('state') is-invalid @enderror"
                                value="{{ old('state', $candidate->state) }}">
                            @error('state')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Country</label>
                            <input type="text" name="country" class="form-control @error('country') is-invalid @enderror"
                                value="{{ old('country', $candidate->country) }}">
                            @error('country')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label">Qualification</label>
                            <input type="text" name="qualification"
                                class="form-control @error('qualification') is-invalid @enderror"
                                value="{{ old('qualification', $candidate->qualification) }}">
                            @error('qualification')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Account Settings -->
            <div class="form-card">
                <div class="form-card-header">
                    <h3 class="form-card-title">
                        <i class="bi bi-shield-check"></i>
                        Account Settings
                    </h3>
                </div>
                <div class="form-card-body">
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">
                                Account Status <span class="required">*</span>
                            </label>
                            <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                                <option value="active" {{ old('status', $candidate->status) === 'active' ? 'selected' : '' }}>
                                    Active
                                </option>
                                <option value="inactive" {{ old('status', $candidate->status) === 'inactive' ? 'selected' : '' }}>
                                    Inactive
                                </option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label">Candidate ID</label>
                            <input type="text" class="form-control"
                                value="NOC-{{ str_pad($candidate->id, 6, '0', STR_PAD_LEFT) }}" disabled>
                            <small class="form-text">System generated, cannot be changed</small>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Email Verification Status</label>
                            <input type="text" class="form-control"
                                value="{{ $candidate->email_verified_at ? 'Verified on ' . $candidate->email_verified_at->format('M d, Y h:i A') : 'Not Verified' }}"
                                disabled>
                            <small class="form-text">Email verification cannot be manually changed</small>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Registration Date</label>
                            <input type="text" class="form-control"
                                value="{{ $candidate->created_at->format('F d, Y h:i A') }}" disabled>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="form-card">
                <div class="form-card-body">
                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle"></i>
                            Update Profile
                        </button>
                        <a href="{{ route('admin.candidates.show', $candidate->id) }}" class="btn btn-secondary">
                            <i class="bi bi-x-circle"></i>
                            Cancel
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection