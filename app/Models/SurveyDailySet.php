<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SurveyDailySet extends Model
{
    protected $fillable = [
        'survey_id', 'set_date', 'question_bank_ids'
    ];

    protected $casts = [
        'question_bank_ids' => 'array',
        'set_date' => 'date',
    ];
}