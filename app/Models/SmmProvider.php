<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SmmProvider extends Model
{
    protected $table = 'smm_providers';

    protected $fillable = [
        'name',
        'slug',
        'api_url',
        'api_key',
        'enabled',
    ];

    protected $casts = [
        'enabled' => 'boolean',
    ];
}
