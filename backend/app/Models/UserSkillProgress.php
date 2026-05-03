<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserSkillProgress extends Model
{
    protected $fillable = [
        'user_id', 'skill_id', 'current_score',
        'attempts_count', 'last_attempt_at', 'score_history',
    ];

    protected $casts = [
        'current_score'  => 'float',
        'attempts_count' => 'integer',
        'last_attempt_at' => 'datetime',
        'score_history'  => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function skill(): BelongsTo
    {
        return $this->belongsTo(Skill::class);
    }
}
