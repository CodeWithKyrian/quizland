<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin user
        User::factory()->hasCreatedPrograms(1, [
            'title' => 'My First Program',
            'slug' => 'my-first-program',
            'description' => 'This is my first program.',
            'is_public' => true,
            'is_published' => true,
            'published_at' => now(),
        ])->create([
            'name' => 'Administrator',
            'email' => 'admin@quizland.com',
            'is_admin' => true,
            'is_creator' => true,
        ]);

        // Regular user
        User::factory()->hasCreatedPrograms(1, [
            'title' => 'My Second Program',
            'slug' => 'my-second-program',
            'description' => 'This is my second program.',
            'is_public' => true,
            'is_published' => true,
            'published_at' => now(),
        ])->create([
            'name' => 'Regular User',
            'email' => 'regular@quizland.com',
            'is_admin' => false,
            'is_creator' => true,
        ]);
    }
}
