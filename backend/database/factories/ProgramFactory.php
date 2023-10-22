<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Program>
 */
class ProgramFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'created_by' => User::factory(),
            'title' => fake()->sentence,
            'slug' => fake()->slug,
            'description' => fake()->paragraph,
            'is_public' => fake()->boolean,
            'is_published' => fake()->boolean(75),
            'published_at' => fake()->dateTime,
        ];
    }
}
