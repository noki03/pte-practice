<?php

namespace App\Repositories;

use App\Models\Task;
use App\Repositories\Contracts\TaskRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class TaskRepository implements TaskRepositoryInterface
{
    public function getFiltered(array $filters, int $perPage = 15): LengthAwarePaginator
    {
        return Task::query()
            ->select(['id', 'ulid', 'question_type', 'section', 'title', 'difficulty', 'estimated_duration_s', 'is_active'])
            ->active()
            ->when(isset($filters['section']),         fn($q) => $q->section($filters['section']))
            ->when(isset($filters['question_type']),   fn($q) => $q->type($filters['question_type']))
            ->when(isset($filters['difficulty']),      fn($q) => $q->difficulty((int) $filters['difficulty']))
            ->orderBy('difficulty')
            ->orderBy('title')
            ->paginate($perPage);
    }

    public function findByUlid(string $ulid): Task
    {
        $task = Task::where('ulid', $ulid)->where('is_active', true)->first();

        if (! $task) {
            throw new ModelNotFoundException("Task [{$ulid}] not found.");
        }

        return $task;
    }
}
