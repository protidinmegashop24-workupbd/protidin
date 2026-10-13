<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuestionBank extends Model
{
    protected $table = 'question_banks';

    protected $fillable = [
        'topic','question','options','correct_option'
    ];

    protected $casts = [
        'options' => 'array',
    ];
}