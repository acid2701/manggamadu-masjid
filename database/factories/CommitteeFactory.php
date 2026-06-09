<?php

namespace Database\Factories;

use App\Models\Committee;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Committee>
 */
class CommitteeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'position' => fake()->randomElement(['Ketua DKM', 'Wakil Ketua', 'Sekretaris', 'Bendahara', 'Seksi Ibadah', 'Seksi Dakwah', 'Seksi Humas']),
            'photo' => null,
            'order' => fake()->numberBetween(0, 10),
            'is_active' => true,
            'period' => '2023-2026',
        ];
    }

    /**
     * Indicate that the committee member is inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }
}
