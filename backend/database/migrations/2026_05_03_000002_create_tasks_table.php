<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->ulid('ulid')->unique();
            $table->string('question_type', 60);
            $table->enum('section', ['speaking', 'writing', 'reading', 'listening']);
            $table->string('title');
            $table->text('instructions')->nullable();
            $table->longText('content')->nullable();
            $table->tinyInteger('difficulty')->unsigned()->default(3);
            $table->smallInteger('estimated_duration_s')->unsigned();
            $table->boolean('is_active')->default(true);
            $table->json('metadata');
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['is_active', 'section', 'question_type']);
            $table->index(['is_active', 'difficulty']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
