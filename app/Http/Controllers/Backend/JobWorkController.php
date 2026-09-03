<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Admin\Role;
use App\Models\Admin\Website;
use App\Models\Job;
use App\Models\JobWork;
use App\Models\User;
use App\Models\ptc_job;
use Illuminate\Http\Request;

class JobWorkController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $datas = JobWork::where('trash', 0)->latest()->get();
        $website = Website::latest()->first();
        $title = 'Worked Job List';

        return view('backend.pages.job-manage.job-work', compact('title', 'website', 'datas'));
    }

    public function job_work_approve($id)
    {
        $job_work = JobWork::find($id);

        $job = Job::find($job_work->job_id);

        $user = User::find($job_work->user_id);
        $user->earning_balance = $user->earning_balance + $job->each_worker_earn;

        $website = Website::latest()->first();
        if($website->referral_earning_commission > 0){
            $earning_commission = ($website->referral_earning_commission * $job->each_worker_earn) / 100;

            $refered_by = User::find($user->rfered_by);
            $refered_by->earning_balance = $refered_by->earning_balance + $earning_commission;
            $refered_by->save();

            $user->earning_commision_from_refer = $user->earning_commision_from_refer + $earning_commission;
        }

        $user->save();

        $job->worker_confirmed = $job->worker_confirmed + 1;

        $job_work->status = 1;
        $job_work->save();

        $job->save();

        return redirect()->back()->with('message','Successfully approved this job!');
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
        $job = JobWork::find($id);
        $job->delete();

        return redirect()->back()->with('message','Successfully deleted this job!');
    }

    /*
    |--------------------------------------------------------------------------
    | PTC (Paid To Click) Job Moderation
    |--------------------------------------------------------------------------
    | All of these share the same backend.pages.ptc-job.running-job view,
    | which switches its heading/columns via Route::is().
    */

    private function ptcAdminView($jobs)
    {
        $website = Website::latest()->first();
        $roles = Role::all()->where('id', '!=', '3');

        return view('backend.pages.ptc-job.running-job', compact('jobs', 'website', 'roles'));
    }

    public function ptcRunningAdmin()
    {
        $jobs = ptc_job::where('ptc_status', 'running')->latest()->paginate(15);
        return $this->ptcAdminView($jobs);
    }

    public function ptcExpiredAdmin()
    {
        $jobs = ptc_job::where('ptc_expire_day', '<', now()->toDateString())->latest()->paginate(15);
        return $this->ptcAdminView($jobs);
    }

    public function ptcAdminPending()
    {
        $jobs = ptc_job::where('ptc_status', 'adminPending')->latest()->paginate(15);
        return $this->ptcAdminView($jobs);
    }

    public function ptcDeleteList()
    {
        $jobs = ptc_job::where('ptc_status', 'deleted')->latest()->paginate(15);
        return $this->ptcAdminView($jobs);
    }

    public function ptcDeleteRequest()
    {
        $jobs = ptc_job::where('ptc_status', 'req_delete')->latest()->paginate(15);
        return $this->ptcAdminView($jobs);
    }

    public function ptcRejectList()
    {
        $jobs = ptc_job::where('ptc_status', 'reject')->latest()->paginate(15);
        return $this->ptcAdminView($jobs);
    }

    public function ptcJobHistoryAdmin()
    {
        $jobs = ptc_job::latest()->paginate(15);
        return $this->ptcAdminView($jobs);
    }

    public function ptcRunningAdminStore(Request $request)
    {
        $request->validate([
            'id'                => 'required|exists:ptc_job,id',
            'ptc_status'        => 'required|in:running,pending,review,reject,adminPending,req_delete,deleted',
            'ptc_reject_notice' => 'nullable|string',
        ]);

        $job = ptc_job::find($request->id);
        $job->ptc_status = $request->ptc_status;
        $job->ptc_reject_notice = $request->ptc_reject_notice;
        $job->save();

        return redirect()->back()->with('message', 'PTC job status updated successfully!');
    }
}
