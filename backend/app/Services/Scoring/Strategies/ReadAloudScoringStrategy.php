<?php

namespace App\Services\Scoring\Strategies;

use App\Models\Attempt;

class ReadAloudScoringStrategy implements ScoringStrategyInterface
{
    private const MAX_POINTS = 5;

    public function evaluate(Attempt $attempt): array
    {
        $taskContent  = strtolower($attempt->task->content ?? '');
        $responseText = strtolower($attempt->response_text ?? '');

        return [
            'content'       => $this->scoreContent($taskContent, $responseText),
            'fluency'       => $this->scoreFluency($attempt),
            'pronunciation' => $this->scorePronunciation($responseText, $taskContent),
        ];
    }

    private function scoreContent(string $original, string $response): int
    {
        if (empty($response)) {
            return 0;
        }

        $originalWords = $this->tokenize($original);
        $responseWords = $this->tokenize($response);

        if (empty($originalWords)) {
            return 0;
        }

        $matched  = count(array_intersect($responseWords, $originalWords));
        $ratio    = $matched / count($originalWords);

        return match(true) {
            $ratio >= 0.95 => 5,
            $ratio >= 0.80 => 4,
            $ratio >= 0.60 => 3,
            $ratio >= 0.40 => 2,
            $ratio >= 0.20 => 1,
            default        => 0,
        };
    }

    private function scoreFluency(Attempt $attempt): int
    {
        $durationMs  = $attempt->audio_duration_ms ?? 0;
        $wordCount   = (int) ($attempt->task->metadata['word_count'] ?? 70);
        $maxDuration = (int) ($attempt->task->metadata['response_time_s'] ?? 40) * 1000;

        if ($durationMs <= 0) {
            return 0;
        }

        $durationSeconds = $durationMs / 1000;
        // Ideal reading speed: 120–160 wpm
        $wordsPerMinute  = ($durationSeconds > 0) ? ($wordCount / $durationSeconds) * 60 : 0;
        // Penalise if far too fast or far too slow
        $usedRatio       = $durationMs / max($maxDuration, 1);

        return match(true) {
            $wordsPerMinute >= 110 && $wordsPerMinute <= 170 && $usedRatio >= 0.7 => 5,
            $wordsPerMinute >= 90  && $wordsPerMinute <= 190 && $usedRatio >= 0.5 => 4,
            $wordsPerMinute >= 70  && $wordsPerMinute <= 210 && $usedRatio >= 0.3 => 3,
            $wordsPerMinute >= 50  && $wordsPerMinute <= 230                       => 2,
            $wordsPerMinute > 0                                                     => 1,
            default                                                                 => 0,
        };
    }

    private function scorePronunciation(string $response, string $original): int
    {
        if (empty($response)) {
            return 0;
        }

        $originalWords  = $this->tokenize($original);
        $responseWords  = $this->tokenize($response);
        $commonCount    = count(array_intersect($responseWords, $originalWords));
        $coverageRatio  = count($originalWords) > 0
            ? $commonCount / count($originalWords)
            : 0;

        // Proxy: high word overlap suggests recognisable pronunciation
        return match(true) {
            $coverageRatio >= 0.90 => 5,
            $coverageRatio >= 0.75 => 4,
            $coverageRatio >= 0.55 => 3,
            $coverageRatio >= 0.35 => 2,
            $coverageRatio >  0    => 1,
            default                => 0,
        };
    }

    private function tokenize(string $text): array
    {
        // Strip punctuation and split on whitespace
        $clean = preg_replace('/[^a-z0-9\s]/', '', $text);

        return array_filter(explode(' ', $clean));
    }
}
