<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'draft_id',
        'gateway',
        'amount',
        'transaction_id',
        'status',
        'txRef'
    ];
}
