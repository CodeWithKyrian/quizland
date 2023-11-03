<?php

namespace App\Http\Requests\API;

use Illuminate\Foundation\Http\FormRequest;

class UserUpdateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['nullable', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
