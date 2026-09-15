<?php

namespace App\Http\Controllers;

use App\Models\Admin\Website;
use App\Models\Job;
use App\Models\JobWork;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class JobMaintenanceController extends Controller
{
    /**
     * Runs the two jobs that used to run inline on every page load of
     * UserJobWorkController::index() and UserDashboardController::index()
     * (trashing week-old job_work rows, auto-approving day-old pending
     * ones) -- scanning the whole job_works table for every visitor was
     * wasteful. Meant to be hit periodically by a cPanel cron job instead
     * (e.g. hourly), not by user traffic.
     */
    public function run(Request $request, $token)
    {
        if (!hash_equals('sRGOELHdF3jvfuekDV5sezqOGNNHhsnz', (string) $token)) {
            abort(403);
        }

        $trashedCount = JobWork::where('trash', 0)
            ->where('created_at', '<=', now()->subDays(7))
            ->update(['trash' => 1]);

        $approvedCount = 0;
        $skippedQuotaFull = 0;

        $pending = JobWork::where('status', 0)
            ->where('created_at', '<', Carbon::now()->subHours(24))
            ->get();

        $website = Website::latest()->first();

        foreach ($pending as $job_work) {
            $job = Job::find($job_work->job_id);

            if (!$job) {
                $job_work->trash = 1;
                $job_work->save();
                continue;
            }

            // Never pay past the job's own worker slots -- this is the same
            // quota check added to the manual Approve buttons.
            if ($job->worker_confirmed >= $job->worker_need) {
                $skippedQuotaFull++;
                continue;
            }

            $user = User::find($job_work->user_id);
            $user->earning_balance = $user->earning_balance + $job->each_worker_earn;
            $user->referral_activated = 1;

            if ($website && $website->referral_earning_commission > 0 && $user->rfered_by) {
                $earning_commission = ($website->referral_earning_commission * $job->each_worker_earn) / 100;

                $refered_by = User::find($user->rfered_by);
                if ($refered_by) {
                    $refered_by->earning_balance = $refered_by->earning_balance + $earning_commission;
                    $refered_by->save();

                    $user->earning_commision_from_refer = $user->earning_commision_from_refer + $earning_commission;
                }
            }

            $user->save();

            $job->worker_confirmed = $job->worker_confirmed + 1;
            $job->save();

            $job_work->status = 1;
            $job_work->save();

            $approvedCount++;
        }

        return response()->json([
            'ran_at' => now()->toDateTimeString(),
            'trashed_old_job_work_rows' => $trashedCount,
            'auto_approved_job_work_rows' => $approvedCount,
            'skipped_quota_already_full' => $skippedQuotaFull,
        ], 200, [], JSON_PRETTY_PRINT);
    }
}
