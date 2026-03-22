<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CandidateRegistration extends Model
{
    protected $table = 'candidate_registration';

    protected $fillable = [
        'name',
        'email',
        'gender',
        'date_of_birth_bs',
        'citizenship_number',
        'citizenship_issue_distric',
        'citizenship_issue_date_bs',
        'password',
        'phone',
    ];

    protected $hidden = ['password'];

    public function applicationForms()
    {
        return $this->hasMany(ApplicationForm::class, 'citizenship_number', 'citizenship_number');
    }
}
