<?php

namespace App\Http\Resources;

use App\Models\Quiz;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Quiz */
class QuizResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'program_id' => $this->program_id,
            'slug' => $this->slug,
            'duration' => $this->convert_duration($this->duration),
            'base_score' => $this->base_score,
            'pass_mark' => $this->pass_mark,
            'started_at' => $this->started_at->format('D, M j, Y'),
            'ended_at' => $this->ended_at->format('D, M j, Y'),


            'program' => new ProgramResource($this->whenLoaded('program')),
            'questions' => QuestionResource::collection($this->whenLoaded('questions')),
            'results' => ResultResource::collection($this->whenLoaded('results')),
            'result' => new ResultResource($this->whenLoaded('result')),
            'responses' => ResponseResource::collection($this->whenLoaded('responses')),

            'questions_count' => $this->questions_count ?? $this->relationLoaded('questions') ? $this->questions->count() : null,
            'responses_count' => $this->responses_count ?? $this->relationLoaded('responses') ? $this->responses->count() : null,
        ];
    }

    private function convert_duration(int $duration): string
    {
        $minutes = $duration % 60;
        $hour = intval($duration / 60);

        $hour_text = match ($hour) {
            0 => '',
            1 => '1hr',
            default => $hour . 'hrs'
        };

        $minutes_text = $minutes ? $minutes . 'mins' : '';

        return trim($hour_text . ' ' . $minutes_text);
    }
}
