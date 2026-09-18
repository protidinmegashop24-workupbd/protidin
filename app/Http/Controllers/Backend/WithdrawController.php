<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Admin\MainWallet;
use App\Models\Admin\Website;
use App\Models\User;
use App\Models\Admin\UserMessage;
use App\Models\Withdraw;
use App\Models\Job;
use App\Models\JobWork;
use App\Models\ptc_earn_history;
use App\Models\SurveySubmission;
use Illuminate\Http\Request;

class WithdrawController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $datas = Withdraw::latest()->get();
        $website = Website::latest()->first();
        $title = 'Withdraw Request';
        return view('backend.pages.system-setting.withdraw', compact('title', 'datas', 'website'));
    }

    public function pending_withdraw_request()
    {
        $datas = Withdraw::where('approval', 0)->latest()->get();
        $website = Website::latest()->first();
        $title = 'Pending Withdraw Request';
        return view('backend.pages.system-setting.withdraw', compact('title', 'datas', 'website'));
    }

    /**
     * A before-you-pay trust summary for a withdrawing user: account
     * standing (ban/suspend/duplicate-device), how their earnings were
     * actually made, and any rejected/reported work -- so the admin can
     * see whether this account "caused any problem anywhere" before
     * approving the payout, without having to click through half a
     * dozen separate admin pages by hand.
     */
    public function userCheck($userId)
    {
        $user = User::find($userId);
        if (!$user) {
            return response()->json(['error' => 'User not found.'], 404);
        }

        $approvedJobs = JobWork::where('user_id', $userId)->where('status', 1)->count();
        $rejectedJobs = JobWork::where('user_id', $userId)->where('status', 2)->count();
        $reportedJobs = JobWork::where('user_id', $userId)->where('status', 3)->count();
        $pendingJobs = JobWork::where('user_id', $userId)->where('status', 0)->count();

        $ptcClicks = ptc_earn_history::where('ptc_worker_id', $userId)->count();
        $ptcEarned = (float) ptc_earn_history::where('ptc_worker_id', $userId)
            ->join('ptc_job', 'ptc_earn_history.ptc_job_id', '=', 'ptc_job.id')
            ->sum('ptc_job.ptc_each_earn');

        $surveysVerified = SurveySubmission::where('user_id', $userId)->where('code_status', 'used')->count();
        $surveyEarned = (float) SurveySubmission::where('user_id', $userId)->where('code_status', 'used')->sum('earned_usd');

        $jobEarned = (float) JobWork::where('job_works.user_id', $userId)->where('job_works.status', 1)
            ->join('jobs', 'job_works.job_id', '=', 'jobs.id')
            ->sum('jobs.each_worker_earn');

        $duplicateDeviceUsers = User::where('id', '!=', $userId)
            ->where('ip_address', $user->ip_address)
            ->where('device_name', $user->device_name)
            ->where('device_brand', $user->device_brand)
            ->where('device_model', $user->device_model)
            ->whereNotNull('device_name')
            ->pluck('code');

        $referredCount = User::where('rfered_by', $userId)->count();

        $flags = [];
        if ($user->is_ban) {
            $flags[] = 'অ্যাকাউন্ট ব্যান করা আছে';
        }
        if ($user->is_suspended) {
            $flags[] = 'অ্যাকাউন্ট সাসপেন্ড করা আছে';
        }
        if (!$user->hasVerifiedEmail()) {
            $flags[] = 'ইমেইল ভেরিফাই করা নেই';
        }
        if ($duplicateDeviceUsers->count() > 0) {
            $flags[] = 'একই ডিভাইস/আইপি থেকে আরও ' . $duplicateDeviceUsers->count() . 'টা অ্যাকাউন্ট আছে (কোড: ' . $duplicateDeviceUsers->implode(', ') . ')';
        }
        if ($rejectedJobs > 0 && $approvedJobs > 0 && $rejectedJobs >= $approvedJobs) {
            $flags[] = "Approved-এর চেয়ে Rejected job বেশি বা সমান ({$rejectedJobs} rejected vs {$approvedJobs} approved)";
        }
        if ($reportedJobs > 0) {
            $flags[] = "{$reportedJobs}টা কাজ Reported হয়েছে";
        }

        $referralCommission = (float) $user->deposit_commision_from_refer + (float) $user->earning_commision_from_refer;
        $totalTrackedEarned = $jobEarned + $ptcEarned + $surveyEarned + $referralCommission;

        // A balance well above everything we can trace to a real earning
        // event usually means a manual admin balance edit happened -- not
        // necessarily wrong, but worth the admin's attention before payout.
        if ($user->earning_balance > $totalTrackedEarned + 0.01) {
            $flags[] = 'ব্যালেন্স ($' . number_format($user->earning_balance, 4) . ') ট্র্যাক-করা মোট ইনকামের ($' . number_format($totalTrackedEarned, 4) . ') চেয়ে বেশি -- সম্ভবত ম্যানুয়াল অ্যাডজাস্টমেন্ট হয়েছে, একবার দেখে নাও';
        }

        return response()->json([
            'name' => $user->name,
            'code' => $user->code,
            'email' => $user->email,
            'email_verified' => $user->hasVerifiedEmail(),
            'joined_at' => optional($user->created_at)->format('d/m/Y'),
            'is_ban' => (bool) $user->is_ban,
            'is_suspended' => (bool) $user->is_suspended,
            'earning_balance' => (float) $user->earning_balance,
            'approved_jobs' => $approvedJobs,
            'job_earned' => $jobEarned,
            'rejected_jobs' => $rejectedJobs,
            'reported_jobs' => $reportedJobs,
            'pending_jobs' => $pendingJobs,
            'ptc_clicks' => $ptcClicks,
            'ptc_earned' => $ptcEarned,
            'surveys_verified' => $surveysVerified,
            'survey_earned' => $surveyEarned,
            'referral_commission' => $referralCommission,
            'total_referrals' => $referredCount,
            'total_tracked_earned' => $totalTrackedEarned,
            'duplicate_device_accounts' => $duplicateDeviceUsers->values(),
            'flags' => $flags,
            'looks_clean' => count($flags) === 0,
        ]);
    }

    public function withdraw_request_approved(Request $request, $id)
    {
        $withdraw = Withdraw::find($id);
        $msg_user_id = $withdraw->user_id;

        if($request->approval == 1){
            $payable = $withdraw->amount - $withdraw->charge;

            $main_wallet = MainWallet::latest()->first();
            $main_wallet->amount = $main_wallet->amount - $payable;
            $main_wallet->save();

        }elseif($request->approval == 2){
            $user = User::find($withdraw->user_id);
            $user->earning_balance = $user->earning_balance + $withdraw->amount;
            $user->save();

            $main_wallet = MainWallet::latest()->first();
            $main_wallet->amount = $main_wallet->amount - $withdraw->amount;
            $main_wallet->save();
        }

        $withdraw->approval = $request->approval;
        $withdraw->reason = $request->reason;
        $withdraw->save();
        
        $data = new UserMessage();
        $data->user_id = $msg_user_id;
        $data->message_title = 'Withdraw';
        $data->message = 'Your withdraw request approved.';
        $data->save();

        return redirect()->back()->with('message','Successfully approved this deposit!');
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
        $withdraw = Withdraw::find($id);
        $withdraw->delete();

        return redirect()->back()->with('message','Successfully deleted this withdraw request!');
    }
}
