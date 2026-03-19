<?php

namespace App\Http\Controllers\Candidate;

use App\Http\Controllers\Controller;
use App\Models\ApplicationForm;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;

class AdmitCardController extends Controller
{
    /**
     * List all admit cards for the candidate
     */
    public function index()
    {
        $candidate = Auth::guard('candidate')->user();

        $applications = ApplicationForm::where('candidate_id', $candidate->id)
            ->whereIn('status', ['approved', 'shortlisted', 'selected'])
            ->whereNotNull('roll_number')
            ->with('vacancy')
            ->latest()
            ->get();

        return view('candidate.admit-card', compact('applications', 'candidate'));
    }

    /**
     * View a specific admit card
     */
    public function show($id)
    {
        $candidate = Auth::guard('candidate')->user();

        $application = ApplicationForm::where('id', $id)
            ->where('candidate_id', $candidate->id)
            ->whereIn('status', ['approved', 'shortlisted', 'selected'])
            ->whereNotNull('roll_number')
            ->with('vacancy')
            ->firstOrFail();

        return view('candidate.admit-card-view', compact('application', 'candidate'));
    }

    /**
     * Download admit card as PDF
     */
    public function download($id)
    {
        $candidate = Auth::guard('candidate')->user();

        $application = ApplicationForm::where('id', $id)
            ->where('candidate_id', $candidate->id)
            ->whereIn('status', ['approved', 'shortlisted', 'selected'])
            ->whereNotNull('roll_number')
            ->with('vacancy')
            ->firstOrFail();

        $pdf = Pdf::loadView('candidate.admit-card-pdf', compact('application', 'candidate'));

        $filename = 'admit-card-' . ($application->roll_number ?? $application->id) . '.pdf';

        return $pdf->download($filename);
    }
}
