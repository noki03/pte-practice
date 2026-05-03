<?php

namespace App\Services\Scoring\Strategies;

use App\Models\Attempt;

interface ScoringStrategyInterface
{
    /**
     * Evaluate an attempt and return per-dimension scores.
     *
     * @return array<string, int>  e.g. ['content' => 4, 'fluency' => 3, 'pronunciation' => 5]
     */
    public function evaluate(Attempt $attempt): array;
}
