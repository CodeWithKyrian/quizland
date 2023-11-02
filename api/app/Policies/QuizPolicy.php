<?php

namespace App\Policies;

use App\Models\Quiz;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

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

        return ($quiz->program->created_by === $user->id)
            || ($quiz->program->is_published && $quiz->program->is_public)
            || ($quiz->program->enrolledUsers()->where('user_id', $user->id)->exists());
    }

    public function create(User $user): bool
    {
        return $user->is_creator;
    }

    public function update(User $user, Quiz $quiz): bool
    {
        $quiz->loadMissing('program');

        return $quiz->program->created_by === $user->id;
    }

    public function delete(User $user, Quiz $quiz): bool
    {
        $quiz->loadMissing('program');

        return $quiz->program->created_by === $user->id;
    }

    public function restore(User $user, Quiz $quiz): bool
    {
        $quiz->loadMissing('program');

        return $quiz->program->created_by === $user->id;
    }

    public function forceDelete(User $user, Quiz $quiz): bool
    {
        $quiz->loadMissing('program');

        return $quiz->program->created_by === $user->id;
    }
}
