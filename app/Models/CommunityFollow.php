<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CommunityFollow extends Model
{
    protected $table = 'community_follows';

    protected $fillable = [
        'follower_id',
        'followed_id',
    ];
}
