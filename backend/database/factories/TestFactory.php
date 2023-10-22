<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Test>
 */
class TestFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->words(3, true),
            'subject_id' => $this->faker->numberBetween(1, 23),
            'duration' => $this->faker->randomElement([60, 90, 120, 30]),
            'base_score' => 100,
            'pass_mark' => 50,
            'starts_at' => now()->subDay(),
            'ends_at' => now()->addDays(3)
        ];
    }
}
