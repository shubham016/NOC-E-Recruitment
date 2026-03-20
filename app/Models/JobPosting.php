<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobPosting extends Model
{
    use HasFactory;

    protected $table = 'job_postings';

    protected $fillable = [
        'advertisement_no',
        'title',
        'position_level',
        'description',
        'requirements',
        'minimum_qualification',
        'department',
        'service_group',
        'category', // Keeping this - replaces inclusive_type and job_type
        'number_of_posts',
        'location',
        'salary_min',
        'salary_max',
        'deadline',
        'status',
        'posted_by',
        'min_age',
        'max_age',
        'required_education',
    ];

    protected $casts = [
        'deadline' => 'datetime',
        'salary_min' => 'decimal:2',
        'salary_max' => 'decimal:2',
        'number_of_posts' => 'integer',
        'min_age' => 'integer',
        'max_age' => 'integer',
    ];

    public function applications()
    {
        return $this->hasMany(ApplicationForm::class, 'job_posting_id');
    }

    public function postedBy()
    {
        return $this->belongsTo(Admin::class, 'posted_by');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeOpen($query)
    {
        return $query->where('deadline', '>', now());
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
            
            // Check if candidate belongs to any inclusive category
            // Female
            if ($application->gender === 'Female') {
                $isEligibleForInclusive = true;
            }
            
            // Janajati (check ethnic_group or community)
            $janajatiGroups = ['Janajati', 'Adivasi', 'Indigenous'];
            if ($application->ethnic_group && in_array($application->ethnic_group, $janajatiGroups)) {
                $isEligibleForInclusive = true;
            }
            if ($application->community && in_array($application->community, $janajatiGroups)) {
                $isEligibleForInclusive = true;
            }
            
            // Madhesi
            $madhesiGroups = ['Madhesi', 'Terai'];
            if ($application->ethnic_group && in_array($application->ethnic_group, $madhesiGroups)) {
                $isEligibleForInclusive = true;
            }
            if ($application->community && in_array($application->community, $madhesiGroups)) {
                $isEligibleForInclusive = true;
            }
            
            // Dalit
            $dalitGroups = ['Dalit'];
            if ($application->ethnic_group && in_array($application->ethnic_group, $dalitGroups)) {
                $isEligibleForInclusive = true;
            }
            if ($application->community && in_array($application->community, $dalitGroups)) {
                $isEligibleForInclusive = true;
            }
            
            // Disabled
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