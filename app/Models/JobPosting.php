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
        'category',
        'inclusive_type',
        'number_of_posts',
        'location',
        'job_type',
        'salary_min',
        'salary_max',
        'deadline',
        'status',
        'posted_by',
        'posted_by_type',
    ];

    protected $casts = [
        'deadline' => 'datetime',
        'salary_min' => 'decimal:2',
        'salary_max' => 'decimal:2',
        'number_of_posts' => 'integer',
    ];

    /**
     * Get all applications for this job posting
     */
    public function applications()
    {
        return $this->hasMany(Application::class, 'job_posting_id');
    }

    /**
     * UPDATED: Polymorphic relationship - can be posted by Admin or HR Administrator
     */
    public function postedBy()
    {
        if ($this->posted_by_type === 'hr_administrator') {
            return $this->belongsTo(HRAdministrator::class, 'posted_by');
        }

        return $this->belongsTo(Admin::class, 'posted_by');
    }

    /**
     * Get the poster (Admin or HR Administrator) - more explicit method
     */
    public function getPosterAttribute()
    {
        if ($this->posted_by_type === 'hr_administrator') {
            return HRAdministrator::find($this->posted_by);
        }

        return Admin::find($this->posted_by);
    }

    /**
     * Check if job was posted by admin
     */
    public function isPostedByAdmin()
    {
        return $this->posted_by_type === 'admin';
    }

    /**
     * Check if job was posted by HR administrator
     */
    public function isPostedByHRAdmin()
    {
        return $this->posted_by_type === 'hr_administrator';
    }

    /**
     * Scopes
     */
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
}