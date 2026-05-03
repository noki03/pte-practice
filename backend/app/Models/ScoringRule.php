<?php

namespace App\Models;

use App\Enums\QuestionType;
use App\Enums\ScoringDimension;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ScoringRule extends Model
{
    protected $fillable = [
        'question_type', 'skill_id', 'dimension', 'weight',
        'max_points', 'is_primary_skill', 'scoring_method', 'rubric_config',
    ];

    protected $casts = [
        'question_type'    => QuestionType::class,
        'dimension'        => ScoringDimension::class,
        'weight'           => 'float',
        'max_points'       => 'integer',
        'is_primary_skill' => 'boolean',
        'rubric_config'    => 'array',
    ];

    public function skill(): BelongsTo
    {
        return $this->belongsTo(Skill::class);
    }
}
