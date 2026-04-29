@extends('layouts.dashboard')

@section('title', 'Vacancy Details')

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
        .page-header {
            background: linear-gradient(135deg, #dc2626 0%, #991b1b 100%);
            border-radius: 12px;
            padding: 2rem;
            color: white;
            margin-bottom: 2rem;
            box-shadow: 0 4px 12px rgba(220, 38, 38, 0.3);
        }

        .govt-badge {
            background: rgba(255, 255, 255, 0.2);
            border: 2px solid rgba(255, 255, 255, 0.3);
            padding: 0.5rem 1rem;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            font-weight: 600;
            margin-bottom: 1rem;
        }

        .detail-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            border: 1px solid #e5e7eb;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .detail-header {
            border-bottom: 2px solid #e5e7eb;
            padding-bottom: 1rem;
            margin-bottom: 1.5rem;
            position: relative;
        }

        .detail-header::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 60px;
            height: 2px;
            background: #dc2626;
        }

        .detail-row {
            display: flex;
            padding: 0.75rem 0;
            border-bottom: 1px solid #f3f4f6;
        }

        .detail-row:last-child {
            border-bottom: none;
        }

        .detail-label {
            font-weight: 600;
            color: #6b7280;
            width: 200px;
            flex-shrink: 0;
        }

        .detail-value {
            color: #1f2937;
            font-weight: 500;
        }

        .status-badge {
            padding: 0.5rem 1rem;
            border-radius: 6px;
            font-size: 0.875rem;
            font-weight: 600;
            display: inline-block;
        }

        .stat-card {
            background: white;
            border-radius: 10px;
            padding: 1.5rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            border: 1px solid #e5e7eb;
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);
        }

        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            margin-bottom: 0.75rem;
        }

        .action-btn {
            padding: 0.5rem 1.5rem;
            font-weight: 600;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .action-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
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

        .qualification-box {
            background: #f9fafb;
            border-left: 4px solid #dc2626;
            padding: 0.5rem 1rem 1rem 1rem;
            border-radius: 6px;
            white-space: normal;
            line-height: 1.6;
        }

        .qualification-box .content-line {
            margin: 0.3rem 0;
        }

        .qualification-box .posts-line {
            margin-bottom: 0.6rem;
        }

        .qualification-box .position-line {
            margin-top: 0.6rem;
        }

        .timeline-item {
            position: relative;
            padding-left: 2rem;
            padding-bottom: 1rem;
            border-left: 2px solid #e5e7eb;
        }

        .timeline-item:last-child {
            border-left: none;
        }

        .timeline-dot {
            position: absolute;
            left: -6px;
            top: 0;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: #dc2626;
        }

        /* Scroll to Top Button */
        .stp {
            position: fixed;
            bottom: 30px;
            right: 30px;
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, #dc2626 0%, #991b1b 100%);
            color: white;
            border: none;
            border-radius: 50%;
            cursor: pointer;
            display: none;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            box-shadow: 0 4px 12px rgba(220, 38, 38, 0.4);
            opacity: 0;
            transition: opacity 0.25s cubic-bezier(0.4, 0, 0.2, 1),
                        transform 0.3s cubic-bezier(0.4, 0, 0.2, 1),
                        box-shadow 0.3s ease;
            z-index: 9999;
            will-change: transform, opacity;
        }

        .stp:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 20px rgba(220, 38, 38, 0.6);
            background: linear-gradient(135deg, #991b1b 0%, #7f1d1d 100%);
        }

        .stp:active {
            transform: translateY(-2px);
        }

        /* Force smooth rendering */
        * {
            scroll-behavior: auto !important;
        }

        html, body {
            scroll-behavior: auto !important;
        }
    </style>
@endsection

@section('content')
    <!-- Page Header -->
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-start">
            <div>
                <div class="govt-badge">
                    <i class="bi bi-building-fill"></i>
                    <span>नेपाल सरकार | Government of Nepal</span>
                </div>
                <h3 class="fw-bold mb-2">
                    <i class="bi bi-file-text-fill me-2"></i>Vacancy Details
                </h3>
                <p class="mb-0 opacity-90">विज्ञापन विवरण</p>
            </div>
            <a href="{{ route('admin.jobs.index') }}" class="btn btn-light btn-lg">
                <i class="bi bi-arrow-left me-2"></i>Back to List
            </a>
        </div>
    </div>

    <!-- Success/Error Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row g-4">
        <!-- Main Content Column -->
        <div class="col-lg-8">
            <!-- Vacancy Information Card -->
            <div class="detail-card">
                <div class="detail-header">
                    <div class="d-flex justify-content-between align-items-start">
                        <h5 class="fw-bold text-danger mb-0">
                            <i class="bi bi-info-circle-fill me-2"></i>Vacancy Information
                        </h5>
                        <span
                            class="status-badge {{ $job->status == 'active' ? 'bg-success text-white' : ($job->status == 'draft' ? 'bg-warning text-dark' : 'bg-danger text-white') }}">
                            {{ ucfirst($job->status) }}
                        </span>
                    </div>
                </div>

                @if($job->notice_no)
                <div class="detail-row">
                    <div class="detail-label">Notice No.</div>
                    <div class="detail-value">
                        <strong class="text-primary">{{ $job->notice_no }}</strong>
                    </div>
                </div>
                @endif

                <div class="detail-row">
                    <div class="detail-label">Advertisement No.</div>
                    <div class="detail-value">
                        <strong class="text-danger">{{ $job->advertisement_no }}</strong>
                    </div>
                </div>

                <div class="detail-row">
                    <div class="detail-label">Position</div>
                    <div class="detail-value">{{ $job->position }}</div>
                </div>

                <div class="detail-row">
                    <div class="detail-label">Level</div>
                    <div class="detail-value">{{ $job->level ? $job->level : '-' }}</div>
                </div>

                <div class="detail-row">
                    <div class="detail-label">Service / Group</div>
                    <div class="detail-value">{{ $job->service_group }}</div>
                </div>

                <div class="detail-row">
                    <div class="detail-label">Demand Post (Number)</div>
                    <div class="detail-value">
                        @php
                            $demandTypeLabels = [
                                'has_open'               => 'Open (खुल्ला)',
                                'incl_women'             => 'Women (महिला)',
                                'incl_aj'                => 'A.J (आ.ज)',
                                'incl_madhesi'           => 'Madhesi (मधेसी)',
                                'incl_janajati'          => 'Janajati (जनजाति)',
                                'incl_apanga'            => 'Apanga (अपाङ्ग)',
                                'incl_dalit'             => 'Dalit (दलित)',
                                'incl_pichadiyeko'       => 'Pichadiyeko Chetra (पिचडिएको क्षेत्र)',
                                'has_internal_open'      => 'Internal Open',
                                'internal_incl_women'    => 'Internal / Women (महिला)',
                                'internal_incl_aj'       => 'Internal / A.J (आ.ज)',
                                'internal_incl_madhesi'  => 'Internal / Madhesi (मधेसी)',
                                'internal_incl_janajati' => 'Internal / Janajati (जनजाति)',
                                'internal_incl_apanga'   => 'Internal / Apanga (अपाङ्ग)',
                                'internal_incl_dalit'    => 'Internal / Dalit (दलित)',
                                'internal_incl_pichadiyeko' => 'Internal / Pichadiyeko Chetra',
                                'is_internal_appraisal'  => 'Internal Appraisal (आन्तरिक बढुवा)',
                            ];
                            $demandPosts = $job->demand_posts ?? [];
                        @endphp
                        @if(!empty($demandPosts))
                            @foreach($demandPosts as $key => $count)
                                @if(isset($demandTypeLabels[$key]) && $count > 0)
                                    <div class="d-flex justify-content-between" style="max-width:320px;">
                                        <span class="text-muted" style="font-size:0.9rem;">{{ $demandTypeLabels[$key] }}</span>
                                        <strong class="text-danger ms-3">{{ $count }}</strong>
                                    </div>
                                @endif
                            @endforeach
                            @if(count($demandPosts) > 1)
                                <div class="d-flex justify-content-between mt-1 pt-1 border-top" style="max-width:320px;">
                                    <span class="fw-semibold text-muted" style="font-size:0.9rem;">Total</span>
                                    <strong class="text-danger ms-3 fs-5">{{ $job->number_of_posts }}</strong>
                                </div>
                            @endif
                        @else
                            <strong class="text-danger fs-5">{{ $job->number_of_posts }}</strong>
                            <small class="text-muted ms-2">positions available</small>
                        @endif
                    </div>
                </div>

                <div class="detail-row">
                    <div class="detail-label">Department</div>
                    <div class="detail-value">{{ $job->department }}</div>
                </div>

                <div class="detail-row">
                    <div class="detail-label">Location</div>
                    <div class="detail-value">
                        <i class="bi bi-geo-alt-fill text-danger me-1"></i>{{ $job->location }}
                    </div>
                </div>

                <div class="detail-row">
                    <div class="detail-label">Category / Type</div>
                    <div class="detail-value">
                        <div class="d-flex flex-wrap gap-2">
                            @if($job->category == 'internal_appraisal')
                                <!-- Internal Appraisal Only -->
                                <span class="badge text-white" style="background-color: #8b5cf6; font-size: 0.875rem; padding: 0.5rem 0.75rem;">
                                    <i class="bi bi-star-fill me-1"></i>Internal Appraisal (आन्तरिक बढुवा)
                                </span>
                            @else
                                <!-- Open Category -->
                                @if($job->has_open || $job->category == 'open')
                                    <span class="badge bg-success" style="font-size: 0.875rem; padding: 0.5rem 0.75rem;">
                                        <i class="bi bi-check-circle-fill me-1"></i>Open (खुल्ला)
                                    </span>
                                @endif

                                <!-- Inclusive Categories -->
                                @if($job->has_inclusive || $job->category == 'inclusive')
                                    @php
                                        // Get inclusive types - could be stored in inclusive_type or inclusive_types array
                                        $inclusiveTypes = [];
                                        if ($job->inclusive_type) {
                                            $inclusiveTypes = [$job->inclusive_type];
                                        }
                                        // If inclusive_types field exists and is an array
                                        if (isset($job->inclusive_types) && is_array($job->inclusive_types)) {
                                            $inclusiveTypes = $job->inclusive_types;
                                        }
                                    @endphp

                                    @if(count($inclusiveTypes) > 0)
                                        @foreach($inclusiveTypes as $type)
                                            <span class="badge bg-info text-white" style="font-size: 0.875rem; padding: 0.5rem 0.75rem;">
                                                <i class="bi bi-people-fill me-1"></i>Inclusive - {{ $type }}
                                            </span>
                                        @endforeach
                                    @else
                                        <span class="badge bg-info text-white" style="font-size: 0.875rem; padding: 0.5rem 0.75rem;">
                                            <i class="bi bi-people-fill me-1"></i>Inclusive (समावेशी)
                                        </span>
                                    @endif
                                @endif

                                <!-- Internal Category -->
                                @if($job->has_internal || $job->category == 'internal')
                                    <span class="badge bg-warning text-dark" style="font-size: 0.875rem; padding: 0.5rem 0.75rem;">
                                        <i class="bi bi-building-fill me-1"></i>Internal (आन्तरिक परीक्षा)
                                    </span>

                                    {{-- Internal Open Sub-category --}}
                                    @if($job->has_internal_open)
                                        <span class="badge text-white" style="background-color: #f59e0b; font-size: 0.875rem; padding: 0.5rem 0.75rem;">
                                            <i class="bi bi-door-open-fill me-1"></i>Internal Open (All NOC Staff)
                                        </span>
                                    @endif

                                    {{-- Internal Inclusive Sub-categories --}}
                                    @if($job->has_internal_inclusive)
                                        @php
                                            // Get internal inclusive types from array
                                            $internalInclusiveTypes = [];
                                            if (isset($job->internal_inclusive_types) && is_array($job->internal_inclusive_types)) {
                                                $internalInclusiveTypes = $job->internal_inclusive_types;
                                            }
                                        @endphp

                                        @if(count($internalInclusiveTypes) > 0)
                                            @foreach($internalInclusiveTypes as $type)
                                                <span class="badge text-white" style="background-color: #d97706; font-size: 0.875rem; padding: 0.5rem 0.75rem;">
                                                    <i class="bi bi-people-fill me-1"></i>Internal Inclusive - {{ $type }}
                                                </span>
                                            @endforeach
                                        @else
                                            <span class="badge text-white" style="background-color: #d97706; font-size: 0.875rem; padding: 0.5rem 0.75rem;">
                                                <i class="bi bi-people-fill me-1"></i>Internal Inclusive
                                            </span>
                                        @endif
                                    @endif
                                @endif
                            @endif

                            @if(!$job->has_open && !$job->has_inclusive && !$job->has_internal && $job->category != 'internal' && $job->category != 'internal_appraisal' && $job->category != 'open' && $job->category != 'inclusive')
                                <span class="badge bg-secondary">{{ ucfirst($job->category) }}</span>
                            @endif
                        </div>
                        <small class="text-muted d-block mt-2">
                            <i class="bi bi-info-circle me-1"></i>Candidates can apply under any of these categories
                        </small>
                    </div>
                </div>

                <div class="detail-row">
                    <div class="detail-label">Deadline</div>
                    <div class="detail-value">
                        <div>
                            {{-- Nepali Date (BS) - Will be populated by JavaScript --}}
                            <strong class="d-block nepali-date-bs text-danger"
                                data-ad-date="{{ $job->deadline->format('Y-m-d') }}">
                                <i class="bi bi-hourglass-split"></i> Converting...
                            </strong>
                            {{-- English Date (AD) --}}
                            <small class="text-muted">{{ $job->deadline->format('F d, Y') }} ({{ $job->deadline->diffForHumans() }})</small>
                        </div>
                    </div>
                </div>

                @if($job->category_fees && count($job->category_fees))
                    @php
                        $feeLabels = [
                            'open'                              => 'Open Application Fee',
                            'inclusive_Women'                   => 'Inclusive (Women) Fee',
                            'inclusive_A.J'                     => 'Inclusive (A.J) Fee',
                            'inclusive_Madhesi'                 => 'Inclusive (Madhesi) Fee',
                            'inclusive_Janajati'                => 'Inclusive (Janajati) Fee',
                            'inclusive_Apanga'                  => 'Inclusive (Apanga) Fee',
                            'inclusive_Dalit'                   => 'Inclusive (Dalit) Fee',
                            'inclusive_Pichadiyeko_Chetra'      => 'Inclusive (Pichadiyeko Chetra) Fee',
                            'internal_open'                     => 'Internal Open Fee',
                            'internal_inclusive_Women'          => 'Internal Inclusive (Women) Fee',
                            'internal_inclusive_A.J'            => 'Internal Inclusive (A.J) Fee',
                            'internal_inclusive_Madhesi'        => 'Internal Inclusive (Madhesi) Fee',
                            'internal_inclusive_Janajati'       => 'Internal Inclusive (Janajati) Fee',
                            'internal_inclusive_Apanga'         => 'Internal Inclusive (Apanga) Fee',
                            'internal_inclusive_Dalit'          => 'Internal Inclusive (Dalit) Fee',
                            'internal_inclusive_Pichadiyeko_Chetra' => 'Internal Inclusive (Pichadiyeko Chetra) Fee',
                        ];
                    @endphp
                    @foreach($job->category_fees as $feeKey => $feeAmt)
                        <div class="detail-row">
                            <div class="detail-label">{{ $feeLabels[$feeKey] ?? ucwords(str_replace('_', ' ', $feeKey)) }}</div>
                            <div class="detail-value">
                                <i class="bi bi-cash-coin text-primary me-1"></i>
                                <strong>NPR {{ number_format($feeAmt, ($feeAmt == floor($feeAmt) ? 0 : 2)) }}</strong>
                            </div>
                        </div>
                    @endforeach
                @endif
                <div class="detail-row">
                    <div class="detail-label">Total Application Fee</div>
                    <div class="detail-value">
                        <i class="bi bi-cash-coin text-primary me-1"></i>
                        @if($job->application_fee)
                            <strong>NPR {{ number_format($job->application_fee, ($job->application_fee == floor($job->application_fee) ? 0 : 2)) }}</strong>
                        @else
                            <span class="text-muted">Not set</span>
                        @endif
                    </div>
                </div>

                <div class="detail-row">
                    <div class="detail-label">Double Dastur Fee</div>
                    <div class="detail-value">
                        <i class="bi bi-cash-stack text-danger me-1"></i>
                        @if($job->double_dastur_fee)
                            <strong>NPR {{ number_format($job->double_dastur_fee, ($job->double_dastur_fee == floor($job->double_dastur_fee) ? 0 : 2)) }}</strong>
                        @else
                            <span class="text-muted">Not set</span>
                        @endif
                    </div>
                </div>

                <div class="detail-row">
                    <div class="detail-label">Posted On</div>
                    <div class="detail-value">
                        {{ $job->created_at->format('F d, Y') }}
                        <small class="text-muted">({{ $job->created_at->diffForHumans() }})</small>
                    </div>
                </div>

                @if($job->postedBy)
                    <div class="detail-row">
                        <div class="detail-label">Posted By</div>
                        <div class="detail-value">
                            <i class="bi bi-person-fill text-danger me-1"></i>{{ $job->postedBy->name }}
                        </div>
                    </div>
                @endif
            </div>

            <!-- Qualification Card -->
            <div class="detail-card">
                <div class="detail-header">
                    <h5 class="fw-bold text-danger mb-0">
                        <i class="bi bi-mortarboard-fill me-2"></i>Minimum Educational Qualification
                    </h5>
                    <small class="text-muted">आवश्यक शिक्षक योग्यता</small>
                </div>
                <div class="qualification-box">
                    @php
                        $lines = explode("\n", $job->minimum_qualification);
                        $processedLines = [];
                        foreach ($lines as $line) {
                            $trimmed = trim($line);
                            if ($trimmed !== '') {
                                $processedLines[] = $trimmed;
                            }
                        }

                        foreach ($processedLines as $index => $line) {
                            $class = 'content-line';

                            // Check if current line starts with "Position:"
                            if (stripos($line, 'Position:') === 0) {
                                $class .= ' position-line';
                            }

                            // Check if current line contains "Number of Posts"
                            if (stripos($line, 'Number of Posts') !== false) {
                                $class .= ' posts-line';
                            }

                            echo '<div class="' . $class . '">' . e($line) . '</div>';
                        }
                    @endphp
                </div>
            </div>

            <!-- Description Card -->
            @if($job->description)
                <div class="detail-card">
                    <div class="detail-header">
                        <h5 class="fw-bold text-danger mb-0">
                            <i class="bi bi-file-text-fill me-2"></i>Description
                        </h5>
                    </div>
                    <div class="qualification-box">
                        {!! nl2br(e(implode("\n", array_filter(array_map('trim', explode("\n", $job->description)), 'strlen')))) !!}
                    </div>
                </div>
            @endif

            <!-- Applications List -->
            @if($job->applications_count > 0)
                <div class="detail-card">
                    <div class="detail-header">
                        <h5 class="fw-bold text-danger mb-0">
                            <!-- <i class="bi bi-people-fill me-2"></i> -->
                            Recent Applications
                            <!-- <span class="badge bg-danger ms-2">{{ $job->applications_count }}</span> -->
                        </h5>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0 modern-table w-100"
                            style="table-layout: auto; white-space: nowrap;">
                            <thead class="table-light">
                                <tr>
                                    <th class="text-center text-uppercase">S.N</th>
                                    <th class="text-center text-uppercase">Candidate</th>
                                    <th class="text-center text-uppercase">Applied Date</th>
                                    <th class="text-center text-uppercase">Status</th>
                                    <th class="text-center text-uppercase">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="text-center align-middle">
                                @forelse($job->applications->take(5) as $index => $application)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>
                                            <div class="d-flex align-items-center justify-content-center">
                                                @if($application->passport_size_photo)
                                                    <img src="{{ asset('storage/' . $application->passport_size_photo) }}"
                                                         alt="{{ $application->name_english }}"
                                                         class="rounded-circle me-2"
                                                         style="width: 55px; height: 55px; object-fit: cover;">
                                                @else
                                                    <div class="bg-danger bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-2"
                                                         style="width: 55px; height: 55px; min-width: 55px;">
                                                        <i class="bi bi-person-fill text-danger fs-4"></i>
                                                    </div>
                                                @endif
                                                <div class="text-start">
                                                    <div class="fw-bold">{{ $application->name_english ?? 'N/A' }}</div>
                                                    <small class="text-muted">Application ID: {{ $application->id }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <strong class="d-block nepali-date-bs text-danger"
                                                    data-ad-date="{{ $application->created_at->format('Y-m-d') }}">
                                                Converting...
                                            </strong>
                                            <small class="text-muted">{{ $application->created_at->format('M d, Y') }}</small>
                                        </td>
                                        <td>
                                            {{ ucfirst(str_replace('_', ' ', $application->status)) }}
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <a href="{{ route('admin.applications.show', $application->id) }}"
                                                   class="btn btn-outline-primary"
                                                   title="View">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                                <button type="button"
                                                        class="btn btn-outline-danger"
                                                        title="Delete"
                                                        onclick="confirmDelete({{ $application->id }})">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted py-4">
                                            <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                            No applications yet
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Delete Form (Hidden) -->
                    <form id="deleteApplicationForm" method="POST" style="display: none;">
                        @csrf
                        @method('DELETE')
                    </form>

                    @if($job->applications_count > 5)
                        <div class="text-center mt-3">
                            <a href="#" class="btn btn-outline-danger">
                                View All {{ $job->applications_count }} Applications
                                <i class="bi bi-arrow-right ms-1"></i>
                            </a>
                        </div>
                    @endif
                </div>
            @endif
        </div>

        <!-- Sidebar Column -->
        <div class="col-lg-4">
            <!-- Actions Card -->
            <div class="detail-card">
                <div class="detail-header">
                    <h6 class="fw-bold mb-0">
                        <i class="bi bi-lightning-fill text-danger me-2"></i>Quick Actions
                    </h6>
                </div>

                <div class="d-grid gap-2">
                    <a href="{{ route('admin.jobs.edit', $job->id) }}" class="btn btn-outline-danger action-btn">
                        <i class="bi bi-pencil-square me-2"></i>Edit Vacancy
                    </a>

                    <form action="{{ route('admin.jobs.duplicate', $job->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-outline-secondary action-btn w-100">
                            <i class="bi bi-files me-2"></i>Duplicate Vacancy
                        </button>
                    </form>

                    @if($job->status == 'active')
                        <form action="{{ route('admin.jobs.changeStatus', $job->id) }}" method="POST">
                            @csrf
                            <input type="hidden" name="status" value="closed">
                            <button type="submit" class="btn btn-outline-warning action-btn w-100"
                                onclick="return confirm('Close this vacancy?')">
                                <i class="bi bi-lock-fill me-2"></i>Close Vacancy
                            </button>
                        </form>
                    @elseif($job->status == 'closed')
                        <form action="{{ route('admin.jobs.changeStatus', $job->id) }}" method="POST">
                            @csrf
                            <input type="hidden" name="status" value="active">
                            <button type="submit" class="btn btn-outline-success action-btn w-100"
                                onclick="return confirm('Reopen this vacancy?')">
                                <i class="bi bi-unlock-fill me-2"></i>Reopen Vacancy
                            </button>
                        </form>
                    @endif

                    <form action="{{ route('admin.jobs.destroy', $job->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger action-btn w-100"
                            onclick="return confirm('⚠️ Are you sure? This action cannot be undone!')">
                            <i class="bi bi-trash-fill me-2"></i>Delete Vacancy
                        </button>
                    </form>
                </div>
            </div>

            <!-- Statistics Card -->
            <div class="detail-card">
                <div class="detail-header">
                    <h6 class="fw-bold mb-0">
                        <i class="bi bi-graph-up text-danger me-2"></i>Application Statistics
                    </h6>
                </div>

                <div class="row g-3">
                    <div class="col-6">
                        <div class="text-center p-3 bg-light rounded">
                            <div class="fs-3 fw-bold text-danger">{{ $applicationStats['total'] ?? 0 }}</div>
                            <small class="text-muted">Total Applied</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="text-center p-3 bg-info bg-opacity-10 rounded">
                            <div class="fs-3 fw-bold text-info">{{ $applicationStats['assigned'] ?? 0 }}</div>
                            <small class="text-muted">Assigned</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="text-center p-3 bg-primary bg-opacity-10 rounded">
                            <div class="fs-3 fw-bold text-primary">{{ $applicationStats['reviewed'] ?? 0 }}</div>
                            <small class="text-muted">Reviewed</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="text-center p-3 bg-warning bg-opacity-10 rounded">
                            <div class="fs-3 fw-bold text-warning">{{ $applicationStats['edit_access'] ?? 0 }}</div>
                            <small class="text-muted">Edit Access</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="text-center p-3 bg-success bg-opacity-10 rounded">
                            <div class="fs-3 fw-bold text-success">{{ $applicationStats['approved'] ?? 0 }}</div>
                            <small class="text-muted">Approved</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="text-center p-3 bg-danger bg-opacity-10 rounded">
                            <div class="fs-3 fw-bold text-danger">{{ $applicationStats['rejected'] ?? 0 }}</div>
                            <small class="text-muted">Rejected</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Timeline Card -->
            <div class="detail-card">
                <div class="detail-header">
                    <h6 class="fw-bold mb-0">
                        <i class="bi bi-clock-history text-danger me-2"></i>Recent Activities (Latest 5)
                    </h6>
                </div>

                @php
                    $timelineCount = 0;
                    $maxTimeline = 5;
                @endphp

                {{-- Show recent application activities --}}
                @foreach($recentActivities as $activity)
                    @if($timelineCount < $maxTimeline)
                        @php
                            $dotColor = match($activity->status) {
                                'approved' => 'bg-success',
                                'rejected' => 'bg-danger',
                                'reviewed' => 'bg-primary',
                                'assigned' => 'bg-info',
                                'edit' => 'bg-warning',
                                'pending' => 'bg-secondary',
                                default => 'bg-secondary'
                            };
                            $statusText = ucfirst(str_replace('_', ' ', $activity->status));
                            $timelineCount++;
                        @endphp
                        <div class="timeline-item">
                            <div class="timeline-dot {{ $dotColor }}"></div>
                            <div>
                                <strong class="d-block">Application {{ $statusText }}</strong>
                                <small class="text-muted">{{ $activity->name_english ?? 'Applicant' }}</small>
                                <br>
                                <small class="text-muted">{{ $activity->updated_at->format('M d, Y h:i A') }}</small>
                            </div>
                        </div>
                    @endif
                @endforeach

                {{-- Show vacancy posted if less than 5 activities --}}
                @if($timelineCount < $maxTimeline)
                    <div class="timeline-item">
                        <div class="timeline-dot"></div>
                        <div>
                            <strong class="d-block">Vacancy Posted</strong>
                            <small class="text-muted">{{ $job->created_at->format('M d, Y h:i A') }}</small>
                        </div>
                    </div>
                    @php $timelineCount++; @endphp
                @endif
            </div>
        </div>
    </div>

    <!-- Scroll to Top Button -->
    <button class="stp" id="stp">
        <i class="fas fa-chevron-up"></i>
    </button>
@endsection

@section('scripts')
    <script>
        // ========================================
        // NEPALI DATE CONVERSION
        // ========================================

        // Convert English numerals to Nepali numerals
        function englishToNepali(str) {
            if (!str) return str;
            const map = { '0': '०', '1': '१', '2': '२', '3': '३', '4': '४', '5': '५', '6': '६', '7': '७', '8': '८', '9': '९' };
            return str.replace(/[0-9]/g, d => map[d]);
        }

        document.addEventListener('DOMContentLoaded', function () {
            console.log('🔧 Initializing Nepali date conversion...');

            // Wait for converter to be ready
            function waitForConverter() {
                if (!window.nepaliLibrariesReady || typeof window.adToBS !== 'function') {
                    setTimeout(waitForConverter, 100);
                    return;
                }

                console.log('✅ Converter ready, converting deadline date...');
                convertDeadlineDate();
            }

            function convertDeadlineDate() {
                // Find ALL date elements with nepali-date-bs class
                const dateElements = document.querySelectorAll('.nepali-date-bs');

                dateElements.forEach(function(dateElement) {
                    const adDate = dateElement.getAttribute('data-ad-date');

                    if (adDate) {
                        try {
                            // Convert AD to BS
                            const bsDate = window.adToBS(adDate);

                            if (bsDate) {
                                // Convert to Nepali numerals
                                const bsNepali = englishToNepali(bsDate);

                                // Update the element with Nepali numeral date
                                dateElement.innerHTML = `${bsNepali}`;
                                console.log(`✅ Date converted: ${adDate} → ${bsDate} → ${bsNepali}`);
                            } else {
                                dateElement.innerHTML = '<i class="bi bi-exclamation-circle"></i> Error';
                            }
                        } catch (error) {
                            console.error(`❌ Error converting date ${adDate}:`, error);
                            dateElement.innerHTML = '<i class="bi bi-x-circle"></i> Error';
                        }
                    }
                });
            }

            // Start the conversion process
            waitForConverter();
        });

        // ========================================
        // SCROLL TO TOP FUNCTIONALITY
        // ========================================
        (function() {
            const btn = document.getElementById('stp');
            if (!btn) return;

            let isAnimating = false;
            let animationId = null;

            btn.style.display = 'none';

            function easeInOutQuad(t) {
                return t < 0.5 ? 2 * t * t : -1 + (4 - 2 * t) * t;
            }

            function scrollToTop() {
                if (animationId) {
                    cancelAnimationFrame(animationId);
                }

                if (isAnimating) return;

                isAnimating = true;

                const startPosition = window.pageYOffset || document.documentElement.scrollTop;
                const startTime = performance.now();
                const duration = 700;

                function animate(currentTime) {
                    const elapsed = currentTime - startTime;
                    const progress = Math.min(elapsed / duration, 1);

                    const easeProgress = easeInOutQuad(progress);
                    const currentPosition = startPosition * (1 - easeProgress);

                    window.scrollTo(0, currentPosition);

                    if (progress < 1) {
                        animationId = requestAnimationFrame(animate);
                    } else {
                        isAnimating = false;
                        animationId = null;
                    }
                }

                animationId = requestAnimationFrame(animate);
            }

            btn.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                e.stopImmediatePropagation();
                scrollToTop();
                return false;
            }, true);

            let visible = false;

            function checkScroll() {
                const scrolled = window.pageYOffset || document.documentElement.scrollTop;

                if (scrolled > 200) {
                    if (!visible) {
                        btn.style.display = 'flex';
                        setTimeout(function() {
                            btn.style.opacity = '1';
                        }, 10);
                        visible = true;
                    }
                } else {
                    if (visible) {
                        btn.style.opacity = '0';
                        setTimeout(function() {
                            btn.style.display = 'none';
                        }, 300);
                        visible = false;
                    }
                }
            }

            let ticking = false;
            window.addEventListener('scroll', function() {
                if (!ticking) {
                    window.requestAnimationFrame(function() {
                        checkScroll();
                        ticking = false;
                    });
                    ticking = true;
                }
            }, { passive: true });

            checkScroll();

        })();

        // ========================================
        // DELETE APPLICATION CONFIRMATION
        // ========================================
        function confirmDelete(applicationId) {
            if (confirm('Are you sure you want to delete this application? This action cannot be undone.')) {
                const form = document.getElementById('deleteApplicationForm');
                form.action = `/admin/applications/${applicationId}`;
                form.submit();
            }
        }
    </script>
@endsection