<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ptc_earn_history extends Model
{
    use HasFactory;
    public $table = 'ptc_earn_history';
    public $timestamps = true;

    protected $fillable = [
        'ptc_worker_id',
        'ptc_job_id',
    ];

    public function user(){
        return $this->belongsTo(User::class,'ptc_worker_id','id');
    }
    public function history(){        
        return $this->belongsTo(ptc_job::class,'ptc_job_id','id');
    }
}
