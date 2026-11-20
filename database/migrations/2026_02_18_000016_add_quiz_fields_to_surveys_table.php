<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('surveys', function (Blueprint $table) {
    $table->string('topic')->after('description');
    $table->unsignedInteger('questions_per_attempt')->default(10);
});
    }

    public function down(): void
    {
        Schema::table('surveys', function (Blueprint $table) {
            $table->dropColumn(['question_bank_id','questions_per_attempt']);
        });
    }
};
