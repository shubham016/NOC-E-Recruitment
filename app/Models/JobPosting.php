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
        'inclusive_type',  // Added
        'number_of_posts',
        'location',
        'job_type',
        'salary_min',
        'salary_max',
        'deadline',
        'status',
        'posted_by',
    ];

    protected $casts = [
        'deadline' => 'datetime',
        'salary_min' => 'decimal:2',
        'salary_max' => 'decimal:2',
        'number_of_posts' => 'integer',
    ];

    public function applications()
    {
        return $this->hasMany(Application::class, 'job_posting_id');
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
}