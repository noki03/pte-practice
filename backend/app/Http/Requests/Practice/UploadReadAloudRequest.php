<?php

namespace App\Http\Requests\Practice;

use Illuminate\Foundation\Http\FormRequest;

class UploadReadAloudRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $maxMb  = config('pte.audio.max_file_size_mb', 5);
        $mimes  = implode(',', config('pte.audio.accepted_mimes', ['audio/webm', 'audio/ogg', 'audio/wav']));
        $maxMs  = config('pte.audio.max_duration_ms', 45_000);

        return [
            'audio'       => ['required', 'file', "max:{$maxMb}000", "mimetypes:{$mimes}"],
            'duration_ms' => ['sometimes', 'integer', 'min:0', "max:{$maxMs}"],
        ];
    }
}
