<?php

namespace App\Http\Requests\API;

use Illuminate\Foundation\Http\FormRequest;

class ProgramInviteRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'email' => ['required', 'email'],
            'program_id' => ['required', 'exists:programs,id'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
