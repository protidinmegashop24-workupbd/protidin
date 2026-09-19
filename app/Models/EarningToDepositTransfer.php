<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EarningToDepositTransfer extends Model
{
    protected $table = 'earning_to_deposit_transfers';

    protected $fillable = [
        'user_id',
        'amount',
    ];

    protected $casts = [
        'amount' => 'float',
    ];
}
