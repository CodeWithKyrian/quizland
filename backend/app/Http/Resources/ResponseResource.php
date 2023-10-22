<?php

namespace App\Http\Resources;

use App\Models\Response;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Response */
class ResponseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'option_id' => $this->option_id,
            'question_id' => $this->question_id,
            'question' => $this->whenLoaded('question', $this->question->body),
            'option' => $this->whenLoaded('option', $this->option->body),
            'is_correct' => $this->option->is_correct,
        ];
    }
}
