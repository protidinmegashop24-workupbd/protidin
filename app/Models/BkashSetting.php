<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BkashSetting extends Model
{
   protected $fillable = ['min_amount', 'description', 'status'];

}
