<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReferralMilestone extends Model
{
    protected $table = 'referral_milestones';

    protected $fillable = [
        'referral_count',
        'reward_amount',
    ];

    protected $casts = [
        'referral_count' => 'integer',
        'reward_amount' => 'float',
    ];
}
