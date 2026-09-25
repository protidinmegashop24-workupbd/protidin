<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->integer('role_id');
            $table->string('name',100);
            $table->string('username',100)->unique()->nullable();
            $table->string('phone',20)->unique();
            $table->string('email',100)->unique();
            $table->string('birthday',100)->nullable();
            $table->string('gender',100)->nullable();
            $table->string('present_address',2000)->nullable();
            $table->string('permanent_address',2000)->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password',100);
            $table->string('image')->nullable();
            $table->integer('status')->default('0');
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
