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
            font-size: 12px;
            line-height: 1.4;
        }
        
        .admit-card-wrapper {
            width: 100%;
            max-width: 800px;
            margin: 0 auto;
        }

        .admit-card {
            border: 2px solid #000;
            background: white;
        }

        /* Header Section */
        .header-section {
            display: table;
            width: 100%;
            padding: 15px 20px;
            border-bottom: 1px solid #000;
        }

        .header-left {
            display: table-cell;
            width: 15%;
            vertical-align: middle;
        }

        .logo-wrapper {
            width: 80px;
            height: 80px;
        }

        .noc-logo {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .header-center {
            display: table-cell;
            width: 65%;
            text-align: center;
            vertical-align: middle;
            padding: 0 10px;
        }

        .org-title {
            font-size: 20px;
            font-weight: 700;
            margin: 0 0 5px 0;
            font-family: 'NotoSansDevanagari', sans-serif;
        }

        .org-subtitle {
            font-size: 14px;
            font-weight: 500;
            margin: 0 0 3px 0;
            font-family: 'NotoSansDevanagari', sans-serif;
        }

        .exam-type {
            font-size: 10px;
            margin: 0 0 5px 0;
            font-family: 'NotoSansDevanagari', sans-serif;
        }

        .card-title {
            font-size: 18px;
            font-weight: 700;
            margin: 5px 0 0 0;
            font-family: 'NotoSansDevanagari', sans-serif;
        }

        .header-right {
            display: table-cell;
            width: 20%;
            vertical-align: middle;
        }

        .photo-wrapper {
            width: 120px;
            height: 140px;
            border: 2px solid #000;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f9f9f9;
            margin-left: auto;
            position: relative;
        }

        .candidate-photo {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* NOC Logo Stamp Overlay */
        .noc-stamp-overlay {
            position: absolute;
            top: -15px;
            left: -30px;
            width: 80px;
            height: 80px;
            opacity: 0.9;
            z-index: 10;
        }

        /* Content Section */
        .content-section {
            display: table;
            width: 100%;
            padding: 15px 20px;
        }

        .details-left {
            display: table-cell;
            width: 60%;
            vertical-align: top;
            padding-right: 15px;
        }

        .details-right {
            display: table-cell;
            width: 40%;
            vertical-align: top;
        }

        /* Info Table */
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }

        .info-table tr {
            border-bottom: 1px solid #ddd;
        }

        .info-table td {
            padding: 5px 8px;
            font-size: 11px;
            font-family: 'NotoSansDevanagari', sans-serif;
        }

        .info-table .label {
            width: 35%;
            font-weight: 500;
        }

        .info-table .value {
            width: 65%;
        }

        .mt-2 {
            margin-top: 15px;
        }

        .instruction-text {
            margin: 15px 0;
            padding: 10px;
            background: #f9f9f9;
            border: 1px solid #ddd;
        }

        .instruction-text p {
            font-size: 10px;
            line-height: 1.6;
            margin: 0;
            font-family: 'NotoSansDevanagari', sans-serif;
        }

        /* Schedule Table */
        .schedule-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .schedule-table th,
        .schedule-table td {
            border: 1px solid #000;
            padding: 5px 8px;
            font-size: 10px;
            text-align: left;
            font-family: 'NotoSansDevanagari', sans-serif;
        }

        .schedule-table th {
            background: #f0f0f0;
            font-weight: 600;
        }

        .schedule-table thead th:nth-child(1) {
            width: 10%;
        }

        .schedule-table thead th:nth-child(2) {
            width: 15%;
        }

        .schedule-table thead th:nth-child(3) {
            width: 75%;
        }

        /* Citizenship and Signature */
        .citizenship-wrapper {
            width: 100%;
            height: 200px;
            border: 2px solid #000;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f9f9f9;
            margin-bottom: 15px;
        }

        .citizenship-image {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .signature-section {
            margin-top: 20px;
        }

        .signature-box {
            width: 100%;
            height: 60px;
            border: 1px solid #000;
            margin-bottom: 5px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .candidate-signature {
            max-width: 90%;
            max-height: 90%;
            object-fit: contain;
        }

        .signature-labels {
            display: table;
            width: 100%;
            margin-top: 5px;
        }

        .signature-label-left,
        .signature-label-right {
            display: table-cell;
            width: 50%;
            font-size: 9px;
            text-align: center;
            font-family: 'NotoSansDevanagari', sans-serif;
        }

        .signature-label-left p,
        .signature-label-right p {
            margin: 0;
            font-weight: 500;
        }

        /* Instructions Section */
        .instructions-section {
            padding: 15px 20px;
            border-top: 1px solid #000;
        }

        .instructions-section h4 {
            font-size: 12px;
            font-weight: 700;
            margin: 0 0 10px 0;
            font-family: 'NotoSansDevanagari', sans-serif;
        }

        .instructions-section ol {
            margin: 0;
            padding-left: 20px;
        }

        .instructions-section li {
            font-size: 9px;
            line-height: 1.5;
            margin-bottom: 5px;
            font-family: 'NotoSansDevanagari', sans-serif;
        }
    </style>
</head>
<body>
    <div class="admit-card-wrapper">
        <div class="admit-card">
            
            <!-- Header Section -->
            <div class="header-section">
                <div class="header-left">
                    <div class="logo-wrapper">
                        @if(!empty($nocLogoImage))
                            <img src="{{ $nocLogoImage }}" alt="NOC Logo" class="noc-logo">
                        @endif
                    </div>
                </div>
                <div class="header-center">
                    <h2 class="org-title">{{ $application->organization_name ?? 'नेपाल आयल निगम लिमिटेड' }}</h2>
                    <h4 class="org-subtitle">{{ $application->organization_address ?? 'केन्द्रिय कार्यालय, टेकु' }}</h4>
                    <p class="exam-type">{{ $application->exam_type ?? 'खुल्ला तथा समावेशी प्रतियोगितात्मक लिखित / प्रयोगात्मक /अन्तर्वार्ता परीक्षाको' }}</p>
                    <h3 class="card-title">प्रवेश पत्र</h3>
                </div>
                <div class="header-right">
                    <div class="photo-wrapper">
                        @if(!empty($photoImage))
                            <img src="{{ $photoImage }}" alt="Photo" class="candidate-photo">
                        @else
                            <div style="text-align: center; color: #999; font-size: 11px;">
                                फोटो
                            </div>
                        @endif
                        
                        @if(!empty($nocLogoImage))
                            <img src="{{ $nocLogoImage }}" alt="NOC Stamp" class="noc-stamp-overlay">
                        @endif
                    </div>
                </div>
            </div>

            <!-- Main Content Area -->
            <div class="content-section">
                
                <!-- Left: Candidate Details -->
                <div class="details-left">
                    <table class="info-table">
                        <tr>
                            <td class="label">रोल नं.</td>
                            <td class="value">: {{ $application->roll_number ?? $application->id }}</td>
                        </tr>
                        <tr>
                            <td class="label">नाम, थर</td>
                            <td class="value">: {{ $application->name_nepali ?? '' }}</td>
                        </tr>
                        <tr>
                            <td class="label">Name, Surname</td>
                            <td class="value">: {{ $application->name_english ?? $candidate->name }}</td>
                        </tr>
                    </table>

                    <table class="info-table mt-2">
                        <tr>
                            <td class="label">विज्ञापन नं.</td>
                            <td class="value">: {{ $application->advertisement_number ?? '' }}</td>
                        </tr>
                        <tr>
                            <td class="label">पद / तह</td>
                            <td class="value">: {{ $application->post_title ?? '' }} / {{ $application->level ?? '' }}</td>
                        </tr>
                        <tr>
                            <td class="label">सेवा / समूह</td>
                            <td class="value">: {{ $application->service_type ?? '' }} / {{ $application->service_group ?? '' }}</td>
                        </tr>
                        <tr>
                            <td class="label">खुल्ला / समावेशी</td>
                            <td class="value">: {{ $application->application_type ?? 'खुल्ला' }}</td>
                        </tr>
                        <tr>
                            <td class="label">नागरिता नं.</td>
                            <td class="value">: {{ $application->citizenship_number ?? '' }}</td>
                        </tr>
                    </table>

                    <div class="instruction-text">
                        <p>देहाय बमोजिमको मिति तथा समयमा तिनुभएको उपयुक्त पदको परीक्षामा तपसिलबाट सूचित हुन अनुरोध गरिएको छ । विज्ञापनमा तोकिएको बर्ग बुझ नम्बरको ठहर भएको जुनसुकै अवस्थामा पनि यो अनुरोध पत्र रद्द हुनेछ ।</p>
                    </div>

                    <!-- Exam Schedule Table -->
                    <table class="schedule-table">
                        <thead>
                            <tr>
                                <th>क्र.नं.</th>
                                <th>पत्र</th>
                                <th>मिति, समय</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>१</td>
                                <td>प्रथम</td>
                                <td>{{ $application->exam_date_first ?? '२०८२-०१-११ / दिउँसोको ०२:०० बजे' }} ({{ $application->exam_venue ?? 'श्री खेत्रि स्याम्पू मा.बि., पिल्खुवाबास' }})</td>
                            </tr>
                            <tr>
                                <td>२</td>
                                <td>द्वितिय</td>
                                <td>{{ $application->exam_date_second ?? '२०८२-०१-११ / दिउँसोको ०२:०० बजे' }} ({{ $application->exam_venue ?? 'श्री खेत्रि स्याम्पू मा.बि., पिल्खुवाबास' }})</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Right: Citizenship and Signature -->
                <div class="details-right">
                    <div class="citizenship-wrapper">
                        @if(!empty($citizenshipImage))
                            <img src="{{ $citizenshipImage }}" alt="Citizenship" class="citizenship-image">
                        @else
                            <div style="text-align: center; color: #999;">
                                <p style="font-size: 11px; margin: 0;">नागरिकता प्रमाणपत्र</p>
                            </div>
                        @endif
                    </div>

                    <div class="signature-section">
                        <div class="signature-box">
                            @if(!empty($signatureImage))
                                <img src="{{ $signatureImage }}" alt="Signature" class="candidate-signature">
                            @endif
                        </div>
                        <div class="signature-labels">
                            <div class="signature-label-left">
                                <p>उम्मेदवार दस्तखत</p>
                            </div>
                            <div class="signature-label-right">
                                <p>आधिकारिक दस्तखत</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Instructions Section -->
            <div class="instructions-section">
                <h4>उम्मेदवारले पालन गर्नुपर्ने निम्नाहरू :</h4>
                <ol>
                    <li>उम्मेदवारले उपस्थितिसमय काखोली मसी भएको इन्टर/कलम मात्र प्रयोग गर्नुपर्नेछ।</li>
                    <li>प्रवेश पत्र बिना कुनै पनि उम्मेदवारलाई परीक्षामा सम्मिलित नगराइने र प्रवेश पत्र अनिवार्य रूपमा साथमा लिइ परीक्षा सञ्चालन हुन्छसम्म कायाप्त राख्न र प्रत्येक प्रश्न पत्रको परीक्षा प्रस्तुत हुन्दाको कालावधि १ घण्टा अगावै प्रवेश कार्यालय बुझाउनुपर्दछ ।</li>
                    <li>लिखित परीक्षा नहीजुन मान्यता प्राप्त अन्तर्गत हुने दिनमा पनि प्रदेयेश रुपमें प्रस्तृत हुनेछ । ।</li>
                    <li>लिखित परीक्षा सुरु भई ३० मिनेट बग्ने समयावधि गुज्रेपछि परीक्षा हलमा प्रवेश गर्न नपाईने र उक्त समयसम्म प्रवेश गर्न नपाई विना अनुमति गत्यागना प्रवेश नै दिइने छैन । त्यस्तो परीक्षा हलमा प्रवेश नगर्न आएस अर्लीको संक्षिप्त पनि हुनेछन् वन्द्यार्यो छैन ।</li>
                    <li>परीक्षा हलमा प्रवेश गर्दा केवटी पनि उम्मेदवारहरुको परीक्षा भाबिर जानु अनुरोध छिटोई छ ।</li>
                    <li>परीक्षा हलमा मोबाइल, क्यालक्यूलेटर, क्यामेरा, पेजर आदि जास्तै राखू हुँदैन । उम्मेदवारले आफसंग क्यानबारी र संबन्धित पत्र हुनैछ । ।</li>
                    <li>परीक्षा हलमा उम्मेदवारले परीक्षा पदबारी निपुणता कुनै पनि आवमा कुरुना जैनदखाने परीक्षा हलबाट निस्काशन गरी तुसैम्मा काग्स्मा बग्गीगमेमा गरावरिद् गरीश र लिस्काही निर्काशन गरीएका उम्मेदवारकै पत्र विज्ञापित परीक्षा कार्यक्रम ब्रिदीस हुने छैन ।</li>
                    <li>उम्मेदवारले परीक्षा दिएको दिनमा हानिन आफिवार्य रुपमा गर्नुनुपर्ने छ ।</li>
                    <li>परीक्षा भवनमा झोला, मोबाइल फोन तथा अन्य इलेक्ट्रोनिक्स डिभाइइएहरु लैजान निषेध गरिएको छ।</li>
                    <li>परीक्षा संचालन हुने ठिन आफनोमै विना पर्ने त्यस्मा आफनोमै पुरे नम्बरको कुनै जिम्मा निषेध परीक्षा कार्यक्रम ब्रिदीको हुने छैन।</li>
                    <li>सदुद्वार बहुउत्तर (Multiple Choice) प्रश्नको उत्तर शेड्डा आफिवार्य KEY उत्तरपुस्तिका लेखुपर्दछ । नदोषी उत्तरपुस्तिकामा ब्याप पद नदुष्ट साईं बदुसार ओएअंजो छुनै अक्षर (Capital Letter) मा A, B, C, D लेखिएको उत्तरमार्ट मात्र मान्यता दिइनेछ।</li>
                    <li>परीक्षाना सम्बन्धित निकायबाट गारी भएको प्रवेश पत्र तथा आनोनैं नामसङ्कार वा नेपालि राज्य बाट्नै फाटो सर्मुरो कुनै पत्र विदबोध्न अनिबार्य रुपमा लिई आफुनु पर्नेछ।</li>
                    <li>कुनै उम्मेदवारले प्रश्नपत्रमा राष्टको उत्तयोको सम्बन्धमा सोधु पर्न परीक्षामा सम्मिलित अन्य उम्मेदवारलाई बाधा नपुर्ने निरोक्ष्यैलाई सोधु पर्नेछ ।</li>
                </ol>
            </div>
        </div>
    </div>
</body>
</html>