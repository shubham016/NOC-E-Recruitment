<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    public const STATUS_PENDING = 'pending';
    public const STATUS_PAID = 'paid';
    public const STATUS_COMPLETED = 'completed';
    public const SUCCESS_STATUSES = [
        self::STATUS_PAID,
        self::STATUS_COMPLETED,
    ];

    protected $fillable = [
        'draft_id',
        'gateway',
        'amount',
        'transaction_id',
        'status',
        'txRef'
    ];

    public function application()
    {
        return $this->belongsTo(\App\Models\ApplicationForm::class, 'draft_id');
    }

    public function isSuccessful(): bool
    {
        return in_array($this->status, self::SUCCESS_STATUSES, true);
    }
}
