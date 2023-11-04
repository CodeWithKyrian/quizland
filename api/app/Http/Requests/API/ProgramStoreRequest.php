<?php

namespace App\Http\Requests\API;

use Illuminate\Foundation\Http\FormRequest;

class ProgramStoreRequest extends FormRequest
{

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'creator_id' => ['required', 'exists:users,id'],
            'title' => ['required', 'string', 'min:3', 'max:255'],
            'description' => ['nullable', 'min:3', 'max:255', 'string'],
            'is_public' => ['required', 'boolean'],
        ];
    }

    /**
     * Prepare the data for validation.
     */
    public function prepareForValidation(): void
    {
        $this->merge([
            'creator_id' => $this->user('api')->id,
        ]);
    }
}
