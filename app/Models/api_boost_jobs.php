<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class api_boost_jobs extends Model
{
    use HasFactory;
    public $table = 'api_boost_jobs';
    public $timestamps = true;

    public function user(){        
        return $this->belongsTo(User::class,'user_id','id');
    }

}