<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();
        User::factory()->create([
            'name' => 'Administrator',
            'email' => 'admin@quizland.com'
        ]);

        // Seed a new Passport client
        \DB::table('oauth_clients')->insert([
            "name" => "QuizLand Password Grant Client",
            "secret" => "FBj2AOK0cwFSn99WOHa46qVsfXX8BzduNWC1jIYt",
            'provider' => 'users',
            'redirect' => 'http://localhost',
            'personal_access_client' => 0,
            'password_client' => 1,
            'revoked' => 0,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        $this->call(SubjectSeeder::class);
        $this->call(TestSeeder::class);
    }
}
