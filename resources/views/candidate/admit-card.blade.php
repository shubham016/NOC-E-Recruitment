@extends('layouts.app')

@section('title', 'Download Admit Card')

@section('content')
@section('sidebar-menu')
    <a href="{{ route('candidate.dashboard') }}" class="sidebar-menu-item">
        <i class="bi bi-speedometer2"></i>
        <span>Dashboard</span>
    </a>
    <a href="{{ route('candidate.jobs.index') }}" class="sidebar-menu-item">
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
    <a href="{{ route('candidate.admit-card') }}" class="sidebar-menu-item active">
        <i class="bi bi-box-arrow-down"></i>
        <span>Download Admit Card</span>
    </a>
    <a href="{{ route('candidate.change-password') }}" class="sidebar-menu-item">
        <i class="bi bi-lock"></i>
        <span>Change Password</span>
    </a>
@endsection
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-header mb-4 pb-3 border-bottom">
                <h2><i class="bi bi-file-earmark-text"></i> Download Admit Card</h2>
                <p class="text-muted">Download your admit card for scheduled examinations</p>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle-fill"></i>
            @foreach($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        @if($applications->isEmpty())
            <div class="col-12">
                <div class="card text-center py-5 shadow-sm">
                    <div class="card-body">
                        <i class="bi bi-file-earmark-text text-muted" style="font-size: 5rem; opacity: 0.3;"></i>
                        <h3 class="mt-4">No Admit Cards Available</h3>
                        <p class="text-muted mb-4">
                            Admit cards will be available once your application is shortlisted for examination.<br>
                            Please check back later or contact the administration for more information.
                        </p>
                        <a href="{{ route('candidate.dashboard') }}" class="btn btn-primary">
                            <i class="bi bi-house-door"></i> Back to Dashboard
                        </a>
                    </div>
                </div>
            </div>
        @else
            @foreach($applications as $application)
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-1">
                            <i class="bi bi-briefcase"></i> 
                            {{ $application->post_title ?? 'Position Applied' }}
                        </h5>
                        <small class="d-block">
                            Application ID: {{ $application->id }}
                        </small>
                        <small class="d-block"> 
                            Roll Number: {{ $application->roll_number }}
                        </small>
                    </div>
                    
                    <div class="card-body">
                        <div class="mb-3 pb-2 border-bottom">
                            <small class="text-muted d-block">Candidate Name</small>
                            <strong>{{ $application->name_english ?? $candidate->name }}</strong>
                        </div>
                        
                        <div class="mb-3 pb-2 border-bottom">
                            <small class="text-muted d-block">Exam Date</small>
                            <strong>{{ date('d M Y', strtotime($application->exam_date)) }}</strong>
                            <small class="d-block text-primary">{{ date('l', strtotime($application->exam_date)) }}</small>
                        </div>
                        
                        <div class="mb-3 pb-2 border-bottom">
                            <small class="text-muted d-block">Exam Time</small>
                            <strong>{{ $application->exam_time }}</strong>
                        </div>
                        
                        @if($application->reporting_time)
                        <div class="mb-3 pb-2 border-bottom">
                            <small class="text-muted d-block">Reporting Time</small>
                            <strong class="text-danger">{{ $application->reporting_time }}</strong>
                        </div>
                        @endif
                        
                        <div class="mb-3 pb-2 border-bottom">
                            <small class="text-muted d-block">Venue</small>
                            <strong>{{ Str::limit($application->exam_venue, 50) }}</strong>
                        </div>
                        
                        <div class="mb-3">
                            <small class="text-muted d-block">Status</small>
                            <span class="badge bg-success">
                                <i class="bi bi-patch-check"></i> {{ ucfirst($application->status) }}
                            </span>
                        </div>
                    </div>
                    
                    <div class="card-footer bg-light">
                        <div class="d-grid gap-2">
                            <a href="{{ route('candidate.admit-card.view', $application->id) }}" 
                               class="btn btn-outline-primary btn-sm">
                                <i class="bi bi-eye"></i> View Admit Card
                            </a>
                        <!--    <a href="{{ route('candidate.admit-card.download', $application->id) }}" 
                               class="btn btn-primary btn-sm">
                                <i class="bi bi-download"></i> Download PDF
                            </a> -->
                        </div>
                        <small class="text-muted d-block text-center mt-2">
                            <i class="bi bi-info-circle"></i> Bring this card on exam day
                        </small>
                    </div>
                </div>
            </div>
            @endforeach
        @endif
    </div>
</div>
@endsection