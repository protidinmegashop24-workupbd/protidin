<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VideoTemplate extends Model
{
    protected $fillable = [
        'name',
        'category',
        'background_path',
        'thumbnail_path',
        'duration_seconds',
        'text_color',
        'font_size',
        'cost',
        'enabled',
    ];

    protected $casts = [
        'duration_seconds' => 'integer',
        'font_size' => 'integer',
        'cost' => 'float',
        'enabled' => 'boolean',
    ];
}
