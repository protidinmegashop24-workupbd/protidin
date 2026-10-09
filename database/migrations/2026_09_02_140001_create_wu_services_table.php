<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWuServicesTable extends Migration
{
    public function up()
    {
        if (Schema::hasTable('wu_services')) {
            return;
        }

        Schema::create('wu_services', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('category')->nullable();
            $table->decimal('price', 12, 2);
            $table->unsignedInteger('delivery_days');
            $table->unsignedInteger('revision_limit')->default(0);
            $table->string('short_description', 500)->nullable();
            $table->text('description');
            $table->string('image')->nullable();
            $table->string('status')->default('pending'); // pending, active, rejected
            $table->boolean('featured')->default(0);
            $table->timestamps();

            $table->index('user_id');
            $table->index('status');
        });
    }

    public function down()
    {
        Schema::dropIfExists('wu_services');
    }
}
