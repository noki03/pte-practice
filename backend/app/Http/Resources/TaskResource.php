<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TaskResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'                   => $this->ulid,
            'question_type'        => $this->question_type,
            'question_type_label'  => $this->question_type->label(),
            'section'              => $this->section,
            'title'                => $this->title,
            'instructions'         => $this->when(isset($this->instructions), $this->instructions),
            'content'              => $this->when(isset($this->content), $this->content),
            'difficulty'           => $this->difficulty,
            'estimated_duration_s' => $this->estimated_duration_s,
            'metadata'             => $this->when(isset($this->metadata), $this->metadata),
            'requires_recording'   => $this->question_type->requiresAudioRecording(),
            'requires_playback'    => $this->question_type->requiresAudioPlayback(),
            'created_at'           => $this->created_at?->toIso8601String(),
        ];
    }
}
