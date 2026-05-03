<?php

namespace App\Services\Progress;

use App\Models\AttemptSkillScore;
use App\Models\UserSkillProgress;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class UserProgressService
{
    private const EMA_ALPHA        = 0.2;
    private const HISTORY_LIMIT    = 50;

    public function updateProgress(int $userId, Collection $skillScores): void
    {
        foreach ($skillScores as $skillScore) {
            $progress = UserSkillProgress::firstOrNew([
                'user_id'  => $userId,
                'skill_id' => $skillScore->skill_id,
            ]);

            $progress->current_score = $this->applyEma(
                $progress->current_score ?? 0,
                $skillScore->weighted_score
            );

            $progress->attempts_count   = ($progress->attempts_count ?? 0) + 1;
            $progress->last_attempt_at  = now();
            $progress->score_history    = $this->appendHistory(
                $progress->score_history ?? [],
                $skillScore->weighted_score
            );

            $progress->save();
        }

        Cache::forget("user_progress:{$userId}");
    }

    public function getProgressForUser(int $userId): Collection
    {
        $cacheKey = "user_progress:{$userId}";
        $ttl      = config('pte.cache.user_progress');

        return Cache::remember($cacheKey, $ttl, fn() =>
            UserSkillProgress::with('skill')
                ->where('user_id', $userId)
                ->get()
        );
    }

    private function applyEma(float $current, float $latest): float
    {
        if ($current === 0.0) {
            return round($latest, 2);
        }

        return round(self::EMA_ALPHA * $latest + (1 - self::EMA_ALPHA) * $current, 2);
    }

    private function appendHistory(array $history, float $score): array
    {
        $history[] = ['score' => round($score, 2), 'date' => now()->toDateString()];

        if (count($history) > self::HISTORY_LIMIT) {
            $history = array_slice($history, -self::HISTORY_LIMIT);
        }

        return $history;
    }
}
