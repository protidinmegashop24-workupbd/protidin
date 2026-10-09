<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWebsitesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('websites', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title')->nullable();
            $table->string('vender_name')->nullable();
            $table->string('license')->nullable();
            $table->string('signature')->nullable();
            $table->text('description')->nullable();
            $table->text('meta_keyword')->nullable();
            $table->text('meta_tag')->nullable();
            $table->string('email')->nullable();
            $table->text('address')->nullable();
            $table->string('mobile')->nullable();
            $table->string('phone')->nullable();
            $table->string('fax')->nullable();
            $table->string('favicon')->nullable();
            $table->string('logo')->nullable();
            $table->text('twitter_api')->nullable();
            $table->text('google_map')->nullable();
            $table->text('icon')->nullable();
            $table->text('link')->nullable();
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
        Schema::dropIfExists('websites');
    }
}
