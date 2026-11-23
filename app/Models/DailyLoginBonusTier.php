<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DailyLoginBonusTier extends Model
{
    protected $table = 'daily_login_bonus_tiers';

    protected $fillable = [
        'day_number',
        'amount',
    ];

    protected $casts = [
        'day_number' => 'integer',
        'amount' => 'float',
    ];
}
