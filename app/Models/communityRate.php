<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class communityRate extends Model
{
    use HasFactory;
    public $table = 'community_rate';
    public $timestamps = true;
    protected $fillable = [
        'bonusKey',
        'bonusRate'
    ];
}
