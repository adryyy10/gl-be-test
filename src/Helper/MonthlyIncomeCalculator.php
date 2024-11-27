<?php

namespace App\Helper;

use App\Interface\MonthlyCalculatorInterface;

class MonthlyIncomeCalculator implements MonthlyCalculatorInterface
{
    public function calculate(array $incomeTransactions, int $months = 2): int
    {
        $totalRecurringIncome = 0.0;
        foreach ($incomeTransactions as $transaction) {
            $totalRecurringIncome += $transaction[4]; // Money In
        }

        return $totalRecurringIncome / $months;
    }
}
