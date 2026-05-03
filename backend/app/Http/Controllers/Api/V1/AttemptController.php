<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Attempt\StoreAttemptRequest;
use App\Http\Resources\AttemptResource;
use App\Models\Attempt;
use App\Models\PracticeSession;
use App\Models\Task;
use App\Services\Attempt\AttemptAudioService;
use App\Services\Attempt\AttemptService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AttemptController extends Controller
{
    public function __construct(
        private readonly AttemptService $attemptService,
        private readonly AttemptAudioService $audioService,
    ) {}

    public function store(StoreAttemptRequest $request): JsonResponse
    {
        $task = Task::where('ulid', $request->input('task_id'))->firstOrFail();

        $sessionId = null;
        if ($request->filled('session_id')) {
            $session   = PracticeSession::where('ulid', $request->input('session_id'))
                ->where('user_id', $request->user()->id)
                ->firstOrFail();
            $sessionId = $session->id;
        }

        $attempt = $this->attemptService->createAttempt(
            $request->user()->id,
            $task,
            $sessionId
        );

        return response()->json(['data' => new AttemptResource($attempt)], 201);
    }

    public function update(Request $request, string $ulid): JsonResponse
    {
        $attempt = $this->findOwnedAttempt($request, $ulid);

        $attempt = $this->attemptService->updateAttempt($attempt, $request->only([
            'response_text',
            'response_data',
        ]));

        return response()->json(['data' => new AttemptResource($attempt)]);
    }

    public function uploadAudio(Request $request, string $ulid): JsonResponse
    {
        $attempt = $this->findOwnedAttempt($request, $ulid);

        $this->audioService->attachAudio($attempt, $request);

        return response()->json(['data' => new AttemptResource($attempt)]);
    }

    public function submit(Request $request, string $ulid): JsonResponse
    {
        $attempt = $this->findOwnedAttempt($request, $ulid);

        $attempt = $this->attemptService->submitAttempt($attempt);

        return response()->json(['data' => new AttemptResource($attempt->load(['skillScores.skill', 'task']))]);
    }

    public function show(Request $request, string $ulid): JsonResponse
    {
        $attempt = $this->findOwnedAttempt($request, $ulid);

        return response()->json(['data' => new AttemptResource($attempt->load(['skillScores.skill', 'task']))]);
    }

    private function findOwnedAttempt(Request $request, string $ulid): Attempt
    {
        return Attempt::where('ulid', $ulid)
            ->where('user_id', $request->user()->id)
            ->firstOrFail();
    }
}
