<?php

declare(strict_types=1);

use App\Models\User;

it('can register a user', function () {
    $response = $this->postJson('register', [
        'name' => 'John Doe',
        'email' => 'johndoe@quizland.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $response
        ->assertStatus(200)
        ->assertJsonStructure(['access_token', 'refresh_token',]);

    $this
        ->assertDatabaseHas('users', ['name' => 'John Doe', 'email' => 'johndoe@quizland.com'])
        ->assertDatabaseHas('oauth_access_tokens', ['user_id' => 1]);
});

it('cannot register a user with an existing email', function () {

    User::factory()->create(['email' => 'johndoe@quizland.com']);

    $response = $this->postJson('register', [
        'name' => 'John Doe',
        'email' => 'johndoe@quizland.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $response
        ->assertStatus(422)
        ->assertJsonStructure(['message', 'errors' => ['email']]);

    $this
        ->assertDatabaseMissing('users', ['name' => 'John Doe', 'email' => 'johndoe@quizland.com'])
        ->assertDatabaseMissing('oauth_access_tokens', ['user_id' => 1]);

});

it('cannot register a user with an invalid email', function () {

    $response = $this->postJson('register', [
        'name' => 'John Doe',
        'email' => 'johndoe',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $response
        ->assertStatus(422)
        ->assertJsonStructure(['message', 'errors' => ['email']]);

});

it('cannot register a user with a password that is too short', function () {

    $response = $this->postJson('register', [
        'name' => 'John Doe',
        'email' => 'johndoe@quizland.com',
        'password' => 'pass',
        'password_confirmation' => 'pass',
    ]);

    $response
        ->assertStatus(422)
        ->assertJsonStructure(['message', 'errors' => ['password']]);

});

it('cannot register a user with a password that does not match the password confirmation', function () {

    $response = $this->postJson('register', [
        'name' => 'John Doe',
        'email' => 'johndoe@quizland.com',
        'password' => 'password',
        'password_confirmation' => 'pass',
    ]);

    $response
        ->assertStatus(422)
        ->assertJsonStructure(['message', 'errors' => ['password']]);

});

it('can login a user', function () {

    User::factory()->create(['email' => 'johndoe@quizland.com', 'password' => bcrypt('password')]);

    $response = $this->postJson('login', [
        'email' => 'johndoe@quizland.com',
        'password' => 'password',
    ]);

    $response
        ->assertStatus(200)
        ->assertJsonStructure(['access_token', 'refresh_token',]);

    $this
        ->assertDatabaseHas('oauth_access_tokens', ['user_id' => 1]);

});

it('cannot login a user with an invalid email', function () {

    User::factory()->create(['email' => 'johndoe@quizland.com', 'password' => bcrypt('password')]);

    $response = $this->postJson('login', [
        'email' => 'anndoe@gmail.com',
        'password' => 'password',
    ]);

    $response
        ->assertStatus(422)
        ->assertJsonStructure(['message', 'errors' => ['email']]);

});

it('cannot login a user with an invalid password', function () {

    User::factory()->create(['email' => 'johndoe@quizland.com', 'password' => bcrypt('password')]);

    $response = $this->postJson('login', [
        'email' => 'johndoe@quizland.com',
        'password' => 'pass',
    ]);

    $response
        ->assertStatus(422)
        ->assertJsonStructure(['message', 'errors' => ['password']]);

});

it('should not allow unauthorized logout', function () {
    $this->postJson('logout')->assertStatus(401);
});
