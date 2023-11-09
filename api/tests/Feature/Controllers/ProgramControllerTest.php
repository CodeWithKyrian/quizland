<?php

declare(strict_types=1);


use App\Mail\ProgramInvitationMail;
use App\Models\Program;
use App\Models\User;
use function Pest\Laravel\{actingAs, deleteJson, getJson, postJson, putJson};


it('can retrieve a paginated list of all available programs', function () {
    Program::factory(10)->publicAndPublished()->create();

    actingAs(User::factory()->create())
        ->getJson('programs')
        ->assertStatus(200)
        ->assertJsonCount(10, 'data')
        ->assertJsonFragment([
            'message' => 'Programs retrieved successfully',
        ]);
});

it('successfully applies the search filter and returns a paginated list of programs', function () {
    Program::factory()->publicAndPublished()->create(['title' => 'Laravel']);
    Program::factory()->publicAndPublished()->create(['title' => 'Vue.js']);
    Program::factory()->publicAndPublished()->create(['title' => 'React.js']);

    actingAs(User::factory()->create())
        ->getJson('programs?search=laravel')
        ->assertStatus(200)
        ->assertJsonCount(1, 'data')
        ->assertJsonFragment([
            'title' => 'Laravel',
        ]);
});

test('regular user cannot retrieve a list of programs that are not public', function () {
    Program::factory()->privateAndPublished()->create();

    actingAs(User::factory()->create())
        ->getJson('programs')
        ->assertStatus(200)
        ->assertJsonCount(0, 'data');
});

test('regular user cannot retrieve a list of programs that are not published', function () {
    Program::factory()->publicAndPublished()->create();
    Program::factory()->privateAndPublished()->create();

    actingAs(User::factory()->create())
        ->getJson('programs')
        ->assertStatus(200)
        ->assertJsonCount(1, 'data');
});

test('regular user cannot retrieve private programs that they did not create', function () {
    $user = User::factory()->create();

    Program::factory()->privateAndPublished()->create();
    Program::factory()->privateAndPublished()->create(['creator_id' => $user->id]);

    actingAs($user)
        ->getJson('programs')
        ->assertStatus(200)
        ->assertJsonCount(1, 'data');
});

test('regular user can retrieve private programs that they enrolled in', function () {
    $user = User::factory()->create();
    $program = Program::factory()->privateAndPublished()->create();
    $user->enroll($program);

    actingAs($user)
        ->getJson('programs')
        ->assertStatus(200)
        ->assertJsonCount(1, 'data');
});

test('admin can retrieve a list of all programs public or private', function () {
    Program::factory()->publicAndPublished()->create();
    Program::factory()->privateAndPublished()->create();

    actingAs(User::factory()->admin()->create())
        ->getJson('programs')
        ->assertStatus(200)
        ->assertJsonCount(2, 'data');
});

test('admin can retrieve published posts only', function () {
    Program::factory()->publicAndPublished()->create();
    Program::factory()->publicAndUnpublished()->create();

    actingAs(User::factory()->admin()->create())
        ->getJson('programs?published=true')
        ->assertStatus(200)
        ->assertJsonCount(1, 'data');
});

test('admin can retrieve unpublished programs only', function () {
    Program::factory()->publicAndPublished()->create();
    Program::factory()->publicAndUnpublished()->create();

    actingAs(User::factory()->admin()->create())
        ->getJson('programs?published=false')
        ->assertStatus(200)
        ->assertJsonCount(1, 'data');
});

it('can retrieve a single program', function () {
    $program = Program::factory()->publicAndPublished()->create();

    actingAs(User::factory()->create())
        ->getJson("programs/$program->id")
        ->assertStatus(200);
});

test('unauthenticated user cannot retrieve a single program', function () {
    $program = Program::factory()->publicAndPublished()->create();

    getJson("programs/$program->id")
        ->assertStatus(401);
});


it('cannot retrieve a private program details', function () {
    $program = Program::factory()->privateAndPublished()->create();

    actingAs(User::factory()->create())
        ->getJson("programs/$program->id")
        ->assertStatus(403);
});

it('cannot retrieve an unpublished program details', function () {
    $program = Program::factory()->publicAndUnpublished()->create();

    actingAs(User::factory()->create())
        ->getJson("programs/$program->id")
        ->assertStatus(403);
});

it('can retrieve a private program if they are enrolled in it', function () {
    $user = User::factory()->create();
    $program = Program::factory()->privateAndPublished()->create();
    $user->enroll($program);

    actingAs($user)
        ->getJson("programs/$program->id")
        ->assertStatus(200);
});

it('can retrieve a private program if they created it', function () {
    $user = User::factory()->create();
    $program = Program::factory()->privateAndPublished()->create(['creator_id' => $user->id]);

    actingAs($user)
        ->getJson("programs/$program->id")
        ->assertStatus(200);
});

it('can create a new program', function () {
    $user = User::factory()->creator()->create();

    actingAs($user)
        ->postJson('programs', [
            'title' => 'Quizland',
            'description' => 'Quizland is a quiz app built with Laravel and Vue.js',
            'is_public' => true,
        ])
        ->assertCreated()
        ->assertJsonFragment([
            'message' => 'Program created successfully',
        ]);

    expect(Program::where('title', 'Quizland')->exists())->toBeTrue();
});

it('cannot create a new program if they are not a creator', function () {
    $user = User::factory()->create();

    actingAs($user)
        ->postJson('programs', [
            'title' => 'Quizland',
            'description' => 'Quizland is a quiz app built with Laravel and Vue.js',
            'is_public' => true,
        ])
        ->assertForbidden();
});

it('cannot create a new program if they are not authenticated', function () {
    postJson('programs', [
        'title' => 'Quizland',
        'description' => 'Quizland is a quiz app built with Laravel and Vue.js',
        'is_public' => true,
    ])
        ->assertUnauthorized();
});

it('can update a program', function () {
    $user = User::factory()->creator()->create();
    $program = Program::factory()->create(['creator_id' => $user->id]);

    actingAs($user)
        ->putJson("programs/$program->id", [
            'title' => 'Quizland',
            'description' => 'Quizland is a quiz app built with Laravel and Vue.js',
            'is_public' => true,
        ])
        ->assertOk()
        ->assertJsonFragment([
            'message' => 'Program updated successfully',
        ]);

    expect(Program::where('title', 'Quizland')->exists())->toBeTrue();
});

it('cannot update a program if they are not the creator', function () {
    $user = User::factory()->creator()->create();
    $program = Program::factory()->create();

    actingAs($user)
        ->putJson("programs/$program->id", [
            'title' => 'Quizland',
            'description' => 'Quizland is a quiz app built with Laravel and Vue.js',
            'is_public' => true,
        ])
        ->assertForbidden();
});

it('cannot update a program if they are not authenticated', function () {
    $program = Program::factory()->create();

    putJson("programs/$program->id", [
        'title' => 'Quizland',
        'description' => 'Quizland is a quiz app built with Laravel and Vue.js',
        'is_public' => true,
    ])
        ->assertUnauthorized();
});

test('admin can update any program', function () {
    $user = User::factory()->admin()->create();
    $program = Program::factory()->create();

    actingAs($user)
        ->putJson("programs/$program->id", [
            'title' => 'Quizland',
            'description' => 'Quizland is a quiz app built with Laravel and Vue.js',
            'is_public' => true,
        ])
        ->assertOk()
        ->assertJsonFragment([
            'message' => 'Program updated successfully',
        ]);

    expect(Program::where('title', 'Quizland')->exists())->toBeTrue();
});

it('can delete a program', function () {
    $user = User::factory()->creator()->create();
    $program = Program::factory()->create(['creator_id' => $user->id]);

    actingAs($user)
        ->deleteJson("programs/$program->id")
        ->assertOk()
        ->assertJsonFragment([
            'message' => 'Program deleted successfully',
        ]);

    expect(Program::where('id', $program->id)->exists())->toBeFalse();
});

it('cannot delete a program if they are not the creator', function () {
    $user = User::factory()->creator()->create();
    $program = Program::factory()->create();

    actingAs($user)
        ->deleteJson("programs/$program->id")
        ->assertForbidden();

    expect(Program::where('id', $program->id)->exists())->toBeTrue();
});

it('cannot delete a program if they are not authenticated', function () {
    $program = Program::factory()->create();

    deleteJson("programs/$program->id")
        ->assertUnauthorized();

    expect(Program::where('id', $program->id)->exists())->toBeTrue();
});

test('admin can delete any program', function () {
    $user = User::factory()->admin()->create();
    $program = Program::factory()->create();

    actingAs($user)
        ->deleteJson("programs/$program->id")
        ->assertOk()
        ->assertJsonFragment([
            'message' => 'Program deleted successfully',
        ]);

    expect(Program::where('id', $program->id)->exists())->toBeFalse();
});

it('can invite an email to a program', function () {
    $user = User::factory()->creator()->create();
    $program = Program::factory()->create(['creator_id' => $user->id]);

    Mail::fake();

    actingAs($user)
        ->postJson("programs/$program->id/invite", [
            'email' => 'johndoe@gmail.com',
        ])
        ->assertOk()
        ->assertJsonFragment([
            'message' => 'Invitation sent successfully',
        ]);

    expect($program->invitations()->count())->toBe(1);

    Mail::assertQueued(
        ProgramInvitationMail::class,
        fn(ProgramInvitationMail $mail) => $mail->hasTo('johndoe@gmail.com')
    );
});

it('cannot invite an email to a program if they are not the creator', function () {
    $user = User::factory()->creator()->create();
    $program = Program::factory()->create();

    Mail::fake();

    actingAs($user)
        ->postJson("programs/$program->id/invite", [
            'email' => 'johndoe@gmail.com',
        ])
        ->assertForbidden();

    expect($program->invitations()->count())->toBe(0);

    Mail::assertNotQueued(ProgramInvitationMail::class);
});

it('can enroll a oneself to a public program', function () {
    $user = User::factory()->create();
    $program = Program::factory()->publicAndPublished()->create();

    actingAs($user)
        ->postJson("programs/$program->id/enroll")
        ->assertOk()
        ->assertJsonFragment([
            'message' => 'User enrolled successfully',
        ]);

    expect($user->enrolledPrograms()->count())->toBe(1)
        ->and($user->enrolledPrograms()->first()->id)->toBe($program->id);
});

test('invited user can enroll to a private program', function () {
    $user = User::factory()->create();
    $program = Program::factory()->privateAndPublished()->create();
    $invitation = $program->invitations()->create([
        'email' => $user->email,
        'token' => sha1($user->email . now()),
        'expires_at' => now()->addDays(5),
    ]);

    actingAs($user)
        ->postJson("programs/$program->id/enroll?invitation_code=$invitation->token")
        ->assertOk()
        ->assertJsonFragment([
            'message' => 'User enrolled successfully',
        ]);

    expect($user->enrolledPrograms()->count())->toBe(1)
        ->and($user->enrolledPrograms()->first()->id)->toBe($program->id);
});

test('uninvited user cannot enroll to a private program', function () {
    $user = User::factory()->create();
    $program = Program::factory()->privateAndPublished()->create();

    actingAs($user)
        ->postJson("programs/$program->id/enroll")
        ->assertForbidden()
        ->assertJsonFragment([
            'message' => 'You are not invited to this program',
        ]);

    expect($user->enrolledPrograms()->count())->toBe(0);
});

test('invited user cannot enroll to a private program if the invitation has expired', function () {
    $user = User::factory()->create();
    $program = Program::factory()->privateAndPublished()->create();
    $invitation = $program->invitations()->create([
        'email' => $user->email,
        'token' => sha1($user->email . now()),
        'expires_at' => now()->subDays(5),
    ]);

    actingAs($user)
        ->postJson("programs/$program->id/enroll?invitation_code=$invitation->token")
        ->assertForbidden()
        ->assertJsonFragment([
            'message' => 'Your invitation has expired!',
        ]);

    expect($user->enrolledPrograms()->count())->toBe(0);
});

it('can enroll a user to a program', function () {
    $user = User::factory()->creator()->create();
    $program = Program::factory()->create(['creator_id' => $user->id]);
    $userToEnroll = User::factory()->create();

    actingAs($user)
        ->postJson("programs/$program->id/enroll", [
            'user_id' => $userToEnroll->id,
        ])
        ->assertOk()
        ->assertJsonFragment([
            'message' => 'User enrolled successfully',
        ]);

    expect($userToEnroll->enrolledPrograms()->count())->toBe(1)
        ->and($userToEnroll->enrolledPrograms()->first()->id)->toBe($program->id);
});

it('cannot enroll a user to a program if they are not the creator', function () {
    $user = User::factory()->creator()->create();
    $program = Program::factory()->create();
    $userToEnroll = User::factory()->create();

    actingAs($user)
        ->postJson("programs/$program->id/enroll", [
            'user_id' => $userToEnroll->id,
        ])
        ->assertForbidden();

    expect($userToEnroll->enrolledPrograms()->count())->toBe(0);
});

it('cannot enroll a user to a program if they are not authenticated', function () {
    $program = Program::factory()->create();
    $userToEnroll = User::factory()->create();

    postJson("programs/$program->id/enroll", [
        'user_id' => $userToEnroll->id,
    ])
        ->assertUnauthorized();

    expect($userToEnroll->enrolledPrograms()->count())->toBe(0);
});

test('admin can enroll any user to any program', function () {
    $user = User::factory()->admin()->create();
    $program = Program::factory()->create();
    $userToEnroll = User::factory()->create();

    actingAs($user)
        ->postJson("programs/$program->id/enroll", [
            'user_id' => $userToEnroll->id,
        ])
        ->assertOk()
        ->assertJsonFragment([
            'message' => 'User enrolled successfully',
        ]);

    expect($userToEnroll->enrolledPrograms()->count())->toBe(1)
        ->and($userToEnroll->enrolledPrograms()->first()->id)->toBe($program->id);
});

it('can publish a program', function () {
    $user = User::factory()->creator()->create();
    $program = Program::factory()->unpublished()->create(['creator_id' => $user->id]);

    actingAs($user)
        ->postJson("programs/$program->id/publish")
        ->assertOk()
        ->assertJsonFragment([
            'message' => 'Program published successfully',
        ]);

    expect($program->fresh()->is_published)->toBeTrue();
});

it('cannot publish a program if they are not the creator', function () {
    $user = User::factory()->creator()->create();
    $program = Program::factory()->unpublished()->create();

    actingAs($user)
        ->postJson("programs/$program->id/publish")
        ->assertForbidden();

    expect($program->fresh()->is_published)->toBeFalse();
});

it('cannot publish a program if they are not authenticated', function () {
    $program = Program::factory()->unpublished()->create();

    postJson("programs/$program->id/publish")
        ->assertUnauthorized();

    expect($program->fresh()->is_published)->toBeFalse();
});

test('admin can publish any program', function () {
    $user = User::factory()->admin()->create();
    $program = Program::factory()->unpublished()->create();

    actingAs($user)
        ->postJson("programs/$program->id/publish")
        ->assertOk()
        ->assertJsonFragment([
            'message' => 'Program published successfully',
        ]);

    expect($program->fresh()->is_published)->toBeTrue();
});

