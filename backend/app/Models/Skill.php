<?php

namespace App\Models;

use App\Enums\SkillType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Skill extends Model
{
    protected $fillable = ['name', 'slug', 'color_hex', 'max_score', 'display_order'];

    protected $casts = [
        'slug' => SkillType::class,
    ];

    public function scoringRules(): HasMany
    {
        return $this->hasMany(ScoringRule::class);
    }

    public function attemptSkillScores(): HasMany
    {
        return $this->hasMany(AttemptSkillScore::class);
    }

    public function userSkillProgress(): HasMany
    {
        return $this->hasMany(UserSkillProgress::class);
    }
}
