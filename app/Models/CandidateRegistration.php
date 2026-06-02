<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class CandidateRegistration extends Authenticatable
{
    protected $table = 'candidate_registration';

    protected $fillable = [
        'name',
        'email',
        'gender',
        'date_of_birth_bs',
        'birth_date_ad',
        'citizenship_number',
        'nid',
        'noc_employee',
        'employee_id',
        'citizenship_issue_distric',
        'citizenship_issue_date_bs',
        'password',
        'phone',
    ];

    protected $hidden = ['password', 'remember_token'];

    public function applicationForms()
    {
        return $this->hasMany(ApplicationForm::class, 'citizenship_number', 'citizenship_number');
    }
}
