<?php

namespace App\Http\Controllers\Api\Practice;

use App\Actions\Practice\UploadReadAloudAudioAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Practice\UploadReadAloudRequest;
use Illuminate\Http\JsonResponse;

class ReadAloudController extends Controller
{
    public function __construct(private readonly UploadReadAloudAudioAction $action) {}

    public function store(UploadReadAloudRequest $request): JsonResponse
    {
        $session = $this->action->execute(
            $request->user()->id,
            $request->file('audio'),
            $request->integer('duration_ms', 0),
        );

        return response()->json([
            'data' => [
                'session_ulid' => $session->ulid,
                'module_type'  => 'read_aloud',
                'audio_url'    => $session->getFirstMediaUrl('user_audio'),
            ],
        ], 201);
    }
}
