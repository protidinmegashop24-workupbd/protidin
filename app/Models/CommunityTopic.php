<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CommunityTopic extends Model
{
    protected $table = 'community_topics';

    protected $fillable = [
        'name',
        'slug',
        'icon',
    ];

    public function posts()
    {
        return $this->belongsToMany(feedpost::class, 'community_post_topics', 'topic_id', 'post_id');
    }
}
