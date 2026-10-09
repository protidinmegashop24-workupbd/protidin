<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWuServiceInquiriesTable extends Migration
{
    public function up()
    {
        if (Schema::hasTable('wu_service_inquiries')) {
            return;
        }

        Schema::create('wu_service_inquiries', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('service_id');
            $table->unsignedBigInteger('sender_id');
            $table->unsignedBigInteger('receiver_id');
            $table->text('message');
            $table->string('file')->nullable();
            $table->boolean('is_seen')->default(0);
            $table->timestamps();

            $table->index('service_id');
            $table->index(['sender_id', 'receiver_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('wu_service_inquiries');
    }
}
