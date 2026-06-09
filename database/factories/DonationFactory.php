<?php

namespace Database\Factories;

use App\Models\Donation;
use App\Models\DonationCategory;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Donation>
 */
class DonationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'donation_category_id' => DonationCategory::factory(),
            'donor_name' => fake()->optional(0.8)->name(),
            'amount' => fake()->randomFloat(2, 10000, 5000000),
            'proof_image' => null,
            'message' => fake()->optional(0.6)->sentence(),
            'status' => 'pending',
            'rejection_note' => null,
            'approved_by' => null,
            'approved_at' => null,
        ];
    }

    /**
     * Indicate that the donation is approved.
     */
    public function approved(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'approved',
            'approved_by' => User::factory(),
            'approved_at' => now(),
        ]);
    }

    /**
     * Indicate that the donation is rejected.
     */
    public function rejected(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'rejected',
            'rejection_note' => fake()->sentence(),
        ]);
    }

    /**
     * Indicate that the donor is anonymous.
     */
    public function anonymous(): static
    {
        return $this->state(fn (array $attributes) => [
            'donor_name' => null,
        ]);
    }
}
