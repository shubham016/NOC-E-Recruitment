<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Approver extends Authenticatable
{
    use Notifiable;

    protected $table = 'approvers';

    protected $fillable = [
        'employee_id',
        'name',
        'phone_number',
        'email',
        'designation',
        'department',
        'vacancy_id',
        'photo',
        'status',
        'password'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Get the name of the unique identifier for the user.
     */
    public function getAuthIdentifierName()
    {
        return 'employee_id';
    }

    /**
     * Get the column name for the "remember me" token.
     */
    public function getRememberTokenName()
    {
        return 'remember_token';
    }

    /**
     * Relationship with vacancy
     */
    public function vacancy()
    {
        return $this->belongsTo(Vacancy::class, 'vacancy_id');
    }

    /**
     * Backward compatibility alias
     * @deprecated Use vacancy() instead
     */
    public function jobPosting()
    {
        return $this->vacancy();
    }

    /**
     * Get notifications for this approver
     */
    public function notifications()
    {
        return $this->morphMany(Notification::class, 'user', 'user_type', 'user_id');
    }

    /**
     * Get unread notifications count
     */
    public function unreadNotifications()
    {
        return $this->notifications()->where('is_read', false);
    }
}
