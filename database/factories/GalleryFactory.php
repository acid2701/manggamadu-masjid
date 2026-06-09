<?php

namespace Database\Factories;

use App\Models\Gallery;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Gallery>
 */
class GalleryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->sentence(3),
            'description' => fake()->optional(0.7)->sentence(),
            'image_path' => 'galleries/placeholder-'.fake()->unique()->numberBetween(1, 100).'.jpg',
            'order' => fake()->numberBetween(0, 50),
            'is_active' => true,
            'uploaded_by' => User::factory(),
        ];
    }

    /**
     * Indicate that the gallery item is inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }
}
