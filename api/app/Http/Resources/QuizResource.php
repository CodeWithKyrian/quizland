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
            'slug' => $this->slug,
            'duration' => $this->convert_duration($this->duration),
            'base_score' => $this->base_score,
            'pass_mark' => $this->pass_mark,
            'started_at' => $this->started_at->format('D, M j, Y'),
            'ended_at' => $this->ended_at->format('D, M j, Y'),

            'questions_count' => $this->when($this->questions_count || $this->questions, $this->questions_count || $this->questions->count()),
            'responses_count' => $this->when($this->responses_count || $this->responses, $this->responses_count || $this->responses->count()),

            'program' => $this->whenLoaded('program', new ProgramResource($this->program)),
            'questions' => $this->whenLoaded('questions', QuestionResource::collection($this->questions)),
            'results' => $this->whenLoaded('results', ResultResource::collection($this->results)),
            'result' => $this->whenLoaded('result', new ResultResource($this->result)),
            'responses' => $this->whenLoaded('responses', ResponseResource::collection($this->responses)),
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
