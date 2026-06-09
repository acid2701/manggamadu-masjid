<?php

use App\Models\Post;
use App\Models\User;

it('can create a post', function () {
    $post = Post::factory()->create([
        'title' => 'Pengumuman Ramadhan',
        'category' => 'pengumuman',
    ]);

    expect($post->title)->toBe('Pengumuman Ramadhan')
        ->and($post->category)->toBe('pengumuman')
        ->and($post->status)->toBe('draft');
});

it('auto-generates slug from title', function () {
    $post = Post::factory()->create([
        'title' => 'Jadwal Kajian Bulan Juni',
        'slug' => '',
    ]);

    expect($post->slug)->toBe('jadwal-kajian-bulan-juni');
});

it('auto-generates excerpt from content', function () {
    $post = Post::factory()->create([
        'content' => str_repeat('Test content ', 50),
        'excerpt' => '',
    ]);

    expect($post->excerpt)->not->toBeEmpty();
});

it('can scope published posts', function () {
    Post::factory()->published()->count(2)->create();
    Post::factory()->create(['status' => 'draft']);

    expect(Post::published()->count())->toBe(2);
});

it('can scope by category', function () {
    Post::factory()->pengumuman()->count(2)->create();
    Post::factory()->berita()->create();

    expect(Post::byCategory('pengumuman')->count())->toBe(2);
});

it('belongs to an author', function () {
    $user = User::factory()->create();
    $post = Post::factory()->create(['author_id' => $user->id]);

    expect($post->author->id)->toBe($user->id);
});

it('uses slug as route key', function () {
    $post = Post::factory()->create(['slug' => 'test-slug']);

    expect($post->getRouteKeyName())->toBe('slug');
});
