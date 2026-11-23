<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactUsText extends Model
{
    use HasFactory;

    protected $fillable = [
        'details',
    ];
}
