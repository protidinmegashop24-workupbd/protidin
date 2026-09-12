<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CommunityBookmark extends Model
{
    protected $table = 'community_bookmarks';

    protected $fillable = [
        'user_id',
        'post_id',
    ];
}
