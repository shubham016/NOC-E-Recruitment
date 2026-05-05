<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Applications Report</title>
    <style>
        @page { margin: 10mm 12mm; size: A4 landscape; }
        * { box-sizing: border-box; }
        body {
            font-family: Arial, sans-serif;
            font-size: 9px;
            background: #fff;
            color: #000;
            margin: 0;
            padding: 0 2px 0 0;
        }
        .org-header { text-align: center; margin-bottom: 10px; }
        .org-header h2 { margin: 0 0 3px 0; font-size: 13px; font-weight: bold; }
        .org-header p { margin: 0; font-size: 9px; }
        .report-title { font-size: 11px; font-weight: bold; margin: 0 0 2px 0; }
        table {
            width: 100%;
            table-layout: auto;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #000;
            padding: 4px 5px;
            font-size: 9px;
            background: #fff;
            vertical-align: top;
        }
        th {
            font-weight: bold;
            text-align: center;
            background: #c9a84c;
            color: #fff;
        }
        .nowrap { white-space: nowrap; }
        .wrap   { word-wrap: break-word; overflow-wrap: break-word; }
        .center { text-align: center; }
    </style>
</head>
<body>
    <div class="org-header">
        <h2>Nepal Oil Corporation Limited</h2>
        <p class="report-title">Applications Report</p>
        <p>Generated: {{ now()->format('Y-m-d H:i') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th class="nowrap center">S.N.</th>
                <th class="nowrap">Adv. No.</th>
                <th class="wrap" style="width:16%;">Applicant Name</th>
                <th class="wrap" style="width:14%;">Email</th>
                <th class="nowrap">Phone</th>
                <th class="wrap" style="width:14%;">Position</th>
                <th class="nowrap">Category</th>
                <th class="nowrap">Status</th>
                <th class="nowrap">Reviewer</th>
                <th class="nowrap">Reviewed At</th>
                <th class="nowrap">Approver</th>
                <th class="nowrap">Applied On</th>
            </tr>
        </thead>
        <tbody>
            @forelse($applications as $i => $app)
                <tr>
                    <td class="center nowrap">{{ $i + 1 }}</td>
                    <td class="nowrap">{{ $app->advertisement_no ?? '-' }}</td>
                    <td class="wrap">{{ $app->name_english }}</td>
                    <td class="wrap">{{ $app->email }}</td>
                    <td class="nowrap">{{ $app->phone ?? '-' }}</td>
                    <td class="wrap">{{ $app->position ?? '-' }}</td>
                    <td class="nowrap">{{ $app->applied_category_label }}</td>
                    <td class="nowrap">{{ ucfirst(str_replace('_', ' ', $app->status ?? '')) }}</td>
                    <td class="nowrap">{{ $app->reviewer?->name ?? '-' }}</td>
                    <td class="nowrap">{{ $app->reviewed_at ? $app->reviewed_at->format('Y-m-d') : '-' }}</td>
                    <td class="nowrap">{{ $app->approver?->name ?? '-' }}</td>
                    <td class="nowrap">{{ $app->created_at->format('Y-m-d') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="12" style="text-align:center;padding:12px;">No records found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
