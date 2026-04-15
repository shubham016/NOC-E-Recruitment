<?php

namespace App\Http\Controllers\Candidate;

use App\Http\Controllers\Controller;
use App\Models\ApplicationForm;
use App\Models\Vacancy;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ApplicationFormController extends Controller
{
    /**
     * Display all applications for the candidate
     */
    public function index()
    {
        $candidate = Auth::guard('candidate')->user();

        $forms = ApplicationForm::where('candidate_id', $candidate->id)
            ->with('vacancy')
            ->latest()
            ->paginate(15);

        return view('candidate.applications.index', compact('forms'));
    }

    /**
     * Show application form for a specific vacancy
     */
    public function create($vacancyId)
    {
        $candidate = Auth::guard('candidate')->user();
        $vacancy = Vacancy::findOrFail($vacancyId);

        // Check if already applied (non-draft)
        $existingApplication = ApplicationForm::where('candidate_id', $candidate->id)
            ->where('vacancy_id', $vacancyId)
            ->first();

        if ($existingApplication && !$existingApplication->isDraft()) {
            return redirect()
                ->route('candidate.vacancies.show', $vacancyId)
                ->with('error', 'You have already applied for this position.');
        }

        // If there's a draft, redirect to edit
        if ($existingApplication && $existingApplication->isDraft()) {
            return redirect()
                ->route('candidate.applications.edit', ['vacancyId' => $vacancyId, 'id' => $existingApplication->id])
                ->with('info', 'You have a draft application for this position. Continue editing.');
        }

        // Check if vacancy is still active
        if ($vacancy->status !== 'active') {
            return redirect()
                ->route('candidate.vacancies.index')
                ->with('error', 'This vacancy is no longer accepting applications.');
        }

        // Check if deadline has passed
        if ($vacancy->deadline && $vacancy->deadline < now()) {
            return redirect()
                ->route('candidate.vacancies.index')
                ->with('error', 'The application deadline for this position has passed.');
        }

        // Check eligibility
        $eligibilityError = $this->checkEligibility($candidate, $vacancy);
        if ($eligibilityError) {
            return redirect()
                ->route('candidate.vacancies.show', $vacancyId)
                ->with('error', $eligibilityError);
        }

        $draftApplication = new ApplicationForm();
        return view('candidate.applications.create', compact('vacancy', 'candidate', 'draftApplication'));
    }

    /**
     * Store application (submit or save as draft)
     */
    public function store(Request $request, $vacancyId)
    {
        $candidate = Auth::guard('candidate')->user();
        $vacancy = Vacancy::findOrFail($vacancyId);

        $isDraft = $request->has('save_draft');

        // Check for existing application
        $existingApplication = ApplicationForm::where('candidate_id', $candidate->id)
            ->where('vacancy_id', $vacancyId)
            ->first();

        if ($existingApplication && !$existingApplication->isDraft()) {
            return redirect()
                ->route('candidate.vacancies.show', $vacancyId)
                ->with('error', 'You have already applied for this position.');
        }

        // Validation rules - relaxed for drafts
        if ($isDraft) {
            $validated = $request->validate($this->draftValidationRules());
        } else {
            $validated = $request->validate($this->submitValidationRules());
        }

        // Handle file uploads
        $validated = $this->handleFileUploads($request, $validated, $candidate->id);

        // Convert same_as_permanent checkbox
        $validated['same_as_permanent'] = $request->has('same_as_permanent') ? 1 : 0;

        // Prepare data
        $data = array_merge($validated, [
            'candidate_id' => $candidate->id,
            'vacancy_id' => $vacancyId,
            'status' => $isDraft ? 'draft' : 'pending',
        ]);

        if (!$isDraft) {
            $data['submitted_at'] = now();
            // Approximate BS date conversion (BS year ≈ AD year + 56-57)
            $adDate = now();
            $bsYear = $adDate->year + 56;
            $bsMonth = $adDate->month;
            $bsDay = $adDate->day;
            $data['submitted_at_bs'] = sprintf('%04d-%02d-%02d', $bsYear, $bsMonth, $bsDay);
        }

        if ($existingApplication) {
            // Update existing draft
            $existingApplication->update($data);
            $application = $existingApplication;
        } else {
            // Create new application
            $application = ApplicationForm::create($data);
        }

        if ($isDraft) {
            return redirect()
                ->route('candidate.applications.show', $application->id)
                ->with('success', 'Application saved as draft successfully!');
        }

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

        $applicationform = ApplicationForm::where('id', $id)
            ->where('candidate_id', $candidate->id)
            ->with(['vacancy', 'reviewer', 'payment'])
            ->firstOrFail();

        return view('candidate.applications.show', compact('applicationform'));
    }

    /**
     * Show edit form
     */
    public function edit($vacancyId, $id)
    {
        $candidate = Auth::guard('candidate')->user();

        $applicationform = ApplicationForm::where('id', $id)
            ->where('candidate_id', $candidate->id)
            ->where('vacancy_id', $vacancyId)
            ->with('vacancy')
            ->firstOrFail();

        if (!$applicationform->canEdit()) {
            return redirect()
                ->route('candidate.applications.show', $id)
                ->with('error', 'This application cannot be edited.');
        }

        $vacancy = $applicationform->vacancy;

        return view('candidate.applications.edit', compact('applicationform', 'vacancy', 'candidate'));
    }

    /**
     * Update application
     */
    public function update(Request $request, $vacancyId, $id)
    {
        $candidate = Auth::guard('candidate')->user();

        $application = ApplicationForm::where('id', $id)
            ->where('candidate_id', $candidate->id)
            ->where('vacancy_id', $vacancyId)
            ->firstOrFail();

        if (!$application->canEdit()) {
            return redirect()
                ->route('candidate.applications.show', $id)
                ->with('error', 'This application cannot be edited.');
        }

        $isDraft = $request->has('save_draft');

        if ($isDraft) {
            $validated = $request->validate($this->draftValidationRules());
        } else {
            $validated = $request->validate($this->submitValidationRules(true));
        }

        // Handle file uploads
        $validated = $this->handleFileUploads($request, $validated, $candidate->id, $application);

        // Convert same_as_permanent checkbox
        $validated['same_as_permanent'] = $request->has('same_as_permanent') ? 1 : 0;

        // Track if application was in 'edit' status before update
        $wasInEditStatus = $application->status === 'edit';
        $reviewerId = $application->reviewer_id;

        if (!$isDraft && $application->isDraft()) {
            $validated['status'] = 'pending';
            $validated['submitted_at'] = now();
            // Add BS date
            $adDate = now();
            $bsYear = $adDate->year + 56;
            $validated['submitted_at_bs'] = sprintf('%04d-%02d-%02d', $bsYear, $adDate->month, $adDate->day);
        }

        // If resubmitting after correction, change status from 'edit' to 'pending'
        if (!$isDraft && $wasInEditStatus) {
            $validated['status'] = 'pending';
            $validated['submitted_at'] = now();
            // Add BS date
            $adDate = now();
            $bsYear = $adDate->year + 56;
            $validated['submitted_at_bs'] = sprintf('%04d-%02d-%02d', $bsYear, $adDate->month, $adDate->day);
        }

        $application->update($validated);

        // Create notification for reviewer when candidate resubmits after correction
        if (!$isDraft && $wasInEditStatus && $reviewerId) {
            Notification::create([
                'user_id' => $reviewerId,
                'user_type' => 'reviewer',
                'type' => 'application_resubmitted',
                'title' => 'Application Resubmitted',
                'message' => 'The candidate has resubmitted the application for "' . $application->vacancy->title . '" after making corrections.',
                'related_id' => $application->id,
                'related_type' => 'application',
            ]);
        }

        if ($isDraft) {
            return redirect()
                ->route('candidate.applications.show', $id)
                ->with('success', 'Draft saved successfully!');
        }

        return redirect()
            ->route('candidate.applications.show', $id)
            ->with('success', 'Application updated successfully!');
    }

    /**
     * Delete/withdraw application
     */
    public function destroy($id)
    {
        $candidate = Auth::guard('candidate')->user();

        $application = ApplicationForm::where('id', $id)
            ->where('candidate_id', $candidate->id)
            ->firstOrFail();

        if (!$application->canWithdraw()) {
            return redirect()
                ->route('candidate.applications.show', $id)
                ->with('error', 'This application cannot be withdrawn.');
        }

        // Delete the application entirely if draft, otherwise just mark deleted
        if ($application->isDraft()) {
            $application->delete();
        } else {
            $application->delete();
        }

        return redirect()
            ->route('candidate.applications.index')
            ->with('success', 'Application withdrawn successfully.');
    }

    /**
     * Store application - flat route (vacancy_id from form body, not URL)
     */
    public function storeFlat(Request $request)
    {
        $vacancyId = $request->input('vacancy_id');
        if (!$vacancyId) {
            return redirect()->back()->withErrors(['error' => 'Vacancy ID is required.']);
        }
        return $this->store($request, $vacancyId);
    }

    /**
     * Show edit form - flat route (just application ID, no vacancyId in URL)
     */
    public function editFlat($id)
    {
        $candidate = Auth::guard('candidate')->user();
        $application = ApplicationForm::where('id', $id)
            ->where('candidate_id', $candidate->id)
            ->firstOrFail();
        return $this->edit($application->vacancy_id, $id);
    }

    /**
     * Update application - flat route (just application ID, no jobId in URL)
     */
    public function updateFlat(Request $request, $id)
    {
        $candidate = Auth::guard('candidate')->user();
        $application = ApplicationForm::where('id', $id)
            ->where('candidate_id', $candidate->id)
            ->firstOrFail();
        return $this->update($request, $application->vacancy_id, $id);
    }

    /**
     * Save application as draft (AJAX endpoint)
     */
    public function saveDraft(Request $request)
    {
        try {
            $candidate = Auth::guard('candidate')->user();

            $vacancyId = $request->input('vacancy_id');
            if (!$vacancyId) {
                return response()->json(['success' => false, 'message' => 'Vacancy ID is required.'], 422);
            }

            $vacancy = Vacancy::find($vacancyId);
            if (!$vacancy) {
                return response()->json(['success' => false, 'message' => 'Vacancy not found.'], 404);
            }

            $validated = $request->validate($this->draftValidationRules());
            $validated = $this->handleFileUploads($request, $validated, $candidate->id);
            $validated['same_as_permanent'] = $request->has('same_as_permanent') ? 1 : 0;

            $draftId = $request->input('draft_id');
            if ($draftId) {
                $application = ApplicationForm::where('id', $draftId)
                    ->where('candidate_id', $candidate->id)
                    ->where('status', 'draft')
                    ->first();
            } else {
                $application = ApplicationForm::where('candidate_id', $candidate->id)
                    ->where('vacancy_id', $vacancyId)
                    ->where('status', 'draft')
                    ->first();
            }

            $data = array_merge($validated, [
                'candidate_id' => $candidate->id,
                'vacancy_id' => $vacancyId,
                'status' => 'draft',
            ]);

            if ($application) {
                $application->update($data);
            } else {
                $application = ApplicationForm::create($data);
            }

            return response()->json([
                'success' => true,
                'draft_id' => $application->id,
                'message' => 'Draft saved successfully.',
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to save draft: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Check eligibility for a vacancy
     */
    public function checkEligibilityAjax(Request $request, $vacancyId)
    {
        $candidate = Auth::guard('candidate')->user();
        $vacancy = Vacancy::findOrFail($vacancyId);

        $error = $this->checkEligibility($candidate, $vacancy);

        if ($error) {
            return response()->json(['eligible' => false, 'message' => $error]);
        }

        return response()->json(['eligible' => true, 'message' => 'You are eligible to apply.']);
    }

    /**
     * Check eligibility based on age and other criteria
     */
    private function checkEligibility($candidate, $vacancy)
    {
        // Check age eligibility if candidate has DOB
        if ($candidate->date_of_birth_bs && ($vacancy->min_age || $vacancy->max_age)) {
            // Simple age calculation based on BS date (approximate)
            // In production, use a proper BS-to-AD date converter
            $age = null;
            if ($candidate->date_of_birth_bs) {
                $parts = explode('-', $candidate->date_of_birth_bs);
                if (count($parts) === 3) {
                    $birthYear = (int) $parts[0];
                    $currentBsYear = 2082; // Approximate current BS year
                    $age = $currentBsYear - $birthYear;
                }
            }

            if ($age !== null) {
                if ($vacancy->min_age && $age < $vacancy->min_age) {
                    return "You must be at least {$vacancy->min_age} years old to apply for this position. Your age: {$age}";
                }
                if ($vacancy->max_age && $age > $vacancy->max_age) {
                    return "You must be at most {$vacancy->max_age} years old to apply for this position. Your age: {$age}";
                }
            }
        }

        return null;
    }

    /**
     * Validation rules for draft saving (all optional)
     */
    private function draftValidationRules()
    {
        return [
            // Personal Information (extended)
            'name_english' => 'nullable|string|max:255',
            'name_nepali' => ['nullable', 'regex:/^[\x{0900}-\x{097F}\s\.\-]+$/u', 'max:255'],
            'email' => 'nullable|email|max:255',
            'advertisement_no' => 'nullable|string|max:255',
            'applying_position' => 'nullable|string|max:255',
            'department' => 'nullable|string|max:255',
            'alternate_phone_number' => 'nullable|string|max:20',
            // General information
            'religion' => 'nullable|string',
            'religion_other' => 'nullable|string|max:255',
            'community' => 'nullable|string|max:255',
            'community_other' => 'nullable|string|max:255',
            'ethnic_group' => 'nullable|string|max:255',
            'ethnic_group_other' => 'nullable|string|max:255',
            'marital_status' => 'nullable|string',
            'employment_status' => 'nullable|string',
            'employment_other' => 'nullable|string|max:255',
            'physical_disability' => 'nullable|string',
            'disability_other' => 'nullable|string|max:255',
            'mother_tongue' => 'nullable|string|max:255',
            'blood_group' => 'nullable|string|max:10',
            'noc_employee' => 'nullable|string',
            'birth_date_ad' => 'nullable|date',
            'birth_date_bs' => 'nullable|date',
            'age' => 'nullable|integer|min:0|max:40',
            'phone' => 'nullable|string|max:20',
            'gender' => 'nullable|string',
            'citizenship_number' => 'nullable|string|max:255',
            'citizenship_issue_date_bs' => 'nullable|date',
            'citizenship_issue_date_ad' => 'nullable|date|before_or_equal:today',
            'citizenship_issue_district' => 'nullable|string|max:255',
            'father_name_english' => 'nullable|string|max:255',
            'father_name_nepali' => 'nullable|string|max:255',
            'father_qualification' => 'nullable|string|max:255',
            'mother_name_english' => 'nullable|string|max:255',
            'mother_name_nepali' => 'nullable|string|max:255',
            'mother_qualification' => 'nullable|string|max:255',
            'parent_occupation' => 'nullable|string|max:255',
            'parent_occupation_other' => 'nullable|string|max:255',
            'grandfather_name_english' => 'nullable|string|max:255',
            'grandfather_name_nepali' => 'nullable|string|max:255',
            'nationality' => 'nullable|string|max:255',
            'spouse_name_english' => 'nullable|string|max:255',
            'spouse_name_nepali' => 'nullable|string|max:255',
            'spouse_nationality' => 'nullable|string|max:255',
            'permanent_province' => 'nullable|string|max:255',
            'permanent_district' => 'nullable|string|max:255',
            'permanent_municipality' => 'nullable|string|max:255',
            'permanent_ward' => 'nullable|string|max:50',
            'permanent_tole' => 'nullable|string|max:255',
            'permanent_house_number' => 'nullable|string|max:50',
            'same_as_permanent' => 'nullable|boolean',
            'mailing_province' => 'nullable|string|max:255',
            'mailing_district' => 'nullable|string|max:255',
            'mailing_municipality' => 'nullable|string|max:255',
            'mailing_ward' => 'nullable|string|max:50',
            'mailing_tole' => 'nullable|string|max:255',
            'mailing_house_number' => 'nullable|string|max:50',
            'cover_letter' => 'nullable|string',
            'years_of_experience' => 'nullable|integer|min:0',
            'relevant_experience' => 'nullable|string',
            // Education
            'education_level' => 'nullable|string|max:255',
            'field_of_study' => 'nullable|string|max:255',
            'institution_name' => 'nullable|string|max:255',
            'university' => 'required|string|max:255',
            'graduation_year' => 'nullable|digits:4|integer|min:1950|max:2090', 
            'graduation_year_english' => 'nullable|digits:4|integer|min:1950|max:2090',
            // Experience
            'has_work_experience' => 'nullable|string',
            'previous_organization' => 'nullable|string|max:255',
            'previous_position' => 'nullable|string|max:255',
            // Terms
            'terms_agree' => 'nullable|boolean',
            // Documents
            'passport_photo' => 'nullable|file|mimes:jpg,jpeg,png|max:700',
            'resume' => 'nullable|file|mimes:pdf,doc,docx|max:700',
            'citizenship_certificate' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:700',
            'educational_certificates' => 'nullable|file|mimes:pdf,zip|max:700',
            'cover_letter_file' => 'nullable|file|mimes:pdf,doc,docx|max:700',
            'experience_certificates' => 'nullable|file|mimes:pdf,zip|max:700',
            'character_certificate' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:700',
            'equivalency_certificate' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:700',
            'signature' => 'nullable|file|mimes:jpg,jpeg,png|max:700',
            'ethnic_certificate' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:700',
            'disability_certificate' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:700',
            'noc_id_card' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:700',
            'other_documents' => 'nullable|file|mimes:pdf,zip|max:700',
        ];
    }

    /**
     * Validation rules for submission (required fields)
     */
    private function submitValidationRules($isUpdate = false)
    {
        $fileRule = $isUpdate ? 'nullable' : 'required';

        return [
            // Personal Information (extended)
            'name_english' => 'required|string|max:255',
            'name_nepali' => ['required', 'regex:/^[\x{0900}-\x{097F}\s\.\-]+$/u', 'max:255'],
            'email' => 'required|email|max:255',
            'advertisement_no' => 'required|string|max:255',
            'applying_position' => 'required|string|max:255',
            'department' => 'required|string|max:255',
            'alternate_phone_number' => 'required|string|max:10',
            // General information
            'religion' => 'required|string',
            'religion_other' => 'nullable|string|max:255',
            'community' => 'nullable|string|max:255',
            'community_other' => 'nullable|string|max:255',
            'ethnic_group' => 'nullable|string|max:255',
            'ethnic_group_other' => 'nullable|string|max:255',
            'marital_status' => 'required|string',
            'employment_status' => 'nullable|string',
            'employment_other' => 'nullable|string|max:255',
            'physical_disability' => 'required|string',
            'disability_other' => 'nullable|string|max:255',
            'mother_tongue' => 'required|string|max:255',
            'blood_group' => 'nullable|string|max:10',
            'noc_employee' => 'required|string',
            'birth_date_ad' => 'nullable|date',
            'birth_date_bs' => 'nullable|date',
            'age' => 'required|integer|min:18|max:65',
            'phone' => 'required|string|max:20',
            'gender' => 'required|string',
            'citizenship_number' => 'required|string|max:255',
            'citizenship_issue_date_bs' => 'nullable|date',
            'citizenship_issue_date_ad' => 'required|date|before_or_equal:today',
            'citizenship_issue_district' => 'required|string|max:255',
            'father_name_english' => 'required|string|max:255',
            'father_name_nepali' => 'required|string|max:255',
            'father_qualification' => 'nullable|string|max:255',
            'mother_name_english' => 'required|string|max:255',
            'mother_name_nepali' => 'required|string|max:255',
            'mother_qualification' => 'nullable|string|max:255',
            'parent_occupation' => 'nullable|string|max:255',
            'parent_occupation_other' => 'nullable|string|max:255',
            'grandfather_name_english' => 'required|string|max:255',
            'grandfather_name_nepali' => 'required|string|max:255',
            'nationality' => 'required|string|max:255',
            'spouse_name_english' => 'nullable|string|max:255',
            'spouse_name_nepali' => 'nullable|string|max:255',
            'spouse_nationality' => 'nullable|string|max:255',
            'permanent_province' => 'required|string|max:255',
            'permanent_district' => 'required|string|max:255',
            'permanent_municipality' => 'required|string|max:255',
            'permanent_ward' => 'required|string|max:50',
            'permanent_tole' => 'nullable|string|max:255',
            'permanent_house_number' => 'nullable|string|max:50',
            'same_as_permanent' => 'nullable|boolean',
            'mailing_province' => 'nullable|string|max:255',
            'mailing_district' => 'nullable|string|max:255',
            'mailing_municipality' => 'nullable|string|max:255',
            'mailing_ward' => 'nullable|string|max:50',
            'mailing_tole' => 'nullable|string|max:255',
            'mailing_house_number' => 'nullable|string|max:50',
            'cover_letter' => 'nullable|string',
            'years_of_experience' => 'required|integer|min:0',
            'relevant_experience' => 'nullable|string',
            // Education
            'education_level' => 'required|string|max:255',
            'field_of_study' => 'required|string|max:255',
            'institution_name' => 'required|string|max:255',
            'graduation_year' => 'required|digits:4|integer|min:1950|max:2090', 
            'graduation_year_english' => 'required|digits:4|integer|min:1950|max:2090', 
            // Experience
            'has_work_experience' => 'required|string',
            'previous_organization' => 'nullable|string|max:255',
            'previous_position' => 'nullable|string|max:255',
            // Terms
            'terms_agree' => 'accepted',
            // Documents
            'passport_photo' => $fileRule . '|file|mimes:jpg,jpeg,png|max:700',
            'citizenship_certificate' => $fileRule . '|file|mimes:pdf,jpg,jpeg,png|max:700',
            'educational_certificates' => $fileRule . '|file|mimes:pdf,zip|max:700',
            'character_certificate' => $fileRule . '|file|mimes:pdf,jpg,jpeg,png|max:700',
            'signature' => $fileRule . '|file|mimes:jpg,jpeg,png|max:700',
            'cover_letter_file' => 'nullable|file|mimes:pdf,doc,docx|max:700',
            'experience_certificates' => 'nullable|file|mimes:pdf,zip|max:700',
            'equivalency_certificate' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:700',
            'ethnic_certificate' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:700',
            'disability_certificate' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:700',
            'noc_id_card' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:700',
            'other_documents' => 'nullable|file|mimes:pdf,zip|max:700',
        ];
    }

    /**
     * Handle file uploads
     */
    private function handleFileUploads(Request $request, array $validated, $candidateId, $existingApplication = null)
    {
        $fileFields = [
            'passport_photo',
            'citizenship_certificate',
            'educational_certificates',
            'character_certificate',
            'equivalency_certificate',
            'signature',
            'resume',
            'cover_letter_file',
            'experience_certificates',
            'noc_id_card',
            'ethnic_certificate',
            'disability_certificate',
            'other_documents',
        ];

        foreach ($fileFields as $field) {
            if ($request->hasFile($field)) {
                // Delete old file if exists
                if ($existingApplication && $existingApplication->$field && Storage::disk('public')->exists($existingApplication->$field)) {
                    Storage::disk('public')->delete($existingApplication->$field);
                }
                $validated[$field] = $request->file($field)->store('applications/' . $candidateId, 'public');
            } else {
                // Don't overwrite existing file paths with null
                unset($validated[$field]);
            }
        }

        return $validated;
    }
}
