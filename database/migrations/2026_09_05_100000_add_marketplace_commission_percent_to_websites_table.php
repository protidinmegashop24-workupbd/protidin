<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (Schema::hasTable('websites') && !Schema::hasColumn('websites', 'marketplace_commission_percent')) {
            Schema::table('websites', function (Blueprint $table) {
                $table->decimal('marketplace_commission_percent', 5, 2)->default(20)->nullable();
            });
        }
    }

    public function down()
    {
    }
};
