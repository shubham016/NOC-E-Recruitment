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
    @include('admin.partials.sidebar')
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
        }

        .job-row:hover {
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
            border: 0.5px solid #e5e7eb !important;
            white-space: nowrap;
            background: #f9fafb;
            text-align: center;
        }

        .modern-table tbody td {
            color: #000;
            border: 0.5px solid #e5e7eb;
            vertical-align: middle;
        }

        .modern-table tbody tr {
            background: #ffffff;
        }

        .modern-table tbody tr.row-hovered td:not(.notice-no-cell) {
            background: #eff1f3;
        }

        .modern-table tbody td.notice-no-cell:hover {
            background: #eff1f3;
        }

        .nepali-date-loading {
            color: #9ca3af;
            font-style: italic;
        }

        /* Status Dropdown Styling */
        .status-change-form select.bg-success {
            background-color: #10b981 !important;
        }

        .status-change-form select.bg-danger {
            background-color: #ef4444 !important;
        }

        .status-change-form select.bg-secondary {
            background-color: #6b7280 !important;
        }

        .status-change-form select option {
            background-color: white;
            color: #000;
        }

        .status-change-form select:hover {
            opacity: 0.9;
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
            <div>
                <a href="{{ route('admin.jobs.create') }}" class="btn btn-light">
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

                    <div class="col-md-4">
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary flex-grow-1">
                                <i class="bi bi-search me-2"></i>Search
                            </button>
                            @if(request()->hasAny(['search', 'status']))
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
        <div class="card-header bg-white py-3 ps-4">
            <div class="d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-bold">
                    <!-- <i class="bi bi-list-ul text-primary me-2"></i> -->
                    Vacancy List
                </h6>
                <span class="badge bg-primary ms-2"> Total {{ $jobs->total() }}</span>
            </div>
        </div>
        <div class="card-body p-0">
            <!-- Bulk Actions Bar -->
            <div id="bulkActionsBar" class="m-3" style="display: none;">
                <div class="card border-0 shadow-sm" style="background: #f8f9fa;">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center gap-2">
                                <button type="button" class="btn btn-sm btn-outline-secondary" onclick="clearSelection()">
                                    <i class="bi bi-x-circle me-1"></i>Clear Selection
                                </button>
                            </div>
                            <div class="d-flex gap-2">
                                <button type="button" class="btn btn-sm btn-success" onclick="exportSelected('csv')">
                                    <i class="bi bi-file-earmark-excel me-1"></i>Export to Excel
                                </button>
                                <button type="button" class="btn btn-sm btn-danger" onclick="exportSelected('pdf')">
                                    <i class="bi bi-file-earmark-pdf me-1"></i>Export to PDF
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table align-middle mb-0 modern-table w-100"
                    style="table-layout: auto; white-space: nowrap; border: none;">
                    <thead class="table-light">
                        <tr>
                            <th class="text-center text-uppercase" style="border: none;">Notice No.</th>
                            <th class="text-center text-uppercase" style="border: none; width: 50px;">
                                <input type="checkbox" id="selectAll" class="form-check-input">
                            </th>
                            <th class="text-center text-uppercase" style="border: none;">S.N</th>
                            <th class="text-center text-uppercase" style="border: none;">Advertisement No.</th>
                            <th class="text-center text-uppercase" style="border: none;">Position / Level</th>
                            <th class="text-center text-uppercase" style="border: none;">Service / Group</th>
                            <th class="text-center text-uppercase" style="border: none;">Type</th>
                            <th class="text-center text-uppercase" style="border: none;">Demand</th>
                            <th class="text-center text-uppercase" style="border: none;">Qualifications</th>
                            <th class="text-center text-uppercase" style="border: none;">Total Fee</th>
                            <th class="text-center text-uppercase" style="border: none;">Double Dastur Fee</th>
                            <th class="text-center text-uppercase" style="border: none;">Deadline</th>
                            <th class="text-center text-uppercase" style="border: none;">Status</th>
                            <th class="text-center text-uppercase" style="border: none;">Actions</th>
                        </tr>
                    </thead>

                    <tbody class="text-center align-middle" style="border: none;">
                        @php
                            // Pre-calculate rowspan for notice_no groups
                            $noticeGroups = [];
                            foreach ($jobs as $j) {
                                $noticeGroups[$j->notice_no] = ($noticeGroups[$j->notice_no] ?? 0) + 1;
                            }
                            $noticeRendered = [];

                            // Pre-calculate rowspan for position+level groups (consecutive rows only)
                            // Works because controller sorts by notice_no → position → level → advertisement_no
                            $jobItems   = $jobs->items();
                            $total      = count($jobItems);
                            $posRowspan = array_fill(0, $total, 0); // 0 = skip cell (covered by rowspan)
                            $i = 0;
                            while ($i < $total) {
                                $key   = ($jobItems[$i]->position ?? '') . '___' . ($jobItems[$i]->level ?? '');
                                $count = 1;
                                while ($i + $count < $total) {
                                    $nk = ($jobItems[$i + $count]->position ?? '') . '___' . ($jobItems[$i + $count]->level ?? '');
                                    if ($nk === $key) { $count++; } else { break; }
                                }
                                $posRowspan[$i] = $count; // first row in group gets the rowspan value
                                $i += $count;
                            }
                            $rowIdx = 0;
                        @endphp
                        @forelse($jobs as $job)
                            @php
                                $statusBadge = match ($job->status) {
                                    'active' => 'bg-success',
                                    'closed' => 'bg-danger',
                                    'draft'  => 'bg-secondary',
                                    default  => 'bg-secondary'
                                };

                                $daysRemaining = now()->diffInDays($job->deadline, false);
                                $deadlineColor = $daysRemaining <= 7 ? 'text-danger' : ($daysRemaining <= 14 ? 'text-warning' : 'text-success');

                                $isFirstInNoticeGroup = !isset($noticeRendered[$job->notice_no]);
                                if ($isFirstInNoticeGroup) {
                                    $noticeRendered[$job->notice_no] = true;
                                }

                                $thisPosRowspan = $posRowspan[$rowIdx]; // > 0 = render cell; 0 = skip
                                $rowIdx++;

                                // ── Build types, demand, and fee arrays in one pass ──
                                $types      = [];
                                $demandVals = [];
                                $feeVals    = [];
                                $dp         = $job->demand_posts  ?? [];
                                $fees       = $job->category_fees ?? [];

                                $inclDemandMap = [
                                    'Women' => 'incl_women', 'A.J' => 'incl_aj',
                                    'Madhesi' => 'incl_madhesi', 'Janajati' => 'incl_janajati',
                                    'Apanga' => 'incl_apanga', 'Dalit' => 'incl_dalit',
                                    'Pichadiyeko Chetra' => 'incl_pichadiyeko',
                                ];
                                $intDemandMap = [
                                    'Women' => 'internal_incl_women', 'A.J' => 'internal_incl_aj',
                                    'Madhesi' => 'internal_incl_madhesi', 'Janajati' => 'internal_incl_janajati',
                                    'Apanga' => 'internal_incl_apanga', 'Dalit' => 'internal_incl_dalit',
                                    'Pichadiyeko Chetra' => 'internal_incl_pichadiyeko',
                                ];

                                if ($job->category === 'internal_appraisal') {
                                    $types[]      = 'Internal Appraisal';
                                    $demandVals[] = $dp['is_internal_appraisal'] ?? $job->number_of_posts;
                                    $feeVals[]    = $job->application_fee;
                                } else {
                                    if ($job->has_open) {
                                        $types[]      = 'Open';
                                        $demandVals[] = $dp['has_open'] ?? $job->number_of_posts;
                                        $feeVals[]    = $fees['open'] ?? null;
                                    }
                                    if ($job->has_inclusive) {
                                        $raw     = $job->inclusive_type;
                                        $decoded = $raw ? (is_array($raw) ? $raw : json_decode($raw, true)) : null;
                                        if (is_array($decoded) && count($decoded)) {
                                            foreach ($decoded as $t) {
                                                $types[]      = ucfirst($t);
                                                $dk           = $inclDemandMap[$t] ?? null;
                                                $demandVals[] = ($dk && isset($dp[$dk])) ? $dp[$dk] : ($job->inclusive_posts ?? $job->number_of_posts);
                                                $feeVals[]    = $fees['inclusive_' . str_replace(' ', '_', $t)] ?? null;
                                            }
                                        } else {
                                            $types[]      = $raw ? ucfirst(is_string($raw) ? trim($raw, '"') : $raw) : 'Inclusive';
                                            $demandVals[] = $job->inclusive_posts ?? $job->number_of_posts;
                                            $feeVals[]    = null;
                                        }
                                    }
                                    if ($job->has_internal && !$job->has_internal_open && !$job->has_internal_inclusive) {
                                        $types[]      = 'Internal';
                                        $demandVals[] = $dp['has_internal'] ?? $job->number_of_posts;
                                        $feeVals[]    = $fees['internal'] ?? null;
                                    }
                                    if ($job->has_internal_open) {
                                        $types[]      = 'Internal/Open';
                                        $demandVals[] = $dp['has_internal_open'] ?? $job->number_of_posts;
                                        $feeVals[]    = $fees['internal_open'] ?? null;
                                    }
                                    if ($job->has_internal_inclusive) {
                                        $rawInt     = $job->internal_inclusive_types;
                                        $decodedInt = $rawInt ? (is_array($rawInt) ? $rawInt : json_decode($rawInt, true)) : null;
                                        if (is_array($decodedInt) && count($decodedInt)) {
                                            foreach ($decodedInt as $t) {
                                                $types[]      = 'Internal/' . ucfirst($t);
                                                $dk           = $intDemandMap[$t] ?? null;
                                                $demandVals[] = ($dk && isset($dp[$dk])) ? $dp[$dk] : $job->number_of_posts;
                                                $feeVals[]    = $fees['internal_inclusive_' . str_replace(' ', '_', $t)] ?? null;
                                            }
                                        } else {
                                            $types[]      = 'Internal/Inclusive';
                                            $demandVals[] = $job->number_of_posts;
                                            $feeVals[]    = null;
                                        }
                                    }
                                    if (empty($types)) {
                                        $types[]      = ucfirst(str_replace('_', ' ', $job->category));
                                        $demandVals[] = $job->number_of_posts;
                                        $feeVals[]    = $job->application_fee;
                                    }
                                }
                            @endphp

                            <tr class="job-row {{ $job->status }}"
                                data-pos-group="{{ $job->position }}_{{ $job->level }}"
                                data-notice-group="{{ $job->notice_no }}">
                                @if($isFirstInNoticeGroup)
                                    <td rowspan="{{ $noticeGroups[$job->notice_no] }}"
                                        class="notice-no-cell align-middle text-center px-3"
                                        style="font-size:0.85rem; font-weight:600; color:#000; text-transform:uppercase; letter-spacing:0.5px; border: 0.5px solid #e5e7eb; vertical-align:middle;">
                                        {{ $job->notice_no }}
                                    </td>
                                @endif
                                <td class="text-center">
                                    <input type="checkbox" name="job_ids[]" value="{{ $job->id }}"
                                        class="form-check-input job-checkbox">
                                </td>
                                <td>{{ $jobs->firstItem() + $loop->index }}</td>
                                <td>{{ $job->advertisement_no }}</td>
                                @if($thisPosRowspan > 0)
                                    <td rowspan="{{ $thisPosRowspan }}"
                                        class="align-middle text-center"
                                        style="border: 0.5px solid #e5e7eb; vertical-align:middle;">
                                        {{ $job->position }}{{ $job->level ? ' / Level ' . $job->level : '' }}
                                    </td>
                                    <td rowspan="{{ $thisPosRowspan }}"
                                        class="align-middle text-center"
                                        style="border: 0.5px solid #e5e7eb; vertical-align:middle;">
                                        {{ $job->service_group ?: $job->department }}
                                    </td>
                                @endif

                                {{-- Type --}}
                                <td>
                                    @foreach($types as $i => $type)
                                        @if($i > 0)<div style="border-top:1px solid #e5e7eb;margin:4px -8px;"></div>@endif
                                        <span style="white-space:nowrap;display:block;">{{ $type }}</span>
                                    @endforeach
                                </td>

                                {{-- Demand --}}
                                <td>
                                    @foreach($demandVals as $i => $val)
                                        @if($i > 0)<div style="border-top:1px solid #e5e7eb;margin:4px -8px;"></div>@endif
                                        <span style="white-space:nowrap;display:block;">{{ $val }}</span>
                                    @endforeach
                                </td>

                                {{-- Qualifications — rowspan per position+level group (same qualification for same role) --}}
                                @if($thisPosRowspan > 0)
                                    <td rowspan="{{ $thisPosRowspan }}"
                                        class="align-middle text-center"
                                        style="border: 0.5px solid #e5e7eb; vertical-align:middle;">
                                        {{ Str::limit($job->minimum_qualification, 50) }}
                                    </td>
                                @endif

{{-- Total Fee — per-type fees stacked (aligned with Type column) --}}
                                <td>
                                    @foreach($feeVals as $i => $feeAmt)
                                        @if($i > 0)<div style="border-top:1px solid #e5e7eb;margin:4px -8px;"></div>@endif
                                        <span style="white-space:nowrap;display:block;">
                                            @if($feeAmt !== null && $feeAmt > 0)
                                                NPR {{ number_format($feeAmt, ($feeAmt == floor($feeAmt) ? 0 : 2)) }}
                                            @else
                                                -
                                            @endif
                                        </span>
                                    @endforeach
                                </td>

                                <td>
                                    @if($job->double_dastur_fee)
                                        NPR {{ number_format($job->double_dastur_fee, ($job->double_dastur_fee == floor($job->double_dastur_fee) ? 0 : 2)) }}
                                    @else
                                        -
                                    @endif
                                </td>

                                {{-- Deadline — rowspan per position+level group (same deadline for same ad batch) --}}
                                @if($thisPosRowspan > 0)
                                    <td rowspan="{{ $thisPosRowspan }}"
                                        class="{{ $deadlineColor }} align-middle text-center"
                                        style="border: 0.5px solid #e5e7eb; vertical-align:middle;">
                                        <small class="d-block fw-semibold nepali-date-bs"
                                            data-ad-date="{{ $job->deadline->format('Y-m-d') }}">
                                            <i class="bi bi-hourglass-split"></i> Converting...
                                        </small>
                                        <small>{{ $job->deadline->format('Y-m-d') }}</small>
                                    </td>
                                @endif

                                <td>
                                    <form action="{{ route('admin.jobs.changeStatus', $job->id) }}" method="POST"
                                        class="status-change-form d-inline">
                                        @csrf
                                        <select name="status" class="form-select form-select-sm border"
                                            onchange="this.form.submit()"
                                            style="font-weight: 600; cursor: pointer; width: auto; padding-right: 2rem;">
                                            <option value="draft" {{ $job->status == 'draft' ? 'selected' : '' }}>Draft</option>
                                            <option value="active" {{ $job->status == 'active' ? 'selected' : '' }}>Active
                                            </option>
                                            <option value="closed" {{ $job->status == 'closed' ? 'selected' : '' }}>Closed
                                            </option>
                                        </select>
                                    </form>
                                </td>

                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('admin.jobs.show', $job->id) }}" class="btn btn-outline-primary"
                                            title="View">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.jobs.edit', $job->id) }}" class="btn btn-outline-secondary"
                                            title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <button type="button" class="btn btn-outline-danger"
                                            onclick="confirmDelete({{ $job->id }})" title="Delete">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="14" class="text-center py-5">
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
            document.querySelectorAll('.job-row').forEach(function(row) {
                row.addEventListener('mouseover', function() {
                    var group = this.dataset.posGroup;
                    document.querySelectorAll('.job-row[data-pos-group="' + group + '"]')
                        .forEach(function(r) { r.classList.add('row-hovered'); });
                });
                row.addEventListener('mouseleave', function() {
                    var group = this.dataset.posGroup;
                    document.querySelectorAll('.job-row[data-pos-group="' + group + '"]')
                        .forEach(function(r) { r.classList.remove('row-hovered'); });
                });
            });
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

        // ============================================
        // Bulk Selection and Export
        // ============================================

        // Select All Checkbox
        document.getElementById('selectAll')?.addEventListener('change', function () {
            const checkboxes = document.querySelectorAll('.job-checkbox');
            checkboxes.forEach(checkbox => checkbox.checked = this.checked);
            updateSelectedCount();
        });

        // Individual Checkbox
        document.querySelectorAll('.job-checkbox').forEach(checkbox => {
            checkbox.addEventListener('change', function () {
                updateSelectedCount();
                const allChecked = document.querySelectorAll('.job-checkbox:checked').length ===
                    document.querySelectorAll('.job-checkbox').length;
                if (document.getElementById('selectAll')) {
                    document.getElementById('selectAll').checked = allChecked;
                }
            });
        });

        // Update Count and Show/Hide Bulk Actions Bar
        function updateSelectedCount() {
            const count   = document.querySelectorAll('.job-checkbox:checked').length;
            const bulkBar = document.getElementById('bulkActionsBar');
            if (bulkBar) bulkBar.style.display = count > 0 ? 'block' : 'none';
        }

        // Clear Selection
        function clearSelection() {
            document.querySelectorAll('.job-checkbox:checked').forEach(cb => {
                cb.checked = false;
            });
            if (document.getElementById('selectAll')) {
                document.getElementById('selectAll').checked = false;
            }
            updateSelectedCount();
        }

        // Export Selected Vacancies
        function exportSelected(type) {
            const selected = [];

            document.querySelectorAll('.job-checkbox:checked').forEach(cb => {
                selected.push(cb.value);
            });

            if (selected.length === 0) {
                alert('Please select at least one vacancy to export.');
                return;
            }

            // Use the export route with type and selected IDs
            let url = "{{ route('admin.jobs.index') }}";
            url += '?export=' + type + '&ids=' + selected.join(',');

            window.location.href = url;
        }
    </script>
@endsection