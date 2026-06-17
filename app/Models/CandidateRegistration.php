<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Casts\Attribute;

class CandidateRegistration extends Authenticatable
{
    use Notifiable;

    protected $table = 'candidate_registration';

    protected $fillable = [
        // ── Account ───────────────────────────────────────────────────────
        'email', 'password', 'phone', 'alternate_phone_number',

        // ── Personal ──────────────────────────────────────────────────────
        'name_english', 'name_nepali', 'gender',
        'birth_date_ad', 'birth_date_bs', 'date_of_birth_bs',
        'age', 'marital_status', 'blood_group', 'nationality', 'mother_tongue',

        // ── Citizenship ───────────────────────────────────────────────────
        'citizenship_number',
        'citizenship_issue_date_bs', 'citizenship_issue_date_ad',
        'citizenship_issue_district',

        // ── Identity / Employment ─────────────────────────────────────────
        'nid', 'noc_employee', 'employee_id',
        'employment_status', 'employment_other',

        // ── Family ────────────────────────────────────────────────────────
        'father_name_english', 'father_name_nepali','parents_occupation',
        'mother_name_english', 'mother_name_nepali',
        'grandfather_name_english', 'grandfather_name_nepali',
        'spouse_name_english', 'spouse_nationality',

        // ── General / Demographic ─────────────────────────────────────────
        'religion', 'religion_other',
        'community', 'community_other',
        'ethnic_group', 'ethnic_group_other',
        'physical_disability', 'disability_other',

        // ── Permanent Address ─────────────────────────────────────────────
        'permanent_province', 'permanent_district', 'permanent_municipality',
        'permanent_ward', 'permanent_tole', 'permanent_house_number',

        // ── Mailing Address ───────────────────────────────────────────────
        'same_as_permanent',
        'mailing_province', 'mailing_district', 'mailing_municipality',
        'mailing_ward', 'mailing_tole', 'mailing_house_number',

        // ── Education ─────────────────────────────────────────────────────
        'education_level', 'field_of_study', 'institution_name', 'university',
        'graduation_year', 'graduation_year_english',
        'transcript', 'character_certificate', 'equivalency_certificate',

        // ── Work Experience ───────────────────────────────────────────────
        'has_work_experience',
        'exp1_organization',  'exp1_position',  'exp1_start_date_bs',  'exp1_start_date',  'exp1_end_date_bs',  'exp1_end_date',  'exp1_years',  'exp1_document',
        'exp2_organization',  'exp2_position',  'exp2_start_date_bs',  'exp2_start_date',  'exp2_end_date_bs',  'exp2_end_date',  'exp2_years',  'exp2_document',
        'exp3_organization',  'exp3_position',  'exp3_start_date_bs',  'exp3_start_date',  'exp3_end_date_bs',  'exp3_end_date',  'exp3_years',  'exp3_document',
        'exp4_organization',  'exp4_position',  'exp4_start_date_bs',  'exp4_start_date',  'exp4_end_date_bs',  'exp4_end_date',  'exp4_years',  'exp4_document',
        'exp5_organization',  'exp5_position',  'exp5_start_date_bs',  'exp5_start_date',  'exp5_end_date_bs',  'exp5_end_date',  'exp5_years',  'exp5_document',
        'exp6_organization',  'exp6_position',  'exp6_start_date_bs',  'exp6_start_date',  'exp6_end_date_bs',  'exp6_end_date',  'exp6_years',  'exp6_document',
        'exp7_organization',  'exp7_position',  'exp7_start_date_bs',  'exp7_start_date',  'exp7_end_date_bs',  'exp7_end_date',  'exp7_years',  'exp7_document',
        'exp8_organization',  'exp8_position',  'exp8_start_date_bs',  'exp8_start_date',  'exp8_end_date_bs',  'exp8_end_date',  'exp8_years',  'exp8_document',
        'exp9_organization',  'exp9_position',  'exp9_start_date_bs',  'exp9_start_date',  'exp9_end_date_bs',  'exp9_end_date',  'exp9_years',  'exp9_document',
        'exp10_organization', 'exp10_position', 'exp10_start_date_bs', 'exp10_start_date', 'exp10_end_date_bs', 'exp10_end_date', 'exp10_years', 'exp10_document',

        // ── Uploaded Documents ────────────────────────────────────────────
        'passport_size_photo', 'signature',
        'citizenship_id_document', 'noc_id_card',
        'disability_certificate', 'ethnic_certificate',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'birth_date_ad'             => 'date',
            'citizenship_issue_date_ad' => 'date',
            'same_as_permanent'         => 'boolean',
            'password'                  => 'hashed',
            'created_at'                => 'datetime',
            'updated_at'                => 'datetime',
        ];
    }

    // ── Relationships ─────────────────────────────────────────────────────

    public function applicationForms()
    {
        return $this->hasMany(ApplicationForm::class, 'citizenship_number', 'citizenship_number');
    }

    public function profileExperiences()
    {
        return $this->hasMany(ApplicationExperience::class, 'candidate_id')
                    ->whereNull('application_form_id')
                    ->orderBy('exp_number');
    }
}