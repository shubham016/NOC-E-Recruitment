<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ __('admin.approvers_report') }}</title>
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
            background: #1a3a6b;
            color: #fff;
        }
        .nowrap { white-space: nowrap; }
        .wrap   { word-wrap: break-word; overflow-wrap: break-word; }
        .center { text-align: center; }
    </style>
</head>
<body>
    <div class="org-header">
        <h2>{{ config('app.org_name', 'Nepal Oil Corporation Limited') }}</h2>
        <p class="report-title">{{ __('admin.approvers_report') }}</p>
        <p>{{ __('admin.generated') }}: {{ now()->format('Y-m-d H:i') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th class="nowrap center">{{ __('admin.sn') }}</th>
                <th class="wrap" style="width:22%;">{{ __('admin.name') }}</th>
                <th class="wrap" style="width:28%;">{{ __('admin.email') }}</th>
                <th class="nowrap">{{ __('admin.status') }}</th>
                <th class="nowrap center">{{ __('admin.approved') }}</th>
                <th class="nowrap center">{{ __('admin.rejected') }}</th>
                <th class="nowrap center">{{ __('admin.total_actioned') }}</th>
            </tr>
        </thead>
        <tbody>
            @forelse($approvers as $i => $a)
                <tr>
                    <td class="center nowrap">{{ $i + 1 }}</td>
                    <td class="wrap">{{ $a->name }}</td>
                    <td class="wrap">{{ $a->email }}</td>
                    <td class="nowrap">{{ __('admin.' . ($a->status ?? 'active')) }}</td>
                    <td class="center nowrap">{{ $a->approved_count ?? 0 }}</td>
                    <td class="center nowrap">{{ $a->rejected_count ?? 0 }}</td>
                    <td class="center nowrap">{{ ($a->approved_count ?? 0) + ($a->rejected_count ?? 0) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="text-align:center;padding:12px;">{{ __('admin.no_records') }}</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
