<?php

namespace Database\Factories;

use App\Models\FinanceRecord;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<FinanceRecord>
 */
class FinanceRecordFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $type = fake()->randomElement(['income', 'expense']);

        return [
            'type' => $type,
            'category' => $type === 'income'
                ? fake()->randomElement(['Donasi', 'Infak', 'Sedekah'])
                : fake()->randomElement(['Operasional', 'Listrik', 'Air', 'Kebersihan', 'Renovasi']),
            'amount' => fake()->randomFloat(2, 50000, 2000000),
            'source' => 'manual',
            'donation_id' => null,
            'description' => fake()->sentence(),
            'transaction_date' => fake()->dateTimeBetween('-6 months', 'now'),
            'recorded_by' => User::factory(),
        ];
    }

    /**
     * Indicate that this is an income record.
     */
    public function income(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'income',
            'category' => fake()->randomElement(['Donasi', 'Infak', 'Sedekah']),
        ]);
    }

    /**
     * Indicate that this is an expense record.
     */
    public function expense(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'expense',
            'category' => fake()->randomElement(['Operasional', 'Listrik', 'Air', 'Kebersihan', 'Renovasi']),
        ]);
    }

    /**
     * Indicate that this record was auto-created from a donation.
     */
    public function fromDonation(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'income',
            'source' => 'donation',
            'category' => 'Donasi',
        ]);
    }
}
