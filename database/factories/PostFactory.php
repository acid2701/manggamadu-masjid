<?php

namespace Database\Factories;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Post>
 */
class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->sentence();

        return [
            'title' => $title,
            'slug' => Str::slug($title),
            'content' => fake()->paragraphs(5, true),
            'excerpt' => fake()->paragraph(),
            'thumbnail' => null,
            'category' => fake()->randomElement(['berita', 'pengumuman', 'kajian']),
            'status' => 'draft',
            'published_at' => null,
            'author_id' => User::factory(),
        ];
    }

    /**
     * Indicate that the post is published.
     */
    public function published(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'published',
            'published_at' => fake()->dateTimeBetween('-3 months', 'now'),
        ]);
    }

    /**
     * Indicate that the post is a pengumuman.
     */
    public function pengumuman(): static
    {
        return $this->state(fn (array $attributes) => [
            'category' => 'pengumuman',
        ]);
    }

    /**
     * Indicate that the post is a berita.
     */
    public function berita(): static
    {
        return $this->state(fn (array $attributes) => [
            'category' => 'berita',
        ]);
    }

    /**
     * Indicate that the post is a kajian.
     */
    public function kajian(): static
    {
        return $this->state(fn (array $attributes) => [
            'category' => 'kajian',
        ]);
    }
}
