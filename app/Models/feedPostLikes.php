<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class feedPostLikes extends Model
{
    use HasFactory;
    protected $table = 'feedpost_likes';
    protected $fillable = [
        'postId',
        'userId'
    ];
    
    public function user(){
        return $this->belongsTo(User::class,'userId');
    }
    public function post(){
        return $this->belongsTo(feedpost::class,'postId');
    }
}
