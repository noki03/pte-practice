<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_skill_progress', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->unsignedTinyInteger('skill_id');
            $table->decimal('current_score', 5, 2)->default(0);
            $table->integer('attempts_count')->unsigned()->default(0);
            $table->timestamp('last_attempt_at')->nullable();
            $table->json('score_history')->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'skill_id']);
            $table->foreign('skill_id')->references('id')->on('skills')->cascadeOnDelete();
            $table->index('user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_skill_progress');
    }
};
