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

        $photoPath = $this->resolvePublicMediaPath(
            $application->passport_size_photo,
            $candidate->passport_size_photo
        );
        $signaturePath = $this->resolvePublicMediaPath(
            $application->signature,
            $candidate->signature
        );
        $citizenshipPath = $this->resolvePublicMediaPath(
            $application->citizenship_id_document,
            $candidate->citizenship_id_document
        );
        $officialSignaturePath = $this->resolvePublicMediaPath($job?->official_signature);

        $photoUrl = $this->publicMediaUrl($photoPath);
        $signatureUrl = $this->publicMediaUrl($signaturePath);
        $citizenshipUrl = $this->publicMediaUrl($citizenshipPath);
        $officialSignatureUrl = $this->publicMediaUrl($officialSignaturePath);

        return view('candidate.admit-card-view', compact(
            'application', 'candidate', 'job', 'inclusiveTypes',
            'photoUrl', 'signatureUrl', 'citizenshipUrl', 'officialSignatureUrl'
        ));
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

        // DomPDF needs local file paths rather than browser URLs.
        $photoImage = $this->publicMediaFile($this->resolvePublicMediaPath(
            $application->passport_size_photo,
            $candidate->passport_size_photo
        ));
        $signatureImage = $this->publicMediaFile($this->resolvePublicMediaPath(
            $application->signature,
            $candidate->signature
        ));
        $citizenshipImage = $this->publicMediaFile($this->resolvePublicMediaPath(
            $application->citizenship_id_document,
            $candidate->citizenship_id_document
        ));
        $officialSignatureImage = $this->publicMediaFile(
            $this->resolvePublicMediaPath($job?->official_signature)
        ) ?: (file_exists(public_path('images/official-signature.png'))
            ? public_path('images/official-signature.png')
            : null);
        $nocLogoImage = file_exists(public_path('img/noc-logo.png'))
            ? public_path('img/noc-logo.png')
            : null;

        $pdf = Pdf::loadView('candidate.admit-card-pdf', compact(
            'application', 'candidate', 'job', 'inclusiveTypes',
            'photoImage', 'signatureImage', 'citizenshipImage',
            'officialSignatureImage', 'nocLogoImage'
        ));

        $filename = 'admit-card-' . ($application->roll_number ?? $application->id) . '.pdf';

        return $pdf->download($filename);
    }

    private function resolvePublicMediaPath(?string ...$paths): ?string
    {
        foreach ($paths as $path) {
            if (!$path) {
                continue;
            }

            $normalized = ltrim(str_replace('\\', '/', $path), '/');
            $normalized = preg_replace('#^(?:storage|public)/#', '', $normalized);

            if (Storage::disk('public')->exists($normalized)) {
                return $normalized;
            }
        }

        return null;
    }

    private function publicMediaUrl(?string $path): ?string
    {
        return $path ? Storage::disk('public')->url($path) : null;
    }

    private function publicMediaFile(?string $path): ?string
    {
        return $path ? Storage::disk('public')->path($path) : null;
    }
}
