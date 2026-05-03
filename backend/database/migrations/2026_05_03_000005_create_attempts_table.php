<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attempts', function (Blueprint $table) {
            $table->id();
            $table->ulid('ulid')->unique();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('task_id')->constrained()->cascadeOnDelete();
            $table->foreignId('session_id')
                ->nullable()
                ->references('id')->on('practice_sessions')
                ->nullOnDelete();
            $table->enum('status', ['in_progress', 'submitted', 'scored', 'failed'])->default('in_progress');
            $table->longText('response_text')->nullable();
            $table->json('response_data')->nullable();
            $table->integer('audio_duration_ms')->unsigned()->nullable();
            $table->timestamp('audio_recorded_at')->nullable();
            $table->decimal('raw_score', 6, 2)->nullable();
            $table->decimal('normalized_score', 5, 2)->nullable();
            $table->json('scoring_metadata')->nullable();
            $table->timestamp('started_at')->useCurrent();
            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('scored_at')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'task_id']);
            $table->index(['user_id', 'status']);
            $table->index('session_id');
            $table->index(['user_id', 'scored_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attempts');
    }
};
