@extends('layouts.app')

@section('title', $job->title)

@section('portal-name', 'Candidate Portal')
@section('brand-icon', 'bi bi-briefcase')
@section('dashboard-route', route('candidate.dashboard'))
@section('user-name', Auth::guard('candidate')->user()?->name ?? 'Guest')
@section('user-role', 'Job Seeker')
@section('user-initial', strtoupper(substr(Auth::guard('candidate')->user()?->name ?? 'G', 0, 1)))
@section('logout-route', route('candidate.logout'))

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
    <div class="container-fluid my-0">
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

        @if($errors->has('eligibility'))
            <div class="alert alert-danger alert-dismissible fade show">
                <i class="fas fa-exclamation-circle"></i> <strong>{{ $errors->first('eligibility') }}</strong>
                @if($errors->has('reasons'))
                    <ul class="mt-2 mb-0">
                        @foreach($errors->get('reasons')[0] as $reason)
                            <li>{{ $reason }}</li>
                        @endforeach
                    </ul>
                @endif
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
                            <strong>Number of Vacancies:</strong> {{ $job->number_of_posts }}
                        </p>
                        <p class="mb-2">
                            <i class="fas fa-calendar text-info"></i>
                            <strong>Posted:</strong> {{ $job->created_at->format('M d, Y') }}
                        </p>
                        <p class="mb-2">
                            <i class="fas fa-star text-warning"></i>
                            <strong>Position Level:</strong> {{ $job->position_level }}
                        </p>
                        <p class="mb-2">
                                    <i class="bi bi-broadcast-pin"></i>
                                    <strong>Advertisement Number:</strong> {{ $job->advertisement_no}}
                                </p>
                    </div>
                    <div class="col-md-6">
                        <p class="mb-2">
                            <i class="fas fa-clock text-danger"></i>
                            <strong>Application Deadline:</strong>
                            <span class="text-danger fw-bold">
                                {{ \Carbon\Carbon::parse($job->application_deadline)->format('F d, Y') }}
                            </span>
                        </p>
                        <p class="mb-2">
                            <i class="fas fa-building text-primary"></i>
                            <strong>Service Group:</strong> {{ $job->service_group }}
                        </p>
                        @if($job->min_age || $job->max_age)
                            <p class="mb-2">
                                <i class="fas fa-user-clock text-info"></i>
                                <strong>Age Requirement:</strong> 
                                @if($job->min_age && $job->max_age)
                                    {{ $job->min_age }} - {{ $job->max_age }} years
                                @elseif($job->min_age)
                                    Minimum {{ $job->min_age }} years
                                @else
                                    Maximum {{ $job->max_age }} years
                                @endif
                            </p>
                        @endif
                        @if($job->minimum_qualification)
                            <p class="mb-2">
                                <i class="fas fa-graduation-cap text-success"></i>
                                <strong>Education:</strong> {{ $job->minimum_qualification }}
                            </p>
                        @endif
                    </div>
                </div>

                @php
                    $hasApplied = false;
                    if(Session::has('candidate_logged_in')) {
                        $candidateCitizenship = DB::table('candidate_registration')
                            ->where('id', Session::get('candidate_id'))
                            ->value('citizenship_number');
                        
                        $hasApplied = DB::table('application_form')
                            ->where('job_posting_id', $job->id)
                            ->where('citizenship_number', $candidateCitizenship)
                            ->exists();
                    }
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
                        <button onclick="checkEligibilityAndApply({{ $job->id }})" 
                            class="btn btn-primary btn-lg" id="applyBtn">
                            <i class="fas fa-paper-plane"></i> Apply for This Position
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
            <div class="text-center mt-4 mb-4">
                <button onclick="checkEligibilityAndApply({{ $job->id }})" 
                    class="btn btn-primary btn-lg" id="applyBtnBottom">
                    <i class="fas fa-paper-plane"></i> Apply for This Position Now
                </button>
            </div>
        @endif
    </div>

    <!-- Eligibility Modal -->
    <div class="modal fade" id="eligibilityModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">
                        <i class="fas fa-exclamation-circle"></i> Not Eligible
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="eligibilityModalBody"></div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script>
    function checkEligibilityAndApply(jobId) {
        // Get all apply buttons
        const buttons = [
            document.getElementById('applyBtn'),
            document.getElementById('applyBtnBottom')
        ].filter(btn => btn !== null);

        const originalHtml = buttons[0]?.innerHTML || '';

        // Disable all buttons and show loading
        buttons.forEach(button => {
            button.disabled = true;
            button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Checking Eligibility...';
        });

        fetch(`/candidate/jobs/${jobId}/check-eligibility`)
            .then(response => response.json())
            .then(data => {
                if (data.eligible) {
                    // Redirect to application form immediately
                    window.location.href = `/candidate/jobs/${jobId}/applications/create`;
                } else {
                    // Show error modal with reasons
                    let errorHtml = '<div class="alert alert-danger"><strong>You are not eligible for this position due to the following reasons:</strong></div>';
                    errorHtml += '<ul class="text-start mb-0">';
                    data.errors.forEach(error => {
                        errorHtml += `<li class="mb-2">${error}</li>`;
                    });
                    errorHtml += '</ul>';
                    errorHtml += '<div class="alert alert-info mt-3 mb-0"><i class="fas fa-info-circle"></i> Please ensure your profile information is accurate and up-to-date.</div>';

                    showEligibilityModal(errorHtml);

                    // Re-enable buttons
                    buttons.forEach(button => {
                        button.disabled = false;
                        button.innerHTML = originalHtml;
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while checking eligibility. Please try again.');
                
                // Re-enable buttons
                buttons.forEach(button => {
                    button.disabled = false;
                    button.innerHTML = originalHtml;
                });
            });
    }

    function showEligibilityModal(content) {
        document.getElementById('eligibilityModalBody').innerHTML = content;
        const modal = new bootstrap.Modal(document.getElementById('eligibilityModal'));
        modal.show();
    }

    // Reset all button states when page loads/becomes visible
    function resetAllButtons() {
        const buttons = [
            document.getElementById('applyBtn'),
            document.getElementById('applyBtnBottom')
        ].filter(btn => btn !== null);

        buttons.forEach(button => {
            // Reset any stuck buttons
            if (button.innerHTML.includes('Checking') || 
                button.innerHTML.includes('Redirecting') || 
                button.innerHTML.includes('spinner')) {
                button.disabled = false;
                // Restore original text based on button ID
                if (button.id === 'applyBtn') {
                    button.innerHTML = '<i class="fas fa-paper-plane"></i> Apply for This Position';
                } else if (button.id === 'applyBtnBottom') {
                    button.innerHTML = '<i class="fas fa-paper-plane"></i> Apply for This Position Now';
                }
            }
        });
    }

    // Run when page loads
    document.addEventListener('DOMContentLoaded', resetAllButtons);
    
    // Run when page becomes visible again (e.g., using back button)
    document.addEventListener('visibilitychange', function() {
        if (!document.hidden) {
            resetAllButtons();
        }
    });
    
    // Also run on page show event (Firefox back button fix)
    window.addEventListener('pageshow', function(event) {
        if (event.persisted) {
            resetAllButtons();
        }
    });
</script>
@endsection