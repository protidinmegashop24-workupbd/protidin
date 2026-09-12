<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class feedPostComments extends Model
{
    use HasFactory;    
    protected $table = 'feedpost_comments';
    protected $fillable = [
        'postId',
        'comment',
        'parentId',
        'userId'
    ];
    
    public function user(){
        return $this->belongsTo(User::class,'userId');
    }
    public function parent(){
        return $this->belongsTo(self::class,'parentId');
    }
    public function post(){
        return $this->belongsTo(feedpost::class,'postId');
    }
    public function replies(){
        return $this->hasMany(self::class,'parentId')->with('replies','user');
    }
}
