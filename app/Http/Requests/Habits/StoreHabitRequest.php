<?php

namespace App\Http\Requests\Habits;

use App\Enums\HabitStatusEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreHabitRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'goal_id' => ['required', 'exists:goals,id'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'schedule_time' => ['nullable', 'date_format:H:i'],
            'repeat_days' => ['nullable', 'array'],
            'repeat_days.*' => ['string'],
            'min_action' => ['nullable', 'string', 'max:255'],
            'min_time' => ['nullable', 'integer', 'min:0'],
            'environment_design' => ['nullable', 'string'],
            'reward' => ['nullable', 'string'],
            'notes' => ['nullable', 'string'],
            'status' => ['nullable', Rule::enum(HabitStatusEnum::class)],
        ];
    }
}
