<?php

namespace App\Models;

use App\Enums\QuestionType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;

class Task extends Model
{
    protected $fillable = [
        'ulid', 'question_type', 'section', 'title', 'instructions',
        'content', 'difficulty', 'estimated_duration_s', 'is_active',
        'metadata', 'created_by',
    ];

    protected $casts = [
        'question_type'      => QuestionType::class,
        'is_active'          => 'boolean',
        'metadata'           => 'array',
        'estimated_duration_s' => 'integer',
        'difficulty'         => 'integer',
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function attempts(): HasMany
    {
        return $this->hasMany(Attempt::class);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeSection(Builder $query, string $section): Builder
    {
        return $query->where('section', $section);
    }

    public function scopeType(Builder $query, string $type): Builder
    {
        return $query->where('question_type', $type);
    }

    public function scopeDifficulty(Builder $query, int $difficulty): Builder
    {
        return $query->where('difficulty', $difficulty);
    }
}
