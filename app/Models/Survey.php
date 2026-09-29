<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Survey extends Model
{
    protected $fillable = [
        'title', 'description', 'reward', 'topic', 'questions_per_attempt', 'is_active'
    ];

    public function submissions()
    {
        return $this->hasMany(SurveySubmission::class);
    }
}