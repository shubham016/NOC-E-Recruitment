<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class HRAdministrator extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'hr_administrators';

    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'photo',
        'status',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    // FIXED: Changed from method to property format for Laravel 12
    protected $casts = [
        'password' => 'hashed',
    ];

    /**
     * Get jobs created by this HR Administrator
     */
    public function jobs()
    {
        return $this->hasMany(Vacancy::class, 'posted_by', 'id');
    }

    public function vacancies()
    {
        return $this->jobs();
    }

    /**
     * Get applications for jobs posted by this HR Administrator
     */
    public function applications()
    {
        return $this->hasManyThrough(
            Application::class,
            Vacancy::class,
            'posted_by',
            'job_posting_id',
            'id',
            'id'
        );
    }

    public function myJobPostings()
    {
        return $this->jobs();
    }

    /**
     * Alias for applications - for backward compatibility
     */
    public function myApplications()
    {
        return $this->applications();
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeInactive($query)
    {
        return $query->where('status', 'inactive');
    }

    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    public function isInactive(): bool
    {
        return $this->status === 'inactive';
    }

    public function getFullNameAttribute(): string
    {
        return $this->name;
    }

    public function getInitialsAttribute(): string
    {
        $words = explode(' ', $this->name);
        if (count($words) >= 2) {
            return strtoupper(substr($words[0], 0, 1) . substr($words[1], 0, 1));
        }
        return strtoupper(substr($this->name, 0, 2));
    }

    public function getPhotoUrlAttribute(): string
    {
        if ($this->photo) {
            return asset('storage/' . $this->photo);
        }
        return asset('images/default-avatar.png');
    }
}