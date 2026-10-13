<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReferralCommissionLog extends Model
{
    protected $table = 'referral_commission_logs';

    protected $fillable = [
        'referrer_id',
        'source_user_id',
        'type',
        'amount',
    ];

    protected $casts = [
        'amount' => 'float',
    ];

    public function referrer()
    {
        return $this->belongsTo(User::class, 'referrer_id');
    }

    public function sourceUser()
    {
        return $this->belongsTo(User::class, 'source_user_id');
    }
}
