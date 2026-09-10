<?php

namespace App\Http\Controllers;

use App\Models\SurveySubmission;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VerifyController extends Controller
{
    private int $dailyLimit = 20;

    public function show(Request $request)
    {
        $userId = $request->user()->id;
        $today  = now()->toDateString();

        $usedToday = SurveySubmission::where('user_id', $userId)
            ->where('code_status', 'used')
            ->whereDate('verified_at', $today)
            ->count();

        $leftToday = max(0, $this->dailyLimit - $usedToday);

        return view('verify.index', compact('usedToday', 'leftToday'));
    }

    public function verify(Request $request)
    {
        $request->validate([
            'code' => 'required|string|max:120',
        ]);

        $user   = $request->user();
        $today  = now()->toDateString();
        $code   = trim($request->code);

        // ✅ daily limit check (BEFORE verify)
        $usedToday = SurveySubmission::where('user_id', $user->id)
            ->where('code_status', 'used')
            ->whereDate('verified_at', $today)
            ->count();

        if ($usedToday >= $this->dailyLimit) {
            return back()->with('error', "❌ আজকে আপনার Verify limit শেষ ({$this->dailyLimit}/{$this->dailyLimit})। কালকে আবার চেষ্টা করুন।");
        }

        // ✅ Find submission by code (this user)
        $sub = SurveySubmission::where('user_id', $user->id)
            ->where('unique_code', $code)
            ->latest()
            ->first();

        if (!$sub) {
            return back()->with('error', '❌ এই Code আপনার account এ পাওয়া যায়নি।');
        }

        // ✅ Already used?
        if ($sub->code_status === 'used') {
            return back()->with('error', '⚠️ এই Code আগেই Verify করা হয়েছে।');
        }

        // ✅ Earned must exist (submit না করলে earned_usd 0 হতে পারে)
        $earned = (float)($sub->earned_usd ?? 0);

        if ($earned <= 0) {
            return back()->with('error', '⚠️ এই Code এর Earned amount পাওয়া যায়নি। আগে Survey submit ঠিকমত হয়েছে কিনা দেখুন।');
        }

        // ✅ Atomic money add + mark used
        DB::transaction(function () use ($user, $sub, $earned) {

            // lock user row
            $u = User::where('id', $user->id)->lockForUpdate()->first();

            // ✅ add to main balance
            $u->earning_balance = (float)($u->earning_balance ?? 0) + $earned;
            $u->referral_activated = 1;
            $u->save();

            // ✅ mark submission used
            $sub->code_status    = 'used';
            $sub->verify_status  = 'verified';
            $sub->verified_at    = now();
            $sub->save();
        });

        // refresh counts for badge
        $usedToday2 = SurveySubmission::where('user_id', $user->id)
            ->where('code_status', 'used')
            ->whereDate('verified_at', $today)
            ->count();

        $leftToday2 = max(0, $this->dailyLimit - $usedToday2);

        return back()->with([
            'success' => "✅ Verified! Earned added to your balance.",
            'verified_code' => $sub->unique_code,
            'correctCount' => (int)($sub->correct_count ?? 0),
            'totalQuestions' => (int)($sub->total_questions ?? 0),
            'earnedUsd' => number_format((float)$sub->earned_usd, 4),
            'usedToday' => $usedToday2,
            'leftToday' => $leftToday2,
        ]);
    }
}
