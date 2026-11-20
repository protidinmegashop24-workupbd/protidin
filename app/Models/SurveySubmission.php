<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SurveySubmission extends Model
{
    protected $fillable = [
        'survey_id','user_id','answers','unique_code','code_status','verify_status',
        'correct_count','total_questions','earned_usd','set_date','verified_at'
    ];

    protected $casts = [
        'answers' => 'array',
        'earned_usd' => 'float',
        'verified_at' => 'datetime',
    ];
}