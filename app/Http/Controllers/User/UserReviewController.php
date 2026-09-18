<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\SiteReview;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserReviewController extends Controller
{
    public function index()
    {
        $title = 'Rate & Review';
        $review = SiteReview::where('user_id', Auth::id())->first();

        $bonusStatus = daily_login_bonus_status(User::find(Auth::id()));
        $claimedDays = $bonusStatus['current_streak_day'];
        $todayClaimed = $bonusStatus['claimed_today'];
        $nextBonusAmount = $bonusStatus['next_amount'];

        return view('user.pages.review', compact('title', 'review', 'claimedDays', 'todayClaimed', 'nextBonusAmount'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => ['required', 'string', 'max:1000', function ($attribute, $value, $fail) {
                $wordCount = count(array_filter(preg_split('/\s+/u', trim($value))));
                if ($wordCount < 10) {
                    $fail('আপনার কমেন্টে কমপক্ষে ১০টি শব্দ থাকতে হবে।');
                }
            }],
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
