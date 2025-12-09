<?php

namespace App\Http\Controllers\Candidate;

use App\Http\Controllers\Controller;
use App\Models\JobPosting;
use App\Models\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ApplicationController extends Controller
{
    /**
     * Show application form
     */
    public function create($id)
    {
        $job = JobPosting::where('status', 'active')
            ->where('deadline', '>=', now())
            ->findOrFail($id);

        // Check if already applied
        $hasApplied = Application::where('candidate_id', auth()->guard('candidate')->user()->candidate->id)
            ->where('job_posting_id', $id)
            ->exists();

        if ($hasApplied) {
            return redirect()
                ->route('candidate.jobs.show', $id)
                ->with('error', 'You have already applied for this position.');
        }

        $candidate = auth()->guard('candidate')->user()->candidate;

        return view('candidate.applications.create', compact('job', 'candidate'));
    }

    /**
     * Submit application
     */
    public function store(Request $request, $id)
    {
        $job = JobPosting::where('status', 'active')
            ->where('deadline', '>=', now())
            ->findOrFail($id);

        // Get authenticated candidate
        $candidate = auth()->guard('candidate')->user();

        // Check if already applied
        $hasApplied = Application::where('candidate_id', $candidate->id)
            ->where('job_posting_id', $id)
            ->exists();

        if ($hasApplied) {
            return redirect()
                ->route('candidate.jobs.show', $id)
                ->with('error', 'You have already applied for this position.');
        }

        // Validate
        $request->validate([
            'cover_letter' => 'required|string|min:100|max:5000',
            'resume' => 'required|file|mimes:pdf|max:5120',
            'additional_documents.*' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:5120',
        ]);

        // Upload resume
        $resumePath = $request->file('resume')->store('resumes', 'public');

        // Upload additional documents
        $additionalDocs = [];
        if ($request->hasFile('additional_documents')) {
            foreach ($request->file('additional_documents') as $file) {
                $path = $file->store('documents', 'public');
                $additionalDocs[] = [
                    'original_name' => $file->getClientOriginalName(),
                    'path' => $path,
                    'size' => $file->getSize(),
                ];
            }
        }

        // Create application
        Application::create([
            'job_posting_id' => $id,
            'candidate_id' => $candidate->id,
            'status' => 'pending',
            'cover_letter' => $request->cover_letter,
            'resume_path' => $resumePath,
            'additional_documents' => $additionalDocs,
        ]);

        return redirect()
            ->route('candidate.applications.index')
            ->with('success', 'Application submitted successfully!');
    }
    /**
     * View my applications
     */
    public function index()
    {
        $candidate = auth()->guard('candidate')->user();

        $applications = Application::where('candidate_id', $candidate->id)
            ->with('jobPosting', 'reviewer')
            ->latest()
            ->paginate(10);

        $stats = [
            'total' => Application::where('candidate_id', $candidate->id)->count(),
            'pending' => Application::where('candidate_id', $candidate->id)->where('status', 'pending')->count(),
            'under_review' => Application::where('candidate_id', $candidate->id)->where('status', 'under_review')->count(),
            'shortlisted' => Application::where('candidate_id', $candidate->id)->where('status', 'shortlisted')->count(),
            'rejected' => Application::where('candidate_id', $candidate->id)->where('status', 'rejected')->count(),
        ];

        return view('candidate.applications.index', compact('applications', 'stats'));
    }

    /**
     * View single application
     */
    public function show($id)
    {
        $application = Application::where('candidate_id', auth()->guard('candidate')->user()->candidate->id)
            ->with('jobPosting', 'reviewer')
            ->findOrFail($id);

        return view('candidate.applications.show', compact('application'));
    }
}