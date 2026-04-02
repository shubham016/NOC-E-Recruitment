@extends('layouts.dashboard')

@section('title', 'Application Details')

@section('portal-name', 'Admin Portal')
@section('brand-icon', 'bi bi-shield-check')
@section('dashboard-route', route('admin.dashboard'))
@section('user-name', Auth::guard('admin')->user()->name)
@section('user-role', 'System Administrator')
@section('user-initial', strtoupper(substr(Auth::guard('admin')->user()->name, 0, 1)))
@section('logout-route', route('admin.logout'))

@section('sidebar-menu')
    @include('admin.partials.sidebar')
@endsection

@section('custom-styles')
<style>
    /* Modern Professional Styling */
    .detail-card {
        background: white;
        border-radius: 8px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        margin-bottom: 1.5rem;
    }

    .detail-card-header {
        background: linear-gradient(135deg, #c9a84c 0%, #a07828 100%);
        color: white;
        padding: 1rem 1.5rem;
        border-radius: 8px 8px 0 0;
        font-weight: 600;
        font-size: 1.1rem;
    }

    .detail-card-body {
        padding: 1.5rem;
    }

    .detail-table {
        width: 100%;
        margin: 0;
    }

    .detail-table tr {
        border-bottom: 1px solid #e5e7eb;
    }

    .detail-table tr:last-child {
        border-bottom: none;
    }

    .detail-table td {
        padding: 0.75rem 1rem;
        vertical-align: top;
    }

    .detail-table td:first-child {
        font-weight: 600;
        color: #374151;
        width: 200px;
    }

    .detail-table td:last-child {
        color: #1f2937;
    }

    .candidate-header {
        background: linear-gradient(135deg, #c9a84c 0%, #a07828 100%);
        color: white;
        padding: 2rem;
        border-radius: 8px;
        margin-bottom: 2rem;
    }

    .status-badge {
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-weight: 600;
        font-size: 0.875rem;
    }

    .status-pending { background: #fef3c7; color: #92400e; }
    .status-approved { background: #d1fae5; color: #065f46; }
    .status-rejected { background: #fee2e2; color: #991b1b; }
    .status-under_review { background: #dbeafe; color: #1e40af; }
</style>
@endsection

@section('content')
<div class="container-fluid px-4 py-4">
    <!-- Back Button -->
    <a href="{{ route('admin.applications.index') }}" class="btn btn-outline-secondary mb-3">
        <i class="bi bi-arrow-left"></i> Back to Applications
    </a>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Candidate Header -->
    <div class="candidate-header">
        <div class="row align-items-center">
            <div class="col-auto">
                @if($application->passport_size_photo)
                    <img src="{{ asset('storage/' . $application->passport_size_photo) }}"
                         alt="Photo"
                         style="width: 100px; height: 100px; border-radius: 50%; border: 4px solid white; object-fit: cover;">
                @else
                    <div style="width: 100px; height: 100px; border-radius: 50%; background: white; color: #a07828; display: flex; align-items: center; justify-content: center; font-size: 2.5rem; font-weight: bold;">
                        {{ strtoupper(substr($application->name_english ?? 'U', 0, 1)) }}
                    </div>
                @endif
            </div>
            <div class="col">
                <h2 class="mb-2">{{ $application->name_english }}</h2>
                <div class="d-flex gap-3 flex-wrap">
                    <span><i class="bi bi-envelope me-2"></i>{{ $application->email }}</span>
                    <span><i class="bi bi-telephone me-2"></i>{{ $application->phone }}</span>
                    <span><i class="bi bi-calendar me-2"></i>{{ $application->created_at->format('M d, Y') }}</span>
                </div>
            </div>
            <div class="col-auto">
                <span class="status-badge status-{{ $application->status }}">
                    {{ ucfirst(str_replace('_', ' ', $application->status)) }}
                </span>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="mb-4 d-flex gap-2">
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#statusModal">
            <i class="bi bi-pencil-square"></i> Update Status
        </button>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#assignModal">
            <i class="bi bi-person-plus"></i> Assign Reviewer
        </button>
        <form action="{{ route('admin.applications.destroy', $application->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?')">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger">
                <i class="bi bi-trash"></i> Delete
            </button>
        </form>
    </div>

    <div class="row">
        <!-- Left Column -->
        <div class="col-lg-8">
            <!-- Vacancy Information -->
            <div class="detail-card">
                <div class="detail-card-header">
                    <i class="bi bi-briefcase me-2"></i>Vacancy Information
                </div>
                <div class="detail-card-body">
                    <table class="detail-table">
                        <tr>
                            <td>Vacancy Title</td>
                            <td>{{ $application->vacancy->title }}</td>
                        </tr>
                        <tr>
                            <td>Advertisement No.</td>
                            <td>{{ $application->vacancy->advertisement_no }}</td>
                        </tr>
                        <tr>
                            <td>Department</td>
                            <td>{{ $application->vacancy->department }}</td>
                        </tr>
                        <tr>
                            <td>Location</td>
                            <td>{{ $application->vacancy->location }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            <!-- Personal Information -->
            <div class="detail-card">
                <div class="detail-card-header">
                    <i class="bi bi-person me-2"></i>Personal Information
                </div>
                <div class="detail-card-body">
                    <table class="detail-table">
                        <tr>
                            <td>Name (English)</td>
                            <td>{{ $application->name_english }}</td>
                        </tr>
                        <tr>
                            <td>Name (Nepali)</td>
                            <td>{{ $application->name_nepali ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td>Date of Birth (BS)</td>
                            <td>{{ $application->birth_date_bs ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td>Date of Birth (AD)</td>
                            <td>{{ $application->birth_date_ad ? \Carbon\Carbon::parse($application->birth_date_ad)->format('Y-m-d') : 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td>Age</td>
                            <td>{{ $application->age ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td>Gender</td>
                            <td>{{ ucfirst($application->gender) }}</td>
                        </tr>
                        <tr>
                            <td>Citizenship Number</td>
                            <td>{{ $application->citizenship_number }}</td>
                        </tr>
                        <tr>
                            <td>Email</td>
                            <td>{{ $application->email }}</td>
                        </tr>
                        <tr>
                            <td>Phone</td>
                            <td>{{ $application->phone }}</td>
                        </tr>
                        <tr>
                            <td>Permanent Address</td>
                            <td>
                                @if($application->permanent_province || $application->permanent_district)
                                    {{ $application->permanent_municipality }}, Ward-{{ $application->permanent_ward }},
                                    {{ $application->permanent_district }}, {{ $application->permanent_province }}
                                @else
                                    N/A
                                @endif
                            </td>
                        </tr>
                    </table>
                </div>
            </div>

            <!-- Education -->
            <div class="detail-card">
                <div class="detail-card-header">
                    <i class="bi bi-mortarboard me-2"></i>Educational Qualification
                </div>
                <div class="detail-card-body">
                    <table class="detail-table">
                        <tr>
                            <td>Education Level</td>
                            <td>{{ $application->education_level ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td>Field of Study</td>
                            <td>{{ $application->field_of_study ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td>Institution Name</td>
                            <td>{{ $application->institution_name ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td>Year of Graduation</td>
                            <td>{{ $application->graduation_year ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td>Work Experience</td>
                            <td>{{ $application->has_work_experience ?? 'N/A' }}</td>
                        </tr>
                        @if($application->has_work_experience == 'Yes')
                        <tr>
                            <td>Years of Experience</td>
                            <td>{{ $application->years_of_experience ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td>Previous Organization</td>
                            <td>{{ $application->previous_organization ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td>Previous Position</td>
                            <td>{{ $application->previous_position ?? 'N/A' }}</td>
                        </tr>
                        @endif
                    </table>
                </div>
            </div>
        </div>

        <!-- Right Column -->
        <div class="col-lg-4">
            <!-- Application Status -->
            <div class="detail-card">
                <div class="detail-card-header">
                    <i class="bi bi-info-circle me-2"></i>Application Status
                </div>
                <div class="detail-card-body">
                    <table class="detail-table">
                        <tr>
                            <td>Status</td>
                            <td><span class="status-badge status-{{ $application->status }}">{{ ucfirst(str_replace('_', ' ', $application->status)) }}</span></td>
                        </tr>
                        <tr>
                            <td>Applied On</td>
                            <td>
                                <div class="nepali-date-bs" data-ad-date="{{ $application->created_at->format('Y-m-d') }}">
                                    <i class="bi bi-hourglass-split"></i> Converting...
                                </div>
                                <small style="color: #718096;">{{ $application->created_at->format('M d, Y h:i A') }}</small>
                            </td>
                        </tr>
                        <tr>
                            <td>Last Updated</td>
                            <td>
                                <div class="nepali-date-bs" data-ad-date="{{ $application->updated_at->format('Y-m-d') }}">
                                    <i class="bi bi-hourglass-split"></i> Converting...
                                </div>
                                <small style="color: #718096;">{{ $application->updated_at->format('M d, Y h:i A') }}</small>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>

            <!-- Reviewer Information -->
            <div class="detail-card">
                <div class="detail-card-header">
                    <i class="bi bi-person-check me-2"></i>Reviewer
                </div>
                <div class="detail-card-body">
                    @if($application->reviewer)
                        <table class="detail-table">
                            <tr>
                                <td>Name</td>
                                <td>{{ $application->reviewer->name }}</td>
                            </tr>
                            <tr>
                                <td>Email</td>
                                <td>{{ $application->reviewer->email }}</td>
                            </tr>
                        </table>
                    @else
                        <p class="text-muted mb-0">No reviewer assigned</p>
                    @endif
                </div>
            </div>

            <!-- Documents -->
            <div class="detail-card">
                <div class="detail-card-header">
                    <i class="bi bi-file-earmark-text me-2"></i>Documents
                </div>
                <div class="detail-card-body">
                    <div class="d-grid gap-2">
                        @if($application->passport_size_photo)
                            <a href="{{ asset('storage/' . $application->passport_size_photo) }}" target="_blank" class="btn btn-outline-primary btn-sm">
                                <i class="bi bi-image"></i> View Photo
                            </a>
                        @endif
                        @if($application->citizenship_front)
                            <a href="{{ asset('storage/' . $application->citizenship_front) }}" target="_blank" class="btn btn-outline-primary btn-sm">
                                <i class="bi bi-file-pdf"></i> Citizenship (Front)
                            </a>
                        @endif
                        @if($application->citizenship_back)
                            <a href="{{ asset('storage/' . $application->citizenship_back) }}" target="_blank" class="btn btn-outline-primary btn-sm">
                                <i class="bi bi-file-pdf"></i> Citizenship (Back)
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Status Update Modal -->
<div class="modal fade" id="statusModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Update Application Status</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.applications.updateStatus', $application->id) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select" required>
                            <option value="pending" {{ $application->status == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="under_review" {{ $application->status == 'under_review' ? 'selected' : '' }}>Under Review</option>
                            <option value="approved" {{ $application->status == 'approved' ? 'selected' : '' }}>Approved</option>
                            <option value="rejected" {{ $application->status == 'rejected' ? 'selected' : '' }}>Rejected</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Status</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Assign Reviewer Modal -->
<div class="modal fade" id="assignModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Assign Reviewer</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.applications.assignReviewer', $application->id) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Select Reviewer</label>
                        <select name="reviewer_id" class="form-select" required>
                            <option value="">-- Select Reviewer --</option>
                            @foreach($reviewers ?? [] as $reviewer)
                                <option value="{{ $reviewer->id }}" {{ $application->reviewer_id == $reviewer->id ? 'selected' : '' }}>
                                    {{ $reviewer->name }} ({{ $reviewer->email }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Assign</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
    <script>
        // Convert English numerals to Nepali numerals
        function englishToNepali(str) {
            if (!str) return str;
            const map = { '0': '०', '1': '१', '2': '२', '3': '३', '4': '४', '5': '५', '6': '६', '7': '७', '8': '८', '9': '९' };
            return str.replace(/[0-9]/g, d => map[d]);
        }

        document.addEventListener('DOMContentLoaded', function () {
            console.log('🔧 Initializing Nepali date conversion for application...');

            // Wait for converter to be ready
            function waitForConverter() {
                if (!window.nepaliLibrariesReady || typeof window.adToBS !== 'function') {
                    setTimeout(waitForConverter, 100);
                    return;
                }

                console.log('✅ Converter ready, converting dates...');
                convertAllDates();
            }

            function convertAllDates() {
                // Find all elements with Nepali date class
                const dateElements = document.querySelectorAll('.nepali-date-bs');

                console.log(`📅 Found ${dateElements.length} dates to convert`);

                dateElements.forEach((element, index) => {
                    const adDate = element.getAttribute('data-ad-date');

                    if (adDate) {
                        try {
                            // Convert AD to BS (returns English numerals like 2082-11-05)
                            const bsDate = window.adToBS(adDate);

                            if (bsDate) {
                                // Convert to Nepali numerals (२०८२-११-०५)
                                const bsNepali = englishToNepali(bsDate);

                                // Update the element with Nepali numeral date
                                element.innerHTML = `${bsNepali}`;
                                console.log(`✅ Date ${index + 1}: ${adDate} → ${bsDate} → ${bsNepali}`);
                            } else {
                                element.innerHTML = '<i class="bi bi-exclamation-circle"></i> Error';
                                element.style.color = '#f56565';
                            }
                        } catch (error) {
                            console.error(`❌ Error converting date ${adDate}:`, error);
                            element.innerHTML = '<i class="bi bi-x-circle"></i> Error';
                            element.style.color = '#f56565';
                        }
                    }
                });

                console.log('✅ All dates converted successfully!');
            }

            // Start the conversion process
            waitForConverter();
        });
    </script>
@endsection
