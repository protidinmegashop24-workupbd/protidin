<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserBoostPackagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_boost_packages', function (Blueprint $table) {
            $table->id();
            $table->text('code');
            $table->integer('category_id');
            $table->integer('sub_category');
            $table->text('description')->nullable();
            $table->text('link')->nullable();
            $table->double('base_cost')->default(0);
            $table->double('unit_cost')->default(0);
            $table->integer('work_need')->default(0);
            $table->double('cost')->default(0);
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
        Schema::dropIfExists('user_boost_packages');
    }
}
