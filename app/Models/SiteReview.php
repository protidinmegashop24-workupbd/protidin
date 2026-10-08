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
        'pinned',
    ];

    protected $casts = [
        'rating' => 'integer',
        'approved_at' => 'datetime',
        'pinned' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
