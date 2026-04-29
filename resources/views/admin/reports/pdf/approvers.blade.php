<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Approvers Report</title>
    <style>
        @page { margin: 10mm 12mm; size: A4 portrait; }
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
        <p class="report-title">Approvers Report</p>
        <p>Generated: {{ now()->format('Y-m-d H:i') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th class="nowrap center">S.N.</th>
                <th class="wrap" style="width:22%;">Name</th>
                <th class="wrap" style="width:28%;">Email</th>
                <th class="nowrap">Status</th>
                <th class="nowrap center">Approved</th>
                <th class="nowrap center">Rejected</th>
                <th class="nowrap center">Total Actioned</th>
            </tr>
        </thead>
        <tbody>
            @forelse($approvers as $i => $a)
                <tr>
                    <td class="center nowrap">{{ $i + 1 }}</td>
                    <td class="wrap">{{ $a->name }}</td>
                    <td class="wrap">{{ $a->email }}</td>
                    <td class="nowrap">{{ ucfirst($a->status ?? 'active') }}</td>
                    <td class="center nowrap">{{ $a->approved_count ?? 0 }}</td>
                    <td class="center nowrap">{{ $a->rejected_count ?? 0 }}</td>
                    <td class="center nowrap">{{ ($a->approved_count ?? 0) + ($a->rejected_count ?? 0) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="text-align:center;padding:12px;">No records found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
