<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (Schema::hasTable('websites') && !Schema::hasColumn('websites', 'marketplace_referral_bonus_percent')) {
            Schema::table('websites', function (Blueprint $table) {
                $table->decimal('marketplace_referral_bonus_percent', 5, 2)->default(0)->nullable();
            });
        }

        if (Schema::hasTable('wu_service_orders') && !Schema::hasColumn('wu_service_orders', 'referred_by_user_id')) {
            Schema::table('wu_service_orders', function (Blueprint $table) {
                $table->unsignedBigInteger('referred_by_user_id')->nullable();
            });
        }
    }

    public function down()
    {
    }
};
