<?php

namespace App\Http\Resources;

use App\Models\Test;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Test */
class TestResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'subject' => $this->subject->name,
            'title' => $this->title,
            'duration' => $this->convert_duration($this->duration),
            'starts_at' => $this->starts_at->format('D, M j, Y'),
            'ends_at' => $this->ends_at->format('D, M j, Y'),
            'questions_count' => $this->when($this->questions_count, $this->questions_count),
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
