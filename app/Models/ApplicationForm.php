<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class ApplicationForm extends Model
{
    use HasFactory;

    // Your actual table name (singular as per your DB)
    protected $table = 'application_form';

    // Allow mass assignment for all these fields
    protected $fillable = [
        // Personal Info
        'name',
        'birth_date_ad',
        'birth_date_bs',
        'age',
        'phone',
        'gender',
        'marital_status',
        'blood_group',
        'nationality',

        // Citizenship
        'citizenship_number',
        'citizenship_issue_date_ad',
        'citizenship_issue_date_bs',
        'citizenship_issue_district',

        // Family
        'father_name_english',
        'father_name_nepali',
        'father_qualification',
        'mother_name_english',
        'mother_name_nepali',
        'mother_qualification',
        'parent_occupation',
        'parent_occupation_other',
        'grandfather_name_english',
        'grandfather_name_nepali',
        'spouse_name_english',
        'spouse_name_nepali',
        'spouse_nationality',

        // General Info
        'religion',
        'religion_other',
        'community',
        'community_other',
        'ethnic_group',
        'ethnic_group_other',
        'mother_tongue',
        'employment_status',
        'employment_other',
        'physical_disability',
        'disability_other',
        'noc_employee',

        // Address
        'permanent_province',
        'permanent_district',
        'permanent_municipality',
        'permanent_ward',
        'permanent_tole',
        'permanent_house_number',

        'same_as_permanent',
        'mailing_province',
        'mailing_district',
        'mailing_municipality',
        'mailing_ward',
        'mailing_tole',
        'mailing_house_number',

        // Education & Experience
        'education_level',
        'field_of_study',
        'institution_name',           
        'graduation_year',
        'has_work_experience',
        'years_of_experience',
        'previous_organization',
        'previous_position',

        // File Uploads
        'passport_size_photo',
        'citizenship_id_document',
        'resume_cv',
        'educational_certificates',
        'noc_id_card',
        'ethnic_certificate',
        'disability_certificate',

        // System
        'status',
        'terms_agree',
    ];

    /**
     * Cast attributes to proper types
     */
    protected function casts(): array
    {
        return [
            // Dates → Carbon instances (fixes "format() on string" error)
            'birth_date_ad'             => 'date',
            'citizenship_issue_date_ad' => 'date',
            'created_at'                => 'datetime',
            'updated_at'                => 'datetime',

            // Booleans
            'same_as_permanent'         => 'boolean',
            'terms_agree'               => 'boolean',

            // JSON / Array
            'educational_certificates'  => 'array',

            // Optional: cast status as string explicitly
            'status'                    => 'string',
        ];
    }

    /**
     * Accessor: Human readable created at
     */
    protected function createdAtFormatted(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->created_at?->format('F d, Y h:i A'),
        );
    }

    /**
     * Accessor: Human readable updated at
     */
    protected function updatedAtFormatted(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->updated_at?->format('F d, Y h:i A'),
        );
    }

    /**
     * Accessor: Full name (in case you want $registrationForm->full_name)
     */
    protected function fullName(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->name,
        );
    }
}