<?php

namespace App\Http\Controllers\Candidate;

use App\Http\Controllers\Controller;
use App\Models\ApplicationForm;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class AdmitCardController extends Controller
{
    /**
     * List all admit cards for the candidate
     */
    public function index()
    {
        if (!Session::has('candidate_logged_in')) {
            return redirect()->route('candidate.login');
        }

        $candidate = DB::table('candidate_registration')
            ->where('id', Session::get('candidate_id'))
            ->first();

        $applications = ApplicationForm::where('citizenship_number', $candidate->citizenship_number)
            ->whereIn('status', ['approved', 'shortlisted', 'selected'])
            ->whereNotNull('roll_number')
            ->latest()
            ->get();

        return view('candidate.admit-card', compact('applications', 'candidate'));
    }

    /**
     * View a specific admit card
     */
    public function show($id)
    {
        if (!Session::has('candidate_logged_in')) {
            return redirect()->route('candidate.login');
        }

        $candidate = DB::table('candidate_registration')
            ->where('id', Session::get('candidate_id'))
            ->first();

        $application = ApplicationForm::where('id', $id)
            ->where('citizenship_number', $candidate->citizenship_number)
            ->whereIn('status', ['approved', 'shortlisted', 'selected'])
            ->whereNotNull('roll_number')
            ->firstOrFail();

        return view('candidate.admit-card-view', compact('application', 'candidate'));
    }

    /**
     * Download admit card as PDF
     */
    public function download($id)
    {
        if (!Session::has('candidate_logged_in')) {
            return redirect()->route('candidate.login');
        }

        $candidate = DB::table('candidate_registration')
            ->where('id', Session::get('candidate_id'))
            ->first();

        $application = ApplicationForm::where('id', $id)
            ->where('citizenship_number', $candidate->citizenship_number)
            ->whereIn('status', ['approved', 'shortlisted', 'selected'])
            ->whereNotNull('roll_number')
            ->firstOrFail();

        $pdf = Pdf::loadView('candidate.admit-card-pdf', compact('application', 'candidate'));

        $filename = 'admit-card-' . ($application->roll_number ?? $application->id) . '.pdf';

        return $pdf->download($filename);
    }
}
