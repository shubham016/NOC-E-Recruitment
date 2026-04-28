<?php

<<<<<<< HEAD
namespace App\Http\Controllers;

use App\Models\ApplicationForm;
use App\Models\JobPosting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ApplicationFormController extends Controller
{
    private $fileFields = [
        'ethnic_certificate'       => 'ethnic-certificates',
        'noc_id_card'              => 'noc-id-cards',
        'disability_certificate'   => 'disability-certificates',
        'citizenship_id_document'  => 'citizenship-documents',
        'passport_size_photo'      => 'passport-photos',
        'signature'                => 'signatures',
        'transcript'               => 'transcripts',
        'character'                => 'character-certificates',
        'equivalent'               => 'equivalency-certificates',
        'work_experience'          => 'work-experience-documents',
        'exp1_document' => 'experience-documents',
        'exp2_document' => 'experience-documents',
        'exp3_document' => 'experience-documents',
    ];

    /**
     * Display a listing of applications
     */
    public function index()
    {
        if (!Session::has('candidate_logged_in')) {
            return redirect()->route('candidate.login')
                ->withErrors(['error' => 'Please login first']);
        }

        $candidate = DB::table('candidate_registration')
            ->where('id', Session::get('candidate_id'))
            ->first();

        $forms = ApplicationForm::with('payment')
        ->where('citizenship_number', $candidate->citizenship_number)
        ->latest()
        ->paginate(10);
=======
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
>>>>>>> efe7d213166d7eb2c3aef5455d337ce01292fe6f

        return view('candidate.applications.index', compact('forms'));
    }

    /**
<<<<<<< HEAD
     * Show the form for creating a new application
     */
    public function create($jobId = null)
    {
        if (!Session::has('candidate_logged_in')) {
            return redirect()->route('candidate.login')
                ->withErrors(['error' => 'Please login first']);
        }

        // Get candidate data
        $candidate = DB::table('candidate_registration')
            ->where('id', Session::get('candidate_id'))
            ->first();

        $job = null;
        if ($jobId) {
            $job = JobPosting::find($jobId);
            
            if (!$job) {
                return redirect()->route('candidate.jobs.index')
                    ->withErrors(['error' => 'Job posting not found']);
            }
        }

        // Check for existing draft application for this job
        $draftApplication = null;
        if ($jobId) {
            $draftApplication = ApplicationForm::where('citizenship_number', $candidate->citizenship_number)
                ->where('job_posting_id', $jobId)
                ->where('status', 'draft')
                ->first();
        } else {
            // Get the most recent draft without job_posting_id
            $draftApplication = ApplicationForm::where('citizenship_number', $candidate->citizenship_number)
                ->whereNull('job_posting_id')
                ->where('status', 'draft')
                ->latest()
                ->first();
        }

        return view('candidate.applications.create', compact('job', 'candidate', 'draftApplication'));
    }

    
    public function saveDraft(Request $request)
{
    if (!Session::has('candidate_logged_in')) {
        return response()->json(['success' => false, 'message' => 'Not authenticated'], 401);
    }

    $candidate = DB::table('candidate_registration')
        ->where('id', Session::get('candidate_id'))
        ->first();

    try {
        // Log incoming request for debugging
        Log::info('Draft save attempt', [
            'candidate_id' => $candidate->id,
            'has_draft_id' => $request->has('draft_id'),
            'draft_id' => $request->draft_id,
            'job_posting_id' => $request->job_posting_id,
            'has_files' => $this->hasAnyFiles($request),
            'files_present' => array_keys($request->allFiles())
        ]);

        // Find or create draft
        $draft = null;
        
        if ($request->filled('draft_id')) {
            $draft = ApplicationForm::where('id', $request->draft_id)
                ->where('citizenship_number', $candidate->citizenship_number)
                ->where('status', 'draft')
                ->first();
                
            Log::info('Found draft by ID', ['draft_id' => $draft ? $draft->id : null]);
        }
        
        if (!$draft && $request->filled('job_posting_id')) {
            $draft = ApplicationForm::where('citizenship_number', $candidate->citizenship_number)
                ->where('job_posting_id', $request->job_posting_id)
                ->where('status', 'draft')
                ->first();
                
            Log::info('Found draft by job_posting_id', ['draft_id' => $draft ? $draft->id : null]);
        }
        
        if (!$draft) {
            $draft = ApplicationForm::where('citizenship_number', $candidate->citizenship_number)
                ->where('status', 'draft')
                ->whereNull('job_posting_id')
                ->latest()
                ->first();
                
            Log::info('Found draft without job_posting_id', ['draft_id' => $draft ? $draft->id : null]);
        }

        // Get all data except files and tokens
        $data = $request->except([
            '_token',
            '_method',
            ...array_keys($this->fileFields)
        ]);

        // Handle same_as_permanent checkbox
        if ($request->boolean('same_as_permanent')) {
            $mailingData = $this->copyPermanentToMailing($request);
            $data = array_merge($data, $mailingData);
        }

        // Add required fields
        $data['citizenship_number'] = $candidate->citizenship_number;
        $data['status'] = 'draft';
        
        if ($request->filled('job_posting_id')) {
            $data['job_posting_id'] = $request->job_posting_id;
        }

        // Remove empty values
        $data = array_filter($data, function($value) {
            return !is_null($value) && $value !== '';
        });

        // Create or update draft
        if ($draft) {
            $draft->update($data);
            Log::info('Draft updated', ['draft_id' => $draft->id]);
        } else {
            $draft = ApplicationForm::create($data);
            Log::info('Draft created', ['draft_id' => $draft->id]);
        }

        // Handle file uploads
        $fileData = [];
        if ($this->hasAnyFiles($request)) {
            Log::info('Processing file uploads', ['files' => array_keys($request->allFiles())]);
            
            $fileData = $this->handleFileUploads($request, $draft, true);
            
            if (!empty($fileData)) {
                $draft->update($fileData);
                Log::info('Files saved successfully', [
                    'draft_id' => $draft->id, 
                    'saved_files' => array_keys($fileData),
                    'file_paths' => $fileData
                ]);
            }
        }

        return response()->json([
            'success' => true, 
            'message' => 'Draft saved successfully',
            'draft_id' => $draft->id,
            'saved_at' => now()->format('h:i A'),
            'files_saved' => !empty($fileData) ? array_keys($fileData) : []
        ]);

    } catch (\Exception $e) {
        Log::error('Draft save error', [
            'message' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
            'line' => $e->getLine(),
            'file' => $e->getFile()
        ]);
        
        return response()->json([
            'success' => false, 
            'message' => 'Error saving draft: ' . $e->getMessage()
        ], 500);
    }
}

    /**
     * Check if request has any files to upload
     */
    private function hasAnyFiles(Request $request)
    {
        foreach ($this->fileFields as $field => $folder) {
            if ($request->hasFile($field)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Store a newly created application
     */
    public function store(Request $request)
    {
        if (!Session::has('candidate_logged_in')) {
            return redirect()->route('candidate.login')
                ->withErrors(['error' => 'Please login first']);
        }

        $candidate = DB::table('candidate_registration')
            ->where('id', Session::get('candidate_id'))
            ->first();

        // Validate the request
        $validated = $request->validate(
            $this->validationRules(),
            $this->validationMessages()
        );

        // Check job eligibility if applying for a job
        if ($request->has('job_posting_id')) {
            $job = JobPosting::find($request->job_posting_id);
            
            if (!$job) {
                return redirect()->back()
                    ->withErrors(['error' => 'Job posting not found'])
                    ->withInput();
            }

            // Check if already applied (exclude drafts)
            $existingApplication = ApplicationForm::where('job_posting_id', $job->id)
                ->where('citizenship_number', $candidate->citizenship_number)
                ->whereNotIn('status', ['draft', 'edit'])
                ->first();

            if ($existingApplication) {
                return redirect()->route('candidate.applications.index')
                    ->withErrors(['error' => 'You have already applied for this position.']);
            }

            // Check eligibility
            $applicationData = (object) [
                'age' => $request->age,
                'education_level' => $request->education_level,
                'gender' => $request->gender,
                'ethnic_group' => $request->ethnic_group,
                'community' => $request->community,
                'physical_disability' => $request->physical_disability,
            ];

            $eligibility = $job->isEligible($applicationData);

            if (!$eligibility['eligible']) {
                return redirect()->back()
                    ->withErrors([
                        'eligibility' => 'You are not eligible for this position.',
                        'reasons' => $eligibility['errors']
                    ])
                    ->withInput();
            }
        }

        // Get all data except files
        $data = $request->except(array_merge(array_keys($this->fileFields), ['status']));
        
        // Check if updating a draft
        $existingDraft = null;
        if ($request->has('draft_id')) {
            $existingDraft = ApplicationForm::where('id', $request->draft_id)
                ->where('citizenship_number', $candidate->citizenship_number)
                ->where('status', 'draft')
                ->first();
        }
        
        // Handle file uploads (pass existing draft to preserve files if no new upload)
        $uploadedFiles = $this->handleFileUploads($request, $existingDraft, false);
        $data = array_merge($data, $uploadedFiles);

        // Handle same as permanent address
        if ($request->boolean('same_as_permanent')) {
            $data = array_merge($data, $this->copyPermanentToMailing($request));
        }

        // Add job posting ID if exists
        if ($request->has('job_posting_id')) {
            $data['job_posting_id'] = $request->job_posting_id;
        }

        $data['citizenship_number'] = $candidate->citizenship_number;
        $data['status'] = 'submitted'; // Final submission

        if ($existingDraft) {
            // Update the draft to final submission
            $existingDraft->update($data);
            $application = $existingDraft;
=======
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
>>>>>>> efe7d213166d7eb2c3aef5455d337ce01292fe6f
        } else {
            // Create new application
            $application = ApplicationForm::create($data);
        }

<<<<<<< HEAD
        return redirect()->route('candidate.applications.index')
=======
        if ($isDraft) {
            return redirect()
                ->route('candidate.applications.show', $application->id)
                ->with('success', 'Application saved as draft successfully!');
        }

        return redirect()
            ->route('candidate.applications.show', $application->id)
>>>>>>> efe7d213166d7eb2c3aef5455d337ce01292fe6f
            ->with('success', 'Application submitted successfully!');
    }

    /**
<<<<<<< HEAD
     * Display the specified application
     */
    public function show(ApplicationForm $applicationform)
    {
        if (!Session::has('candidate_logged_in')) {
            return redirect()->route('candidate.login')
                ->withErrors(['error' => 'Please login first']);
        }

        $candidate = DB::table('candidate_registration')
            ->where('id', Session::get('candidate_id'))
            ->first();

        if ($applicationform->citizenship_number !== $candidate->citizenship_number) {
            return redirect()->route('candidate.applications.index')
                ->withErrors(['error' => 'Unauthorized access']);
        }
=======
     * Show single application
     */
    public function show($id)
    {
        $candidate = Auth::guard('candidate')->user();

        $applicationform = ApplicationForm::where('id', $id)
            ->where('candidate_id', $candidate->id)
            ->with(['vacancy', 'reviewer', 'payment'])
            ->firstOrFail();
>>>>>>> efe7d213166d7eb2c3aef5455d337ce01292fe6f

        return view('candidate.applications.show', compact('applicationform'));
    }

    /**
<<<<<<< HEAD
     * Show the form for editing the specified application
     */
    public function edit(ApplicationForm $applicationform)
{
    if (!Session::has('candidate_logged_in')) {
        return redirect()->route('candidate.login');
    }

    $candidate = DB::table('candidate_registration')
        ->where('id', Session::get('candidate_id'))
        ->first();

    if ($applicationform->citizenship_number !== $candidate->citizenship_number) {
        return redirect()->route('candidate.applications.index')
            ->withErrors(['error' => 'Unauthorized access']);
    }

    // ✅ Block editing after payment/submission
    if (!in_array($applicationform->status, ['draft', 'edit', 'edited'])) {
        return redirect()->route('candidate.applications.index')
            ->with('error', 'This application has already been submitted and cannot be edited.');
    }

    return view('candidate.applications.edit', compact('applicationform'));
}

    /**
     * Update the specified application
     */
    public function update(Request $request, ApplicationForm $applicationform)
    {
        \Log::info('UPDATE DEBUG', [
    'current_status_in_db' => $applicationform->status,
    'status_in_data'       => $data['status'] ?? 'NOT SET',
    'all_data_keys'        => array_keys($data),
]);
        if (!Session::has('candidate_logged_in')) {
            return redirect()->route('candidate.login')
                ->withErrors(['error' => 'Please login first']);
        }

        $candidate = DB::table('candidate_registration')
            ->where('id', Session::get('candidate_id'))
            ->first();

        if ($applicationform->citizenship_number !== $candidate->citizenship_number) {
            return redirect()->route('candidate.applications.index')
                ->withErrors(['error' => 'Unauthorized access']);
        }

        $validated = $request->validate(
            $this->validationRules(false),
            $this->validationMessages()
        );

        $data = $request->except(array_merge(array_keys($this->fileFields), ['status']));
        
        $uploadedFiles = $this->handleFileUploads($request, $applicationform, false);

        $data = array_merge($data, $uploadedFiles);

        if ($request->boolean('same_as_permanent')) {
            $data = array_merge($data, $this->copyPermanentToMailing($request));
        }

        // If the form is in a post-payment edit state, mark it as 'edited'
        if (in_array($applicationform->status, ['edit', 'edited'])) {
            $data['status'] = 'edited';
        }

        $applicationform->update($data);

        return redirect()->route('candidate.applications.index')
=======
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
>>>>>>> efe7d213166d7eb2c3aef5455d337ce01292fe6f
            ->with('success', 'Application updated successfully!');
    }

    /**
<<<<<<< HEAD
     * Remove the specified application
     */
    public function destroy(ApplicationForm $applicationform)
    {
        if (!Session::has('candidate_logged_in')) {
            return redirect()->route('candidate.login')
                ->withErrors(['error' => 'Please login first']);
        }

        $candidate = DB::table('candidate_registration')
            ->where('id', Session::get('candidate_id'))
            ->first();

        if ($applicationform->citizenship_number !== $candidate->citizenship_number) {
            return redirect()->route('candidate.applications.index')
                ->withErrors(['error' => 'Unauthorized access']);
        }

        $this->deleteAssociatedFiles($applicationform);
        $applicationform->delete();

        return redirect()->route('candidate.applications.index')
            ->with('success', 'Application deleted successfully!');
    }

    /**
     * Check eligibility for a job
     */
    public function checkEligibility(Request $request, $jobId)
    {
        if (!Session::has('candidate_logged_in')) {
            return response()->json([
                'eligible' => false,
                'errors' => ['Please login first']
            ], 401);
        }

        $job = JobPosting::find($jobId);
        
        if (!$job) {
            return response()->json([
                'eligible' => false,
                'errors' => ['Job posting not found']
            ], 404);
        }

        $candidate = DB::table('candidate_registration')
            ->where('id', Session::get('candidate_id'))
            ->first();

        $existingApplication = ApplicationForm::where('job_posting_id', $job->id)
            ->where('citizenship_number', $candidate->citizenship_number)
            ->whereNotIn('status', ['draft', 'edit'])
            ->first();

        if ($existingApplication) {
            return response()->json([
                'eligible' => false,
                'errors' => ['You have already applied for this position.']
            ]);
        }

        $applicationData = (object) [
            'age' => $candidate->age ?? 0,
            'education_level' => $candidate->education_level ?? '',
            'gender' => $candidate->gender ?? '',
            'ethnic_group' => $candidate->ethnic_group ?? '',
            'community' => $candidate->community ?? '',
            'physical_disability' => $candidate->physical_disability ?? 'no',
        ];

        $eligibility = $job->isEligible($applicationData);

        return response()->json($eligibility);
    }

    /**
     * Validation rules for application form
     */
    private function validationRules($isStore = true)
    {
        $rules = [
            'name_english' => 'required|string|max:255',
            'name_nepali' => 'required|string|max:255',
            'birth_date_ad' => 'required|date',
            'birth_date_bs' => 'required|string',
            'age' => 'required|integer|min:18|max:40',
            'phone' => 'required|digits:10',
            'email' => 'required|email',
            'gender' => 'required|in:Male,Female,Other',
            'citizenship_number' => 'required|string|max:50',
            'citizenship_issue_date_bs' => 'required|string',
            'citizenship_issue_district' => 'required|string',
            'permanent_province' => 'required|string',
            'permanent_district' => 'required|string',
            'permanent_municipality' => 'required|string',
            'permanent_ward' => 'required|string|max:50',
            'mailing_province' => 'required|string',
            'mailing_district' => 'required|string',
            'mailing_municipality' => 'required|string',
            'mailing_ward' => 'required|string|max:50',
            'father_name_english' => 'required|string',
            'mother_name_english' => 'required|string',
            'grandfather_name_english' => 'required|string',
            'father_qualification' => 'required|string',
            'mother_qualification' => 'required|string',
            'parent_occupation' => 'required|string',
            'nationality' => 'required|string',
            'blood_group' => 'required|string',
            'marital_status' => 'required|string',
            'religion' => 'required|string',
            'community' => 'required|string',
            'ethnic_group' => 'required|in:Dalit,Janajati,Madhesi,Brahmin/Chhetri,Other',
            'mother_tongue' => 'required|string',
            'employment_status' => 'required|string',
            'education_level' => 'required|string',
            'field_of_study' => 'required|string',
            'institution_name' => 'required|string',
            'graduation_year' => 'required|integer',
            'has_work_experience' => 'required|in:Yes,No',

            'same_as_permanent' => 'nullable|boolean',
            'physical_disability' => 'required|in:yes,no',
            'noc_employee' => 'required|in:yes,no',
            'job_posting_id' => 'nullable|exists:job_postings,id',
            'advertisement_no' => 'required|string',
            'department' => 'required|string',
            'applying_position' => 'required|string',
            'alternate_phone_number' => 'required|digits:10',
        ];

        // File validation - required on store unless already exists in draft
        if ($isStore) {
            $rules['citizenship_id_document'] = 'required|file|mimes:jpg,jpeg,png,pdf|max:2048';
            $rules['passport_size_photo'] = 'required|image|mimes:jpg,jpeg,png,webp|max:2048';
            $rules['transcript'] = 'required|file|mimes:jpg,jpeg,png,pdf|max:2048';
            $rules['character'] = 'required|file|mimes:jpg,jpeg,png,pdf|max:2048';
            $rules['work_experience'] = 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048';
            $rules['signature'] = 'required|file|mimes:jpg,jpeg,png,pdf|max:2048';
            $rules['equivalent'] = 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048';
        } else {
            $rules['citizenship_id_document'] = 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048';
            $rules['passport_size_photo'] = 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048';
            $rules['transcript'] = 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048';
            $rules['character'] = 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048';
            $rules['work_experience'] = 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048';
            $rules['signature'] = 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048';
            $rules['equivalent'] = 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048';
        }

        // Conditional validation for NOC ID Card
        if ($isStore) {
            $rules['noc_id_card'] = 'required_if:noc_employee,yes|nullable|file|mimes:jpg,jpeg,png,pdf|max:2048';
        } else {
            $rules['noc_id_card'] = 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048';
        }

        // Conditional validation for Disability Certificate
        if ($isStore) {
            $rules['disability_certificate'] = 'required_if:physical_disability,yes|nullable|file|mimes:jpg,jpeg,png,pdf|max:2048';
        } else {
            $rules['disability_certificate'] = 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048';
        }

        // Conditional validation for Ethnic Certificate
        if ($isStore) {
            $rules['ethnic_certificate'] = 'required_if:ethnic_group,Dalit,Janajati|nullable|file|mimes:jpg,jpeg,png,pdf|max:2048';
        } else {
            $rules['ethnic_certificate'] = 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048';
        }

        return $rules;
    }

    /**
     * Custom validation messages
     */
    private function validationMessages()
    {
        return [
            'noc_employee.required' => 'Please select whether you are a NOC employee.',
            'noc_id_card.required_if' => 'NOC ID Card is required when you are a NOC employee.',
            'noc_id_card.mimes' => 'NOC ID Card must be an image (JPEG, JPG, PNG) or PDF.',
            'noc_id_card.max' => 'NOC ID Card must not exceed 2MB.',
            
            'physical_disability.required' => 'Please select whether you have a physical disability.',
            'disability_certificate.required_if' => 'Disability Certificate is required when you have a physical disability.',
            'disability_certificate.mimes' => 'Disability Certificate must be an image (JPEG, JPG, PNG) or PDF.',
            'disability_certificate.max' => 'Disability Certificate must not exceed 2MB.',
            
            'ethnic_group.required' => 'Please select your ethnic group.',
            'ethnic_certificate.required_if' => 'Ethnic Certificate is required for Dalit and Janajati ethnic groups.',
            'ethnic_certificate.mimes' => 'Ethnic Certificate must be an image (JPEG, JPG, PNG) or PDF.',
            'ethnic_certificate.max' => 'Ethnic Certificate must not exceed 2MB.',

            'passport_size_photo.required' => 'Passport size photo is required.',
            'citizenship_id_document.required' => 'Citizenship/ID document is required.',
            'transcript.required' => 'Transcript certificate is required.',
            'character.required' => 'Character certificate is required.',
            'signature.required' => 'Signature is required.',
=======
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
>>>>>>> efe7d213166d7eb2c3aef5455d337ce01292fe6f
        ];
    }

    /**
<<<<<<< HEAD
     * Handle file uploads for the application
     */
    private function handleFileUploads(Request $request, ?ApplicationForm $model = null, $isDraft = false)
{
    $data = [];

    foreach ($this->fileFields as $field => $folder) {
        // Skip if no file uploaded
        if (!$request->hasFile($field)) {
            // PRESERVE existing files if model exists
            if ($model && $model->$field) {
                $data[$field] = $model->$field;
            }
            continue;
        }

        $file = $request->file($field);
        
        // Validate file
        if (!$file->isValid()) {
            Log::warning("Invalid file upload for field: $field");
            continue;
        }

        // Only delete old file if NOT a draft AND file exists
        if (!$isDraft && $model && $model->$field && Storage::disk('public')->exists($model->$field)) {
            Storage::disk('public')->delete($model->$field);
            Log::info("Deleted old file", ['field' => $field, 'path' => $model->$field]);
        }

        // Upload new file
        $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs($folder, $filename, 'public');
        
        Log::info("File uploaded successfully", [
            'field' => $field,
            'filename' => $filename,
            'path' => $path,
            'folder' => $folder,
            'full_path' => storage_path('app/public/' . $path)
        ]);
        
        $data[$field] = $path;
    }

    return $data;
}

    /**
     * Copy permanent address to mailing address
     */
    private function copyPermanentToMailing($request)
    {
        return [
            'mailing_province' => $request->permanent_province,
            'mailing_district' => $request->permanent_district,
            'mailing_municipality' => $request->permanent_municipality,
            'mailing_ward' => $request->permanent_ward,
            'mailing_tole' => $request->permanent_tole,
            'mailing_house_number' => $request->permanent_house_number,
        ];
    }

    /**
     * Delete all associated files when deleting application
     */
    private function deleteAssociatedFiles(ApplicationForm $model)
    {
        foreach ($this->fileFields as $field => $folder) {
            if (!$model->$field) continue;

            if (Storage::disk('public')->exists($model->$field)) {
                Storage::disk('public')->delete($model->$field);
            }
        }
    }
}
=======
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
>>>>>>> efe7d213166d7eb2c3aef5455d337ce01292fe6f
