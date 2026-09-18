<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DailyLoginBonusClaim extends Model
{
    protected $table = 'daily_login_bonus_claims';

    protected $fillable = [
        'user_id',
        'claim_date',
        'day_number',
        'amount',
    ];

    protected $casts = [
        'claim_date' => 'date',
        'day_number' => 'integer',
        'amount' => 'float',
    ];
}
