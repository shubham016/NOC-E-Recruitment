@extends('layouts.dashboard')

@section('title', 'Manage Candidates')

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

        .candidate-row {
            transition: all 0.2s;
        }

        .candidate-row:hover {
            background-color: #f8fafc;
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

        .search-section {
            background: white;
            border-radius: 0.5rem;
            padding: 1.5rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-1">Manage Candidates</h1>
                <p class="text-muted mb-0">View and manage all registered candidates</p>
            </div>
        </div>

        <!-- Success/Error Messages -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Statistics Cards -->
        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <div class="stat-card">
                    <div class="d-flex align-items-center gap-3">
                        <div class="bg-primary bg-opacity-10 rounded-3 p-3">
                            <i class="bi bi-people-fill text-primary fs-4"></i>
                        </div>
                        <div>
                            <h3 class="fw-bold mb-0">{{ $stats['total'] }}</h3>
                            <small class="text-muted">Total Candidates</small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-card">
                    <div class="d-flex align-items-center gap-3">
                        <div class="bg-success bg-opacity-10 rounded-3 p-3">
                            <i class="bi bi-file-earmark-text-fill text-success fs-4"></i>
                        </div>
                        <div>
                            <h3 class="fw-bold mb-0">{{ $stats['with_applications'] }}</h3>
                            <small class="text-muted">With Applications</small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-card">
                    <div class="d-flex align-items-center gap-3">
                        <div class="bg-warning bg-opacity-10 rounded-3 p-3">
                            <i class="bi bi-calendar-fill text-warning fs-4"></i>
                        </div>
                        <div>
                            <h3 class="fw-bold mb-0">{{ $stats['this_month'] }}</h3>
                            <small class="text-muted">This Month</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Search & Filter Section -->
        <div class="search-section mb-4">
            <form method="GET" class="row g-3">
                <div class="col-md-8">
                    <label class="form-label fw-semibold">Search</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="bi bi-search"></i>
                        </span>
                        <input type="text" name="search" class="form-control"
                            placeholder="Search by name, email, username, mobile..." value="{{ $search ?? '' }}">
                    </div>
                </div>
                <div class="col-md-2">
                    <label class="form-label">&nbsp;</label>
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-search me-1"></i> Search
                    </button>
                </div>
                <div class="col-md-2">
                    <label class="form-label">&nbsp;</label>
                    <a href="{{ route('admin.candidates.index') }}" class="btn btn-secondary w-100">
                        <i class="bi bi-x-circle me-1"></i> Clear
                    </a>
                </div>
            </form>
        </div>

        <!-- Candidates Table -->
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3">
                <div class="d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 fw-bold">
                        <i class="bi bi-list-ul text-primary me-2"></i>Candidates List
                    </h6>
                    <span class="badge bg-primary ms-2"> Total {{ $candidates->total() }}</span>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0 modern-table w-100"
                        style="table-layout: auto; white-space: nowrap;">
                        <thead class="table-light">
                            <tr>
                                <th class="text-center text-uppercase">S.N</th>
                                <th class="text-center text-uppercase">Name</th>
                                <th class="text-center text-uppercase">Citizenship No.</th>
                                <th class="text-center text-uppercase">Email</th>
                                <th class="text-center text-uppercase">Phone</th>
                                <th class="text-center text-uppercase">Applications</th>
                                <th class="text-center text-uppercase">Registered</th>
                                <th class="text-center text-uppercase">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="text-center align-middle">
                            @forelse($candidates as $candidate)
                                <tr class="candidate-row">
                                    <td>{{ $candidates->firstItem() + $loop->index }}</td>
                                    <td>{{ $candidate->name_english }}</td>
                                    <td>{{ $candidate->citizenship_number }}</td>
                                    <td>{{ $candidate->email }}</td>
                                    <td>{{ $candidate->phone }}</td>
                                    <td>
                                        {{ $candidate->applications_count }}
                                    </td>
                                    <td>
                                        <div class="nepali-date-bs" data-ad-date="{{ $candidate->created_at->format('Y-m-d') }}">
                                            <i class="bi bi-hourglass-split"></i> Converting...
                                        </div>
                                        <small style="color: #718096;">{{ $candidate->created_at->format('M d, Y') }}</small>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('admin.candidates.show', $candidate->id) }}"
                                                class="btn btn-outline-primary" title="View">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.candidates.edit', $candidate->id) }}"
                                                class="btn btn-outline-secondary" title="Edit">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <button type="button" class="btn btn-outline-danger"
                                                onclick="confirmDelete({{ $candidate->id }})" title="Delete">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-5">
                                        <i class="bi bi-inbox display-1 text-muted"></i>
                                        <h5 class="text-muted mt-3">No Candidates Found</h5>
                                        <p class="text-muted">No candidates have registered yet.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            @if($candidates->hasPages())
                <div class="card-footer bg-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="text-muted small">
                            Showing {{ $candidates->firstItem() }} to {{ $candidates->lastItem() }} of {{ $candidates->total() }}
                        </div>
                        <div>
                            {{ $candidates->links() }}
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
        function confirmDelete(candidateId) {
            if (confirm('Are you sure you want to delete this candidate? This action cannot be undone.')) {
                const form = document.getElementById('deleteForm');
                form.action = `/admin/candidates/${candidateId}`;
                form.submit();
            }
        }

        // Convert English numerals to Nepali numerals
        function englishToNepali(str) {
            if (!str) return str;
            const map = { '0': '०', '1': '१', '2': '२', '3': '३', '4': '४', '5': '५', '6': '६', '7': '७', '8': '८', '9': '९' };
            return str.replace(/[0-9]/g, d => map[d]);
        }

        document.addEventListener('DOMContentLoaded', function () {
            console.log('🔧 Initializing Nepali date conversion for candidates table...');

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
                                console.log(`✅ Row ${index + 1}: ${adDate} → ${bsDate} → ${bsNepali}`);
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