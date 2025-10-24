<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class HabitResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'goal_id' => $this->goal_id,
            'name' => $this->name,
            'description' => $this->description,
            'schedule_time' => $this->schedule_time,
            'repeat_days' => $this->repeat_days ?? [],
            'min_action' => $this->min_action,
            'min_time' => $this->min_time,
            'environment_design' => $this->environment_design,
            'reward' => $this->reward,
            'notes' => $this->notes,
            'status' => $this->status->value ?? $this->status,
            'logs' => HabitLogResource::collection($this->whenLoaded('logs')),
        ];
    }
}
