<?php

namespace App\Policies;

use App\Models\Program;
use App\Models\Quiz;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class QuizPolicy
{
    use HandlesAuthorization;

    public function before(User $user, $ability): ?bool
    {
        return $user->is_admin ? true : null;
    }

    public function view(User $user, Quiz $quiz): bool
    {
        $quiz->loadMissing('program');

        return ($quiz->program->creator_id === $user->id)
            || ($quiz->program->is_published && $quiz->program->is_public)
            || ($quiz->program->enrolledUsers()->where('user_id', $user->id)->exists());
    }

    public function create(User $user, int $programId): Response
    {
        if(!$user->is_creator)
        {
            return Response::deny('You are not allowed to create quizzes', 403);
        }

        $isTheCreator =  $user->createdPrograms()->where('id', $programId)->exists();

        if(!$isTheCreator)
        {
            return Response::deny('You are not allowed to create quizzes for this program', 403);
        }

        return Response::allow();
    }

    public function update(User $user, Quiz $quiz): bool
    {
        $quiz->loadMissing('program');

        return $quiz->program->creator_id === $user->id;
    }

    public function delete(User $user, Quiz $quiz): bool
    {
        $quiz->loadMissing('program');

        return $quiz->program->creator_id === $user->id;
    }

    public function restore(User $user, Quiz $quiz): bool
    {
        $quiz->loadMissing('program');

        return $quiz->program->creator_id === $user->id;
    }

    public function forceDelete(User $user, Quiz $quiz): bool
    {
        $quiz->loadMissing('program');

        return $quiz->program->creator_id === $user->id;
    }
}
