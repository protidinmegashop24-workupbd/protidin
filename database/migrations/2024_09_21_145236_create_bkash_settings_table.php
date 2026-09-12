<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBkashSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    { 
        Schema::create('bkash_settings', function (Blueprint $table) {
            
              $table->text('description')->nullable(); 
            $table->id();
            $table->decimal('min_amount', 10, 2)->default(10.00);
            $table->decimal('conversion_rate', 10, 4)->default(0.01); // প্রতি 1 BDT এর জন্য
            $table->timestamps();
        });

        // ডিফল্ট সেটিংস যোগ করা
        DB::table('bkash_settings')->insert([
            'min_amount' => 10.00,
            'conversion_rate' => 0.01,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bkash_settings');
         Schema::table('bkash_settings', function (Blueprint $table) {
        $table->dropColumn('description');
         });
    }
}
