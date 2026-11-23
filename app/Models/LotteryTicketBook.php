<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LotteryTicketBook extends Model
{
    use HasFactory;

    protected $casts = [
        'is_winner' => 'boolean',
    ];

    public function lottery()
    {
        return $this->belongsTo(Lottery::class, 'lottery_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
