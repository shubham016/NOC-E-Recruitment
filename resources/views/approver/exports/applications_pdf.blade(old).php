<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Applications Export - Nepal Oil Corporation</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 11px;
            color: #1a1a2e;
            background: #ffffff;
            line-height: 1.5;
        }

        /* ─── Page Layout ─── */
        .page-wrapper {
            padding: 30px 35px;
        }

        /* ─── Header ─── */
        .header {
            border-bottom: 3px solid #a07828;
            padding-bottom: 18px;
            margin-bottom: 22px;
            position: relative;
        }

        .header-inner {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .org-logo-placeholder {
            width: 56px;
            height: 56px;
            background: linear-gradient(135deg, #c9a84c 0%, #a07828 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .org-logo-placeholder span {
            color: white;
            font-size: 20px;
            font-weight: 900;
            letter-spacing: -1px;
        }

        .org-info h1 {
            font-size: 16px;
            font-weight: 800;
            color: #1a2a4a;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }

        .org-info p {
            font-size: 10px;
            color: #666;
            margin-top: 2px;
        }

        .org-info .system-label {
            font-size: 9.5px;
            color: #a07828;
            font-style: italic;
            margin-top: 1px;
        }

        /* Gold accent bar */
        .gold-accent {
            position: absolute;
            right: 0;
            top: 0;
            width: 4px;
            height: 100%;
            background: linear-gradient(180deg, #c9a84c, #a07828);
            border-radius: 2px;
        }

        /* ─── Report Title Block ─── */
        .report-title-block {
            background: linear-gradient(135deg, #1a2a4a 0%, #253a5e 100%);
            border-radius: 8px;
            padding: 14px 20px;
            margin-bottom: 18px;
            border-left: 5px solid #c9a84c;
        }

        .report-title-block h2 {
            font-size: 14px;
            font-weight: 700;
            color: #ffffff;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .report-title-block .meta {
            display: flex;
            gap: 24px;
            margin-top: 6px;
        }

        .report-title-block .meta span {
            font-size: 9.5px;
            color: #c9a84c;
        }

        .report-title-block .meta strong {
            color: #ffffff;
        }

        /* ─── Summary Stats ─── */
        .stats-row {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
        }

        .stat-box {
            flex: 1;
            border: 1px solid #e8e2d4;
            border-radius: 6px;
            padding: 10px 12px;
            text-align: center;
            background: #fdfaf4;
            border-top: 3px solid #c9a84c;
        }

        .stat-box .num {
            font-size: 20px;
            font-weight: 800;
            color: #a07828;
            line-height: 1;
            display: block;
        }

        .stat-box .label {
            font-size: 9px;
            color: #666;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-top: 4px;
            display: block;
        }

        /* ─── Table ─── */
        .section-heading {
            font-size: 10px;
            font-weight: 700;
            color: #a07828;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 8px;
            padding-bottom: 4px;
            border-bottom: 1px solid #e8e2d4;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 10px;
        }

        thead tr {
            background: linear-gradient(135deg, #1a2a4a 0%, #253a5e 100%);
        }

        thead th {
            padding: 9px 8px;
            text-align: left;
            color: #c9a84c;
            font-weight: 700;
            font-size: 9px;
            text-transform: uppercase;
            letter-spacing: 0.6px;
            border: none;
        }

        thead th:first-child {
            border-radius: 4px 0 0 0;
            text-align: center;
            width: 32px;
        }

        thead th:last-child {
            border-radius: 0 4px 0 0;
        }

        tbody tr {
            border-bottom: 1px solid #f0ece3;
        }

        tbody tr:nth-child(even) {
            background: #fdfaf4;
        }

        tbody tr:nth-child(odd) {
            background: #ffffff;
        }

        tbody td {
            padding: 8px 8px;
            color: #2c2c3e;
            vertical-align: middle;
        }

        tbody td:first-child {
            text-align: center;
            font-weight: 700;
            color: #a07828;
            font-size: 9px;
        }

        .candidate-name {
            font-weight: 700;
            color: #1a2a4a;
            font-size: 10px;
            display: block;
        }

        .candidate-email {
            color: #888;
            font-size: 9px;
            display: block;
            margin-top: 1px;
        }

        .position-title {
            font-weight: 600;
            color: #253a5e;
            display: block;
        }

        .dept-name {
            color: #888;
            font-size: 9px;
            display: block;
        }

        .date-main {
            font-weight: 600;
            color: #1a2a4a;
            display: block;
        }

        .date-bs {
            color: #888;
            font-size: 9px;
            display: block;
        }

        /* Status Badges */
        .badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 12px;
            font-size: 8.5px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .badge-approved  { background: #d1fae5; color: #065f46; }
        .badge-rejected  { background: #fee2e2; color: #991b1b; }
        .badge-pending   { background: #fef3c7; color: #92400e; }
        .badge-assigned  { background: #dbeafe; color: #1e40af; }
        .badge-reviewed  { background: #ede9fe; color: #5b21b6; }
        .badge-default   { background: #f3f4f6; color: #374151; }

        /* Priority Badges */
        .priority-badge {
            display: inline-block;
            padding: 2px 7px;
            border-radius: 10px;
            font-size: 8px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.4px;
        }

        .priority-high     { background: #fee2e2; color: #991b1b; }
        .priority-medium   { background: #fef3c7; color: #92400e; }
        .priority-low      { background: #d1fae5; color: #065f46; }
        .priority-critical { background: #1f2937; color: #f9fafb; }
        .priority-normal   { background: #f3f4f6; color: #374151; }

        /* ─── Notes row ─── */
        .notes-row td {
            background: #f9f7f2 !important;
            padding: 5px 8px 8px 28px !important;
            color: #555;
            font-size: 9px;
            font-style: italic;
            border-bottom: 2px solid #e8e2d4 !important;
        }

        .notes-label {
            font-weight: 700;
            color: #a07828;
            font-style: normal;
        }

        /* ─── Empty State ─── */
        .empty-state {
            text-align: center;
            padding: 40px 20px;
            color: #888;
        }

        .empty-state .icon {
            font-size: 32px;
            display: block;
            margin-bottom: 8px;
        }

        /* ─── Footer ─── */
        .footer {
            margin-top: 28px;
            padding-top: 14px;
            border-top: 2px solid #e8e2d4;
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
        }

        .footer-left p {
            font-size: 8.5px;
            color: #888;
            line-height: 1.6;
        }

        .footer-left strong {
            color: #a07828;
        }

        .footer-right {
            text-align: right;
        }

        .footer-right p {
            font-size: 8.5px;
            color: #888;
        }

        .confidential-stamp {
            display: inline-block;
            border: 1.5px solid #a07828;
            color: #a07828;
            font-size: 8px;
            font-weight: 800;
            letter-spacing: 2px;
            text-transform: uppercase;
            padding: 3px 10px;
            border-radius: 3px;
            transform: rotate(-2deg);
            margin-bottom: 4px;
        }

        /* Page break utility */
        .page-break { page-break-after: always; }

        @media print {
            body { print-color-adjust: exact; -webkit-print-color-adjust: exact; }
        }
    </style>
</head>
<body>
<div class="page-wrapper">

    {{-- ── Header ── --}}
    <div class="header">
        <div class="header-inner">
            <div class="org-logo-placeholder">
                <span>NOC</span>
            </div>
            <div class="org-info">
                <h1>Nepal Oil Corporation Ltd.</h1>
                <p>Babarmahal, Kathmandu, Nepal</p>
                <p class="system-label">Online Recruitment Management System</p>
            </div>
        </div>
        <div class="gold-accent"></div>
    </div>

    {{-- ── Report Title ── --}}
    <div class="report-title-block">
        <h2>Applications Export Report</h2>
        <div class="meta">
            <span>Generated: <strong>{{ now()->format('F d, Y  h:i A') }}</strong></span>
            <span>Exported By: <strong>{{ Auth::guard('approver')->user()->name ?? 'Approver' }}</strong></span>
            <span>Total Records: <strong>{{ $applications->count() }}</strong></span>
        </div>
    </div>

    {{-- ── Summary Stats ── --}}
    @php
        $approved  = $applications->where('status', 'approved')->count();
        $rejected  = $applications->where('status', 'rejected')->count();
        $pending   = $applications->where('status', 'pending')->count();
        $reviewed  = $applications->where('status', 'reviewed')->count();
        $assigned  = $applications->where('status', 'assigned')->count();
    @endphp

    <div class="stats-row">
        <div class="stat-box">
            <span class="num">{{ $applications->count() }}</span>
            <span class="label">Total</span>
        </div>
        <div class="stat-box">
            <span class="num" style="color:#065f46;">{{ $approved }}</span>
            <span class="label">Approved</span>
        </div>
        <div class="stat-box">
            <span class="num" style="color:#991b1b;">{{ $rejected }}</span>
            <span class="label">Rejected</span>
        </div>
        <div class="stat-box">
            <span class="num" style="color:#92400e;">{{ $pending }}</span>
            <span class="label">Pending</span>
        </div>
        <div class="stat-box">
            <span class="num" style="color:#5b21b6;">{{ $reviewed }}</span>
            <span class="label">Reviewed</span>
        </div>
        <div class="stat-box">
            <span class="num" style="color:#1e40af;">{{ $assigned }}</span>
            <span class="label">Assigned</span>
        </div>
    </div>

    {{-- ── Applications Table ── --}}
    <div class="section-heading">Application Details</div>

    @if($applications->count() > 0)
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Candidate</th>
                <th>Position / Department</th>
                <th>Applied Date</th>
                <th>Deadline</th>
                <th>Priority</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($applications as $index => $application)
                @php
                    // Status badge class
                    $statusClass = match($application->status) {
                        'approved' => 'badge-approved',
                        'rejected' => 'badge-rejected',
                        'pending'  => 'badge-pending',
                        'assigned' => 'badge-assigned',
                        'reviewed' => 'badge-reviewed',
                        default    => 'badge-default',
                    };

                    // Priority
                    $daysRemaining = $application->jobPosting
                        ? (int) now()->diffInDays($application->jobPosting->deadline, false)
                        : 0;

                    if ($application->manual_priority) {
                        $priorityText  = ucfirst($application->manual_priority);
                        $priorityClass = 'priority-' . $application->manual_priority;
                    } else {
                        if ($daysRemaining <= 2) {
                            $priorityText  = 'High';
                            $priorityClass = 'priority-high';
                        } elseif ($daysRemaining <= 5) {
                            $priorityText  = 'Medium';
                            $priorityClass = 'priority-medium';
                        } elseif ($daysRemaining <= 10) {
                            $priorityText  = 'Low';
                            $priorityClass = 'priority-low';
                        } else {
                            $priorityText  = 'Normal';
                            $priorityClass = 'priority-normal';
                        }
                    }

                    $appliedDate = ($application->submitted_at ?? $application->created_at);
                @endphp

                <tr>
                    <td>{{ $index + 1 }}</td>

                    <td>
                        <span class="candidate-name">{{ $application->name_english ?? 'N/A' }}</span>
                        <span class="candidate-email">{{ $application->email ?? 'N/A' }}</span>
                    </td>

                    <td>
                        <span class="position-title">{{ $application->jobPosting->title ?? 'N/A' }}</span>
                        <span class="dept-name">{{ $application->jobPosting->department ?? 'N/A' }}</span>
                    </td>

                    <td>
                        <span class="date-main">{{ $appliedDate->format('M d, Y') }}</span>
                        <span class="date-bs">{{ $appliedDate->format('h:i A') }}</span>
                    </td>

                    <td>
                        @if($application->jobPosting)
                            <span class="date-main">{{ $application->jobPosting->deadline->format('M d, Y') }}</span>
                            @if($application->jobPosting->deadline_bs)
                                <span class="date-bs">{{ $application->jobPosting->deadline_bs }} (BS)</span>
                            @endif
                        @else
                            <span style="color:#aaa;">N/A</span>
                        @endif
                    </td>

                    <td>
                        <span class="priority-badge {{ $priorityClass }}">{{ $priorityText }}</span>
                        @if(!$application->manual_priority)
                            <span style="font-size:8px;color:#aaa;display:block;margin-top:2px;">Auto</span>
                        @endif
                    </td>

                    <td>
                        <span class="badge {{ $statusClass }}">{{ ucfirst($application->status) }}</span>
                    </td>
                </tr>

                {{-- Show approver notes if present --}}
                @if($application->approver_notes)
                <tr class="notes-row">
                    <td colspan="7">
                        <span class="notes-label">Approver Notes: </span>{{ $application->approver_notes }}
                    </td>
                </tr>
                @endif

            @endforeach
        </tbody>
    </table>

    @else
        <div class="empty-state">
            <span class="icon">📭</span>
            <p>No applications found for the selected criteria.</p>
        </div>
    @endif

    {{-- ── Footer ── --}}
    <div class="footer">
        <div class="footer-left">
            <p><strong>Nepal Oil Corporation Ltd.</strong></p>
            <p>Babarmahal, Kathmandu, Nepal</p>
            <p>Online Recruitment Management System</p>
            <p style="margin-top:4px;">This document is system-generated. No signature required.</p>
        </div>
        <div class="footer-right">
            <div class="confidential-stamp">Confidential</div>
            <p>Printed on: {{ now()->format('Y-m-d H:i:s') }}</p>
            <p>Page 1 of 1</p>
        </div>
    </div>

</div>
</body>
</html>
