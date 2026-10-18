<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTypeAndFileToWuServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('wu_services', function (Blueprint $table) {
            if (!Schema::hasColumn('wu_services', 'type')) {
                $table->string('type')->default('service')->after('user_id'); // service | digital_product
            }
            if (!Schema::hasColumn('wu_services', 'file_path')) {
                $table->string('file_path')->nullable()->after('image'); // private path to the downloadable file (digital_product only)
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('wu_services', function (Blueprint $table) {
            $table->dropColumn(['type', 'file_path']);
        });
    }
}
