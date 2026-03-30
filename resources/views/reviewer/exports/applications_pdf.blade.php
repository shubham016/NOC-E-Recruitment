<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Applications Export - Nepal Oil Corporation</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 10px;
            color: #111;
            background: #fff;
        }

        .page {
            padding: 24px 28px;
        }

        /* ── Header ── */
        .header {
            text-align: center;
            border-bottom: 2px solid #a07828;
            padding-bottom: 12px;
            margin-bottom: 14px;
        }

        .header h1 {
            font-size: 15px;
            font-weight: 800;
            color: #1a2a4a;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .header p {
            font-size: 9.5px;
            color: #555;
            margin-top: 2px;
        }

        .header .report-label {
            display: inline-block;
            margin-top: 8px;
            background: #a07828;
            color: #fff;
            font-size: 9px;
            font-weight: 700;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            padding: 3px 14px;
            border-radius: 2px;
        }

        /* ── Meta Row ── */
        .meta-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 14px;
            font-size: 9px;
            color: #444;
            border-bottom: 1px solid #e0d5b8;
            padding-bottom: 8px;
        }

        .meta-row span { line-height: 1.8; display: block; }
        .meta-row strong { color: #1a2a4a; }

        /* ── Stats ── */
        .stats {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 16px;
        }

        .stats td {
            text-align: center;
            border: 1px solid #e0d5b8;
            padding: 7px 4px;
            background: #fdfaf4;
        }

        .stats .stat-num {
            font-size: 16px;
            font-weight: 800;
            color: #a07828;
            display: block;
            line-height: 1;
        }

        .stats .stat-lbl {
            font-size: 8px;
            color: #666;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-top: 3px;
            display: block;
        }

        .stats .approved  .stat-num { color: #065f46; }
        .stats .rejected  .stat-num { color: #991b1b; }
        .stats .pending   .stat-num { color: #92400e; }
        .stats .reviewed  .stat-num { color: #5b21b6; }
        .stats .assigned  .stat-num { color: #1e40af; }

        /* ── Section title ── */
        .section-title {
            font-size: 9px;
            font-weight: 700;
            color: #a07828;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 6px;
            padding-left: 2px;
        }

        /* ── Main Table ── */
        .main-table {
            width: 100%;
            border-collapse: collapse;
        }

        .main-table thead tr {
            background: #1a2a4a;
        }

        .main-table thead th {
            color: #c9a84c;
            font-size: 8.5px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 8px 7px;
            text-align: left;
            border: 1px solid #253a5e;
            white-space: nowrap;
        }

        .main-table thead th.center { text-align: center; }

        .main-table tbody tr:nth-child(even) { background: #fdfaf4; }
        .main-table tbody tr:nth-child(odd)  { background: #ffffff; }

        .main-table tbody td {
            padding: 8px 7px;
            border: 1px solid #e8e2d4;
            vertical-align: top;
            font-size: 9.5px;
            color: #222;
        }

        .main-table tbody td.center { text-align: center; vertical-align: middle; }

        .sn {
            font-weight: 700;
            color: #a07828;
            font-size: 9px;
        }

        .name  { font-weight: 700; color: #1a2a4a; display: block; }
        .email { color: #777; font-size: 8.5px; display: block; margin-top: 1px; }

        .pos  { font-weight: 600; color: #1a2a4a; display: block; }
        .dept { color: #777; font-size: 8.5px; display: block; margin-top: 1px; }

        .d-main { font-weight: 600; display: block; }
        .d-sub  { color: #888; font-size: 8.5px; display: block; margin-top: 1px; }

        /* Badges */
        .badge {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 10px;
            font-size: 8px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.4px;
        }

        .b-approved  { background: #d1fae5; color: #065f46; }
        .b-rejected  { background: #fee2e2; color: #991b1b; }
        .b-pending   { background: #fef3c7; color: #92400e; }
        .b-assigned  { background: #dbeafe; color: #1e40af; }
        .b-reviewed  { background: #ede9fe; color: #5b21b6; }
        .b-default   { background: #f3f4f6; color: #374151; }

        .p-high     { background: #fee2e2; color: #991b1b; }
        .p-medium   { background: #fef3c7; color: #92400e; }
        .p-low      { background: #d1fae5; color: #065f46; }
        .p-critical { background: #1f2937; color: #f9fafb; }
        .p-normal   { background: #f3f4f6; color: #374151; }

        .auto-label { font-size: 7.5px; color: #aaa; display: block; margin-top: 2px; text-align: center; }

        /* Notes sub-row */
        .notes-row td {
            background: #f9f7f2 !important;
            font-size: 8.5px;
            color: #555;
            font-style: italic;
            padding: 4px 7px 6px 20px !important;
            border-top: none !important;
        }

        .notes-lbl { font-weight: 700; color: #a07828; font-style: normal; }

        /* Empty state */
        .empty { text-align: center; padding: 30px; color: #999; font-size: 11px; }

        /* Footer */
        .footer {
            margin-top: 20px;
            padding-top: 10px;
            border-top: 1.5px solid #e0d5b8;
            display: flex;
            justify-content: space-between;
            font-size: 8.5px;
            color: #888;
        }

        .footer strong { color: #a07828; }

        .confidential {
            border: 1.5px solid #a07828;
            color: #a07828;
            font-size: 7.5px;
            font-weight: 800;
            letter-spacing: 2px;
            text-transform: uppercase;
            padding: 2px 8px;
            border-radius: 2px;
            display: inline-block;
            margin-bottom: 3px;
        }

        @media print {
            body { print-color-adjust: exact; -webkit-print-color-adjust: exact; }
        }
    </style>
</head>
<body>
<div class="page">

    {{-- ── Header ── --}}
    <div class="header">
        <h1>Nepal Oil Corporation Ltd.</h1>
        <p>Babarmahal, Kathmandu, Nepal &nbsp;|&nbsp; Online Recruitment Management System</p>
        <span class="report-label">Applications Export Report</span>
    </div>

    {{-- ── Meta ── --}}
    @php
        $approved = $applications->where('status', 'approved')->count();
        $rejected = $applications->where('status', 'rejected')->count();
        $pending  = $applications->where('status', 'pending')->count();
        $reviewed = $applications->where('status', 'reviewed')->count();
        $assigned = $applications->where('status', 'assigned')->count();
    @endphp

    <div class="meta-row">
        <div>
            <span>Generated On: <strong>{{ now()->format('F d, Y  h:i A') }}</strong></span>
            <span>Exported By: <strong>{{ Auth::guard('approver')->user()->name ?? 'Approver' }}</strong></span>
            <span>Employee ID: <strong>{{ Auth::guard('approver')->user()->employee_id ?? 'Approver' }}</strong></span>
        </div>
        <div style="text-align:right;">
            <span>Total Records: <strong>{{ $applications->count() }}</strong></span>
            <span>Report Type: <strong>Recruitment Generated Applications</strong></span>
        </div>
    </div>

    {{-- ── Summary Stats ── --}}
    <table class="stats">
        <tr>
            <td>
                <span class="stat-num">{{ $applications->count() }}</span>
                <span class="stat-lbl">Total</span>
            </td>
            <td class="approved">
                <span class="stat-num">{{ $approved }}</span>
                <span class="stat-lbl">Approved</span>
            </td>
            <td class="rejected">
                <span class="stat-num">{{ $rejected }}</span>
                <span class="stat-lbl">Rejected</span>
            </td>
            <td class="pending">
                <span class="stat-num">{{ $pending }}</span>
                <span class="stat-lbl">Pending</span>
            </td>
        </tr>
    </table>

    {{-- ── Table ── --}}
    <div class="section-title">Application Details</div>

    @if($applications->count() > 0)
    <table class="main-table">
        <thead>
            <tr>
                <th class="center" style="width:28px;">#</th>
                <th style="width:160px;">AD No</th>
                <th style="width:160px;">Application ID</th>
                <th style="width:90px;">Full Name</th>
                <th style="width:90px;">Vacancy Type</th>
                <th style="width:90px;">Applied Date</th>
                <th class="center" style="width:65px;">Payment</th>
                <th class="center" style="width:65px;">Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($applications as $index => $application)
                @php
                    $statusClass = match($application->status) {
                        'approved' => 'b-approved',
                        'rejected' => 'b-rejected',
                        'pending'  => 'b-pending',
                        default    => 'b-default',
                    };


                    $appliedDate = $application->submitted_at ?? $application->created_at;
                @endphp

                <tr>
                    <td class="center sn">{{ $index + 1 }}</td>
                    <td>
                        <span class="name">{{ $application->jobPosting->advertisement_no ?? 'N/A' }}</span>
                        <span class="email">{{ $application->jobPosting->title ?? 'N/A' }}</span>
                    </td>

                    <td>
                        <span class="name">{{ $application->id ?? 'N/A' }}</span>
                        <span class="email">{{ $application->email ?? 'N/A' }}</span>
                    </td>

                    <td>
                        <span class="pos">{{ $application->name_english ?? 'N/A' }}</span>
                        <span class="dept">{{ $application->name_nepali ?? 'N/A' }}</span>
                    </td>

                    <td>
                        <span class="pos">{{ $application->vacancy_type ?? 'N/A' }}</span>
                        <!-- <span class="dept">{{ $application->name_nepali ?? 'N/A' }}</span> -->
                    </td>

                    <td>
                        <span class="d-main">{{ $appliedDate->format('M d, Y') }}</span>
                        <span class="d-sub">{{ $appliedDate->format('h:i A') }}</span>
                    </td>

                    <td>
                        <span class="pos">{{ $application->payment->status ?? 'N/A' }}</span>
                        <span class="dept">{{ $application->payment->amount ?? 'N/A' }}</span>
                    </td>

                    <td class="center">
                        <span class="badge {{ $statusClass }}">{{ ucfirst($application->status) }}</span>
                    </td>
                </tr>

                @if($application->approver_notes)
                <tr class="notes-row">
                    <td colspan="7">
                        <span class="notes-lbl">Approver Notes:</span>
                        {{ $application->approver_notes }}
                    </td>
                </tr>
                @endif

            @endforeach
        </tbody>
    </table>

    @else
        <div class="empty">No applications found for the selected records.</div>
    @endif

    {{-- ── Footer ── --}}
    <div class="footer">
        <div>
            <p><strong>Nepal Oil Corporation Ltd.</strong> &nbsp;|&nbsp; Babarmahal, Kathmandu</p>
            <p style="margin-top:3px;">This document is system-generated. No signature required.</p>
        </div>
        <div style="text-align:right;">
            <div class="confidential">Confidential</div>
            <p>Printed: {{ now()->format('Y-m-d H:i:s') }}</p>
        </div>
    </div>

</div>
</body>
</html>