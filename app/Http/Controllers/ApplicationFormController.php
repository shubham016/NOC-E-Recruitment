<?php

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

        $forms = ApplicationForm::where('citizenship_number', $candidate->citizenship_number)
            ->latest()
            ->paginate(10);

        return view('candidate.applications.index', compact('forms'));
    }

    /**
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
                ->where('status', '!=', 'draft')
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
        $data = $request->except(array_keys($this->fileFields));
        
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
        $data['status'] = 'pending'; // Final submission

        if ($existingDraft) {
            // Update the draft to final submission
            $existingDraft->update($data);
            $application = $existingDraft;
        } else {
            // Create new application
            $application = ApplicationForm::create($data);
        }

        return redirect()->route('candidate.applications.index')
            ->with('success', 'Application submitted successfully!');
    }

    /**
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

        return view('candidate.applications.show', compact('applicationform'));
    }

    /**
     * Show the form for editing the specified application
     */
    public function edit(ApplicationForm $applicationform)
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

        return view('candidate.applications.edit', compact('applicationform'));
    }

    /**
     * Update the specified application
     */
    public function update(Request $request, ApplicationForm $applicationform)
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

        $validated = $request->validate(
            $this->validationRules(false),
            $this->validationMessages()
        );

        $data = $request->except(array_keys($this->fileFields));
        
        $uploadedFiles = $this->handleFileUploads($request, $applicationform, false);

        $data = array_merge($data, $uploadedFiles);

        if ($request->boolean('same_as_permanent')) {
            $data = array_merge($data, $this->copyPermanentToMailing($request));
        }

        $applicationform->update($data);

        return redirect()->route('candidate.applications.index')
            ->with('success', 'Application updated successfully!');
    }

    /**
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
            ->where('status', '!=', 'draft')
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
        ];
    }

    /**
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