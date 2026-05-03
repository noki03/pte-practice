<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AttemptSkillScore extends Model
{
    protected $fillable = [
        'attempt_id', 'skill_id', 'is_primary_skill',
        'dimension_scores', 'raw_score', 'weighted_score',
    ];

    protected $casts = [
        'is_primary_skill' => 'boolean',
        'dimension_scores' => 'array',
        'raw_score'        => 'float',
        'weighted_score'   => 'float',
    ];

    public function attempt(): BelongsTo
    {
        return $this->belongsTo(Attempt::class);
    }

    public function skill(): BelongsTo
    {
        return $this->belongsTo(Skill::class);
    }
}
