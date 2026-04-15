# Laravel 12 Checkbox Validation Best Practices

## Overview
This comprehensive guide covers checkbox validation in Laravel 12, including validation rules, custom rules, conditional validation, and best practices for the recruitment system.

---

## 1. Single Checkbox Validation

### Basic Checkbox Handling
HTML form input:
```html
<input type="checkbox" class="form-check-input" id="terms_agree" name="terms_agree" value="1">
<label class="form-check-label" for="terms_agree">I agree to the terms</label>
```

### Validation Rules

#### Accept Rule (Recommended for Checkboxes)
```php
// In controller or FormRequest
$validated = $request->validate([
    'terms_agree' => 'accepted', // Must be present and equal to "1", "on", "true", or true
]);
```

#### Boolean Rule
```php
$validated = $request->validate([
    'same_as_permanent' => 'nullable|boolean', // Accepts: true, 1, "1", "true", "on"
]);
```

#### String/Boolean Conversion
```php
// In controller (current pattern in recruitment_system)
$validated['same_as_permanent'] = $request->has('same_as_permanent') ? 1 : 0;

// Alternative: Using implicit boolean conversion
$validated['same_as_permanent'] = (bool) $request->input('same_as_permanent', false);
```

---

## 2. Multiple Checkboxes (Checkbox Arrays)

### HTML Form Structure
```html
<!-- Skills checkboxes -->
<div class="form-check">
    <input type="checkbox" class="form-check-input" id="skill_php" name="skills[]" value="php">
    <label class="form-check-label" for="skill_php">PHP</label>
</div>
<div class="form-check">
    <input type="checkbox" class="form-check-input" id="skill_js" name="skills[]" value="javascript">
    <label class="form-check-label" for="skill_js">JavaScript</label>
</div>
<div class="form-check">
    <input type="checkbox" class="form-check-input" id="skill_python" name="skills[]" value="python">
    <label class="form-check-label" for="skill_python">Python</label>
</div>

<!-- Languages checkboxes -->
<div class="form-check">
    <input type="checkbox" class="form-check-input" id="lang_english" name="languages[]" value="english">
    <label class="form-check-label" for="lang_english">English</label>
</div>
<div class="form-check">
    <input type="checkbox" class="form-check-input" id="lang_nepali" name="languages[]" value="nepali">
    <label class="form-check-label" for="lang_nepali">Nepali</label>
</div>
```

### Validation Rules for Checkbox Arrays

#### Basic Array Validation
```php
// All items in array must be strings from allowed list
$validated = $request->validate([
    'skills' => 'array|in:php,javascript,python,java',
    'skills.*' => 'string|in:php,javascript,python,java', // Each item in array
]);
```

#### Array Size Rules
```php
$validated = $request->validate([
    'skills' => 'required|array|min:1|max:5', // At least 1, max 5 selections
    'skills.*' => 'string|in:php,javascript,python,java',
]);
```

#### Distinct/Unique Values
```php
$validated = $request->validate([
    'skills' => 'required|array|distinct', // No duplicate selections
    'skills.*' => 'string|in:php,javascript,python,java',
]);
```

### Processing Checkbox Arrays
```php
// Get array of selected values
$skills = $request->input('skills', []); // ['php', 'javascript']

// Convert to comma-separated string for database storage
$skillsString = implode(',', $skills);

// Or JSON storage
$skillsJson = json_encode($skills);

// Convert to boolean flags
$hasPhp = in_array('php', $skills);
```

---

## 3. Conditional Checkbox Validation

### Required If Another Field Has Value
```php
// Mailing address checkboxes only required if same_as_permanent is NOT checked
$validated = $request->validate([
    'same_as_permanent' => 'nullable|boolean',
    'mailing_address' => 'required_unless:same_as_permanent,1|string',
    // or
    'mailing_address' => 'required_if:same_as_permanent,0|string',
]);
```

### Required If Multiple Conditions
```php
$validated = $request->validate([
    'has_disability' => 'nullable|boolean',
    'disability_type' => 'required_if:has_disability,1|string',
    'disability_certificate' => 'required_if:has_disability,1|file|mimes:pdf,jpg,jpeg,png|max:2048',
    'disability_percentage' => 'required_if:has_disability,1|integer|min:0|max:100',
]);
```

### Multiple Checkboxes with Dependencies
```php
$validated = $request->validate([
    'has_work_experience' => 'required|boolean', // Yes/No checkbox
    'previous_organization' => 'required_if:has_work_experience,1|string|max:255',
    'previous_position' => 'required_if:has_work_experience,1|string|max:255',
    'years_of_experience' => 'required_if:has_work_experience,1|integer|min:0',
]);

// Current pattern in ApplicationFormController (lines 620-622):
'has_work_experience' => 'required|string',
'previous_organization' => 'nullable|string|max:255',
'previous_position' => 'nullable|string|max:255',
```

---

## 4. Custom Validation Rules for Checkboxes

### Laravel 12 Custom Rule Classes

#### Single Validation Class
```php
namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class AtLeastOneCheckboxSelected implements ValidationRule
{
    /**
     * Run the validation rule.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // $value is array of selected checkboxes
        if (empty($value) || (is_array($value) && count($value) === 0)) {
            $fail("At least one {$attribute} must be selected.");
        }
    }
}
```

Usage:
```php
use App\Rules\AtLeastOneCheckboxSelected;

$validated = $request->validate([
    'skills' => ['required', 'array', new AtLeastOneCheckboxSelected()],
    'skills.*' => 'string',
]);
```

#### Multiple Checkboxes Must Have Selection
```php
namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class AtLeastOneSelected implements ValidationRule
{
    protected $fields;

    public function __construct(array $fields)
    {
        $this->fields = $fields;
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $request = app('request');

        foreach ($this->fields as $field) {
            if ($request->has($field)) {
                return; // At least one field selected
            }
        }

        $fail('At least one option must be selected.');
    }
}
```

Usage:
```php
use App\Rules\AtLeastOneSelected;

$validated = $request->validate([
    'document_type' => [
        'required',
        new AtLeastOneSelected([
            'passport', 'citizenship', 'national_id', 'driving_license'
        ])
    ],
]);
```

#### Conditional Rule Class
```php
namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\ValidationRule;

class RequiredIfCheckboxUnchecked implements DataAwareRule, ValidationRule
{
    protected $data = [];
    protected $checkboxField;

    public function __construct($checkboxField)
    {
        $this->checkboxField = $checkboxField;
    }

    public function setData($data): static
    {
        $this->data = $data;
        return $this;
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // If checkbox is checked (value exists or equals 1)
        if (!empty($this->data[$this->checkboxField])) {
            return;
        }

        // If checkbox is NOT checked, field is required
        if (empty($value)) {
            $fail("{$attribute} is required when {$this->checkboxField} is not checked.");
        }
    }
}
```

---

## 5. Form Request Validation Class (Recommended)

### Creating a FormRequest
```bash
php artisan make:request StoreApplicationFormRequest
```

### Complete FormRequest Example
```php
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

        if ($isDraft) {
            return $this->draftValidationRules();
        }

        return $this->submitValidationRules();
    }

    /**
     * Relaxed validation rules for draft saving
     */
    protected function draftValidationRules(): array
    {
        return [
            // Basic checkboxes
            'terms_agree' => 'nullable|boolean',
            'same_as_permanent' => 'nullable|boolean',
            'has_disability' => 'nullable|boolean',
            'has_work_experience' => 'nullable|boolean',

            // Checkbox arrays
            'skills' => 'nullable|array',
            'skills.*' => 'string',
            'languages' => 'nullable|array',
            'languages.*' => 'string',

            // Conditional fields
            'disability_certificate' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ];
    }

    /**
     * Strict validation rules for submission
     */
    protected function submitValidationRules(): array
    {
        return [
            // Single checkboxes - must accept terms
            'terms_agree' => 'accepted',
            'same_as_permanent' => 'nullable|boolean',

            // Conditional single checkbox
            'has_disability' => 'required|boolean',
            'disability_certificate' => 'required_if:has_disability,1|file|mimes:pdf,jpg,jpeg,png|max:2048',

            // Checkbox arrays - at least one required
            'skills' => 'required|array|min:1',
            'skills.*' => 'string|in:php,javascript,python,java,go',

            'languages' => 'required|array|min:1',
            'languages.*' => 'string|in:english,nepali,hindi,mandarin',
        ];
    }

    /**
     * Custom messages for validation errors
     */
    public function messages(): array
    {
        return [
            'terms_agree.accepted' => 'You must agree to the terms and conditions.',
            'skills.min' => 'Please select at least one skill.',
            'skills.*.in' => 'One or more selected skills are invalid.',
            'languages.required' => 'Please select at least one language.',
            'disability_certificate.required_if' => 'Disability certificate is required if you have a disability.',
        ];
    }

    /**
     * Custom attributes for error messages
     */
    public function attributes(): array
    {
        return [
            'terms_agree' => 'terms agreement',
            'skills.*' => 'skill',
            'languages.*' => 'language',
        ];
    }

    /**
     * Prepare the data for validation
     */
    protected function prepareForValidation(): void
    {
        // Convert checkbox to boolean for strict comparison
        $this->merge([
            'same_as_permanent' => (bool) $this->input('same_as_permanent'),
            'has_disability' => (bool) $this->input('has_disability'),
            'has_work_experience' => (bool) $this->input('has_work_experience'),
        ]);
    }
}
```

### Using FormRequest in Controller
```php
namespace App\Http\Controllers\Candidate;

use App\Http\Requests\StoreApplicationFormRequest;

class ApplicationFormController extends Controller
{
    public function store(StoreApplicationFormRequest $request)
    {
        // $request->validated() returns validated data with checkboxes properly converted
        $validated = $request->validated();

        // Access checkbox values safely
        $termsAgreed = $validated['terms_agree']; // boolean
        $skills = $validated['skills']; // array

        // Create application with validated data
        ApplicationForm::create($validated);
    }
}
```

---

## 6. Converting Checkbox Arrays to Storage Formats

### Database Storage Options

#### Option 1: JSON Storage
```php
// Validation
$validated = $request->validate([
    'skills' => 'required|array',
    'skills.*' => 'string',
]);

// Storage
$application->update([
    'skills' => json_encode($validated['skills']), // JSON string in database
]);

// Retrieval with automatic conversion
class ApplicationForm extends Model
{
    protected $casts = [
        'skills' => 'array', // Automatically decode JSON
        'languages' => 'array',
    ];
}

// Usage
$skills = $application->skills; // ['php', 'javascript']
```

#### Option 2: Comma-Separated String
```php
$application->update([
    'skills' => implode(',', $validated['skills']),
]);

// Retrieval
$skills = explode(',', $application->skills);
```

#### Option 3: Enum Storage
```php
use Illuminate\Database\Eloquent\Casts\AsEnumCollection;

class ApplicationForm extends Model
{
    protected $casts = [
        'skills' => AsEnumCollection::class.':'.SkillEnum::class,
    ];
}
```

---

## 7. Blade Template Best Practices

### Checkboxes with Old Values
```blade
<!-- Single checkbox -->
<div class="form-check">
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
        <span class="text-danger d-block mt-1">{{ $message }}</span>
    @enderror
</div>

<!-- Multiple checkboxes (current pattern in recruitment_system) -->
<div class="form-check">
    <input
        type="checkbox"
        class="form-check-input @error('skills.*') is-invalid @enderror"
        id="skill_php"
        name="skills[]"
        value="php"
        {{ in_array('php', old('skills', [])) ? 'checked' : '' }}
    >
    <label class="form-check-label" for="skill_php">PHP</label>
</div>

@error('skills')
    <span class="text-danger d-block mt-1">{{ $message }}</span>
@enderror
```

### Conditional Display Based on Checkbox
```blade
<div class="form-check mb-3">
    <input
        type="checkbox"
        class="form-check-input"
        id="has_disability"
        name="has_disability"
        value="1"
        {{ old('has_disability') ? 'checked' : '' }}
    >
    <label class="form-check-label" for="has_disability">
        I have a disability
    </label>
</div>

<!-- Hidden by default, shown when checkbox is checked -->
<div id="disability_section" style="display: {{ old('has_disability') ? 'block' : 'none' }};">
    <div class="mb-3">
        <label for="disability_type" class="form-label">Disability Type</label>
        <select
            class="form-control @error('disability_type') is-invalid @enderror"
            id="disability_type"
            name="disability_type"
        >
            <option value="">Select</option>
            <option value="physical" {{ old('disability_type') === 'physical' ? 'selected' : '' }}>Physical</option>
            <option value="visual" {{ old('disability_type') === 'visual' ? 'selected' : '' }}>Visual</option>
            <option value="hearing" {{ old('disability_type') === 'hearing' ? 'selected' : '' }}>Hearing</option>
        </select>
        @error('disability_type')
            <span class="text-danger d-block mt-1">{{ $message }}</span>
        @enderror
    </div>
</div>

<script>
// Show/hide disability section based on checkbox
document.getElementById('has_disability').addEventListener('change', function() {
    document.getElementById('disability_section').style.display = this.checked ? 'block' : 'none';
});
</script>
```

---

## 8. Server-Side HTML Rendering with AJAX Validation

### Pre-validation with Laravel Precognition (Laravel 12+)
```blade
<form wire:submit="store">
    @livewire('application-form')
</form>
```

### Using Form Requests with AJAX
```javascript
// Client-side form submission with validation feedback
document.getElementById('applicationForm').addEventListener('submit', async function(e) {
    e.preventDefault();

    const formData = new FormData(this);

    try {
        const response = await fetch('/candidate/applications/store', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
            }
        });

        const data = await response.json();

        if (response.ok) {
            // Success
            window.location.href = data.redirect;
        } else if (response.status === 422) {
            // Validation errors
            displayValidationErrors(data.errors);
        }
    } catch (error) {
        console.error('Error:', error);
    }
});

function displayValidationErrors(errors) {
    Object.keys(errors).forEach(field => {
        const input = document.querySelector(`[name="${field}"]`);
        if (input) {
            input.classList.add('is-invalid');
            const errorDiv = document.querySelector(`#${field}_error`) ||
                           document.createElement('span');
            errorDiv.className = 'text-danger d-block mt-1';
            errorDiv.textContent = errors[field][0];
            if (!document.querySelector(`#${field}_error`)) {
                input.parentElement.appendChild(errorDiv);
            }
        }
    });
}
```

---

## 9. Advanced: Dynamic Checkbox Validation

### Real-time Validation with Livewire (Laravel 12)
```php
namespace App\Livewire;

use Livewire\Component;

class ApplicationForm extends Component
{
    public bool $hasDisability = false;
    public string $disabilityType = '';
    public $disabilityCertificate = null;

    public function updatedHasDisability()
    {
        // React to checkbox change
        if (!$this->hasDisability) {
            $this->disabilityType = '';
            $this->disabilityCertificate = null;
        }
    }

    public function rules()
    {
        return [
            'hasDisability' => 'nullable|boolean',
            'disabilityType' => 'required_if:hasDisability,true|string',
            'disabilityCertificate' => 'required_if:hasDisability,true|file',
        ];
    }

    public function submit()
    {
        $validated = $this->validate();

        // Process validated data
    }

    public function render()
    {
        return view('livewire.application-form');
    }
}
```

---

## 10. Testing Checkbox Validation

### Laravel TestCase Examples
```php
namespace Tests\Feature;

use Tests\TestCase;

class ApplicationFormValidationTest extends TestCase
{
    /** @test */
    public function it_requires_acceptance_of_terms()
    {
        $response = $this->post('/candidate/applications/store', [
            'name' => 'John Doe',
            // Missing 'terms_agree'
        ]);

        $response->assertSessionHasErrors('terms_agree');
    }

    /** @test */
    public function it_accepts_checkbox_as_accepted()
    {
        $response = $this->post('/candidate/applications/store', [
            'name' => 'John Doe',
            'terms_agree' => '1', // or 'on', 'true'
        ]);

        $response->assertSessionDoesntHaveErrors('terms_agree');
    }

    /** @test */
    public function it_requires_at_least_one_skill()
    {
        $response = $this->post('/candidate/applications/store', [
            'name' => 'John Doe',
            'skills' => [], // No skills selected
        ]);

        $response->assertSessionHasErrors('skills');
    }

    /** @test */
    public function it_validates_conditional_disability_fields()
    {
        // When disability is checked, certificate is required
        $response = $this->post('/candidate/applications/store', [
            'name' => 'John Doe',
            'has_disability' => '1',
            // Missing 'disability_certificate'
        ]);

        $response->assertSessionHasErrors('disability_certificate');
    }

    /** @test */
    public function it_accepts_valid_checkbox_array()
    {
        $response = $this->post('/candidate/applications/store', [
            'name' => 'John Doe',
            'skills' => ['php', 'javascript', 'python'],
        ]);

        $response->assertSessionDoesntHaveErrors('skills');
    }
}
```

---

## 11. Summary Table: Checkbox Validation Rules

| Scenario | Rule | Example |
|----------|------|---------|
| Required acceptance | `accepted` | `'terms_agree' => 'accepted'` |
| Optional checkbox | `nullable\|boolean` | `'same_as_permanent' => 'nullable\|boolean'` |
| Checkbox array required | `required\|array\|min:1` | `'skills' => 'required\|array\|min:1'` |
| Checkbox array items | `string\|in:...` | `'skills.*' => 'string\|in:php,js'` |
| Conditional required | `required_if:field,value` | `'cert' => 'required_if:disability,1'` |
| Conditional unless | `required_unless:field,value` | `'cert' => 'required_unless:skip,1'` |
| Custom rule | `new CustomRule()` | `'field' => [new AtLeastOne()]` |

---

## 12. Best Practices Summary

1. **Use FormRequest Classes**: Move validation to dedicated FormRequest classes instead of inline $request->validate()
   - Better organization and reusability
   - Centralized error messages and attributes
   - Easier to test

2. **Use `accepted` for Terms**: For mandatory acceptance checkboxes
   ```php
   'terms_agree' => 'accepted' // Best for mandatory checkboxes
   ```

3. **Use `boolean` for Optional Checkboxes**: For optional yes/no fields
   ```php
   'same_as_permanent' => 'nullable|boolean' // Best for optional
   ```

4. **Validate Array Items**: Always validate individual items in checkbox arrays
   ```php
   'skills' => 'array|min:1',
   'skills.*' => 'string|in:allowed,values'
   ```

5. **Use Custom Rules for Complex Logic**: Create rule classes for business logic
   ```php
   'field' => [new AtLeastOneCheckboxSelected()]
   ```

6. **Implement Conditional Validation**: Use `required_if`, `required_unless`, or custom rules
   ```php
   'disability_cert' => 'required_if:has_disability,1'
   ```

7. **Handle JSON Storage**: Use Eloquent casts for array/JSON fields
   ```php
   protected $casts = ['skills' => 'array'];
   ```

8. **Preserve Form State**: Always use `old()` in Blade templates
   ```blade
   {{ old('same_as_permanent') ? 'checked' : '' }}
   ```

9. **Test Thoroughly**: Write tests for all checkbox scenarios
   - Submission with checkboxes
   - Validation errors
   - Conditional requirements

10. **Use JavaScript for UX**: Show/hide conditional fields with JavaScript
    ```javascript
    document.getElementById('checkbox').addEventListener('change', function() {
        document.getElementById('conditional_field').style.display = this.checked ? 'block' : 'none';
    });
    ```

---

## 13. Recruitment System Application Examples

### Current Implementation (ApplicationFormController)
```php
// Line 114: Single checkbox conversion
$validated['same_as_permanent'] = $request->has('same_as_permanent') ? 1 : 0;

// Line 624: Terms acceptance validation
'terms_agree' => 'accepted',

// RECOMMENDATION: Convert to FormRequest
public function store(StoreApplicationFormRequest $request)
{
    $validated = $request->validated();
    ApplicationForm::create($validated);
}
```

### Recommended Refactoring Locations
1. **ApplicationFormController::store()** - Use StoreApplicationFormRequest
2. **Candidate Application Form Views** - Add old() for checkboxes (line 398, 669)
3. **HR and Admin Forms** - Apply same validation patterns
4. **Reviewer/Approver Forms** - Add checkbox validation for decisions

---

## 14. Laravel 12 Specific Features

### New in Laravel 12
- **Attributes for custom rules**: Define field names for better error messages
- **PrepareForValidation Hook**: Process data before validation
- **Validator after() Method**: Add custom validation logic
- **Livewire Integration**: Real-time validation with checkboxes

### Code Connect: FormRequest
```php
// app/Http/Requests/StoreApplicationFormRequest.php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreApplicationFormRequest extends FormRequest
{
    public function authorize(): bool { return true; }
    public function rules(): array { /* validation */ }
    public function messages(): array { /* messages */ }
    public function attributes(): array { /* custom names */ }
}
```

---

## References
- [Laravel 12 Validation Documentation](https://laravel.com/docs/12/validation)
- [FormRequest API](https://laravel.com/docs/12/validation#form-request-validation)
- [Custom Validation Rules](https://laravel.com/docs/12/validation#custom-validation-rules)
- [Conditional Validation](https://laravel.com/docs/12/validation#conditionally-adding-rules)
