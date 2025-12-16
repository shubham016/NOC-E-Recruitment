<?php

namespace App\Http\Controllers\Candidate;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\JobPosting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ApplicationController extends Controller
{
    /**
     * Display all applications
     */
    public function index()
    {
        $candidate = Auth::guard('candidate')->user();

        $applications = Application::where('candidate_id', $candidate->id)
            ->with('jobPosting')
            ->latest()
            ->paginate(15);

        return view('candidate.applications.index', compact('applications'));
    }

    /**
     * Show application form
     */
    public function create($jobId)
    {
        $candidate = Auth::guard('candidate')->user();
        $job = JobPosting::findOrFail($jobId);

        // Check if already applied
        $existingApplication = Application::where('candidate_id', $candidate->id)
            ->where('job_posting_id', $jobId)
            ->first();

        if ($existingApplication) {
            return redirect()
                ->route('candidate.jobs.show', $jobId)
                ->with('error', 'You have already applied for this position.');
        }

        // Check if job is still active
        if ($job->status !== 'active') {
            return redirect()
                ->route('candidate.jobs.index')
                ->with('error', 'This job posting is no longer accepting applications.');
        }

        return view('candidate.applications.create', compact('job'));
    }

    /**
     * Store application
     */
    public function store(Request $request, $jobId)
    {
        $candidate = Auth::guard('candidate')->user();
        $job = JobPosting::findOrFail($jobId);

        // Check if already applied
        $existingApplication = Application::where('candidate_id', $candidate->id)
            ->where('job_posting_id', $jobId)
            ->first();

        if ($existingApplication) {
            return redirect()
                ->route('candidate.jobs.show', $jobId)
                ->with('error', 'You have already applied for this position.');
        }

        $validated = $request->validate([
            // General Information
            'religion' => 'required|string',
            'religion_other' => 'nullable|string|max:255',
            'community' => 'nullable|string|max:255',
            'ethnic_group' => 'nullable|string|max:255',
            'marital_status' => 'required|string',
            'employment_status' => 'nullable|string',
            'physical_disability' => 'required|string',
            'mother_tongue' => 'required|string|max:255',
            'blood_group' => 'nullable|string|max:10',
            'noc_employee' => 'required|string',

            // Personal Information
            'birth_date_ad' => 'required|date|before:today',
            'birth_date_bs' => 'nullable|string|max:20',
            'age' => 'required|integer|min:18|max:65',
            'phone' => 'required|string|max:20',
            'gender' => 'required|string',

            // Citizenship Information
            'citizenship_number' => 'required|string|max:255',
            'citizenship_issue_date_bs' => 'nullable|string|max:20',
            'citizenship_issue_date_ad' => 'required|date|before_or_equal:today',
            'citizenship_issue_district' => 'required|string|max:255',

            // Family Information
            'father_name_english' => 'required|string|max:255',
            'father_name_nepali' => 'required|string|max:255',
            'father_qualification' => 'nullable|string|max:255',
            'mother_name_english' => 'required|string|max:255',
            'mother_name_nepali' => 'required|string|max:255',
            'mother_qualification' => 'nullable|string|max:255',
            'parent_occupation' => 'nullable|string|max:255',
            'grandfather_name_english' => 'required|string|max:255',
            'grandfather_name_nepali' => 'required|string|max:255',
            'nationality' => 'required|string|max:255',
            'spouse_name_english' => 'nullable|string|max:255',
            'spouse_name_nepali' => 'nullable|string|max:255',
            'spouse_nationality' => 'nullable|string|max:255',

            // Permanent Address
            'permanent_province' => 'required|string|max:255',
            'permanent_district' => 'required|string|max:255',
            'permanent_municipality' => 'required|string|max:255',
            'permanent_ward' => 'required|string|max:50',
            'permanent_tole' => 'nullable|string|max:255',
            'permanent_house_number' => 'nullable|string|max:50',

            // Mailing Address
            'same_as_permanent' => 'nullable|boolean',
            'mailing_province' => 'nullable|string|max:255',
            'mailing_district' => 'nullable|string|max:255',
            'mailing_municipality' => 'nullable|string|max:255',
            'mailing_ward' => 'nullable|string|max:50',
            'mailing_tole' => 'nullable|string|max:255',
            'mailing_house_number' => 'nullable|string|max:50',

            // Job Application Specific
            'cover_letter' => 'required|string|min:100',
            'years_of_experience' => 'required|integer|min:0',
            'relevant_experience' => 'nullable|string',
            // 'current_salary' => 'nullable|string|max:255',
            // 'expected_salary' => 'nullable|string|max:255',
            // 'available_from' => 'nullable|date|after_or_equal:today',

            // Documents (Required)
            'passport_photo' => 'required|file|mimes:jpg,jpeg,png|max:1024',
            'resume' => 'required|file|mimes:pdf,doc,docx|max:2048',
            'citizenship_certificate' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'educational_certificates' => 'required|file|mimes:pdf,zip|max:10240',

            // Documents (Optional)
            'cover_letter_file' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
            'experience_certificates' => 'nullable|file|mimes:pdf,zip|max:10240',
            'noc_id_card' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'ethnic_certificate' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'disability_certificate' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'other_documents' => 'nullable|file|mimes:pdf,zip|max:10240',
        ]);

        // Handle file uploads
        $fileFields = [
            'passport_photo',
            'resume',
            'cover_letter_file',
            'citizenship_certificate',
            'educational_certificates',
            'experience_certificates',
            'noc_id_card',
            'ethnic_certificate',
            'disability_certificate',
            'other_documents',
        ];

        foreach ($fileFields as $field) {
            if ($request->hasFile($field)) {
                $validated[$field] = $request->file($field)->store('applications/' . $candidate->id, 'public');
            }
        }

        // Convert same_as_permanent checkbox
        $validated['same_as_permanent'] = $request->has('same_as_permanent') ? 1 : 0;

        // Create application
        $application = Application::create([
            'candidate_id' => $candidate->id,
            'job_posting_id' => $jobId,
            'submitted_at' => now(),
            'status' => 'pending',
            ...$validated
        ]);

        return redirect()
            ->route('candidate.applications.show', $application->id)
            ->with('success', 'Application submitted successfully!');
    }

    /**
     * Show single application
     */
    public function show($id)
    {
        $candidate = Auth::guard('candidate')->user();

        $application = Application::where('id', $id)
            ->where('candidate_id', $candidate->id)
            ->with(['jobPosting', 'reviewer'])
            ->firstOrFail();

        return view('candidate.applications.show', compact('application'));
    }

    /**
     * Show edit form
     */
    public function edit($jobId, $id)
    {
        $candidate = Auth::guard('candidate')->user();

        $application = Application::where('id', $id)
            ->where('candidate_id', $candidate->id)
            ->where('job_posting_id', $jobId)
            ->with('jobPosting')
            ->firstOrFail();

        // Check if can edit
        if (!$application->canEdit()) {
            return redirect()
                ->route('candidate.applications.show', $id)
                ->with('error', 'This application cannot be edited. Only pending applications can be edited.');
        }

        return view('candidate.applications.edit', compact('application'));
    }

    /**
     * Update application
     */
    public function update(Request $request, $jobId, $id)
    {
        $candidate = Auth::guard('candidate')->user();

        $application = Application::where('id', $id)
            ->where('candidate_id', $candidate->id)
            ->where('job_posting_id', $jobId)
            ->firstOrFail();

        // Check if can edit
        if (!$application->canEdit()) {
            return redirect()
                ->route('candidate.applications.show', $id)
                ->with('error', 'This application cannot be edited. Only pending applications can be edited.');
        }

        $validated = $request->validate([
            // General Information
            'religion' => 'required|string',
            'religion_other' => 'nullable|string|max:255',
            'community' => 'nullable|string|max:255',
            'ethnic_group' => 'nullable|string|max:255',
            'marital_status' => 'required|string',
            'employment_status' => 'nullable|string',
            'physical_disability' => 'required|string',
            'mother_tongue' => 'required|string|max:255',
            'blood_group' => 'nullable|string|max:10',
            'noc_employee' => 'required|string',

            // Personal Information
            'birth_date_ad' => 'required|date|before:today',
            'birth_date_bs' => 'nullable|string|max:20',
            'age' => 'required|integer|min:18|max:65',
            'phone' => 'required|string|max:20',
            'gender' => 'required|string',

            // Citizenship Information
            'citizenship_number' => 'required|string|max:255',
            'citizenship_issue_date_bs' => 'nullable|string|max:20',
            'citizenship_issue_date_ad' => 'required|date|before_or_equal:today',
            'citizenship_issue_district' => 'required|string|max:255',

            // Family Information
            'father_name_english' => 'required|string|max:255',
            'father_name_nepali' => 'required|string|max:255',
            'father_qualification' => 'nullable|string|max:255',
            'mother_name_english' => 'required|string|max:255',
            'mother_name_nepali' => 'required|string|max:255',
            'mother_qualification' => 'nullable|string|max:255',
            'parent_occupation' => 'nullable|string|max:255',
            'grandfather_name_english' => 'required|string|max:255',
            'grandfather_name_nepali' => 'required|string|max:255',
            'nationality' => 'required|string|max:255',
            'spouse_name_english' => 'nullable|string|max:255',
            'spouse_name_nepali' => 'nullable|string|max:255',
            'spouse_nationality' => 'nullable|string|max:255',

            // Permanent Address
            'permanent_province' => 'required|string|max:255',
            'permanent_district' => 'required|string|max:255',
            'permanent_municipality' => 'required|string|max:255',
            'permanent_ward' => 'required|string|max:50',
            'permanent_tole' => 'nullable|string|max:255',
            'permanent_house_number' => 'nullable|string|max:50',

            // Mailing Address
            'same_as_permanent' => 'nullable|boolean',
            'mailing_province' => 'nullable|string|max:255',
            'mailing_district' => 'nullable|string|max:255',
            'mailing_municipality' => 'nullable|string|max:255',
            'mailing_ward' => 'nullable|string|max:50',
            'mailing_tole' => 'nullable|string|max:255',
            'mailing_house_number' => 'nullable|string|max:50',

            // Job Application Specific
            'cover_letter' => 'required|string|min:100',
            'years_of_experience' => 'required|integer|min:0',
            'relevant_experience' => 'nullable|string',
            // 'current_salary' => 'nullable|string|max:255',
            // 'expected_salary' => 'nullable|string|max:255',
            // 'available_from' => 'nullable|date|after_or_equal:today',

            // Documents (Optional on update)
            'passport_photo' => 'nullable|file|mimes:jpg,jpeg,png|max:1024',
            'resume' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
            'citizenship_certificate' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'educational_certificates' => 'nullable|file|mimes:pdf,zip|max:10240',
            'cover_letter_file' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
            'experience_certificates' => 'nullable|file|mimes:pdf,zip|max:10240',
            'noc_id_card' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'ethnic_certificate' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'disability_certificate' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'other_documents' => 'nullable|file|mimes:pdf,zip|max:10240',
        ]);

        // Handle file uploads
        $fileFields = [
            'passport_photo',
            'resume',
            'cover_letter_file',
            'citizenship_certificate',
            'educational_certificates',
            'experience_certificates',
            'noc_id_card',
            'ethnic_certificate',
            'disability_certificate',
            'other_documents',
        ];

        foreach ($fileFields as $field) {
            if ($request->hasFile($field)) {
                // Delete old file if exists
                if ($application->$field && Storage::disk('public')->exists($application->$field)) {
                    Storage::disk('public')->delete($application->$field);
                }
                // Upload new file
                $validated[$field] = $request->file($field)->store('applications/' . $candidate->id, 'public');
            }
        }

        // Convert same_as_permanent checkbox
        $validated['same_as_permanent'] = $request->has('same_as_permanent') ? 1 : 0;

        // Update application
        $application->update($validated);

        return redirect()
            ->route('candidate.applications.show', $id)
            ->with('success', 'Application updated successfully!');
    }

    /**
     * Withdraw application
     */
    public function destroy($id)
    {
        $candidate = Auth::guard('candidate')->user();

        $application = Application::where('id', $id)
            ->where('candidate_id', $candidate->id)
            ->firstOrFail();

        // Check if can withdraw
        if (!$application->canWithdraw()) {
            return redirect()
                ->route('candidate.applications.show', $id)
                ->with('error', 'This application cannot be withdrawn. Only pending or under review applications can be withdrawn.');
        }

        $application->update(['status' => 'withdrawn']);

        return redirect()
            ->route('candidate.applications.index')
            ->with('success', 'Application withdrawn successfully.');
    }
}