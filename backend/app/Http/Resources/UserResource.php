<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'                => $this->ulid,
            'name'              => $this->name,
            'email'             => $this->email,
            'email_verified'    => ! is_null($this->email_verified_at),
            'target_exam_date'  => $this->target_exam_date?->toDateString(),
            'target_score'      => $this->target_score,
            'timezone'          => $this->timezone,
            'ui_preferences'    => $this->ui_preferences ?? ['dark_mode' => false],
            'created_at'        => $this->created_at->toIso8601String(),
        ];
    }
}
