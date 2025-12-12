<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Contracts\Auth\MustVerifyEmail;

class Candidate extends Authenticatable implements MustVerifyEmail
{
    use HasFactory;

    protected $fillable = [
        'first_name',
        'middle_name',
        'last_name',
        'name',
        'username',
        'email',
        'email_verified_at',
        'mobile_number',
        'password',
        'profile_picture',
        'city',
        'state',
        'country',
        'qualification',
        'skills',
        'resume_path',
        'status',
        'notification_settings',
        'privacy_settings',

    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'notification_settings' => 'array',
        'privacy_settings' => 'array',
    ];

    /**
     * Boot method to auto-generate full name
     */
    protected static function boot()
    {
        parent::boot();

        // When creating a new candidate
        static::creating(function ($candidate) {
            $candidate->name = static::generateFullName(
                $candidate->first_name,
                $candidate->middle_name,
                $candidate->last_name
            );
        });

        // When updating a candidate
        static::updating(function ($candidate) {
            // Only regenerate name if name fields changed
            if ($candidate->isDirty(['first_name', 'middle_name', 'last_name'])) {
                $candidate->name = static::generateFullName(
                    $candidate->first_name,
                    $candidate->middle_name,
                    $candidate->last_name
                );
            }
        });
    }

    /**
     * Generate full name from first, middle, and last name
     */
    public static function generateFullName($firstName, $middleName, $lastName)
    {
        $parts = array_filter([$firstName, $middleName, $lastName]);
        return implode(' ', $parts);
    }

    /**
     * Get full name attribute
     */
    public function getFullNameAttribute()
    {
        return $this->name;
    }

    /**
     * Check if email is verified
     */
    public function hasVerifiedEmail()
    {
        return !is_null($this->email_verified_at);
    }

    /**
     * Mark email as verified
     */
    public function markEmailAsVerified()
    {
        return $this->forceFill([
            'email_verified_at' => $this->freshTimestamp(),
        ])->save();
    }

    /**
     * Find candidate by username or email (for login)
     */
    public static function findByUsernameOrEmail($identifier)
    {
        return static::where('username', $identifier)
            ->orWhere('email', $identifier)
            ->first();
    }

    /**
     * Get all applications for this candidate
     */
    public function applications()
    {
        return $this->hasMany(Application::class);
    }

    /**
     * Check if candidate is active
     */
    public function isActive()
    {
        return $this->status === 'active';
    }
}