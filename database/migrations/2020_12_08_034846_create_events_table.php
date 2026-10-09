<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('title',200);
            $table->string('slug',200);
            $table->text('details',5000);
            $table->string('location',255);
            $table->string('event_date',50);
            $table->string('event_start_time',50);
            $table->string('event_end_time',50);
            $table->string('feature_image');
            $table->integer('status')->default('0');
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
        Schema::dropIfExists('events');
    }
}
