<?php

declare(strict_types=1);

use App\Models\Program;
use App\Models\Quiz;
use App\Models\Result;
use App\Models\User;
use function Pest\Laravel\actingAs;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->admin = User::factory()->admin()->create();
    $this->program = Program::factory()->publicAndPublished()->create(['creator_id' => $this->user->id]);
});

it('can retrieve a list of all available quizzes', function () {
    Quiz::factory()->count(5)->create(['program_id' => $this->program->id]);

    $this->actingAs($this->user)
        ->getJson('quizzes')
        ->assertOk()
        ->assertJsonCount(5, 'data')
        ->assertJsonFragment([
            'message' => 'Quizzes retrieved successfully'
        ]);
});

test('user can retrieve only the quizzes they have already written', function () {
    $quizzes = Quiz::factory()->count(5)->create(['program_id' => $this->program->id]);

    Result::factory()->create(['user_id' => $this->user->id, 'quiz_id' => $quizzes->first()->id]);

    $this->actingAs($this->user)
        ->getJson('quizzes?written=true')
        ->assertOk()
        ->assertJsonCount(1, 'data')
        ->assertJsonFragment([
            'message' => 'Quizzes retrieved successfully'
        ]);
});

test('user can retrieve only the quizzes they have not written', function () {
    $quizzes = Quiz::factory()->count(5)->create(['program_id' => $this->program->id]);

    Result::factory()->create(['user_id' => $this->user->id, 'quiz_id' => $quizzes->first()->id]);

    $this->actingAs($this->user)
        ->getJson('quizzes?written=false')
        ->assertOk()
        ->assertJsonCount(4, 'data')
        ->assertJsonFragment([
            'message' => 'Quizzes retrieved successfully'
        ]);
});

test('user cannot retrieve quizzes that belong to a private program', function () {
    $privateProgram = Program::factory()->privateAndPublished()->create();

    Quiz::factory()->count(10)->create(['program_id' => $privateProgram->id]);
    Quiz::factory()->count(5)->create(['program_id' => $this->program->id]);

    $this->actingAs($this->user)
        ->getJson('quizzes')
        ->assertOk()
        ->assertJsonCount(5, 'data')
        ->assertJsonFragment([
            'message' => 'Quizzes retrieved successfully'
        ]);
});

test('user cannot retrieve quizzes that belong to a draft program', function () {
    $draftProgram = Program::factory()->publicAndDraft()->create();

    Quiz::factory()->count(10)->create(['program_id' => $draftProgram->id]);
    Quiz::factory()->count(5)->create(['program_id' => $this->program->id]);

    $this->actingAs($this->user)
        ->getJson('quizzes')
        ->assertOk()
        ->assertJsonCount(5, 'data')
        ->assertJsonFragment([
            'message' => 'Quizzes retrieved successfully'
        ]);
});

test('user can retrieve quizzes from a private program if they are enrolled to it', function () {
    $privateProgram = Program::factory()->privateAndPublished()->create();
    $this->user->enroll($privateProgram);

    Quiz::factory()->count(10)->create(['program_id' => $privateProgram->id]);
    Quiz::factory()->count(5)->create(['program_id' => $this->program->id]);

    $this->actingAs($this->user)
        ->getJson('quizzes')
        ->assertOk()
        ->assertJsonCount(15, 'data')
        ->assertJsonFragment([
            'message' => 'Quizzes retrieved successfully'
        ]);
});

test('user can retrieve quizzes from a private program if they created it', function () {
    $privateProgram = Program::factory()->privateAndPublished()->create(['creator_id' => $this->user->id]);

    Quiz::factory()->count(10)->create(['program_id' => $privateProgram->id]);
    Quiz::factory()->count(5)->create(['program_id' => $this->program->id]);

    $this->actingAs($this->user)
        ->getJson('quizzes')
        ->assertOk()
        ->assertJsonCount(15, 'data')
        ->assertJsonFragment([
            'message' => 'Quizzes retrieved successfully'
        ]);
});

test('user can retrieve quizzes from a draft program if they created it', function () {
    $draftProgram = Program::factory()->draft()->create(['creator_id' => $this->user->id]);

    Quiz::factory()->count(10)->create(['program_id' => $draftProgram->id]);
    Quiz::factory()->count(5)->create(['program_id' => $this->program->id]);

    $this->actingAs($this->user)
        ->getJson('quizzes')
        ->assertOk()
        ->assertJsonCount(15, 'data')
        ->assertJsonFragment([
            'message' => 'Quizzes retrieved successfully'
        ]);
});

test('user can create a new quiz if they created the program', function () {

    $this->user->update(['is_creator' => true]);

    actingAs($this->user)
        ->postJson('quizzes', [
            'program_id' => $this->program->id,
            'title' => 'New Quiz',
            'description' => 'This is a new quiz',
            'duration' => 60,
            'base_score' => 100,
            'pass_mark' => 50,
            'started_at' => now()->addDays(2),
            'ended_at' => now()->addDays(3)
        ])
        ->assertCreated()
        ->assertJsonFragment([
            'message' => 'Quiz created successfully'
        ]);
});

test('user cannot create a quiz for a program they did not create', function() {
    $program = Program::factory()->create();

    actingAs($this->user)
        ->postJson('quizzes', [
            'program_id' => $program->id,
            'title' => 'New Quiz',
            'description' => 'This is a new quiz',
            'duration' => 60,
            'base_score' => 100,
            'pass_mark' => 50,
            'started_at' => now()->addDays(2),
            'ended_at' => now()->addDays(3)
        ])
        ->assertForbidden();
});

test('user cannot update a quiz they did not create', function() {

});




