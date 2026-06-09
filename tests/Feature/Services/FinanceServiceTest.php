<?php

use App\Models\FinanceRecord;
use App\Services\FinanceService;

it('calculates monthly income', function () {
    $now = now();
    FinanceRecord::factory()->income()->create([
        'amount' => 100000,
        'transaction_date' => $now,
    ]);
    FinanceRecord::factory()->income()->create([
        'amount' => 200000,
        'transaction_date' => $now,
    ]);
    FinanceRecord::factory()->expense()->create([
        'amount' => 50000,
        'transaction_date' => $now,
    ]);

    $service = app(FinanceService::class);

    expect($service->getMonthlyIncome($now->year, $now->month))->toBe(300000.0);
});

it('calculates monthly expense', function () {
    $now = now();
    FinanceRecord::factory()->expense()->create([
        'amount' => 75000,
        'transaction_date' => $now,
    ]);
    FinanceRecord::factory()->expense()->create([
        'amount' => 125000,
        'transaction_date' => $now,
    ]);

    $service = app(FinanceService::class);

    expect($service->getMonthlyExpense($now->year, $now->month))->toBe(200000.0);
});

it('calculates all-time balance', function () {
    FinanceRecord::factory()->income()->create(['amount' => 500000]);
    FinanceRecord::factory()->income()->create(['amount' => 300000]);
    FinanceRecord::factory()->expense()->create(['amount' => 200000]);

    $service = app(FinanceService::class);

    expect($service->getBalance())->toBe(600000.0);
});

it('returns monthly summary', function () {
    $now = now();
    FinanceRecord::factory()->income()->create([
        'amount' => 400000,
        'transaction_date' => $now,
    ]);
    FinanceRecord::factory()->expense()->create([
        'amount' => 150000,
        'transaction_date' => $now,
    ]);

    $service = app(FinanceService::class);
    $summary = $service->getMonthlySummary($now->year, $now->month);

    expect($summary['income'])->toBe(400000.0)
        ->and($summary['expense'])->toBe(150000.0)
        ->and($summary['balance'])->toBe(250000.0);
});

it('returns monthly income history', function () {
    $now = now();
    FinanceRecord::factory()->income()->create([
        'amount' => 100000,
        'transaction_date' => $now,
    ]);
    FinanceRecord::factory()->income()->create([
        'amount' => 200000,
        'transaction_date' => $now->copy()->subMonth(),
    ]);

    $service = app(FinanceService::class);
    $history = $service->getMonthlyIncomeHistory(3);

    expect($history)->toHaveCount(3);
});
