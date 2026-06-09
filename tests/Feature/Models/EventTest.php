<?php

use App\Models\Event;
use App\Models\User;

it('can create an event', function () {
    $event = Event::factory()->create([
        'title' => 'Kajian Tafsir',
        'category' => 'Kajian',
    ]);

    expect($event->title)->toBe('Kajian Tafsir')
        ->and($event->category)->toBe('Kajian')
        ->and($event->is_active)->toBeTrue();
});

it('auto-generates slug from title', function () {
    $event = Event::factory()->create([
        'title' => 'Pengajian Rutin Jumat',
        'slug' => '',
    ]);

    expect($event->slug)->toBe('pengajian-rutin-jumat');
});

it('can scope active events', function () {
    Event::factory()->count(2)->create(['is_active' => true]);
    Event::factory()->inactive()->create();

    expect(Event::active()->count())->toBe(2);
});

it('can scope upcoming events', function () {
    Event::factory()->count(2)->create([
        'start_datetime' => now()->addDays(5),
    ]);
    Event::factory()->past()->create();

    expect(Event::upcoming()->count())->toBe(2);
});

it('belongs to a creator', function () {
    $user = User::factory()->create();
    $event = Event::factory()->create(['created_by' => $user->id]);

    expect($event->creator->id)->toBe($user->id);
});
