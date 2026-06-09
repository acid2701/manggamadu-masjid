<?php

use App\Models\Donation;
use App\Models\DonationCategory;
use App\Models\FinanceRecord;
use App\Models\User;

it('can create a donation', function () {
    $donation = Donation::factory()->create([
        'donor_name' => 'Ahmad',
        'amount' => 150000,
    ]);

    expect($donation->donor_name)->toBe('Ahmad')
        ->and($donation->amount)->toBe('150000.00')
        ->and($donation->status)->toBe('pending');
});

it('belongs to a donation category', function () {
    $category = DonationCategory::factory()->create();
    $donation = Donation::factory()->create(['donation_category_id' => $category->id]);

    expect($donation->category->id)->toBe($category->id);
});

it('belongs to an approver', function () {
    $user = User::factory()->create();
    $donation = Donation::factory()->approved()->create(['approved_by' => $user->id]);

    expect($donation->approver->id)->toBe($user->id);
});

it('has a finance record relationship', function () {
    $donation = Donation::factory()->approved()->create();
    $record = FinanceRecord::factory()->fromDonation()->create([
        'donation_id' => $donation->id,
        'recorded_by' => $donation->approved_by,
    ]);

    expect($donation->financeRecord->id)->toBe($record->id);
});

it('can scope pending donations', function () {
    Donation::factory()->count(2)->create(['status' => 'pending']);
    Donation::factory()->approved()->create();

    expect(Donation::pending()->count())->toBe(2);
});

it('can scope approved donations', function () {
    Donation::factory()->create(['status' => 'pending']);
    Donation::factory()->approved()->count(3)->create();

    expect(Donation::approved()->count())->toBe(3);
});

it('detects anonymous donor', function () {
    $anonymous = Donation::factory()->anonymous()->create();
    $named = Donation::factory()->create(['donor_name' => 'Budi']);

    expect($anonymous->isAnonymous())->toBeTrue()
        ->and($anonymous->display_name)->toBe('Anonim')
        ->and($named->isAnonymous())->toBeFalse()
        ->and($named->display_name)->toBe('Budi');
});
