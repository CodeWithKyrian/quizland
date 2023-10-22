<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Result */
class ResultResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'test_id' => $this->test_id,
            'score' => $this->score,
            'attempts' => $this->values->count(),

            'test' => $this->whenLoaded('test', new TestResource($this->test)),

            'values' => $this->test->questions->map(function ($question) {
                $selection = $this->values->firstWhere('question_id', $question->id);
                $selected_option = $selection['option_id'] ?? '';
                $selected_option = $question->options->firstWhere('id', $selected_option);
                $correct_option = $question->options->firstWhere('is_correct', true);

                $selections = collect();
                $selections->push($correct_option?->body);
                if ($selected_option) {
                    $selections->push($selected_option->body);
                }

                return [
                    'question' => $question->body,
                    'correct' => $selected_option?->is_correct ?? false,
                    'options' => $question->options->map(fn($option) => $option->body),
                    'selections' => $selections,
                    'answer' => $correct_option?->body,
                ];
            }),
        ];
    }
}
