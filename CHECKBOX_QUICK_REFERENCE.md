# Laravel 12 Checkbox Validation - Quick Reference

## One-Liner Validation Rules

### Single Checkbox
```php
'checkbox_field' => 'accepted'                    // Must be checked (yes/no required)
'checkbox_field' => 'nullable|boolean'            // Optional yes/no
'checkbox_field' => 'required|boolean'            // Required yes/no
```

### Checkbox Array
```php
'skills' => 'required|array|min:1'                // At least 1 selection
'skills' => 'required|array|min:1|max:5'         // Between 1-5 selections
'skills.*' => 'string|in:php,js,python'          // Validate each item
```

### Conditional
```php
'field' => 'required_if:checkbox,1'               // Required when checkbox checked
'field' => 'required_unless:checkbox,1'           // Required unless checkbox checked
```

---

## HTML Patterns

### Single Checkbox
```html
<input type="checkbox" name="terms_agree" value="1">
```

### Checkbox Array
```html
<input type="checkbox" name="skills[]" value="php">
<input type="checkbox" name="skills[]" value="javascript">
```

### With Label (Bootstrap 5)
```html
<div class="form-check">
    <input type="checkbox" class="form-check-input" id="field" name="field" value="1">
    <label class="form-check-label" for="field">Label Text</label>
</div>
```

---

## FormRequest Boilerplate

```php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MyFormRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'terms_agree' => 'accepted',
            'field' => 'nullable|boolean',
            'skills' => 'required|array|min:1',
            'skills.*' => 'string|in:php,js,python',
        ];
    }

    public function messages(): array
    {
        return [
            'terms_agree.accepted' => 'You must accept terms.',
            'skills.min' => 'Select at least one skill.',
        ];
    }

    public function prepareForValidation(): void
    {
        $this->merge([
            'field' => (bool) $this->input('field'),
        ]);
    }
}
```

---

## Blade Template Patterns

### With Error Handling
```blade
<div class="form-check">
    <input
        type="checkbox"
        class="form-check-input @error('field') is-invalid @enderror"
        name="field"
        value="1"
        {{ old('field') ? 'checked' : '' }}
    >
    <label class="form-check-label" for="field">Label</label>
    @error('field')<span class="text-danger">{{ $message }}</span>@enderror
</div>
```

### Checkbox Array
```blade
@foreach($options as $option)
    <div class="form-check">
        <input
            type="checkbox"
            name="field[]"
            value="{{ $option }}"
            {{ in_array($option, old('field', [])) ? 'checked' : '' }}
        >
        <label>{{ $option }}</label>
    </div>
@endforeach
```

### Conditional Section
```blade
<div id="conditional" style="display: {{ old('checkbox') ? 'block' : 'none' }}">
    <!-- Hidden fields shown when checkbox checked -->
</div>

<script>
    document.getElementById('checkbox').addEventListener('change', (e) => {
        document.getElementById('conditional').style.display = e.target.checked ? 'block' : 'none';
    });
</script>
```

---

## Controller Pattern

### Using FormRequest
```php
public function store(MyFormRequest $request)
{
    $validated = $request->validated();

    // Checkbox values are already properly typed
    $boolean = $validated['field'];      // bool
    $array = $validated['skills'];       // array

    Model::create($validated);
}
```

### Without FormRequest
```php
public function store(Request $request)
{
    $validated = $request->validate([
        'terms_agree' => 'accepted',
        'field' => 'nullable|boolean',
    ]);

    Model::create($validated);
}
```

---

## Model Pattern

### With Casting
```php
class ApplicationForm extends Model
{
    protected $casts = [
        'field' => 'boolean',
        'skills' => 'array',      // JSON ↔ Array
        'date' => 'datetime',
    ];
}

// Usage
$app->field;      // Returns bool (true/false)
$app->skills;     // Returns array ['php', 'js']
```

---

## Testing Patterns

```php
// Required checkbox
$this->post('/form', ['terms_agree' => '1'])
    ->assertSessionDoesntHaveErrors('terms_agree');

$this->post('/form', [])  // Missing checkbox
    ->assertSessionHasErrors('terms_agree');

// Checkbox array
$this->post('/form', ['skills' => ['php', 'js']])
    ->assertSessionDoesntHaveErrors('skills');

$this->post('/form', ['skills' => []])  // Empty array
    ->assertSessionHasErrors('skills');

// Conditional
$this->post('/form', ['has_disability' => '1'])  // Checked
    ->assertSessionHasErrors('disability_cert');  // Certificate required

$this->post('/form', ['has_disability' => '0'])  // Unchecked
    ->assertSessionDoesntHaveErrors('disability_cert');
```

---

## Common Issues & Solutions

| Issue | Solution |
|-------|----------|
| Unchecked checkbox not sent to server | Use `$request->has('field')` or validate with `boolean` rule |
| Form state lost after validation error | Use `old('field')` in Blade template |
| Can't access array checkbox values | Ensure rule: `'field.*' => 'string\|in:...'` |
| Conditional field always required | Use `required_if:checkbox,1` not `required` |
| JSON checkbox array not stored | Add cast: `'field' => 'array'` in Model |
| Generic error messages | Add custom messages in FormRequest |

---

## Rules Reference Table

```
┌──────────────────────┬─────────────────┬────────────────────────┐
│ Validation Rule      │ Checkbox Type   │ When to Use            │
├──────────────────────┼─────────────────┼────────────────────────┤
│ accepted             │ Single          │ Required agreement     │
│ nullable\|boolean    │ Single          │ Optional yes/no        │
│ boolean              │ Single          │ Required yes/no        │
│ array                │ Multiple        │ Array of selections    │
│ array\|min:1         │ Multiple        │ At least one selected  │
│ array\|min:1\|max:5  │ Multiple        │ Between 1-5 selected   │
│ in:a,b,c             │ Array items     │ Validate each item     │
│ required_if:fld,val  │ Conditional     │ Required if other true │
│ required_unless:f,v  │ Conditional     │ Required unless other  │
│ distinct             │ Array           │ No duplicates allowed  │
└──────────────────────┴─────────────────┴────────────────────────┘
```

---

## File Locations - Recruitment System

```
Candidate Portal:
├── Controller: app/Http/Controllers/Candidate/ApplicationFormController.php
├── Request: app/Http/Requests/Candidate/StoreApplicationFormRequest.php
├── View: resources/views/candidate/applications/create.blade.php
└── Model: app/Models/ApplicationForm.php

HR Administrator Portal:
├── Controller: app/Http/Controllers/HRAdministrator/HRApplicationController.php
├── Request: app/Http/Requests/HRAdministrator/ReviewApplicationRequest.php
└── View: resources/views/hr-administrator/applications/

Reviewer Portal:
├── Controller: app/Http/Controllers/Reviewer/ApplicationReviewController.php
├── Request: app/Http/Requests/Reviewer/SubmitReviewRequest.php
└── View: resources/views/reviewer/applications/

Approver Portal:
├── Controller: app/Http/Controllers/Approver/AssignedToMeController.php
├── Request: app/Http/Requests/Approver/SubmitDecisionRequest.php
└── View: resources/views/approver/
```

---

## Key Takeaways

1. **Use `accepted` for mandatory checkboxes** - Best practice for terms/agreements
2. **Use `boolean` for yes/no fields** - Clearer intent than string validation
3. **Always validate array items individually** - `'field.*' => 'string|in:...'`
4. **Use FormRequest classes** - Better organization than inline validation
5. **Always use `old()` in templates** - Preserve form state after errors
6. **Add custom error messages** - Makes user experience better
7. **Test all checkbox scenarios** - Required, unchecked, arrays, conditional
8. **Use casts in models** - Automatic JSON to array conversion

---

## Documentation Files

| File | Purpose |
|------|---------|
| `LARAVEL_12_CHECKBOX_VALIDATION.md` | Comprehensive guide with all features |
| `CHECKBOX_VALIDATION_EXAMPLES.php` | Code examples and implementations |
| `CHECKBOX_MIGRATION_GUIDE.md` | Step-by-step migration from inline validation |
| `CHECKBOX_QUICK_REFERENCE.md` | This file - quick lookup |

---

## Additional Resources

- [Laravel Validation Docs](https://laravel.com/docs/12/validation)
- [FormRequest Class](https://laravel.com/docs/12/validation#form-request-validation)
- [Custom Validation Rules](https://laravel.com/docs/12/validation#custom-validation-rules)
- [Eloquent Casts](https://laravel.com/docs/12/eloquent-mutators#attribute-casting)

