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
        foreach ($groupedTransactions as $group => $months) {
            if (count($months) >= 2) { // Appears in at least two months
                foreach ($months as $month => $transactionsInMonth) {
                    foreach ($transactionsInMonth as $transaction) {
                        if ($transaction[4] > 0) { // $transaction[4] = Money In
                            $incomeTransactions[] = $transaction;
                        } elseif ($transaction[3] > 0) { // $transaction[3] = Money Out
                            $expenseTransactions[] = $transaction;
                        }
                    }
                }
            }
        }

        return [$incomeTransactions, $expenseTransactions];
    }
}
