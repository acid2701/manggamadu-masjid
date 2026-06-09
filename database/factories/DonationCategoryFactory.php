<?php

namespace Database\Factories;

use App\Models\DonationCategory;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<DonationCategory>
 */
class DonationCategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->unique()->randomElement(['Infak', 'Sedekah', 'Zakat', 'Wakaf', 'Pembangunan', 'Operasional', 'Sosial', 'Qurban']),
            'description' => fake()->sentence(),
            'is_active' => true,
            'created_by' => User::factory(),
        ];
    }

    /**
     * Indicate that the category is inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }
}
