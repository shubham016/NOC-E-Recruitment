<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    use HasFactory;

    protected $fillable = [
        'job_posting_id',
        'candidate_id',
        'reviewed_by',
        'status',
        'cover_letter',
        'resume_path',
        'additional_documents',
        'reviewer_notes',
        'reviewed_at',
    ];

    protected $casts = [
        'additional_documents' => 'array',
        'reviewed_at' => 'datetime',
    ];

    /**
     * Get the job posting
     */
    public function jobPosting()
    {
        return $this->belongsTo(JobPosting::class, 'job_posting_id');
    }

    /**
     * Get the candidate
     */
    public function candidate()
    {
        return $this->belongsTo(Candidate::class);
    }

    /**
     * Get the reviewer who reviewed this application
     */
    public function reviewer()
    {
        return $this->belongsTo(Reviewer::class, 'reviewed_by');
    }
}