<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Admin extends Authenticatable
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'photo',
        'status',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Relationship: Admin posted many vacancies
     */
    public function vacancies()
    {
        return $this->hasMany(Vacancy::class, 'posted_by');
    }

    /**
     * Backward compatibility alias
     * @deprecated Use vacancies() instead
     */
    public function jobPostings()
    {
        return $this->vacancies();
    }

    /**
     * Get the full photo URL
     */
    public function getPhotoUrlAttribute()
    {
        if ($this->photo) {
            return asset('storage/' . $this->photo);
        }
        return null;
    }
}