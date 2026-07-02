@extends('layouts.dashboard')

@section('title', __('admin.settings'))
@section('portal-name', __('admin.portal_name'))
@section('brand-icon', 'bi bi-shield-check')
@section('dashboard-route', route('admin.dashboard'))
@section('user-name', Auth::guard('admin')->user()?->name ?? 'Guest')
@section('user-role', __('admin.system_administrator'))
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
        background: linear-gradient(135deg, #1a3a6b 0%, #122a52 100%);
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
        border: 3px solid #1a3a6b;
    }

    .avatar-placeholder {
        width: 90px;
        height: 90px;
        border-radius: 50%;
        background: linear-gradient(135deg, #1a3a6b 0%, #122a52 100%);
        color: #fff;
        font-size: 2rem;
        font-weight: 700;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 3px solid #1a3a6b;
    }

    .password-strength-bar {
        height: 4px;
        border-radius: 2px;
        transition: width 0.3s, background 0.3s;
    }

    .page-header {
        background: linear-gradient(135deg, #1a3a6b 0%, #122a52 100%);
        border-radius: 12px;
        padding: 1.5rem;
        color: white;
        margin-bottom: 1.5rem;
        box-shadow: 0 4px 12px rgba(26, 58, 107, 0.22);
    }

    .settings-card .btn-primary {
        background: linear-gradient(135deg, #2a5298 0%, #1a3a6b 100%);
        border-color: #1a3a6b;
        color: #fff;
    }

    .settings-card .btn-primary:hover {
        background: linear-gradient(135deg, #1f467f 0%, #122a52 100%);
        border-color: #122a52;
        color: #fff;
    }
</style>
@endsection

@section('content')

    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h4 class="fw-bold mb-1"><i class="bi bi-gear me-2"></i>{{ __('admin.system_settings') }}</h4>
                <p class="mb-0 opacity-90">{{ __('admin.manage_account') }}</p>
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
                    <button class="nav-link {{ ($activeTab ?? 'profile') === 'profile' ? 'active' : '' }}" id="tab-profile" data-bs-toggle="tab" data-bs-target="#pane-profile" type="button" role="tab">
                        <i class="bi bi-person-circle"></i> {{ __('admin.profile') }}
                    </button>
                    <button class="nav-link {{ ($activeTab ?? 'profile') === 'password' ? 'active' : '' }}" id="tab-password" data-bs-toggle="tab" data-bs-target="#pane-password" type="button" role="tab">
                        <i class="bi bi-shield-lock"></i> {{ __('admin.change_password') }}
                    </button>
                </nav>
            </div>
        </div>

        {{-- Right panes --}}
        <div class="col-lg-9">
            <div class="tab-content">

                {{-- ── Profile Tab ── --}}
                <div class="tab-pane fade {{ ($activeTab ?? 'profile') === 'profile' ? 'show active' : '' }}" id="pane-profile" role="tabpanel">
                    <div class="settings-card">
                        <div class="card-header">
                            <h6 class="fw-bold mb-0">{{ __('admin.profile_information') }}</h6>
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
                                            <i class="bi bi-upload me-1"></i>{{ __('admin.upload_photo') }}
                                        </label>
                                        <input type="file" id="photo" name="photo" class="d-none" accept="image/jpeg,image/jpg,image/png" onchange="previewAvatar(this)">
                                        <p class="text-muted small mb-0 mt-1">{{ __('admin.jpg_png_max') }}</p>
                                        @error('photo')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">{{ __('admin.full_name') }} <span class="text-danger">*</span></label>
                                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                            value="{{ old('name', $admin->name) }}" required>
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">{{ __('admin.email_address') }} <span class="text-danger">*</span></label>
                                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                            value="{{ old('email', $admin->email) }}" required>
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">{{ __('admin.phone_number') }}</label>
                                        <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror"
                                            value="{{ old('phone', $admin->phone) }}" placeholder="e.g. 98XXXXXXXX">
                                        @error('phone')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">{{ __('admin.role') }}</label>
                                        <input type="text" class="form-control bg-light" value="{{ __('admin.system_administrator') }}" readonly>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-end mt-4">
                                    <button type="submit" class="btn btn-primary px-4">
                                        <i class="bi bi-check-circle me-2"></i>{{ __('admin.save_changes') }}
                                    </button>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>

                {{-- ── Change Password Tab ── --}}
                <div class="tab-pane fade {{ ($activeTab ?? 'profile') === 'password' ? 'show active' : '' }}" id="pane-password" role="tabpanel">
                    <div class="settings-card">
                        <div class="card-header">
                            <h6 class="fw-bold mb-0">{{ __('admin.change_password') }}</h6>
                        </div>
                        <div class="card-body p-4">

                            <form method="POST" action="{{ route('admin.change-password.post') }}">
                                @csrf

                                <div class="row g-3">
                                    <div class="col-12">
                                        <label class="form-label fw-semibold">{{ __('admin.current_password') }} <span class="text-danger">*</span></label>
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
                                        <label class="form-label fw-semibold">{{ __('admin.new_password') }} <span class="text-danger">*</span></label>
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
                                            <small class="text-muted" id="strengthLabel">{{ __('admin.at_least_8_chars') }}</small>
                                        </div>
                                        @error('password')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">{{ __('admin.confirm_new_password') }} <span class="text-danger">*</span></label>
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
                                    <strong>{{ __('admin.password_requirements') }}:</strong>
                                    <ul class="mb-0 mt-1 ps-3 small text-muted">
                                        <li>{{ __('admin.at_least_8_chars') }}</li>
                                        <li>{{ __('admin.mix_upper_lower') }}</li>
                                        <li>{{ __('admin.include_numbers_special') }}</li>
                                    </ul>
                                </div>

                                <div class="d-flex justify-content-end mt-4">
                                    <button type="submit" class="btn btn-primary px-4">
                                        <i class="bi bi-shield-check me-2"></i>{{ __('admin.update_password') }}
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

        const colors  = ['#ef4444', '#3b82f6', '#1a3a6b', '#22c55e'];
        const labels  = ["{{ __('admin.weak') }}", "{{ __('admin.fair') }}", "{{ __('admin.good') }}", "{{ __('admin.strong') }}"];
        const bars    = ['bar1','bar2','bar3','bar4'];

        bars.forEach((id, i) => {
            const el = document.getElementById(id);
            el.style.background = i < score ? colors[score - 1] : '#e5e7eb';
        });

        const label = document.getElementById('strengthLabel');
        label.textContent = val.length === 0 ? "{{ __('admin.at_least_8_chars') }}" : labels[score - 1] ?? "{{ __('admin.weak') }}";
        label.style.color = val.length === 0 ? '' : colors[score - 1];
    }
</script>
@endsection
