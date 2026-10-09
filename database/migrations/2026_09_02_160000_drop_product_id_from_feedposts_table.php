<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropProductIdFromFeedpostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasColumn('feedposts', 'productId')) {
            Schema::table('feedposts', function (Blueprint $table) {
                $table->dropColumn('productId');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('feedposts', function (Blueprint $table) {
            $table->unsignedBigInteger('productId')->nullable()->after('video');
        });
    }
}
