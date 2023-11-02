<?php

namespace App\Http\Controllers\API;

use App\Filters\Program\ByAccess;
use App\Filters\Program\BySearch;
use App\Filters\Program\ByStatus;
use App\Filters\Program\ByVisibility;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\ProgramInviteRequest;
use App\Http\Requests\API\ProgramStoreRequest;
use App\Http\Resources\ProgramResource;
use App\Mail\ProgramInvitationMail;
use App\Models\Program;
use App\Models\ProgramInvitation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ProgramController extends Controller
{
    /**
     *  Retrieve a paginated list of all available programs
     */
    public function index(Request $request)
    {
        $programs = Program::query()
            ->withCount('quizzes', 'enrolledUsers')
            ->filter([ByStatus::class, ByVisibility::class, BySearch::class, ByAccess::class])
            ->orderBy('created_at', 'desc')
            ->paginate($request->input('per_page', 10));

        return ProgramResource::collection($programs);
    }

    /**
     * Retrieve a single program details
     */
    public function show(Program $program)
    {
        $this->authorize('view', $program);

        $program->loadCount('quizzes', 'enrolledUsers');

        return new ProgramResource($program);
    }

    /**
     * Create a new program
     */
    public function store(ProgramStoreRequest $request)
    {
        $this->authorize('create', Program::class);

        $program = Program::create($request->validated());

        return response()->json(['message' => 'Program created successfully'], 201);
    }

    /**
     * Update a program details
     */
    public function update(ProgramStoreRequest $request, Program $program)
    {
        $this->authorize('update', $program);

        $program->update($request->validated());

        return response()->json(['message' => 'Program updated successfully']);
    }

    /**
     * Delete a program
     */
    public function destroy(Program $program)
    {
        $this->authorize('delete', $program);

        $program->delete();

        return response()->json(['message' => 'Program deleted successfully']);
    }

    /**
     * Invite a user to a program
     */
    public function invite(ProgramInviteRequest $request, Program $program)
    {
        $this->authorize('create', $program);

        $email = $request->input('email');

        $invitation = $program->invitations()->create([
            'email' => $email,
            'token' => sha1($email . now()),
            'expires_at' => now()->addDays(5),
        ]);


        // TODO: Verify this url is working and is sync with the frontend architecture.
        $url = config('app.frontend_url') . '/programs/' . $program->id . '/enroll?invitation_code=' . $invitation->token;

        Mail::to($email)->send(new ProgramInvitationMail($invitation, $url));

        return response()->json(['message' => 'Invitation sent successfully']);
    }

    /**
     * Enroll a user to a program
     */
    public function enroll(Program $program)
    {
        $user = auth('api')->user();

        if (!$program->is_public) {
            $invitation = ProgramInvitation::query()
                ->where('user_id', $user->id)
                ->where('program_id', $program->id)
                ->first();

            if (!$invitation) {
                return response()->json(['message' => 'You are not invited to this program'], 403);
            }

            if ($invitation->expires_at->isPast()) {
                return response()->json(['message' => 'Your invitation has expired!'], 403);
            }
        }

        $user->enroll($program);

        return response()->json(['message' => 'You have been enrolled to this program']);
    }

    /**
     * Publish a program
     */
    public function publish(Program $program)
    {
        $this->authorize('update', $program);

        $program->update(['is_published' => true, 'published_at' => now()]);

        return response()->json(['message' => 'Program published successfully']);
    }

    /**
     * Export a program
     */
    public function export(Program $program)
    {
        $this->authorize('view', $program);

        // TODO: Export a program to a PDF file or Excel file. Let's export to JSON for now.

        $program->load('quizzes.questions.answers');

        return response()->json($program);
    }

}
