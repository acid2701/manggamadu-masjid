<?php

namespace Database\Factories;

use App\Models\Event;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Event>
 */
class EventFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->sentence(4);
        $startDate = fake()->dateTimeBetween('+1 day', '+3 months');

        return [
            'title' => $title,
            'slug' => Str::slug($title),
            'description' => fake()->paragraphs(3, true),
            'location' => 'Masjid Sholikin, Potronayan',
            'category' => fake()->randomElement(['Kajian', 'Pengajian', 'Event']),
            'poster' => null,
            'start_datetime' => $startDate,
            'end_datetime' => fake()->optional(0.7)->dateTimeBetween($startDate, (clone $startDate)->modify('+4 hours')),
            'is_active' => true,
            'created_by' => User::factory(),
        ];
    }

    /**
     * Indicate that the event is in the past.
     */
    public function past(): static
    {
        return $this->state(fn (array $attributes) => [
            'start_datetime' => fake()->dateTimeBetween('-3 months', '-1 day'),
            'end_datetime' => null,
        ]);
    }

    /**
     * Indicate that the event is inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }
}
