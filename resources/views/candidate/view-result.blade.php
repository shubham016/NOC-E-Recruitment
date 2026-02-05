@extends('layouts.app')

@section('title', 'View Result')

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
    <a href="{{ route('candidate.viewresult') }}" class="sidebar-menu-item active">
        <i class="bi bi-file-earmark-check"></i>
        <span>View Result</span>
    </a>
    {{-- <a href="{{ route('candidate.my-profile') }}" class="sidebar-menu-item">
        <i class="bi bi-person"></i>
        <span>My Profile</span>
    </a>
    --}}
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
<div class="page-header">
    <h1 class="page-title">
        <i class="bi bi-clipboard-data text-primary"></i> View Result
    </h1>
    <p class="page-subtitle">Check your examination and interview results</p>
</div>

@if($results->isEmpty())
    <div class="card shadow-sm">
        <div class="card-body text-center py-5">
            <i class="bi bi-inbox display-1 text-muted mb-3"></i>
            <h4 class="text-muted">No Results Available</h4>
            <p class="text-secondary">Your results will appear here once they are published by the administration.</p>
            <a href="{{ route('candidate.dashboard') }}" class="btn btn-primary mt-3">
                <i class="bi bi-house-door"></i> Back to Dashboard
            </a>
        </div>
    </div>
@else
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">
                <i class="bi bi-table"></i> My Results
            </h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-bordered align-middle">
                    <thead class="table-light">
                        <tr>
                            <th class="text-center">S.N.</th>
                            <th>Roll Number</th>
                            <th>Full Name</th>
                            <th>Citizenship Number</th>
                            <th>Post</th>
                            <th>Advertisement Code</th>
                            <th class="text-center">Marks</th>
                            <th>Class</th>
                            <th>Quota</th>
                            <th>Service</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($results as $index => $result)
                        <tr>
                            <td class="text-center">{{ $index + 1 }}</td>
                            <td>
                                <strong class="text-primary">{{ $result->roll_number ?? 'N/A' }}</strong>
                            </td>
                            <td>{{ $result->full_name ?? 'N/A' }}</td>
                            <td>{{ $result->citizenship_number ?? 'N/A' }}</td>
                            <td>
                                <span class="badge bg-info text-dark">
                                    {{ $result->post ?? 'N/A' }}
                                </span>
                            </td>
                            <td>{{ $result->advertisement_code ?? 'N/A' }}</td>
                            <td class="text-center">
                                @if($result->marks !== null)
                                    <strong class="text-success fs-5">{{ $result->marks }}</strong>
                                @else
                                    <span class="text-muted">N/A</span>
                                @endif
                            </td>
                            <td>
                                @if($result->class)
                                    <span class="badge 
                                        @if(strtolower($result->class) === 'first' || strtolower($result->class) === '1st') 
                                            bg-success
                                        @elseif(strtolower($result->class) === 'second' || strtolower($result->class) === '2nd')
                                            bg-primary
                                        @elseif(strtolower($result->class) === 'third' || strtolower($result->class) === '3rd')
                                            bg-warning text-dark
                                        @else
                                            bg-secondary
                                        @endif
                                    ">
                                        {{ $result->class }}
                                    </span>
                                @else
                                    <span class="text-muted">N/A</span>
                                @endif
                            </td>
                            <td>{{ $result->quota ?? 'N/A' }}</td>
                            <td>{{ $result->recommended_service ?? 'N/A' }}</td>
                            <td class="text-center">
                                @if($result->marks !== null && $result->class)
                                    <span class="badge bg-success">
                                        <i class="bi bi-check-circle"></i> Published
                                    </span>
                                @else
                                    <span class="badge bg-warning text-dark">
                                        <i class="bi bi-hourglass-split"></i> Pending
                                    </span>
                                @endif
                            </td>
                            <td class="text-center">
                                <a href="{{ url('/candidate/result/' . $result->id) }}" 
                                   class="btn btn-sm btn-outline-primary" 
                                   title="View Details">
                                    <i class="bi bi-eye"></i> Details
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Results Summary -->
            <div class="row mt-4">
                <div class="col-md-4">
                    <div class="card bg-light">
                        <div class="card-body text-center">
                            <h6 class="text-muted mb-2">Total Applications</h6>
                            <h3 class="text-primary mb-0">{{ $results->count() }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card bg-light">
                        <div class="card-body text-center">
                            <h6 class="text-muted mb-2">Results Published</h6>
                            <h3 class="text-success mb-0">
                                {{ $results->whereNotNull('marks')->count() }}
                            </h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card bg-light">
                        <div class="card-body text-center">
                            <h6 class="text-muted mb-2">Pending Results</h6>
                            <h3 class="text-warning mb-0">
                                {{ $results->whereNull('marks')->count() }}
                            </h3>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Download Button -->
            <div class="text-center mt-4">
                <button class="btn btn-primary" onclick="window.print()">
                    <i class="bi bi-printer"></i> Print Results
                </button>
            </div>
        </div>
    </div>
@endif

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
        /* Hide the Action column when printing */
        th:last-child, td:last-child {
            display: none !important;
        }
    }
</style>
@endpush

@endsection