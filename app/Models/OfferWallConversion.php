<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OfferWallConversion extends Model
{
    protected $table = 'offer_wall_conversions';

    protected $fillable = [
        'provider_slug',
        'user_id',
        'trans_id',
        'status',
        'amount_usd',
        'raw_payload',
        'credited_at',
    ];

    protected $casts = [
        'amount_usd' => 'float',
        'credited_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
