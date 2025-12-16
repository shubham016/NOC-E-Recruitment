<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    use HasFactory;

    protected $fillable = [
        'candidate_id',
        'job_posting_id',
        'reviewer_id',

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

        // Job Application Specific
        'cover_letter',
        'years_of_experience',
        'relevant_experience',
        // 'current_salary',
        // 'expected_salary',
        // 'available_from',

        // Documents
        'passport_photo',
        'resume',
        'cover_letter_file',
        'educational_certificates',
        'experience_certificates',
        'other_documents',

        // Application Status
        'status',
        'admin_notes',
        'reviewer_notes',
        'reviewed_at',
        'submitted_at',
    ];

    protected $casts = [
        'birth_date_ad' => 'date',
        'citizenship_issue_date_ad' => 'date',
        // 'available_from' => 'date',
        'reviewed_at' => 'datetime',
        'submitted_at' => 'datetime',
        'same_as_permanent' => 'boolean',
        'age' => 'integer',
        'years_of_experience' => 'integer',
    ];

    /**
     * Get the candidate that owns the application
     */
    public function candidate()
    {
        return $this->belongsTo(Candidate::class);
    }

    /**
     * Get the job posting for this application
     */
    public function jobPosting()
    {
        return $this->belongsTo(JobPosting::class);
    }

    /**
     * Get the reviewer assigned to this application
     */
    public function reviewer()
    {
        return $this->belongsTo(Reviewer::class);
    }

    /**
     * Check if application can be edited
     */
    public function canEdit()
    {
        return in_array($this->status, ['pending']);
    }

    /**
     * Check if application can be withdrawn
     */
    public function canWithdraw()
    {
        return in_array($this->status, ['pending', 'under_review']);
    }

    /**
     * Get status badge color
     */
    public function getStatusColorAttribute()
    {
        return match ($this->status) {
            'pending' => 'warning',
            'under_review' => 'info',
            'shortlisted' => 'success',
            'rejected' => 'danger',
            'withdrawn' => 'secondary',
            default => 'secondary'
        };
    }

    /**
     * Get status label
     */
    public function getStatusLabelAttribute()
    {
        return ucfirst(str_replace('_', ' ', $this->status));
    }

    /**
     * Get full permanent address
     */
    public function getFullPermanentAddressAttribute()
    {
        $parts = array_filter([
            $this->permanent_tole,
            "Ward " . $this->permanent_ward,
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
            "Ward " . $this->mailing_ward,
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
            'resume',
            'citizenship_certificate',
            'educational_certificates',
        ];

        foreach ($requiredDocs as $doc) {
            if (empty($this->$doc)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Get uploaded documents count
     */
    public function getUploadedDocumentsCountAttribute()
    {
        $docs = [
            'passport_photo',
            'resume',
            'cover_letter_file',
            'citizenship_certificate',
            'educational_certificates',
            'experience_certificates',
            'noc_id_card',
            'ethnic_certificate',
            'disability_certificate',
            'other_documents',
        ];

        $count = 0;
        foreach ($docs as $doc) {
            if (!empty($this->$doc)) {
                $count++;
            }
        }

        return $count;
    }
}