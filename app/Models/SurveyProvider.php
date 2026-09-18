<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SurveyProvider extends Model
{
    protected $table = 'survey_providers';

    protected $fillable = [
        'name',
        'slug',
        'enabled',
        'app_id',
        'secret_key',
        'config',
    ];

    protected $casts = [
        'enabled' => 'boolean',
    ];
}
