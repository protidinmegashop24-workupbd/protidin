<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'details',
        'location',
        'event_date',
        'event_start_time',
        'event_end_time',
        'feature_image',
        'status',
    ];
}
