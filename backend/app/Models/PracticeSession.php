<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PracticeSession extends Model
{
    protected $fillable = [
        'ulid', 'user_id', 'session_type', 'status',
        'started_at', 'completed_at', 'overall_score', 'meta',
    ];

    protected $casts = [
        'started_at'    => 'datetime',
        'completed_at'  => 'datetime',
        'overall_score' => 'float',
        'meta'          => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function attempts(): HasMany
    {
        return $this->hasMany(Attempt::class, 'session_id');
    }
}
