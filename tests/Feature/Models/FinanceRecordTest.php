<?php

use App\Models\FinanceRecord;
use App\Models\User;

it('can create a finance record', function () {
    $record = FinanceRecord::factory()->income()->create([
        'amount' => 500000,
        'category' => 'Donasi',
    ]);

    expect($record->type)->toBe('income')
        ->and($record->amount)->toBe('500000.00')
        ->and($record->category)->toBe('Donasi');
});

it('can scope income records', function () {
    FinanceRecord::factory()->income()->count(3)->create();
    FinanceRecord::factory()->expense()->count(2)->create();

    expect(FinanceRecord::income()->count())->toBe(3);
});

it('can scope expense records', function () {
    FinanceRecord::factory()->income()->count(3)->create();
    FinanceRecord::factory()->expense()->count(2)->create();

    expect(FinanceRecord::expense()->count())->toBe(2);
});

it('can scope records for a specific month', function () {
    $now = now();
    FinanceRecord::factory()->create(['transaction_date' => $now]);
    FinanceRecord::factory()->create(['transaction_date' => $now->copy()->subMonths(2)]);

    expect(FinanceRecord::forMonth(now()->year, now()->month)->count())->toBe(1);
});

it('belongs to a recorder', function () {
    $user = User::factory()->create();
    $record = FinanceRecord::factory()->create(['recorded_by' => $user->id]);

    expect($record->recorder->id)->toBe($user->id);
});
