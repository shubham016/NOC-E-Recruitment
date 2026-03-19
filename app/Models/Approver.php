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
        'job_posting_id',
        'photo',
        'status',
        'password'
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relationship with job posting
     */
    public function jobPosting()
    {
        return $this->belongsTo(JobPosting::class, 'job_posting_id');
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
