<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\JobWork;
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
        $activeReferrals = User::where('rfered_by', $user->id)
            ->whereIn('id', JobWork::where('status', 1)->pluck('user_id')->unique())
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
