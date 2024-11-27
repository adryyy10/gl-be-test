<?php

namespace App\Helper;

use App\Interface\MonthlyCalculatorInterface;

class MonthlyExpensesCalculator implements MonthlyCalculatorInterface
{
    public function calculate(array $expenseTransactions, int $months = 2): int
    {
        $totalRecurringExpenses = 0.0;
        foreach ($expenseTransactions as $transaction) {
            $totalRecurringExpenses += $transaction[3]; // Money out
        }

        return $totalRecurringExpenses / $months;
    }
}
