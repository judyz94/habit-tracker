<?php

namespace App\Http\Requests\HabitLogs;

use Illuminate\Foundation\Http\FormRequest;

class UpdateHabitLogRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'habit_id' => ['sometimes', 'exists:habits,id'],
            'date' => ['sometimes', 'date'],
            'completed' => ['boolean'],
            'notes' => ['nullable', 'string'],
        ];
    }
}
