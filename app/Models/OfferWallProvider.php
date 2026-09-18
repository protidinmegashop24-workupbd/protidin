<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OfferWallProvider extends Model
{
    protected $table = 'offer_wall_providers';

    protected $fillable = [
        'name',
        'slug',
        'enabled',
        'app_id',
        'secret_key',
        'widget_url_template',
        'config',
    ];

    protected $casts = [
        'enabled' => 'boolean',
    ];
}
