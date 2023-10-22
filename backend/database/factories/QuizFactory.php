<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Quiz>
 */
class QuizFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'program_id' => 1,
            'title' => fake()->sentence,
            'description' => fake()->paragraph,
            'slug' => fake()->slug,
            'duration' => fake()->numberBetween(10, 60),
            'base_score' => fake()->numberBetween(10, 100),
            'pass_mark' => fake()->numberBetween(10, 100),
            'started_at' => fake()->dateTimeBetween('-1 week', '+1 week'),
            'ended_at' => fake()->dateTimeBetween('-1 week', '+1 week'),
        ];
    }
}
