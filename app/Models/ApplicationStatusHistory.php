<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApplicationStatusHistory extends Model
{
    protected $table = 'application_status_histories';

    protected $fillable = [
        'application_form_id',
        'stage_name',
        'done_by',
        'done_by_type',
        'done_by_id',
        'remarks',
    ];

    // Map status values → readable stage names
    public static function stageName(string $status): string
    {
        return match ($status) {
            'edit'     => 'Allow Edit',
            'reviewed' => 'Verified',
            'approved' => 'Approved',
            'rejected' => 'Rejected',
            'pending'  => 'Pending',
            default    => ucfirst($status),
        };
    }

    public function application()
    {
        return $this->belongsTo(ApplicationForm::class, 'application_form_id');
    }
}
