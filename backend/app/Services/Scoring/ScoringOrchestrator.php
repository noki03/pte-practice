<?php

namespace App\Services\Scoring;

use App\Enums\AttemptStatus;
use App\Enums\QuestionType;
use App\Models\Attempt;
use App\Models\ScoringRule;
use App\Services\Progress\UserProgressService;
use App\Services\Scoring\Strategies\ReadAloudScoringStrategy;
use App\Services\Scoring\Strategies\ScoringStrategyInterface;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class ScoringOrchestrator
{
    public function __construct(
        private readonly IntegratedScoringService $integratedScoring,
        private readonly UserProgressService $progressService,
    ) {}

    public function score(Attempt $attempt): Attempt
    {
        $attempt->load('task');

        $rules    = $this->loadScoringRules($attempt->task->question_type);
        $strategy = $this->resolveStrategy($attempt->task->question_type);

        $dimensionScores = $strategy->evaluate($attempt);

        DB::transaction(function () use ($attempt, $dimensionScores, $rules) {
            $normalizedScore = $this->integratedScoring->distribute($attempt, $dimensionScores, $rules);

            $rawScore = array_sum($dimensionScores);

            $attempt->update([
                'status'           => AttemptStatus::Scored,
                'raw_score'        => $rawScore,
                'normalized_score' => $normalizedScore,
                'scoring_metadata' => ['dimension_scores' => $dimensionScores],
                'scored_at'        => now(),
            ]);
        });

        $skillScores = $attempt->skillScores()->get();
        $this->progressService->updateProgress($attempt->user_id, $skillScores);

        return $attempt->load(['skillScores.skill', 'task']);
    }

    private function loadScoringRules(QuestionType $questionType): \Illuminate\Support\Collection
    {
        $cacheKey = "scoring_rules:{$questionType->value}";
        $ttl      = config('pte.cache.scoring_rules');

        return Cache::remember($cacheKey, $ttl, fn() =>
            ScoringRule::where('question_type', $questionType->value)->get()
        );
    }

    private function resolveStrategy(QuestionType $questionType): ScoringStrategyInterface
    {
        return match($questionType) {
            QuestionType::ReadAloud => new ReadAloudScoringStrategy(),
            default                 => new ReadAloudScoringStrategy(), // Phase 1 fallback
        };
    }
}
