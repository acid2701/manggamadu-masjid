<?php

use App\Models\Donation;
use App\Models\DonationCategory;
use App\Models\User;

it('can create a donation category', function () {
    $category = DonationCategory::factory()->create([
        'name' => 'Infak',
        'description' => 'Infak umum masjid',
    ]);

    expect($category->name)->toBe('Infak')
        ->and($category->description)->toBe('Infak umum masjid')
        ->and($category->is_active)->toBeTrue();
});

it('has a creator relationship', function () {
    $user = User::factory()->create();
    $category = DonationCategory::factory()->create(['created_by' => $user->id]);

    expect($category->creator->id)->toBe($user->id);
});

it('has many donations', function () {
    $category = DonationCategory::factory()->create();
    Donation::factory()->count(3)->create(['donation_category_id' => $category->id]);

    expect($category->donations)->toHaveCount(3);
});

it('can scope active categories', function () {
    DonationCategory::factory()->count(2)->create(['is_active' => true]);
    DonationCategory::factory()->create(['is_active' => false]);

    expect(DonationCategory::active()->count())->toBe(2);
});
