<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Advertisement extends Model
{
    use HasFactory;

    // Both the banner and video-ad creation flows set properties
    // individually rather than mass-assigning, so nothing here currently
    // relies on $fillable -- kept anyway as the documented, safe set of
    // columns any future ::create()/fill() call is allowed to touch.
    protected $fillable = [
        'user_id',
        'ad_type',
        'title',
        'link',
        'image',
        'video_path',
        'thumbnail_path',
        'duration',
        'exp_date',
        'cost',
        'reward_per_view',
        'cost_per_view',
        'min_watch_seconds',
        'budget_total',
        'budget_spent',
        'total_rewarded_views',
        'approval',
    ];

    protected $casts = [
        'reward_per_view' => 'float',
        'cost_per_view' => 'float',
        'budget_total' => 'float',
        'budget_spent' => 'float',
        'min_watch_seconds' => 'integer',
        'total_rewarded_views' => 'integer',
    ];
}
