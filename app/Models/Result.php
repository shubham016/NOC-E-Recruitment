<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Result extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     */
    protected $table = 'results';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'candidate_id',
        'application_id',
        'full_name',
        'citizenship_number',
        'roll_number',
        'advertisement_code',
        'advertisement_number',
        'post',
        'quota',
        'marks',
        'class',
        'recommended_service',
        'status',
        'remarks',
        'published_at',
    ];

    /**
     * The attributes that should be cast.
     */
    protected function casts(): array
    {
        return [
            'marks' => 'decimal:2',
            'published_at' => 'datetime',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    /**
     * Relationship: Result belongs to a Candidate
     * Uncomment if you have CandidateRegistration model
     */
    // public function candidate()
    // {
    //     return $this->belongsTo(CandidateRegistration::class, 'candidate_id');
    // }

    /**
     * Relationship: Result belongs to an Application
     * Uncomment if you want to link to application_form
     */
    // public function application()
    // {
    //     return $this->belongsTo(ApplicationForm::class, 'application_id');
    // }

    /**
     * Scope: Get only published results
     */
    public function scopePublished($query)
    {
        return $query->where('status', 'published')
                    ->whereNotNull('marks')
                    ->whereNotNull('published_at');
    }

    /**
     * Scope: Get pending results
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope: Get results for a specific candidate
     */
    public function scopeForCandidate($query, $candidateId)
    {
        return $query->where('candidate_id', $candidateId);
    }

    /**
     * Scope: Get results by advertisement
     */
    public function scopeByAdvertisement($query, $advertisementCode)
    {
        return $query->where('advertisement_code', $advertisementCode);
    }

    /**
     * Accessor: Check if result is published
     */
    protected function isPublished(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->status === 'published' && $this->marks !== null,
        );
    }

    /**
     * Accessor: Get class badge color
     */
    protected function classBadgeColor(): Attribute
    {
        return Attribute::make(
            get: function () {
                return match(strtolower($this->class ?? '')) {
                    'first', '1st' => 'success',
                    'second', '2nd' => 'primary',
                    'third', '3rd' => 'warning',
                    default => 'secondary',
                };
            }
        );
    }

    /**
     * Accessor: Get status badge color
     */
    protected function statusBadgeColor(): Attribute
    {
        return Attribute::make(
            get: function () {
                return match($this->status) {
                    'published' => 'success',
                    'pending' => 'warning',
                    'withheld' => 'danger',
                    default => 'secondary',
                };
            }
        );
    }

    /**
     * Accessor: Formatted published date
     */
    protected function publishedAtFormatted(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->published_at?->format('F d, Y h:i A') ?? 'Not Published',
        );
    }

    /**
     * Accessor: Human readable created at
     */
    protected function createdAtFormatted(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->created_at?->format('F d, Y h:i A'),
        );
    }

    /**
     * Mutator: Auto-publish when marks are set
     */
    protected function marks(): Attribute
    {
        return Attribute::make(
            set: function ($value) {
                // Auto-publish result when marks are assigned
                if ($value !== null && $this->status === 'pending') {
                    $this->status = 'published';
                    $this->published_at = now();
                }
                return $value;
            }
        );
    }
}