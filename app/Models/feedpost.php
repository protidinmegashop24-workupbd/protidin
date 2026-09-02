<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class feedpost extends Model
{
    use HasFactory;
    protected $table = 'feedposts';

    protected $fillable = [
        'postContent',
        'fetchUrl',
        'fetchTitle',
        'fetchDescription',
        'fetchImg',
        'summary',
        'aiRating',
        'status',
        'totalUserEarn',
        'totalOwnerEarn',
        'likes',
        'commnets',
        'shares',
        'userId',
        'image',
        'video',
        'productId'
    ];
    protected $casts = [
        'aiRating'       => 'integer',
        'totalUserEarn'  => 'float',
        'totalOwnerEarn' => 'float',
        'likes'          => 'integer',
        'commnets'       => 'integer',
        'shares'         => 'integer',
        'has_liked'      => 'boolean',
        'has_commented'  => 'boolean',
    ];
    public function user(){
        return $this->belongsTo(User::class,'userId');
    }
    public function comment_history(){
        return $this->hasMany(feedPostComments::class,'postId');
    }
    public function like_history(){
        return $this->hasMany(feedPostLikes::class,'postId');
    }    
}
