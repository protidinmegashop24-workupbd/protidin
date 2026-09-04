<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Admin\Category;
use App\Models\Admin\Continent;
use App\Models\Admin\Country;
use App\Models\Admin\JobFee;
use App\Models\Admin\LocationZone;
use App\Models\Admin\LocationZoneCountry;
use App\Models\Admin\MainWallet;
use App\Models\Admin\SubCategory;
use App\Models\Admin\Website;
use App\Models\BoostCharge;
use App\Models\BoostJob;
use App\Models\Job;
use App\Models\JobCountry;
use App\Models\User;
use App\Models\ptc_job;
use App\Models\ptc_earn_history;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class UserJobController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // if( Auth::user()->is_ban == 1 && suspend_url_status('job') == 1){
        $currenturl = url()->current();
        if( Auth::user()->is_ban == 1 && suspend_url_status($currenturl) == 1){
            return redirect()->route('user.account-suspended');
        }
        
        job_delete(Auth::user()->id);
        $datas = Job::where('user_id', Auth::user()->id)->where('status', '!=', 2)->latest()->paginate(25);
        $title = 'Job List';

        return view('user.pages.job-list', compact('title', 'datas'));
    }

    public function get_recent_job(Request $request)
    {
        $html = '';
        $last_id = '';
        $job_found = 0;
        $job_founds = Job::where('status', 1)->orderBy('created_at', 'DESC')->get();
        foreach ($job_founds as $key=>$job){
            if ($job->worker_need > $job->worker_confirmed && $job->worker_need > complete_work_this_job($job->id) && work_by_me($job->id) == 0 && this_job_for_me($job->id) == 1){
                $job_found = $job_found + 1;
            }
        }
        $jobs = Job::where('status', 1)->orderBy('created_at', 'DESC')->limit(20)->get();
        $jobs_found = $jobs->count();
        $html .= '<h4 class="text-center"><strong>Total Found: '.$job_found.'</strong></h4>';

        if($jobs->count() > 0){
            foreach ($jobs as $key=>$job){
                if ($job->worker_need > $job->worker_confirmed && $job->worker_need > complete_work_this_job($job->id) && work_by_me($job->id) == 0 && this_job_for_me($job->id) == 1){
                    $last_id = $job->id;
                    $html .= '
                            <a href="'.route('job-details', $job->code).'">
                                <div class="border p-1 mb-2 row job-area">
                                    <div class="col-lg-4 col-md-5 col-12 text-dark fw-700 job_title">'. $job->title .'</div>
                                    <div class="col-lg-6 col-md-5 col-9">
                                        <div class="row pt-1 m-0 justify-content-end">
                                            <div class="col-lg-6 col-md-5 col-6">
                                                <div class="progress progress-md p-0">
                                                    <div class="progress-bar progress-bar-animated'; if(this_job_complet_rate($job->id) >= 60){ $html .= 'bg-success'; }elseif(this_job_pending_ratio($job->id) >= 20){ $html .= 'bg-primary'; }else{ $html .= 'text-dark'; } $html .= '" style="width: '. this_job_complet_rate($job->id) .'%">'. this_job_complet_rate($job->id) .'%</div>
                                                </div>
                                                <h6 class="text-center" style="margin:5px 0px 0px 0px;">'. complete_work_this_job($job->id) .' of '. $job->worker_need .'</h6>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-2 col-md-2 col-3 text-center text-success"><h5>$'.number_format((float)$job->each_worker_earn, 4, '.', '').'</h5></div>
                                </div>
                            </a>
                    ';
                }
            }
        }

        return ['html'=>$html, 'last_id'=>$last_id, 'job_found'=>$job_found];
    }

    public function get_heigh_cost_job(Request $request)
    {
        $html = '';
        $last_id = '';
        $job_found = 0;
        $job_founds = Job::where('status', 1)->orderBy('budget', 'DESC')->get();
        foreach ($job_founds as $key=>$job){
            if ($job->worker_need > $job->worker_confirmed && $job->worker_need > complete_work_this_job($job->id) && work_by_me($job->id) == 0 && this_job_for_me($job->id) == 1){
                $job_found = $job_found + 1;
            }
        }
        $jobs = Job::where('status', 1)->orderBy('budget', 'DESC')->limit(20)->get();
        $jobs_found = $jobs->count();
        $html .= '<h4 class="text-center"><strong>Total Found: '.$job_found.'</strong></h4>';

        if($jobs->count() > 0){
            foreach ($jobs as $key=>$job){
                if ($job->worker_need > $job->worker_confirmed && $job->worker_need > complete_work_this_job($job->id) && work_by_me($job->id) == 0 && this_job_for_me($job->id) == 1){
                    $last_id = $job->id;
                    $html .= '
                            <a href="'.route('job-details', $job->code).'">
                                <div class="border p-1 mb-2 row job-area">
                                    <div class="col-lg-4 col-md-5 col-12 text-dark fw-700 job_title">'. $job->title .'</div>
                                    <div class="col-lg-6 col-md-5 col-9">
                                        <div class="row pt-1 m-0 justify-content-end">
                                            <div class="col-lg-6 col-md-5 col-6">
                                                <div class="progress progress-md p-0">
                                                    <div class="progress-bar progress-bar-animated'; if(this_job_approve_ratio($job->id) >= 60){ $html .= 'bg-success'; }elseif(this_job_pending_ratio($job->id) >= 20){ $html .= 'bg-primary'; }else{ $html .= 'text-dark'; } $html .= '" style="width: '. this_job_complet_rate($job->id) .'%">'. this_job_complet_rate($job->id) .'%</div>
                                                </div>
                                                <h6 class="text-center" style="margin:5px 0px 0px 0px;">'. complete_work_this_job($job->id) .' of '. $job->worker_need .'</h6>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-2 col-md-2 col-3 text-center text-success"><h5>$'.number_format((float)$job->each_worker_earn, 4, '.', '').'</h5></div>
                                </div>
                            </a>
                    ';
                }
            }
        }

        return ['html'=>$html, 'last_id'=>$last_id, 'job_found'=>$job_found];
    }

    public function get_job_country_wise(Request $request)
    {
        $country_id = $request->country_id;
        $html = '';
        $last_id = '';
        $job_found = 0;
        $job_founds = Job::where('continent_id', $country_id)->where('status', 1)->get();
        foreach ($job_founds as $key=>$job){
            if ($job->worker_need > $job->worker_confirmed && $job->worker_need > complete_work_this_job($job->id) && work_by_me($job->id) == 0 && this_job_for_me($job->id) == 1){
                $job_found = $job_found + 1;
            }
        }
        $jobs = Job::where('continent_id', $country_id)->where('status', 1)->orderBy('created_at', 'DESC')->limit(20)->get();
        $jobs_found = $jobs->count();
        $html .= '<h4 class="text-center"><strong>Total Found: '.$job_found.'</strong></h4>';

        if($jobs->count() > 0){
            foreach ($jobs as $key=>$job){
                if ($job->worker_need > $job->worker_confirmed && $job->worker_need > complete_work_this_job($job->id) && work_by_me($job->id) == 0 && this_job_for_me($job->id) == 1){
                    $last_id = $job->id;
                    $html .= '
                            <a href="'.route('job-details', $job->code).'">
                                <div class="border p-1 mb-2 row job-area">
                                    <div class="col-lg-4 col-md-5 col-12 text-dark fw-700 job_title">'. $job->title .'</div>
                                    <div class="col-lg-6 col-md-5 col-9">
                                        <div class="row pt-1 m-0 justify-content-end">
                                            <div class="col-lg-6 col-md-5 col-6">
                                                <div class="progress progress-md p-0">
                                                    <div class="progress-bar progress-bar-animated'; if(this_job_approve_ratio($job->id) >= 60){ $html .= 'bg-success'; }elseif(this_job_pending_ratio($job->id) >= 20){ $html .= 'bg-primary'; }else{ $html .= 'text-dark'; } $html .= '" style="width: '. this_job_complet_rate($job->id) .'%">'. this_job_complet_rate($job->id) .'%</div>
                                                </div>
                                                <h6 class="text-center" style="margin:5px 0px 0px 0px;">'. complete_work_this_job($job->id) .' of '. $job->worker_need .'</h6>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-2 col-md-2 col-3 text-center text-success"><h5>$'.number_format((float)$job->each_worker_earn, 4, '.', '').'</h5></div>
                                </div>
                            </a>
                    ';
                }
            }
        }

        return ['html'=>$html, 'last_id'=>$last_id, 'job_found'=>$job_found];
    }

    public function get_job_category_wise(Request $request)
    {
        $category_id = $request->category_id;
        $html = '';
        $last_id = '';
        $job_found = 0;
        $job_founds = Job::where('category_id', $category_id)->where('status', 1)->get();
        foreach ($job_founds as $key=>$job){
            if ($job->worker_need > $job->worker_confirmed && $job->worker_need > complete_work_this_job($job->id) && work_by_me($job->id) == 0 && this_job_for_me($job->id) == 1){
                $job_found = $job_found + 1;
            }
        }
        $jobs = Job::where('category_id', $category_id)->where('status', 1)->orderBy('created_at', 'DESC')->limit(20)->get();
        $jobs_found = $jobs->count();

        $html .= '<h4 class="text-center"><strong>Total Found: '.$job_found.'</strong></h4>';

        if($jobs->count() > 0){
            foreach ($jobs as $key=>$job){
                if ($job->worker_need > $job->worker_confirmed && $job->worker_need > complete_work_this_job($job->id) && work_by_me($job->id) == 0 && this_job_for_me($job->id) == 1){
                    $last_id = $job->id;
                    $html .= '
                            <a href="'.route('job-details', $job->code).'">
                                <div class="border p-1 mb-2 row job-area">
                                    <div class="col-lg-4 col-md-5 col-12 text-dark fw-700 job_title">'. $job->title .'</div>
                                    <div class="col-lg-6 col-md-5 col-9">
                                        <div class="row pt-1 m-0 justify-content-end">
                                            <div class="col-lg-6 col-md-5 col-6">
                                                <div class="progress progress-md p-0">
                                                    <div class="progress-bar progress-bar-animated'; if(this_job_approve_ratio($job->id) >= 60){ $html .= 'bg-success'; }elseif(this_job_pending_ratio($job->id) >= 20){ $html .= 'bg-primary'; }else{ $html .= 'text-dark'; } $html .= '" style="width: '. this_job_complet_rate($job->id) .'%">'. this_job_complet_rate($job->id) .'%</div>
                                                </div>
                                                <h6 class="text-center" style="margin:5px 0px 0px 0px;">'. complete_work_this_job($job->id) .' of '. $job->worker_need .'</h6>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-2 col-md-2 col-3 text-center text-success"><h5>$'.number_format((float)$job->each_worker_earn, 4, '.', '').'</h5></div>
                                </div>
                            </a>
                    ';
                }
            }
        }

        return ['html'=>$html, 'last_id'=>$last_id, 'job_found'=>$job_found];
    }

    public function get_regular_job(Request $request)
    {
        $html = '';
        $jobs = Job::where('status', 1)->where('worker_need', '!=', 'worker_confirmed')->latest()->get();
        // $jobs = Job::where('status', 1)->where('worker_need', '!=', 'worker_confirmed')->latest()->limit(20)->get();

        $job_found = $jobs->count();
        $html .= '<h4 class="text-center"><strong>Total Found: '.job_found().'</strong></h4>';

        if($jobs->count() > 0){
            foreach ($jobs as $key=>$job){
                if ($job->worker_need > $job->worker_confirmed && $job->worker_need > complete_work_this_job($job->id) && work_by_me($job->id) == 0 && this_job_for_me($job->id) == 1){
                    $html .= '
                            <a href="'.route('job-details', $job->code).'">
                                <div class="border p-1 mb-2 row job-area">
                                    <div class="col-lg-4 col-md-5 col-12 text-dark fw-700 job_title">'. $job->title .'</div>
                                    <div class="col-lg-6 col-md-5 col-9">
                                        <div class="row pt-1 m-0 justify-content-end">
                                            <div class="col-lg-6 col-md-5 col-6">
                                                <div class="progress progress-md p-0">
                                                    <div class="progress-bar progress-bar-animated'; if(this_job_approve_ratio($job->id) >= 60){ $html .= 'bg-success'; }elseif(this_job_pending_ratio($job->id) >= 20){ $html .= 'bg-primary'; }else{ $html .= 'text-dark'; } $html .= '" style="width: '. this_job_complet_rate($job->id) .'%">'. this_job_complet_rate($job->id) .'%</div>
                                                </div>
                                                <h6 class="text-center" style="margin:5px 0px 0px 0px;">'. complete_work_this_job($job->id) .' of '. $job->worker_need .'</h6>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-2 col-md-2 col-3 text-center text-success"><h5>$'.number_format((float)$job->each_worker_earn, 4, '.', '').'</h5></div>
                                </div>
                            </a>
                    ';
                }
            }
        }

        return $html;
    }

    public function get_load_more_job(Request $request)
    {
        $last_id = $request->last_id;
        $filter_type = $request->filter_type;
        $category_id = $request->category_id;
        $country_id = $request->country_id;
        $new_last_id = '';
        $html = '';
        if($filter_type == 'category'){
            $jobs = Job::where('category_id', $category_id)->where('id', '<', $last_id)->where('status', 1)->orderBy('created_at', 'DESC')->limit(20)->get();
        }elseif($filter_type == 'country'){
            $jobs = Job::where('continent_id', $country_id)->where('id', '<', $last_id)->where('status', 1)->orderBy('created_at', 'DESC')->limit(20)->get();
        }else{
            $jobs = Job::where('id', '<', $last_id)->where('status', 1)->orderBy('created_at', 'DESC')->limit(20)->get();
        }
        
        $job_found = $jobs->count();

        if($jobs->count() > 0){
            foreach ($jobs as $key=>$job){
                if ($job->worker_need > $job->worker_confirmed && $job->worker_need > complete_work_this_job($job->id) && work_by_me($job->id) == 0 && this_job_for_me($job->id) == 1){
                    $new_last_id = $job->id;
                    $html .= '
                            <a href="'.route('job-details', $job->code).'">
                                <div class="border p-1 mb-2 row job-area">
                                    <div class="col-lg-4 col-md-5 col-12 text-dark fw-700 job_title">'. $job->title .'</div>
                                    <div class="col-lg-6 col-md-5 col-9">
                                        <div class="row pt-1 m-0 justify-content-end">
                                            <div class="col-lg-6 col-md-5 col-6">
                                                <div class="progress progress-md p-0">
                                                    <div class="progress-bar progress-bar-animated'; if(this_job_approve_ratio($job->id) >= 60){ $html .= 'bg-success'; }elseif(this_job_pending_ratio($job->id) >= 20){ $html .= 'bg-primary'; }else{ $html .= 'text-dark'; } $html .= '" style="width: '. this_job_complet_rate($job->id) .'%">'. this_job_complet_rate($job->id) .'%</div>
                                                </div>
                                                <h6 class="text-center" style="margin:5px 0px 0px 0px;">'. complete_work_this_job($job->id) .' of '. $job->worker_need .'</h6>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-2 col-md-2 col-3 text-center text-success"><h5>$'.number_format((float)$job->each_worker_earn, 4, '.', '').'</h5></div>
                                </div>
                            </a>
                    ';
                }
            }
        }

        return ['html'=>$html, 'last_id'=>$new_last_id, 'job_found'=>$job_found];
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // if( Auth::user()->is_ban == 1 && suspend_url_status('job-create') == 1){
        $currenturl = url()->current();
        if( Auth::user()->is_ban == 1 && suspend_url_status($currenturl) == 1){
            return redirect()->route('user.account-suspended');
        }
        
        $continents = Continent::orderBy('id', 'ASC')->get();
        $countrys = Country::orderBy('id', 'ASC')->get();
        $categorys = Category::orderBy('id', 'ASC')->get();
        $job_fee = JobFee::latest()->first();
        $title = 'Add new job';

        return view('user.pages.job-create', compact('continents', 'countrys', 'categorys', 'job_fee', 'title'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'continent' => 'required',
            'category_id' => 'required',
            'sub_category' => 'required',
            'specific_task' => 'required',
            'required_proof' => 'required',
            'worker_need' => 'required',
            'each_worker_earn' => 'required',
            'required_screenshots' => 'required',
            'estimited_day' => 'required',
            'budget' => 'required',
        ]);
        
        // return $request;

        $website = Website::latest()->first();

        $job_cost = $request->budget;
        if(Auth::user()->deposit_balance < $job_cost){
            return redirect()->back()->with('error','You have no sufficient balance for job.');
        }else{
            $user_balance = User::find(Auth::user()->id);
            $user_balance->deposit_balance = $user_balance->deposit_balance - $job_cost;
            $user_balance->save();

            $main_wallet = MainWallet::latest()->first();
            $main_wallet->amount = $main_wallet->amount + $job_cost;
            $main_wallet->save();
        }

        $last_ac = Job::select('id')->latest()->first();
        if (isset($last_ac)) {
            $code = sprintf('%04d', $last_ac->id + 1000001);
        } else {
            $code = sprintf('%04d', 1000001);
        }

        $job = new Job();
        $job->code = $code;
        $job->title = $request->title;
        $job->slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $request->title))).'-'.uniqid();
        $job->continent_id = $request->continent;
        $job->category_id = $request->category_id;
        $job->sub_category = $request->sub_category;
        $job->specific_task = trim(implode("|",$request->specific_task),"|");
        $job->required_proof = $request->required_proof;

        $image = $request->file('thumbnail');
        if ($image) {
            $image_name = Str::random(20);
            $ext = strtolower($image->getClientOriginalExtension());
            $image_full_name = $image_name.'.'.$ext;
            $upload_path = 'backend/img/job/';
            $image_url = $upload_path.$image_full_name;
            $success = $image->move($upload_path, $image_full_name);
            $job->thumbnail_image = $image_url;
        }

        $max_reject = ceil(($request->worker_need * $website->job_work_reject_ratio) / 100);

        $job->worker_need = $request->worker_need;
        $job->max_reject = $max_reject;
        $job->each_worker_earn = $request->each_worker_earn;
        $job->required_screenshots = $request->required_screenshots;
        $job->estimited_day = $request->estimited_day;
        $job->budget = $request->budget;
        $job->user_id = Auth::user()->id;
        $job->save();
        
        $check_job = Job::where('code', $code)->first();
        if($check_job && $request->country_id){
            foreach($request->country_id as $country_id){
                $job_country = new JobCountry();
                $job_country->job_id = $check_job->id;
                $job_country->country_id = $country_id;
                $job_country->save();
            }
        }

        return redirect()->route('user.job-post-done')->with('message','Job added successfully');
    }
    
    public function job_post_done()
    {
        job_delete(Auth::user()->id);
        $datas = Job::where('user_id', Auth::user()->id)->where('status', '!=', 2)->latest()->paginate(25);
        $title = 'Job Post Done';

        return view('user.pages.job-post-done', compact('title', 'datas'));
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
        $continents = Continent::orderBy('id', 'ASC')->get();
        $categorys = Category::orderBy('id', 'ASC')->get();
        $job = Job::find($id);
        $job_countrys = JobCountry::where('job_id', $id)->orderBy('id', 'ASC')->get();
        $job_sub_cats = SubCategory::where('category_id', $job->category_id)->orderBy('id', 'ASC')->get();
        $job_fee = JobFee::latest()->first();
        $title = 'Update job';

        return view('user.pages.job-edit', compact('continents', 'job_countrys', 'job', 'job_fee', 'categorys', 'job_sub_cats', 'title'));
    }

    public function job_work_need_update(Request $request, $id)
    {
        $request->validate([
            'worker' => 'required',
        ]);

        $job_fee = JobFee::latest()->first();
        $job = Job::find($id);

        $total_cost = $job->each_worker_earn * $request->worker;

        if(Auth::user()->deposit_balance < $total_cost){
            return redirect()->back()->with('error','You have no sufficient balance for job.');
        }else{
            $user_balance = User::find(Auth::user()->id);
            $user_balance->deposit_balance = $user_balance->deposit_balance - $total_cost;
            $user_balance->save();
        }

        $job->worker_need = $job->worker_need + $request->worker;
        if($request->estimited_day){
            $job->estimited_day = $job->estimited_day + $request->estimited_day;
        }
        $job->budget = $job->budget + $total_cost;
        $job->save();

        return redirect()->back()->with('message','Job worker updated successfully');
    }

    public function job_boosting_update(Request $request, $id)
    {
        $request->validate([
            'boost_charge' => 'required',
        ]);

        $boost_charge = BoostCharge::find($request->boost_charge);
        $charge = $boost_charge->charge;
        $job = Job::find($id);
        
        if($job->status == 0){
            return redirect()->back()->with('error','This job is not approved yet. After approve you can boost it!');
        }elseif($job->pause == 1){
            return redirect()->back()->with('error','This job is pause mode. After resume you can boost it!');
        }

        if(Auth::user()->deposit_balance < $charge){
            return redirect()->back()->with('error','You have no sufficient balance for job.');
        }else{
            $startTime = date("Y-m-d H:i:s");
            $expired_time = date('Y-m-d H:i:s', strtotime('+'.$boost_charge->duration .'minutes', strtotime($startTime)));
        
            $ck_exist = BoostJob::where('job_id', $id)->first();
            if($ck_exist){
                $boost_job = BoostJob::find($ck_exist->id);
                $boost_job->start_time = $startTime;
                $boost_job->expired_time = $expired_time;
                $boost_job->save();
            }else{
                $boost_job = new BoostJob();
                $boost_job->job_id = $id;
                $boost_job->start_time = $startTime;
                $boost_job->expired_time = $expired_time;
                $boost_job->save();
            }
            
            $user_balance = User::find(Auth::user()->id);
            $user_balance->deposit_balance = $user_balance->deposit_balance - $charge;
            $user_balance->save();

            $main_wallet = MainWallet::latest()->first();
            $main_wallet->amount = $main_wallet->amount + $charge;
            $main_wallet->save();
        }

        return redirect()->back()->with('message','Successfully boost your job.');
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
        $request->validate([
            'worker_need' => 'required',
            'estimited_day' => 'required',
            'budget' => 'required',
        ]);

        $job = Job::find($id);
        $job->worker_need = $request->worker_need;
        $job->estimited_day = $request->estimited_day;
        
        $new_budget = number_format($request->budget, 5, ".", "") - number_format($job->budget, 5, ".", "");
        if(Auth::user()->deposit_balance < $new_budget){
            return redirect()->back()->with('error','You have no sufficient balance for job.');
        }else{
            $user_balance = User::find(Auth::user()->id);
            $user_balance->deposit_balance = $user_balance->deposit_balance - number_format($new_budget, 5, ".", "");
            $user_balance->save();

            $main_wallet = MainWallet::latest()->first();
            $main_wallet->amount = $main_wallet->amount + $new_budget;
            $main_wallet->save();
        }
        
        $job->budget = $request->budget + $new_budget;
        $job->created_at = date('Y-m-d G:i:s');
        $job->save();

        return redirect()->back()->with('message','Job updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function pause_job($id)
    {
        if( Auth::user()->is_ban == 1){
            return redirect()->route('user.account-suspended');
        }
        
        $job = Job::find($id);
        $updated_at = $job->updated_at;
        $job->pause = 1;
        $job->updated_at = $updated_at;
        $job->save();

        return redirect()->back()->with('message','Job Paused successfully');
    }
    
    public function start_job($id)
    {
        if( Auth::user()->is_ban == 1){
            return redirect()->route('user.account-suspended');
        }
        
        $job = Job::find($id);
        $updated_at = $job->updated_at;
        $job->pause = 0;
        $job->updated_at = $updated_at;
        $job->save();

        return redirect()->back()->with('message','Job Started successfully');
    }
    
    public function destroy($id)
    {
        $job = Job::find($id);
        $job->delete_request = 1;
        $job->save();

        return redirect()->route('user.job')->with('message','Job delete request successfully submited.');
    }

    /*
    |--------------------------------------------------------------------------
    | PTC (Paid To Click) Jobs
    |--------------------------------------------------------------------------
    */

    // Per-click price for each package_name option on the PTC job create form.
    private function ptcPackagePrice($packageName)
    {
        $prices = [
            '1' => 0.00020,
            '2' => 0.00025,
            '3' => 0.00035,
            '4' => 0.0005,
            '5' => 0.0007,
            '6' => 0.00085,
            '7' => 0.001,
        ];

        return $prices[$packageName] ?? null;
    }

    // Required view/wait time (seconds) for each package -- higher-paying
    // packages require a longer view. To change these numbers later, just
    // edit this array (and the matching option labels in ptc-job-create.blade.php).
    private function ptcPackageWaitTime($packageName)
    {
        $waitTimes = [
            '1' => 1,
            '2' => 2,
            '3' => 3,
            '4' => 4,
            '5' => 5,
            '6' => 6,
            '7' => 10,
        ];

        return $waitTimes[$packageName] ?? 10;
    }

    public function ptcAdd()
    {
        return view('user.pages.ptc-job-create');
    }

    public function ptcAddStore(Request $request)
    {
        $request->validate([
            'ptc_title'         => 'required|string|max:255',
            'ptc_jobLink'       => 'required|url',
            'package_name'      => 'required|in:1,2,3,4,5,6,7',
            'ptc_worker_needed' => 'required|integer|min:1',
            'ptc_expire_day'    => 'required|date|after_or_equal:today',
            'ptc_job_details'   => 'nullable|string',
        ]);

        $price = $this->ptcPackagePrice($request->package_name);
        $waitTime = $this->ptcPackageWaitTime($request->package_name);
        $totalCost = round($price * $request->ptc_worker_needed * 1.03, 5);

        $user = User::find(Auth::id());
        if ($user->deposit_balance < $totalCost) {
            return redirect()->back()->with('error', 'You have no sufficient balance for this PTC job.');
        }

        $user->deposit_balance = $user->deposit_balance - $totalCost;
        $user->save();

        $main_wallet = MainWallet::latest()->first();
        if ($main_wallet) {
            $main_wallet->amount = $main_wallet->amount + $totalCost;
            $main_wallet->save();
        }

        ptc_job::create([
            'ptc_post_user_id'  => Auth::id(),
            'ptc_title'         => $request->ptc_title,
            'ptc_jobLink'       => $request->ptc_jobLink,
            'ptc_each_earn'     => $price,
            'ptc_worker_needed' => $request->ptc_worker_needed,
            'ptc_clicked'       => 0,
            'ptc_wait_time'     => $waitTime,
            'ptc_expire_day'    => $request->ptc_expire_day,
            'ptc_job_details'   => $request->ptc_job_details,
            // Goes live immediately -- no admin approval needed. Admin can
            // still pause/reject it afterward from the PTC moderation pages.
            'ptc_status'        => 'running',
        ]);

        return redirect()->route('user.myPostedJobHistory')->with('message', 'PTC job posted and is now running.');
    }

    public function ptcEdit($id)
    {
        $job = ptc_job::where('id', $id)->where('ptc_post_user_id', Auth::id())->first();
        if (!$job) {
            abort(404);
        }

        return view('user.pages.ptc-job-edit', compact('job'));
    }

    public function ptcEditStore(Request $request)
    {
        $request->validate([
            'id'              => 'required|exists:ptc_job,id',
            'ptc_title'       => 'required|string|max:255',
            'ptc_jobLink'     => 'required|url',
            'ptc_expire_day'  => 'required|date',
            'ptc_job_details' => 'nullable|string',
            'ptc_status'      => 'required|in:pending,review,running',
        ]);

        $job = ptc_job::where('id', $request->id)->where('ptc_post_user_id', Auth::id())->first();
        if (!$job) {
            abort(404);
        }

        $job->ptc_title = $request->ptc_title;
        $job->ptc_jobLink = $request->ptc_jobLink;
        $job->ptc_expire_day = $request->ptc_expire_day;
        $job->ptc_job_details = $request->ptc_job_details;

        // No admin approval gate -- an edited job just goes straight back
        // to running (whether it was being renewed after expiry or fixed
        // up after an admin rejection).
        $job->ptc_status = 'running';

        $job->save();

        return redirect()->route('user.myPostedJobHistory')->with('message', 'PTC job updated successfully.');
    }

    public function ptcList()
    {
        // Jobs are click-once-per-day per worker (not once ever) -- so only
        // today's claims exclude a job from the list; it reappears tomorrow.
        $claimedTodayJobIds = ptc_earn_history::where('ptc_worker_id', Auth::id())
            ->whereDate('created_at', now()->toDateString())
            ->pluck('ptc_job_id');

        $jobs = ptc_job::where('ptc_status', 'running')
            ->where('ptc_post_user_id', '!=', Auth::id())
            ->where('ptc_expire_day', '>=', now()->toDateString())
            ->whereColumn('ptc_clicked', '<', 'ptc_worker_needed')
            ->whereNotIn('id', $claimedTodayJobIds)
            ->latest()
            ->get();

        return view('user.pages.ptc-job-list', compact('jobs'));
    }

    public function ptcEarned()
    {
        $historys = ptc_earn_history::where('ptc_worker_id', Auth::id())->latest()->get();

        return view('user.pages.ptc-earn-history', compact('historys'));
    }

    public function myRunning()
    {
        $jobs = ptc_job::where('ptc_post_user_id', Auth::id())->where('ptc_status', 'running')->latest()->get();

        return view('user.pages.ptc-my-running-job', compact('jobs'));
    }

    public function myPostedJobHistory()
    {
        $jobs = ptc_job::where('ptc_post_user_id', Auth::id())->latest()->get();

        return view('user.pages.ptc-my-job-history', compact('jobs'));
    }

    public function jobSeeker(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:ptc_job,id',
        ]);

        $job = ptc_job::find($request->id);

        if ($job->ptc_post_user_id == Auth::id()) {
            return 'You cannot click your own job.';
        }

        if ($job->ptc_status !== 'running') {
            return 'This job is no longer available.';
        }

        if ($job->ptc_clicked >= $job->ptc_worker_needed) {
            return 'This job has already reached its click limit.';
        }

        $claimedToday = ptc_earn_history::where('ptc_job_id', $job->id)
            ->where('ptc_worker_id', Auth::id())
            ->whereDate('created_at', now()->toDateString())
            ->exists();

        if ($claimedToday) {
            return 'You have already claimed this job today. Come back tomorrow.';
        }

        $job->increment('ptc_clicked');
        if ($job->ptc_clicked >= $job->ptc_worker_needed) {
            $job->ptc_status = 'done';
            $job->save();
        }

        ptc_earn_history::create([
            'ptc_worker_id' => Auth::id(),
            'ptc_job_id'    => $job->id,
        ]);

        $user = User::find(Auth::id());
        $user->earning_balance = $user->earning_balance + $job->ptc_each_earn;
        $user->save();

        return 'Yes, you got the balance: $' . number_format($job->ptc_each_earn, 5);
    }
}
