<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void {
    Schema::create('survey_submissions', function (Blueprint $table) {
      $table->id();
      $table->foreignId('survey_id')->constrained()->cascadeOnDelete();
      $table->foreignId('user_id')->constrained()->cascadeOnDelete();
      $table->json('answers');
      $table->string('unique_code')->unique();
      $table->enum('code_status', ['new','used'])->default('new');
      $table->enum('verify_status', ['pending','approved','rejected'])->default('pending');
      $table->timestamp('verified_at')->nullable();
      $table->timestamps();

      $table->index(['user_id','created_at']);
      $table->index(['survey_id','created_at']);
    });
  }
  public function down(): void { Schema::dropIfExists('survey_submissions'); }
};
