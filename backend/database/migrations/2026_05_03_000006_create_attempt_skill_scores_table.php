<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attempt_skill_scores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('attempt_id')->constrained()->cascadeOnDelete();
            $table->unsignedTinyInteger('skill_id');
            $table->boolean('is_primary_skill')->default(false);
            $table->json('dimension_scores');
            $table->decimal('raw_score', 5, 2);
            $table->decimal('weighted_score', 5, 2);
            $table->timestamps();

            $table->unique(['attempt_id', 'skill_id']);
            $table->foreign('skill_id')->references('id')->on('skills')->cascadeOnDelete();
            $table->index('skill_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attempt_skill_scores');
    }
};
