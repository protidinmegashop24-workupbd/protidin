<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\DailyLoginBonusClaim;
use App\Models\DailyLoginBonusTier;
use App\Models\SiteReview;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserReviewController extends Controller
{
    public function index()
    {
        $title = 'Rate & Review';
        $review = SiteReview::where('user_id', Auth::id())->first();

        $claimedDays = 0;
        $todayClaimed = false;
        $nextBonusAmount = null;

        if ($review && $review->status === 'approved') {
            $claimedDays = DailyLoginBonusClaim::where('user_id', Auth::id())->count();
            $todayClaimed = DailyLoginBonusClaim::where('user_id', Auth::id())
                ->where('claim_date', now()->toDateString())
                ->exists();

            $nextDay = $claimedDays + ($todayClaimed ? 1 : 0);
            $nextTier = DailyLoginBonusTier::where('day_number', $nextDay)->first()
                ?? DailyLoginBonusTier::orderByDesc('day_number')->first();
            $nextBonusAmount = $nextTier ? (float) $nextTier->amount : null;
        }

        return view('user.pages.review', compact('title', 'review', 'claimedDays', 'todayClaimed', 'nextBonusAmount'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|min:10|max:1000',
        ]);

        $review = SiteReview::where('user_id', Auth::id())->first();

        if ($review && $review->status === 'approved') {
            return redirect()->back()->with('error', 'আপনার রিভিউ ইতিমধ্যে অনুমোদিত হয়ে গেছে, আর পরিবর্তন করা যাবে না।');
        }

        SiteReview::updateOrCreate(
            ['user_id' => Auth::id()],
            [
                'rating' => $request->rating,
                'comment' => $request->comment,
                'status' => 'pending',
                'approved_at' => null,
            ]
        );

        return redirect()->back()->with('message', 'ধন্যবাদ! আপনার রিভিউ জমা হয়েছে, অ্যাডমিন অনুমোদনের অপেক্ষায় আছে।');
    }
}
