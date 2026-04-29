<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Vacancy List Export</title>
    <style>
        @page { margin: 10mm 12mm; }
        * { box-sizing: border-box; }
        body {
            font-family: Arial, sans-serif;
            font-size: 10px;
            background: #fff;
            color: #000;
            margin: 0;
            padding: 0 2px 0 0;
        }
        .org-header { text-align: center; margin-bottom: 12px; }
        .org-header h2 { margin: 0 0 4px 0; font-size: 14px; font-weight: bold; }
        .notice-line { font-size: 10px; font-weight: bold; margin: 0; }

        table {
            width: 100%;
            table-layout: auto;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #000;
            padding: 5px;
            font-size: 10px;
            background: #fff;
            vertical-align: top;
        }
        th { font-weight: bold; text-align: center; }

        .center  { text-align: center; }
        .nowrap  { white-space: nowrap; }
        .wrap    { word-wrap: break-word; overflow-wrap: break-word; }

        .stacked { padding: 0; }
        .srow    { padding: 5px; font-size: 10px; text-align: center; white-space: nowrap; }
        .sdiv    { height: 1px; background-color: #000; font-size: 0; line-height: 0; margin: 0; }
    </style>
</head>
<body>
    <div class="org-header">
        <h2>Nepal Oil Corporation Limited</h2>
        <p class="notice-line">
            Notice Number: {{ $jobs->pluck('notice_no')->filter()->unique()->implode(', ') ?: '-' }}
        </p>
    </div>

    <table>
        <thead>
            <tr>
                <th class="nowrap center">S.N</th>
                <th class="nowrap">Adv. No.</th>
                <th class="wrap" style="width:22%;">Position / Level</th>
                <th class="wrap" style="width:18%;">Service/Group</th>
                <th class="nowrap center">Open/Inclusive</th>
                <th class="nowrap center">Demand</th>
                <th class="wrap" style="width:30%;">Qualifications</th>
            </tr>
        </thead>
        <tbody>
            @foreach($jobs as $index => $job)
                @php
                    $types = [];
                    if ($job->category === 'internal_appraisal') {
                        $types[] = 'Internal Appraisal';
                    } else {
                        if ($job->has_open) { $types[] = 'Open'; }
                        if ($job->has_inclusive) {
                            $raw = $job->inclusive_type;
                            if ($raw) {
                                $decoded = is_array($raw) ? $raw : json_decode($raw, true);
                                if (is_array($decoded) && count($decoded)) {
                                    foreach ($decoded as $t) { $types[] = ucfirst($t); }
                                } else {
                                    $types[] = ucfirst(is_string($raw) ? trim($raw, '"') : $raw);
                                }
                            } else { $types[] = 'Inclusive'; }
                        }
                        if ($job->has_internal && !$job->has_internal_open && !$job->has_internal_inclusive) { $types[] = 'Internal'; }
                        if ($job->has_internal_open)     { $types[] = 'Internal/Open'; }
                        if ($job->has_internal_inclusive) {
                            $rawInt = $job->internal_inclusive_types;
                            if ($rawInt) {
                                $decodedInt = is_array($rawInt) ? $rawInt : json_decode($rawInt, true);
                                if (is_array($decodedInt) && count($decodedInt)) {
                                    foreach ($decodedInt as $t) { $types[] = 'Internal/' . ucfirst($t); }
                                } else { $types[] = 'Internal/Inclusive'; }
                            } else { $types[] = 'Internal/Inclusive'; }
                        }
                        if (empty($types)) { $types[] = ucfirst(str_replace('_', ' ', $job->category)); }
                    }

                    $demandVals = [];
                    $dp = $job->demand_posts ?? [];
                    if ($job->category === 'internal_appraisal') {
                        $demandVals[] = $dp['is_internal_appraisal'] ?? $job->number_of_posts;
                    } else {
                        if ($job->has_open) { $demandVals[] = $dp['has_open'] ?? $job->number_of_posts; }
                        if ($job->has_inclusive) {
                            $raw = $job->inclusive_type;
                            $decoded = $raw ? (is_array($raw) ? $raw : json_decode($raw, true)) : null;
                            if (is_array($decoded) && count($decoded)) {
                                $inclKeyMap = [
                                    'Women' => 'incl_women', 'A.J' => 'incl_aj',
                                    'Madhesi' => 'incl_madhesi', 'Janajati' => 'incl_janajati',
                                    'Apanga' => 'incl_apanga', 'Dalit' => 'incl_dalit',
                                    'Pichadiyeko Chetra' => 'incl_pichadiyeko',
                                ];
                                foreach ($decoded as $t) {
                                    $k = $inclKeyMap[$t] ?? null;
                                    $demandVals[] = ($k && isset($dp[$k])) ? $dp[$k] : ($job->inclusive_posts ?? $job->number_of_posts);
                                }
                            } else { $demandVals[] = $job->inclusive_posts ?? $job->number_of_posts; }
                        }
                        if ($job->has_internal_open) { $demandVals[] = $dp['has_internal_open'] ?? $job->number_of_posts; }
                        if ($job->has_internal_inclusive) {
                            $rawInt = $job->internal_inclusive_types;
                            $decodedInt = $rawInt ? (is_array($rawInt) ? $rawInt : json_decode($rawInt, true)) : null;
                            if (is_array($decodedInt) && count($decodedInt)) {
                                $intKeyMap = [
                                    'Women' => 'internal_incl_women', 'A.J' => 'internal_incl_aj',
                                    'Madhesi' => 'internal_incl_madhesi', 'Janajati' => 'internal_incl_janajati',
                                    'Apanga' => 'internal_incl_apanga', 'Dalit' => 'internal_incl_dalit',
                                    'Pichadiyeko Chetra' => 'internal_incl_pichadiyeko',
                                ];
                                foreach ($decodedInt as $t) {
                                    $k = $intKeyMap[$t] ?? null;
                                    $demandVals[] = ($k && isset($dp[$k])) ? $dp[$k] : $job->number_of_posts;
                                }
                            } else { $demandVals[] = $job->number_of_posts; }
                        }
                        if (empty($demandVals)) { $demandVals[] = $job->number_of_posts; }
                    }

                    $positionDisplay = $job->position;
                    if ($job->level) { $positionDisplay .= ' / Level ' . $job->level; }
                @endphp

                <tr>
                    <td class="nowrap center">{{ $index + 1 }}</td>
                    <td class="nowrap">{{ $job->advertisement_no }}</td>
                    <td class="wrap">{{ $positionDisplay }}</td>
                    <td class="wrap">{{ $job->service_group ?: $job->department }}</td>

                    <td class="stacked">
                        @foreach($types as $i => $type)
                            @if($i > 0)<div class="sdiv"></div>@endif
                            <div class="srow">{{ $type }}</div>
                        @endforeach
                    </td>

                    <td class="stacked">
                        @foreach($demandVals as $i => $val)
                            @if($i > 0)<div class="sdiv"></div>@endif
                            <div class="srow">{{ $val }}</div>
                        @endforeach
                    </td>

                    <td class="wrap">{{ $job->minimum_qualification ?: '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
