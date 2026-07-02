@extends('layouts.dashboard')

@section('title', __('admin.vacancy_details'))

@section('portal-name', __('admin.portal_name'))
@section('brand-icon', 'bi bi-shield-check')
@section('dashboard-route', route('admin.dashboard'))
@section('user-name', Auth::guard('admin')->user()->name)
@section('user-role', __('admin.system_administrator'))
@section('user-initial', strtoupper(substr(Auth::guard('admin')->user()->name, 0, 1)))
@section('logout-route', route('admin.logout'))

@section('sidebar-menu')
    @include('admin.partials.sidebar')
@endsection

@section('custom-styles')
    <style>
        .page-header {
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid #d0daea;
        }

        .page-title {
            color: #1e293b;
            font-size: 1.75rem;
            font-weight: 700;
            margin-bottom: 0.25rem;
        }

        .page-subtitle {
            color: #64748b;
            font-size: 0.95rem;
            margin-bottom: 0;
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
            background: #1a3a6b;
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

        .section-title-navy {
            color: #1a3a6b !important;
        }

        .navy-text {
            color: #1a3a6b !important;
        }

        .navy-badge {
            background: #1a3a6b !important;
            color: #fff !important;
        }

        .btn-outline-navy {
            border-color: #1a3a6b;
            color: #1a3a6b;
            background: #fff;
        }

        .btn-outline-navy:hover,
        .btn-outline-navy:focus {
            background: linear-gradient(135deg, #1a3a6b 0%, #122a52 100%);
            border-color: #122a52;
            color: #fff;
        }

        .applicant-avatar-fallback {
            background: rgba(26, 58, 107, 0.12);
            color: #1a3a6b;
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
            border-left: 4px solid #1a3a6b;
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
            background: #1a3a6b;
        }

        /* Scroll to Top Button */
        .stp {
            position: fixed;
            bottom: 30px;
            right: 30px;
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, #1a3a6b 0%, #122a52 100%);
            color: white;
            border: none;
            border-radius: 50%;
            cursor: pointer;
            display: none;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            box-shadow: 0 4px 12px rgba(26, 58, 107, 0.4);
            opacity: 0;
            transition: opacity 0.25s cubic-bezier(0.4, 0, 0.2, 1),
                        transform 0.3s cubic-bezier(0.4, 0, 0.2, 1),
                        box-shadow 0.3s ease;
            z-index: 9999;
            will-change: transform, opacity;
        }

        .stp:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 20px rgba(26, 58, 107, 0.6);
            background: linear-gradient(135deg, #122a52 0%, #0f2344 100%);
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

        /* ---- Print Styles ---- */
        .print-header { display: none; }
        .print-only { display: none; }

        @media print {
            /* Hide everything by default */
            body * { visibility: hidden !important; }

            /* Show only the print section */
            #vacancy-print-section,
            #vacancy-print-section * { visibility: visible !important; }

            /* Show the print-only header */
            .print-header,
            .print-header * { visibility: visible !important; display: block !important; }

            /* Position print section at top */
            #vacancy-print-section {
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                padding: 20px;
            }

            /* Remove shadows and borders for clean print */
            #vacancy-print-section .detail-card {
                box-shadow: none !important;
                border: 1px solid #ccc !important;
                page-break-inside: avoid;
            }

            /* Print header styling */
            .print-header {
                text-align: center;
                margin-bottom: 16px;
                border-bottom: 2px solid #1a3a6b;
                padding-bottom: 12px;
            }

            .print-header h4 {
                font-size: 18px;
                font-weight: 700;
                color: #1a3a6b;
                margin: 0 0 4px 0;
            }

            .print-header p {
                font-size: 13px;
                color: #555;
                margin: 0;
            }

            /* Hide action buttons inside print section (status badge stays) */
            .stp { display: none !important; }

            /* Hide relative time and Posts: line */
            .no-print { display: none !important; }

            /* Show print-only elements */
            .print-only { display: block !important; visibility: visible !important; }
            .print-only * { visibility: visible !important; }
            .print-only table { display: table !important; }
            .print-only thead { display: table-header-group !important; }
            .print-only tbody { display: table-row-group !important; }
            .print-only tr { display: table-row !important; }
            .print-only th,
            .print-only td { display: table-cell !important; }

            @page {
                margin: 1.5cm;
            }
        }
    </style>
@endsection

@section('content')
    <!-- Page Header -->
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center gap-3 flex-wrap">
            <div>
                <h1 class="page-title">
                    {{ __('admin.vacancy_information') }}
                </h1>
                <p class="page-subtitle">विज्ञापन विवरण</p>
            </div>
            <a href="{{ route('admin.jobs.index') }}" class="btn btn-outline-navy">
                {{ __('admin.back') }}
            </a>
        </div>
    </div>

    <!-- Success/Error Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row g-4">
        <!-- Main Content Column -->
        <div class="col-lg-8">
            <div id="vacancy-print-section">
            <!-- Print-only header -->
            <div class="print-header">
                <h4>Vacancy Details &nbsp;|&nbsp; विज्ञापन विवरण</h4>
                <p>नेपाल सरकार | Government of Nepal &nbsp;&mdash;&nbsp; {{ $job->position }} &nbsp;&mdash;&nbsp; Advertisement No. {{ $job->advertisement_no }}</p>
            </div>

            <!-- Vacancy Information Card -->
            <div class="detail-card">
                <div class="detail-header">
                    <div class="d-flex justify-content-between align-items-start">
                        <h5 class="fw-bold section-title-navy mb-0">
                            {{ __('admin.vacancy_information') }}
                        </h5>
                        <span
                            class="status-badge {{ $job->status == 'active' ? 'bg-success text-white' : ($job->status == 'draft' ? 'bg-warning text-dark' : 'bg-danger text-white') }}">
                            {{ __('admin.' . $job->status) }}
                        </span>
                    </div>
                </div>

                @if($job->notice_no)
                <div class="detail-row">
                    <div class="detail-label">{{ __('admin.notice_no') }}</div>
                    <div class="detail-value">
                        {{ $job->notice_no }}
                    </div>
                </div>
                @endif

                <div class="detail-row">
                    <div class="detail-label">{{ __('admin.advertisement_no') }}</div>
                    <div class="detail-value">
                        {{ $job->advertisement_no }}
                    </div>
                </div>

                <div class="detail-row">
                    <div class="detail-label">{{ __('admin.position_level') }}</div>
                    <div class="detail-value">{{ $job->position }} / {{ $job->level ?: '-' }}</div>
                </div>

                <div class="detail-row">
                    <div class="detail-label">{{ __('admin.service_slash_group') }}</div>
                    <div class="detail-value">{{ $job->service_group }}</div>
                </div>

                <div class="detail-row">
                    <div class="detail-label">{{ __('admin.demand_post') }}</div>
                    <div class="detail-value">
                        {{ $job->number_of_posts }}
                    </div>
                </div>

                {{-- Department is same as Service / Group — hidden --}}
                {{-- <div class="detail-row">
                    <div class="detail-label">Department</div>
                    <div class="detail-value">{{ $job->department }}</div>
                </div> --}}

                {{-- <div class="detail-row">
                    <div class="detail-label">Location</div>
                    <div class="detail-value">
                        {{ $job->location }}
                    </div>
                </div> --}}

                <div class="detail-row">
                    <div class="detail-label">{{ __('admin.category_type') }}</div>
                    <div class="detail-value">
                        <div class="d-flex flex-wrap gap-2">
                            @if($job->category == 'internal_appraisal')
                                <span class="badge text-white" style="background-color: #8b5cf6; font-size: 0.875rem; padding: 0.5rem 0.75rem;">
                                    {{ __('admin.internal_appraisal') }}
                                </span>
                            @elseif($job->category == 'open')
                                {{ __('admin.open') }}
                            @elseif($job->category == 'inclusive')
                                @php
                                    $inclusiveTypes = json_decode($job->inclusive_type ?? '[]', true) ?: [];
                                @endphp
                                <span class="badge" style="background:#f3f4f6; color:#1f2937; font-size: 0.875rem; padding: 0.5rem 0.75rem;">
                                    @if(count($inclusiveTypes) > 0)
                                        {{ __('admin.inclusive') }} ({{ implode(', ', $inclusiveTypes) }})
                                    @else
                                        {{ __('admin.inclusive') }}
                                    @endif
                                </span>
                            @elseif($job->category == 'internal')
                                <span class="badge navy-badge" style="font-size: 0.875rem; padding: 0.5rem 0.75rem;">
                                    {{ __('admin.internal') }}
                                </span>
                                @php
                                    $internalInclusiveTypes = json_decode($job->internal_inclusive_types ?? '[]', true) ?: [];
                                @endphp
                                @foreach($internalInclusiveTypes as $type)
                                    <span class="badge text-white" style="background-color: #1a3a6b; font-size: 0.875rem; padding: 0.5rem 0.75rem;">
                                        {{ __('admin.internal') }} {{ __('admin.inclusive') }} - {{ $type }}
                                    </span>
                                @endforeach
                            @else
                                <span class="badge bg-secondary" style="font-size: 0.875rem; padding: 0.5rem 0.75rem;">
                                    {{ ucfirst($job->category) }}
                                </span>
                            @endif
                        </div>
                        <!-- <small class="text-muted d-block mt-2">Candidates can apply under any of these categories</small> -->
                    </div>
                </div>

                <div class="detail-row">
                    <div class="detail-label">{{ __('admin.deadline') }}</div>
                    <div class="detail-value">
                        <div>
                            {{-- Nepali Date (BS) - Will be populated by JavaScript --}}
                            <strong class="d-block nepali-date-bs navy-text"
                                data-ad-date="{{ $job->deadline->format('Y-m-d') }}">
                                {{ __('admin.converting') }}
                            </strong>
                            {{-- English Date (AD) --}}
                            <small class="text-muted">{{ $job->deadline->format('F d, Y') }} <span class="no-print">({{ $job->deadline->diffForHumans() }})</span></small>
                        </div>
                    </div>
                </div>

                @if($job->double_dastur_date)
                <div class="detail-row">
                    <div class="detail-label">{{ __('admin.double_dastur_date') }}</div>
                    <div class="detail-value">
                        <div>
                            <strong class="d-block nepali-date-bs navy-text"
                                data-ad-date="{{ $job->double_dastur_date->format('Y-m-d') }}">
                                {{ __('admin.converting') }}
                            </strong>
                            <small class="text-muted">{{ $job->double_dastur_date->format('F d, Y') }} <span class="no-print">({{ $job->double_dastur_date->diffForHumans() }})</span></small>
                        </div>
                    </div>
                </div>
                @endif

                @if($job->category_fees && count($job->category_fees))
                    @php
                        $feeLabels = [
                            'open'                              => __('admin.fee_open'),
                            'inclusive_Women'                   => __('admin.fee_inclusive_women'),
                            'inclusive_A.J'                     => __('admin.fee_inclusive_aj'),
                            'inclusive_Madhesi'                 => __('admin.fee_inclusive_madhesi'),
                            'inclusive_Janajati'                => __('admin.fee_inclusive_janajati'),
                            'inclusive_Apanga'                  => __('admin.fee_inclusive_apanga'),
                            'inclusive_Dalit'                   => __('admin.fee_inclusive_dalit'),
                            'inclusive_Pichadiyeko_Chetra'      => __('admin.fee_inclusive_pichadiyeko'),
                            'internal_open'                     => __('admin.fee_internal_open'),
                            'internal_inclusive_Women'          => __('admin.fee_internal_incl_women'),
                            'internal_inclusive_A.J'            => __('admin.fee_internal_incl_aj'),
                            'internal_inclusive_Madhesi'        => __('admin.fee_internal_incl_madhesi'),
                            'internal_inclusive_Janajati'       => __('admin.fee_internal_incl_janajati'),
                            'internal_inclusive_Apanga'         => __('admin.fee_internal_incl_apanga'),
                            'internal_inclusive_Dalit'          => __('admin.fee_internal_incl_dalit'),
                            'internal_inclusive_Pichadiyeko_Chetra' => __('admin.fee_internal_incl_pichadiyeko'),
                        ];
                    @endphp
                    @foreach($job->category_fees as $feeKey => $feeAmt)
                        <div class="detail-row">
                            <div class="detail-label">{{ $feeLabels[$feeKey] ?? ucwords(str_replace('_', ' ', $feeKey)) }}</div>
                            <div class="detail-value">
                                {{ __('admin.npr') }} {{ number_format($feeAmt, ($feeAmt == floor($feeAmt) ? 0 : 2)) }}
                            </div>
                        </div>
                    @endforeach
                @endif
                <div class="detail-row">
                    <div class="detail-label">{{ __('admin.total_application_fee') }}</div>
                    <div class="detail-value">
                        @if($job->application_fee !== null)
                            <strong>{{ __('admin.npr') }} {{ number_format($job->application_fee, ($job->application_fee == floor($job->application_fee) ? 0 : 2)) }}</strong>
                        @else
                            <span class="text-muted">{{ __('admin.not_set') }}</span>
                        @endif
                    </div>
                </div>

                <div class="detail-row">
                    <div class="detail-label">{{ __('admin.double_dastur_fee') }}</div>
                    <div class="detail-value">
                        @if($job->double_dastur_fee !== null)
                            {{ __('admin.npr') }} {{ number_format($job->double_dastur_fee, ($job->double_dastur_fee == floor($job->double_dastur_fee) ? 0 : 2)) }}
                        @else
                            <span class="text-muted">{{ __('admin.not_set') }}</span>
                        @endif
                    </div>
                </div>

                <div class="detail-row no-print">
                    <div class="detail-label">{{ __('admin.posted_date') }}</div>
                    <div class="detail-value">
                        {{ $job->created_at->format('F d, Y') }}
                        <small class="text-muted">({{ $job->created_at->diffForHumans() }})</small>
                    </div>
                </div>

                @if($job->postedBy)
                    <div class="detail-row no-print">
                        <div class="detail-label">{{ __('admin.posted_by') }}</div>
                        <div class="detail-value">
                            {{ $job->postedBy->name }}
                        </div>
                    </div>
                @endif

                @if(!in_array($job->category, ['internal', 'internal_appraisal']) && ($job->min_age_male || $job->max_age_male || $job->min_age_female || $job->max_age_female || $job->min_age_disabled || $job->max_age_disabled))
                <div class="print-only mt-3">
                    <div style="font-weight:600; margin-bottom:4px;">{{ __('admin.age_limit') }} <small style="font-weight:400;">उमेर हद</small></div>
                    <table style="width:100%; border-collapse:collapse; font-size:13px;">
                        <thead>
                            <tr style="background:#f3f4f6;">
                                <th style="border:1px solid #ccc; padding:6px 10px; text-align:left;">{{ __('admin.category') }}</th>
                                <th style="border:1px solid #ccc; padding:6px 10px; text-align:center;">{{ __('admin.min_age') }}</th>
                                <th style="border:1px solid #ccc; padding:6px 10px; text-align:center;">{{ __('admin.max_age') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td style="border:1px solid #ccc; padding:6px 10px;">{{ __('admin.male') }}</td>
                                <td style="border:1px solid #ccc; padding:6px 10px; text-align:center;">{{ $job->min_age_male ?? '—' }}</td>
                                <td style="border:1px solid #ccc; padding:6px 10px; text-align:center;">{{ $job->max_age_male ?? '—' }}</td>
                            </tr>
                            <tr>
                                <td style="border:1px solid #ccc; padding:6px 10px;">{{ __('admin.female') }}</td>
                                <td style="border:1px solid #ccc; padding:6px 10px; text-align:center;">{{ $job->min_age_female ?? '—' }}</td>
                                <td style="border:1px solid #ccc; padding:6px 10px; text-align:center;">{{ $job->max_age_female ?? '—' }}</td>
                            </tr>
                            <tr>
                                <td style="border:1px solid #ccc; padding:6px 10px;">{{ __('admin.disabled_category') }}</td>
                                <td style="border:1px solid #ccc; padding:6px 10px; text-align:center;">{{ $job->min_age_disabled ?? '—' }}</td>
                                <td style="border:1px solid #ccc; padding:6px 10px; text-align:center;">{{ $job->max_age_disabled ?? '—' }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                @endif
            </div>

            <!-- Qualification Card -->
            <div class="detail-card">
                <div class="detail-header">
                    <h5 class="fw-bold section-title-navy mb-0">
                        {{ __('admin.min_qualification') }}
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

            <!-- Age Limit Card -->
            @if(!in_array($job->category, ['internal', 'internal_appraisal']) && ($job->min_age_male || $job->max_age_male || $job->min_age_female || $job->max_age_female || $job->min_age_disabled || $job->max_age_disabled))
            <div class="detail-card no-print">
                <div class="detail-header">
                    <h5 class="fw-bold section-title-navy mb-0">{{ __('admin.age_limit') }}</h5>
                    <small class="text-muted">उमेर हद</small>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered mb-0" style="font-size:14px;">
                        <thead style="background:#f3f4f6;">
                            <tr>
                                <th style="width:34%;">{{ __('admin.category') }}</th>
                                <th style="width:33%;">{{ __('admin.min_age') }}</th>
                                <th style="width:33%;">{{ __('admin.max_age') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>{{ __('admin.male') }}</td>
                                <td>{{ $job->min_age_male ?? '—' }}</td>
                                <td>{{ $job->max_age_male ?? '—' }}</td>
                            </tr>
                            <tr>
                                <td>{{ __('admin.female') }}</td>
                                <td>{{ $job->min_age_female ?? '—' }}</td>
                                <td>{{ $job->max_age_female ?? '—' }}</td>
                            </tr>
                            <tr>
                                <td>{{ __('admin.disabled_category') }}</td>
                                <td>{{ $job->min_age_disabled ?? '—' }}</td>
                                <td>{{ $job->max_age_disabled ?? '—' }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            @endif

            {{-- Description Card removed — auto-generated content duplicates Position/Level row --}}

            </div>{{-- #vacancy-print-section --}}

            <!-- Applications List -->
                <div class="detail-card">
                    <div class="detail-header">
                        <h5 class="fw-bold section-title-navy mb-0">
                            {{ __('admin.applications') }}
                        </h5>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0 modern-table w-100"
                            style="table-layout: auto; white-space: nowrap;">
                            <thead class="table-light">
                                <tr>
                                    <th class="text-center text-uppercase">{{ __('admin.sn') }}</th>
                                    <th class="text-center text-uppercase">{{ __('admin.applicant_name') }}</th>
                                    <th class="text-center text-uppercase">{{ __('admin.posted_date') }}</th>
                                    <th class="text-center text-uppercase">{{ __('admin.status') }}</th>
                                    <th class="text-center text-uppercase">{{ __('admin.actions') }}</th>
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
                                                         style="width: 55px; height: 55px; object-fit: cover; border: 2px solid #e5e7eb;"
                                                         onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                                    <div class="applicant-avatar-fallback rounded-circle align-items-center justify-content-center me-2 fw-bold"
                                                         style="width: 55px; height: 55px; min-width: 55px; font-size: 1.2rem; display: none;">
                                                        {{ strtoupper(substr($application->name_english ?? 'A', 0, 1)) }}
                                                    </div>
                                                @else
                                                    <div class="applicant-avatar-fallback rounded-circle d-flex align-items-center justify-content-center me-2 fw-bold"
                                                         style="width: 55px; height: 55px; min-width: 55px; font-size: 1.2rem;">
                                                        {{ strtoupper(substr($application->name_english ?? 'A', 0, 1)) }}
                                                    </div>
                                                @endif
                                                <div class="text-start">
                                                    <div class="fw-bold">{{ $application->name_english ?? 'N/A' }}</div>
                                                    <small class="text-muted">{{ __('admin.application_id_label') }} {{ $application->id }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <strong class="d-block nepali-date-bs navy-text"
                                                    data-ad-date="{{ $application->created_at->format('Y-m-d') }}">
                                                {{ __('admin.converting') }}
                                            </strong>
                                            <small class="text-muted">{{ $application->created_at->format('M d, Y') }}</small>
                                        </td>
                                        <td>
                                            {{ __('admin.' . $application->status) }}
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <a href="{{ route('admin.applications.show', $application->id) }}"
                                                   class="btn btn-outline-navy"
                                                   title="View">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                                <button type="button"
                                                        class="btn btn-outline-danger"
                                                        title="{{ __('admin.delete') }}"
                                                        onclick="confirmDelete({{ $application->id }})">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted py-4">
                                            {{ __('admin.no_applications_yet') }}
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
                            <a href="#" class="btn btn-outline-navy">
                                View All {{ $job->applications_count }} Applications
                                </a>
                        </div>
                    @endif
                </div>
        </div>

        <!-- Sidebar Column -->
        <div class="col-lg-4">
            <!-- Actions Card -->
            <div class="detail-card">
                <div class="detail-header">
                    <h6 class="fw-bold mb-0">
                        {{ __('admin.actions') }}
                    </h6>
                </div>

                <div class="d-grid gap-2">
                    <button type="button" class="btn btn-outline-dark action-btn" onclick="printVacancy()">
                        {{ __('admin.print') }} {{ __('admin.vacancy_list') }}
                    </button>

                    <a href="{{ route('admin.jobs.edit', $job->id) }}" class="btn btn-outline-primary action-btn">
                        {{ __('admin.edit') }} {{ __('admin.vacancy_list') }}
                    </a>

                    <!-- <form action="{{ route('admin.jobs.duplicate', $job->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-outline-secondary action-btn w-100">
                            {{ __('admin.create') }} {{ __('admin.vacancy_list') }}
                        </button>
                    </form> -->

                    {{-- Close Vacancy button hidden --}}
                    @if($job->status == 'closed')
                        <form action="{{ route('admin.jobs.changeStatus', $job->id) }}" method="POST">
                            @csrf
                            <input type="hidden" name="status" value="active">
                            <button type="submit" class="btn btn-outline-success action-btn w-100"
                                onclick="return confirm('{{ __('admin.reopen_vacancy_confirm') }}')">
                                {{ __('admin.reopen_vacancy') }}
                            </button>
                        </form>
                    @endif

                    <form action="{{ route('admin.jobs.destroy', $job->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger action-btn w-100"
                            onclick="return confirm('{{ __('admin.delete_vacancy_confirm') }}')">
                            {{ __('admin.delete_vacancy') }}
                        </button>
                    </form>
                </div>
            </div>

            <!-- Statistics Card -->
            <div class="detail-card">
                <div class="detail-header">
                    <h6 class="fw-bold mb-0">
                        {{ __('admin.total_applications') }}
                    </h6>
                </div>

                <div class="row g-3">
                    <div class="col-6">
                        <div class="text-center p-3 bg-light rounded">
                            <div class="fs-3 fw-bold text-danger">{{ $applicationStats['total'] ?? 0 }}</div>
                            <small class="text-muted">{{ __('admin.applied') }}</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="text-center p-3 bg-info bg-opacity-10 rounded">
                            <div class="fs-3 fw-bold text-info">{{ $applicationStats['assigned'] ?? 0 }}</div>
                            <small class="text-muted">{{ __('admin.assign') }}</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="text-center p-3 bg-primary bg-opacity-10 rounded">
                            <div class="fs-3 fw-bold text-primary">{{ $applicationStats['reviewed'] ?? 0 }}</div>
                            <small class="text-muted">{{ __('admin.reviewed') }}</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="text-center p-3 bg-warning bg-opacity-10 rounded">
                            <div class="fs-3 fw-bold text-warning">{{ $applicationStats['edit_access'] ?? 0 }}</div>
                            <small class="text-muted">{{ __('admin.edit_access') }}</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="text-center p-3 bg-success bg-opacity-10 rounded">
                            <div class="fs-3 fw-bold text-success">{{ $applicationStats['approved'] ?? 0 }}</div>
                            <small class="text-muted">{{ __('admin.approved') }}</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="text-center p-3 bg-danger bg-opacity-10 rounded">
                            <div class="fs-3 fw-bold text-danger">{{ $applicationStats['rejected'] ?? 0 }}</div>
                            <small class="text-muted">{{ __('admin.rejected') }}</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Timeline Card -->
            <div class="detail-card">
                <div class="detail-header">
                    <h6 class="fw-bold mb-0">
                        {{ __('admin.recent_activities') }} <span class="text-success">({{ __('admin.latest_5') }})</span>
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
                            $statusText = __('admin.' . $activity->status);
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
                            <strong class="d-block">{{ __('admin.vacancy_posted') }}</strong>
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
                                dateElement.innerHTML = 'Error';
                            }
                        } catch (error) {
                            console.error(`❌ Error converting date ${adDate}:`, error);
                            dateElement.innerHTML = 'Error';
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
        // PRINT VACANCY
        // ========================================
        function printVacancy() {
            window.print();
        }

        // ========================================
        // DELETE APPLICATION CONFIRMATION
        // ========================================
        function confirmDelete(applicationId) {
            if (confirm('{{ __('admin.delete_app_confirm') }}')) {
                const form = document.getElementById('deleteApplicationForm');
                form.action = `/admin/applications/${applicationId}`;
                form.submit();
            }
        }
    </script>
@endsection
