<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ImageTemplate extends Model
{
    protected $fillable = [
        'name',
        'background_path',
        'enabled',
    ];

    protected $casts = [
        'enabled' => 'boolean',
    ];
}
