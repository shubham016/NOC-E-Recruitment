<?php

namespace App\Http\Controllers\Candidate;

use App\Http\Controllers\Controller;
use App\Models\ApplicationForm;
use App\Models\JobPosting;
use App\Models\ApplicationExperience;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
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
        'additional_documents'     => 'additional-documents',
        // exp{n}_document are handled exclusively by saveExperiences() — keep them out of here
    ];

    /**
     * Display a listing of applications
     */
    public function index()
    {
        $candidate = Auth::guard('candidate')->user();

        $forms = ApplicationForm::with('payment')
            ->where('citizenship_number', $candidate->citizenship_number)
            ->latest()
            ->paginate(10);

        return view('candidate.applications.index', compact('forms'));
    }

    /**
     * Show the form for creating a new application
     */
    public function create($jobId = null)
    {
        $candidate = Auth::guard('candidate')->user();
        // dd($candidate);

        $job = null;
        $groupJobs = collect();
        if ($jobId) {
            $job = JobPosting::find($jobId);

            if (!$job) {
                return redirect()->route('candidate.jobs.index')
                    ->withErrors(['error' => 'Job posting not found']);
            }

            // Block fully expired vacancies
            $fullyExpired = $job->status !== 'active'
                || (
                    $job->deadline && now()->gt($job->deadline)
                    && (!$job->double_dastur_date || now()->gt($job->double_dastur_date))
                );

            if ($fullyExpired) {
                return redirect()->route('candidate.jobs.index')
                    ->with('error', 'The application deadline for this vacancy has fully expired.');
            }

            // Load all sibling jobs sharing the same position + level + service_group
            $groupJobs = JobPosting::where('status', 'active')
                ->where(function ($q) {
                    $q->where('deadline', '>=', now())
                        ->orWhere(function ($inner) {
                            $inner->whereNotNull('double_dastur_date')
                                ->where('double_dastur_date', '>=', now());
                        });
                })
                ->where('position', $job->position)
                ->where('level', $job->level)
                ->where('service_group', $job->service_group)
                ->orderBy('advertisement_no', 'asc')
                ->get();

            if ($groupJobs->isEmpty()) {
                $groupJobs = collect([$job]);
            }
        }

        // Check for existing draft application for this job
        $draftApplication = null;
        if ($jobId) {
            $draftApplication = ApplicationForm::with('experiences')
                ->where('citizenship_number', $candidate->citizenship_number)
                ->where('job_posting_id', $jobId)
                ->where('status', 'draft')
                ->first();
        } else {
            // Get the most recent draft without job_posting_id
            $draftApplication = ApplicationForm::with('experiences')
                ->where('citizenship_number', $candidate->citizenship_number)
                ->whereNull('job_posting_id')
                ->where('status', 'draft')
                ->latest()
                ->first();
        }

        if ($draftApplication) {
            $draftApplication->setRelation(
                'experiences',
                $draftApplication->experiences ?? collect()
            );
        }

         $profileExperiences = ApplicationExperience::where('candidate_id', $candidate->id)
            ->whereNull('application_form_id')
            ->orderBy('exp_number')
            ->get()
            ->keyBy('exp_number');

       
    // Extract individual variables for the blade
    $exp1  = $profileExperiences[1]  ?? null;
    $exp2  = $profileExperiences[2]  ?? null;
    $exp3  = $profileExperiences[3]  ?? null;
    $exp4  = $profileExperiences[4]  ?? null;
    $exp5  = $profileExperiences[5]  ?? null;
    $exp6  = $profileExperiences[6]  ?? null;
    $exp7  = $profileExperiences[7]  ?? null;
    $exp8  = $profileExperiences[8]  ?? null;
    $exp9  = $profileExperiences[9]  ?? null;
    $exp10 = $profileExperiences[10] ?? null;

    return view('candidate.applications.create', compact(
        'job', 'candidate', 'draftApplication', 'groupJobs',
        'exp1', 'exp2', 'exp3', 'exp4', 'exp5',
        'exp6', 'exp7', 'exp8', 'exp9', 'exp10'
    ));
    }

    public function saveDraft(Request $request)
    {
        $candidate = Auth::guard('candidate')->user();

        if (!$candidate) {
            return response()->json(['success' => false, 'message' => 'Not authenticated'], 401);
        }

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
            // Defensive: force-remove all file fields in case except() left UploadedFile objects
            foreach (array_keys($this->fileFields) as $ff) {
                unset($data[$ff]);
            }

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
            $data = array_filter($data, function ($value) {
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

            // Keep candidate profile birth date in sync
            $this->syncBirthDateToProfile(
                $candidate->id,
                $request->birth_date_bs,
                $request->birth_date_ad
            );

            // Save experience rows
            $this->saveExperiences($request, $draft);

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
        $candidate = Auth::guard('candidate')->user();

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
        } else {
            // Create new application
            $application = ApplicationForm::create($data);
        }

        $this->saveExperiences($request, $application);

        // Keep candidate profile birth date in sync
        $this->syncBirthDateToProfile(
            $candidate->id,
            $request->birth_date_bs,
            $request->birth_date_ad
        );

        return redirect()->route('candidate.applications.index')
            ->with('success', 'Application submitted successfully!');
    }

    /**
     * Display the specified application
     */
    public function show(ApplicationForm $applicationform)
    {
        $candidate = Auth::guard('candidate')->user();

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
        $candidate = Auth::guard('candidate')->user();

        if ($applicationform->citizenship_number !== $candidate->citizenship_number) {
            return redirect()->route('candidate.applications.index')
                ->withErrors(['error' => 'Unauthorized access']);
        }

        // Block editing after payment/submission
        if (!in_array($applicationform->status, ['draft', 'edit', 'edited'])) {
            return redirect()->route('candidate.applications.index')
                ->with('error', 'This application has already been submitted and cannot be edited.');
        }

        $applicationform->load('experiences');

        $payment = \App\Models\Payment::where('draft_id', $applicationform->id)
            ->where('status', 'paid')
            ->latest()
            ->first();

        $job = null;
        $groupJobs = collect();
        if ($applicationform->job_posting_id) {
            $job = JobPosting::find($applicationform->job_posting_id);
            if ($job) {
                $groupJobs = JobPosting::where('status', 'active')
                    ->where(function ($q) {
                        $q->where('deadline', '>=', now())
                            ->orWhere(function ($inner) {
                                $inner->whereNotNull('double_dastur_date')
                                    ->where('double_dastur_date', '>=', now());
                            });
                    })
                    ->where('position', $job->position)
                    ->where('level', $job->level)
                    ->where('service_group', $job->service_group)
                    ->orderBy('advertisement_no', 'asc')
                    ->get();
                if ($groupJobs->isEmpty()) {
                    $groupJobs = collect([$job]);
                }
            }
        }

        return view('candidate.applications.edit', compact('applicationform', 'candidate', 'payment', 'job', 'groupJobs'));
    }

    /**
     * Update the specified application
     */
    public function update(Request $request, ApplicationForm $applicationform)
    {
        $candidate = Auth::guard('candidate')->user();

        if ($applicationform->citizenship_number !== $candidate->citizenship_number) {
            return redirect()->route('candidate.applications.index')
                ->withErrors(['error' => 'Unauthorized access']);
        }

        $validated = $request->validate(
            $this->validationRules(false),
            $this->validationMessages()
        );

        $data = $request->except(array_merge(array_keys($this->fileFields), ['status']));
        // Defensive: force-remove all file fields in case except() left UploadedFile objects
        foreach (array_keys($this->fileFields) as $ff) {
            unset($data[$ff]);
        }

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
        $this->saveExperiences($request, $applicationform);

        // Keep candidate profile birth date in sync
        $this->syncBirthDateToProfile(
            $candidate->id,
            $request->birth_date_bs,
            $request->birth_date_ad
        );

        return redirect()->route('candidate.applications.index')
            ->with('success', 'Application updated successfully!');
    }

    /**
     * Remove the specified application
     */
    public function destroy(ApplicationForm $applicationform)
    {
        $candidate = Auth::guard('candidate')->user();

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
     * Alias for checkEligibility — used by the vacancies route
     */
    public function checkEligibilityAjax(Request $request, $vacancyId)
    {
        return $this->checkEligibility($request, $vacancyId);
    }

    /**
     * Check eligibility for a job
     */
    public function checkEligibility(Request $request, $jobId)
    {
        $candidate = Auth::guard('candidate')->user();

        if (!$candidate) {
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
            'age' => 'required|string|min:18|max:40',
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
            'father_qualification' => 'nullable|string',
            'mother_qualification' => 'nullable|string',
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
            'alternate_phone_number' => 'nullable|digits:10',
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
                Log::warning("Invalid file upload for field: $field", ['error' => $file->getError()]);
                continue;
            }

            // Only delete old file if NOT a draft AND file exists
            if (!$isDraft && $model && $model->$field && Storage::disk('public')->exists($model->$field)) {
                Storage::disk('public')->delete($model->$field);
                Log::info("Deleted old file", ['field' => $field, 'path' => $model->$field]);
            }

            // Upload new file
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

            // Ensure the target directory exists
            Storage::disk('public')->makeDirectory($folder);

            $path = $file->storeAs($folder, $filename, 'public');

            if (!$path) {
                Log::error("storeAs failed — file not saved", [
                    'field' => $field,
                    'folder' => $folder,
                    'filename' => $filename,
                ]);
                // Preserve existing value rather than storing a bad path
                if ($model && $model->$field) {
                    $data[$field] = $model->$field;
                }
                continue;
            }

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

    /**
     * Sync birth date entered on the application form back to the candidate profile.
     * Keeps date_of_birth_bs and birth_date_ad consistent across the system.
     */
    private function syncBirthDateToProfile(int $candidateId, ?string $birthDateBs, ?string $birthDateAd): void
    {
        if (empty($birthDateBs)) return;

        DB::table('candidate_registration')
            ->where('id', $candidateId)
            ->update([
                'birth_date_bs' => $birthDateBs,
                'birth_date_ad'    => $birthDateAd ?: null,
            ]);
    }

    private function saveExperiences(Request $request, ApplicationForm $application): void
    {
        $hasAnyData = false;
        for ($i = 1; $i <= 10; $i++) {
            if (
                !empty($request->input("exp{$i}_organization")) ||
                !empty($request->input("exp{$i}_position")) ||
                !empty($request->input("exp{$i}_start_date_bs")) ||
                !empty($request->input("exp{$i}_years"))
            ) {
                $hasAnyData = true;
                break;
            }
        }

        if (!$hasAnyData) {
            Log::info('saveExperiences: no data, preserving existing records', [
                'application_id' => $application->id,
            ]);
            return;
        }

        // Snapshot existing documents keyed by exp_number before deleting
        $existingDocs = \App\Models\ApplicationExperience::where('application_form_id', $application->id)
            ->pluck('document', 'exp_number')
            ->toArray();

        \App\Models\ApplicationExperience::where('application_form_id', $application->id)->delete();

        for ($i = 1; $i <= 10; $i++) {
            $org      = $request->input("exp{$i}_organization");
            $position = $request->input("exp{$i}_position");
            $startBs  = $request->input("exp{$i}_start_date_bs");
            $startAd  = $request->input("exp{$i}_start_date");
            $endBs    = $request->input("exp{$i}_end_date_bs");
            $endAd    = $request->input("exp{$i}_end_date");
            $years    = $request->input("exp{$i}_years");

            if (empty($org) && empty($position) && empty($startBs) && empty($years)) {
                continue;
            }

            $expData = [
                'application_form_id' => $application->id,
                'exp_number'          => $i,
                'organization'        => $org,
                'position'            => $position,
                'start_date_bs'       => $startBs,
                'start_date'          => $startAd ?: null,
                'end_date_bs'         => $endBs,
                'end_date'            => $endAd ?: null,
                'years'               => $years ?: null,
            ];

            $fileField = "exp{$i}_document";
            if ($request->hasFile($fileField) && $request->file($fileField)->isValid()) {
                $file     = $request->file($fileField);
                $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $path     = $file->storeAs('experience-documents', $filename, 'public');
                $expData['document'] = $path;
            } elseif (!empty($existingDocs[$i])) {
                $expData['document'] = $existingDocs[$i];
            }

            \App\Models\ApplicationExperience::create($expData);

            Log::info('Experience saved', [
                'application_id' => $application->id,
                'exp_number'     => $i,
                'organization'   => $org,
            ]);
        }
    }
}
