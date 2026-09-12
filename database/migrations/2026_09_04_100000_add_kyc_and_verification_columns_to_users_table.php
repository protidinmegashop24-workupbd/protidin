<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddKycAndVerificationColumnsToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'is_verified')) {
                $table->boolean('is_verified')->default(0);
            }
            if (!Schema::hasColumn('users', 'kyc_status')) {
                $table->string('kyc_status')->nullable(); // pending | approve | unapprove
            }
            if (!Schema::hasColumn('users', 'kyc_notice')) {
                $table->text('kyc_notice')->nullable();
            }
            if (!Schema::hasColumn('users', 'kyc_name')) {
                $table->string('kyc_name')->nullable();
            }
            if (!Schema::hasColumn('users', 'kyc_nid_number')) {
                $table->string('kyc_nid_number')->nullable();
            }
            if (!Schema::hasColumn('users', 'kyc_address')) {
                $table->string('kyc_address')->nullable();
            }
            if (!Schema::hasColumn('users', 'kyc_birthday')) {
                $table->date('kyc_birthday')->nullable();
            }
            if (!Schema::hasColumn('users', 'kyc_card_type')) {
                $table->string('kyc_card_type')->nullable(); // nid | birth
            }
            if (!Schema::hasColumn('users', 'kyc_userimg')) {
                $table->string('kyc_userimg')->nullable();
            }
            if (!Schema::hasColumn('users', 'kyc_frontpart')) {
                $table->string('kyc_frontpart')->nullable();
            }
            if (!Schema::hasColumn('users', 'kyc_backpart')) {
                $table->string('kyc_backpart')->nullable();
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
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'is_verified',
                'kyc_status',
                'kyc_notice',
                'kyc_name',
                'kyc_nid_number',
                'kyc_address',
                'kyc_birthday',
                'kyc_card_type',
                'kyc_userimg',
                'kyc_frontpart',
                'kyc_backpart',
            ]);
        });
    }
}
