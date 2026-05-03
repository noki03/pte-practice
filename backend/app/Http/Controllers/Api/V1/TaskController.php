<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\TaskResource;
use App\Services\Task\TaskService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class TaskController extends Controller
{
    public function __construct(private readonly TaskService $taskService) {}

    public function index(Request $request): AnonymousResourceCollection
    {
        $filters = $request->only(['section', 'question_type', 'difficulty']);
        $perPage = min((int) $request->input('per_page', 15), 50);

        $tasks = $this->taskService->getFilteredTasks($filters, $perPage);

        return TaskResource::collection($tasks);
    }

    public function show(string $ulid): JsonResponse
    {
        $task = $this->taskService->getTaskByUlid($ulid);

        return response()->json(['data' => new TaskResource($task)]);
    }
}
