<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('scoring_rules', function (Blueprint $table) {
            $table->id();
            $table->string('question_type', 60);
            $table->unsignedTinyInteger('skill_id');
            $table->string('dimension', 50);
            $table->decimal('weight', 5, 4);
            $table->tinyInteger('max_points')->unsigned()->default(5);
            $table->boolean('is_primary_skill')->default(false);
            $table->string('scoring_method', 50);
            $table->json('rubric_config')->nullable();
            $table->timestamps();

            $table->foreign('skill_id')->references('id')->on('skills')->cascadeOnDelete();
            $table->index(['question_type', 'skill_id', 'is_primary_skill']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('scoring_rules');
    }
};
