<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class feedUserEarnHistory extends Model
{
    use HasFactory;
    protected $table = 'feed_user_earn_history';
    protected $fillable = [
        'userId',
        'earnType',
        'price',
        'postId'
    ];
    public function user(){
        return $this->belongsTo(User::class,'userId');
    }
    public function post(){
        return $this->belongsTo(feedpost::class,'postId');
    }
}
