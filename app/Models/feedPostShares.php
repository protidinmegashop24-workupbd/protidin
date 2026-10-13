<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class feedPostShares extends Model
{
    use HasFactory;
    protected $table = 'feed_post_shares';

    protected $fillable = [
        'postId',
        'userId',
    ];

    public function user(){
        return $this->belongsTo(User::class,'userId');
    }

    public function post(){
        return $this->belongsTo(feedpost::class,'postId');
    }
}
