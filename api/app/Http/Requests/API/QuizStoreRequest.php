<?php

namespace App\Http\Requests\API;

use Illuminate\Foundation\Http\FormRequest;

class QuizStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'program_id' => ['required', 'exists:programs'],
            'title' => ['required', 'string', 'min:3', 'max:255'],
            'description' => ['nullable', 'min:3', 'max:255', 'string'],
            'duration' => ['required', 'integer', 'min:10'],
            'base_score' => ['required', 'integer', 'min:10'],
            'pass_mark' => ['required', 'integer'],
            'started_at' => ['nullable', 'date', 'after_or_equal:today'],
            'ended_at' => ['nullable', 'date', 'after_or_equal:started_at'],
        ];
    }

    public function messages(): array
    {
        return [
            'program_id.required' => 'The program field is required.',
            'program_id.exists' => 'The selected program is invalid.',
        ];
    }
}
