<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MissionVision extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'details'
    ];
}
