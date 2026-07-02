@extends('layouts.dashboard')

@section('title', __('admin.my_profile'))
@section('portal-name', __('admin.portal_name'))
@section('brand-icon', 'bi bi-shield-lock')
@section('dashboard-route', route('admin.dashboard'))
@section('logout-route', route('admin.logout'))

@section('sidebar-menu')
    @include('admin.partials.sidebar')
@endsection

@section('custom-styles')
<style>
    .btn-outline-navy {
        border: 1px solid #1a3a6b;
        color: #1a3a6b;
        background: #fff;
        transition: all 0.2s ease;
    }

    .btn-outline-navy:hover,
    .btn-outline-navy:focus {
        background: linear-gradient(135deg, #1a3a6b 0%, #122a52 100%);
        border-color: #122a52;
        color: #fff;
    }
    
    .btn-outline-navy-secondary {
        border: 1px solid #6b7280;
        color: #374151;
        background: #fff;
        transition: all 0.2s ease;
    }

    .btn-outline-navy-secondary:hover,
    .btn-outline-navy-secondary:focus {
        background: linear-gradient(135deg, #1a3a6b 0%, #122a52 100%);
        border-color: #122a52;
        color: #fff;
    }
</style>
@endsection

@section('content')
    <div class="page-header">
        <h1 class="page-title">{{ __('admin.my_profile') }}</h1>
        <p class="page-subtitle">{{ __('admin.view_manage_profile') }}</p>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <!-- Profile Information Card -->
        <div class="col-lg-4 mb-4">
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <div class="mb-3">
                        @if ($admin->photo)
                            <img src="{{ asset('storage/' . $admin->photo) }}" alt="{{ $admin->name }}"
                                class="rounded-circle" style="width: 120px; height: 120px; object-fit: cover;">
                        @else
                            <div class="rounded-circle mx-auto d-flex align-items-center justify-content-center"
                                style="width: 120px; height: 120px; background: linear-gradient(135deg, #1a3a6b 0%, #122a52 100%); color: white; font-size: 3rem; font-weight: 600;">
                                {{ substr($admin->name, 0, 1) }}
                            </div>
                        @endif
                    </div>
                    <h4 class="mb-1">{{ $admin->name }}</h4>
                    <p class="text-muted mb-3">{{ __('admin.system_administrator') }}</p>
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.profile.edit') }}" class="btn btn-outline-navy">
                            <i class="bi bi-pencil me-2"></i>{{ __('admin.edit_profile') }}
                        </a>
                        <a href="{{ route('admin.change-password') }}" class="btn btn-outline-navy-secondary">
                            <i class="bi bi-lock me-2"></i>{{ __('admin.change_password') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Profile Details & Statistics -->
        <div class="col-lg-8 mb-4">
            <!-- Contact Information -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="bi bi-person-lines-fill me-2 text-primary"></i>{{ __('admin.contact_information') }}</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="text-muted small">{{ __('admin.name') }}</label>
                            <p class="mb-0 fw-semibold">{{ $admin->name }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small">{{ __('admin.email_address') }}</label>
                            <p class="mb-0 fw-semibold">{{ $admin->email }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small">{{ __('admin.phone_number') }}</label>
                            <p class="mb-0 fw-semibold">{{ $admin->phone ?? __('admin.not_provided') }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small">{{ __('admin.status') }}</label>
                            <p class="mb-0">
                                <span class="badge bg-success">{{ __('admin.active') }}</span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistics -->
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="bi bi-bar-chart me-2 text-primary"></i>{{ __('admin.system_overview') }}</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="d-flex align-items-center p-3 bg-light rounded">
                                <div class="flex-shrink-0">
                                    <div class="rounded-circle d-flex align-items-center justify-content-center"
                                        style="width: 50px; height: 50px; background: rgba(42, 82, 152, 0.2);">
                                        <i class="bi bi-briefcase text-warning" style="font-size: 1.5rem;"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h3 class="mb-0">{{ $stats['total_vacancies'] }}</h3>
                                    <p class="text-muted mb-0 small">{{ __('admin.total_vacancies') }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex align-items-center p-3 bg-light rounded">
                                <div class="flex-shrink-0">
                                    <div class="rounded-circle d-flex align-items-center justify-content-center"
                                        style="width: 50px; height: 50px; background: rgba(40, 167, 69, 0.2);">
                                        <i class="bi bi-check-circle text-success" style="font-size: 1.5rem;"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h3 class="mb-0">{{ $stats['active_vacancies'] }}</h3>
                                    <p class="text-muted mb-0 small">{{ __('admin.active_vacancies') }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex align-items-center p-3 bg-light rounded">
                                <div class="flex-shrink-0">
                                    <div class="rounded-circle d-flex align-items-center justify-content-center"
                                        style="width: 50px; height: 50px; background: rgba(13, 110, 253, 0.2);">
                                        <i class="bi bi-file-earmark-text text-primary" style="font-size: 1.5rem;"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h3 class="mb-0">{{ $stats['total_applications'] }}</h3>
                                    <p class="text-muted mb-0 small">{{ __('admin.total_applications') }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex align-items-center p-3 bg-light rounded">
                                <div class="flex-shrink-0">
                                    <div class="rounded-circle d-flex align-items-center justify-content-center"
                                        style="width: 50px; height: 50px; background: rgba(255, 193, 7, 0.2);">
                                        <i class="bi bi-clock-history text-warning" style="font-size: 1.5rem;"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h3 class="mb-0">{{ $stats['pending_applications'] }}</h3>
                                    <p class="text-muted mb-0 small">{{ __('admin.pending_applications') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
