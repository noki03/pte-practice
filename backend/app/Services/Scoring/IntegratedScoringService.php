<?php

namespace App\Services\Scoring;

use App\Models\Attempt;
use App\Models\AttemptSkillScore;
use Illuminate\Support\Collection;

class IntegratedScoringService
{
    private const PTE_MIN = 10;
    private const PTE_MAX = 90;

    /**
     * Distribute dimension scores across skills, persist AttemptSkillScore rows,
     * and return the normalized 10–90 score.
     *
     * @param  array<string,int>  $dimensionScores  e.g. ['content' => 4, 'fluency' => 3, ...]
     * @param  Collection         $rules            ScoringRule models for this question type
     */
    public function distribute(Attempt $attempt, array $dimensionScores, Collection $rules): float
    {
        $rulesBySkill = $rules->groupBy('skill_id');
        $totalRaw     = 0.0;
        $totalMax     = 0.0;

        foreach ($rulesBySkill as $skillId => $skillRules) {
            $skillDimensionScores = [];
            $rawScore             = 0.0;
            $maxScore             = 0.0;

            foreach ($skillRules as $rule) {
                $dimension  = $rule->dimension->value;
                $earned     = $dimensionScores[$dimension] ?? 0;
                $weighted   = $earned * $rule->weight;

                $skillDimensionScores[$dimension] = $earned;
                $rawScore += $weighted;
                $maxScore += $rule->max_points * $rule->weight;
            }

            $weightedScore = $maxScore > 0 ? ($rawScore / $maxScore) * 5 : 0;

            AttemptSkillScore::updateOrCreate(
                ['attempt_id' => $attempt->id, 'skill_id' => $skillId],
                [
                    'is_primary_skill' => $skillRules->first()->is_primary_skill,
                    'dimension_scores' => $skillDimensionScores,
                    'raw_score'        => round($rawScore, 2),
                    'weighted_score'   => round($weightedScore, 2),
                ]
            );

            if ($skillRules->first()->is_primary_skill) {
                $totalRaw += $rawScore;
                $totalMax += $maxScore;
            }
        }

        return $this->normalize($totalRaw, $totalMax);
    }

    private function normalize(float $raw, float $max): float
    {
        if ($max <= 0) {
            return self::PTE_MIN;
        }

        $ratio = $raw / $max;

        return round(self::PTE_MIN + ($ratio * (self::PTE_MAX - self::PTE_MIN)), 1);
    }
}
