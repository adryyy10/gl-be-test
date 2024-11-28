<?php

namespace App\Helper\Transaction;

/**
 * Responsible for grouping transactions by month and details.
 * It organizes parsed transaction data into a structured format, making it easier
 * to analyze or process grouped transactions.
 */
class TransactionGrouper
{
    public function groupByMonthAndDetails(array $parsedTransactions): array
    {
        return array_reduce(
            $parsedTransactions,
            function (array $transactionGroups, array $transaction): array {
                $month = $transaction[0]->format('Y-m'); // date
                $key = $transaction[1].'|'.$transaction[2]; // Payment Type + Details
    
                $transactionGroups[$key][$month][] = $transaction;

                return $transactionGroups;
            },
            [],
        );
    }
}
