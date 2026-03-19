<!DOCTYPE html>
<html lang="ne">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>रिक्त पदको सूची - नेपाली</title>
    <style>
        @page {
            margin: 20px;
        }
        body {
            font-family: 'DejaVu Sans'$vacancy, sans-serif;
            font-size: 11px;
            line-height: 1.6;
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
        <h1>नेपाल सरकार</h1>
        <h2>रिक्त पद विज्ञापन सूची</h2>
        <p>उत्पन्न मिति: {{ $generatedDate }} | कुल रिक्त पदहरू: {{ $vacancies->count() }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th class="sn-col">क्र.सं.</th>
                <th class="adv-col">विज्ञापन नं.</th>
                <th class="position-col">पद / तह</th>
                <th class="dept-col">सेवा / समूह</th>
                <th class="cat-col">श्रेणी</th>
                <th class="posts-col">पद संख्या</th>
                <th class="deadline-col">अन्तिम मिति</th>
                <th class="status-col">स्थिति</th>
                <th class="date-col">प्रकाशित मिति</th>
            </tr>
        </thead>
        <tbody>
            @forelse($vacancies as $index => $job)
                <tr>
                    <td class="sn-col">{{ $index + 1 }}</td>
                    <td class="adv-col"><strong>{{ $vacancy->advertisement_no }}</strong></td>
                    <td class="position-col">{{ $vacancy->position_level }}</td>
                    <td class="dept-col">{{ $vacancy->department }}</td>
                    <td class="cat-col">
                        @if($vacancy->category === 'open')
                            खुल्ला
                        @elseif($vacancy->category === 'inclusive')
                            समावेशी
                        @elseif($vacancy->category === 'internal')
                            आन्तरिक
                        @endif

                        @if($vacancy->category === 'internal' && $vacancy->internal_type)
                            <br><small>
                                @if($vacancy->internal_type === 'open')
                                    (खुल्ला)
                                @elseif($vacancy->internal_type === 'inclusive')
                                    (समावेशी)
                                @endif
                            </small>
                        @endif

                        @if($vacancy->inclusive_type)
                            <br><small>({{ $vacancy->inclusive_type }})</small>
                        @endif
                    </td>
                    <td class="posts-col">{{ $vacancy->number_of_posts }}</td>
                    <td class="deadline-col">
                        @if($vacancy->deadline_bs)
                            {{ $vacancy->deadline_bs }} बि.सं.
                        @else
                            {{ $vacancy->deadline->format('Y-m-d') }}
                        @endif
                        @if($vacancy->double_dastur_bs)
                            <br><small style="color: green;">दोहोरो दस्तुर: {{ $vacancy->double_dastur_bs }}</small>
                        @elseif($vacancy->double_dastur_date)
                            <br><small style="color: green;">दोहोरो दस्तुर: {{ \Carbon\Carbon::parse($vacancy->double_dastur_date)->format('Y-m-d') }}</small>
                        @endif
                    </td>
                    <td class="status-col">
                        <span class="status-{{ $vacancy->status }}">
                            @if($vacancy->status === 'active')
                                सक्रिय
                            @elseif($vacancy->status === 'closed')
                                बन्द
                            @elseif($vacancy->status === 'draft')
                                मस्यौदा
                            @endif
                        </span>
                    </td>
                    <td class="date-col">{{ $vacancy->created_at->format('Y-m-d') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" style="text-align: center; padding: 20px;">कुनै सक्रिय रिक्त पद फेला परेन।</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p><strong>नोट:</strong> यो कम्प्युटर-जनरेट गरिएको कागजात हो। हस्ताक्षर आवश्यक छैन।</p>
        <p>दोहोरो दस्तुर = विस्तारित समय सीमा | आधिकारिक प्रयोगको लागि मात्र</p>
    </div>
</body>
</html>
