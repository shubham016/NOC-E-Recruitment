<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SmsLog extends Model
{
    protected $table = 'sms_logs';

    protected $fillable = [
        'admin_id',
        'candidate_id',
        'job_posting_id',
        'phone',
        'message',
        'response_code',
        'response_message',
    ];

    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }

    public function candidate()
    {
        return $this->belongsTo(CandidateRegistration::class, 'candidate_id');
    }

    public function jobPosting()
    {
        return $this->belongsTo(JobPosting::class);
    }
}
