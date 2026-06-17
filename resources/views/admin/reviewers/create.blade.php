@extends('layouts.dashboard')

@section('title', __('admin.create_reviewer'))

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

@section('content')
<div class="container-fluid px-4 py-4">
    <!-- Page Header -->
    <div class="card border-0 shadow-sm mb-4" style="background: linear-gradient(135deg, #1d6df7 0%, #1557c0 100%);">
        <div class="card-body text-white p-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="mb-2 fw-bold"><i class="bi bi-person-plus me-2"></i>{{ __('admin.create_reviewer') }}</h2>
                    <p class="mb-0 opacity-90">{{ __('admin.add_reviewer_to_system') }}</p>
                </div>
                <a href="{{ route('admin.reviewers.index') }}" class="btn btn-light">
                    <i class="bi bi-arrow-left me-2"></i>{{ __('admin.back') }}
                </a>
            </div>
        </div>
    </div>

    <!-- Create Form -->
    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">
            <form action="{{ route('admin.reviewers.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="row g-3">
                    <!-- Employee ID -->
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">{{ __('admin.employee_id') }} <span class="text-danger">*</span></label>
                        <input type="text" name="employee_id" class="form-control @error('employee_id') is-invalid @enderror"
                               value="{{ old('employee_id') }}" required>
                        @error('employee_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Name -->
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">{{ __('admin.name') }} <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                               value="{{ old('name') }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">{{ __('admin.email') }} <span class="text-danger">*</span></label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                               value="{{ old('email') }}" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Phone Number -->
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">{{ __('admin.phone') }}</label>
                        <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror"
                               value="{{ old('phone') }}">
                        @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Department -->
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">{{ __('admin.department') }} <span class="text-danger">*</span></label>
                        <input type="text" name="department" class="form-control @error('department') is-invalid @enderror"
                               value="{{ old('department') }}" required>
                        @error('department')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Designation -->
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">{{ __('admin.designation') }}</label>
                        <input type="text" name="designation" class="form-control @error('designation') is-invalid @enderror"
                               value="{{ old('designation') }}">
                        @error('designation')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Status -->
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">{{ __('admin.status') }} <span class="text-danger">*</span></label>
                        <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                            <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>{{ __('admin.active') }}</option>
                            <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>{{ __('admin.inactive') }}</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">{{ __('admin.password') }} <span class="text-danger">*</span></label>
                        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">{{ __('admin.at_least_8_chars') }}</small>
                    </div>

                    <!-- Photo -->
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">{{ __('admin.photo') }}</label>
                        <input type="file" name="photo" class="form-control @error('photo') is-invalid @enderror" accept="image/*">
                        @error('photo')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">{{ __('admin.jpg_png_max') }}</small>
                    </div>
                </div>

                <div class="mt-4 d-flex gap-2">
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="bi bi-save me-2"></i>{{ __('admin.create_reviewer') }}
                    </button>
                    <a href="{{ route('admin.reviewers.index') }}" class="btn btn-outline-secondary btn-lg">
                        <i class="bi bi-x-circle me-2"></i>{{ __('admin.cancel') }}
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
