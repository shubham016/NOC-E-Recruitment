@extends('layouts.app')

@section('title', 'Result Detail')

@section('sidebar-menu')
    <a href="{{ route('candidate.dashboard') }}" class="sidebar-menu-item">
        <i class="bi bi-speedometer2"></i>
        <span>Dashboard</span>
    </a>
    <a href="{{ route('candidate.viewresult') }}" class="sidebar-menu-item">
        <i class="bi bi-search"></i>
        <span>Vacancy</span>
    </a>
    <a href="{{ route('candidate.applications.index') }}" class="sidebar-menu-item">
        <i class="bi bi-file-earmark-text"></i>
        <span>My Applications</span>
    </a>
    <a href="{{ route('candidate.viewresult') }}" class="sidebar-menu-item active">
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
<div class="page-header mb-4">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="page-title">
                <i class="bi bi-file-earmark-check text-primary"></i> Result Detail
            </h1>
            <p class="page-subtitle">Detailed view of your examination result</p>
        </div>
        <a href="{{ route('candidate.viewresult') }}" class="btn btn-outline-primary">
            <i class="bi bi-arrow-left"></i> Back to Results
        </a>
    </div>
</div>

<div class="card shadow-sm">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0">
            <i class="bi bi-person-badge"></i> Candidate Information
        </h5>
    </div>
    <div class="card-body">
        <div class="row g-4">
            <!-- Personal Information -->
            <div class="col-md-6">
                <div class="border rounded p-3 h-100">
                    <h6 class="text-primary mb-3">
                        <i class="bi bi-person-circle"></i> Personal Details
                    </h6>
                    <table class="table table-sm table-borderless mb-0">
                        <tr>
                            <td class="text-muted" width="45%">Full Name:</td>
                            <td><strong>{{ $result->full_name ?? 'N/A' }}</strong></td>
                        </tr>
                        <tr>
                            <td class="text-muted">Citizenship No:</td>
                            <td><strong>{{ $result->citizenship_number ?? 'N/A' }}</strong></td>
                        </tr>
                        <tr>
                            <td class="text-muted">Roll Number:</td>
                            <td>
                                <span class="badge bg-info text-dark fs-6">
                                    {{ $result->roll_number ?? 'Not Assigned' }}
                                </span>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>

            <!-- Application Information -->
            <div class="col-md-6">
                <div class="border rounded p-3 h-100">
                    <h6 class="text-primary mb-3">
                        <i class="bi bi-briefcase"></i> Application Details
                    </h6>
                    <table class="table table-sm table-borderless mb-0">
                        <tr>
                            <td class="text-muted" width="50%">Applied Post:</td>
                            <td><strong>{{ $result->post ?? 'N/A' }}</strong></td>
                        </tr>
                        <tr>
                            <td class="text-muted">Advertisement Code:</td>
                            <td><strong>{{ $result->advertisement_code ?? 'N/A' }}</strong></td>
                        </tr>
                        <tr>
                            <td class="text-muted">Advertisement No:</td>
                            <td><strong>{{ $result->advertisement_number ?? 'N/A' }}</strong></td>
                        </tr>
                        <tr>
                            <td class="text-muted">Quota:</td>
                            <td>
                                <span class="badge bg-secondary">
                                    {{ $result->quota ?? 'N/A' }}
                                </span>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>

            <!-- Examination Result -->
            <div class="col-12">
                <div class="border rounded p-4 bg-light">
                    <h5 class="text-center text-primary mb-4">
                        <i class="bi bi-trophy"></i> Examination Result
                    </h5>
                    
                    @if($result->marks !== null)
                        <div class="row text-center g-3">
                            <div class="col-md-3">
                                <div class="card bg-white border-0 shadow-sm">
                                    <div class="card-body">
                                        <h6 class="text-muted mb-2">Obtained Marks</h6>
                                        <h2 class="text-success mb-0">{{ number_format($result->marks, 2) }}</h2>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card bg-white border-0 shadow-sm">
                                    <div class="card-body">
                                        <h6 class="text-muted mb-2">Class</h6>
                                        <h4 class="mb-0">
                                            <span class="badge bg-{{ $result->class_badge_color }}">
                                                {{ $result->class ?? 'N/A' }}
                                            </span>
                                        </h4>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card bg-white border-0 shadow-sm">
                                    <div class="card-body">
                                        <h6 class="text-muted mb-2">Service</h6>
                                        <h6 class="mb-0 text-truncate" title="{{ $result->recommended_service ?? 'N/A' }}">
                                            {{ $result->recommended_service ?? 'N/A' }}
                                        </h6>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card bg-white border-0 shadow-sm">
                                    <div class="card-body">
                                        <h6 class="text-muted mb-2">Status</h6>
                                        <h5 class="mb-0">
                                            <span class="badge bg-{{ $result->status_badge_color }}">
                                                <i class="bi bi-check-circle"></i> {{ ucfirst($result->status) }}
                                            </span>
                                        </h5>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Success Message -->
                        @if($result->status === 'published')
                            <div class="alert alert-success mt-4 mb-0 text-center">
                                <i class="bi bi-check-circle-fill"></i>
                                <strong>Congratulations!</strong> Your result has been published.
                            </div>
                        @endif
                    @else
                        <div class="alert alert-warning text-center mb-0">
                            <i class="bi bi-hourglass-split"></i>
                            <strong>Result {{ ucfirst($result->status) }}</strong>
                            <p class="mb-0 mt-2">
                                @if($result->status === 'pending')
                                    Your examination result has not been published yet. Please check back later.
                                @else
                                    {{ $result->remarks ?? 'Please contact administration for more information.' }}
                                @endif
                            </p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Application Timeline -->
            <div class="col-12">
                <div class="border rounded p-3">
                    <h6 class="text-primary mb-3">
                        <i class="bi bi-clock-history"></i> Timeline
                    </h6>
                    <table class="table table-sm table-borderless mb-0">
                        <tr>
                            <td class="text-muted" width="30%">Application Submitted:</td>
                            <td><strong>{{ $result->created_at_formatted }}</strong></td>
                        </tr>
                        @if($result->status === 'published' && $result->published_at)
                        <tr>
                            <td class="text-muted">Result Published:</td>
                            <td><strong>{{ $result->published_at_formatted }}</strong></td>
                        </tr>
                        @endif
                    </table>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="text-center mt-4">
            <button class="btn btn-primary me-2" onclick="window.print()">
                <i class="bi bi-printer"></i> Print Result
            </button>
            <a href="{{ route('candidate.viewresult') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Back to All Results
            </a>
        </div>
    </div>
</div>

@push('styles')
<style>
    @media print {
        .sidebar, .navbar, footer, .btn, .page-subtitle {
            display: none !important;
        }
        .main-content {
            margin-left: 0 !important;
            padding: 0 !important;
        }
        .card {
            box-shadow: none !important;
            border: 1px solid #ddd !important;
        }
        .page-header {
            margin-bottom: 1rem !important;
        }
    }
</style>
@endpush

@endsection