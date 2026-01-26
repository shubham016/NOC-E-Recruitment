@extends('layouts.app')

@section('title', 'Browse Jobs')

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
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="mb-1"><i class="fas fa-search"></i> Browse Vacancies</h2>
                <p class="text-muted mb-0">Find and apply for available positions</p>
            </div>
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

        <!-- Search & Filter -->
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <form method="GET" action="{{ route('candidate.jobs.index') }}">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <input type="text" name="search" class="form-control" placeholder="Search by Vacancy title..."
                                value="{{ request('search') }}">
                        </div>
                        <div class="col-md-3">
                            <select name="department" class="form-select">
                                <option value="">All Departments</option>
                                <option value="Engineering" {{ request('department') == 'Engineering' ? 'selected' : '' }}>
                                    Engineering</option>
                                <option value="Administration" {{ request('department') == 'Administration' ? 'selected' : '' }}>Administration</option>
                                <option value="Finance" {{ request('department') == 'Finance' ? 'selected' : '' }}>Finance
                                </option>
                                <option value="Human Resources" {{ request('department') == 'Human Resources' ? 'selected' : '' }}>Human Resources</option>
                                <option value="Operations" {{ request('department') == 'Operations' ? 'selected' : '' }}>
                                    Operations</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select name="location" class="form-select">
                                <option value="">All Locations</option>
                                <option value="Kathmandu" {{ request('location') == 'Kathmandu' ? 'selected' : '' }}>Kathmandu
                                </option>
                                <option value="Pokhara" {{ request('location') == 'Pokhara' ? 'selected' : '' }}>Pokhara
                                </option>
                                <option value="Lalitpur" {{ request('location') == 'Lalitpur' ? 'selected' : '' }}>Lalitpur
                                </option>
                                <option value="Bhaktapur" {{ request('location') == 'Bhaktapur' ? 'selected' : '' }}>Bhaktapur
                                </option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fas fa-search"></i> Search
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Job Listings -->
        @if($jobs->count() > 0)
            <div class="row">
                @foreach($jobs as $job)
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="card h-100 shadow-sm hover-card">
                            <div class="card-header bg-primary text-white">
                                <h5 class="mb-0">{{ $job->title }}</h5>
                            </div>
                            <div class="card-body">
                                <p class="mb-2">
                                    <i class="fas fa-building text-primary"></i>
                                    <strong>Department:</strong> {{ $job->service_group }}
                                </p>
                                <p class="mb-2">
                                    <i class="fas fa-map-marker-alt text-danger"></i>
                                    <strong>Location:</strong> {{ $job->location }}
                                </p>
                                <p class="mb-2">
                                    <i class="fas fa-briefcase text-success"></i>
                                    <strong>Type:</strong> {{ ucfirst($job->category) }}
                                </p>
                                <p class="mb-2">
                                    <i class="fas fa-users text-info"></i>
                                    <strong>Vacancies:</strong> {{ $job->number_of_posts}}
                                </p>
                                <p class="mb-2">
                                    <i class="fas fa-calendar text-warning"></i>
                                    <strong>Deadline:</strong>
                                    {{ \Carbon\Carbon::parse($job->application_deadline)->format('M d, Y') }}
                                </p>
                                <p class="mb-2">
                                    <i class="fas fa-star text-info"></i>
                                    <strong>Position:</strong> {{ $job->position_level}}
                                </p>
                                <p class="mb-2">
                                    <i class="bi bi-person-fill"></i>
                                    <strong>Minimum Age:</strong> {{ $job->minimum_age}}
                                </p>
                                <p class="mb-2">
                                    <i class="bi bi-broadcast-pin"></i>
                                    <strong>Advertisement Number:</strong> {{ $job->advertisement_no}}
                                </p>

                                @if($job->description)
                                    <p class="text-muted small mb-3">{{ Str::limit($job->description, 100) }}</p>
                                @endif

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

                                @if($hasApplied)
                                    <div class="alert alert-success py-2 mb-2">
                                        <i class="fas fa-check-circle"></i> Already Applied
                                    </div>
                                @endif
                            </div>
                            <div class="card-footer bg-light">
                                <div class="d-flex gap-2">
                                    <a href="{{ route('candidate.jobs.show', $job->id) }}"
                                        class="btn btn-outline-primary btn-sm flex-fill">
                                        <i class="fas fa-eye"></i> View Details
                                    </a>
                                    @if(!$hasApplied && $job->status === 'active')
                                        <button onclick="checkEligibilityAndApply({{ $job->id }})"
                                            class="btn btn-primary btn-sm flex-fill apply-btn-{{ $job->id }}">
                                            <i class="fas fa-paper-plane"></i> Apply Now
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-4">
                {{ $jobs->links() }}
            </div>
        @else
            <div class="card shadow-sm">
                <div class="card-body text-center py-5">
                    <i class="fas fa-inbox fa-4x text-muted mb-3"></i>
                    <h5 class="text-muted">No Vacancy Available</h5>
                    <p class="text-muted mb-0">There are no Vacancy postings matching your criteria at the moment.</p>
                </div>
            </div>
        @endif
    </div>

    <!-- Eligibility Modal -->
    <div class="modal fade" id="eligibilityModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">
                        <i class="fas fa-exclamation-circle"></i> 
                        <span id="eligibilityModalTitle">Not Eligible</span>
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

        <style>
            .hover-card {
                transition: transform 0.2s, box-shadow 0.2s;
            }

            .hover-card:hover {
                transform: translateY(-5px);
                box-shadow: 0 .5rem 1rem rgba(0, 0, 0, .15) !important;
            }
        </style>

        <script>
    function checkEligibilityAndApply(jobId) {
        // Get the button that was clicked
        const button = document.querySelector(`.apply-btn-${jobId}`);
        const originalHtml = button.innerHTML;
        
        // Disable button and show loading state
        button.disabled = true;
        button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Checking Eligibility...';

        fetch(`/candidate/jobs/${jobId}/check-eligibility`)
            .then(response => response.json())
            .then(data => {
                if (data.eligible) {
                    // Redirect immediately without changing button text
                    window.location.href = `/candidate/jobs/${jobId}/applications/create`;
                } else {
                    // Show error modal with reasons
                    let errorHtml = '<div class="alert alert-danger"><strong>You are not eligible for this position due to the following reasons:</strong></div>';
                    errorHtml += '<ul class="text-start mb-0">';
                    data.errors.forEach(error => {
                        errorHtml += `<li class="mb-2">${error}</li>`;
                    });
                    errorHtml += '</ul>';

                    showEligibilityModal(errorHtml);

                    // Reset button state
                    button.disabled = false;
                    button.innerHTML = originalHtml;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while checking eligibility. Please try again.');
                
                // Reset button state on error
                button.disabled = false;
                button.innerHTML = originalHtml;
            });
    }

    function showEligibilityModal(content) {
        document.getElementById('eligibilityModalBody').innerHTML = content;
        const modal = new bootstrap.Modal(document.getElementById('eligibilityModal'));
        modal.show();
    }

    // Reset all button states when page loads/becomes visible
    function resetAllButtons() {
        document.querySelectorAll('[class*="apply-btn-"]').forEach(button => {
            // Reset any stuck buttons
            if (button.innerHTML.includes('Checking') || 
                button.innerHTML.includes('Redirecting') || 
                button.innerHTML.includes('Eligible')) {
                button.disabled = false;
                button.innerHTML = '<i class="fas fa-paper-plane"></i> Apply Now';
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