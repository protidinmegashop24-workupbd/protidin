<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Advertisement extends Model
{
    use HasFactory;

    // The existing banner flow (UserAdvertisementController::store()) sets
    // properties individually rather than mass-assigning, so this fillable
    // list only matters for the new video-ad flow that uses ::create().
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

    public function isVideoAd()
    {
        return $this->ad_type === 'video';
    }

    // A video ad still has budget left once it's spent less than its
    // total -- checked with a fresh DB read (not this in-memory instance)
    // wherever it gates a reward, since budget_spent changes concurrently.
    public function hasBudgetRemaining()
    {
        return (float) $this->budget_spent < (float) $this->budget_total;
    }
}
