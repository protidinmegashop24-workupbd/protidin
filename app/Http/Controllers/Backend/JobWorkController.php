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

        if ($job_work->status == 1) {
            return redirect()->back()->with('message', 'This job work is already approved and paid.');
        }

        $job = Job::find($job_work->job_id);

        $user = User::find($job_work->user_id);
        $user->earning_balance = $user->earning_balance + $job->each_worker_earn;
        $user->referral_activated = 1;

        $website = Website::latest()->first();
        if($website->referral_earning_commission > 0 && $user->rfered_by){
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

        $job_work->status = 1;
        $job_work->save();

        $job->save();

        \Illuminate\Support\Facades\Log::info('JobWorkApprove: done', [
            'job_work_id' => $id,
            'user_id' => $user->id,
            'each_worker_earn' => $job->each_worker_earn,
            'balance_from_fresh_db_read' => User::find($user->id)->earning_balance,
        ]);

        return redirect()->back()->with('message','Successfully approved this job!');
    }

    /**
     * Lists job works still awaiting a final admin decision (pending, or
     * previously flagged for a reject review) -- shown with Approve/Unsatisfy
     * actions on backend.pages.job-manage.job-work-reject-request.
     */
    public function reject_request()
    {
        $datas = JobWork::whereIn('status', [0, 5])->latest()->get();
        $website = Website::latest()->first();
        $title = 'Job Work Reject Request';

        return view('backend.pages.job-manage.job-work-reject-request', compact('title', 'website', 'datas'));
    }

    /**
     * History of job works the admin has finally rejected.
     */
    public function rejected_work()
    {
        $datas = JobWork::where('status', 2)->latest()->get();
        $website = Website::latest()->first();
        $title = 'Rejected Job Work';

        return view('backend.pages.job-manage.job-work-reject', compact('title', 'website', 'datas'));
    }

    /**
     * The admin's final say on a job work, usable at any time regardless of
     * its current state: if it was already approved and paid, the payment
     * (and any referral commission it triggered) is reversed before marking
     * it rejected, so money never gets stuck credited on a rejected job.
     */
    public function job_work_final_reject($id)
    {
        $job_work = JobWork::find($id);
        $job = Job::find($job_work->job_id);

        \Illuminate\Support\Facades\Log::info('JobWorkFinalReject: start', [
            'job_work_id' => $id,
            'status_before' => $job_work->status,
            'job_found' => (bool) $job,
        ]);

        if ($job_work->status == 1 && $job) {
            $user = User::find($job_work->user_id);
            $balance_before = $user->earning_balance;
            $user->earning_balance = $user->earning_balance - $job->each_worker_earn;

            $website = Website::latest()->first();
            if ($website->referral_earning_commission > 0 && $user->rfered_by) {
                $earning_commission = ($website->referral_earning_commission * $job->each_worker_earn) / 100;

                $refered_by = User::find($user->rfered_by);
                if ($refered_by) {
                    $refered_by->earning_balance = $refered_by->earning_balance - $earning_commission;
                    $refered_by->save();
                }

                $user->earning_commision_from_refer = $user->earning_commision_from_refer - $earning_commission;
            }

            $save_result = $user->save();

            \Illuminate\Support\Facades\Log::info('JobWorkFinalReject: balance updated', [
                'job_work_id' => $id,
                'user_id' => $user->id,
                'each_worker_earn' => $job->each_worker_earn,
                'balance_before' => $balance_before,
                'balance_after_in_memory' => $user->earning_balance,
                'save_result' => $save_result,
                'balance_from_fresh_db_read' => User::find($user->id)->earning_balance,
            ]);

            $job->worker_confirmed = max(0, $job->worker_confirmed - 1);
            $job->save();
        } else {
            \Illuminate\Support\Facades\Log::info('JobWorkFinalReject: skipped refund (status was not 1)', [
                'job_work_id' => $id,
                'status_before' => $job_work->status,
            ]);
        }

        $job_work->status = 2;
        $job_work->save();

        return redirect()->back()->with('message', 'Successfully rejected this job work! Payment reversed if it was already paid.');
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
