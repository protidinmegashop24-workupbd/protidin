<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWuServiceCategoriesTable extends Migration
{
    public function up()
    {
        if (Schema::hasTable('wu_service_categories')) {
            return;
        }

        Schema::create('wu_service_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('slug');
            $table->boolean('status')->default(1);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('wu_service_categories');
    }
}
