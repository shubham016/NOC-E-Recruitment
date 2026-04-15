<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Candidate extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'candidate_registration';

    protected $fillable = [
        'name',
        'gender',
        'noc_employee',
        'date_of_birth_bs',
        'citizenship_number',
        'nid',
        'citizenship_issue_distric',
        'citizenship_issue_date_bs',
        'password',
        'email',
        'email_verified_at',
        'phone',
        'age',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'email_verified_at' => 'datetime',
        ];
    }

    /**
<<<<<<< HEAD
=======
     * Get the candidate's full name (computed from first, middle, last name)
     */
    public function getNameAttribute(): string
    {
        $parts = array_filter([$this->first_name, $this->middle_name, $this->last_name]);
        return implode(' ', $parts);
    }

    /**
>>>>>>> 55e8c2322fd9818955a408f1f667542e5cee9f98
     * Find candidate by email, username, or citizenship number (for login)
     */
    public static function findByCredential($identifier)
    {
        return static::where('email', $identifier)
            ->orWhere('username', $identifier)
            ->orWhere('citizenship_number', $identifier)
            ->first();
    }

    /**
     * Get all applications (old model) for this candidate
     */
    public function applications()
    {
        return $this->hasMany(Application::class);
    }

    /**
     * Get all application forms for this candidate
     */
    public function applicationForms()
    {
        return $this->hasMany(ApplicationForm::class);
    }

    /**
     * Get all results for this candidate
     */
    public function results()
    {
        return $this->hasMany(Result::class);
    }

    /**
     * Check if candidate is active
     */
    public function isActive()
    {
        return $this->status === 'active';
    }
}