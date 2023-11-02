<?php

namespace App\Http\Controllers\API;

use App\Filters\User\ByRole;
use App\Filters\User\BySearch;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\UpdatePasswordRequest;
use App\Http\Requests\API\UserUpdateRequest;
use App\Http\Resources\UserResource;
use App\Models\User;

class UserController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', User::class);

        $users = User::query()
            ->filter([BySearch::Class, ByRole::Class,])
            ->paginate(request()->input('per_page', 10));

        return UserResource::collection($users);
    }

    public function show(?User $user)
    {
        $user ??= request()->user();

        $this->authorize('view', $user);

        return new UserResource($user);
    }

    public function notifications(?User $user)
    {
        $user ??= request()->user();

        $this->authorize('view', $user);

        return response()->json($user->notifications);
    }

    public function update(UserUpdateRequest $request, ?User $user)
    {
        $user ??= request()->user();

        $this->authorize('update', $user);

        $user->update($request->validated());

        return response()->json(['message' => 'User updated successfully']);
    }

    public function updatePassword(UpdatePasswordRequest $request, ?User $user)
    {
        $user ??= request()->user();

        $this->authorize('update', $user);

        $user->update($request->validated('password'));

        // TODO: Send email to user that password has been changed

        return response()->json(['message' => 'Password updated successfully']);
    }

    public function destroy(User $user)
    {
        $this->authorize('delete', $user);

        $user->delete();

        return response()->json(['message' => 'User deleted successfully']);
    }
}
