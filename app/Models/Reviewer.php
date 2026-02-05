<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Reviewer extends Authenticatable
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'department',
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
     * Relationship: Reviewer has reviewed many applications
     */
    public function applications()
    {
        return $this->hasMany(Application::class, 'reviewer_id');
    }

    /**
     * Check if reviewer is active
     */
    public function isActive()
    {
        return $this->status === 'active';
    }

    /**
     * Scope: Only active reviewers
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}