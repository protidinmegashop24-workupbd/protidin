<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrimaryDescription extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'details'
    ];
}
