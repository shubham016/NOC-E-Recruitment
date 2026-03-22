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
<div class="page-header">
    <h1 class="page-title">
        <i class="bi bi-search text-dark"></i> Browse Vacancies
    </h1>
    <p class="page-subtitle">Find and apply for available positions</p>
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
                <div class="col-md-2">
                    <button type="submit" class="btn btn-light w-100">
                        <i class="fas fa-search"></i> Search
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

@if($jobs->count() > 0)
    <div class="card shadow-sm">
        <div class="card-header bg-light text-black">
            <h5 class="mb-0">
                <i class="bi bi-table"></i> Available Vacancies
            </h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-bordered align-middle">
                    <thead class="table-light">
                        <tr>
                            <th class="text-center">S.N.</th>
                            <th>Job Title</th>
                            <th>Department</th>
                            <th>Category</th>
                            <th class="text-center">Vacancies</th>
                            <th>Position Level</th>
                            <th>Advertisement No.</th>
                            <th>Deadline</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($jobs as $index => $job)
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
                        <tr>
                            <td class="text-center">{{ $jobs->firstItem() + $index }}</td>
                            <td>
                                <strong class="text-dark">{{ $job->title }}</strong>
                            </td>
                            <td>{{ $job->service_group }}</td>
                            <td>
                                <span class="text-dark">
                                    {{ ucfirst($job->category) }}
                                </span>
                            </td>
                            <td class="text-center">
                                <strong class="text-dark">{{ $job->number_of_posts }}</strong>
                            </td>
                            <td>{{ $job->position_level }}</td>
                            <td>{{ $job->advertisement_no }}</td>
                            <td>
                                <div>
                                    <small class="text-danger d-block fw-semibold nepali-date-bs"
                                        data-ad-date="{{ \Carbon\Carbon::parse($job->deadline)->format('Y-m-d') }}">
                                        <i class="bi bi-hourglass-split"></i> ...
                                    </small>
                                    <small class="text-muted">{{ \Carbon\Carbon::parse($job->deadline)->format('M d, Y') }}</small>
                                </div>
                            </td>
                            <td class="text-center">
                                @if($hasApplied)
                                    <span class="badge bg-secondary">
                                        <i class="fas fa-check-circle"></i> Applied
                                    </span>
                                @elseif($job->status === 'active')
                                    <span class="badge bg-success">
                                        <i class="bi bi-circle-fill"></i> Active
                                    </span>
                                @else
                                    <span class="badge bg-secondary">
                                        <i class="bi bi-circle"></i> Closed
                                    </span>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="d-flex gap-1 justify-content-center">
                                    <a href="{{ route('candidate.jobs.show', $job->id) }}" 
                                       class="btn btn-sm btn-outline-danger" 
                                       title="View Details">
                                        <i class="bi bi-eye"></i> Details
                                    </a>
                                    @if(!$hasApplied && $job->status === 'active')
                                        <button onclick="checkEligibilityAndApply({{ $job->id }})"
                                            class="btn btn-sm btn-danger apply-btn-{{ $job->id }}"
                                            title="Apply Now">
                                            <i class="fas fa-paper-plane"></i> Apply
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Pagination -->
    <div class="d-flex justify-content-center mt-4">
        {{ $jobs->links() }}
    </div>
@else
    <div class="card shadow-sm">
        <div class="card-body text-center py-5">
            <i class="bi bi-inbox display-1 text-muted mb-3"></i>
            <h4 class="text-muted">No Vacancies Available</h4>
            <p class="text-secondary">There are no vacancy postings matching your criteria at the moment.</p>
            <a href="{{ route('candidate.dashboard') }}" class="btn btn-danger mt-3">
                <i class="bi bi-house-door"></i> Back to Dashboard
            </a>
        </div>
    </div>
@endif

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

<script>
function checkEligibilityAndApply(jobId) {
    // Get the button that was clicked
    const button = document.querySelector(`.apply-btn-${jobId}`);
    const originalHtml = button.innerHTML;
    
    // Disable button and show loading state
    button.disabled = true;
    button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Checking...';

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
            button.innerHTML = '<i class="fas fa-paper-plane"></i> Apply';
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

// Convert AD dates to Nepali BS
(function() {
    function englishToNepali(str) {
        const map = {'0':'०','1':'१','2':'२','3':'३','4':'४','5':'५','6':'६','7':'७','8':'८','9':'९'};
        return str.replace(/[0-9]/g, d => map[d]);
    }

    function convertAllDates() {
        document.querySelectorAll('.nepali-date-bs').forEach(function(el) {
            var adDate = el.getAttribute('data-ad-date');
            if (adDate) {
                try {
                    var bsDate = window.adToBS(adDate);
                    if (bsDate) {
                        el.textContent = englishToNepali(bsDate);
                    } else {
                        el.textContent = '';
                    }
                } catch(e) {
                    el.textContent = '';
                }
            }
        });
    }

    function waitAndConvert() {
        if (!window.nepaliLibrariesReady || typeof window.adToBS !== 'function') {
            setTimeout(waitAndConvert, 100);
            return;
        }
        convertAllDates();
    }

    document.addEventListener('DOMContentLoaded', waitAndConvert);
})();
</script>
@endsection