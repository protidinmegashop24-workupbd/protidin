<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSharesToFeedpostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('feedposts', function (Blueprint $table) {
            $table->unsignedInteger('shares')->default(0)->after('commnets');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('feedposts', function (Blueprint $table) {
            $table->dropColumn('shares');
        });
    }
}
