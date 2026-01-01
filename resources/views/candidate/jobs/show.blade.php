@extends('layouts.dashboard')

@section('title', $job->title)

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
    <a href="{{ route('candidate.jobs.index') }}" class="sidebar-menu-item active">
        <i class="bi bi-search"></i>
        <span>Browse Vacancy</span>
    </a>
    <a href="{{ route('candidate.applications.index') }}" class="sidebar-menu-item">
        <i class="bi bi-file-earmark-text"></i>
        <span>My Applications</span>
    </a>
    <a href="#" class="sidebar-menu-item">
        <i class="bi bi-bookmark"></i>
        <span>Saved Vacancies</span>
    </a>
    <a href="{{ route('candidate.profile.edit') }}" class="sidebar-menu-item">
        <i class="bi bi-person"></i>
        <span>My Profile</span>
    </a>
    <a href="{{ route('candidate.settings.index') }}" class="sidebar-menu-item">
        <i class="bi bi-gear"></i>
        <span>Settings</span>
    </a>
@endsection

@section('content')
    <div class="container-fluid my-4">
        <!-- Back Button -->
        <div class="mb-3">
            <a href="{{ route('candidate.jobs.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left"></i> Back to Vacancies
            </a>
        </div>

        <!-- Alerts -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show">
                <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Job Header -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-primary text-white">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h3 class="mb-2">{{ $job->title }}</h3>
                        <p class="mb-0">
                            <i class="fas fa-building"></i> {{ $job->department }} |
                            <i class="fas fa-map-marker-alt"></i> {{ $job->location }}
                        </p>
                    </div>
                    <span class="badge bg-light text-dark fs-6">
                        {{ ucfirst($job->status) }}
                    </span>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p class="mb-2">
                            <i class="fas fa-briefcase text-primary"></i>
                            <strong>Employment Type:</strong> {{ ucfirst($job->category) }}
                        </p>
                        <p class="mb-2">
                            <i class="fas fa-users text-success"></i>
                            <strong>Number of Vacancies:</strong> {{ ucfirst($job->number_of_posts) }}
                        </p>
                        <p class="mb-2">
                            <i class="fas fa-calendar text-info"></i>
                            <strong>Posted:</strong> {{ $job->created_at->format('M d, Y') }}
                        </p>
                    </div>
                    <div class="col-md-6">
                        <p class="mb-2">
                            <i class="fas fa-clock text-warning"></i>
                            <strong>Application Deadline:</strong>
                            <span
                                class="text-danger fw-bold">{{ \Carbon\Carbon::parse($job->application_deadline)->format('F d, Y') }}</span>
                        </p>
                        {{-- <p class="mb-2">
                            <i class="fas fa-money-bill text-success"></i>
                            <strong>Salary Range:</strong> {{ $job->salary_range ?? 'As per company policy' }}
                        </p> --}}
                    </div>
                </div>

                @php
                    $hasApplied = Auth::guard('candidate')->user()->applications()->where('job_posting_id', $job->id)->exists();
                @endphp

                <hr>

                <div class="d-flex gap-2">
                    @if($hasApplied)
                        <div class="alert alert-success mb-0 flex-fill">
                            <i class="fas fa-check-circle"></i> You have already applied for this position
                        </div>
                        <a href="{{ route('candidate.applications.index') }}" class="btn btn-primary">
                            <i class="fas fa-list"></i> View My Applications
                        </a>
                    @elseif($job->status === 'active')
                        <a href="{{ route('candidate.jobs.applications.create', $job->id) }}" class="btn btn-primary btn-lg">
                            <i class="fas fa-paper-plane"></i> Apply for This Position
                        </a>
                        <button type="button" class="btn btn-outline-secondary btn-lg">
                            <i class="fas fa-bookmark"></i> Save Vacancy
                        </button>
                    @else
                        <div class="alert alert-warning mb-0">
                            <i class="fas fa-exclamation-triangle"></i> This job is no longer accepting applications
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Job Details Tabs -->
        <div class="card shadow-sm">
            <div class="card-header">
                <ul class="nav nav-tabs card-header-tabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" data-bs-toggle="tab" href="#description">
                            <i class="fas fa-info-circle"></i> Description
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#requirements">
                            <i class="fas fa-list-check"></i> Requirements
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#responsibilities">
                            <i class="fas fa-tasks"></i> Responsibilities
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#benefits">
                            <i class="fas fa-gift"></i> Benefits
                        </a>
                    </li>
                </ul>
            </div>
            <div class="card-body">
                <div class="tab-content">
                    <!-- Description Tab -->
                    <div class="tab-pane fade show active" id="description">
                        <h5 class="mb-3">Vacancy Description</h5>
                        <div class="text-muted">
                            {!! nl2br(e($job->description ?? 'No description available.')) !!}
                        </div>
                    </div>

                    <!-- Requirements Tab -->
                    <div class="tab-pane fade" id="requirements">
                        <h5 class="mb-3">Requirements & Qualifications</h5>
                        <div class="text-muted">
                            {!! nl2br(e($job->requirements ?? 'No requirements specified.')) !!}
                        </div>
                    </div>

                    <!-- Responsibilities Tab -->
                    <div class="tab-pane fade" id="responsibilities">
                        <h5 class="mb-3">Key Responsibilities</h5>
                        <div class="text-muted">
                            {!! nl2br(e($job->responsibilities ?? 'No responsibilities specified.')) !!}
                        </div>
                    </div>

                    <!-- Benefits Tab -->
                    <div class="tab-pane fade" id="benefits">
                        <h5 class="mb-3">Benefits & Perks</h5>
                        <div class="text-muted">
                            {!! nl2br(e($job->benefits ?? 'Benefits will be discussed during the interview.')) !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Apply Button (Bottom) -->
        @if(!$hasApplied && $job->status === 'active')
            <div class="text-center mt-4">
                <a href="{{ route('candidate.jobs.applications.create', $job->id) }}" class="btn btn-primary btn-lg">
                    <i class="fas fa-paper-plane"></i> Apply for This Position Now
                </a>
            </div>
        @endif
    </div>
@endsection