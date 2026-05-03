<?php

namespace App\Services\Attempt;

use App\Enums\AttemptStatus;
use App\Models\Attempt;
use App\Models\Task;
use App\Services\Scoring\ScoringOrchestrator;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class AttemptService
{
    public function __construct(private readonly ScoringOrchestrator $scoringOrchestrator) {}

    public function createAttempt(int $userId, Task $task, ?int $sessionId): Attempt
    {
        return Attempt::create([
            'ulid'       => Str::ulid(),
            'user_id'    => $userId,
            'task_id'    => $task->id,
            'session_id' => $sessionId,
            'status'     => AttemptStatus::InProgress,
            'started_at' => now(),
        ]);
    }

    public function updateAttempt(Attempt $attempt, array $data): Attempt
    {
        if ($attempt->status !== AttemptStatus::InProgress) {
            throw ValidationException::withMessages([
                'attempt' => 'This attempt can no longer be edited.',
            ]);
        }

        $attempt->update(array_filter([
            'response_text' => $data['response_text'] ?? null,
            'response_data' => $data['response_data'] ?? null,
        ]));

        return $attempt;
    }

    public function submitAttempt(Attempt $attempt): Attempt
    {
        if (! $attempt->canBeSubmitted()) {
            throw ValidationException::withMessages([
                'attempt' => 'This attempt has already been submitted.',
            ]);
        }

        $attempt->update([
            'status'       => AttemptStatus::Submitted,
            'submitted_at' => now(),
        ]);

        return $this->scoringOrchestrator->score($attempt);
    }
}
