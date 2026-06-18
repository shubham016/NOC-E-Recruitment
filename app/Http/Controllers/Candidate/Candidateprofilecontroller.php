<?php

namespace App\Http\Controllers\Candidate;

use App\Http\Controllers\Controller;
use App\Models\CandidateRegistration;
use App\Models\ApplicationExperience;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;


class CandidateProfileController extends Controller
{
    /**
     * Display the candidate profile page.
     */
    public function show()
    {
        $candidate = Auth::guard('candidate')->user();

    $profileExperiences = ApplicationExperience::where('candidate_id', $candidate->id)
        ->whereNull('application_form_id')
        ->orderBy('exp_number')
        ->get();

    return view('candidate.my-profile', compact(
        'candidate',
        'profileExperiences'
    ));
    }

    /**
     * Show the edit profile page.
     */
    public function edit()
    {
        $candidate = Auth::guard('candidate')->user();

    $profileExperiences = ApplicationExperience::where('candidate_id', $candidate->id)
        ->whereNull('application_form_id')
        ->orderBy('exp_number')
        ->get();

    return view('candidate.edit-profile', compact(
        'candidate',
        'profileExperiences'
    ));
    }

    /**
     * Update and finalise candidate profile.
     */
    public function update(Request $request)
    {
        // $candidate = Auth::guard('candidate')->user();
        $candidate = CandidateRegistration::findOrFail(
            Auth::guard('candidate')->id()
        );

        $validated = $this->validateProfile($request, $candidate);
        $validated = $this->handleFileUploads($request, $validated, $candidate);
        $validated = $this->handleExperienceRows($request, $validated, $candidate);

        // Recompute birth_date_ad from BS date
        if (!empty($validated['birth_date_bs'])) {
            $validated['birth_date_ad'] = $this->bsToAD($validated['birth_date_bs']);
        } elseif (!empty($validated['birth_date_bs'])) {
            $validated['birth_date_ad'] = $this->bsToAD($validated['birth_date_bs']);
        }

        // Mirror mailing address from permanent if same_as_permanent is checked
        if (!empty($validated['same_as_permanent'])) {
            $validated = $this->mirrorPermanentAddress($validated);
        }

        // Keep age from existing if not submitted (readonly field)
        if (empty($validated['age'])) {
            $validated['age'] = $candidate->age;
        }
        $candidate->update($validated);

         // ← Save experiences to application_experiences with candidate_id
        $this->saveProfileExperiences($request, $candidate);

        return redirect()
            ->route('candidate.my-profile')
            ->with('success', 'Profile updated successfully.');
    }

    private function saveProfileExperiences(Request $request, CandidateRegistration $candidate): void
{
    // Check if any experience data was submitted
    $hasAnyData = false;
    for ($i = 1; $i <= 10; $i++) {
        if (!empty($request->input("exp{$i}_organization"))) {
            $hasAnyData = true;
            break;
        }
    }

    if (!$hasAnyData) return;

    // Snapshot existing docs before deleting
    $existingDocs = ApplicationExperience::where('candidate_id', $candidate->id)
        ->whereNull('application_form_id')
        ->pluck('document', 'exp_number')
        ->toArray();

    ApplicationExperience::where('candidate_id', $candidate->id)
        ->whereNull('application_form_id')
        ->delete();

    for ($i = 1; $i <= 10; $i++) {
        $org = $request->input("exp{$i}_organization");
        if (empty($org)) continue;

        $expData = [
            'candidate_id'        => $candidate->id,
            'application_form_id' => null,
            'exp_number'          => $i,
            'organization'        => $org,
            'position'            => $request->input("exp{$i}_position"),
            'start_date_bs'       => $request->input("exp{$i}_start_date_bs"),
            'start_date'          => $request->input("exp{$i}_start_date") ?: null,
            'end_date_bs'         => $request->input("exp{$i}_end_date_bs"),
            'end_date'            => $request->input("exp{$i}_end_date") ?: null,
            'years'               => $request->input("exp{$i}_years") ?: null,
        ];

        // Handle document upload
        $fileField = "exp{$i}_document";
        if ($request->hasFile($fileField) && $request->file($fileField)->isValid()) {
            // Delete old doc if exists
            if (!empty($existingDocs[$i]) && Storage::disk('public')->exists($existingDocs[$i])) {
                Storage::disk('public')->delete($existingDocs[$i]);
            }
            $file = $request->file($fileField);
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $expData['document'] = $file->storeAs('profile-experience-documents', $filename, 'public');
        } elseif (!empty($existingDocs[$i])) {
            $expData['document'] = $existingDocs[$i];
        }

        ApplicationExperience::create($expData);
    }
}

    /**
     * Save draft — stores all fields without strict validation.
     */
    // public function saveDraft(Request $request)
    // {
    //     $candidate = Auth::guard('candidate')->user();

    //     $request->validate([
    //         'email' => [
    //             'nullable', 'email', 'max:255',
    //             Rule::unique('candidate_registration', 'email')->ignore($candidate->id),
    //         ],
    //         'citizenship_number' => [
    //             'nullable', 'string', 'max:100',
    //             Rule::unique('candidate_registration', 'citizenship_number')->ignore($candidate->id),
    //         ],
    //         'passport_size_photo'     => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:700'],
    //         'signature'               => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:700'],
    //         'citizenship_id_document' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:700'],
    //         'noc_id_card'             => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:700'],
    //         'disability_certificate'  => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:700'],
    //         'ethnic_certificate'      => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:700'],
    //         'transcript'              => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:700'],
    //         'character_certificate'   => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:700'],
    //         'equivalency_certificate' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:700'],
    //     ]);

    //     $data = $request->except(['_token', '_method']);

    //     $data = $this->handleFileUploads($request, $data, $candidate);
    //     $data = $this->handleExperienceRows($request, $data, $candidate);

    //     if (!empty($data['birth_date_bs'])) {
    //         $data['birth_date_ad'] = $this->bsToAD($data['birth_date_bs']);
    //     } elseif (!empty($data['date_of_birth_bs'])) {
    //         $data['birth_date_ad'] = $this->bsToAD($data['date_of_birth_bs']);
    //     }

    //     if (!empty($data['same_as_permanent'])) {
    //         $data = $this->mirrorPermanentAddress($data);
    //     }

    //     if (empty($data['age'])) {
    //         unset($data['age']); // don't overwrite existing age with empty
    //     }

    //     // Only update columns that exist in $fillable
    //     $fillable = (new CandidateRegistration)->getFillable();
    //     $data = array_intersect_key($data, array_flip($fillable));

    //     $candidate->update($data);

    //     return redirect()
    //         ->route('candidate.edit-profile')
    //         ->with('draft_success', 'Draft saved. You can continue editing anytime.');
    // }

    // ── Validation ────────────────────────────────────────────────────────

    private function validateProfile(Request $request, CandidateRegistration $candidate): array
    {
        return $request->validate([
            // ── Account ───────────────────────────────────────────────────
            'email' => [
                'required',
                'email',
                'max:150',
                Rule::unique('candidate_registration', 'email')->ignore($candidate->id),
            ],
            'phone'                  => ['required', 'string', 'max:20'],
            'alternate_phone_number' => ['nullable', 'string', 'max:20'],

            // ── Personal ──────────────────────────────────────────────────
            'name_english'     => ['required', 'string', 'max:150'],
            'name_nepali'      => ['nullable', 'string', 'max:150'],
            'gender'           => ['required', 'in:Male,Female,Other'],
            'birth_date_bs'    => ['nullable', 'string', 'max:20'],
            'birth_date_ad'    => ['nullable', 'string', 'max:20'],
            'age'              => ['nullable', 'string', 'max:50'],
            'marital_status'   => ['nullable', 'string', 'max:20'],
            'blood_group'      => ['nullable', 'string', 'max:5'],
            'nationality'      => ['nullable', 'string', 'max:50'],
            'mother_tongue'    => ['nullable', 'string', 'max:50'],

            // ── Citizenship ───────────────────────────────────────────────
            'citizenship_number' => [
                'nullable',
                'string',
                'max:50',
                Rule::unique('candidate_registration', 'citizenship_number')->ignore($candidate->id),
            ],
            'citizenship_issue_district' => ['nullable', 'string', 'max:100'],
            'citizenship_issue_date_bs'  => ['nullable', 'string', 'max:20'],
            'citizenship_issue_date_ad'  => ['nullable', 'string', 'max:20'],

            // ── Identity / Employment ─────────────────────────────────────
            'nid'               => ['nullable', 'string', 'max:50'],
            'noc_employee'      => ['nullable', 'string', 'max:5'],
            'employee_id'       => ['nullable', 'string', 'max:50'],
            'employment_status' => ['nullable', 'string', 'max:30'],
            'employment_other'  => ['nullable', 'string', 'max:100'],

            // ── Family ────────────────────────────────────────────────────
            'father_name_english'      => ['nullable', 'string', 'max:100'],
            'parents_occupation'      => ['nullable', 'string', 'max:100'],
            'father_name_nepali'       => ['nullable', 'string', 'max:100'],
            'mother_name_english'      => ['nullable', 'string', 'max:100'],
            'mother_name_nepali'       => ['nullable', 'string', 'max:100'],
            'grandfather_name_english' => ['nullable', 'string', 'max:100'],
            'grandfather_name_nepali'  => ['nullable', 'string', 'max:100'],
            'spouse_name_english'      => ['nullable', 'string', 'max:100'],
            'spouse_name_nepali'       => ['nullable', 'string', 'max:100'],
            'spouse_nationality'       => ['nullable', 'string', 'max:50'],

            // ── Demographic ───────────────────────────────────────────────
            'religion'            => ['nullable', 'string', 'max:30'],
            'religion_other'      => ['nullable', 'string', 'max:100'],
            'community'           => ['nullable', 'string', 'max:30'],
            'community_other'     => ['nullable', 'string', 'max:100'],
            'ethnic_group'        => ['nullable', 'string', 'max:30'],
            'ethnic_group_other'  => ['nullable', 'string', 'max:100'],
            'physical_disability' => ['nullable', 'string', 'max:5'],
            'disability_other'    => ['nullable', 'string', 'max:100'],

            // ── Permanent Address ─────────────────────────────────────────
            'permanent_province'     => ['nullable', 'string', 'max:50'],
            'permanent_district'     => ['nullable', 'string', 'max:100'],
            'permanent_municipality' => ['nullable', 'string', 'max:100'],
            'permanent_ward'         => ['nullable', 'string', 'max:10'],
            'permanent_tole'         => ['nullable', 'string', 'max:100'],
            'permanent_house_number' => ['nullable', 'string', 'max:20'],

            // ── Mailing Address ───────────────────────────────────────────
            'same_as_permanent'    => ['nullable'],
            'mailing_province'     => ['nullable', 'string', 'max:50'],
            'mailing_district'     => ['nullable', 'string', 'max:100'],
            'mailing_municipality' => ['nullable', 'string', 'max:100'],
            'mailing_ward'         => ['nullable', 'string', 'max:10'],
            'mailing_tole'         => ['nullable', 'string', 'max:100'],
            'mailing_house_number' => ['nullable', 'string', 'max:20'],

            // ── Education ─────────────────────────────────────────────────
            'education_level'         => ['nullable', 'string', 'max:50'],
            'field_of_study'          => ['nullable', 'string', 'max:100'],
            'institution_name'        => ['nullable', 'string', 'max:150'],
            'university'              => ['nullable', 'string', 'max:150'],
            'graduation_year'         => ['nullable', 'string', 'max:4'],
            'graduation_year_english' => ['nullable', 'string', 'max:4'],

            // ── Work Experience ───────────────────────────────────────────
            'has_work_experience' => ['nullable', 'string', 'max:5'],

            // exp1–exp10 text fields
            'exp1_organization'  => ['nullable', 'string', 'max:150'],
            'exp1_position'      => ['nullable', 'string', 'max:100'],
            'exp1_start_date_bs' => ['nullable', 'string', 'max:20'],
            'exp1_start_date'    => ['nullable', 'string', 'max:20'],
            'exp1_end_date_bs'   => ['nullable', 'string', 'max:20'],
            'exp1_end_date'      => ['nullable', 'string', 'max:20'],
            'exp1_years'         => ['nullable', 'numeric'],

            'exp2_organization'  => ['nullable', 'string', 'max:150'],
            'exp2_position'      => ['nullable', 'string', 'max:100'],
            'exp2_start_date_bs' => ['nullable', 'string', 'max:20'],
            'exp2_start_date'    => ['nullable', 'string', 'max:20'],
            'exp2_end_date_bs'   => ['nullable', 'string', 'max:20'],
            'exp2_end_date'      => ['nullable', 'string', 'max:20'],
            'exp2_years'         => ['nullable', 'numeric'],

            'exp3_organization'  => ['nullable', 'string', 'max:150'],
            'exp3_position'      => ['nullable', 'string', 'max:100'],
            'exp3_start_date_bs' => ['nullable', 'string', 'max:20'],
            'exp3_start_date'    => ['nullable', 'string', 'max:20'],
            'exp3_end_date_bs'   => ['nullable', 'string', 'max:20'],
            'exp3_end_date'      => ['nullable', 'string', 'max:20'],
            'exp3_years'         => ['nullable', 'numeric'],

            'exp4_organization'  => ['nullable', 'string', 'max:150'],
            'exp4_position'      => ['nullable', 'string', 'max:100'],
            'exp4_start_date_bs' => ['nullable', 'string', 'max:20'],
            'exp4_start_date'    => ['nullable', 'string', 'max:20'],
            'exp4_end_date_bs'   => ['nullable', 'string', 'max:20'],
            'exp4_end_date'      => ['nullable', 'string', 'max:20'],
            'exp4_years'         => ['nullable', 'numeric'],

            'exp5_organization'  => ['nullable', 'string', 'max:150'],
            'exp5_position'      => ['nullable', 'string', 'max:100'],
            'exp5_start_date_bs' => ['nullable', 'string', 'max:20'],
            'exp5_start_date'    => ['nullable', 'string', 'max:20'],
            'exp5_end_date_bs'   => ['nullable', 'string', 'max:20'],
            'exp5_end_date'      => ['nullable', 'string', 'max:20'],
            'exp5_years'         => ['nullable', 'numeric'],

            'exp6_organization'  => ['nullable', 'string', 'max:150'],
            'exp6_position'      => ['nullable', 'string', 'max:100'],
            'exp6_start_date_bs' => ['nullable', 'string', 'max:20'],
            'exp6_start_date'    => ['nullable', 'string', 'max:20'],
            'exp6_end_date_bs'   => ['nullable', 'string', 'max:20'],
            'exp6_end_date'      => ['nullable', 'string', 'max:20'],
            'exp6_years'         => ['nullable', 'numeric'],

            'exp7_organization'  => ['nullable', 'string', 'max:150'],
            'exp7_position'      => ['nullable', 'string', 'max:100'],
            'exp7_start_date_bs' => ['nullable', 'string', 'max:20'],
            'exp7_start_date'    => ['nullable', 'string', 'max:20'],
            'exp7_end_date_bs'   => ['nullable', 'string', 'max:20'],
            'exp7_end_date'      => ['nullable', 'string', 'max:20'],
            'exp7_years'         => ['nullable', 'numeric'],

            'exp8_organization'  => ['nullable', 'string', 'max:150'],
            'exp8_position'      => ['nullable', 'string', 'max:100'],
            'exp8_start_date_bs' => ['nullable', 'string', 'max:20'],
            'exp8_start_date'    => ['nullable', 'string', 'max:20'],
            'exp8_end_date_bs'   => ['nullable', 'string', 'max:20'],
            'exp8_end_date'      => ['nullable', 'string', 'max:20'],
            'exp8_years'         => ['nullable', 'numeric'],

            'exp9_organization'  => ['nullable', 'string', 'max:150'],
            'exp9_position'      => ['nullable', 'string', 'max:100'],
            'exp9_start_date_bs' => ['nullable', 'string', 'max:20'],
            'exp9_start_date'    => ['nullable', 'string', 'max:20'],
            'exp9_end_date_bs'   => ['nullable', 'string', 'max:20'],
            'exp9_end_date'      => ['nullable', 'string', 'max:20'],
            'exp9_years'         => ['nullable', 'numeric'],

            'exp10_organization'  => ['nullable', 'string', 'max:150'],
            'exp10_position'      => ['nullable', 'string', 'max:100'],
            'exp10_start_date_bs' => ['nullable', 'string', 'max:20'],
            'exp10_start_date'    => ['nullable', 'string', 'max:20'],
            'exp10_end_date_bs'   => ['nullable', 'string', 'max:20'],
            'exp10_end_date'      => ['nullable', 'string', 'max:20'],
            'exp10_years'         => ['nullable', 'numeric'],

            // ── File Uploads ──────────────────────────────────────────────
            'passport_size_photo'     => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:700'],
            'signature'               => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:700'],
            'citizenship_id_document' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:700'],
            'noc_id_card'             => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:700'],
            'disability_certificate'  => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:700'],
            'ethnic_certificate'      => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:700'],
            'transcript'              => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:700'],
            'character_certificate'   => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:700'],
            'equivalency_certificate' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:700'],
        ]);

        return $validated;
    }

    // ── File Upload Helper ────────────────────────────────────────────────

    private function handleFileUploads(Request $request, array $data, CandidateRegistration $candidate): array
    {
        $fileFields = [
            'ethnic_certificate'       => 'ethnic-certificates',
            'noc_id_card'              => 'noc-id-cards',
            'disability_certificate'   => 'disability-certificates',
            'citizenship_id_document'  => 'citizenship-documents',
            'passport_size_photo'      => 'passport-photos',
            'signature'                => 'signatures',
            'transcript'               => 'transcripts',
            'character_certificate'    => 'character-certificates',
            'equivalency_certificate'  => 'equivalency-certificates',
            'work_experience'          => 'work-experience-documents',
            // 'additional_documents'     => 'additional-documents',
        ];

        foreach ($fileFields as $field => $folder) {
            if ($request->hasFile($field) && $request->file($field)->isValid()) {
                // Delete old file if exists
                if (!empty($candidate->$field) && Storage::disk('public')->exists($candidate->$field)) {
                    Storage::disk('public')->delete($candidate->$field);
                }
                $data[$field] = $request->file($field)->store($folder, 'public');
            } else {
                // Keep existing — don't overwrite with null
                unset($data[$field]);
            }
        }

        return $data;
    }

    // ── Experience Rows Helper ────────────────────────────────────────────

    private function handleExperienceRows(Request $request, array $data, CandidateRegistration $candidate): array
    {
        $hasExp = $request->input('has_work_experience');

        foreach (range(1, 10) as $i) {
            // Clear text fields by default
            $data["exp{$i}_organization"]  = null;
            $data["exp{$i}_position"]      = null;
            $data["exp{$i}_start_date_bs"] = null;
            $data["exp{$i}_start_date"]    = null;
            $data["exp{$i}_end_date_bs"]   = null;
            $data["exp{$i}_end_date"]      = null;
            $data["exp{$i}_years"]         = null;
            $data["exp{$i}_document"]      = $candidate->{"exp{$i}_document"}; // preserve existing doc
        }

        if ($hasExp === 'Yes') {
            foreach (range(1, 10) as $i) {
                $org = $request->input("exp{$i}_organization");
                if (empty($org)) continue;

                $data["exp{$i}_organization"]  = $org;
                $data["exp{$i}_position"]      = $request->input("exp{$i}_position");
                $data["exp{$i}_start_date_bs"] = $request->input("exp{$i}_start_date_bs");
                $data["exp{$i}_start_date"]    = $request->input("exp{$i}_start_date") ?: null;
                $data["exp{$i}_end_date_bs"]   = $request->input("exp{$i}_end_date_bs");
                $data["exp{$i}_end_date"]      = $request->input("exp{$i}_end_date") ?: null;
                $data["exp{$i}_years"]         = $request->input("exp{$i}_years");

                // Experience document upload
                if ($request->hasFile("exp{$i}_document") && $request->file("exp{$i}_document")->isValid()) {
                    $oldDoc = $candidate->{"exp{$i}_document"};
                    if (!empty($oldDoc) && Storage::disk('public')->exists($oldDoc)) {
                        Storage::disk('public')->delete($oldDoc);
                    }
                    $data["exp{$i}_document"] = $request->file("exp{$i}_document")
                        ->store('candidate/documents', 'public');
                }
            }
        } else {
            // Wipe experience docs when no experience
            foreach (range(1, 10) as $i) {
                $oldDoc = $candidate->{"exp{$i}_document"};
                if (!empty($oldDoc) && Storage::disk('public')->exists($oldDoc)) {
                    Storage::disk('public')->delete($oldDoc);
                }
                $data["exp{$i}_document"] = null;
            }
        }

        return $data;
    }

    // ── Address Mirror Helper ─────────────────────────────────────────────

    private function mirrorPermanentAddress(array $data): array
    {
        $data['mailing_province']     = $data['permanent_province']     ?? null;
        $data['mailing_district']     = $data['permanent_district']     ?? null;
        $data['mailing_municipality'] = $data['permanent_municipality'] ?? null;
        $data['mailing_ward']         = $data['permanent_ward']         ?? null;
        $data['mailing_tole']         = $data['permanent_tole']         ?? null;
        $data['mailing_house_number'] = $data['permanent_house_number'] ?? null;

        return $data;
    }

    // ── BS → AD Converter ─────────────────────────────────────────────────

    private function bsToAD(string $bsDate): ?string
    {
        $bsMonthData = [
            1975 => [31, 31, 32, 32, 31, 30, 30, 29, 30, 29, 30, 30],
            1976 => [31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 31],
            1977 => [30, 32, 31, 32, 31, 30, 30, 30, 29, 30, 29, 31],
            1978 => [31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30],
            1979 => [31, 31, 32, 32, 31, 30, 30, 29, 30, 29, 30, 30],
            1980 => [31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 31],
            1981 => [31, 31, 31, 32, 31, 31, 29, 30, 30, 29, 30, 30],
            1982 => [31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30],
            1983 => [31, 31, 32, 32, 31, 30, 30, 29, 30, 29, 30, 30],
            1984 => [31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 31],
            1985 => [31, 31, 31, 32, 31, 31, 29, 30, 30, 29, 30, 30],
            1986 => [31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30],
            1987 => [31, 32, 31, 32, 31, 30, 30, 29, 30, 29, 30, 30],
            1988 => [31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 31],
            1989 => [31, 31, 31, 32, 31, 31, 30, 29, 30, 29, 30, 30],
            1990 => [31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30],
            1991 => [31, 32, 31, 32, 31, 30, 30, 29, 30, 29, 30, 30],
            1992 => [31, 32, 31, 32, 31, 30, 30, 30, 29, 30, 29, 31],
            1993 => [31, 31, 31, 32, 31, 31, 30, 29, 30, 29, 30, 30],
            1994 => [31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30],
            1995 => [31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 30],
            1996 => [31, 32, 31, 32, 31, 30, 30, 30, 29, 30, 29, 31],
            1997 => [31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30],
            1998 => [31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30],
            1999 => [31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 31],
            2000 => [30, 32, 31, 32, 31, 30, 30, 30, 29, 30, 29, 31],
            2001 => [31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30],
            2002 => [31, 31, 32, 32, 31, 30, 30, 29, 30, 29, 30, 30],
            2003 => [31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 31],
            2004 => [30, 32, 31, 32, 31, 30, 30, 30, 29, 30, 29, 31],
            2005 => [31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30],
            2006 => [31, 31, 32, 32, 31, 30, 30, 29, 30, 29, 30, 30],
            2007 => [31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 31],
            2008 => [31, 31, 31, 32, 31, 31, 29, 30, 30, 29, 29, 31],
            2009 => [31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30],
            2010 => [31, 31, 32, 32, 31, 30, 30, 29, 30, 29, 30, 30],
            2011 => [31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 31],
            2012 => [31, 31, 31, 32, 31, 31, 29, 30, 30, 29, 30, 30],
            2013 => [31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30],
            2014 => [31, 31, 32, 32, 31, 30, 30, 29, 30, 29, 30, 30],
            2015 => [31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 31],
            2016 => [31, 31, 31, 32, 31, 31, 29, 30, 30, 29, 30, 30],
            2017 => [31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30],
            2018 => [31, 32, 31, 32, 31, 30, 30, 29, 30, 29, 30, 30],
            2019 => [31, 32, 31, 32, 31, 30, 30, 30, 29, 30, 29, 31],
            2020 => [31, 31, 31, 32, 31, 31, 30, 29, 30, 29, 30, 30],
            2021 => [31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30],
            2022 => [31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 30],
            2023 => [31, 32, 31, 32, 31, 30, 30, 30, 29, 30, 29, 31],
            2024 => [31, 31, 31, 32, 31, 31, 30, 29, 30, 29, 30, 30],
            2025 => [31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30],
            2026 => [31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 31],
            2027 => [30, 32, 31, 32, 31, 30, 30, 30, 29, 30, 29, 31],
            2028 => [31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30],
            2029 => [31, 31, 32, 31, 32, 30, 30, 29, 30, 29, 30, 30],
            2030 => [31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 31],
            2031 => [30, 32, 31, 32, 31, 30, 30, 30, 29, 30, 29, 31],
            2032 => [31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30],
            2033 => [31, 31, 32, 32, 31, 30, 30, 29, 30, 29, 30, 30],
            2034 => [31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 31],
            2035 => [30, 32, 31, 32, 31, 31, 29, 30, 30, 29, 29, 31],
            2036 => [31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30],
            2037 => [31, 31, 32, 32, 31, 30, 30, 29, 30, 29, 30, 30],
            2038 => [31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 31],
            2039 => [31, 31, 31, 32, 31, 31, 29, 30, 30, 29, 30, 30],
            2040 => [31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30],
            2041 => [31, 31, 32, 32, 31, 30, 30, 29, 30, 29, 30, 30],
            2042 => [31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 31],
            2043 => [31, 31, 31, 32, 31, 31, 29, 30, 30, 29, 30, 30],
            2044 => [31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30],
            2045 => [31, 32, 31, 32, 31, 30, 30, 29, 30, 29, 30, 30],
            2046 => [31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 31],
            2047 => [31, 31, 31, 32, 31, 31, 30, 29, 30, 29, 30, 30],
            2048 => [31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30],
            2049 => [31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 30],
            2050 => [31, 32, 31, 32, 31, 30, 30, 30, 29, 30, 29, 31],
            2051 => [31, 31, 31, 32, 31, 31, 30, 29, 30, 29, 30, 30],
            2052 => [31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30],
            2053 => [31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 30],
            2054 => [31, 32, 31, 32, 31, 30, 30, 30, 29, 30, 29, 31],
            2055 => [31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30],
            2056 => [31, 31, 32, 31, 32, 30, 30, 29, 30, 29, 30, 30],
            2057 => [31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 31],
            2058 => [30, 32, 31, 32, 31, 30, 30, 30, 29, 30, 29, 31],
            2059 => [31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30],
            2060 => [31, 31, 32, 32, 31, 30, 30, 29, 30, 29, 30, 30],
            2061 => [31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 31],
            2062 => [30, 32, 31, 32, 31, 31, 29, 30, 29, 30, 29, 31],
            2063 => [31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30],
            2064 => [31, 31, 32, 32, 31, 30, 30, 29, 30, 29, 30, 30],
            2065 => [31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 31],
            2066 => [31, 31, 31, 32, 31, 31, 29, 30, 30, 29, 29, 31],
            2067 => [31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30],
            2068 => [31, 31, 32, 32, 31, 30, 30, 29, 30, 29, 30, 30],
            2069 => [31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 31],
            2070 => [31, 31, 31, 32, 31, 31, 29, 30, 30, 29, 30, 30],
            2071 => [31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30],
            2072 => [31, 32, 31, 32, 31, 30, 30, 29, 30, 29, 30, 30],
            2073 => [31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 31],
            2074 => [31, 31, 31, 32, 31, 31, 30, 29, 30, 29, 30, 30],
            2075 => [31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30],
            2076 => [31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 30],
            2077 => [31, 32, 31, 32, 31, 30, 30, 30, 29, 30, 29, 31],
            2078 => [31, 31, 31, 32, 31, 31, 30, 29, 30, 29, 30, 30],
            2079 => [31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30],
            2080 => [31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 30],
            2081 => [31, 32, 31, 32, 31, 30, 30, 30, 29, 30, 29, 30],
            2082 => [31, 31, 31, 32, 31, 31, 30, 29, 30, 29, 30, 31],
            2083 => [31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30],
            2084 => [31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 31],
            2085 => [30, 32, 31, 32, 31, 30, 30, 30, 29, 30, 29, 31],
            2086 => [31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30],
            2087 => [31, 31, 32, 32, 31, 30, 30, 29, 30, 29, 30, 30],
            2088 => [31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 31],
            2089 => [30, 32, 31, 32, 31, 31, 29, 30, 29, 30, 29, 31],
            2090 => [31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30],
            2091 => [31, 31, 32, 32, 31, 30, 30, 29, 30, 29, 30, 30],
            2092 => [31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 31],
            2093 => [31, 31, 31, 32, 31, 31, 29, 30, 30, 29, 29, 31],
            2094 => [31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30],
            2095 => [31, 31, 32, 32, 31, 30, 30, 29, 30, 29, 30, 30],
            2096 => [31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 31],
            2097 => [30, 32, 31, 32, 31, 31, 29, 30, 30, 29, 29, 31],
            2098 => [31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30],
            2099 => [31, 31, 32, 32, 31, 30, 30, 29, 30, 29, 30, 30],
        ];

        $parts = explode('-', trim($bsDate));
        if (count($parts) !== 3) return null;
        [$y, $m, $d] = array_map('intval', $parts);
        if (!$y || !$m || !$d) return null;

        $totalDays = 0;
        for ($year = 2000; $year < $y; $year++) {
            $totalDays += isset($bsMonthData[$year]) ? array_sum($bsMonthData[$year]) : 365;
        }
        for ($month = 1; $month < $m; $month++) {
            $totalDays += $bsMonthData[$y][$month - 1] ?? 30;
        }
        $totalDays += $d - 1;

        $adRef = new \DateTime('1943-04-14');
        $adRef->modify("+{$totalDays} days");
        return $adRef->format('Y-m-d');
    }
}
