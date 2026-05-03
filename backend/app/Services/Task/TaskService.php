<?php

namespace App\Services\Task;

use App\Models\Task;
use App\Repositories\Contracts\TaskRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;

class TaskService
{
    public function __construct(private readonly TaskRepositoryInterface $taskRepository) {}

    public function getFilteredTasks(array $filters, int $perPage = 15): LengthAwarePaginator
    {
        $cacheKey = 'tasks:' . md5(serialize($filters) . ':' . $perPage);
        $ttl      = config('pte.cache.task_list');

        return Cache::remember($cacheKey, $ttl, fn() =>
            $this->taskRepository->getFiltered($filters, $perPage)
        );
    }

    public function getTaskByUlid(string $ulid): Task
    {
        $cacheKey = "task:{$ulid}";
        $ttl      = config('pte.cache.task_detail');

        return Cache::remember($cacheKey, $ttl, fn() =>
            $this->taskRepository->findByUlid($ulid)
        );
    }
}
