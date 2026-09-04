<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Admin\Category;
use App\Models\Admin\Client;
use App\Models\Admin\Country;
use App\Models\Admin\ContinentCountry;
use App\Models\Admin\Deposit;
use App\Models\Admin\LocationZone;
use App\Models\Admin\LocationZoneCountry;
use App\Models\Admin\Service;
use App\Models\Admin\SubCategory;
use App\Models\Admin\UserMessage;
use App\Models\Admin\Website;
use App\Models\BoostSubCategory;
use App\Models\Job;
use App\Models\JobWork;
use App\Models\Policy;
use App\Models\TopDepositUserHeadline;
use App\Models\TopEarningUserHeadline;
use App\Models\TopReferralUserHeadline;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Library\UddoktaPay;

class UserDashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $userId = Auth::user()->id;
        $userInfo = User::find(Auth::user()->id);
        $deposits = Deposit::where(['user_id' => $userId, 'approval' => 0])->get();
        
        foreach($deposits as $deposit) {
            $data = UddoktaPay::verify_payment($deposit->invoice_id);
            if (isset($data['status']) && $data['status'] == 'COMPLETED') {
                $userInfo->deposit_balance = $userInfo->deposit_balance + ($data['amount'] / 100);
                $userInfo->save();
                
                $deposit_update = Deposit::find($deposit->id);
                $deposit_update->approval = 1;
                $deposit_update->save();
            }
        }
        
        // Auto-approve work proofs the job owner didn't review within 24 hours.
        // Must mirror job_work_approve()'s effects exactly (pay the worker,
        // referral commission, and increment worker_confirmed) -- otherwise
        // worker_confirmed drifts from the real approved count and progress
        // bars/"job complete" checks go wrong.
        $works = JobWork::where('created_at', '<', Carbon::parse('-24 hours'))->where('status', 0)->get();
        if($works->count() > 0){
            $website = Website::latest()->first();
            foreach($works as $job_work){
                $job = Job::find($job_work->job_id);
                if($job){
                    $user = User::find($job_work->user_id);
                    $user->earning_balance = $user->earning_balance + $job->each_worker_earn;

                    if($website && $website->referral_earning_commission > 0){
                        $earning_commission = ($website->referral_earning_commission * $job->each_worker_earn) / 100;

                        $refered_by = User::find($user->rfered_by);
                        if($refered_by){
                            $refered_by->earning_balance = $refered_by->earning_balance + $earning_commission;
                            $refered_by->save();

                            $user->earning_commision_from_refer = $user->earning_commision_from_refer + $earning_commission;
                        }
                    }

                    $user->save();

                    $job->worker_confirmed = $job->worker_confirmed + 1;
                    $job->save();
                }

                $job_work->status = 1;
                $job_work->save();
            }
        }
        
        // delete data more than 30 days
        
        // For auto user block-------------------
        // $total_attempt = user_complete_job(Auth::user()->id);
        // $total_pending = user_complete_job_pending(Auth::user()->id);
        // $total_reject = user_complete_job_reject(Auth::user()->id);
        // $total_approve = user_complete_job_approve(Auth::user()->id);
        // if($total_attempt > 0){
        //     $total_activity_work = $total_attempt - ($total_pending + $total_reject);
        //     if($total_activity_work > 0){
        //         $approval_ratio = ($total_approve * 100) / $total_activity_work;
        //         $user_block_ration = site_info()->user_block_ratio;
        //         if($user_block_ration >= $approval_ratio){
        //             update_user_block(Auth::user()->id);
        //         }
        //     }
        // }
        // For auto user block end-------------------
        
        
        $date = Carbon::now()->subDays(30);
        JobWork::where('created_at', '<=', $date)->update(['trash' => 1]);
        // JobWork::where('created_at', '<=', $date)->delete();

        $location_zone = LocationZone::latest()->get();
        $countries = Country::latest()->get();
        $categorys = Category::latest()->get();
        $job_found = job_found();
        $jobs = Job::where('status', 1)->where('worker_need', '!=', 'worker_confirmed')->latest()->limit(20)->get();
        return view('user.pages.home', compact('location_zone', 'countries', 'categorys', 'jobs', 'job_found'));
    }
    
    public function job_details($code)
    {
        $job = Job::where('code', $code)->first();
        if(!$job){
            return redirect()->back()->with('error','This job is not found!');
        }
        $title = 'Job details';
        return view('user.pages.my-job-details', compact('title', 'job'));
    }

    public function policy_details($slug)
    {
        $policy = Policy::where('slug', $slug)->first();
        return view('user.pages.policy', compact('policy'));
    }

    public function top_deposit_user()
    {
        $deposit_users = Deposit::where('approval', 1) // Filter for approved deposits (1 for approved)
            ->groupBy('user_id')
            ->select('user_id')
            ->get();
    
        $top_users = [];
    
        foreach ($deposit_users as $user) {
            $amount = Deposit::where('user_id', $user->user_id)
                ->where('approval', 1) // Filter for approved deposits (1 for approved)
                ->sum('amount');
    
            $top_users[] = [
                'user_id' => $user->user_id,
                'amount' => $amount,
            ];
        }
    
        array_multisort(array_column($top_users, 'amount'), SORT_DESC, $top_users);
    
        $i = 0;
        $top_users_list = [];
    
        foreach ($top_users as $deposit_user) {
            if ($i < 10) {
                $top_users_list[] = $deposit_user;
            }
            $i++;
        }
    
        $headlines = TopDepositUserHeadline::all();
        return view('user.pages.top-deposit-user', compact('top_users_list', 'headlines'));
    }

    public function top_earning_user()
    {
        $top_users= User::take(10)->orderBy('earning_balance','DESC')->get();
        $headlines = TopEarningUserHeadline::all();
        return view('user.pages.top-earning-user', compact('top_users','headlines'));
    }

    public function top_referral_user()
    {
        $top_users= User::take(10)->orderBy('total_refer','DESC')->get();
        $headlines = TopReferralUserHeadline::all();
        return view('user.pages.top-referral-user', compact('top_users','headlines'));
    }

    public function message_list()
    {
        UserMessage::where('user_id', Auth::user()->id)->update(['seen' => 1]);
        $datas = UserMessage::where('user_id', Auth::user()->id)->latest()->get();
        return view('user.pages.message-list', compact('datas'));
    }

    public function get_country(Request $request)
    {
        $location = $request->location_zone;

        $countrys = LocationZoneCountry::where('zone_id', $location)->orderBy('id', 'ASC')->get();
        $html = '';
        $html .= '<option value="">Select One</option>';
        if($countrys){
            foreach($countrys as $country){
                $ck_country = Country::find($country->country_id);
                if($ck_country){
                    $html .= '<option value="'.$ck_country->id.'">'.$ck_country->name.'</option>';
                }
            }
        }

        return $html;
    }


    public function get_continent_country(Request $request)
    {
        $continent_id = $request->continet_id;

        $countrys = ContinentCountry::where('continent_id', $continent_id)->orderBy('id', 'ASC')->get();
        $html = '';
        foreach($countrys as $c_country){
            $html .= '
                <div class="col001">
                    <div class="col022">
                        <input type="checkbox" id="ex-int-'.$c_country->country_id.'" class="custom-control-input exclude-country" name="country_id[]" value="'.$c_country->country_id.'">
                        <label for="ex-int-'.$c_country->country_id.'" class="exclude-country-label  bg-gray border-0">'.country($c_country->country_id).'</label>
                    </div>
                </div>
            ';
        }

        return $html;
    }


    public function get_sub_category(Request $request)
    {
        $category_id = $request->category_id;

        $sub_cats = SubCategory::where('category_id', $category_id)->orderBy('id', 'ASC')->get();
        $html = '';
        $html .= '<option value="">Select One</option>';
        if($sub_cats){
            foreach($sub_cats as $category){
                $html .= '<option value="'.$category->id.'">'.$category->name.'</option>';
            }
        }

        return $html;
    }
    
    public function get_sub_categorys(Request $request)
    {
        $category_id = $request->category_id;

        $sub_cats = SubCategory::where('category_id', $category_id)->orderBy('id', 'ASC')->get();
        $html = '';
        if($sub_cats){
            foreach($sub_cats as $category){
                $html .= '
                    <div class="category-item-div cchild-item '.$category->id.' m-0" onclick="selectSubCategory('. $category->id .')">
                        <input data-cpc="0.02" data-upload="0" type="radio" name="sub_category" value="'.$category->id.'" id="radio-child-'.$category->id.'"><label class="zone-item" for="radio-child-'.$category->id.'">'.$category->name.'</label>
                    </div>
                ';
            }
        }

        return $html;
    }


    public function get_sub_category_price(Request $request)
    {
        $id = $request->sub_category;

        $sub_cats = SubCategory::find($id);
        return $sub_cats->minimum_cost;
    }

    public function get_new_task_complete_area(Request $request)
    {
        $need_to_completed_step = $request->need_to_completed_step;
        $html = '
            <div class="form-group" id="another_area_'.$need_to_completed_step.'">
                <label for="link" class="form-label">Step '.$need_to_completed_step.' [ Max 100 character ] <button type="button" class="btn btn-sm btn-danger" onclick="deleteCompleteArea('.$need_to_completed_step.')">X</button></label>
                <textarea class="form-control" name="specific_task[]" id="" cols="30" rows="1"></textarea>
            </div>
        ';

        return $html;
    }

    public function get_boost_sub_category(Request $request)
    {
        $category_id = $request->category_id;

        $sub_cats = BoostSubCategory::where('category_id', $category_id)->get();
        $html = '';
        $html .= '<option value="">Select One</option>';
        if($sub_cats){
            foreach($sub_cats as $category){
                $html .= '<option value="'.$category->id.'">'.$category->id.'-'.$category->name.'</option>';
            }
        }

        return $html;
    }

    public function get_boost_sub_category_price(Request $request)
    {
        $id = $request->sub_category;

        $sub_cat = BoostSubCategory::find($id);

        return ['cost'=>$sub_cat->cost, 'notice'=>$sub_cat->notice];
    }

    public function account_not_verified()
    {
        return view('user.pages.account-not-verified');
    }

    public function account_instant_verify()
    {
        return view('user.pages.instant-verify');
    }

    public function instant_verify_my_account(Request $request)
    {
        $request->validate([
            'balance_type' => 'required|in:deposit_balance,earning_balance',
        ]);

        $user = User::find(Auth::id());

        if ($user->is_verified) {
            return redirect()->back()->with('error', 'Your account is already verified.');
        }

        $fee = (float) (optional(site_info())->instant_verify_fee ?? 0);
        $column = $request->balance_type;

        if ($fee > 0) {
            if ((float) $user->{$column} < $fee) {
                return redirect()->back()->with('error', 'Insufficient balance to verify your account.');
            }
            $user->{$column} = (float) $user->{$column} - $fee;
        }

        $user->is_verified = 1;
        $user->save();

        return redirect()->back()->with('success', 'Your account has been verified successfully!');
    }

    public function account_instant_verify_by_nid()
    {
        return view('user.pages.verify-by-nid');
    }

    public function account_instant_verify_by_nid_store(Request $request)
    {
        $user = User::find(Auth::id());

        if (in_array($user->kyc_status, ['pending', 'approve'])) {
            return redirect()->back()->with('error', 'Your KYC has already been submitted.');
        }

        $isResubmit = $user->kyc_status === 'unapprove';

        $request->validate([
            'kyc_name'       => 'required|string|max:255',
            'kyc_nid_number' => 'required|string|max:50',
            'kyc_address'    => 'required|string|max:500',
            'kyc_birthday'   => 'required|date',
            'kyc_card_type'  => 'required|in:nid,birth',
            'kyc_userimg'    => ($isResubmit ? 'nullable' : 'required') . '|image|max:4096',
            'kyc_frontpart'  => ($isResubmit ? 'nullable' : 'required') . '|image|max:4096',
            'kyc_backpart'   => ($isResubmit ? 'nullable' : 'required') . '|image|max:4096',
        ]);

        $dir = 'uploads/kyc';
        if (!file_exists(base_path($dir))) {
            @mkdir(base_path($dir), 0755, true);
        }

        foreach (['kyc_userimg', 'kyc_frontpart', 'kyc_backpart'] as $field) {
            if ($request->hasFile($field)) {
                $file = $request->file($field);
                $filename = 'kyc_' . $user->id . '_' . $field . '_' . time() . '.' . $file->getClientOriginalExtension();
                $file->move(base_path($dir), $filename);
                $user->{$field} = $dir . '/' . $filename;
            }
        }

        $user->kyc_name = $request->kyc_name;
        $user->kyc_nid_number = $request->kyc_nid_number;
        $user->kyc_address = $request->kyc_address;
        $user->kyc_birthday = $request->kyc_birthday;
        $user->kyc_card_type = $request->kyc_card_type;
        $user->kyc_status = 'pending';
        $user->kyc_notice = null;
        $user->save();

        return redirect()->back()->with('success', 'Your KYC information has been submitted and is pending review.');
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
