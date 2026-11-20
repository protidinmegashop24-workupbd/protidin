<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\ReferralCommissionLog;
use App\Models\ReferralMilestone;
use App\Models\ReferralMilestonePayout;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

class UserReferralController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title = "Referral Link";
        $user = Auth::user();
        $referralLink = route('register.with.code', $user->code);

        $totalReferrals = User::where('rfered_by', $user->id)->count();
        // "Active" means this referred user did something that counts as
        // activation (deposit, job work, service/lottery/investment/web
        // script purchase, etc.) -- referral_activated is set on all of
        // those paths, not just approved job work.
        $activeReferrals = User::where('rfered_by', $user->id)
            ->where('referral_activated', 1)
            ->count();

        $depositCommission = (float) $user->deposit_commision_from_refer;
        $earningCommission = (float) $user->earning_commision_from_refer;

        $marketplaceBonus = Schema::hasTable('referral_commission_logs')
            ? (float) ReferralCommissionLog::where('referrer_id', $user->id)
                ->where('type', 'marketplace')
                ->sum('amount')
            : 0;

        $milestoneBonus = Schema::hasTable('referral_milestone_payouts')
            ? (float) ReferralMilestonePayout::where('user_id', $user->id)->sum('amount')
            : 0;

        $totalReferralIncome = $depositCommission + $earningCommission + $marketplaceBonus + $milestoneBonus;

        $nextMilestoneTarget = null;
        $nextMilestoneReward = 0;
        $progressPercent = 0;

        if (Schema::hasTable('referral_milestones')) {
            $paidMilestoneIds = ReferralMilestonePayout::where('user_id', $user->id)->pluck('milestone_id');
            $nextMilestone = ReferralMilestone::whereNotIn('id', $paidMilestoneIds)
                ->orderBy('referral_count')
                ->first();

            if ($nextMilestone) {
                $nextMilestoneTarget = $nextMilestone->referral_count;
                $nextMilestoneReward = (float) $nextMilestone->reward_amount;
                $progressPercent = $nextMilestoneTarget > 0
                    ? min(100, (int) round(($activeReferrals / $nextMilestoneTarget) * 100))
                    : 0;
            }
        }

        $recentRewards = collect();

        if (Schema::hasTable('referral_commission_logs')) {
            $commissionLogs = ReferralCommissionLog::where('referrer_id', $user->id)
                ->where('amount', '>', 0)
                ->latest()
                ->take(10)
                ->get();

            $sourceNames = User::whereIn('id', $commissionLogs->pluck('source_user_id')->unique())
                ->pluck('name', 'id');

            $labelByType = [
                'deposit' => 'deposit',
                'earning' => 'earning',
                'marketplace' => 'marketplace order',
            ];

            $recentRewards = $recentRewards->concat($commissionLogs->map(function ($log) use ($sourceNames, $labelByType) {
                $name = $sourceNames[$log->source_user_id] ?? 'a referred user';
                $label = $labelByType[$log->type] ?? $log->type;
                return (object) [
                    'amount' => $log->amount,
                    'type' => $log->type,
                    'note' => "From {$name}'s {$label}",
                    'created_at' => $log->created_at,
                ];
            }));
        }

        if (Schema::hasTable('referral_milestone_payouts')) {
            $milestonePayouts = ReferralMilestonePayout::where('user_id', $user->id)
                ->with('milestone')
                ->latest()
                ->take(10)
                ->get();

            $recentRewards = $recentRewards->concat($milestonePayouts->map(function ($payout) {
                return (object) [
                    'amount' => $payout->amount,
                    'type' => 'milestone',
                    'note' => $payout->milestone
                        ? "{$payout->milestone->referral_count} active referrals milestone reached"
                        : 'Milestone reward',
                    'created_at' => $payout->created_at,
                ];
            }));
        }

        $recentRewards = $recentRewards->sortByDesc('created_at')->take(10)->values();

        return view('user.pages.referral', compact(
            'title', 'referralLink', 'totalReferrals', 'activeReferrals',
            'depositCommission', 'earningCommission', 'marketplaceBonus', 'milestoneBonus',
            'totalReferralIncome', 'nextMilestoneTarget', 'nextMilestoneReward', 'progressPercent',
            'recentRewards'
        ));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function view_list()
    {
        $title = "Referral Users";
        $datas = User::where('rfered_by', Auth::user()->id)->latest()->paginate(25);

        // The commission each referred user's row shows is how much I (the
        // logged-in referrer) earned FROM that specific person -- not their
        // own deposit_commision_from_refer/earning_commision_from_refer,
        // which is a different number (what THEY earned from people THEY
        // referred, almost always $0 for someone who hasn't referred anyone).
        $commissionTotals = ReferralCommissionLog::where('referrer_id', Auth::id())
            ->whereIn('source_user_id', $datas->pluck('id'))
            ->selectRaw('source_user_id, type, SUM(amount) as total')
            ->groupBy('source_user_id', 'type')
            ->get();

        $lookup = [];
        foreach ($commissionTotals as $row) {
            $lookup[$row->source_user_id][$row->type] = (float) $row->total;
        }

        $datas->getCollection()->transform(function ($referredUser) use ($lookup) {
            $referredUser->deposit_commission_from_this_user = $lookup[$referredUser->id]['deposit'] ?? 0;
            $referredUser->earning_commission_from_this_user = $lookup[$referredUser->id]['earning'] ?? 0;
            return $referredUser;
        });

        return view('user.pages.referral-user', compact('title', 'datas'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
