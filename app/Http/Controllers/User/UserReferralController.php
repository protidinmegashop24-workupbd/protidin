<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\ReferralCommissionLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

        // Activation bonus, marketplace bonus, and milestone rewards aren't
        // built yet -- show honest zeros/empty state instead of crashing or
        // making up numbers.
        $activationBonus = 0;
        $milestoneBonus = 0;
        $totalReferralIncome = $depositCommission + $earningCommission + $activationBonus + $milestoneBonus;

        $nextMilestoneTarget = null;
        $nextMilestoneReward = 0;
        $progressPercent = 0;

        $recentRewards = collect();

        return view('user.pages.referral', compact(
            'title', 'referralLink', 'totalReferrals', 'activeReferrals',
            'depositCommission', 'earningCommission', 'activationBonus', 'milestoneBonus',
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
