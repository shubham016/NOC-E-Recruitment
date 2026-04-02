<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Vacancy List Export</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #333;
        }
        .header h2 {
            margin: 0;
            color: #333;
        }
        .header p {
            margin: 5px 0;
            color: #666;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th {
            background-color: #f3f4f6;
            color: #000;
            padding: 8px;
            text-align: left;
            border: 1px solid #000;
            font-weight: bold;
            font-size: 10px;
        }
        td {
            padding: 6px 8px;
            border: 1px solid #ddd;
            font-size: 10px;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .status-active { color: #10b981; font-weight: bold; }
        .status-closed { color: #ef4444; font-weight: bold; }
        .status-draft { color: #6b7280; font-weight: bold; }
        .footer {
            margin-top: 20px;
            padding-top: 10px;
            border-top: 1px solid #ddd;
            text-align: center;
            font-size: 10px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>Vacancy List Export</h2>
        <p>Generated on: {{ now()->format('F d, Y h:i A') }}</p>
        <p>Total Vacancies: {{ count($jobs) }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 5%;">S.N</th>
                <th style="width: 10%;">Adv. No.</th>
                <th style="width: 20%;">Position</th>
                <th style="width: 12%;">Service/Group</th>
                <th style="width: 10%;">Category</th>
                <th style="width: 5%;">Posts</th>
                <th style="width: 8%;">Apps</th>
                <th style="width: 10%;">Deadline</th>
                <th style="width: 8%;">Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($jobs as $index => $job)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $job->advertisement_no }}</td>
                    <td>{{ $job->position_level }}</td>
                    <td>{{ $job->service_group ?: $job->department }}</td>
                    <td>
                        @if($job->category == 'internal_appraisal')
                            Internal Appraisal
                        @elseif($job->category == 'internal')
                            Internal{{ $job->internal_type ? '/' . ucfirst($job->internal_type) : '' }}
                        @elseif($job->category == 'inclusive')
                            Inclusive{{ $job->inclusive_type ? '/' . ucfirst($job->inclusive_type) : '' }}
                        @else
                            {{ ucfirst($job->category) }}
                        @endif
                    </td>
                    <td>{{ $job->number_of_posts }}</td>
                    <td>{{ $job->applications_count ?? 0 }}</td>
                    <td>{{ $job->deadline ? $job->deadline->format('Y-m-d') : '-' }}</td>
                    <td class="status-{{ $job->status }}">{{ ucfirst($job->status) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>This is a system-generated document. No signature required.</p>
        <p>&copy; {{ now()->year }} E-Recruitment System. All rights reserved.</p>
    </div>
</body>
</html>
