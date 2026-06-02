<?php

namespace App\Http\Controllers\Candidate;

use App\Http\Controllers\Controller;
use App\Models\ApplicationForm;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class AdmitCardController extends Controller
{
    /**
     * List all admit cards for the candidate
     */
    public function index()
    {
        $candidate = Auth::guard('candidate')->user();

        $applications = ApplicationForm::where('citizenship_number', $candidate->citizenship_number)
            ->whereIn('status', ['assigned', 'approved', 'shortlisted', 'selected'])
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
        $candidate = Auth::guard('candidate')->user();

        $application = ApplicationForm::where('id', $id)
            ->where('citizenship_number', $candidate->citizenship_number)
            ->whereIn('status', ['assigned', 'approved', 'shortlisted', 'selected'])
            ->whereNotNull('roll_number')
            ->firstOrFail();

        $job = $application->job_posting_id
            ? \App\Models\JobPosting::find($application->job_posting_id)
            : null;

        // Resolve inclusive sub-types: from current job or sibling job with same position+level
        $inclusiveTypes = [];
        if ($job) {
            $rawInclusive = $job->inclusive_type;
            $inclusiveTypes = is_array($rawInclusive)
                ? $rawInclusive
                : (is_string($rawInclusive) ? json_decode($rawInclusive, true) : []);

            if (empty($inclusiveTypes)) {
                $siblingJob = \App\Models\JobPosting::where('position', $job->position)
                    ->where('level', $job->level)
                    ->where('has_inclusive', 1)
                    ->whereNotNull('inclusive_type')
                    ->first();
                if ($siblingJob) {
                    $raw = $siblingJob->inclusive_type;
                    $inclusiveTypes = is_array($raw) ? $raw : (is_string($raw) ? json_decode($raw, true) : []);
                }
            }
        }
        $inclusiveTypes = array_filter((array) $inclusiveTypes);

        return view('candidate.admit-card-view', compact('application', 'candidate', 'job', 'inclusiveTypes'));
    }

    /**
     * Download admit card as PDF
     */
    public function download($id)
    {
        $candidate = Auth::guard('candidate')->user();

        $application = ApplicationForm::where('id', $id)
            ->where('citizenship_number', $candidate->citizenship_number)
            ->whereIn('status', ['assigned', 'approved', 'shortlisted', 'selected'])
            ->whereNotNull('roll_number')
            ->firstOrFail();

        $job = $application->job_posting_id
            ? \App\Models\JobPosting::find($application->job_posting_id)
            : null;

        $inclusiveTypes = [];
        if ($job) {
            $rawInclusive = $job->inclusive_type;
            $inclusiveTypes = is_array($rawInclusive)
                ? $rawInclusive
                : (is_string($rawInclusive) ? json_decode($rawInclusive, true) : []);

            if (empty($inclusiveTypes)) {
                $siblingJob = \App\Models\JobPosting::where('position', $job->position)
                    ->where('level', $job->level)
                    ->where('has_inclusive', 1)
                    ->whereNotNull('inclusive_type')
                    ->first();
                if ($siblingJob) {
                    $raw = $siblingJob->inclusive_type;
                    $inclusiveTypes = is_array($raw) ? $raw : (is_string($raw) ? json_decode($raw, true) : []);
                }
            }
        }
        $inclusiveTypes = array_filter((array) $inclusiveTypes);

        // Build local file paths for DomPDF (must use server paths, not URLs)
        $signatureImage = ($application->signature && Storage::disk('public')->exists($application->signature))
            ? storage_path('app/public/' . $application->signature)
            : null;

        $citizenshipImage = ($application->citizenship_id_document && Storage::disk('public')->exists($application->citizenship_id_document))
            ? storage_path('app/public/' . $application->citizenship_id_document)
            : null;

        $officialSignatureImage = ($job && $job->official_signature && Storage::disk('public')->exists($job->official_signature))
            ? storage_path('app/public/' . $job->official_signature)
            : (file_exists(public_path('images/official-signature.png')) ? public_path('images/official-signature.png') : null);

        $pdf = Pdf::loadView('candidate.admit-card-pdf', compact(
            'application', 'candidate', 'job', 'inclusiveTypes',
            'signatureImage', 'citizenshipImage', 'officialSignatureImage'
        ));

        $filename = 'admit-card-' . ($application->roll_number ?? $application->id) . '.pdf';

        return $pdf->download($filename);
    }
}
