<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Approver extends Authenticatable
{
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
        'password'
    ];

    protected $casts = [
        'password' => 'hashed',
    ];

    /**
     * Get the name of the unique identifier for the user.
     *
     * @return string
     */
    public function getAuthIdentifierName()
    {
        return 'employee_id';
    }

    /**
     * Get the username used for authentication (for login form)
     *
     * @return string
     */
    public function username()
    {
        return 'employee_id';
    }

    public function jobPosting()
    {
        return $this->belongsTo(JobPosting::class, 'job_posting_id');
    }

    public function vacancy()
    {
        return $this->jobPosting();
    }

    /**
     * Get all application forms assigned to this approver
     */
    public function applicationForms()
    {
        return $this->hasMany(ApplicationForm::class, 'approver_id');
    }
}