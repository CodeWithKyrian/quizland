<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()->hasCreatedPrograms(1, [
            'title' => 'My First Program',
            'slug' => 'my-first-program',
            'description' => 'This is my first program.',
            'is_public' => true,
            'is_published' => true,
            'published_at' => now(),
        ])->create([
            'name' => 'Administrator',
            'email' => 'admin@quizland.com'
        ]);



    }
}
