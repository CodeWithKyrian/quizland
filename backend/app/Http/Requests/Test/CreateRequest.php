<?php

namespace App\Http\Requests\Test;

use Illuminate\Foundation\Http\FormRequest;

class CreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'duration' => ($this->input('duration')['hours'] * 60) + $this->input('duration')['minutes'],
            'starts_at' => $this->input('date')[0],
            'ends_at' => $this->input('date')[1],
        ]);
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string',
            'subject_id' => 'required|int',
            'duration' => 'required',
            'base_score' => 'required',
            'pass_mark' => 'required',
            'starts_at' => 'required',
            'ends_at' => 'required'
        ];
    }


}
