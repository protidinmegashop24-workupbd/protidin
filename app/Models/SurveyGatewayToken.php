<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SurveyGatewayToken extends Model
{
    protected $fillable = [
        'survey_id', 'user_id', 'token', 'used_at',
    ];

    protected $casts = [
        'used_at' => 'datetime',
    ];
}
