@extends('layouts.dashboard')

@section('title', 'Settings')
@section('portal-name', 'Admin Portal')
@section('brand-icon', 'bi bi-shield-check')
@section('dashboard-route', route('admin.dashboard'))
@section('user-name', Auth::guard('admin')->user()?->name ?? 'Guest')
@section('user-role', 'System Administrator')
@section('user-initial', Auth::guard('admin')->user() ? strtoupper(substr(Auth::guard('admin')->user()->name, 0, 1)) : 'G')
@section('logout-route', route('admin.logout'))

@section('sidebar-menu')
    @include('admin.partials.sidebar')
@endsection

@section('custom-styles')
<style>
    .settings-nav .nav-link {
        color: #4b5563;
        font-weight: 500;
        padding: 0.75rem 1.25rem;
        border-radius: 8px;
        border: none;
        display: flex;
        align-items: center;
        gap: 0.6rem;
        transition: all 0.2s;
    }

    .settings-nav .nav-link:hover {
        background: #f3f4f6;
        color: #111827;
    }

    .settings-nav .nav-link.active {
        background: linear-gradient(135deg, #c9a84c 0%, #a07828 100%);
        color: #fff !important;
    }

    .settings-nav .nav-link i {
        font-size: 1.05rem;
    }

    .settings-card {
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        background: #fff;
    }

    .settings-card .card-header {
        background: #f9fafb;
        border-bottom: 1px solid #e5e7eb;
        border-radius: 12px 12px 0 0;
        padding: 1.1rem 1.5rem;
    }

    .avatar-preview {
        width: 90px;
        height: 90px;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid #c9a84c;
    }

    .avatar-placeholder {
        width: 90px;
        height: 90px;
        border-radius: 50%;
        background: linear-gradient(135deg, #c9a84c 0%, #a07828 100%);
        color: #fff;
        font-size: 2rem;
        font-weight: 700;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 3px solid #c9a84c;
    }

    .password-strength-bar {
        height: 4px;
        border-radius: 2px;
        transition: width 0.3s, background 0.3s;
    }
</style>
@endsection

@section('content')

    <div class="page-header" style="background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%); border-radius: 12px; padding: 1.5rem; color: white; margin-bottom: 1.5rem;">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h4 class="fw-bold mb-1"><i class="bi bi-gear me-2"></i>Settings</h4>
                <p class="mb-0 opacity-90">Manage your account and preferences</p>
            </div>
        </div>
    </div>

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

    <div class="row g-4">

        {{-- Left nav --}}
        <div class="col-lg-3">
            <div class="settings-card p-2">
                <nav class="settings-nav nav flex-column gap-1" id="settingsTabs" role="tablist">
                    <button class="nav-link active" id="tab-profile" data-bs-toggle="tab" data-bs-target="#pane-profile" type="button" role="tab">
                        <i class="bi bi-person-circle"></i> Profile
                    </button>
                    <button class="nav-link" id="tab-password" data-bs-toggle="tab" data-bs-target="#pane-password" type="button" role="tab">
                        <i class="bi bi-shield-lock"></i> Change Password
                    </button>
                </nav>
            </div>
        </div>

        {{-- Right panes --}}
        <div class="col-lg-9">
            <div class="tab-content">

                {{-- ── Profile Tab ── --}}
                <div class="tab-pane fade show active" id="pane-profile" role="tabpanel">
                    <div class="settings-card">
                        <div class="card-header">
                            <h6 class="fw-bold mb-0">Profile Information</h6>
                        </div>
                        <div class="card-body p-4">

                            <form method="POST" action="{{ route('admin.profile.update') }}" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')

                                {{-- Avatar row --}}
                                <div class="d-flex align-items-center gap-4 mb-4">
                                    <div id="avatarWrap">
                                        @if($admin->photo)
                                            <img src="{{ asset('storage/' . $admin->photo) }}" alt="{{ $admin->name }}" class="avatar-preview" id="avatarPreview">
                                        @else
                                            <div class="avatar-placeholder" id="avatarPlaceholder">{{ strtoupper(substr($admin->name, 0, 1)) }}</div>
                                            <img src="" alt="" class="avatar-preview d-none" id="avatarPreview">
                                        @endif
                                    </div>
                                    <div>
                                        <label for="photo" class="btn btn-sm btn-outline-secondary">
                                            <i class="bi bi-upload me-1"></i>Upload Photo
                                        </label>
                                        <input type="file" id="photo" name="photo" class="d-none" accept="image/jpeg,image/jpg,image/png" onchange="previewAvatar(this)">
                                        <p class="text-muted small mb-0 mt-1">JPG or PNG, max 2 MB</p>
                                        @error('photo')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Full Name <span class="text-danger">*</span></label>
                                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                            value="{{ old('name', $admin->name) }}" required>
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Email Address <span class="text-danger">*</span></label>
                                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                            value="{{ old('email', $admin->email) }}" required>
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Phone Number</label>
                                        <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror"
                                            value="{{ old('phone', $admin->phone) }}" placeholder="e.g. 98XXXXXXXX">
                                        @error('phone')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Role</label>
                                        <input type="text" class="form-control bg-light" value="System Administrator" readonly>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-end mt-4">
                                    <button type="submit" class="btn btn-primary px-4">
                                        <i class="bi bi-check-circle me-2"></i>Save Changes
                                    </button>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>

                {{-- ── Change Password Tab ── --}}
                <div class="tab-pane fade" id="pane-password" role="tabpanel">
                    <div class="settings-card">
                        <div class="card-header">
                            <h6 class="fw-bold mb-0">Change Password</h6>
                        </div>
                        <div class="card-body p-4">

                            <form method="POST" action="{{ route('admin.change-password.post') }}">
                                @csrf

                                <div class="row g-3">
                                    <div class="col-12">
                                        <label class="form-label fw-semibold">Current Password <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <input type="password" name="current_password" id="current_password"
                                                class="form-control @error('current_password') is-invalid @enderror" required>
                                            <button class="btn btn-outline-secondary" type="button" onclick="togglePwd('current_password', 'icon_cur')">
                                                <i class="bi bi-eye" id="icon_cur"></i>
                                            </button>
                                        </div>
                                        @error('current_password')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">New Password <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <input type="password" name="password" id="new_password"
                                                class="form-control @error('password') is-invalid @enderror"
                                                required minlength="8" oninput="checkStrength(this.value)">
                                            <button class="btn btn-outline-secondary" type="button" onclick="togglePwd('new_password', 'icon_new')">
                                                <i class="bi bi-eye" id="icon_new"></i>
                                            </button>
                                        </div>
                                        <div class="mt-1">
                                            <div class="d-flex gap-1">
                                                <div class="password-strength-bar flex-fill bg-secondary" id="bar1"></div>
                                                <div class="password-strength-bar flex-fill bg-secondary" id="bar2"></div>
                                                <div class="password-strength-bar flex-fill bg-secondary" id="bar3"></div>
                                                <div class="password-strength-bar flex-fill bg-secondary" id="bar4"></div>
                                            </div>
                                            <small class="text-muted" id="strengthLabel">Min. 8 characters</small>
                                        </div>
                                        @error('password')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Confirm New Password <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <input type="password" name="password_confirmation" id="confirm_password"
                                                class="form-control" required minlength="8">
                                            <button class="btn btn-outline-secondary" type="button" onclick="togglePwd('confirm_password', 'icon_con')">
                                                <i class="bi bi-eye" id="icon_con"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <div class="alert alert-light border mt-4 mb-0">
                                    <strong>Password requirements:</strong>
                                    <ul class="mb-0 mt-1 ps-3 small text-muted">
                                        <li>At least 8 characters</li>
                                        <li>Mix of uppercase and lowercase letters</li>
                                        <li>Include numbers and special characters</li>
                                    </ul>
                                </div>

                                <div class="d-flex justify-content-end mt-4">
                                    <button type="submit" class="btn btn-primary px-4">
                                        <i class="bi bi-shield-check me-2"></i>Update Password
                                    </button>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>

            </div>{{-- end tab-content --}}
        </div>

    </div>
@endsection

@section('scripts')
<script>
    // Auto-open password tab if there's a password-related error
    @if($errors->has('current_password') || $errors->has('password'))
        document.addEventListener('DOMContentLoaded', function () {
            document.getElementById('tab-password').click();
        });
    @endif

    // Avatar preview
    function previewAvatar(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function (e) {
                const preview = document.getElementById('avatarPreview');
                const placeholder = document.getElementById('avatarPlaceholder');
                preview.src = e.target.result;
                preview.classList.remove('d-none');
                if (placeholder) placeholder.classList.add('d-none');
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    // Toggle password visibility
    function togglePwd(fieldId, iconId) {
        const field = document.getElementById(fieldId);
        const icon  = document.getElementById(iconId);
        if (field.type === 'password') {
            field.type = 'text';
            icon.classList.replace('bi-eye', 'bi-eye-slash');
        } else {
            field.type = 'password';
            icon.classList.replace('bi-eye-slash', 'bi-eye');
        }
    }

    // Password strength indicator
    function checkStrength(val) {
        let score = 0;
        if (val.length >= 8)                      score++;
        if (/[A-Z]/.test(val) && /[a-z]/.test(val)) score++;
        if (/[0-9]/.test(val))                    score++;
        if (/[^A-Za-z0-9]/.test(val))             score++;

        const colors  = ['#ef4444', '#f97316', '#eab308', '#22c55e'];
        const labels  = ['Weak', 'Fair', 'Good', 'Strong'];
        const bars    = ['bar1','bar2','bar3','bar4'];

        bars.forEach((id, i) => {
            const el = document.getElementById(id);
            el.style.background = i < score ? colors[score - 1] : '#e5e7eb';
        });

        const label = document.getElementById('strengthLabel');
        label.textContent = val.length === 0 ? 'Min. 8 characters' : labels[score - 1] ?? 'Weak';
        label.style.color = val.length === 0 ? '' : colors[score - 1];
    }
</script>
@endsection
