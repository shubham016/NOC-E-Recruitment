@extends('layouts.app')

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
    <a href="{{ route('candidate.my-profile') }}" class="sidebar-menu-item active">
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
<div class="container-fluid px-4">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h4 class="mb-0"><i class="fas fa-user-circle me-2"></i>My Profile</h4>
                    <a href="{{ route('candidate.dashboard') }}" class="btn btn-light btn-sm">
                        <i class="fas fa-arrow-left me-1"></i>Back to Dashboard
                    </a>
                </div>
                
                <div class="card-body">
                    <div class="row">
                        <!-- Personal Information -->
                        <div class="col-md-6 mb-4">
                            <h5 class="border-bottom pb-2 mb-3">Personal Information</h5>
                            
                            <div class="mb-3">
                                <label class="text-muted small">Full Name</label>
                                <p class="fw-bold">{{ $candidate->name }}</p>
                            </div>
                            
                            <div class="mb-3">
                                <label class="text-muted small">Email</label>
                                <p class="fw-bold">{{ $candidate->email }}</p>
                            </div>
                            
                            <div class="mb-3">
                                <label class="text-muted small">Gender</label>
                                <p class="fw-bold">{{ $candidate->gender }}</p>
                            </div>
                            
                            <div class="mb-3">
                                <label class="text-muted small">Date of Birth (BS)</label>
                                <p class="fw-bold">{{ $candidate->date_of_birth_bs }}</p>
                            </div>
                        </div>
                        
                        <!-- Citizenship Information -->
                        <div class="col-md-6 mb-4">
                            <h5 class="border-bottom pb-2 mb-3">Citizenship Information</h5>
                            
                            <div class="mb-3">
                                <label class="text-muted small">Citizenship Number</label>
                                <p class="fw-bold">{{ $candidate->citizenship_number }}</p>
                            </div>
                            
                            <div class="mb-3">
                                <label class="text-muted small">Issue District</label>
                                <p class="fw-bold">{{ $candidate->citizenship_issue_distric }}</p>
                            </div>
                            
                            <div class="mb-3">
                                <label class="text-muted small">Issue Date (BS)</label>
                                <p class="fw-bold">{{ $candidate->citizenship_issue_date_bs }}</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Account Information -->
                    <div class="row mt-3">
                        <div class="col-12">
                            <h5 class="border-bottom pb-2 mb-3">Account Information</h5>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="text-muted small">Profile Created</label>
                                    <p class="fw-bold">{{ \Carbon\Carbon::parse($candidate->created_at)->format('F d, Y') }}</p>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label class="text-muted small">Last Updated</label>
                                    <p class="fw-bold">{{ \Carbon\Carbon::parse($candidate->updated_at)->format('F d, Y') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Action Buttons -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <a href="{{ route('candidate.applications.index') }}" class="btn btn-primary me-2">
                                <i class="fas fa-file-alt me-1"></i>View My Applications
                            </a>
                            <a href="{{ route('candidate.applications.create') }}" class="btn btn-success">
                                <i class="fas fa-plus me-1"></i>Create New Application
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection