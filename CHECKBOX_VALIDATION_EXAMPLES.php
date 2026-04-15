<?php
/**
 * CHECKBOX VALIDATION CODE EXAMPLES
 * Practical implementations for the recruitment system
 * Laravel 12
 */

// ============================================================================
// EXAMPLE 1: FormRequest for Application Form (Recommended Approach)
// ============================================================================
// File: app/Http/Requests/StoreApplicationFormRequest.php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreApplicationFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth('candidate')->check();
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $isDraft = $this->has('save_draft');
        return $isDraft ? $this->draftValidationRules() : $this->submitValidationRules();
    }

    /**
     * Draft validation rules (all optional)
     */
    protected function draftValidationRules(): array
    {
        return [
            // Single checkboxes - optional for drafts
            'same_as_permanent' => 'nullable|boolean',
            'has_disability' => 'nullable|boolean',
            'has_work_experience' => 'nullable|boolean',
            'terms_agree' => 'nullable|boolean',

            // Conditional fields - optional for drafts
            'disability_certificate' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'disability_percentage' => 'nullable|integer|min:0|max:100',

            // Other fields (simplified - add all fields from controller)
            'name_english' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
        ];
    }

    /**
     * Submit validation rules (strict)
     */
    protected function submitValidationRules(): array
    {
        return [
            // Single checkbox - MUST accept terms
            'terms_agree' => 'accepted', // Must be true/1/on

            // Single checkbox - optional but type-checked if present
            'same_as_permanent' => 'nullable|boolean',

            // Conditional checkbox fields
            'has_disability' => 'required|boolean',
            'disability_certificate' => 'required_if:has_disability,1|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'disability_percentage' => 'required_if:has_disability,1|integer|min:0|max:100',

            // Work experience conditional fields
            'has_work_experience' => 'required|boolean',
            'previous_organization' => 'required_if:has_work_experience,1|string|max:255',
            'previous_position' => 'required_if:has_work_experience,1|string|max:255',
            'years_of_experience' => 'required_if:has_work_experience,1|integer|min:0',

            // Checkbox arrays
            'required_skills' => 'required|array|min:1|max:5',
            'required_skills.*' => 'string|in:php,javascript,python,java,go,rust',

            'languages' => 'required|array|min:1',
            'languages.*' => 'string|in:english,nepali,hindi,mandarin',

            // Other required fields
            'name_english' => 'required|string|max:255',
            'email' => 'required|email|max:255',
        ];
    }

    /**
     * Custom error messages
     */
    public function messages(): array
    {
        return [
            'terms_agree.accepted' => 'You must accept the terms and conditions to proceed.',
            'has_disability.required' => 'Please indicate whether you have a disability.',
            'disability_certificate.required_if' => 'Disability certificate is required when you indicate having a disability.',
            'disability_percentage.required_if' => 'Disability percentage is required when you indicate having a disability.',
            'required_skills.min' => 'Please select at least one required skill.',
            'required_skills.max' => 'You can select up to 5 skills.',
            'languages.min' => 'Please select at least one language.',
            'languages.*.in' => 'One or more selected languages are invalid.',
        ];
    }

    /**
     * Custom attribute names for error messages
     */
    public function attributes(): array
    {
        return [
            'same_as_permanent' => 'same as permanent address checkbox',
            'has_disability' => 'disability indicator',
            'disability_certificate' => 'disability certificate file',
            'has_work_experience' => 'work experience indicator',
            'required_skills' => 'required skills',
            'required_skills.*' => 'skill',
            'languages.*' => 'language',
        ];
    }

    /**
     * Prepare data before validation (convert checkboxes to booleans)
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'same_as_permanent' => (bool) $this->input('same_as_permanent'),
            'has_disability' => (bool) $this->input('has_disability'),
            'has_work_experience' => (bool) $this->input('has_work_experience'),
        ]);
    }
}


// ============================================================================
// EXAMPLE 2: Using FormRequest in Controller
// ============================================================================
// File: app/Http/Controllers/Candidate/ApplicationFormController.php

namespace App\Http\Controllers\Candidate;

use App\Http\Requests\StoreApplicationFormRequest;
use App\Models\ApplicationForm;

class ApplicationFormController extends Controller
{
    /**
     * Store application using FormRequest
     */
    public function store(StoreApplicationFormRequest $request)
    {
        $candidate = auth('candidate')->user();

        // $request->validated() returns all validated data
        $validated = $request->validated();

        // No need to manually convert checkboxes - already done in prepareForValidation()
        $same_as_permanent = $validated['same_as_permanent']; // boolean
        $has_disability = $validated['has_disability']; // boolean
        $skills = $validated['required_skills']; // array

        // Prepare data for database
        $data = array_merge($validated, [
            'candidate_id' => $candidate->id,
            'vacancy_id' => $request->input('vacancy_id'),
            'status' => 'pending',
            'submitted_at' => now(),
            // Convert skills array to JSON or CSV as needed
            'skills' => json_encode($skills),
            'languages' => json_encode($validated['languages']),
        ]);

        // Create application
        $application = ApplicationForm::create($data);

        return redirect()
            ->route('candidate.applications.show', $application->id)
            ->with('success', 'Application submitted successfully!');
    }
}


// ============================================================================
// EXAMPLE 3: Custom Validation Rule - At Least One Checkbox
// ============================================================================
// File: app/Rules/AtLeastOneCheckboxSelected.php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class AtLeastOneCheckboxSelected implements ValidationRule
{
    protected $fieldName;

    public function __construct($fieldName = 'options')
    {
        $this->fieldName = $fieldName;
    }

    /**
     * Run the validation rule
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Handle null, empty array, or zero count
        if (empty($value) || (is_array($value) && count($value) === 0)) {
            $fail("At least one {$this->fieldName} must be selected.");
        }
    }
}

// Usage in FormRequest:
/*
public function rules(): array
{
    return [
        'required_skills' => ['required', 'array', new AtLeastOneCheckboxSelected('skill')],
        'required_skills.*' => 'string',
    ];
}
*/


// ============================================================================
// EXAMPLE 4: Custom Rule - Conditional Checkbox Dependency
// ============================================================================
// File: app/Rules/RequiredIfCheckboxChecked.php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\ValidationRule;

class RequiredIfCheckboxChecked implements DataAwareRule, ValidationRule
{
    protected $data = [];
    protected $checkboxField;
    protected $fieldName;

    public function __construct($checkboxField, $fieldName = 'field')
    {
        $this->checkboxField = $checkboxField;
        $this->fieldName = $fieldName;
    }

    /**
     * Set the data being validated
     */
    public function setData($data): static
    {
        $this->data = $data;
        return $this;
    }

    /**
     * Run the validation rule
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // If checkbox field is NOT checked, skip validation
        if (empty($this->data[$this->checkboxField])) {
            return;
        }

        // If checkbox IS checked and field is empty, fail
        if (empty($value)) {
            $fail("{$this->fieldName} is required when the checkbox is checked.");
        }
    }
}

// Usage in FormRequest:
/*
public function rules(): array
{
    return [
        'has_disability' => 'required|boolean',
        'disability_certificate' => [
            'nullable',
            new RequiredIfCheckboxChecked('has_disability', 'Disability certificate'),
        ],
    ];
}
*/


// ============================================================================
// EXAMPLE 5: Database Model with Checkbox Array Casting
// ============================================================================
// File: app/Models/ApplicationForm.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApplicationForm extends Model
{
    protected $fillable = [
        'candidate_id',
        'vacancy_id',
        'same_as_permanent',
        'has_disability',
        'disability_percentage',
        'has_work_experience',
        'required_skills',
        'languages',
        'status',
        'submitted_at',
        // ... other fields
    ];

    /**
     * Automatically cast JSON fields to arrays
     */
    protected $casts = [
        'same_as_permanent' => 'boolean',
        'has_disability' => 'boolean',
        'has_work_experience' => 'boolean',
        'disability_percentage' => 'integer',
        'required_skills' => 'array', // JSON array → PHP array
        'languages' => 'array',       // JSON array → PHP array
        'submitted_at' => 'datetime',
    ];

    /**
     * Get skills as a readable string
     */
    public function getSkillsLabelAttribute(): string
    {
        if (!$this->required_skills) {
            return 'Not specified';
        }
        return implode(', ', $this->required_skills);
    }

    /**
     * Check if candidate has specific skill
     */
    public function hasSkill($skill): bool
    {
        return in_array($skill, $this->required_skills ?? []);
    }
}

// Usage:
/*
$application = ApplicationForm::find(1);
$application->same_as_permanent; // Returns: true/false (boolean)
$application->required_skills;   // Returns: ['php', 'javascript'] (array)
$application->skills_label;      // Returns: "php, javascript" (string)
$application->hasSkill('php');   // Returns: true/false
*/


// ============================================================================
// EXAMPLE 6: Blade Template with Checkboxes and Old Values
// ============================================================================
// File: resources/views/candidate/applications/create.blade.php

/*
<!-- Single checkbox with error handling -->
<div class="form-check mb-3">
    <input
        type="checkbox"
        class="form-check-input @error('same_as_permanent') is-invalid @enderror"
        id="same_as_permanent"
        name="same_as_permanent"
        value="1"
        {{ old('same_as_permanent') ? 'checked' : '' }}
    >
    <label class="form-check-label" for="same_as_permanent">
        Same as permanent address
    </label>
    @error('same_as_permanent')
        <span class="text-danger d-block mt-1 small">{{ $message }}</span>
    @enderror
</div>

<!-- Conditional fields shown based on checkbox -->
<div id="mailing_address_section" style="display: {{ old('same_as_permanent') ? 'none' : 'block' }};">
    <div class="mb-3">
        <label for="mailing_province" class="form-label">Mailing Province</label>
        <input
            type="text"
            class="form-control @error('mailing_province') is-invalid @enderror"
            id="mailing_province"
            name="mailing_province"
            value="{{ old('mailing_province') }}"
        >
        @error('mailing_province')
            <span class="text-danger d-block mt-1 small">{{ $message }}</span>
        @enderror
    </div>
</div>

<!-- Disability checkbox with conditional fields -->
<div class="form-check mb-3">
    <input
        type="checkbox"
        class="form-check-input @error('has_disability') is-invalid @enderror"
        id="has_disability"
        name="has_disability"
        value="1"
        {{ old('has_disability') ? 'checked' : '' }}
    >
    <label class="form-check-label" for="has_disability">
        I have a disability
    </label>
    @error('has_disability')
        <span class="text-danger d-block mt-1 small">{{ $message }}</span>
    @enderror
</div>

<div id="disability_section" style="display: {{ old('has_disability') ? 'block' : 'none' }};">
    <div class="mb-3">
        <label for="disability_certificate" class="form-label">Disability Certificate</label>
        <input
            type="file"
            class="form-control @error('disability_certificate') is-invalid @enderror"
            id="disability_certificate"
            name="disability_certificate"
            accept=".pdf,.jpg,.jpeg,.png"
        >
        @error('disability_certificate')
            <span class="text-danger d-block mt-1 small">{{ $message }}</span>
        @enderror
    </div>

    <div class="mb-3">
        <label for="disability_percentage" class="form-label">Disability Percentage</label>
        <input
            type="number"
            class="form-control @error('disability_percentage') is-invalid @enderror"
            id="disability_percentage"
            name="disability_percentage"
            min="0"
            max="100"
            value="{{ old('disability_percentage') }}"
        >
        @error('disability_percentage')
            <span class="text-danger d-block mt-1 small">{{ $message }}</span>
        @enderror
    </div>
</div>

<!-- Multiple checkboxes (array) -->
<fieldset class="mb-3">
    <legend class="form-label mb-3">Required Skills</legend>
    @foreach(['php', 'javascript', 'python', 'java', 'go'] as $skill)
        <div class="form-check">
            <input
                type="checkbox"
                class="form-check-input @error('required_skills.*') is-invalid @enderror"
                id="skill_{{ $skill }}"
                name="required_skills[]"
                value="{{ $skill }}"
                {{ in_array($skill, old('required_skills', [])) ? 'checked' : '' }}
            >
            <label class="form-check-label" for="skill_{{ $skill }}">
                {{ ucfirst($skill) }}
            </label>
        </div>
    @endforeach
    @error('required_skills')
        <span class="text-danger d-block mt-2 small">{{ $message }}</span>
    @enderror
</fieldset>

<!-- Terms agreement checkbox -->
<div class="form-check mb-3">
    <input
        type="checkbox"
        class="form-check-input @error('terms_agree') is-invalid @enderror"
        id="terms_agree"
        name="terms_agree"
        value="1"
        required
    >
    <label class="form-check-label" for="terms_agree">
        I agree to the terms and conditions
    </label>
    @error('terms_agree')
        <span class="text-danger d-block mt-1 small">{{ $message }}</span>
    @enderror
</div>

<script>
// Toggle visibility of conditional sections
document.getElementById('same_as_permanent').addEventListener('change', function() {
    document.getElementById('mailing_address_section').style.display = this.checked ? 'none' : 'block';
});

document.getElementById('has_disability').addEventListener('change', function() {
    document.getElementById('disability_section').style.display = this.checked ? 'block' : 'none';
});
</script>
*/


// ============================================================================
// EXAMPLE 7: Testing Checkbox Validation
// ============================================================================
// File: tests/Feature/ApplicationFormValidationTest.php

namespace Tests\Feature;

use App\Models\Candidate;
use Tests\TestCase;

class ApplicationFormValidationTest extends TestCase
{
    protected $candidate;

    protected function setUp(): void
    {
        parent::setUp();
        $this->candidate = Candidate::factory()->create();
    }

    /** @test */
    public function it_requires_terms_acceptance()
    {
        $response = $this->actingAs($this->candidate, 'candidate')
            ->post(route('candidate.applications.store'), [
                'name_english' => 'John Doe',
                'vacancy_id' => 1,
                // Missing 'terms_agree'
            ]);

        $response->assertSessionHasErrors('terms_agree');
    }

    /** @test */
    public function it_accepts_terms_checkbox_with_value_1()
    {
        $response = $this->actingAs($this->candidate, 'candidate')
            ->post(route('candidate.applications.store'), [
                'name_english' => 'John Doe',
                'terms_agree' => '1', // Explicitly set checkbox
                'vacancy_id' => 1,
            ]);

        $response->assertSessionDoesntHaveErrors('terms_agree');
    }

    /** @test */
    public function it_validates_conditional_disability_fields()
    {
        // When disability is checked, certificate is required
        $response = $this->actingAs($this->candidate, 'candidate')
            ->post(route('candidate.applications.store'), [
                'name_english' => 'John Doe',
                'has_disability' => '1',
                // Missing 'disability_certificate'
                'terms_agree' => '1',
                'vacancy_id' => 1,
            ]);

        $response->assertSessionHasErrors('disability_certificate');
    }

    /** @test */
    public function it_skips_conditional_fields_when_checkbox_unchecked()
    {
        // When disability is NOT checked, certificate is not required
        $response = $this->actingAs($this->candidate, 'candidate')
            ->post(route('candidate.applications.store'), [
                'name_english' => 'John Doe',
                'has_disability' => '0',
                // No disability_certificate provided
                'terms_agree' => '1',
                'vacancy_id' => 1,
            ]);

        $response->assertSessionDoesntHaveErrors('disability_certificate');
    }

    /** @test */
    public function it_requires_at_least_one_skill_selected()
    {
        $response = $this->actingAs($this->candidate, 'candidate')
            ->post(route('candidate.applications.store'), [
                'name_english' => 'John Doe',
                'required_skills' => [], // No skills selected
                'terms_agree' => '1',
                'vacancy_id' => 1,
            ]);

        $response->assertSessionHasErrors('required_skills');
    }

    /** @test */
    public function it_accepts_multiple_checkbox_selections()
    {
        $response = $this->actingAs($this->candidate, 'candidate')
            ->post(route('candidate.applications.store'), [
                'name_english' => 'John Doe',
                'required_skills' => ['php', 'javascript', 'python'],
                'terms_agree' => '1',
                'vacancy_id' => 1,
            ]);

        $response->assertSessionDoesntHaveErrors('required_skills');
    }

    /** @test */
    public function it_enforces_max_checkbox_selections()
    {
        $response = $this->actingAs($this->candidate, 'candidate')
            ->post(route('candidate.applications.store'), [
                'name_english' => 'John Doe',
                'required_skills' => ['php', 'javascript', 'python', 'java', 'go', 'rust'], // 6 skills, max is 5
                'terms_agree' => '1',
                'vacancy_id' => 1,
            ]);

        $response->assertSessionHasErrors('required_skills');
    }
}


// ============================================================================
// EXAMPLE 8: HTML Form with Bootstrap Styling
// ============================================================================

/*
<!-- Bootstrap 5 Checkbox Styling -->
<div class="form-check form-switch mb-3">
    <input
        class="form-check-input"
        type="checkbox"
        id="same_as_permanent"
        name="same_as_permanent"
        value="1"
        {{ old('same_as_permanent') ? 'checked' : '' }}
    >
    <label class="form-check-label" for="same_as_permanent">
        Use same address as permanent
    </label>
</div>

<!-- Multiple checkboxes with Bootstrap grid -->
<fieldset class="mb-3">
    <legend class="form-label mb-3">Select Required Skills</legend>
    <div class="row">
        @foreach(['php', 'javascript', 'python', 'java', 'go'] as $skill)
            <div class="col-md-6 col-lg-4 mb-2">
                <div class="form-check">
                    <input
                        type="checkbox"
                        class="form-check-input"
                        id="skill_{{ $skill }}"
                        name="required_skills[]"
                        value="{{ $skill }}"
                        {{ in_array($skill, old('required_skills', [])) ? 'checked' : '' }}
                    >
                    <label class="form-check-label" for="skill_{{ $skill }}">
                        {{ ucfirst($skill) }}
                    </label>
                </div>
            </div>
        @endforeach
    </div>
</fieldset>

<!-- Checkbox with inline label (gold theme) -->
<div class="form-check mb-3">
    <input
        type="checkbox"
        class="form-check-input"
        id="accept_terms"
        name="terms_agree"
        value="1"
        required
        style="accent-color: #c9a84c;"
    >
    <label class="form-check-label text-muted" for="accept_terms">
        I accept the <a href="#" class="text-decoration-none" style="color: #c9a84c;">terms and conditions</a>
    </label>
</div>
*/

?>
