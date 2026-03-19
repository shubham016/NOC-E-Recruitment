<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApplicationForm extends Model
{
    use HasFactory;

    protected $table = 'application_form';

    protected $fillable = [
        'candidate_id',
        'vacancy_id',
        'reviewer_id',
        'status',
        'manual_priority',
        'priority_note',

        // General Information
        'religion',
        'religion_other',
        'community',
        'community_other',
        'ethnic_group',
        'ethnic_group_other',
        'ethnic_certificate',
        'marital_status',
        'employment_status',
        'employment_other',
        'physical_disability',
        'disability_other',
        'disability_certificate',
        'mother_tongue',
        'blood_group',
        'noc_employee',
        'noc_id_card',

        // Personal Information
        'birth_date_ad',
        'birth_date_bs',
        'age',
        'phone',
        'gender',

        // Citizenship Information
        'citizenship_number',
        'citizenship_issue_date_bs',
        'citizenship_issue_date_ad',
        'citizenship_issue_district',
        'citizenship_certificate',

        // Family Information
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
        'nationality',
        'spouse_name_english',
        'spouse_name_nepali',
        'spouse_nationality',

        // Permanent Address
        'permanent_province',
        'permanent_district',
        'permanent_municipality',
        'permanent_ward',
        'permanent_tole',
        'permanent_house_number',

        // Mailing Address
        'same_as_permanent',
        'mailing_province',
        'mailing_district',
        'mailing_municipality',
        'mailing_ward',
        'mailing_tole',
        'mailing_house_number',

        // Personal Information (extended)
        'name_english',
        'name_nepali',
        'email',
        'advertisement_no',
        'applying_position',
        'department',
        'alternate_phone_number',

        // Job Application Specific
        'cover_letter',
        'years_of_experience',
        'relevant_experience',

        // Educational Background
        'education_level',
        'field_of_study',
        'institution_name',
        'graduation_year',

        // Work Experience Details
        'has_work_experience',
        'previous_organization',
        'previous_position',

        // Documents
        'passport_photo',
        'resume',
        'cover_letter_file',
        'citizenship_certificate',
        'educational_certificates',
        'experience_certificates',
        'character_certificate',
        'equivalency_certificate',
        'signature',
        'other_documents',

        // Terms
        'terms_agree',

        // Review fields
        'admin_notes',
        'reviewer_notes',
        'reviewed_at',
        'submitted_at',
        'submitted_at_bs',

        // Admit card fields
        'exam_date',
        'exam_time',
        'exam_venue',
        'roll_number',
        'admit_card_generated',
    ];

    protected $casts = [
        'birth_date_ad' => 'date',
        'citizenship_issue_date_ad' => 'date',
        'reviewed_at' => 'datetime',
        'submitted_at' => 'datetime',
        'same_as_permanent' => 'boolean',
        'admit_card_generated' => 'boolean',
        'terms_agree' => 'boolean',
        'age' => 'integer',
        'years_of_experience' => 'integer',
        'graduation_year' => 'integer',
    ];

    /**
     * Get the candidate that owns the application
     */
    public function candidate()
    {
        return $this->belongsTo(Candidate::class);
    }

    /**
     * Get the vacancy for this application
     */
    public function vacancy()
    {
        return $this->belongsTo(Vacancy::class);
    }

    /**
     * Backward compatibility alias
     * @deprecated Use vacancy() instead
     */
    public function jobPosting()
    {
        return $this->vacancy();
    }

    /**
     * Get the reviewer assigned to this application
     */
    public function reviewer()
    {
        return $this->belongsTo(Reviewer::class, 'reviewer_id');
    }

    /**
     * Get payment for this application
     */
    public function payment()
    {
        return $this->hasOne(Payment::class, 'draft_id');
    }

    /**
     * Get result for this application
     */
    public function result()
    {
        return $this->hasOne(Result::class, 'application_id');
    }

    /**
     * Check if application is a draft
     */
    public function isDraft()
    {
        return $this->status === 'draft';
    }

    /**
     * Check if application can be edited
     */
    public function canEdit()
    {
        return in_array($this->status, ['draft', 'pending', 'edit']);
    }

    /**
     * Check if application can be withdrawn/deleted
     */
    public function canWithdraw()
    {
        return in_array($this->status, ['draft', 'pending']);
    }

    /**
     * Get status badge color
     */
    public function getStatusColorAttribute()
    {
        return match ($this->status) {
            'draft' => 'secondary',
            'pending' => 'warning',
            'assigned' => 'info',
            'approved' => 'success',
            'shortlisted' => 'primary',
            'selected' => 'success',
            'rejected' => 'danger',
            default => 'secondary'
        };
    }

    /**
     * Get status label
     */
    public function getStatusLabelAttribute()
    {
        return ucfirst($this->status);
    }

    /**
     * Get full permanent address
     */
    public function getFullPermanentAddressAttribute()
    {
        $parts = array_filter([
            $this->permanent_tole,
            $this->permanent_ward ? "Ward " . $this->permanent_ward : null,
            $this->permanent_municipality,
            $this->permanent_district,
            $this->permanent_province,
        ]);

        return implode(', ', $parts);
    }

    /**
     * Get full mailing address
     */
    public function getFullMailingAddressAttribute()
    {
        if ($this->same_as_permanent) {
            return $this->full_permanent_address;
        }

        $parts = array_filter([
            $this->mailing_tole,
            $this->mailing_ward ? "Ward " . $this->mailing_ward : null,
            $this->mailing_municipality,
            $this->mailing_district,
            $this->mailing_province,
        ]);

        return implode(', ', $parts);
    }

    /**
     * Check if has all required documents
     */
    public function hasAllDocuments()
    {
        $requiredDocs = [
            'passport_photo',
            'citizenship_certificate',
            'educational_certificates',
            'character_certificate',
            'signature',
        ];

        foreach ($requiredDocs as $doc) {
            if (empty($this->$doc)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Check if admit card can be generated
     */
    public function canGenerateAdmitCard()
    {
        return in_array($this->status, ['approved', 'shortlisted', 'selected'])
            && $this->exam_date
            && $this->exam_venue
            && $this->roll_number;
    }

    /**
     * Scopes
     */
    public function scopeDrafts($query)
    {
        return $query->where('status', 'draft');
    }

    public function scopeSubmitted($query)
    {
        return $query->where('status', '!=', 'draft');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }
}
