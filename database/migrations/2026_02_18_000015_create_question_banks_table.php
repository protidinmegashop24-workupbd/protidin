<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('question_banks', function (Blueprint $table) {
    $table->id();
    $table->string('topic'); // islamic / general
    $table->text('question');
    $table->json('options');
    $table->string('correct_option');
    $table->timestamps();
});
    }

    public function down(): void
    {
        Schema::dropIfExists('question_banks');
    }
};
