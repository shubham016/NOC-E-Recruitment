<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApplicationExperience extends Model
{
    protected $table = 'application_experiences';

    protected $fillable = [
        'application_form_id',
        'candidate_id', 
        'exp_number',
        'organization',
        'position',
        'start_date_bs',
        'start_date',
        'end_date_bs',
        'end_date',
        'years',
        'document',
    ];
}
