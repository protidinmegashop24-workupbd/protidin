<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWuServiceDeliveriesTable extends Migration
{
    public function up()
    {
        if (Schema::hasTable('wu_service_deliveries')) {
            return;
        }

        Schema::create('wu_service_deliveries', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('service_order_id');
            $table->unsignedBigInteger('seller_id');
            $table->text('message');
            $table->string('file')->nullable();
            $table->timestamps();

            $table->index('service_order_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('wu_service_deliveries');
    }
}
