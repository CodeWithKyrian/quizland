<?php

namespace App\Http\Resources;

use App\Models\Program;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Program */
class ProgramResource extends JsonResource
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
            'title' => $this->title,
            'creator_id' => $this->creator_id,
            'created_by' => $this->whenLoaded('creator', fn() => $this->creator->name),
            'description' => $this->description,
            'is_public' => $this->is_public,
            'is_published' => $this->is_published,
            'published_at' => $this->published_at->format('D, M j, Y'),
            'created_at' => $this->created_at->format('D, M j, Y'),

            'enrolled_users' => UserResource::collection($this->whenLoaded('enrolledUsers')),
            'quizzes' => QuizResource::collection($this->whenLoaded('quizzes')),

            'enrolled_users_count' => $this->enrolled_users_count ?? $this->enrolledUsers->count(),
            'quizzes_count' => $this->quizzes_count ?? $this->quizzes->count(),
        ];
    }
}
