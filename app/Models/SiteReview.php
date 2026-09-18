<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteReview extends Model
{
    protected $table = 'site_reviews';

    protected $fillable = [
        'user_id',
        'rating',
        'comment',
        'status',
        'approved_at',
    ];

    protected $casts = [
        'rating' => 'integer',
        'approved_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
