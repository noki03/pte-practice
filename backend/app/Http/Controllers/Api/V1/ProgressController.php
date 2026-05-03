<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Services\Progress\UserProgressService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProgressController extends Controller
{
    public function __construct(private readonly UserProgressService $progressService) {}

    public function index(Request $request): JsonResponse
    {
        $progress = $this->progressService->getProgressForUser($request->user()->id);

        return response()->json([
            'data' => $progress->map(fn($p) => [
                'skill'          => $p->skill?->name,
                'skill_slug'     => $p->skill?->slug,
                'color_hex'      => $p->skill?->color_hex,
                'current_score'  => $p->current_score,
                'attempts_count' => $p->attempts_count,
                'last_attempt_at' => $p->last_attempt_at?->toIso8601String(),
                'score_history'  => $p->score_history ?? [],
            ]),
        ]);
    }
}
