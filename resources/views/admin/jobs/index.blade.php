@extends('layouts.dashboard')

@section('title', 'Post New Vacancy')

@section('portal-name', 'Admin Portal')
@section('brand-icon', 'bi bi-shield-check')
@section('dashboard-route', route('admin.dashboard'))
@section('user-name', Auth::guard('admin')->user()?->name ?? 'Guest')
@section('user-role', 'System Administrator')
@section('user-initial', Auth::guard('admin')->user() ? strtoupper(substr(Auth::guard('admin')->user()->name, 0, 1)) : 'G')
@section('logout-route', route('admin.logout'))

@section('sidebar-menu')
    <a href="{{ route('admin.dashboard') }}" class="sidebar-menu-item">
        <i class="bi bi-speedometer2"></i>
        <span>Dashboard</span>
    </a>
    <a href="{{ route('admin.jobs.create') }}" class="sidebar-menu-item active">
        <i class="bi bi-briefcase"></i>
        <span>Post Vacancy</span>
        <span class="badge bg-primary ms-auto">{{ $stats['total'] }}</span>
    </a>
    <a href="{{ route('admin.applications.index') }}" class="sidebar-menu-item">
        <i class="bi bi-file-earmark-text"></i>
        <span>Applications</span>
    </a>
    <a href="#" class="sidebar-menu-item">
        <i class="bi bi-people"></i>
        <span>Candidates</span>
    </a>
    <a href="#" class="sidebar-menu-item">
        <i class="bi bi-person-badge"></i>
        <span>Reviewers</span>
    </a>
    <a href="#" class="sidebar-menu-item">
        <i class="bi bi-bar-chart"></i>
        <span>Reports</span>
    </a>
    <a href="#" class="sidebar-menu-item">
        <i class="bi bi-gear"></i>
        <span>Settings</span>
    </a>
@endsection

@section('custom-styles')
    <style>
        .page-header {
            background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
            border-radius: 12px;
            padding: 1.5rem;
            color: white;
            margin-bottom: 1.5rem;
            box-shadow: 0 4px 12px rgba(99, 102, 241, 0.2);
        }

        .stat-card {
            background: white;
            border-radius: 10px;
            padding: 1.25rem;
            transition: all 0.3s ease;
            border: 1px solid #e5e7eb;
            height: 100%;
        }

        .stat-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        }

        .job-row {
            transition: all 0.3s ease;
            border-left: 3px solid transparent;
        }

        .job-row:hover {
            background-color: #f8fafc;
            border-left-color: #6366f1;
            transform: translateX(2px);
        }

        .job-row.draft {
            border-left-color: #9ca3af;
            background: linear-gradient(to right, rgba(156, 163, 175, 0.02) 0%, white 100%);
        }

        .job-row.active {
            border-left-color: #10b981;
            background: linear-gradient(to right, rgba(16, 185, 129, 0.02) 0%, white 100%);
        }

        .job-row.closed {
            border-left-color: #ef4444;
            background: linear-gradient(to right, rgba(239, 68, 68, 0.02) 0%, white 100%);
        }

        /* Modern Table */
        .modern-table {
            width: 100%;
            border-collapse: collapse;
        }

        .modern-table thead {
            background: #f9fafb;
        }

        .modern-table thead th {
            padding: 1.25rem 1.5rem;
            font-weight: 700;
            color: #000;
            font-size: 0.875rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border: 1px solid #000;
            white-space: nowrap;
            background: linear-gradient(135deg, #f9fafb 0%, #f3f4f6 100%);
            text-align: center;
        }

        .modern-table tbody td {
            color: #000;
            border: 1px solid #060606;
            vertical-align: middle;
        }

        .modern-table tbody tr {
            transition: all 0.2s;
        }

        .modern-table tbody tr:hover {
            background: #f8fafc;
        }

        .modern-table tbody tr:last-child td {
            border-bottom: none;
        }

        .nepali-date-loading {
            color: #9ca3af;
            font-style: italic;
        }
    </style>
@endsection

@section('content')
    <!-- Page Header -->
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h4 class="fw-bold mb-1">
                    <i class="bi bi-briefcase me-2"></i>Vacancy Management
                </h4>
                <p class="mb-0 opacity-90">Create and manage Vacancies</p>
            </div>
            <a href="{{ route('admin.jobs.create') }}" class="btn btn-light">
                <i class="bi bi-plus-circle me-2"></i>Post New Vacancy
            </a>
        </div>
    </div>

    <!-- Success/Error Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Statistics Cards -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="stat-card">
                <div class="d-flex align-items-center gap-3">
                    <div class="bg-primary bg-opacity-10 rounded-3 p-3">
                        <i class="bi bi-briefcase-fill text-primary fs-4"></i>
                    </div>
                    <div>
                        <h3 class="fw-bold mb-0">{{ $stats['total'] }}</h3>
                        <small class="text-muted">Total Jobs</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="d-flex align-items-center gap-3">
                    <div class="bg-success bg-opacity-10 rounded-3 p-3">
                        <i class="bi bi-check-circle-fill text-success fs-4"></i>
                    </div>
                    <div>
                        <h3 class="fw-bold mb-0">{{ $stats['active'] }}</h3>
                        <small class="text-muted">Active Jobs</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="d-flex align-items-center gap-3">
                    <div class="bg-danger bg-opacity-10 rounded-3 p-3">
                        <i class="bi bi-x-circle-fill text-danger fs-4"></i>
                    </div>
                    <div>
                        <h3 class="fw-bold mb-0">{{ $stats['closed'] }}</h3>
                        <small class="text-muted">Closed Jobs</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="d-flex align-items-center gap-3">
                    <div class="bg-warning bg-opacity-10 rounded-3 p-3">
                        <i class="bi bi-clock-fill text-warning fs-4"></i>
                    </div>
                    <div>
                        <h3 class="fw-bold mb-0">{{ $stats['draft'] }}</h3>
                        <small class="text-muted">Draft Jobs</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Search and Filter -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.jobs.index') }}">
                <div class="row g-3">
                    <div class="col-md-4">
                        <input type="text" class="form-control" name="search" placeholder="Search Vacancies..."
                            value="{{ request('search') }}">
                    </div>
                    <div class="col-md-4">
                        <select class="form-select" name="status">
                            <option value="">All Status</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="closed" {{ request('status') == 'closed' ? 'selected' : '' }}>Closed</option>
                            <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                        </select>
                    </div>
                    {{-- <div class="col-md-2">
                        <select class="form-select" name="job_type">
                            <option value="">All Types</option>
                            <option value="permanent" {{ request('job_type') == 'permanent' ? 'selected' : '' }}>Permanent
                            </option>
                            <option value="temporary" {{ request('job_type') == 'temporary' ? 'selected' : '' }}>Temporary
                            </option>
                            <option value="contract" {{ request('job_type') == 'contract' ? 'selected' : '' }}>Contract
                            </option>
                            <option value="internship" {{ request('job_type') == 'internship' ? 'selected' : '' }}>Internship
                            </option>
                        </select>
                    </div> --}}
                    <div class="col-md-4">
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary flex-grow-1">
                                <i class="bi bi-search me-2"></i>Search
                            </button>
                            @if(request()->hasAny(['search', 'status', 'job_type']))
                                <a href="{{ route('admin.jobs.index') }}" class="btn btn-outline-secondary">
                                    <i class="bi bi-x-lg"></i>
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Jobs Table -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3">
            <div class="d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-bold">
                    <i class="bi bi-list-ul text-primary me-2"></i>Vacancy List
                </h6>
                <span class="badge bg-primary ms-2"> Total {{ $jobs->total() }}</span>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 modern-table">
                    <thead class="table-light">
                    <tr>
                        <th>Sr. No.</th>
                        <th>Advertisement No.</th>
                        <th>Position</th>
                        <th>Service</th>
                        <th>Type</th>
                        <th class="ps-4">Demand</th>
                        <th>Qualifications</th>
                        <th class="ps-4">Deadline</th>
                        <th>Applications</th>
                        <th>Status</th>
                        <th class="text-center">Actions</th>
                    </tr>
                    </thead>

                    <tbody class="text-center align-middle">
                        @forelse($jobs as $job)
                            @php
                                $statusBadge = match ($job->status) {
                                    'active' => 'bg-success',
                                    'closed' => 'bg-danger',
                                    'draft' => 'bg-secondary',
                                    default => 'bg-secondary'
                                };

                                $daysRemaining = now()->diffInDays($job->deadline, false);
                                $deadlineColor = $daysRemaining <= 7 ? 'text-danger' : ($daysRemaining <= 14 ? 'text-warning' : 'text-success');
                            @endphp
                            <tr class="job-row {{ $job->status }}">
                                <td>{{ $jobs->firstItem() + $loop->index }}</td>
                                <td>{{ $job->advertisement_no }}</td>
                                <td>{{ $job->position_level }}</td>
                                <td>{{ $job->service_group }}</td>

                                <td>
                                    <span class="badge bg-light text-dark">{{ ucfirst($job->category) }}</span>
                                </td>

                                <td class="ps-4">{{ $job->number_of_posts }}</td>

                                <td>{{ Str::limit($job->minimum_qualification, 50) }}</td>
                                <td>
                                    <div>
                                        {{-- Nepali Date (BS) - Will be populated by JavaScript --}}
                                        <small class="text-danger d-block fw-semibold nepali-date-bs"
                                            data-ad-date="{{ $job->deadline->format('Y-m-d') }}">
                                            <i class="bi bi-hourglass-split"></i> Converting...
                                        </small>
                                        {{-- English Date (AD) --}}
                                        <small class="text-danger">{{ $job->deadline->format('Y-m-d') }}</small>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-primary">{{ $job->applications_count ?? 0 }}</span>
                                </td>
                                <td>
                                    <span class="badge {{ $statusBadge }}">
                                        {{ ucfirst($job->status) }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('admin.jobs.show', $job->id) }}" class="btn btn-outline-primary" title="View">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.jobs.edit', $job->id) }}" class="btn btn-outline-secondary" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <button type="button" class="btn btn-outline-danger" onclick="confirmDelete({{ $job->id }})" title="Delete">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="11" class="text-center py-5">
                                    <i class="bi bi-inbox display-1 text-muted"></i>
                                    <h5 class="text-muted mt-3">No Vacancy Found</h5>
                                    <p class="text-muted">Start by posting your first Vacancy!</p>
                                    <a href="{{ route('admin.jobs.create') }}" class="btn btn-primary mt-2">
                                        <i class="bi bi-plus-circle me-2"></i>Post New Vacancy
                                    </a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if($jobs->hasPages())
            <div class="card-footer bg-white py-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="text-muted small">
                        Showing {{ $jobs->firstItem() }} to {{ $jobs->lastItem() }} of {{ $jobs->total() }}
                    </div>
                    <div>
                        {{ $jobs->links() }}
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- Delete Confirmation Form (Hidden) -->
    <form id="deleteForm" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>
@endsection

@section('scripts')
    <script>
        function changeSorting(value) {
            const [sortBy, sortOrder] = value.split('-');
            const url = new URL(window.location.href);
            url.searchParams.set('sort_by', sortBy);
            url.searchParams.set('sort_order', sortOrder);
            window.location.href = url.toString();
        }

        function confirmDelete(jobId) {
            if (confirm('Are you sure you want to delete this job? This action cannot be undone.')) {
                const form = document.getElementById('deleteForm');
                form.action = `/admin/jobs/${jobId}`;
                form.submit();
            }
        }

        // ========================================
        // NEPALI DATE CONVERSION FOR ALL DEADLINES
        // ========================================

        // Convert English numerals to Nepali numerals
        function englishToNepali(str) {
            if (!str) return str;
            const map = { '0': '०', '1': '१', '2': '२', '3': '३', '4': '४', '5': '५', '6': '६', '7': '७', '8': '८', '9': '९' };
            return str.replace(/[0-9]/g, d => map[d]);
        }

        document.addEventListener('DOMContentLoaded', function () {
            console.log('🔧 Initializing Nepali date conversion for table...');

            // Wait for converter to be ready
            function waitForConverter() {
                if (!window.nepaliLibrariesReady || typeof window.adToBS !== 'function') {
                    setTimeout(waitForConverter, 100);
                    return;
                }

                console.log('✅ Converter ready, converting all dates...');
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
                                element.classList.remove('nepali-date-loading');
                                console.log(`✅ Row ${index + 1}: ${adDate} → ${bsDate} → ${bsNepali}`);
                            } else {
                                element.innerHTML = '<i class="bi bi-exclamation-circle"></i> Error';
                                element.classList.add('text-danger');
                            }
                        } catch (error) {
                            console.error(`❌ Error converting date ${adDate}:`, error);
                            element.innerHTML = '<i class="bi bi-x-circle"></i> Error';
                            element.classList.add('text-danger');
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