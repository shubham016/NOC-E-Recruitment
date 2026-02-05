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
        'reviewer_id',
        'status',
        'cover_letter',
        'resume_path',
        'additional_documents',
        'application_score',
        'reviewer_notes',
        'reviewed_at',
        'shortlisted_at',
        'rejected_at',
        'rejection_reason',
    ];

    protected $casts = [
        'reviewed_at' => 'datetime',
        'shortlisted_at' => 'datetime',
        'rejected_at' => 'datetime',
        'additional_documents' => 'array',
        'application_score' => 'integer',
    ];

    // Relationships
    public function jobPosting()
    {
        return $this->belongsTo(JobPosting::class, 'job_posting_id');
    }

    public function candidate()
    {
        return $this->belongsTo(Candidate::class, 'candidate_id');
    }

    public function reviewer()
    {
        return $this->belongsTo(Reviewer::class, 'reviewer_id');
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeUnderReview($query)
    {
        return $query->where('status', 'under_review');
    }

    public function scopeShortlisted($query)
    {
        return $query->where('status', 'shortlisted');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    // Helper methods
    public function getStatusBadgeClass()
    {
        return match ($this->status) {
            'pending' => 'bg-warning text-dark',
            'under_review' => 'bg-info text-white',
            'shortlisted' => 'bg-success text-white',
            'rejected' => 'bg-danger text-white',
            default => 'bg-secondary text-white',
        };
    }

    public function getStatusLabel()
    {
        return match ($this->status) {
            'pending' => 'Pending Review',
            'under_review' => 'Under Review',
            'shortlisted' => 'Shortlisted',
            'rejected' => 'Rejected',
            default => ucfirst($this->status),
        };
    }
}