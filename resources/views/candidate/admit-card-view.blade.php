@extends('layouts.app')

@section('title', 'View Admit Card')

@section('content')
@section('sidebar-menu')
    <a href="{{ route('candidate.dashboard') }}" class="sidebar-menu-item">
        <i class="bi bi-speedometer2"></i>
        <span>Dashboard</span>
    </a>
    <a href="{{ route('candidate.my-profile') }}" class="sidebar-menu-item">
        <i class="bi bi-person"></i>
        <span>My Profile</span>
    </a>
    <a href="{{ route('candidate.jobs.index') }}" class="sidebar-menu-item">
        <i class="bi bi-search"></i>
        <span>Vacancy</span>
    </a>
    <a href="{{ route('candidate.applications.index') }}" class="sidebar-menu-item">
        <i class="bi bi-file-earmark-text"></i>
        <span>My Applications</span>
    </a>
    <a href="{{ route('candidate.viewresult') }}" class="sidebar-menu-item">
        <i class="bi bi-file-earmark-check"></i>
        <span>View Result</span>
    </a>
    <a href="{{ route('candidate.admit-card') }}" class="sidebar-menu-item active">
        <i class="bi bi-box-arrow-down"></i>
        <span>Download Admit Card</span>
    </a>
    <a href="{{ route('candidate.change-password') }}" class="sidebar-menu-item">
        <i class="bi bi-lock"></i>
        <span>Change Password</span>
    </a>
@endsection

<div class="document-button">
    <a href="{{ route('candidate.admit-card') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Back to Admit Cards
    </a>
    <button onclick="window.print()" class="btn btn-danger float-end">
        <i class="bi bi-printer"></i> Print or Save as PDF
    </button>
</div>

<div class="admit-card-wrapper" id="printableArea">
    <div class="inner-card-wrapper">
        <div class="admit-card">
            
            <!-- Header Section -->
            <div class="header-section">
                <div class="header-left">
                    <div class="logo-wrapper">
                        @if(file_exists(public_path('img/noc-logo.png')))
                            <img src="{{ asset('img/noc-logo.png') }}" alt="NOC Logo" class="noc-logo">
                        @endif
                    </div>
                </div>
                <div class="header-center">
                    <h2 class="org-title">{{ 'नेपाल आयल निगम लिमिटेड' }}</h2>
                    <h4 class="org-subtitle">{{ $application->organization_address ?? 'केन्द्रिय कार्यालय, बबरमहल' }}</h4>
                    <p class="exam-type">{{ $application->exam_type ?? 'खुल्ला तथा समावेशी प्रतियोगितात्मक लिखित / प्रयोगात्मक /अन्तर्वार्ता परीक्षाको' }}</p>
                    <h3 class="card-title">प्रवेश पत्र</h3>
                </div>
                <div class="header-right">
                    <div class="photo-wrapper">
                        @if($photoUrl)
                            <img src="{{ $photoUrl }}" alt="Photo" class="candidate-photo">
                        @else
                            <div class="photo-placeholder">फोटो</div>
                        @endif
                        
                        <!-- NOC Logo Stamp Overlay -->
                        @if(file_exists(public_path('img/images.png')))
                            <img src="{{ asset('img/images.png') }}" alt="NOC Stamp" class="noc-stamp-overlay">
                        @elseif(file_exists(public_path('images/images.png')))
                            <img src="{{ asset('images/images.png') }}" alt="NOC Stamp" class="noc-stamp-overlay">
                        @endif
                    </div>
                </div>
            </div>

            @php
                $npNums = ['0'=>'०','1'=>'१','2'=>'२','3'=>'३','4'=>'४','5'=>'५','6'=>'६','7'=>'७','8'=>'८','9'=>'९'];
                $toNp = fn($s) => strtr((string)$s, $npNums);
                $enNpPhrases = [
                    'General Manager'       => 'महाप्रबन्धक',
                    'Deputy General Manager'=> 'उप महाप्रबन्धक',
                    'Chief Manager'         => 'मुख्य प्रबन्धक',
                    'Senior Manager'        => 'वरिष्ठ प्रबन्धक',
                    'Assistant Manager'     => 'सहायक प्रबन्धक',
                    'Deputy Manager'        => 'उप प्रबन्धक',
                    'Senior Assistant'      => 'वरिष्ठ सहायक',
                    'Junior Assistant'      => 'कनिष्ठ सहायक',
                    'Senior Officer'        => 'वरिष्ठ अधिकृत',
                    'Junior Officer'        => 'कनिष्ठ अधिकृत',
                    'Executive Director'    => 'कार्यकारी निर्देशक',
                    'Manager'               => 'प्रबन्धक',
                    'Assistant'             => 'सहायक',
                    'Officer'               => 'अधिकृत',
                    'Engineer'              => 'इन्जिनियर',
                    'Accountant'            => 'लेखापाल',
                    'Supervisor'            => 'पर्यवेक्षक',
                    'Director'              => 'निर्देशक',
                    'Human Resources'       => 'मानव संसाधन',
                    'Information Technology'=> 'सूचना प्रविधि',
                    'Technical'             => 'प्राविधिक',
                    'Finance'               => 'वित्त',
                    'Administration'        => 'प्रशासन',
                    'Marketing'             => 'बजार',
                    'Operations'            => 'सञ्चालन',
                    'Planning'              => 'योजना',
                    'Procurement'           => 'खरिद',
                    'Accounts'              => 'लेखा',
                    'Legal'                 => 'कानुनी',
                    'IT'                    => 'सूचना प्रविधि',
                    'Others'                => 'अन्य',
                    'Other'                 => 'अन्य',
                    'General'               => 'सामान्य',
                    'Level'                 => 'तह',
                ];
                $toNpText = function($str) use ($enNpPhrases, $toNp) {
                    if (!$str) return '';
                    foreach ($enNpPhrases as $en => $np) {
                        $str = preg_replace('/\b'.preg_quote($en,'/').'\\b/i', $np, $str);
                    }
                    return $toNp($str);
                };
            @endphp

            <!-- Main Content Area -->
            <div class="content-section">
                
                <!-- Left: Candidate Details -->
                <div class="details-left">
                    <table class="info-table">
                        <tr>
                            <td class="label">रोल नं.</td>
                            <td class="value">: <strong>{{ $application->roll_number ?? $application->id }}</td>
                        </tr>
                        <tr>
                            <td class="label">नाम, थर</td>
                            <td class="value">: <strong>{{ $application->name_nepali ?? '' }}</td>
                        </tr>
                        <tr>
                            <td class="label">Name, Surname</td>
                            <td class="value">: <strong>{{ $application->name_english ?? $candidate->name }}</td>
                        </tr>
                    </table>

                    <table class="info-table mt-2">
                        <tr>
                            <td class="label">विज्ञापन नं.</td>
                            <td class="value">: <strong>{{ $application->advertisement_no ?? '' }}</td>
                        </tr>
                        <tr>
                            <td class="label">पद / तह</td>
                            <td class="value">: <strong>{{ $toNpText($application->post_title ?? ($job ? $job->position . ($job->level ? ' / Level ' . $job->level : '') : '')) }}</td>
                        </tr>
                        <tr>
                            <td class="label">सेवा / समूह</td>
                            <td class="value">: <strong>{{ $toNpText($application->admit_card_service_group ?? ($job ? $job->service_group : '')) }}</td>
                        </tr>
                        <tr>
                            <td class="label">खुल्ला / समावेशी</td>
                            <td class="value">: <strong>{{ $application->application_type ?? 'खुल्ला' }}</td>
                        </tr>
                        <tr>
                            <td class="label">नागरिकता नं.</td>
                            <td class="value">: <strong>{{ $application->citizenship_number ?? '' }}</td>
                        </tr>
                    </table>

                    <div class="instruction-text">
                        <p>देहाय बमोजिमको मिति तथा समयमा लिइने उक्त पदको परीक्षामा तपाईंलाई सहभागी हुन अनुमति दिइएको छ। विज्ञापनमा तोकिएका शर्तहरू पूरा नभएको ठहर भएकोमा जुनसुकै अवस्थामा पनि यो अनुमति पत्र रद्द हुनेछ।</p>
                    </div>

                    <!-- Exam Schedule Table -->
                    @php
                        $formatExamTime = function ($time) use ($toNp) {
                            if (!$time) return null;

                            $upper = strtoupper($time);
                            if (str_contains($upper, 'AM')) {
                                $period = 'बिहान';
                                $time = trim(str_ireplace('AM', '', $time));
                            } elseif (str_contains($upper, 'PM')) {
                                $period = 'दिउँसोको';
                                $time = trim(str_ireplace('PM', '', $time));
                            } else {
                                $period = null;
                            }

                            return trim(($period ? $period . ' ' : '') . $toNp($time) . ' बजे');
                        };
                        $fallbackVenue = $application->exam_venue ?: null;
                        $dateFirst = $application->exam_date_first ? $toNp($application->exam_date_first) : null;
                        $timeFirst = $formatExamTime($application->exam_time_first);
                        $venueFirst = $application->exam_venue_first ?: $fallbackVenue;
                        $dateSecond = $application->exam_date_second ? $toNp($application->exam_date_second) : null;
                        $timeSecond = $formatExamTime($application->exam_time_second);
                        $venueSecond = $application->exam_venue_second ?: $fallbackVenue;
                    @endphp
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
                                <td>
                                    {{ implode(' / ', array_filter([$dateFirst, $timeFirst])) ?: '-' }}
                                    @if($venueFirst) ({{ $venueFirst }}) @endif
                                </td>
                            </tr>
                            @if($dateSecond || $timeSecond || $application->exam_venue_second)
                            <tr>
                                <td>२</td>
                                <td>द्वितीय</td>
                                <td>
                                    {{ implode(' / ', array_filter([$dateSecond, $timeSecond])) ?: '-' }}
                                    @if($venueSecond) ({{ $venueSecond }}) @endif
                                </td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>

                <!-- Right: Citizenship and Signature -->
                <div class="details-right">
                    <div class="citizenship-wrapper">
                        @if($citizenshipUrl)
                            <img src="{{ $citizenshipUrl }}" alt="Citizenship" class="citizenship-image">
                        @else
                            <div class="citizenship-placeholder">
                                <p>नागरिकता प्रमाणपत्र</p>
                            </div>
                        @endif
                    </div>

                    <div class="signature-section d-flex justify-content-between">

                <!-- Candidate Signature (from storage) -->
                <div class="signature-container text-center">
                    <div class="signature-box">
                        @if($signatureUrl)
                            <img src="{{ $signatureUrl }}" alt="Candidate Signature" class="signature-image">
                        @endif
                    </div>
                    <p class="signature-label">उम्मेदवार दस्तखत</p>
                </div>

                <!-- Official Signature -->
                <div class="signature-container text-center">
                    <div class="signature-box">
                        @if($officialSignatureUrl)
                            <img src="{{ $officialSignatureUrl }}" alt="Official Signature" class="signature-image">
                        @elseif(file_exists(public_path('images/official-signature.png')))
                            <img src="{{ asset('images/official-signature.png') }}" class="signature-image">
                        @endif
                    </div>
                    <p class="signature-label">आधिकारिक दस्तखत</p>
                </div>

            </div>

                </div>
            </div>

            <!-- Instructions Section -->
            <div class="instructions-section">
                <h4>उम्मेदवारले पालन गर्नुपर्ने नियमहरू :</h4>
                <ol>
                    <li>उम्मेदवारले उत्तरपुस्तिकामा कालो मसी भएको डटपेन/कलम मात्र प्रयोग गर्नुपर्नेछ।</li>
                    <li>प्रवेशपत्र बिना कुनै पनि उम्मेदवारलाई परीक्षामा सहभागी गराइने छैन। त्यसैले प्रवेशपत्र अनिवार्य रूपमा साथमा लिएर परीक्षा सुरु हुनुभन्दा कम्तीमा १ घण्टा अगावै परीक्षा भवनमा उपस्थित हुनुपर्नेर्छ।</li>
                    <li>लिखित परीक्षाको नतिजा प्रकाशित भएपछि अन्तर्वार्ता हुने दिनमा पनि प्रवेशपत्र ल्याउनुपर्नेछ।</li>
                    <li>परीक्षा सुरु हुनुभन्दा ३० मिनेट अगावै घण्टीद्वारा सूचना दिएपछि परीक्षा हलमा प्रवेश गर्न दिइनेछ। वस्तुगत परीक्षा सुरु भएको १५ मिनेटपछि र विषयगत परीक्षा सुरु भएको आधा घण्टापछि आउने तथा दुवै परीक्षा सँगै हुने अवस्थामा २० मिनेटपछि आउने उम्मेदवारले परीक्षामा बस्न पाउने छैनन्।</li>
                    <li>वस्तुगत परिक्षा शुरु भयेको २० मिनेट पछी र विषयगत परिक्षा शुरु भयेको ४० मिनेट पछी मात्र परिक्षाार्थीहरुलाइ परिक्षा हलबाट बाहिर जान दिइने छ।</li>
                    <li>परीक्षा हलमा प्रवेश गरेपछि किताब, कापी, कागज, चिट आदि आफूसँग राख्न पाइने छैन। उम्मेदवारले आपसमा कुराकानी वा संकेत गर्न पाउने छैनन्।</li>
                    <li>परीक्षा हलमा उम्मेदवारले परीक्षा मर्यादा विपरीत कुनै कार्य गरेमा केन्द्राध्यक्षले उनलाई परीक्षा हलबाट निष्कासन गरी तुरुन्त कानुन बमोजिम कारबाही गर्न सक्नेछन्। त्यस्तो निष्कासन गरिएको उम्मेदवारको सो विषयको परीक्षा स्वतः रद्द भएको मानिनेछ।</li>
                    <li>उम्मेदवारले परीक्षा दिएको दिनमा हाजिर अनिवार्य रूपमा गर्नुपर्नेर्छ।</li>
                    <li>परीक्षा भवनमा झोला, मोबाइल फोन तथा अन्य इलेक्ट्रोनिक उपकरणहरू लैजान निषेध गरिएको छ।</li>
                    <li>परीक्षा सञ्चालन हुने दिन अप्रत्याशित रूपमा बिदा परे पनि आयोगको पूर्वसूचना बिना निर्धारित परीक्षा कार्यक्रम स्थगित गरिने छैन।</li>
                    <li>वस्तुगत बहुविकल्पीय (Multiple Choice) प्रश्नहरूको उत्तर लेख्दा उत्तरपुस्तिकामा अनिवार्य रूपमा KEY लेख्नुपर्छ। नलेखेमा उत्तरपुस्तिका स्वतः रद्द हुनेछ। साथै बहुविकल्पीय उत्तर अंग्रेजी ठूलो अक्षर (Capital Letter) मा A, B, C, D मा मात्र लेखिएको हुनुपर्छ।</li>
                    <li>परीक्षामा सम्बन्धित निकायबाट जारी भएको प्रवेशपत्रसँगै आफ्नो नागरिकता वा नेपाल सरकारबाट जारी गरिएको फोटोसहितको कुनै परिचयपत्र अनिवार्य रूपमा ल्याउनुपर्नेर्छ।</li>
                    <li>कुनै उम्मेदवारले प्रश्नपत्रमा रहेको अस्पष्टता सम्बन्धी कुरा सोही पदका अन्य उम्मेदवारलाई बाधा नपर्ने गरी निरीक्षकसँग सोध्नुपर्नेर्छ।</li>
                </ol>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
@import url('https://fonts.googleapis.com/css2?family=Mukta:wght@400;500;600;700&display=swap');

body {
    font-family: 'Mukta', sans-serif;
    font-size: 14px;
    background: #f5f5f5;
}

.admit-card-wrapper {
    width: 100%;
    padding: 10px 20px;
    background: white;
}

.inner-card-wrapper {
    max-width: 800px;
    margin: 0 auto;
}

.admit-card {
    border: 2px solid #000;
    padding: 0;
    background: white;
}

/* Header Section */
.header-section {
    display: flex;
    padding: 15px 20px;
    border-bottom: 1px solid #000;
    align-items: center;
}

.header-left {
    width: 15%;
}

.logo-wrapper {
    width: 80px;
    height: 80px;
}

.header-center .org-title {
    font-size: 28px !important;
    font-weight: 700;
}

.header-center .org-subtitle {
    font-size: 18px !important;
}

.header-center .exam-type {
    font-size: 14px !important;
}

.header-center .card-title {
    font-size: 22px !important;
    font-weight: 700;
}

.header-center {
    width: 65%;
    text-align: center;
    padding: 0 10px;
}

.org-title {
    font-size: 20px;
    font-weight: 700;
    margin: 0 0 5px 0;
    color: #000;
}

.org-subtitle {
    font-size: 14px;
    font-weight: 500;
    margin: 0 0 3px 0;
    color: #000000;
}

.exam-type {
    font-size: 11px;
    margin: 0 0 5px 0;
    color: #000000;
}

.card-title {
    font-size: 18px;
    font-weight: 700;
    margin: 5px 0 0 0;
    color: #000;
}

.header-right {
    width: 20%;
}

.photo-wrapper {
    width: 120px;
    height: 140px;
    border: 1px solid #000;
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

.photo-placeholder {
    color: #999;
    font-size: 12px;
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
    pointer-events: none;
}

/* Content Section */
.content-section {
    display: flex;
    padding: 15px 20px;
    gap: 20px;
}

.details-left {
    width: 60%;
}

.details-right {
    width: 40%;
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
    padding: 6px 8px;
    font-size: 12px;
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
    font-size: 11px;
    line-height: 1.6;
    margin: 0;
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
    padding: 6px 8px;
    font-size: 11px;
    text-align: left;
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
    height: 380px;
    /* border: 1px solid #000;  */
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

.citizenship-placeholder {
    color: #999;
    text-align: center;
}

.citizenship-placeholder p {
    font-size: 12px;
    margin: 0;
}

.signature-section {
    margin-top: 20px;
}

.signature-container {
    width: 45%;
}

.signature-box {
    width: 100%;
    height: 60px;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden; /* prevents overflow */
}

.signature-image {
    max-height: 100px;
    max-width: 90%;
    object-fit: contain;
}


.candidate-signature {
    max-width: 90%;
    max-height: 90%;
    object-fit: contain;
}

.signature-labels {
    display: flex;
    justify-content: space-between;
    margin-top: 5px;
}

.signature-label-left,
.signature-label-right {
    font-size: 10px;
    text-align: center;
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
     margin-top: -20px;
}

.instructions-section h4 {
    font-size: 13px;
    font-weight: 700;
    margin: 0 0 10px 0;
}

.instructions-section ol {
    margin: 0;
    padding-left: 20px;
}

.instructions-section li {
    font-size: 10px;
    line-height: 1.6;
    margin-bottom: 6px;
}

.document-button {
    margin-bottom: 15px;
    padding: 8px 15px;
}

.document-button .btn {
    padding: 6px 12px;
    font-size: 13px;
}

/* Print Styles */
@media print {
    body {
        background: white;
    }

    .document-button,
    .btn, 
    nav, 
    .navbar, 
    .sidebar, 
    footer,
    header {
        display: none !important;
    }

    .admit-card-wrapper {
        padding: 0;
    }

    .inner-card-wrapper {
        max-width: 100%;
    }

    .admit-card {
        border: 2px solid #000;
    }

    @page {
        margin: 10mm;
        size: A4;
    }
}


</style>
@endpush
@endsection
