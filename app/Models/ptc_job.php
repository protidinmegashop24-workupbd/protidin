<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ptc_job extends Model
{
    use HasFactory;
    public $table = 'ptc_job';
    public $timestamps = true;

    protected $fillable = [
        'ptc_post_user_id',
        'ptc_title',
        'ptc_jobLink',
        'ptc_each_earn',
        'ptc_worker_needed',
        'ptc_clicked',
        'ptc_wait_time',
        'ptc_expire_day',
        'ptc_job_details',
        'ptc_status',
        'ptc_reject_notice',
    ];

    public function user(){
        return $this->belongsTo(User::class,'ptc_post_user_id','id');
    }

}
