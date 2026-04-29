<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Candidates Report</title>
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
        <p class="report-title">Registered Candidates Report</p>
        <p>Generated: {{ now()->format('Y-m-d H:i') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th class="nowrap center">S.N.</th>
                <th class="wrap" style="width:18%;">Full Name</th>
                <th class="nowrap">Username</th>
                <th class="wrap" style="width:18%;">Email</th>
                <th class="nowrap">Mobile</th>
                <th class="nowrap">Gender</th>
                <th class="nowrap">City</th>
                <th class="nowrap">State</th>
                <th class="nowrap">Status</th>
                <th class="nowrap">Registered On</th>
            </tr>
        </thead>
        <tbody>
            @forelse($candidates as $i => $c)
                <tr>
                    <td class="center nowrap">{{ $i + 1 }}</td>
                    <td class="wrap">{{ trim($c->first_name . ' ' . $c->middle_name . ' ' . $c->last_name) }}</td>
                    <td class="nowrap">{{ $c->username }}</td>
                    <td class="wrap">{{ $c->email }}</td>
                    <td class="nowrap">{{ $c->mobile_number ?? '-' }}</td>
                    <td class="nowrap">{{ $c->gender ? ucfirst($c->gender) : '-' }}</td>
                    <td class="nowrap">{{ $c->city ?? '-' }}</td>
                    <td class="nowrap">{{ $c->state ?? '-' }}</td>
                    <td class="nowrap">{{ ucfirst($c->status ?? 'active') }}</td>
                    <td class="nowrap">{{ $c->created_at->format('Y-m-d') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="10" style="text-align:center;padding:12px;">No records found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
