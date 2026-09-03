<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ptc_job extends Model
{
    use HasFactory;
    public $table = 'ptc_job';
    public $timestamps = true;

    public function user(){        
        return $this->belongsTo(User::class,'ptc_post_user_id','id');
    }

}
