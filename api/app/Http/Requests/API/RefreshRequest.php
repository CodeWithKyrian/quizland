<?php

namespace App\Http\Requests\API;

use Illuminate\Foundation\Http\FormRequest;

class RefreshRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'refresh_token' => ['required', 'string'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
