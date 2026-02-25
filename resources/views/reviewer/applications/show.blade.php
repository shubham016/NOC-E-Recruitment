@extends('layouts.dashboard')

@section('title', 'Review Application')

@section('portal-name', 'Reviewer Portal')
@section('brand-icon', 'bi bi-clipboard-check')
@section('dashboard-route', route('reviewer.dashboard'))
@section('user-name', Auth::guard('reviewer')->user()->name)
@section('user-role', 'Application Reviewer')
@section('user-initial', strtoupper(substr(Auth::guard('reviewer')->user()->name, 0, 1)))
@section('logout-route', route('reviewer.logout'))

@section('sidebar-menu')
    <a href="{{ route('reviewer.dashboard') }}" class="sidebar-menu-item">
        <i class="bi bi-speedometer2"></i>
        <span>Dashboard</span>
    </a>
    <a href="{{ route('reviewer.applications.index') }}" class="sidebar-menu-item active">
        <i class="bi bi-hourglass-split"></i>
        <span>Pending Reviews</span>
    </a>
    <a href="#" class="sidebar-menu-item">
        <i class="bi bi-check-circle"></i>
        <span>Reviewed Applications</span>
    </a>
    <a href="#" class="sidebar-menu-item">
        <i class="bi bi-star"></i>
        <span>Shortlisted</span>
    </a>
    <a href="#" class="sidebar-menu-item">
        <i class="bi bi-x-circle"></i>
        <span>Rejected</span>
    </a>
@endsection

@section('custom-styles')
<style>
    .review-header {
        background: linear-gradient(135deg, #64748b 0%, #475569 100%);
        border-radius: 12px;
        padding: 2rem;
        color: white;
        margin-bottom: 2rem;
        box-shadow: 0 4px 12px rgba(100, 116, 139, 0.3);
    }

    .info-card {
        background: white;
        border-radius: 10px;
        padding: 1.5rem;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        border: 1px solid #e5e7eb;
        margin-bottom: 1.5rem;
    }

    .info-card h5 {
        color: #64748b;
        font-weight: 700;
        border-bottom: 2px solid #e5e7eb;
        padding-bottom: 0.75rem;
        margin-bottom: 1rem;
    }

    .info-row {
        display: flex;
        padding: 0.75rem 0;
        border-bottom: 1px solid #f3f4f6;
    }

    .info-row:last-child {
        border-bottom: none;
    }

    .info-label {
        font-weight: 600;
        color: #6b7280;
        width: 200px;
        flex-shrink: 0;
    }

    .info-value {
        color: #1f2937;
        flex: 1;
    }

    .review-actions {
        position: sticky;
        top: 20px;
    }

    .status-badge {
        padding: 0.5rem 1rem;
        border-radius: 6px;
        font-weight: 600;
        display: inline-block;
    }

    .document-link {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        background: #f3f4f6;
        border-radius: 6px;
        text-decoration: none;
        color: #1f2937;
        transition: all 0.2s;
    }

    .document-link:hover {
        background: #e5e7eb;
        transform: translateY(-2px);
    }
</style>
@endsection

@section('content')
<div class="container-fluid px-4 py-4">
    <!-- Page Header -->
    <div class="review-header">
        <div class="d-flex justify-content-between align-items-start">
            <div>
                <a href="{{ route('reviewer.applications.index') }}" class="text-white text-decoration-none mb-2 d-inline-block opacity-75">
                    <i class="bi bi-arrow-left-circle me-2"></i>Back to Applications
                </a>
                <h2 class="mb-1 fw-bold">Application Review</h2>
                <p class="mb-0 opacity-90">Candidate: {{ $application->name_english ?? 'N/A' }}</p>
            </div>
            <div>
                @php
                    $statusColors = [
                        'pending' => 'bg-warning text-dark',
                        'approved' => 'bg-info text-white',
                        'shortlisted' => 'bg-success text-white',
                        'rejected' => 'bg-danger text-white',
                        'selected' => 'bg-primary text-white',
                    ];
                    $statusColor = $statusColors[$application->status] ?? 'bg-secondary text-white';
                @endphp
                <span class="status-badge {{ $statusColor }} fs-5">
                    {{ ucfirst($application->status) }}
                </span>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Main Content -->
        <div class="col-lg-8">
            <!-- Job Information -->
            <div class="info-card">
                <h5><i class="bi bi-briefcase me-2"></i>Job Information</h5>
                <div class="info-row">
                    <div class="info-label">Position:</div>
                    <div class="info-value"><strong>{{ $application->jobPosting->title ?? 'N/A' }}</strong></div>
                </div>
                <div class="info-row">
                    <div class="info-label">Advertisement No:</div>
                    <div class="info-value">{{ $application->advertisement_no ?? 'N/A' }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Department:</div>
                    <div class="info-value">{{ $application->jobPosting->department ?? 'N/A' }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Category:</div>
                    <div class="info-value">
                        <span class="badge bg-info">{{ ucfirst($application->jobPosting->category ?? 'N/A') }}</span>
                    </div>
                </div>
                <div class="info-row">
                    <div class="info-label">Deadline:</div>
                    <div class="info-value text-danger fw-bold">
                        {{ $application->jobPosting->deadline->format('F d, Y') ?? 'N/A' }}
                    </div>
                </div>
            </div>

            <!-- Personal Information -->
            <div class="info-card">
                <h5><i class="bi bi-person me-2"></i>Personal Information</h5>
                <div class="info-row">
                    <div class="info-label">Name (English):</div>
                    <div class="info-value"><strong>{{ $application->name_english ?? 'N/A' }}</strong></div>
                </div>
                <div class="info-row">
                    <div class="info-label">Name (Nepali):</div>
                    <div class="info-value">{{ $application->name_nepali ?? 'N/A' }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Email:</div>
                    <div class="info-value">{{ $application->email ?? 'N/A' }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Phone:</div>
                    <div class="info-value">{{ $application->phone ?? 'N/A' }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Birth Date:</div>
                    <div class="info-value">{{ $application->birth_date_ad ?? 'N/A' }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Age:</div>
                    <div class="info-value">{{ $application->age ?? 'N/A' }} years</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Gender:</div>
                    <div class="info-value">{{ ucfirst($application->gender ?? 'N/A') }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Citizenship Number:</div>
                    <div class="info-value">{{ $application->citizenship_number ?? 'N/A' }}</div>
                </div>
            </div>

            <!-- Education -->
            <div class="info-card">
                <h5><i class="bi bi-mortarboard me-2"></i>Educational Background</h5>
                <div class="info-row">
                    <div class="info-label">Education Level:</div>
                    <div class="info-value">{{ $application->education_level ?? 'N/A' }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Field of Study:</div>
                    <div class="info-value">{{ $application->field_of_study ?? 'N/A' }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Institution:</div>
                    <div class="info-value">{{ $application->institution_name ?? 'N/A' }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Graduation Year:</div>
                    <div class="info-value">{{ $application->graduation_year ?? 'N/A' }}</div>
                </div>
            </div>

            <!-- Work Experience -->
            @if($application->has_work_experience === 'yes')
            <div class="info-card">
                <h5><i class="bi bi-briefcase-fill me-2"></i>Work Experience</h5>
                <div class="info-row">
                    <div class="info-label">Previous Organization:</div>
                    <div class="info-value">{{ $application->previous_organization ?? 'N/A' }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Previous Position:</div>
                    <div class="info-value">{{ $application->previous_position ?? 'N/A' }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Years of Experience:</div>
                    <div class="info-value">{{ $application->years_of_experience ?? 'N/A' }} years</div>
                </div>
            </div>
            @endif

            <!-- Documents -->
            <div class="info-card">
                <h5><i class="bi bi-paperclip me-2"></i>Documents</h5>
                <div class="d-flex flex-wrap gap-2">
                    @if($application->resume)
                        <a href="{{ Storage::url($application->resume) }}" target="_blank" class="document-link">
                            <i class="bi bi-file-earmark-text"></i>
                            Resume
                        </a>
                    @endif
                    @if($application->citizenship_certificate)
                        <a href="{{ Storage::url($application->citizenship_certificate) }}" target="_blank" class="document-link">
                            <i class="bi bi-file-earmark-pdf"></i>
                            Citizenship
                        </a>
                    @endif
                    @if($application->educational_certificates)
                        <a href="{{ Storage::url($application->educational_certificates) }}" target="_blank" class="document-link">
                            <i class="bi bi-file-earmark-pdf"></i>
                            Certificates
                        </a>
                    @endif
                    @if($application->passport_photo)
                        <a href="{{ Storage::url($application->passport_photo) }}" target="_blank" class="document-link">
                            <i class="bi bi-image"></i>
                            Photo
                        </a>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar - Review Actions -->
        <div class="col-lg-4">
            <div class="review-actions">
                <!-- Review Status Form -->
                <div class="info-card">
                    <h5><i class="bi bi-clipboard-check me-2"></i>Review Action</h5>

                    <form action="{{ route('reviewer.applications.updateStatus', $application->id) }}" method="POST" id="reviewForm">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label fw-bold">Status</label>
                            <select name="status" class="form-select" required>
                                <option value="">Select Status...</option>
                                <option value="approved" {{ $application->status == 'approved' ? 'selected' : '' }}>Approved</option>
                                <option value="shortlisted" {{ $application->status == 'shortlisted' ? 'selected' : '' }}>Shortlisted</option>
                                <option value="rejected" {{ $application->status == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                <option value="selected" {{ $application->status == 'selected' ? 'selected' : '' }}>Selected</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Reviewer Notes</label>
                            <textarea name="reviewer_notes" class="form-control" rows="5" placeholder="Add your review comments...">{{ $application->reviewer_notes }}</textarea>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle me-2"></i>Submit Review
                            </button>
                            <a href="{{ route('reviewer.applications.index') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left me-2"></i>Back to List
                            </a>
                        </div>
                    </form>
                </div>

                <!-- Review History -->
                @if($application->reviewed_at)
                <div class="info-card mt-3">
                    <h5><i class="bi bi-clock-history me-2"></i>Review History</h5>
                    <div class="info-row">
                        <div class="info-label">Reviewed By:</div>
                        <div class="info-value">{{ $application->reviewer->name ?? 'N/A' }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Reviewed At:</div>
                        <div class="info-value">{{ $application->reviewed_at->format('M d, Y h:i A') }}</div>
                    </div>
                </div>
                @endif

                <!-- Payment Status -->
                @if($application->payment)
                <div class="info-card mt-3">
                    <h5><i class="bi bi-credit-card me-2"></i>Payment Status</h5>
                    <div class="text-center">
                        <span class="badge bg-{{ $application->payment->status == 'completed' ? 'success' : 'warning' }} fs-6 px-3 py-2">
                            <i class="bi bi-{{ $application->payment->status == 'completed' ? 'check-circle' : 'hourglass-split' }}"></i>
                            {{ ucfirst($application->payment->status) }}
                        </span>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.getElementById('reviewForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const formData = new FormData(this);
    const status = formData.get('status');
    const notes = formData.get('reviewer_notes');

    if (confirm(`Are you sure you want to mark this application as "${status.toUpperCase()}"?`)) {
        fetch(this.action, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                'Accept': 'application/json',
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                status: status,
                reviewer_notes: notes
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('✅ ' + data.message);
                window.location.reload();
            }
        })
        .catch(error => {
            alert('❌ Error updating status. Please try again.');
            console.error('Error:', error);
        });
    }
});
</script>
@endsection
