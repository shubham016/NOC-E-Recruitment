<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Vacancy List - English</title>
    <style>
        @page {
            margin: 20px;
        }
        body {
            font-family: 'DejaVu Sans'$vacancy, sans-serif;
            font-size: 10px;
            line-height: 1.4;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
        }
        .header h1 {
            margin: 0 0 5px 0;
            font-size: 18px;
            font-weight: bold;
        }
        .header h2 {
            margin: 0 0 5px 0;
            font-size: 14px;
            font-weight: normal;
        }
        .header p {
            margin: 0;
            font-size: 9px;
            color: #666;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        table th {
            background-color: #f0f0f0;
            border: 1px solid #000;
            padding: 6px 4px;
            font-size: 9px;
            font-weight: bold;
            text-align: left;
        }
        table td {
            border: 1px solid #000;
            padding: 5px 4px;
            font-size: 9px;
            vertical-align: top;
        }
        table tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .sn-col { width: 4%; text-align: center; }
        .adv-col { width: 10%; }
        .position-col { width: 22%; }
        .dept-col { width: 15%; }
        .cat-col { width: 12%; }
        .posts-col { width: 6%; text-align: center; }
        .deadline-col { width: 12%; }
        .status-col { width: 8%; text-align: center; }
        .date-col { width: 11%; }
        .footer {
            margin-top: 20px;
            padding-top: 10px;
            border-top: 1px solid #ccc;
            text-align: center;
            font-size: 8px;
            color: #666;
        }
        .status-active { color: #10b981; font-weight: bold; }
        .status-closed { color: #ef4444; font-weight: bold; }
        .status-draft { color: #6b7280; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Government of Nepal</h1>
        <h2>Vacancy Advertisement List</h2>
        <p>Generated on: {{ $generatedDate }} | Total Vacancies: {{ $jobs->count() }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th class="sn-col">S.N.</th>
                <th class="adv-col">Advertisement No.</th>
                <th class="position-col">Position/Level</th>
                <th class="dept-col">Department</th>
                <th class="cat-col">Category</th>
                <th class="posts-col">Posts</th>
                <th class="deadline-col">Deadline</th>
                <th class="status-col">Status</th>
                <th class="date-col">Posted Date</th>
            </tr>
        </thead>
        <tbody>
            @forelse($jobs as $index => $job)
                <tr>
                    <td class="sn-col">{{ $index + 1 }}</td>
                    <td class="adv-col"><strong>{{ $vacancy->advertisement_no }}</strong></td>
                    <td class="position-col">{{ $vacancy->position_level }}</td>
                    <td class="dept-col">{{ $vacancy->department }}</td>
                    <td class="cat-col">
                        {{ ucfirst($vacancy->category) }}
                        @if($vacancy->category === 'internal' && $vacancy->internal_type)
                            <br><small>({{ ucfirst($vacancy->internal_type) }})</small>
                        @endif
                        @if($vacancy->inclusive_type)
                            <br><small>({{ $vacancy->inclusive_type }})</small>
                        @endif
                    </td>
                    <td class="posts-col">{{ $vacancy->number_of_posts }}</td>
                    <td class="deadline-col">
                        {{ $vacancy->deadline->format('Y-m-d') }}
                        @if($vacancy->deadline_bs)
                            <br><small>{{ $vacancy->deadline_bs }}</small>
                        @endif
                        @if($vacancy->double_dastur_date)
                            <br><small style="color: green;">DD: {{ \Carbon\Carbon::parse($vacancy->double_dastur_date)->format('Y-m-d') }}</small>
                        @endif
                    </td>
                    <td class="status-col">
                        <span class="status-{{ $vacancy->status }}">{{ ucfirst($vacancy->status) }}</span>
                    </td>
                    <td class="date-col">{{ $vacancy->created_at->format('Y-m-d') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" style="text-align: center; padding: 20px;">No active vacancies found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p><strong>Note:</strong> This is a computer-generated document. No signature required.</p>
        <p>DD = Double Dastur (Extended Deadline) | For official use only</p>
    </div>
</body>
</html>
