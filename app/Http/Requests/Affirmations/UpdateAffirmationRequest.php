<?php

namespace App\Http\Requests\Affirmations;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAffirmationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'text' => ['required', 'string'],
            'category' => ['nullable', 'string', 'max:255'],
        ];
    }
}
