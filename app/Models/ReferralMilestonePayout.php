<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReferralMilestonePayout extends Model
{
    protected $table = 'referral_milestone_payouts';

    protected $fillable = [
        'user_id',
        'milestone_id',
        'amount',
    ];

    protected $casts = [
        'amount' => 'float',
    ];

    public function milestone()
    {
        return $this->belongsTo(ReferralMilestone::class, 'milestone_id');
    }
}
