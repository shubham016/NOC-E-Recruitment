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
}