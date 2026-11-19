<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lottery extends Model
{
    use HasFactory;

    protected $casts = [
        'draws_at' => 'datetime',
        'drawn_at' => 'datetime',
        'auto_renew' => 'boolean',
    ];

    public function tickets()
    {
        return $this->hasMany(LotteryTicketBook::class, 'lottery_id');
    }
}
