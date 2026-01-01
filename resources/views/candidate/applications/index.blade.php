@extends('layouts.dashboard')

@section('title', 'My Applications')

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
    <a href="{{ route('candidate.jobs.index') }}" class="sidebar-menu-item">
        <i class="bi bi-search"></i>
        <span>Browse Vacancy</span>
    </a>
    <a href="{{ route('candidate.applications.index') }}" class="sidebar-menu-item active">
        <i class="bi bi-file-earmark-text"></i>
        <span>My Applications</span>
    </a>
    <a href="#" class="sidebar-menu-item">
        <i class="bi bi-bookmark"></i>
        <span>Saved Jobs</span>
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
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h4 class="mb-0"><i class="fas fa-list"></i> All Application Records</h4>
            <a href="{{ route('candidate.jobs.index') }}" class="btn btn-light btn-sm">
                <i class="fas fa-plus"></i> New Application
            </a>
        </div>

        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            @if($applications->count() > 0)
                <div class="table-responsive">
                    <table class="table table-striped table-hover align-middle">
                        <thead class="table-bg-primary">
                            <tr>
                                <th width="80">Photo</th>
                                <th>I.D</th>
                                <th>Job Position</th>
                                <th>Phone</th>
                                <th>Citizenship No.</th>
                                <th>Submitted</th>
                                <th width="120">Status</th>
                                <th width="180">Documents</th>
                                <th width="160">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($applications as $application)
                                <tr>
                                    <td class="text-center">
                                        @if($application->passport_photo)
                                            <img src="{{ asset('storage/' . $application->passport_photo) }}"
                                                 class="rounded-circle border"
                                                 width="50" height="50"
                                                 style="object-fit: cover;">
                                        @else
                                            <div class="bg-secondary rounded-circle" style="width:50px;height:50px;"></div>
                                        @endif
                                    </td>
                                    <td>{{ $loop->iteration + ($applications->currentPage() - 1) * $applications->perPage() }}</td>
                                    <td><strong>{{ $application->jobPosting->title ?? '-' }}</strong></td>
                                    <td>{{ $application->phone ?? '-' }}</td>
                                    <td>{{ $application->citizenship_number ?? '-' }}</td>
                                    <td>{{ $application->created_at->format('M d, Y') }}</td>
                                    <td>
                                        <span class="badge bg-{{ $application->status_color }}">
                                            {{ $application->status_label }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="d-flex flex-wrap gap-1">
                                            @if($application->noc_id_card) <span class="badge bg-info">NOC</span> @endif
                                            @if($application->disability_certificate) <span class="badge bg-warning">DIS</span> @endif
                                            @if($application->citizenship_certificate) <span class="badge bg-success">CIT</span> @endif
                                            @if($application->resume) <span class="badge bg-primary">CV</span> @endif
                                            @if($application->educational_certificates) <span class="badge bg-secondary">EDU</span> @endif
                                            @if($application->ethnic_certificate) <span class="badge bg-dark">ETH</span> @endif
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-grid gap-1">
                                        <!-- View Button -->
                                        <a href="{{ route('candidate.applications.show', $application->id) }}" 
                                            class="btn btn-info btn-sm">
                                            <i class="fas fa-eye"></i> View
                                        </a>

                                        <!-- Edit Button -->
                                        @if($application->canEdit())
                                            <a href="{{ route('candidate.jobs.applications.edit', [$application->job_posting_id, $application->id]) }}" 
                                                class="btn btn-warning btn-sm">
                                                <i class="fas fa-edit"></i> Edit
                                            </a>
                                        @endif

                                        <!-- Withdraw Button -->
                                        @if($application->canWithdraw())
                                            <form action="{{ route('candidate.applications.destroy', $application->id) }}" 
                                                    method="POST"
                                                    onsubmit="return confirm('Are you sure you want to withdraw this application? This action cannot be undone.');">
                                                @csrf 
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm w-100">
                                                    <i class="fas fa-times"></i> Withdraw
                                                </button>
                                            </form>
                                        @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-center mt-4">
                    {{ $applications->links() }}
                </div>
            @else
                <div class="alert alert-info text-center">
                    <i class="fas fa-info-circle"></i> No application records found.
                    <a href="{{ route('candidate.jobs.index') }}" class="alert-link">Browse Vacancy and Apply</a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection