@extends('layouts.app')

@section('title', __('candidate.candidate_dashboard'))

@section('sidebar-menu')
    <a href="{{ route('candidate.dashboard') }}" class="sidebar-menu-item active">
        <i class="bi bi-speedometer2"></i>
        <span>{{ __('candidate.dashboard') }}</span>
    </a>
    <a href="{{ route('candidate.my-profile') }}" class="sidebar-menu-item">
        <i class="bi bi-person"></i>
        <span>{{ __('candidate.my_profile') }}</span>
    </a>
    <a href="{{ route('candidate.jobs.index') }}" class="sidebar-menu-item">
        <i class="bi bi-search"></i>
        <span>{{ __('candidate.vacancy') }}</span>
    </a>
    <a href="{{ route('candidate.applications.index') }}" class="sidebar-menu-item">
        <i class="bi bi-file-earmark-text"></i>
        <span>{{ __('candidate.my_applications') }}</span>
    </a>
    <a href="{{ route('candidate.viewresult') }}" class="sidebar-menu-item">
        <i class="bi bi-file-earmark-check"></i>
        <span>{{ __('candidate.view_result') }}</span>
    </a>
    {{-- <a href="{{ route('candidate.my-profile') }}" class="sidebar-menu-item">
        <i class="bi bi-person"></i>
        <span>My Profile</span>
    </a>
    --}}
    <a href="{{ route('candidate.admit-card') }}" class="sidebar-menu-item">
        <i class="bi bi-box-arrow-down"></i>
        <span>{{ __('candidate.download_admit_card') }}</span>
    </a>
    <a href="{{ route('candidate.change-password') }}" class="sidebar-menu-item">
        <i class="bi bi-lock"></i>
        <span>{{ __('candidate.change_password') }}</span>
    </a>
@endsection

@section('custom-styles')
    <style>
        .dashboard-header {
        background: linear-gradient(135deg, #1a3a6b 0%, #122a52 100%);
        border-radius: 10px;
        padding: 1.5rem 2rem;
        color: white;
        margin-bottom: 2rem;
        box-shadow: 0 4px 12px rgba(26, 58, 107, 0.3);
    }
        .job-card {
            transition: all 0.3s ease;
            cursor: pointer;
            border-left: 4px solid transparent;
        }

        .job-card:hover {
            border-left-color: #10b981;
            transform: translateX(5px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1) !important;
        }

        .company-logo {
            width: 50px;
            height: 50px;
            border-radius: 8px;
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 700;
            font-size: 1.25rem;
        }

        .btn-apply {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            border: none;
            color: white;
        }

        .btn-apply:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(16, 185, 129, 0.3);
            color: white;
        }

        .badge-custom {
            padding: 0.35rem 0.65rem;
            font-weight: 500;
            font-size: 0.75rem;
        }
    </style>
@endsection

@section('content')


    <!-- Dashboard Header -->
    <div class="dashboard-header">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h2 class="fw-bold mb-1">
                    {{ __('candidate.welcome', ['name' => $candidate->name_english ?? Auth::guard('candidate')->user()->name_english ?? 'Candidate']) }}
                </h2>
                <p class="mb-0 opacity-75" style="font-size: 0.95rem;">{{ __('candidate.recruitment_candidate') }}</p>
            </div>
            <div class="col-md-4 text-md-end mt-2 mt-md-0">
                <small class="opacity-90">
                    <span id="english-date"></span><br>
                    <span id="nepali-date"></span>
                </small>
            </div>
        </div>
    </div>

    <!-- Page Header -->
    <!-- <div class="page-header mb-4">
        <h1 class="page-title">Welcome, {{ $candidate->name_english ?? Auth::guard('candidate')->user()->name_english ?? 'Candidate' }}!</h1>
    </div> -->

    <!-- Stats Cards -->
        <div class="row g-4 mb-4">
            <div class="col-12 col-sm-6 col-xl-3">
            <a href="{{ route('candidate.jobs.index') }}" class="text-decoration-none d-block">
                <div class="card stat-card h-60 shadow-sm cursor-pointer">
                    <h3 class="h2 fw-bold mb-1">{{ $jobpostingsCount ?? 0 }}</h3>
                    <p class="text-muted mb-2">{{ __('candidate.total_live_advertisements') }}</p>
                    <small class="text-danger">
                        <i class="bi bi-info-circle me-1"></i>{{ __('candidate.currently_active') }}
                    </small>
                </div>
            </a>
            </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <a href="{{ route('candidate.jobs.index') }}" class="text-decoration-none d-block">
                <div class="card stat-card h-60 shadow-sm cursor-pointer">
                    <h3 class="h2 fw-bold mb-1">{{ $jobpostingsCount ?? 0 }}</h3>
                    <p class="text-muted mb-2">{{ __('candidate.total_vacancy') }}</p>
                    <small class="text-warning">
                        <i class="bi bi-clock me-1"></i>{{ __('candidate.all_time') }}
                    </small>
                </div>
            </a>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
             <a href="{{ route('candidate.applications.index') }}" class="text-decoration-none d-block">
            <div class="card stat-card h-60 shadow-sm cursor-pointer">
                <h3 class="h2 fw-bold mb-1">{{ $applicationsCount ?? 0 }}</h3>
                <p class="text-muted mb-2">{{ __('candidate.my_applications') }}</p>
                <small class="text-success">
                    <i class="bi bi-check-circle me-1"></i>{{ __('candidate.all_applications') }}
                </small>
            </div>
             </a>
        </div>
    </div>
@endsection
