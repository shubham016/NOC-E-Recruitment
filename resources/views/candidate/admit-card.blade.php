@extends('layouts.app')

@section('title', 'Download Admit Card')

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
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-header mb-4 pb-3 border-bottom">
                <h2><i class="bi bi-file-earmark-text"></i> Download Admit Card</h2>
                <p class="text-muted">Download your admit card for scheduled examinations</p>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle-fill"></i>
            @foreach($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @php
        $npNums = ['0'=>'०','1'=>'१','2'=>'२','3'=>'३','4'=>'४','5'=>'५','6'=>'६','7'=>'७','8'=>'८','9'=>'९'];
        $toNp = function($str) use ($npNums) { return strtr((string)$str, $npNums); };
        $enNpPhrases = [
            'General Manager'        => 'महाप्रबन्धक',
            'Deputy General Manager' => 'उप महाप्रबन्धक',
            'Chief Manager'          => 'मुख्य प्रबन्धक',
            'Assistant Manager'      => 'सहायक प्रबन्धक',
            'Deputy Manager'         => 'उप प्रबन्धक',
            'Senior Manager'         => 'वरिष्ठ प्रबन्धक',
            'Senior Assistant'       => 'वरिष्ठ सहायक',
            'Junior Assistant'       => 'कनिष्ठ सहायक',
            'Senior Officer'         => 'वरिष्ठ अधिकृत',
            'Junior Officer'         => 'कनिष्ठ अधिकृत',
            'Executive Director'     => 'कार्यकारी निर्देशक',
            'Human Resources'        => 'मानव संसाधन',
            'Information Technology' => 'सूचना प्रविधि',
            'Manager'                => 'प्रबन्धक',
            'Assistant'              => 'सहायक',
            'Officer'                => 'अधिकृत',
            'Engineer'               => 'इन्जिनियर',
            'Accountant'             => 'लेखापाल',
            'Supervisor'             => 'पर्यवेक्षक',
            'Director'               => 'निर्देशक',
            'Level'                  => 'तह',
            'Technical'              => 'प्राविधिक',
            'Finance'                => 'वित्त',
            'Administration'         => 'प्रशासन',
            'Marketing'              => 'बजार',
            'Operations'             => 'सञ्चालन',
            'Planning'               => 'योजना',
            'Procurement'            => 'खरिद',
            'Accounts'               => 'लेखा',
            'Legal'                  => 'कानुनी',
            'IT'                     => 'सूचना प्रविधि',
            'Others'                 => 'अन्य',
            'Other'                  => 'अन्य',
            'General'                => 'सामान्य',
            'Naxal'                  => 'नक्साल',
            'Kathmandu'              => 'काठमाडौं',
            'Lalitpur'               => 'ललितपुर',
            'Bhaktapur'              => 'भक्तपुर',
            'Pokhara'                => 'पोखरा',
            'Butwal'                 => 'बुटवल',
            'Biratnagar'             => 'विराटनगर',
            'Janakpur'               => 'जनकपुर',
            'Hetauda'                => 'हेटौंडा',
            'Dharan'                 => 'धरान',
            'Birgunj'                => 'वीरगञ्ज',
            'Nepalgunj'              => 'नेपालगञ्ज',
            'Dhangadhi'              => 'धनगढी',
            'Banepa'                 => 'बनेपा',
            'Dhulikhel'              => 'धुलिखेल',
            'Kirtipur'               => 'कीर्तिपुर',
            'Maharajgunj'            => 'महाराजगञ्ज',
            'Baluwatar'              => 'बालुवाटार',
            'Babarmahal'             => 'बबरमहल',
            'Tripureshwor'           => 'त्रिपुरेश्वर',
            'Putalisadak'            => 'पुतलीसडक',
            'Anamnagar'              => 'अनामनगर',
            'Tinkune'                => 'तीनकुने',
            'Koteshwor'              => 'कोटेश्वर',
            'Chabahil'               => 'चाबहिल',
            'Baneshwor'              => 'बानेश्वर',
            'Lazimpat'               => 'लाजिम्पाट',
            'Kupondole'              => 'कुपण्डोल',
            'Jawalakhel'             => 'जावलाखेल',
            'Pulchowk'               => 'पुल्चोक',
            'Sanepa'                 => 'सानेपा',
        ];
        $toNpText = function($str) use ($enNpPhrases, $toNp) {
            if (!$str) return '';
            foreach ($enNpPhrases as $en => $np) {
                $str = preg_replace('/\b' . preg_quote($en, '/') . '\b/i', $np, $str);
            }
            return $toNp($str);
        };
    @endphp

    <div class="row">
        @if($applications->isEmpty())
            <div class="col-12">
                <div class="card text-center py-5 shadow-sm">
                    <div class="card-body">
                        <i class="bi bi-file-earmark-text text-muted" style="font-size: 5rem; opacity: 0.3;"></i>
                        <h3 class="mt-4">No Admit Cards Available</h3>
                        <p class="text-muted mb-4">
                            Admit cards will be available once your application is shortlisted for examination.<br>
                            Please check back later or contact the administration for more information.
                        </p>
                        <a href="{{ route('candidate.dashboard') }}" class="btn btn-danger">
                            <i class="bi bi-house-door"></i> Back to Dashboard
                        </a>
                    </div>
                </div>
            </div>
        @else
            @foreach($applications as $application)
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-header bg-light text-dark">
                        <h5 class="mb-1">
                            <i class="bi bi-briefcase"></i> 
                            {{ $application->post_title ?? 'Position Applied' }}
                        </h5>
                        <small class="d-block">
                            Application ID: {{ $application->id }}
                        </small>
                        <small class="d-block"> 
                            Roll Number: {{ $application->roll_number }}
                        </small>
                    </div>
                    
                    <div class="card-body">
                        <div class="mb-3 pb-2 border-bottom">
                            <small class="text-muted d-block">Candidate Name</small>
                            <strong>{{ $application->name_english ?? $candidate->name }}</strong>
                        </div>
                        
                        <div class="mb-3 pb-2 border-bottom">
                            <small class="text-muted d-block">प्रथम पत्र मिति / Time</small>
                            <strong>{{ $application->exam_date_first ?? '-' }}</strong>
                            @if($application->exam_time_first)
                                <small class="d-block text-dark">{{ $application->exam_time_first }}</small>
                            @endif
                            @if($application->exam_venue_first)
                                <small class="d-block text-muted mt-1">{{ Str::limit($toNpText($application->exam_venue_first), 60) }}</small>
                            @elseif($application->exam_venue)
                                <small class="d-block text-muted mt-1">{{ Str::limit($toNpText($application->exam_venue), 60) }}</small>
                            @endif
                        </div>

                        @if($application->exam_date_second)
                        <div class="mb-3 pb-2 border-bottom">
                            <small class="text-muted d-block">द्वितीय पत्र मिति / Time</small>
                            <strong>{{ $application->exam_date_second }}</strong>
                            @if($application->exam_time_second)
                                <small class="d-block text-dark">{{ $application->exam_time_second }}</small>
                            @endif
                            @if($application->exam_venue_second)
                                <small class="d-block text-muted mt-1">{{ Str::limit($toNpText($application->exam_venue_second), 60) }}</small>
                            @elseif($application->exam_venue)
                                <small class="d-block text-muted mt-1">{{ Str::limit($toNpText($application->exam_venue), 60) }}</small>
                            @endif
                        </div>
                        @endif
                        
                        <div class="mb-3">
                            <small class="text-muted d-block">Status</small>
                            <span class="badge bg-success">
                                <i class="bi bi-patch-check"></i> {{ ucfirst($application->status) }}
                            </span>
                        </div>
                    </div>
                    
                    <div class="card-footer bg-light">
                        <div class="d-grid gap-2">
                            <a href="{{ route('candidate.admit-card.view', $application->id) }}" 
                               class="btn btn-outline-danger btn-sm">
                                <i class="bi bi-eye"></i> View Admit Card
                            </a>
                        <!-- <a href="{{ route('candidate.admit-card.download', $application->id) }}" 
                               class="btn btn-danger btn-sm">
                                <i class="bi bi-download"></i> Download PDF
                            </a> -->
                        </div>
                        <small class="text-muted d-block text-center mt-2">
                            <i class="bi bi-info-circle"></i> Bring this card on exam day
                        </small>
                    </div>
                </div>
            </div>
            @endforeach
        @endif
    </div>
</div>
@endsection