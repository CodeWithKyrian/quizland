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
            'creator_id' => User::factory(),
            'title' => fake()->sentence,
            'description' => fake()->paragraph,
            'is_public' => fake()->boolean,
            'is_published' => fake()->boolean(75),
            'published_at' => fake()->dateTime,
        ];
    }

    public function published(): self
    {
        return $this->state([
            'is_published' => true,
            'published_at' => now(),
        ]);
    }

    public function draft(): self
    {
        return $this->state([
            'is_published' => false,
            'published_at' => null,
        ]);
    }

    public function publicAndPublished(): self
    {
        return $this->state([
            'is_public' => true,
            'is_published' => true,
            'published_at' => now(),
        ]);
    }

    public function publicAndDraft(): self
    {
        return $this->state([
            'is_public' => true,
            'is_published' => false,
            'published_at' => null,
        ]);
    }

    public function privateAndPublished(): self
    {
        return $this->state([
            'is_public' => false,
            'is_published' => true,
            'published_at' => now(),
        ]);
    }
}
