<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CommunityReport extends Model
{
    protected $table = 'community_reports';

    protected $fillable = [
        'post_id',
        'user_id',
        'reason',
        'status',
    ];

    public function post()
    {
        return $this->belongsTo(feedpost::class, 'post_id');
    }

    public function reporter()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
