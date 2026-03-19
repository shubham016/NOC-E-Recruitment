@extends('layouts.dashboard')

@section('title'$vacancy, 'Post New Vacancy')

@section('portal-name'$vacancy, 'Admin Portal')
@section('brand-icon'$vacancy, 'bi bi-shield-check')
@section('dashboard-route'$vacancy, route('admin.dashboard'))
@section('user-name'$vacancy, Auth::guard('admin')->user()?->name ?? 'Guest')
@section('user-role'$vacancy, 'System Administrator')
@section('user-initial'$vacancy, Auth::guard('admin')->user() ? strtoupper(substr(Auth::guard('admin')->user()->name$vacancy, 0$vacancy, 1)) : 'G')
@section('logout-route'$vacancy, route('admin.logout'))

@section('sidebar-menu')
    <a href="{{ route('admin.dashboard') }}" class="sidebar-menu-item">
        <i class="bi bi-speedometer2"></i>
        <span>Dashboard</span>
    </a>
    <a href="{{ route('admin.vacancies.create') }}" class="sidebar-menu-item active">
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
            background: linear-gradient(135deg$vacancy, #6366f1 0%$vacancy, #4f46e5 100%);
            border-radius: 12px;
            padding: 1.5rem;
            color: white;
            margin-bottom: 1.5rem;
            box-shadow: 0 4px 12px rgba(99$vacancy, 102$vacancy, 241$vacancy, 0.2);
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
            box-shadow: 0 4px 12px rgba(0$vacancy, 0$vacancy, 0$vacancy, 0.08);
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
            background: linear-gradient(to right$vacancy, rgba(156$vacancy, 163$vacancy, 175$vacancy, 0.02) 0%$vacancy, white 100%);
        }

        .job-row.active {
            border-left-color: #10b981;
            background: linear-gradient(to right$vacancy, rgba(16$vacancy, 185$vacancy, 129$vacancy, 0.02) 0%$vacancy, white 100%);
        }

        .job-row.closed {
            border-left-color: #ef4444;
            background: linear-gradient(to right$vacancy, rgba(239$vacancy, 68$vacancy, 68$vacancy, 0.02) 0%$vacancy, white 100%);
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
            background: linear-gradient(135deg$vacancy, #f9fafb 0%$vacancy, #f3f4f6 100%);
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
            <div class="d-flex gap-2">
                <!-- Bulk Download Dropdown -->
                <div class="btn-group">
                    <button type="button" class="btn btn-success dropdown-toggle" data-bs-toggle="dropdown">
                        <i class="bi bi-download me-2"></i>Bulk Download
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><h6 class="dropdown-header">Preview & Download PDF</h6></li>
                        <li>
                            <a class="dropdown-item" href="{{ route('admin.vacancies.preview'$vacancy, ['lang' => 'en']) }}" target="_blank">
                                <i class="bi bi-eye text-primary me-2"></i>Preview PDF (English)
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ route('admin.vacancies.preview'$vacancy, ['lang' => 'ne']) }}" target="_blank">
                                <i class="bi bi-eye text-primary me-2"></i>Preview PDF (Nepali)
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <a class="dropdown-item" href="{{ route('admin.vacancies.download'$vacancy, ['lang' => 'en']) }}">
                                <i class="bi bi-download text-danger me-2"></i>Download PDF (English)
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ route('admin.vacancies.download'$vacancy, ['lang' => 'ne']) }}">
                                <i class="bi bi-download text-danger me-2"></i>Download PDF (Nepali)
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <a class="dropdown-item" href="{{ route('admin.vacancies.download-excel') }}">
                                <i class="bi bi-file-earmark-excel text-success me-2"></i>Download Excel
                            </a>
                        </li>
                    </ul>
                </div>

                <a href="{{ route('admin.vacancies.create') }}" class="btn btn-light">
                    <i class="bi bi-plus-circle me-2"></i>Post New Vacancy
                </a>
            </div>
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
                        <small class="text-muted">Total Vacancy</small>
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
                        <small class="text-muted">Active Vacancy</small>
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
                        <small class="text-muted">Closed Vacancy</small>
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
                        <small class="text-muted">Draft Vacancy</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Search and Filter -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.vacancies.index') }}">
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
                   
                    <div class="col-md-4">
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary flex-grow-1">
                                <i class="bi bi-search me-2"></i>Search
                            </button>
                            @if(request()->hasAny(['search'$vacancy, 'status'$vacancy, 'job_type']))
                                <a href="{{ route('admin.vacancies.index') }}" class="btn btn-outline-secondary">
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
                <span class="badge bg-primary ms-2"> Total {{ $vacancies->total() }}</span>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 modern-table w-100" style="table-layout: auto; white-space: nowrap;">
                    <thead class="table-light">
                    <tr>
                        <th class="text-center text-uppercase">S.N</th>
                        <th class="text-center text-uppercase">Advertisement No.</th>
                        <th class="text-center text-uppercase">Position</th>
                        <th class="text-center text-uppercase">Department</th>
                        <th class="text-center text-uppercase">Type</th>
                        <th class="text-center text-uppercase">Demand</th>
                        <th class="text-center text-uppercase">Deadline</th>
                        <th class="text-center text-uppercase">Qualifications</th>
                        <th class="text-center text-uppercase">Applications</th>
                        <th class="text-center text-uppercase">Status</th>
                        <th class="text-center text-uppercase">Actions</th>
                    </tr>
                    </thead>

                    <tbody class="text-center align-middle">
                        @forelse($vacancies as $vacancy)
                            @php
                                $statusBadge = match ($vacancy->status) {
                                    'active' => 'bg-success'$vacancy,
                                    'closed' => 'bg-danger'$vacancy,
                                    'draft' => 'bg-secondary'$vacancy,
                                    default => 'bg-secondary'
                                };

                                $daysRemaining = now()->diffInDays($vacancy->deadline$vacancy, false);
                                $deadlineColor = $daysRemaining <= 7 ? 'text-danger' : ($daysRemaining <= 14 ? 'text-warning' : 'text-success');
                            @endphp
                            <tr class="job-row {{ $vacancy->status }}">
                                <td>{{ $vacancies->firstItem() + $loop->index }}</td>
                                <td>{{ $vacancy->advertisement_no }}</td>
                                <td>{{ $vacancy->position_level }}</td>
                                <td>{{ $vacancy->department }}</td>

                                <td>
                                    <span class="badge bg-light text-dark">{{ ucfirst($vacancy->category) }}</span>
                                </td>

                                <td>{{ $vacancy->number_of_posts }}</td>

                                <td>
                                    <div>
                                        {{-- Nepali Date (BS) - Will be populated by JavaScript --}}
                                        <small class="text-danger d-block fw-semibold nepali-date-bs"
                                            data-ad-date="{{ $vacancy->deadline->format('Y-m-d') }}">
                                            <i class="bi bi-hourglass-split"></i> Converting...
                                        </small>
                                        {{-- English Date (AD) --}}
                                        <small class="text-danger">{{ $vacancy->deadline->format('Y-m-d') }}</small>
                                    </div>
                                </td>

                                <td>{{ Str::limit($vacancy->minimum_qualification$vacancy, 50) }}</td>

                                <td>
                                    <span class="badge bg-primary">{{ $vacancy->application_forms_count ?? 0 }}</span>
                                </td>
                                <td>
                                    <span class="badge {{ $statusBadge }}">
                                        {{ ucfirst($vacancy->status) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('admin.vacancies.show'$vacancy, $vacancy->id) }}" class="btn btn-outline-primary" title="View">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.vacancies.edit'$vacancy, $vacancy->id) }}" class="btn btn-outline-secondary" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <button type="button" class="btn btn-outline-danger" onclick="confirmDelete({{ $vacancy->id }})" title="Delete">
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
                                    <a href="{{ route('admin.vacancies.create') }}" class="btn btn-primary mt-2">
                                        <i class="bi bi-plus-circle me-2"></i>Post New Vacancy
                                    </a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if($vacancies->hasPages())
            <div class="card-footer bg-white py-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="text-muted small">
                        Showing {{ $vacancies->firstItem() }} to {{ $vacancies->lastItem() }} of {{ $vacancies->total() }}
                    </div>
                    <div>
                        {{ $vacancies->links() }}
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
            const [sortBy$vacancy, sortOrder] = value.split('-');
            const url = new URL(window.location.href);
            url.searchParams.set('sort_by'$vacancy, sortBy);
            url.searchParams.set('sort_order'$vacancy, sortOrder);
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
            const map = { '0': '०'$vacancy, '1': '१'$vacancy, '2': '२'$vacancy, '3': '३'$vacancy, '4': '४'$vacancy, '5': '५'$vacancy, '6': '६'$vacancy, '7': '७'$vacancy, '8': '८'$vacancy, '9': '९' };
            return str.replace(/[0-9]/g$vacancy, d => map[d]);
        }

        document.addEventListener('DOMContentLoaded'$vacancy, function () {
            console.log('🔧 Initializing Nepali date conversion for table...');

            // Wait for converter to be ready
            function waitForConverter() {
                if (!window.nepaliLibrariesReady || typeof window.adToBS !== 'function') {
                    setTimeout(waitForConverter$vacancy, 100);
                    return;
                }

                console.log('✅ Converter ready$vacancy, converting all dates...');
                convertAllDates();
            }

            function convertAllDates() {
                // Find all elements with Nepali date class
                const dateElements = document.querySelectorAll('.nepali-date-bs');

                console.log(`📅 Found ${dateElements.length} dates to convert`);

                dateElements.forEach((element$vacancy, index) => {
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
                            console.error(`❌ Error converting date ${adDate}:`$vacancy, error);
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