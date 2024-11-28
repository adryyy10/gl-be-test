<?php

namespace App\Helper\Transaction;

/**
 * This class is responsible for identifying recurring income and expense transactions
 * from grouped transaction data. Recurring transactions are those that appear in at least
 * two distinct months.
 */
class RecurringTransactionIdentifier
{
    public function identifyRecurringTransaction(array $groupedTransactions): array
    {
        $incomeTransactions = [];
        $expenseTransactions = [];

        $recurringGroups = array_filter($groupedTransactions, fn($months) => count($months) >= 2);
        
        foreach ($recurringGroups as $months) {
            $allTransactions = array_merge(...array_values($months)); // Flatten array

            $incomeTransactions = array_merge(
                $incomeTransactions,
                array_filter($allTransactions, fn($transaction) => $transaction[4] > 0) // Filter Money In
            );

            $expenseTransactions = array_merge(
                $expenseTransactions,
                array_filter($allTransactions, fn($transaction) => $transaction[3] > 0) // Filter Money Out
            );
        }

        return [$incomeTransactions, $expenseTransactions];
    }
}
