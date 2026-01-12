@extends('layouts.app')

@section('title', 'Browse Jobs')

@section('sidebar-menu')
    <a href="{{ route('candidate.dashboard') }}" class="sidebar-menu-item">
        <i class="bi bi-speedometer2"></i>
        <span>Dashboard</span>
    </a>
    <a href="{{ route('candidate.jobs.index') }}" class="sidebar-menu-item active">
        <i class="bi bi-search"></i>
        <span>Vacancy</span>
    </a>
    <a href="{{ route('candidate.applications.index') }}" class="sidebar-menu-item">
        <i class="bi bi-file-earmark-text"></i>
        <span>My Applications</span>
    </a>
    <a href="{{ route('candidate.viewresult') }}" class="sidebar-menu-item">
        <i class="bi bi-file-earmark-check"></i>
        <span>View Result</span>
    </a>
    <a href="{{ route('candidate.my-profile') }}" class="sidebar-menu-item">
        <i class="bi bi-person"></i>
        <span>My Profile</span>
    </a>
    <a href="{{ route('candidate.admit-card') }}" class="sidebar-menu-item">
        <i class="bi bi-box-arrow-down"></i>
        <span>Download Admit Card</span>
    </a>
    <a href="{{ route('candidate.change-password') }}" class="sidebar-menu-item">
        <i class="bi bi-lock"></i>
        <span>Change Password</span>
    </a>
@endsection

@section('content')
<div class="container-fluid">
    <div class="page-header">
        <h1 class="page-title">Browse Available Jobs</h1>
        <p class="page-subtitle">Find your next career opportunity</p>
    </div>

    <!-- Search and Filters -->
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('candidate.jobs.index') }}">
                <div class="row g-3">
                    <!-- Search -->
                    <div class="col-md-3">
                        <label class="form-label">Search</label>
                        <input type="text" 
                               name="search" 
                               value="{{ request('search') }}"
                               placeholder="Job title, dept, location..."
                               class="form-control">
                    </div>

                    <!-- Department Filter -->
                    <div class="col-md-3">
                        <label class="form-label">Department</label>
                        <select name="department" class="form-select">
                            <option value="">All Departments</option>
                            @foreach($departments as $dept)
                                <option value="{{ $dept }}" {{ request('department') == $dept ? 'selected' : '' }}>
                                    {{ $dept }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Location Filter -->
                    <div class="col-md-3">
                        <label class="form-label">Location</label>
                        <select name="location" class="form-select">
                            <option value="">All Locations</option>
                            @foreach($locations as $loc)
                                <option value="{{ $loc }}" {{ request('location') == $loc ? 'selected' : '' }}>
                                    {{ $loc }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Position Level Filter -->
                    <div class="col-md-3">
                        <label class="form-label">Position Level</label>
                        <select name="position_level" class="form-select">
                            <option value="">All Levels</option>
                            @foreach($positionLevels as $level)
                                <option value="{{ $level }}" {{ request('position_level') == $level ? 'selected' : '' }}>
                                    {{ $level }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="mt-3">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-search"></i> Apply Filters
                    </button>
                    <a href="{{ route('candidate.jobs.index') }}" class="btn btn-secondary">
                        <i class="bi bi-x-circle"></i> Clear Filters
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Job Count -->
    <div class="mb-3">
        <p class="text-muted">Showing {{ $jobs->total() }} job(s)</p>
    </div>

    <!-- Job Listings -->
    @if($jobs->count() > 0)
        <div class="row g-4">
            @foreach($jobs as $job)
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 shadow-sm hover-shadow">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <h5 class="card-title mb-0">{{ $job->title }}</h5>
                                @if(in_array($job->id, $appliedJobIds))
                                    <span class="badge bg-success">Applied</span>
                                @endif
                            </div>

                            <div class="mb-3">
                                <p class="mb-1 small"><strong>Ad No:</strong> {{ $job->advertisement_no }}</p>
                                <p class="mb-1 small"><strong>Department:</strong> {{ $job->department }}</p>
                                <p class="mb-1 small"><strong>Location:</strong> {{ $job->location }}</p>
                                <p class="mb-1 small"><strong>Level:</strong> {{ $job->position_level }}</p>
                                <p class="mb-1 small"><strong>Deadline:</strong> {{ \Carbon\Carbon::parse($job->deadline)->format('M d, Y') }}</p>
                                <p class="mb-0 small"><strong>Applications:</strong> {{ $job->applications_count }}</p>
                            </div>

                            <a href="{{ route('candidate.jobs.show', $job->id) }}" class="btn btn-primary w-100">
                                <i class="bi bi-eye"></i> View Details
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-4 d-flex justify-content-center">
            {{ $jobs->links() }}
        </div>
    @else
        <div class="card shadow-sm">
            <div class="card-body text-center py-5">
                <i class="bi bi-inbox" style="font-size: 3rem; color: #6c757d;"></i>
                <p class="text-muted mt-3 mb-0">No jobs found matching your criteria.</p>
            </div>
        </div>
    @endif
</div>

<style>
    .hover-shadow {
        transition: box-shadow 0.3s ease;
    }
    .hover-shadow:hover {
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
    }
</style>
@endsection