<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;

class AdmitCardController extends Controller
{
    // Helper method to convert image to base64
    private function getImageBase64($path)
    {
        if (empty($path)) {
            return null;
        }
        
        // Try storage/app/public path first
        $fullPath = storage_path('app/public/' . $path);
        
        // If not found, try public path
        if (!file_exists($fullPath)) {
            $fullPath = public_path('storage/' . $path);
        }
        
        // If still not found, try direct public path
        if (!file_exists($fullPath)) {
            $fullPath = public_path($path);
        }
        
        if (!file_exists($fullPath)) {
            Log::warning('Image file not found: ' . $path);
            return null;
        }
        
        try {
            $imageData = file_get_contents($fullPath);
            $base64 = base64_encode($imageData);
            $mimeType = mime_content_type($fullPath);
            
            return "data:{$mimeType};base64,{$base64}";
        } catch (\Exception $e) {
            Log::error('Error converting image to base64: ' . $e->getMessage());
            return null;
        }
    }

    // Show Admit Card Page
    public function index()
    {
        // Check if candidate is logged in
        if (!Session::has('candidate_logged_in')) {
            return redirect()->route('candidate.login')
                ->withErrors(['error' => 'Please login first']);
        }
        
        // Get candidate information
        $candidate = DB::table('candidate_registration')
            ->where('id', Session::get('candidate_id'))
            ->first();
        
        // Get all applications with admit card available (shortlisted status)
        $applications = DB::table('application_form')
            ->where('citizenship_number', $candidate->citizenship_number)
            ->where('status', 'shortlisted')
            ->whereNotNull('exam_date') // Only show if exam details are set
            ->get();
        
        return view('candidate.admit-card', compact('applications', 'candidate'));
    }
    
    // Download Admit Card as PDF
    public function download($applicationId)
    {
        // Check if candidate is logged in
        if (!Session::has('candidate_logged_in')) {
            return redirect()->route('candidate.login')
                ->withErrors(['error' => 'Please login first']);
        }
        
        // Get candidate information
        $candidate = DB::table('candidate_registration')
            ->where('id', Session::get('candidate_id'))
            ->first();
        
        // Get application details
        $application = DB::table('application_form')
            ->where('id', $applicationId)
            ->where('citizenship_number', $candidate->citizenship_number)
            ->where('status', 'shortlisted')
            ->first();
        
        if (!$application) {
            return redirect()->back()
                ->withErrors(['error' => 'Admit card not found or not available']);
        }
        
        // Convert images to base64
        $citizenshipImage = $this->getImageBase64($application->citizenship_id_document ?? '');
        $photoImage = $this->getImageBase64($application->passport_size_photo ?? '');
        
        // Handle emblem - try multiple paths
        $emblemPath = $application->emblem_path ?? 'Emblem_of_Nepal.svg';
        $emblemImage = $this->getImageBase64($emblemPath);
        
        // If emblem not found in uploaded files, try public assets
        if (!$emblemImage) {
            $emblemImage = $this->getImageBase64('images/Emblem_of_Nepal.svg');
        }
        
        // Generate PDF
        $pdf = PDF::loadView('candidate.admit-card-pdf', [
            'application' => $application,
            'candidate' => $candidate,
            'citizenshipImage' => $citizenshipImage,
            'photoImage' => $photoImage,
            'emblemImage' => $emblemImage,
        ]);
        
        // Set paper and options
        $pdf->setPaper('A4', 'portrait');
        $pdf->setOptions([
            'isHtml5ParserEnabled' => true,
            'isRemoteEnabled' => true,
            'isFontSubsettingEnabled' => true,
            'defaultFont' => 'Noto Sans Devanagari', 
            'dpi' => 96,
            'enable_font_kerning' => true,
        ]);
        
        // Download PDF
        $fileName = 'admit-card-' . ($application->roll_number ?? $applicationId) . '.pdf';
        
        return $pdf->download($fileName);
    }
    
    // View Admit Card (before download) 
    public function view($applicationId)
    {
        // Check if candidate is logged in
        if (!Session::has('candidate_logged_in')) {
            return redirect()->route('candidate.login')
                ->withErrors(['error' => 'Please login first']);
        }
        
        // Get candidate information
        $candidate = DB::table('candidate_registration')
            ->where('id', Session::get('candidate_id'))
            ->first();
        
        // Get application details
        $application = DB::table('application_form')
            ->where('id', $applicationId)
            ->where('citizenship_number', $candidate->citizenship_number)
            ->where('status', 'shortlisted')
            ->first();
        
        if (!$application) {
            return redirect()->back()
                ->withErrors(['error' => 'Admit card not found or not available']);
        }
        
        // Use your original view file (admit-card-view.blade.php)
        return view('candidate.admit-card-view', [
            'application' => $application,
            'candidate' => $candidate
        ]);
    }
    
    // Check if admit card is available for application
    public function checkAvailability($applicationId)
    {
        // Check if candidate is logged in
        if (!Session::has('candidate_logged_in')) {
            return response()->json(['available' => false, 'message' => 'Not logged in'], 401);
        }
        
        // Get candidate information
        $candidate = DB::table('candidate_registration')
            ->where('id', Session::get('candidate_id'))
            ->first();
        
        // Check if application exists and is shortlisted
        $application = DB::table('application_form')
            ->where('id', $applicationId)
            ->where('citizenship_number', $candidate->citizenship_number)
            ->where('status', 'shortlisted')
            ->whereNotNull('exam_date')
            ->exists();
        
        return response()->json([
            'available' => $application,
            'message' => $application ? 'Admit card available' : 'Admit card not available'
        ]);
    }
}