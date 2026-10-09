<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMissingColumnsToPtcTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('ptc_job')) {
            Schema::table('ptc_job', function (Blueprint $table) {
                if (!Schema::hasColumn('ptc_job', 'ptc_post_user_id')) {
                    $table->unsignedBigInteger('ptc_post_user_id')->nullable();
                }
                if (!Schema::hasColumn('ptc_job', 'ptc_title')) {
                    $table->string('ptc_title')->nullable();
                }
                if (!Schema::hasColumn('ptc_job', 'ptc_jobLink')) {
                    $table->string('ptc_jobLink')->nullable();
                }
                if (!Schema::hasColumn('ptc_job', 'ptc_each_earn')) {
                    $table->decimal('ptc_each_earn', 10, 5)->default(0);
                }
                if (!Schema::hasColumn('ptc_job', 'ptc_worker_needed')) {
                    $table->unsignedInteger('ptc_worker_needed')->default(0);
                }
                if (!Schema::hasColumn('ptc_job', 'ptc_clicked')) {
                    $table->unsignedInteger('ptc_clicked')->default(0);
                }
                if (!Schema::hasColumn('ptc_job', 'ptc_wait_time')) {
                    $table->unsignedInteger('ptc_wait_time')->default(10);
                }
                if (!Schema::hasColumn('ptc_job', 'ptc_expire_day')) {
                    $table->date('ptc_expire_day')->nullable();
                }
                if (!Schema::hasColumn('ptc_job', 'ptc_job_details')) {
                    $table->text('ptc_job_details')->nullable();
                }
                if (!Schema::hasColumn('ptc_job', 'ptc_status')) {
                    $table->string('ptc_status')->default('pending'); // pending|review|running|reject|adminPending|req_delete|deleted|done
                }
                if (!Schema::hasColumn('ptc_job', 'ptc_reject_notice')) {
                    $table->text('ptc_reject_notice')->nullable();
                }
            });
        }

        if (Schema::hasTable('ptc_earn_history')) {
            Schema::table('ptc_earn_history', function (Blueprint $table) {
                if (!Schema::hasColumn('ptc_earn_history', 'ptc_worker_id')) {
                    $table->unsignedBigInteger('ptc_worker_id')->nullable();
                }
                if (!Schema::hasColumn('ptc_earn_history', 'ptc_job_id')) {
                    $table->unsignedBigInteger('ptc_job_id')->nullable();
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Intentionally left blank -- these columns are part of the PTC
        // feature's core schema and shouldn't be dropped by a rollback of
        // this defensive/guarded migration.
    }
}
