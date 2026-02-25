<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Result extends Model
{
    use HasFactory;

    protected $fillable = [
        'candidate_id',
        'application_id',
        'full_name',
        'citizenship_number',
        'roll_number',
        'marks',
        'status',
        'rank',
        'remarks',
        'published_at',
    ];

    protected $casts = [
        'marks' => 'decimal:2',
        'published_at' => 'datetime',
    ];

    /**
     * Get the candidate
     */
    public function candidate()
    {
        return $this->belongsTo(Candidate::class);
    }

    /**
     * Get the application form
     */
    public function applicationForm()
    {
        return $this->belongsTo(ApplicationForm::class, 'application_id');
    }

    /**
     * Check if result is published
     */
    public function isPublished()
    {
        return !is_null($this->published_at);
    }

    /**
     * Check if candidate passed
     */
    public function hasPassed()
    {
        return $this->status === 'pass';
    }
}
