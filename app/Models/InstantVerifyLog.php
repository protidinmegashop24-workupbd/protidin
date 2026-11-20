<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InstantVerifyLog extends Model
{
    protected $table = 'instant_verify_logs';

    protected $fillable = [
        'user_id',
        'fee_charged',
        'balance_column',
    ];

    protected $casts = [
        'fee_charged' => 'float',
    ];
}
