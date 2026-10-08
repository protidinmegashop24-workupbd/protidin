<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\DailyLoginBonusTier;
use App\Models\SiteReview;
use Illuminate\Http\Request;

class SiteReviewController extends Controller
{
    public function index()
    {
        $title = 'Pending Reviews';
        $datas = SiteReview::with('user')->where('status', 'pending')->latest()->get();
        return view('backend.pages.reviews.index', compact('title', 'datas'));
    }

    public function approvedList()
    {
        $title = 'Approved Reviews';
        $datas = SiteReview::with('user')->where('status', 'approved')->latest()->get();
        return view('backend.pages.reviews.index', compact('title', 'datas'));
    }

    public function rejectedList()
    {
        $title = 'Rejected Reviews';
        $datas = SiteReview::with('user')->where('status', 'rejected')->latest()->get();
        return view('backend.pages.reviews.index', compact('title', 'datas'));
    }

    public function approve($id)
    {
        $review = SiteReview::find($id);
        $review->status = 'approved';
        $review->approved_at = now();
        $review->save();

        return redirect()->back()->with('message', 'Review approved -- now visible on the homepage, and the daily login bonus has started for this user.');
    }

    public function reject($id)
    {
        $review = SiteReview::find($id);
        $review->status = 'rejected';
        $review->approved_at = null;
        $review->save();

        return redirect()->back()->with('message', 'Review rejected.');
    }

    /**
     * Pinned reviews always show in the homepage's 9-review carousel
     * (HomeController::index), regardless of how recently they were
     * approved -- the remaining slots fill with the latest approved
     * reviews as usual. Unpinning just lets it rotate out normally again.
     */
    public function togglePin($id)
    {
        $review = SiteReview::find($id);
        if (!$review) {
            return redirect()->back()->with('message', 'Review not found.');
        }

        $review->pinned = !$review->pinned;
        $review->save();

        return redirect()->back()->with('message', $review->pinned ? 'Review pinned -- it will always show on the homepage.' : 'Review unpinned.');
    }

    public function bonusTiers()
    {
        $title = 'Daily Login Bonus Schedule';
        $tiers = DailyLoginBonusTier::orderBy('day_number')->get();
        return view('backend.pages.reviews.bonus-tiers', compact('title', 'tiers'));
    }

    public function updateBonusTiers(Request $request)
    {
        $request->validate([
            'amounts' => 'required|array',
            'amounts.*' => 'required|numeric|min:0',
        ]);

        foreach ($request->amounts as $tierId => $amount) {
            DailyLoginBonusTier::where('id', $tierId)->update(['amount' => $amount]);
        }

        return redirect()->back()->with('message', 'Daily login bonus schedule updated.');
    }
}
