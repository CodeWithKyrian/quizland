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
            'created_by' => $this->created_by,
            'creator' => $this->whenLoaded('creator', $this->creator->name),
            'description' => $this->description,
            'is_public' => $this->is_public,
            'is_published' => $this->is_published,
            'published_at' => $this->published_at->format('D, M j, Y'),

            'enrolled_users' => $this->whenLoaded('enrolledUsers', UserResource::collection($this->enrolledUsers)),
            'quizzes' => $this->whenLoaded('quizzes', QuizResource::collection($this->quizzes)),

            'enrolled_users_count' => $this->when($this->enrolled_users_count || $this->enrolledUsers, $this->enrolled_users_count || $this->enrolledUsers->count()),
            'quizzes_count' => $this->when($this->quizzes_count || $this->quizzes, $this->quizzes_count || $this->quizzes->count()),
        ];
    }
}
