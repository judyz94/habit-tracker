<?php

namespace App\Http\Requests\HabitLogs;

use Illuminate\Foundation\Http\FormRequest;

class StoreHabitLogRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'habit_id' => ['required', 'exists:habits,id'],
            'date' => ['required', 'date'],
            'completed' => ['boolean'],
            'notes' => ['nullable', 'string'],
        ];
    }
}
