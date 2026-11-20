<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWuServiceOrdersTable extends Migration
{
    public function up()
    {
        if (Schema::hasTable('wu_service_orders')) {
            return;
        }

        Schema::create('wu_service_orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('service_id');
            $table->unsignedBigInteger('buyer_id');
            $table->unsignedBigInteger('seller_id');
            $table->decimal('price', 12, 2);
            $table->decimal('escrow_amount', 12, 2)->default(0);
            $table->decimal('admin_commission', 12, 2)->default(0);
            $table->decimal('seller_amount', 12, 2)->default(0);
            $table->text('requirements')->nullable();
            $table->string('status')->default('in_progress'); // in_progress, revision_requested, delivered, completed, cancelled
            $table->string('payment_status')->default('held'); // held, released, refunded
            $table->dateTime('delivery_deadline')->nullable();
            $table->dateTime('completed_at')->nullable();
            $table->timestamps();

            $table->index('buyer_id');
            $table->index('seller_id');
            $table->index('service_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('wu_service_orders');
    }
}
