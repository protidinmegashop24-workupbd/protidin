<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWuServiceReviewsTable extends Migration
{
    public function up()
    {
        if (Schema::hasTable('wu_service_reviews')) {
            return;
        }

        Schema::create('wu_service_reviews', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('service_order_id');
            $table->unsignedBigInteger('buyer_id');
            $table->unsignedBigInteger('seller_id');
            $table->unsignedTinyInteger('rating');
            $table->string('comment', 1000)->nullable();
            $table->timestamps();

            $table->index('service_order_id');
            $table->index('seller_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('wu_service_reviews');
    }
}
