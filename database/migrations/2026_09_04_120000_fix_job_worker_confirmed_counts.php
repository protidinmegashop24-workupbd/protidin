<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * One-time data repair: worker_confirmed on jobs had drifted from the
     * real number of approved (status = 1) job_works rows for many jobs,
     * because the old 24/72-hour auto-approve logic in
     * UserDashboardController::index() paid workers and marked their proof
     * approved without ever incrementing worker_confirmed. Recompute it
     * from the real data so progress bars and "job complete" checks match
     * what actually happened.
     */
    public function up()
    {
        if (Schema::hasTable('jobs') && Schema::hasTable('job_works')) {
            DB::statement('
                UPDATE jobs
                SET worker_confirmed = (
                    SELECT COUNT(*) FROM job_works
                    WHERE job_works.job_id = jobs.id AND job_works.status = 1
                )
            ');
        }
    }

    public function down()
    {
        // Data repair only; not reversible.
    }
};
