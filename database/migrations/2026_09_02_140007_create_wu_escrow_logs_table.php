<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWuEscrowLogsTable extends Migration
{
    public function up()
    {
        if (Schema::hasTable('wu_escrow_logs')) {
            return;
        }

        Schema::create('wu_escrow_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id');
            $table->unsignedBigInteger('buyer_id');
            $table->unsignedBigInteger('seller_id');
            $table->decimal('amount', 12, 2);
            $table->string('type'); // hold, release, refund, commission
            $table->string('note')->nullable();
            $table->timestamps();

            $table->index('order_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('wu_escrow_logs');
    }
}
