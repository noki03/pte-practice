<?php

namespace App\Services\Attempt;

use App\Models\Attempt;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class AttemptAudioService
{
    public function attachAudio(Attempt $attempt, Request $request): void
    {
        $maxMb   = config('pte.audio.max_file_size_mb', 5);
        $mimes   = implode(',', config('pte.audio.accepted_mimes', ['audio/webm', 'audio/ogg', 'audio/wav']));
        $maxMs   = config('pte.audio.max_duration_ms', 45_000);

        $request->validate([
            'audio' => ['required', 'file', "max:{$maxMb}000", "mimetypes:{$mimes}"],
        ]);

        $file         = $request->file('audio');
        $durationMs   = (int) $request->input('duration_ms', 0);

        if ($durationMs > $maxMs) {
            throw ValidationException::withMessages([
                'audio' => "Audio exceeds maximum duration of {$maxMs}ms.",
            ]);
        }

        $attempt->clearMediaCollection('audio_response');

        $attempt->addMediaFromRequest('audio')
            ->usingFileName("attempt_{$attempt->ulid}.webm")
            ->toMediaCollection('audio_response');

        $attempt->update([
            'audio_duration_ms'  => $durationMs ?: null,
            'audio_recorded_at'  => now(),
        ]);
    }
}
