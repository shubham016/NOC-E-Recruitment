<?php

namespace App\Http\Controllers;

use App\Models\RegistrationForm;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class RegistrationFormController extends Controller
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
        $forms = RegistrationForm::latest()->paginate(10);
        return view('registration_forms.index', compact('forms'));
    }

    public function create()
    {
        return view('registration_forms.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate($this->validationRules());

        $data = $request->except(array_keys($this->fileFields));
        $data = array_merge($data, $this->handleFileUploads($request));

        // Copy mailing address if same
        if ($request->boolean('same_as_permanent')) {
            $data = array_merge($data, $this->copyPermanentToMailing($request));
        }

        RegistrationForm::create($data);

        return redirect()->route('registration-forms.index')
            ->with('success', 'Registration submitted successfully!');
    }

    public function show(RegistrationForm $registrationForm)
    {
        return view('registration_forms.show', compact('registrationForm'));
    }

    public function edit(RegistrationForm $registrationForm)
    {
        return view('registration_forms.edit', compact('registrationForm'));
    }

    public function update(Request $request, RegistrationForm $registrationForm)
    {
        $validated = $request->validate($this->validationRules(false)); // false = not required on update

        $data = $request->except(array_keys($this->fileFields));
        $uploadedFiles = $this->handleFileUploads($request, $registrationForm);

        $data = array_merge($data, $uploadedFiles);

        // Copy mailing address
        if ($request->boolean('same_as_permanent')) {
            $data = array_merge($data, $this->copyPermanentToMailing($request));
        }

        $registrationForm->update($data);

        return redirect()->route('registration-forms.index')
            ->with('success', 'Registration updated successfully!');
    }

    public function destroy(RegistrationForm $registrationForm)
    {
        $this->deleteAssociatedFiles($registrationForm);
        $registrationForm->delete();

        return redirect()->route('registration-forms.index')
            ->with('success', 'Registration deleted successfully!');
    }

    

    private function validationRules($isStore = true)
    {
        $rules = [
            'name' => 'required|string|max:255',
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

            // File rules
            'citizenship_id_document' => $isStore ? 'required|file|mimes:jpg,jpeg,png,pdf|max:2048' : 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'resume_cv'               => $isStore ? 'required|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:2048' : 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:2048',
            'passport_size_photo'     => $isStore ? 'required|image|mimes:jpg,jpeg,png,webp|max:2048' : 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',

            'ethnic_certificate'      => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'noc_id_card'             => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'disability_certificate'  => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',

            'educational_certificates'     => 'nullable|array',
            'educational_certificates.*'   => 'file|mimes:pdf,jpg,jpeg,png|max:2048', // 2MB,
        ];

        return $rules;
    }

    private function handleFileUploads(Request $request, ?RegistrationForm $model = null)
    {
        $data = [];

        foreach ($this->fileFields as $field => $folder) {
            if (!$request->hasFile($field)) {
                continue;
            }

            $files = $request->file($field);

            // Handle multiple files (educational_certificates)
            if (is_array($files)) {
                // Delete old files
                if ($model && $model->$field) {
                    $old = is_string($model->$field) ? json_decode($model->$field, true) : ($model->$field ?? []);
                    foreach ($request->file('educational_certificates') as $file) {
                        $path = $file->store('educational_certificates', 'public');
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
                // Single file
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

    private function deleteAssociatedFiles(RegistrationForm $model)
    {
        foreach ($this->fileFields as $field => $folder) {
            if (!$model->$field) continue;

            $paths = is_string($model->$field)
                ? json_decode($model->$field, true)
                : ($model->$field ?? []);

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