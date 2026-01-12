<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>प्रवेश पत्र - {{ $application->id }}</title>
    <style>
        @font-face {
            font-family: 'NotoSansDevanagari';
            font-style: normal;
            font-weight: normal;
            src: url('{{ public_path("storage/fonts/NotoSansDevanagari-Regular.ttf") }}') format('truetype');
        }

        @font-face {
            font-family: 'NotoSansDevanagari';
            font-style: normal;
            font-weight: bold;
            src: url('{{ public_path("storage/fonts/NotoSansDevanagari-Bold.ttf") }}') format('truetype');
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'NotoSansDevanagari', 'DejaVu Sans', sans-serif;
            padding: 0;
            font-size: 11px;
            line-height: 1.4;
        }
        
        .admit-card {
            width: 100%;
            max-width: 210mm;
            margin: 0 auto;
            background: white;
        }
        
        .header-section {
            background: #000;
            padding: 3px;
            margin-bottom: 15px;
        }
        
        .top-bar {
            background: white;
            padding: 10px;
            text-align: center;
            position: relative;
        }
        
        .emblem-section {
            text-align: center;
            margin-bottom: 5px;
        }
        
        .emblem-img {
            width: 60px;
            height: 60px;
            margin: 0 auto;
        }
        
        .org-name {
            font-size: 14px;
            font-weight: bold;
            margin: 8px 0 3px 0;
            font-family: 'NotoSansDevanagari', sans-serif;
        }
        
        .org-name-nepali {
            font-size: 12px;
            color: #333;
            margin-bottom: 5px;
            font-family: 'NotoSansDevanagari', sans-serif;
        }
        
        .admit-card-number {
            position: absolute;
            top: 10px;
            left: 10px;
            font-size: 10px;
            font-family: 'NotoSansDevanagari', sans-serif;
        }
        
        .main-content {
            padding: 0 15px;
        }
        
        .content-grid {
            display: table;
            width: 100%;
            margin-bottom: 15px;
        }
        
        .left-section {
            display: table-cell;
            width: 35%;
            vertical-align: top;
            padding-right: 10px;
        }
        
        .center-section {
            display: table-cell;
            width: 40%;
            vertical-align: top;
            padding: 0 10px;
        }
        
        .right-section {
            display: table-cell;
            width: 25%;
            vertical-align: top;
        }
        
        .citizenship-box {
            border: 1px solid #d00;
            padding: 3px;
            height: 180px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f9f9f9;
        }
        
        .citizenship-box img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
        }
        
        .photo-box {
            border: 1px solid #d00;
            width: 100%;
            height: 180px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f9f9f9;
        }
        
        .photo-box img {
            max-width: 100%;
            max-height: 100%;
            object-fit: cover;
        }
        
        .signature-box {
            border: 1px solid #999;
            margin-top: 10px;
            height: 40px;
            background: white;
        }
        
        .signature-label {
            font-size: 9px;
            text-align: center;
            margin-top: 2px;
            font-family: 'NotoSansDevanagari', sans-serif;
        }
        
        .details-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 10px;
        }
        
        .details-table td {
            padding: 6px 8px;
            border: 1px solid #999;
            vertical-align: middle;
            font-family: 'NotoSansDevanagari', sans-serif;
        }
        
        .details-table .label-col {
            background: #e8f4f8;
            font-weight: bold;
            width: 45%;
            font-size: 10px;
            white-space: nowrap;
            font-family: 'NotoSansDevanagari', sans-serif;
        }
        
        .details-table .value-col {
            background: white;
            font-size: 10px;
            font-family: 'NotoSansDevanagari', sans-serif;
        }
        
        .section-title {
            background: #e8f4f8;
            padding: 6px;
            font-weight: bold;
            text-align: center;
            border: 1px solid #999;
            margin: 12px 0 8px 0;
            font-size: 11px;
            font-family: 'NotoSansDevanagari', sans-serif;
        }
        
        .exam-details {
            width: 100%;
            margin: 10px 0;
        }
        
        .exam-row {
            display: table;
            width: 100%;
            border: 1px solid #999;
        }
        
        .exam-label {
            display: table-cell;
            width: 35%;
            padding: 6px 8px;
            background: #e8f4f8;
            font-weight: bold;
            border-right: 1px solid #999;
            font-size: 10px;
            vertical-align: middle;
            font-family: 'NotoSansDevanagari', sans-serif;
        }
        
        .exam-value {
            display: table-cell;
            padding: 6px 8px;
            background: white;
            font-size: 10px;
            vertical-align: middle;
            font-family: 'NotoSansDevanagari', sans-serif;
        }
        
        .instructions {
            margin-top: 15px;
            padding: 10px;
            background: #fffef5;
            border: 1px solid #999;
        }
        
        .instructions-title {
            font-weight: bold;
            margin-bottom: 8px;
            text-align: center;
            font-size: 11px;
            font-family: 'NotoSansDevanagari', sans-serif;
        }
        
        .instructions ul {
            margin-left: 20px;
            font-size: 10px;
        }
        
        .instructions li {
            margin-bottom: 4px;
            line-height: 1.5;
            font-family: 'NotoSansDevanagari', sans-serif;
        }
        
        .footer-black {
            background: #000;
            padding: 3px;
            margin-top: 15px;
        }
        
        .clearfix::after {
            content: "";
            display: table;
            clear: both;
        }
        
    </style>
</head>
<body>
    <div class="admit-card">
    
        <!-- Header with Emblem -->
        <div class="top-bar">
            <div class="admit-card-number">
                प्रवेश पत्र नं. {{ $application->roll_number ?? $application->id }}
            </div>
            
            <div class="emblem-section">
                @if(!empty($emblemImage))
                    <img src="{{ $emblemImage }}" class="emblem-img" alt="Nepal Emblem">
                @else
                    <div class="emblem-img" style="border: 1px solid #ccc; border-radius: 50%; background: #f5f5f5;"></div>
                @endif
            </div>
            
            <div class="org-name">{{ $application->organization_name ?? 'लोक सेवा आयोग' }}</div>
            <div class="org-name-nepali">{{ $application->post_title ?? 'नेपाल' }}</div>
        </div>
        
        <!-- Main Content -->
        <div class="main-content">
            <div class="content-grid">
                <!-- Left: Citizenship Card -->
                <div class="left-section">
                    <div class="citizenship-box">
                        @if(!empty($citizenshipImage))
                            <img src="{{ $citizenshipImage }}" alt="Citizenship">
                        @else
                            <div style="text-align: center; color: #999;">
                                <div style="font-size: 9px;">नागरिकता</div>
                                <div style="font-size: 8px;">Citizenship Card</div>
                            </div>
                        @endif
                    </div>
                </div>
                
                <!-- Center: Details -->
                <div class="center-section">
                    <table class="details-table">
                        <tr>
                            <td class="label-col">क) नाम थर (अंग्रेजीमा)</td>
                            <td class="value-col">{{ $application->name_english ?? $candidate->name }}</td>
                        </tr>
                        <tr>
                            <td class="label-col">ख) नाम थर (देवनागरीमा)</td>
                            <td class="value-col">{{ $application->name_nepali ?? '' }}</td>
                        </tr>
                        <tr>
                            <td class="label-col">ग) लिङ्ग</td>
                            <td class="value-col">{{ $application->gender ?? $candidate->gender }}</td>
                        </tr>
                        <tr>
                            <td class="label-col">घ) जन्म मिति (बि.सं.)</td>
                            <td class="value-col">{{ $application->birth_date_bs ?? $candidate->date_of_birth_bs ?? '' }}</td>
                        </tr>
                        <tr>
                            <td class="label-col">ङ) बाबुको नाम (देवनागरीमा)</td>
                            <td class="value-col">{{ $application->father_name_nepali ?? '' }}</td>
                        </tr>
                        <tr>
                            <td class="label-col">च) स्थायी ठेगाना</td>
                            <td class="value-col">
                                {{ $application->permanent_district ?? '' }}
                                @if($application->permanent_municipality ?? '')
                                    , {{ $application->permanent_municipality }}
                                @endif
                            </td>
                        </tr>
                    </table>
                    
                    <div class="section-title">सम्पर्क विवरण / Contact Details</div>
                    <table class="details-table">
                        <tr>
                            <td class="label-col">मोबाइल नम्बर</td>
                            <td class="value-col">{{ $application->phone ?? '' }}</td>
                        </tr>
                        <tr>
                            <td class="label-col">नागरिकता नं</td>
                            <td class="value-col">{{ $application->citizenship_number }}</td>
                        </tr>
                        <tr>
                            <td class="label-col">जारी मिति</td>
                            <td class="value-col">{{ $application->citizenship_issue_date_bs ?? '' }}</td>
                        </tr>
                        <tr>
                            <td class="label-col">जारी जिल्ला</td>
                            <td class="value-col">{{ $application->citizenship_issue_district ?? '' }}</td>
                        </tr>
                    </table>
                </div>
                
                <!-- Right: Photo -->
                <div class="right-section">
                    <div class="photo-box">
                        @if(!empty($photoImage))
                            <img src="{{ $photoImage }}" alt="Photo">
                        @else
                            <div style="text-align: center; color: #999; font-size: 9px;">
                                फोटो<br>Photo
                            </div>
                        @endif
                    </div>
                    <div class="signature-box"></div>
                    <div class="signature-label">
                        <span style="font-family: 'NotoSansDevanagari';">उम्मेदवारको दस्तखत</span><br>
                        <span style="font-family: Arial; font-size: 8px;">Candidate's Signature</span>
                    </div>

                </div>
            </div>
            
            <!-- Exam Details -->
            <div class="section-title">परीक्षा सम्बन्धी विवरण / Examination Details</div>
            <div class="exam-details">
                <div class="exam-row">
                    <div class="exam-label">क) परीक्षाको प्रकार</div>
                    <div class="exam-value">{{ $application->post_title ?? 'लिखित परीक्षा' }}</div>
                </div>
                <div class="exam-row">
                    <div class="exam-label">ख) परीक्षा हुने मिति</div>
                    <div class="exam-value">{{ date('Y-m-d', strtotime($application->exam_date)) }} ({{ date('l', strtotime($application->exam_date)) }})</div>
                </div>
                <div class="exam-row">
                    <div class="exam-label">ग) परीक्षा हुने समय</div>
                    <div class="exam-value">{{ $application->exam_time }}</div>
                </div>
                @if(isset($application->reporting_time) && $application->reporting_time)
                <div class="exam-row">
                    <div class="exam-label">घ) रिपोर्टिङ समय</div>
                    <div class="exam-value" style="font-weight: bold; color: #d00;">{{ $application->reporting_time }}</div>
                </div>
                @endif
                <div class="exam-row">
                    <div class="exam-label">ङ) परीक्षा केन्द्र</div>
                    <div class="exam-value">{{ $application->exam_venue }}</div>
                </div>
            </div>
            
            <!-- Instructions -->
            <div class="instructions">
                <div class="instructions-title">सम्बन्धित व्यक्तिलाई विशेष सूचनाहरु</div>
                @if(isset($application->exam_instructions) && $application->exam_instructions)
                    <div style="white-space: pre-line; font-family: 'NotoSansDevanagari', sans-serif;">{{ $application->exam_instructions }}</div>
                @else
                <ul>
                    <li>परीक्षा भवन भित्र मोबाइल लैजान तथा प्रयोग गर्न पाईने छैन।</li>
                    <li>परीक्षा हुने समय भन्दा कम्तिमा ३० मिनेट अगावै परीक्षा केन्द्रमा उपस्थित हुनुपर्नेछ।</li>
                    <li>नीलो वा कालो कलम तथा अन्य आवश्यक सामानहरु आफै ल्याउनु पर्नेछ।</li>
                    <li>परीक्षा शुरु भएपछि कुनै पनि हालतमा परीक्षा केन्द्रमा प्रवेश गर्न पाइने छैन।</li>
                    <li>यो प्रवेश पत्र तथा फोटो सहितको नागरिकता अनिवार्य रुपमा साथमा ल्याउनु पर्नेछ।</li>
                    <li>परीक्षा केन्द्रबाट परीक्षा सुरु हुने समय तथा बस्ने स्थान बारे जानकारी लिनुहोला।</li>
                </ul>
                @endif
            </div>
        </div>
    </div>
</body>
</html>