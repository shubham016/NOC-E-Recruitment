@extends('layouts.app')

@section('title', 'View Admit Card')

@section('content')
@section('sidebar-menu')
    <a href="{{ route('candidate.dashboard') }}" class="sidebar-menu-item">
        <i class="bi bi-speedometer2"></i>
        <span>Dashboard</span>
    </a>
    <a href="#" class="sidebar-menu-item">
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
    <a href="{{ route('candidate.my-profile') }}" class="sidebar-menu-item">
        <i class="bi bi-person"></i>
        <span>My Profile</span>
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
    <div class="row mb-3">
        <div class="col-12">
            <a href="{{ route('candidate.admit-card') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Back to Admit Cards
            </a>
            <a href="{{ route('candidate.admit-card.download', $application->id) }}" class="btn btn-primary float-end">
                <i class="bi bi-download"></i> Download PDF
            </a>
            <button onclick="window.print()" class="btn btn-info float-end me-2">
                <i class="bi bi-printer"></i> Print
            </button>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card shadow-lg" id="admit-card-print" style="max-width: 210mm; margin: 0 auto;">
                <div class="card-body p-0">
                    <!-- Header -->
                    <div class="p-3 text-center border-bottom" style="position: relative;">
                        <div style="position: absolute; left: 15px; top: 15px; font-size: 11px;">
                            प्रवेश पत्र नं. {{ isset($application->id) ? $application->id : $application->id }}
                        </div>
                        
                        <div class="mb-2">
                            @if(file_exists(public_path('storage/Emblem_of_Nepal.svg')))
                                <img src="{{ asset('storage/Emblem_of_Nepal.svg') }}" 
                                     style="width: 60px; height: 60px; margin: 0 auto; display: block;" 
                                     alt="Nepal Emblem">
                            @else
                                <div style="width: 60px; height: 60px; margin: 0 auto; border: 1px solid #ccc; border-radius: 50%; background: #f5f5f5;"></div>
                            @endif
                        </div>
                        <h4 class="mb-1">{{ $application->organization_name ?? 'लोक सेवा आयोग' }}</h4>
                        <p class="mb-0 text-secondary">{{ $application->post_title ?? 'नेपाल' }}</p>
                    </div>
                    
                    <!-- Main Content -->
                    <div class="p-3">
                        <div class="row">
                            <!-- Left: Citizenship -->
                            <div class="col-md-4">
                                <div class="border border-danger p-2 text-center" style="height: 200px; background: #f9f9f9; display: flex; align-items: center; justify-content: center;">
                                    @if(isset($application->citizenship_id_document) && $application->citizenship_id_document)
                                        <img src="{{ asset('storage/' . $application->citizenship_id_document) }}" 
                                             alt="Citizenship" 
                                             style="max-width: 100%; max-height: 100%; object-fit: contain;">
                                    @else
                                        <div>
                                            <div style="font-size: 12px;">नागरिकता</div>
                                            <div style="font-size: 11px;">Citizenship Card</div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            
                            <!-- Center: Details -->
                            <div class="col-md-5">
                                <table class="table table-bordered table-sm mb-3" style="font-size: 11px;">
                                    <tr>
                                        <td class="bg-light" style="width: 50%;"><strong>क) नाम थर (अंग्रेजीमा)</strong></td>
                                        <td>{{ $application->name_english ?? $candidate->name }}</td>
                                    </tr>
                                    <tr>
                                        <td class="bg-light"><strong>ख) नाम थर (देवनागरीमा)</strong></td>
                                        <td>{{ $application->name_nepali ?? '' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="bg-light"><strong>ग) लिङ्ग</strong></td>
                                        <td>{{ $application->gender ?? $candidate->gender }}</td>
                                    </tr>
                                    <tr>
                                        <td class="bg-light"><strong>घ) जन्म मिति (बि.सं.)</strong></td>
                                        <td>{{ $application->birth_date_bs ?? $candidate->date_of_birth_bs ?? '' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="bg-light"><strong>ङ) बाबुको नाम (देवनागरीमा)</strong></td>
                                        <td>{{ $application->father_name_nepali ?? '' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="bg-light"><strong>च) स्थायी ठेगाना</strong></td>
                                        <td>
                                            {{ $application->permanent_district ?? '' }}
                                            @if(isset($application->permanent_municipality))
                                                , {{ $application->permanent_municipality }}
                                            @endif
                                        </td>
                                    </tr>
                                </table>
                                
                                <div class="bg-light p-2 text-center mb-2" style="font-size: 12px;">
                                    <strong>सम्पर्क विवरण / Contact Details</strong>
                                </div>
                                <table class="table table-bordered table-sm" style="font-size: 11px;">
                                    <tr>
                                        <td class="bg-light" style="width: 50%;"><strong>मोबाइल नम्बर</strong></td>
                                        <td>{{ $application->phone ?? '' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="bg-light"><strong>नागरिकता नं</strong></td>
                                        <td>{{ $application->citizenship_number }}</td>
                                    </tr>
                                    <tr>
                                        <td class="bg-light"><strong>जारी मिति</strong></td>
                                        <td>{{ $application->citizenship_issue_date_bs ?? '' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="bg-light"><strong>जारी जिल्ला</strong></td>
                                        <td>{{ $application->citizenship_issue_district ?? '' }}</td>
                                    </tr>
                                </table>
                            </div>
                            
                            <!-- Right: Photo -->
                            <div class="col-md-3">
                                <div class="border border-danger text-center" style="height: 200px; background: #f9f9f9; display: flex; align-items: center; justify-content: center;">
                                    @if(isset($application->passport_size_photo) && $application->passport_size_photo)
                                        <img src="{{ asset('storage/' . $application->passport_size_photo) }}" 
                                             alt="Photo" 
                                             style="max-width: 100%; max-height: 100%; object-fit: cover;">
                                    @else
                                        <div>
                                            <i class="bi bi-person" style="font-size: 2rem;"></i>
                                            <p class="mb-0 mt-1" style="font-size: 11px;">Photo</p>
                                        </div>
                                    @endif
                                </div>
                                <div class="border mt-2" style="height: 50px; background: white;"></div>
                                <div class="text-center mt-1" style="font-size: 10px;">
                                    उम्मेदवारको दस्तखत<br>
                                    <small>Candidate's Signature</small>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Exam Details -->
                        <div class="mt-3">
                            <div class="bg-light p-2 text-center mb-2" style="font-size: 12px;">
                                <strong>परीक्षा सम्बन्धी विवरण / Examination Details</strong>
                            </div>
                            <table class="table table-bordered table-sm" style="font-size: 11px;">
                                <tr>
                                    <td class="bg-light" style="width: 35%;"><strong>क) परीक्षाको प्रकार / Type</strong></td>
                                    <td>{{ $application->post_title ?? 'लिखित परीक्षा' }}</td>
                                </tr>
                                <tr>
                                    <td class="bg-light"><strong>ख) परीक्षा हुने मिति / Date</strong></td>
                                    <td>{{ date('Y-m-d', strtotime($application->exam_date)) }} ({{ date('l', strtotime($application->exam_date)) }})</td>
                                </tr>
                                <tr>
                                    <td class="bg-light"><strong>ग) परीक्षा हुने समय / Time</strong></td>
                                    <td>{{ $application->exam_time }}</td>
                                </tr>
                                @if(isset($application->reporting_time) && $application->reporting_time)
                                <tr>
                                    <td class="bg-light"><strong>घ) रिपोर्टिङ समय / Reporting</strong></td>
                                    <td class="text-danger fw-bold">{{ $application->reporting_time }}</td>
                                </tr>
                                @endif
                                <tr>
                                    <td class="bg-light"><strong>ङ) परीक्षा केन्द्र / Center</strong></td>
                                    <td>{{ $application->exam_venue }}</td>
                                </tr>
                            </table>
                        </div>
                        
                        <!-- Instructions -->
                        <div class="border p-3 mt-3" style="background: #fffef5; font-size: 11px;">
                            <div class="text-center mb-2">
                                <strong>सम्बन्धित व्यक्तिलाई विशेष सूचनाहरु / Instructions</strong>
                            </div>
                            @if(isset($application->exam_instructions) && $application->exam_instructions)
                                <div style="white-space: pre-line;">{{ $application->exam_instructions }}</div>
                            @else
                            <ul style="line-height: 1.6;">
                                <li>परीक्षा भवन भित्र मोबाइल लैजान तथा प्रयोग गर्न पाईने छैन।</li>
                                <li>परीक्षा हुने समय भन्दा कम्तिमा ३० मिनेट अगावै परीक्षा केन्द्रमा उपस्थित हुनुपर्नेछ।</li>
                                <li>नीलो वा कालो सिसा कलम तथा अन्य आवश्यक सामानहरु आफै ल्याउनु पर्नेछ।</li>
                                <li>परीक्षा शुरु भएपछि प्रवेश गर्न पाइने छैन।</li>
                                <li>यो प्रवेश पत्र तथा फोटो सहितको नागरिकता प्रमाणपत्र अनिवार्य साथमा ल्याउनु पर्नेछ।</li>
                                <li>परीक्षा केन्द्रबाट विशेष समय र बस्ने स्थान बारे जानकारी लिनुहोला।</li>
                            </ul>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
@media print {
    .btn, nav, .navbar, .sidebar, footer {
        display: none !important;
    }
    
    #admit-card-print {
        box-shadow: none !important;
        margin: 0 !important;
        max-width: 100% !important;
    }
    
    body {
        background: white !important;
    }
    
    .container-fluid {
        padding: 0 !important;
    }
}
</style>
@endpush
@endsection