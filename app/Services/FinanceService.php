<?php

namespace App\Services;

use App\Models\FinanceRecord;

class FinanceService
{
    /**
     * Get total income for a specific month.
     */
    public function getMonthlyIncome(int $year, int $month): float
    {
        return (float) FinanceRecord::income()
            ->forMonth($year, $month)
            ->sum('amount');
    }

    /**
     * Get total expense for a specific month.
     */
    public function getMonthlyExpense(int $year, int $month): float
    {
        return (float) FinanceRecord::expense()
            ->forMonth($year, $month)
            ->sum('amount');
    }

    /**
     * Get all-time balance (total income - total expense).
     */
    public function getBalance(): float
    {
        $totalIncome = (float) FinanceRecord::income()->sum('amount');
        $totalExpense = (float) FinanceRecord::expense()->sum('amount');

        return $totalIncome - $totalExpense;
    }

    /**
     * Get monthly summary (income, expense, balance for a specific month).
     *
     * @return array{income: float, expense: float, balance: float, month: int, year: int}
     */
    public function getMonthlySummary(int $year, int $month): array
    {
        $income = $this->getMonthlyIncome($year, $month);
        $expense = $this->getMonthlyExpense($year, $month);

        return [
            'income' => $income,
            'expense' => $expense,
            'balance' => $income - $expense,
            'month' => $month,
            'year' => $year,
        ];
    }

    /**
     * Get donation income for the last N months (for chart data).
     *
     * @return array<int, array{month: string, year: int, total: float}>
     */
    public function getMonthlyIncomeHistory(int $months = 6): array
    {
        $result = [];
        $date = now();

        for ($i = 0; $i < $months; $i++) {
            $year = $date->year;
            $month = $date->month;

            $result[] = [
                'month' => $date->translatedFormat('M'),
                'year' => $year,
                'total' => $this->getMonthlyIncome($year, $month),
            ];

            $date = $date->subMonth();
        }

        return array_reverse($result);
    }
}
