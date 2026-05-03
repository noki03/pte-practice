<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AttemptResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'                 => $this->ulid,
            'status'             => $this->status,
            'response_text'      => $this->response_text,
            'response_data'      => $this->response_data,
            'audio_duration_ms'  => $this->audio_duration_ms,
            'audio_recorded_at'  => $this->audio_recorded_at?->toIso8601String(),
            'audio_url'          => $this->getFirstMediaUrl('audio_response') ?: null,
            'raw_score'          => $this->raw_score,
            'normalized_score'   => $this->normalized_score,
            'scoring_metadata'   => $this->scoring_metadata,
            'skill_scores'       => $this->whenLoaded('skillScores', fn() =>
                $this->skillScores->map(fn($s) => [
                    'skill'           => $s->skill?->name,
                    'skill_slug'      => $s->skill?->slug,
                    'is_primary'      => $s->is_primary_skill,
                    'dimension_scores' => $s->dimension_scores,
                    'raw_score'       => $s->raw_score,
                    'weighted_score'  => $s->weighted_score,
                ])
            ),
            'task'               => $this->whenLoaded('task', fn() => [
                'id'           => $this->task->ulid,
                'title'        => $this->task->title,
                'question_type' => $this->task->question_type,
            ]),
            'started_at'         => $this->started_at->toIso8601String(),
            'submitted_at'       => $this->submitted_at?->toIso8601String(),
            'scored_at'          => $this->scored_at?->toIso8601String(),
        ];
    }
}
