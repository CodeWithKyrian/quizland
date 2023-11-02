<?php

namespace App\Policies;

use App\Models\Program;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProgramPolicy
{
    use HandlesAuthorization;

    public function before(User $user, $ability): ?bool
    {
        return $user->is_admin ? true : null;
    }

    public function view(User $user, Program $program): bool
    {
        return ($program->created_by === $user->id)
            || ($program->is_published && $program->is_public)
            || ($program->enrolledUsers()->where('user_id', $user->id)->exists());
    }

    public function create(User $user): bool
    {
        return $user->is_creator;
    }

    public function update(User $user, Program $program): bool
    {
        return $program->created_by === $user->id;
    }

    public function delete(User $user, Program $program): bool
    {
        return $program->created_by === $user->id;
    }

    public function restore(User $user, Program $program): bool
    {
        return $program->created_by === $user->id;
    }

    public function forceDelete(User $user, Program $program): bool
    {
        return $program->created_by === $user->id;
    }
}
