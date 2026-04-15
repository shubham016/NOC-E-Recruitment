<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobPosting extends Model
{
    use HasFactory;

    protected $table = 'job_postings';

    protected $fillable = [
        'notice_no',
        'advertisement_no',
        'title',
        'position_level',
        'description',
        'requirements',
        'minimum_qualification',
        'department',
        'service_group',
        'category',
        'internal_type',
        'inclusive_type',
        'has_open',
        'has_inclusive',
        'has_internal',
        'has_internal_open',
        'has_internal_inclusive',
        'internal_inclusive_types',
        'open_posts',
        'inclusive_posts',
        'number_of_posts',
        'location',
        'deadline',
        'deadline_bs',
        'double_dastur_date',
        'double_dastur_bs',
        'application_fee',
        'double_dastur_fee',
        'status',
        'posted_by',
        'min_age',
        'max_age',
    ];

    protected $casts = [
        'deadline' => 'datetime',
        'double_dastur_date' => 'datetime',
        'application_fee' => 'float',
        'double_dastur_fee' => 'float',
        'number_of_posts' => 'integer',
        'has_open' => 'boolean',
        'has_inclusive' => 'boolean',
        'has_internal' => 'boolean',
        'has_internal_open' => 'boolean',
        'has_internal_inclusive' => 'boolean',
        'internal_inclusive_types' => 'array',
        'open_posts' => 'integer',
        'inclusive_posts' => 'integer',
        'min_age' => 'integer',
        'max_age' => 'integer',
    ];

    /**
     * Get all application forms for this job posting
     */
    public function applications()
    {
        return $this->hasMany(ApplicationForm::class, 'job_posting_id');
    }

    /**
     * Alias for applications()
     */
    public function applicationForms()
    {
        return $this->applications();
    }

    /**
     * Polymorphic relationship - can be posted by Admin or HR Administrator
     */
    public function postedBy()
    {
        if ($this->posted_by_type === 'hr_administrator') {
            return $this->belongsTo(HRAdministrator::class, 'posted_by');
        }

        return $this->belongsTo(Admin::class, 'posted_by');
    }

    /**
     * Get the poster (Admin or HR Administrator)
     */
    public function getPosterAttribute()
    {
        if ($this->posted_by_type === 'hr_administrator') {
            return HRAdministrator::find($this->posted_by);
        }

        return Admin::find($this->posted_by);
    }

    public function isPostedByAdmin()
    {
        return $this->posted_by_type === 'admin';
    }

    public function isPostedByHRAdmin()
    {
        return $this->posted_by_type === 'hr_administrator';
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeOpen($query)
    {
        return $query->where('deadline', '>', now());
    }

    public function scopePostedByAdmin($query)
    {
        return $query->where('posted_by_type', 'admin');
    }

    public function scopePostedByHRAdmin($query)
    {
        return $query->where('posted_by_type', 'hr_administrator');
    }

    public function scopePostedBy($query, $userType, $userId)
    {
        return $query->where('posted_by_type', $userType)
                    ->where('posted_by', $userId);
    }

    /**
     * Check if a candidate is eligible for this job posting
     */
    public function isEligible($application)
    {
        $errors = [];

        // Check Age Requirements
        if ($this->min_age && $application->age < $this->min_age) {
            $errors[] = "Minimum age requirement is {$this->min_age} years. Your age: {$application->age} years.";
        }

        if ($this->max_age && $application->age > $this->max_age) {
            $errors[] = "Maximum age requirement is {$this->max_age} years. Your age: {$application->age} years.";
        }

        // Check Education Level
        if ($this->minimum_qualification) {
            $educationLevels = [
                'SLC/SEE' => 1,
                'SLC' => 1,
                'SEE' => 1,
                '+2/Intermediate' => 2,
                '+2' => 2,
                'Intermediate' => 2,
                'Bachelor' => 3,
                'Bachelors' => 3,
                'Master' => 4,
                'Masters' => 4,
                'PhD' => 5,
                'Doctorate' => 5,
            ];

            $requiredLevel = $educationLevels[$this->minimum_qualification] ?? 0;
            $candidateLevel = $educationLevels[$application->education_level] ?? 0;

            if ($candidateLevel < $requiredLevel) {
                $errors[] = "Required education: {$this->minimum_qualification}. Your education: {$application->education_level}.";
            }
        }

        // Check Job Category (Open or Inclusive)
        if ($this->category === 'Inclusive') {
            $isEligibleForInclusive = false;

            if ($application->gender === 'Female') {
                $isEligibleForInclusive = true;
            }

            $janajatiGroups = ['Janajati', 'Adivasi', 'Indigenous'];
            if ($application->ethnic_group && in_array($application->ethnic_group, $janajatiGroups)) {
                $isEligibleForInclusive = true;
            }
            if ($application->community && in_array($application->community, $janajatiGroups)) {
                $isEligibleForInclusive = true;
            }

            $madhesiGroups = ['Madhesi', 'Terai'];
            if ($application->ethnic_group && in_array($application->ethnic_group, $madhesiGroups)) {
                $isEligibleForInclusive = true;
            }
            if ($application->community && in_array($application->community, $madhesiGroups)) {
                $isEligibleForInclusive = true;
            }

            $dalitGroups = ['Dalit'];
            if ($application->ethnic_group && in_array($application->ethnic_group, $dalitGroups)) {
                $isEligibleForInclusive = true;
            }
            if ($application->community && in_array($application->community, $dalitGroups)) {
                $isEligibleForInclusive = true;
            }

            if ($application->physical_disability === 'yes' || $application->physical_disability === '1') {
                $isEligibleForInclusive = true;
            }

            if (!$isEligibleForInclusive) {
                $errors[] = "This position is for inclusive categories only (Female, Janajati, Madhesi, Dalit, or Persons with Disabilities).";
            }
        }

        return [
            'eligible' => empty($errors),
            'errors' => $errors
        ];
    }
}
