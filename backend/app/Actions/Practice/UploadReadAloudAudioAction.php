<?php

namespace App\Actions\Practice;

use App\Models\PracticeSession;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

class UploadReadAloudAudioAction
{
    public function execute(int $userId, UploadedFile $audio, int $durationMs = 0): PracticeSession
    {
        $session = PracticeSession::create([
            'ulid'         => (string) Str::ulid(),
            'user_id'      => $userId,
            'session_type' => 'free_practice',
            'status'       => 'active',
            'started_at'   => now(),
            'meta'         => [
                'module_type' => 'read_aloud',
                'duration_ms' => $durationMs ?: null,
            ],
        ]);

        $session->clearMediaCollection('user_audio');

        $session
            ->addMedia($audio)
            ->usingFileName("read_aloud_{$session->ulid}.webm")
            ->toMediaCollection('user_audio');

        return $session;
    }
}
