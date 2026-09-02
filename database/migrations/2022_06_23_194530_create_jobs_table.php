<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJobsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jobs', function (Blueprint $table) {
            $table->id();
            $table->text('code');
            $table->text('title');
            $table->text('slug')->unique();
            $table->integer('location_zone_id');
            $table->integer('location_zone_country')->nullable();
            $table->integer('category_id');
            $table->integer('sub_category');
            $table->text('specific_task');
            $table->text('required_proof');
            $table->text('thumbnail_image');
            $table->integer('worker_need')->default(0);
            $table->integer('each_worker_earn')->default(0);
            $table->integer('required_screenshots')->default(0);
            $table->integer('estimited_day')->default(0);
            $table->text('extended_period')->nullable();
            $table->double('budget')->default(0);
            $table->integer('status')->default(0);
            $table->integer('user_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('jobs');
    }
}
