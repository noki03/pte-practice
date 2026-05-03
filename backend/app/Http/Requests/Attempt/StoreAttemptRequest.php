<?php

namespace App\Http\Requests\Attempt;

use Illuminate\Foundation\Http\FormRequest;

class StoreAttemptRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'task_id'    => ['required', 'string', 'exists:tasks,ulid'],
            'session_id' => ['sometimes', 'nullable', 'string', 'exists:practice_sessions,ulid'],
        ];
    }
}
