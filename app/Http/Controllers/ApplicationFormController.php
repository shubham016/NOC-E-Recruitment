<?php

namespace App\Http\Controllers;

use App\Models\ApplicationForm;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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
    ];

    public function index()
    {
        $forms = ApplicationForm::latest()->paginate(10);
        return view('candidate.applications.index', compact('forms'));
    }

    public function create()
    {
        return view('candidate.applications.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate($this->validationRules());

        $data = $request->except(array_keys($this->fileFields));
        $data = array_merge($data, $this->handleFileUploads($request));

        if ($request->boolean('same_as_permanent')) {
            $data = array_merge($data, $this->copyPermanentToMailing($request));
        }

        ApplicationForm::create($data);

        return redirect()->route('candidate.applications.index')
            ->with('success', 'Application submitted successfully!');
    }

    public function show(ApplicationForm $applicationform)
    {
        return view('candidate.applications.show', compact('applicationform'));
    }

        public function edit(ApplicationForm $applicationform)
        {
            return view('candidate.applications.edit', compact('applicationform'));
        }



    public function update(Request $request, ApplicationForm $applicationform)
    {
        $validated = $request->validate($this->validationRules(false));

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
        $this->deleteAssociatedFiles($applicationform);
        $applicationform->delete();

        return redirect()->route('candidate.applications.index')
            ->with('success', 'Application deleted successfully!');
    }

    private function validationRules($isStore = true)
    {
        return [
            'name_english' => 'required|string|max:255',
            'name_nepali' => 'required|string|max:255',
            'birth_date_ad' => 'required|date',
            'age' => 'required|integer|min:18|max:100',
            'phone' => 'required|string',
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

            'same_as_permanent' => 'nullable|boolean',
            'physical_disability' => 'nullable|in:yes,no',

            // File validations
            'citizenship_id_document' => $isStore ? 'required|file|mimes:jpg,jpeg,png,pdf|max:2048' : 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'resume_cv'               => $isStore ? 'required|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:2048' : 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:2048',
            'passport_size_photo'     => $isStore ? 'required|image|mimes:jpg,jpeg,png,webp|max:2048' : 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',

            'ethnic_certificate'      => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'noc_id_card'             => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'disability_certificate'  => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',

            'educational_certificates'     => 'nullable|array',
            'educational_certificates.*'   => 'file|mimes:pdf,jpg,jpeg,png|max:2048',
        ];
    }

    private function handleFileUploads(Request $request, ?ApplicationForm $model = null)
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
