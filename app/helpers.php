<?php

use App\Models\Admin\Advertisement;
use App\Models\Admin\Category;
use App\Models\Admin\Country;
use App\Models\Admin\Continent;
use App\Models\Admin\ContinentCountry;
use App\Models\Admin\Deposit;
use App\Models\Admin\DepositDocumentData;
use App\Models\Admin\DepositAccount;
use App\Models\Admin\DepositAccountDocument;
use App\Models\Admin\Headline;
use App\Models\Admin\LocationZone;
use App\Models\Admin\MainWallet;
use App\Models\Admin\SubCategory;
use App\Models\Admin\DollarRate;
use App\Models\Admin\UserMessage;
use App\Models\Admin\DepositFee;
use App\Models\Admin\UserVerifyDocumentData;
use App\Models\Admin\Website;
use App\Models\BoostCategory;
use App\Models\Admin\Aboutus;
use App\Models\BoostSubCategory;
use App\Models\DepositDocument;
use App\Models\GoogleAd;
use App\Models\Admin\PaidAdRate;
use App\Models\Job;
use App\Models\JobCountry;
use App\Models\Admin\JobFee;
use App\Models\BoostCharge;
use App\Models\BoostJob;
use App\Models\JobWork;
use App\Models\JobHide;
use App\Models\Policy;
use App\Models\User;
use App\Models\Withdraw;
use App\Models\WithdrawDocumentData;
use App\Models\WithdrawMethod;
use App\Models\WithdrawMethodDocument;
use App\Models\Admin\AdminType;
use App\Models\Admin\Module;
use App\Models\Admin\ScreenShootCharge;
use App\Models\Admin\SubModule;
use App\Models\Admin\AdminPermission;

use App\Models\Admin\SpinSetting;
use App\Models\Admin\UserDailySpin;

use App\Models\SupportTicket;
use App\Models\SupportTicketData;
use App\Models\InvestmentPackage;
use App\Models\InvestmentPackageBook;

use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use \DateTime;

function about_us(){
    return Aboutus::latest()->first();
}

function minimum_deposit(){
    $data = 0;
    $depositFee = DepositFee::latest()->first();
    if($depositFee){
        $data = $depositFee->minimum;
    }
    return $data;
}

function getCreatedAtAttribute($date) {
    return Carbon::createFromFormat('Y-m-d H:i:s', $date)->format('H:i:s');
}

function spin_setting(){
    return SpinSetting::latest()->first();
}

function user_rating($user_id){
    $rating = 0;
    $total_rated = JobWork::where('user_id', $user_id)->where('is_rated', 1)->count();
    $total_rating = JobWork::where('user_id', $user_id)->where('is_rated', 1)->sum('rating');
    if($total_rating > 0){
        $rating = $total_rating / $total_rated;
    }
    return number_format($rating, 1);
}

function support_ticket_data($ticket_id){
    return SupportTicketData::where('ticket_id', $ticket_id)->latest()->get();
}

function find_support_ticket($id){
    return SupportTicket::find($id);
}

function today_user_spin($user_id){
    return UserDailySpin::where('user_id', $user_id)->whereDate('created_at', Carbon::today())->count();
}

function time_elapsed_string($datetime, $full = false) {
    $now = new DateTime;
    $ago = new DateTime($datetime);
    $diff = $now->diff($ago);

    $diff->w = floor($diff->d / 7);
    $diff->d -= $diff->w * 7;

    $string = array(
        'y' => 'year',
        'm' => 'month',
        'w' => 'week',
        'd' => 'day',
        'h' => 'hour',
        'i' => 'minute',
        's' => 'second',
    );
    foreach ($string as $k => &$v) {
        if ($diff->$k) {
            $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
        } else {
            unset($string[$k]);
        }
    }    
    if (!$full) $string = array_slice($string, 0, 1);
    return $string ? implode(', ', $string) . ' ago' : 'just now';
} 

function screenshoot_charge(){
    $fee = 0;
    $data = ScreenShootCharge::latest()->first();
    if($data->status == 1){
        $fee = $data->fee;
    }
    return $fee;
}

function find_job_fee(){
    return JobFee::latest()->first();
}

function min_job_fee(){
    $fee = 0;
    $data = JobFee::latest()->first();
    if($data){
        $fee = $data->min_fee;
    }
    return $fee;
}

function boost_charges(){
    return BoostCharge::orderBy('id', 'ASC')->get();
}

function has_complete_job(){
    $result = 0;
    $jobs = Job::where('user_id', Auth::user()->id)->whereColumn('worker_need', 'worker_confirmed')->get();
    if($jobs->count() > 0){
        $result = 1;
    }
    return $result;
}

function boost_active($job_id){
    $startTime = date("Y-m-d H:i:s");
    $result = 0;
    $boost_job = BoostJob::where('job_id', $job_id)->where('expired_time', '>=', $startTime)->first();
    if($boost_job){
        $result = 1;
    }
    return $result;
}

function job_ready_for_boost($job_id){
    $result = 1;
    $boost_job = BoostJob::where('job_id', $job_id)->first();
    if($boost_job){
        $startTime = strtotime(date("Y-m-d H:i:s"));
        $diff = round(abs(strtotime($boost_job->expired_time) - $startTime) / 60,2);
        if(find_job_fee()->boost_interval > $diff){
            $result = 0;
        }
    }
    return $result;
}

function remain_interval_for_boost($job_id){
    $result = 0;
    $boost_job = BoostJob::where('job_id', $job_id)->first();
    if($boost_job){
        $startTime = strtotime(date("Y-m-d H:i:s"));
        $diff = round(abs(strtotime($boost_job->expired_time) - $startTime) / 60,2);
        // if(find_job_fee()->boost_interval > $diff){
        //     $result = find_job_fee()->boost_interval - $diff;
        // }
        
        if($diff > 0){
            $result = $diff;
        }
    }
    return $result;
}

function boost_active_time($job_id){
    $result = 0;
    $boost_job = BoostJob::where('job_id', $job_id)->first();
    if($boost_job){
        $startTime = strtotime(date("Y-m-d H:i:s"));
        $diff = round(abs(strtotime($boost_job->expired_time) - $startTime) / 60,2);
        if(find_job_fee()->boost_interval > $diff){
            $result = find_job_fee()->boost_interval - $diff;
        }
    }
    $startTime = date("Y-m-d H:i:s");
    $acive_time = date('Y-m-d H:i:s', strtotime('+'.$result .'minutes', strtotime($startTime)));
    
    return $acive_time;
}

function boost_jobs(){
    $startTime = date("Y-m-d H:i:s");
    return BoostJob::where('expired_time', '>=', $startTime)->orderBy('start_time', 'ASC')->get();
}

function hide_job_for_country($job_id){
    return JobCountry::where('job_id', $job_id)->orderBy('country_id', 'ASC')->get();
}

function this_job_for_me($job_id){
    $result = 1;
    $data = JobCountry::where('job_id', $job_id)->where('country_id', Auth::user()->country)->first();
    if($data){
        $result = 0;
    }
    return $result;
}

function main_category($id){
    $category = Category::find($id);
    if($category){
        echo $category->name;
    }else{
        echo 'N/A';
    }
}

function main_boost_category($id){
    $category = BoostCategory::find($id);
    if($category){
        echo $category->name;
    }else{
        echo 'N/A';
    }
}

function sub_boost_category($id){
    $category = BoostSubCategory::find($id);
    if($category){
        echo $category->id.'-'.$category->name;
    }else{
        echo 'N/A';
    }
}

function account_name($id){
    $d_account = DepositAccount::find($id);
    if($d_account){
        echo $d_account->name;
    }else{
        echo 'N/A';
    }
}

function deposit_account_documents($account_id){
    return DepositAccountDocument::where('account_id', $account_id)->orderBy('id', 'ASC')->get();
}

function deposit_document_datas($deposit_id){
    return DepositDocumentData::where('deposit_id', $deposit_id)->orderBy('id', 'ASC')->get();
}

function withdraw_method_name($id){
    $d_account = WithdrawMethod::find($id);
    if($d_account){
        echo $d_account->name;
    }else{
        echo 'N/A';
    }
}

function withdraw_method_documents($account_id){
    return WithdrawMethodDocument::where('account_id', $account_id)->orderBy('id', 'ASC')->get();
}

function withdraw_document_datas($withdraw_id){
    return WithdrawDocumentData::where('withdraw_id', $withdraw_id)->orderBy('id', 'ASC')->get();
}

function user_verify_document_datas($user_id){
    return UserVerifyDocumentData::where('user_id', $user_id)->orderBy('id', 'ASC')->get();
}

function country($id){
    $country = Country::find($id);
    if($country){
        return $country->name;
    }else{
        return 'N/A';
    }
}

function continent($id){
    $data = Continent::find($id);
    if($data){
        return $data->name;
    }else{
        return 'N/A';
    }
}

function continent_country($continent_id){
    return ContinentCountry::where('continent_id', $continent_id)->orderBy('country_id', 'ASC')->get();
}

function country_continent($country_id){
    return ContinentCountry::where('country_id', $country_id)->orderBy('continent_id', 'ASC')->get();
}

function user_verify_data($user_id, $label, $type){
    return UserVerifyDocumentData::where('user_id', $user_id)->where('label', $label)->where('type', $type)->first();
}

function website_title(){
    $website = Website::latest()->first();
    if($website){
        echo $website->title;
    }else{
        echo 'N/A';
    }
}

function website_logo(){
    $website = Website::latest()->first();
    if($website){
        return $website->logo;
    }else{
        return 'N/A';
    }
}

function website_favicon(){
    $website = Website::latest()->first();
    if($website){
        return $website->favicon;
    }else{
        return 'N/A';
    }
}

function website_phone(){
    $website = Website::latest()->first();
    if($website){
        echo $website->phone;
    }else{
        echo 'N/A';
    }
}

function website_address(){
    $website = Website::latest()->first();
    if($website){
        echo $website->address;
    }else{
        echo 'N/A';
    }
}

function website_email(){
    $website = Website::latest()->first();
    if($website){
        echo $website->email;
    }else{
        echo 'N/A';
    }
}

function website_description(){
    $website = Website::latest()->first();
    if($website){
        echo $website->description;
    }else{
        echo 'N/A';
    }
}

function accepted_task_note(){
    $website = Website::latest()->first();
    if($website){
        echo $website->accepted_task_note;
    }else{
        echo 'N/A';
    }
}

function complete_task_note(){
    $website = Website::latest()->first();
    if($website){
        echo $website->complete_task_note;
    }else{
        echo 'N/A';
    }
}

function referral_notice(){
    $website = Website::latest()->first();
    if($website){
        echo $website->referral_notice;
    }else{
        echo 'N/A';
    }
}

function website_icon(){
    $website = Website::latest()->first();
    return $website->icon;
}

function website_link(){
    $website = Website::latest()->first();
    return $website->link;
}

function website_info(){
    return Website::latest()->first();
}

function site_info(){
    return Website::latest()->first();
}

function user_name($id){
    $user = User::find($id);
    if($user){
        echo $user->name;
    }else{
        echo 'N/A';
    }
}

function user_code($id){
    $user = User::find($id);
    if($user){
        echo $user->code;
    }else{
        echo 'N/A';
    }
}

function user_phone($id){
    $user = User::find($id);
    if($user){
        echo $user->phone;
    }else{
        echo 'N/A';
    }
}

function user_activity($id){
    $user = User::find($id);
    if($user){
        return $user->activity;
    }else{
        return 0;
    }
}

function user_image($id){
    $user = User::find($id);
    if($user){
        if($user->image != NULL){
            return $user->image;
        }else{
            return 'frontend/img/skmj-user.jpg';
        }
    }else{
        return 'frontend/img/skmj-user.jpg';
    }
}

function location_zone($id){
    $location = LocationZone::find($id);
    if($location){
        echo $location->name;
    }else{
        echo 'N/A';
    }
}

function category($id){
    $category = Category::find($id);
    if($category){
        echo $category->name;
    }else{
        echo 'N/A';
    }
}

function find_job($id){
    return Job::find($id);
}

function job_title($id){
    $job = Job::find($id);
    if($job){
        echo $job->title;
    }else{
        echo 'This job is deleted';
    }
}

function job_earning($id){
    $result = 0;
    $job = Job::find($id);
    if($job){
        $result = $job->each_worker_earn;
    }
    return $result;
}

function job_owner($id){
    $job = Job::find($id);
    if($job){
        $user = User::find($job->user_id);
        if($user){
            echo $user->name;
        }else{
            echo 'N/A';
        }
    }else{
        echo 'This job is deleted';
    }
}

function this_work_for_my_job($id){
    $job = Job::find($id);
    if($job){
        if($job->user_id == Auth::user()->id){
            return 1;
        }else{
            return 0;
        }
    }else{
        return 0;
    }
}

function sub_category($id){
    $category = SubCategory::find($id);
    if($category){
        echo $category->name;
    }else{
        echo 'N/A';
    }
}

function sub_categorys($category_id){
    return SubCategory::where('category_id', $category_id)->orderBy('id', 'ASC')->get();
}

function specific_task($id){
    $html = '';
    $job = Job::find($id);
    if($job){
        $tasks = explode("|",$job->specific_task);
        if($tasks){
            foreach($tasks as $key=>$task){
                $html .= ($key+1).'. '.$task.'</br>';
            }
        }
    }else{
        $html .= 'This job is deleted';
    }

    echo $html;
}

function headlines(){
    return Headline::latest()->get();
}

function countrys(){
    return Country::latest()->get();
}

function user_total_job($id){
    return Job::where('user_id', $id)->count();
}

function total_attend_work($id){
    return JobWork::where('user_id', $id)->count();
}

function user_complete_job($id){
    return JobWork::where('user_id', $id)->count();
}

function user_complete_job_pending($id){
    return JobWork::where('user_id', $id)->where('status', 0)->count();
}

function user_complete_job_approve($id){
    return JobWork::where('user_id', $id)->where('status', 1)->count();
}

function user_complete_job_reject($id){
    return JobWork::where('user_id', $id)->where('status', 2)->orWhere('status', 3)->count();
}

function complete_work_this_job($id){
    return JobWork::where('job_id', $id)->where('status', '!=', 2)->count();
    // return JobWork::where('job_id', $id)->where('status', 1)->count();
}


function user_total_pending_job($id){
    return Job::where('user_id', $id)->where('status', 0)->count();
}
function user_total_approve_job($id){
    return Job::where('user_id', $id)->where('status', 1)->count();
}
function user_total_reject_job($id){
    return Job::where('user_id', $id)->where('status', 2)->count();
}
function user_total_jobe_delete_request($id){
    return Job::where('user_id', $id)->where('delete_request', 1)->count();
}

function work_approve_ratio($user_id){
    $ratio = 0;
    $total_attempt = user_complete_job($user_id);
    $total_approve = user_complete_job_approve($user_id);
    if($total_attempt > 0){
        $ratio = ($total_approve * 100) / $total_attempt;
    }
    return $ratio;
}

function work_pending_ratio($user_id){
    $ratio = 0;
    $total_attempt = user_complete_job($user_id);
    $total_pending = user_complete_job_pending($user_id);
    if($total_attempt > 0){
        $ratio = ($total_pending * 100) / $total_attempt;
    }
    return $ratio;
}

function work_reject_ratio($user_id){
    $ratio = 0;
    $total_attempt = user_complete_job($user_id);
    $total_reject = user_complete_job_reject($user_id);
    if($total_attempt > 0){
        $ratio = ($total_reject * 100) / $total_attempt;
    }
    return $ratio;
}

function work_satisfication($user_id){
    $approval_ratio = 0;
    $total_attempt = user_complete_job($user_id);
    $total_pending = user_complete_job_pending($user_id);
    $total_reject = user_complete_job_reject($user_id);
    $total_approve = user_complete_job_approve($user_id);
    if($total_attempt > 0){
        $total_activity_work = $total_attempt - ($total_pending + $total_reject);
        if($total_activity_work > 0){
            $approval_ratio = ($total_approve * 100) / $total_activity_work;
        }
    }
    return $approval_ratio;
}


function job_approve_ratio($user_id){
    $ratio = 0;
    $total_attempt = user_total_job($user_id);
    $total_approve = user_total_approve_job($user_id);
    if($total_attempt > 0){
        $ratio = ($total_approve * 100) / $total_attempt;
    }
    return $ratio;
}

function job_pending_ratio($user_id){
    $ratio = 0;
    $total_attempt = user_total_job($user_id);
    $total_pending = user_total_pending_job($user_id);
    if($total_attempt > 0){
        $ratio = ($total_pending * 100) / $total_attempt;
    }
    return $ratio;
}

function job_reject_ratio($user_id){
    $ratio = 0;
    $total_attempt = user_total_job($user_id);
    $total_reject = user_total_reject_job($user_id);
    if($total_attempt > 0){
        $ratio = ($total_reject * 100) / $total_attempt;
    }
    return $ratio;
}

function job_satisfication($user_id){
    $approval_ratio = 0;
    $total_attempt = user_total_job($user_id);
    $total_pending = user_total_pending_job($user_id);
    $total_reject = user_total_reject_job($user_id);
    $total_approve = user_total_approve_job($user_id);
    if($total_attempt > 0){
        $total_activity_work = $total_attempt - ($total_pending + $total_reject);
        if($total_activity_work > 0){
            $approval_ratio = ($total_approve * 100) / $total_activity_work;
        }
    }
    return $approval_ratio;
}

function work_by_me($id){
    $work = JobWork::where('job_id', $id)->where('user_id', Auth::user()->id)->first();
    if($work){
        return 1;
    }else{
        return 0;
    }
}

function work_for_me($id){
    $work = JobHide::where('job_id', $id)->where('user_id', Auth::user()->id)->first();
    if($work){
        return 0;
    }else{
        return 1;
    }
}

function totle_work_done(){
    return JobWork::where('status', 1)->count();
}

function pending_work_for_job($id){
    return JobWork::where('job_id', $id)->where('status', 0)->count();
}

function complete_work_for_job($id){
    return JobWork::where('job_id', $id)->where('status', 1)->count();
}

function reject_work_for_job($id){
    return JobWork::where('job_id', $id)->where('status', 2)->count();
}

function this_job_complet_rate($id){
    $complete = complete_work_this_job($id);
    $job = Job::find($id);
    $need_complete = $job->worker_need;
    return $complete_rate = ceil((100 * $complete)/$need_complete);
}

function this_job_total_work($id){
    return JobWork::where('job_id', $id)->count();
}

function this_job_pending_ratio($id){
    $result = 0;
    $job = Job::find($id);
    $need_complete = $job->worker_need;
    $pending = pending_work_for_job($id);
    if($need_complete > 0){
        $result = ceil((100 * $pending)/$need_complete);
    }
    return $result;
}

function this_job_approve_ratio($id){
    $result = 0;
    $job = Job::find($id);
    $need_complete = $job->worker_need;
    $complete = complete_work_for_job($id);
    if($need_complete > 0){
        $result = ceil((100 * $complete)/$need_complete);
    }
    return $result;
}

function this_job_reject_ratio($id){
    $result = 0;
    $job = Job::find($id);
    $need_complete = $job->worker_need;
    $reject = reject_work_for_job($id);
    if($need_complete > 0){
        $result = ceil((100 * $reject)/$need_complete);
    }
    return $result;
}

function ad_banner(){
    $today = date('Y-m-d');
    return $ads = Advertisement::where('exp_date', '>=', $today)->where('approval', 1)->inRandomOrder()->get();
}

function system_policy(){
    return Policy::latest()->get();
}

function total_pending_job(){
    return Job::where('status', 0)->count();
}

function total_reject_job(){
    return Job::where('status', 2)->count();
}

function total_job_delete_requests(){
    return Job::where('delete_request', 1)->count();
}

function total_complete_job(){
    return Job::where('status', 1)->whereColumn('worker_need', '<=', 'worker_confirmed')->count();
}

function total_job(){
    return Job::where('status', 1)->whereColumn('worker_need', '>', 'worker_confirmed')->count();
}

function total_deposit(){
    return Deposit::where('approval', '!=', 2)->sum('amount');
}

function total_pending_deposit(){
    return Deposit::where('approval', 0)->count();
}

function total_withdraw(){
    return Withdraw::where('approval', '!=', 2)->sum('amount');
}

function total_pending_withdraw(){
    return Withdraw::where('approval', 0)->count();
}

function total_pending_ads(){
    return Advertisement::where('approval', 0)->count();
}

function total_approval_ads(){
    $today = date('Y-m-d');
    return Advertisement::where('approval', 1)->where('exp_date', '>=', $today)->count();
}

function total_expired_ads(){
    $today = date('Y-m-d');
    return Advertisement::where('exp_date', '<', $today)->count();
}

function find_user($id){
    return User::find($id);
}

function total_user(){
    return User::where('role_id', 3)->count();
}

function total_user_balance(){
    return User::where('role_id', 3)->sum('earning_balance');
}

function total_admin_balance(){
    $main_wallet = MainWallet::latest()->first();
    return $main_wallet->amount;
}

function user_message_seen($user_id){
    return UserMessage::where('user_id', $user_id)->where('seen', 0)->count();
}

function job_delete($user_id){
    $jobs = Job::where('user_id', $user_id)->latest()->get();
    foreach ($jobs as $key=>$job){
        if ($job->worker_confirmed == $job->worker_need){
            $check_job_work = JobWork::where('job_id', $job->id)->count();
            if($check_job_work <= 0){
                $d_job = Job::find($job->id);
                $job->delete_request = 1;
                $job->save();
            }
        }
    }

    return 'Done';
}


function job_found(){
    $data = 0;
    $jobs = Job::where('status', 1)->get();
    foreach ($jobs as $key=>$job){
        if ($job->worker_need > $job->worker_confirmed ){
            $data = $data + 1;
        }
    }

    return $data;
}

function google_head_ad(){
    return GoogleAd::where('position', 'Head')->first();
}

function google_body_ad(){
    return GoogleAd::where('position', 'Body')->first();
}

function google_footer_ad(){
    return GoogleAd::where('position', 'Footer')->first();
}

function all_latest_notification($user_id){
    return UserMessage::where('user_id', $user_id)->latest()->get();
}

function latest_notification($user_id){
    return UserMessage::where('user_id', $user_id)->where('seen', 0)->latest()->get();
}

function dollar_rate(){
    return DollarRate::latest()->first();
}

function paid_ads_rate(){
    return PaidAdRate::orderBy('id', 'ASC')->get();
}

function deposit_documents(){
    return DepositDocument::orderBy('id', 'ASC')->get();
}

function sub_modules($module_id){
    return SubModule::where('main_module', $module_id)->orderBy('serial', 'ASC')->get();
}

function admin_module_permission($admin_type, $module_id){
    return AdminPermission::where('admin_type', $admin_type)->where('module_id', $module_id)->first();
}

function admin_sub_module_permission($admin_type, $module_id, $sub_module_id){
    return AdminPermission::where('admin_type', $admin_type)->where('module_id', $module_id)->where('sub_module_id', $sub_module_id)->first();
}

if (!function_exists('linkify')) {
    // Escapes plain text then turns bare http(s) URLs into clickable links
    // that open in a new tab -- used to render community post content so a
    // link typed inside the text works like Facebook.
    function linkify($text){
        if ($text === null || $text === '') {
            return '';
        }

        $escaped = e($text);

        return preg_replace_callback('/(https?:\/\/[^\s<]+)/i', function ($m) {
            $url = rtrim($m[1], '.,!?)]');
            $trailing = substr($m[1], strlen($url));
            return '<a href="' . $url . '" target="_blank" rel="noopener noreferrer">' . $url . '</a>' . $trailing;
        }, $escaped);
    }
}

if (!function_exists('wu_service_image')) {
    // Used throughout the marketplace (WuServiceController + views) to resolve a
    // service's stored image path to a public URL, falling back to a default image.
    function wu_service_image($path){
        if (!$path || trim($path) === '') {
            return asset('frontend/assets/img/default-service.svg');
        }

        $path = ltrim($path, '/');

        if (\Illuminate\Support\Str::startsWith($path, ['http://', 'https://'])) {
            return $path;
        }

        if (file_exists(base_path($path))) {
            return asset($path);
        }

        return asset('frontend/assets/img/default-service.svg');
    }
}

if (!function_exists('wu_marketplace_unread_inquiries')) {
    // Count of unread pre-order inquiries addressed to the logged-in user, shown as a
    // sidebar badge next to "Marketplace".
    function wu_marketplace_unread_inquiries(){
        if (!auth()->check()) {
            return 0;
        }
        return \Illuminate\Support\Facades\DB::table('wu_service_inquiries')
            ->where('receiver_id', auth()->id())
            ->where('is_seen', 0)
            ->count();
    }
}

if (!function_exists('wu_marketplace_unread_order_messages')) {
    // Count of unread order-chat messages addressed to the logged-in user, shown as a
    // sidebar badge next to "Marketplace".
    function wu_marketplace_unread_order_messages(){
        if (!auth()->check()) {
            return 0;
        }
        return \Illuminate\Support\Facades\DB::table('wu_service_messages')
            ->where('receiver_id', auth()->id())
            ->where('is_seen', 0)
            ->count();
    }
}








