<?php

namespace App\Models;

use App\Enums\AttemptStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Attempt extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $fillable = [
        'ulid', 'user_id', 'task_id', 'session_id', 'status',
        'response_text', 'response_data', 'audio_duration_ms',
        'audio_recorded_at', 'raw_score', 'normalized_score',
        'scoring_metadata', 'started_at', 'submitted_at', 'scored_at',
    ];

    protected $casts = [
        'status'           => AttemptStatus::class,
        'response_data'    => 'array',
        'scoring_metadata' => 'array',
        'audio_duration_ms' => 'integer',
        'raw_score'        => 'float',
        'normalized_score' => 'float',
        'started_at'       => 'datetime',
        'submitted_at'     => 'datetime',
        'scored_at'        => 'datetime',
        'audio_recorded_at' => 'datetime',
    ];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('audio_response')
            ->singleFile()
            ->acceptsMimeTypes(config('pte.audio.accepted_mimes'));
    }

    public function registerMediaConversions(?Media $media = null): void
    {
        // No image conversions needed for audio
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class);
    }

    public function session(): BelongsTo
    {
        return $this->belongsTo(PracticeSession::class, 'session_id');
    }

    public function skillScores(): HasMany
    {
        return $this->hasMany(AttemptSkillScore::class);
    }

    public function isScored(): bool
    {
        return $this->status === AttemptStatus::Scored;
    }

    public function canBeSubmitted(): bool
    {
        return $this->status === AttemptStatus::InProgress;
    }
}
