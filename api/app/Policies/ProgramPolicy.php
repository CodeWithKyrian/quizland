<?php

namespace App\Policies;

use App\Models\Program;
use App\Models\ProgramInvitation;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class ProgramPolicy
{
    use HandlesAuthorization;

    public function before(User $user, $ability): ?bool
    {
        return $user->is_admin ? true : null;
    }

    public function view(User $user, Program $program): bool
    {
        return ($program->creator_id === $user->id)
            || ($program->is_published && $program->is_public)
            || ($program->enrolledUsers()->where('user_id', $user->id)->exists());
    }

    public function create(User $user): bool
    {
        return $user->is_creator;
    }

    public function update(User $user, Program $program): bool
    {
        return $program->creator_id === $user->id;
    }

    public function delete(User $user, Program $program): bool
    {
        return $program->creator_id === $user->id;
    }

    public function restore(User $user, Program $program): bool
    {
        return $program->creator_id === $user->id;
    }

    public function enroll(User $user, Program $program): Response
    {
        if ($program->is_public) {
            return Response::allow();
        }

        $invitation = ProgramInvitation::query()
            ->where('email', $user->email)
            ->where('program_id', $program->id)
            ->first();

        if (is_null($invitation)) {
            return Response::deny('You are not invited to this program', 403);
        }

        if ($invitation->expires_at->isPast()) {
            return Response::deny('Your invitation has expired!', 403);
        }

        return Response::allow();
    }

    public function forceDelete(User $user, Program $program): bool
    {
        return $program->creator_id === $user->id;
    }
}
