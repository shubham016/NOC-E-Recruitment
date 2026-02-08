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
        'resume_cv'                => 'resumes',
        'educational_certificates' => 'educational-certificates',
        'passport_size_photo'      => 'passport-photos',
        'signature'  => 'signature',
    ];

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

    /**
     * Auto-save draft via AJAX
     */
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
                'job_posting_id' => $request->job_posting_id
            ]);

            // Get all data except files and tokens
            $data = $request->except([
                '_token',
                '_method',
                ...array_keys($this->fileFields)
            ]);

            // Only handle file uploads if files are actually present
            if ($this->hasAnyFiles($request)) {
                $fileData = $this->handleFileUploads($request, null, true);
                $data = array_merge($data, $fileData);
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

            // Remove empty values that might cause issues
            $data = array_filter($data, function($value) {
                return !is_null($value) && $value !== '';
            });

            // Find or create draft
            $draft = null;
            
            // First try to find by draft_id if provided
            if ($request->filled('draft_id')) {
                $draft = ApplicationForm::where('id', $request->draft_id)
                    ->where('citizenship_number', $candidate->citizenship_number)
                    ->where('status', 'draft')
                    ->first();
                    
                Log::info('Found draft by ID', ['draft_id' => $draft ? $draft->id : null]);
            }
            
            // If no draft found and job_posting_id exists, try to find existing draft for this job
            if (!$draft && $request->filled('job_posting_id')) {
                $draft = ApplicationForm::where('citizenship_number', $candidate->citizenship_number)
                    ->where('job_posting_id', $request->job_posting_id)
                    ->where('status', 'draft')
                    ->first();
                    
                Log::info('Found draft by job_posting_id', ['draft_id' => $draft ? $draft->id : null]);
            }
            
            // If still no draft, try to find any draft for this candidate
            if (!$draft) {
                $draft = ApplicationForm::where('citizenship_number', $candidate->citizenship_number)
                    ->where('status', 'draft')
                    ->whereNull('job_posting_id')
                    ->latest()
                    ->first();
                    
                Log::info('Found draft without job_posting_id', ['draft_id' => $draft ? $draft->id : null]);
            }

            if ($draft) {
                $draft->update($data);
                Log::info('Draft updated', ['draft_id' => $draft->id]);
            } else {
                $draft = ApplicationForm::create($data);
                Log::info('Draft created', ['draft_id' => $draft->id]);
            }

            return response()->json([
                'success' => true, 
                'message' => 'Draft saved successfully',
                'draft_id' => $draft->id,
                'saved_at' => now()->format('h:i A')
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

    public function store(Request $request)
    {
        if (!Session::has('candidate_logged_in')) {
            return redirect()->route('candidate.login')
                ->withErrors(['error' => 'Please login first']);
        }

        $candidate = DB::table('candidate_registration')
            ->where('id', Session::get('candidate_id'))
            ->first();

        $validated = $request->validate(
            $this->validationRules(),
            $this->validationMessages()
        );

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

        $data = $request->except(array_keys($this->fileFields));
        
        // Check if updating a draft
        $existingDraft = null;
        if ($request->has('draft_id')) {
            $existingDraft = ApplicationForm::find($request->draft_id);
        }
        
        $data = array_merge($data, $this->handleFileUploads($request, $existingDraft));

        if ($request->boolean('same_as_permanent')) {
            $data = array_merge($data, $this->copyPermanentToMailing($request));
        }

        if ($request->has('job_posting_id')) {
            $data['job_posting_id'] = $request->job_posting_id;
        }

        $data['citizenship_number'] = $candidate->citizenship_number;
        $data['status'] = 'pending'; // Final submission

        if ($existingDraft) {
            // Update the draft to final submission
            $existingDraft->update($data);
        } else {
            // Create new application
            ApplicationForm::create($data);
        }

        return redirect()->route('candidate.applications.index')
            ->with('success', 'Application submitted successfully!');
    }

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
        $uploadedFiles = $this->handleFileUploads($request, $applicationform);

        $data = array_merge($data, $uploadedFiles);

        if ($request->boolean('same_as_permanent')) {
            $data = array_merge($data, $this->copyPermanentToMailing($request));
        }

        $applicationform->update($data);

        return redirect()->route('candidate.applications.index')
            ->with('success', 'Application updated successfully!');
    }

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

    private function validationRules($isStore = true)
    {
        $rules = [
            'name_english' => 'required|string|max:255',
            'name_nepali' => 'required|string|max:255',
            'birth_date_ad' => 'required|date',
            'age' => 'required|integer|min:18|max:40',
            'phone' => 'required|string',
            'email' => 'required|email',
            'gender' => 'required|in:Male,Female,Other',
            'citizenship_number' => 'required|string|max:50',
            'citizenship_issue_district' => 'required|string',
            'permanent_province' => 'required|string',
            'permanent_district' => 'required|string',
            'permanent_municipality' => 'required|string',
            'permanent_ward' => 'required|string|max:50',
            'father_name_english' => 'required|string',
            'mother_name_english' => 'required|string',
            'grandfather_name_english' => 'required|string',
            'nationality' => 'required|string',
            'marital_status' => 'required|string',
            'education_level' => 'nullable|string',

            'same_as_permanent' => 'nullable|boolean',
            'physical_disability' => 'required|in:yes,no',
            'noc_employee' => 'required|in:yes,no',
            'ethnic_group' => 'required|in:Dalit,Janajati,Madhesi,Brahmin/Chhetri,Other',
            'job_posting_id' => 'nullable|exists:job_postings,id',
            'advertisement_no' => 'nullable|string',
            'department' => 'nullable|string',
            'applying_position' => 'nullable|string',
            'alternate_phone_number' => 'nullable|digits:10',

            'citizenship_id_document' => $isStore ? 'required|file|mimes:jpg,jpeg,png,pdf|max:2048' : 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'resume_cv'               => $isStore ? 'required|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:2048' : 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:2048',
            'passport_size_photo'     => $isStore ? 'required|image|mimes:jpg,jpeg,png,webp|max:2048' : 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',

            'educational_certificates'     => 'nullable|array',
            'educational_certificates.*'   => 'file|mimes:pdf,jpg,jpeg,png|max:2048',
        ];

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

        // Conditional validation for Ethnic Certificate (required for Dalit and Janajati)
        if ($isStore) {
            $rules['ethnic_certificate'] = 'required_if:ethnic_group,Dalit,Janajati|nullable|file|mimes:jpg,jpeg,png,pdf|max:2048';
        } else {
            $rules['ethnic_certificate'] = 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048';
        }

        return $rules;
    }

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
        ];
    }

    private function handleFileUploads(Request $request, ?ApplicationForm $model = null, $isDraft = false)
    {
        $data = [];

        foreach ($this->fileFields as $field => $folder) {

            if (!$request->hasFile($field)) continue;

            $files = $request->file($field);

            if (is_array($files)) {

                if ($model && $model->$field) {
                    $old = json_decode($model->$field, true) ?? [];
                    foreach ($old as $path) {
                        Storage::disk('public')->delete($path);
                    }
                }

                $paths = [];
                foreach ($files as $file) {
                    $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                    $path = $file->storeAs($folder, $filename, 'public');
                    $paths[] = $path;
                }

                $data[$field] = json_encode($paths);

            } else {

                if ($model && $model->$field && Storage::disk('public')->exists($model->$field)) {
                    Storage::disk('public')->delete($model->$field);
                }

                $filename = time() . '_' . uniqid() . '.' . $files->getClientOriginalExtension();
                $path = $files->storeAs($folder, $filename, 'public');
                $data[$field] = $path;
            }
        }

        return $data;
    }

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

    private function deleteAssociatedFiles(ApplicationForm $model)
    {
        foreach ($this->fileFields as $field => $folder) {

            if (!$model->$field) continue;

            $paths = json_decode($model->$field, true);

            if (is_array($paths)) {
                foreach ($paths as $path) {
                    Storage::disk('public')->delete($path);
                }
            } else {
                Storage::disk('public')->delete($model->$field);
            }
        }
    }
}