<?php

declare(strict_types=1);


use App\Models\Program;
use App\Models\User;
use function Pest\Laravel\actingAs;


it('can retrieve a paginated list of all available programs', function () {
    Program::factory(10)->create([
        ''
    ]);

    actingAs(User::factory()->create())
        ->getJson('programs')
        ->assertStatus(200)
        ->assertJsonCount(10, 'data')
        ->assertJsonFragment([
            'message' => 'Programs retrieved successfully',
        ]);
});
