<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdView extends Model
{
    protected $table = 'ad_views';

    protected $fillable = [
        'user_id',
        'ad_id',
        'watched_seconds',
        'reward_amount',
        'ip_address',
    ];

    protected $casts = [
        'watched_seconds' => 'integer',
        'reward_amount' => 'float',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function ad()
    {
        return $this->belongsTo(\App\Models\Admin\Advertisement::class, 'ad_id');
    }
}
