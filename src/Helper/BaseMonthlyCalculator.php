<?php

namespace App\Helper;

abstract class BaseMonthlyCalculator
{
    public function calculate(array $transactions, int $months = 2) {
        $total = 0.0;
        foreach ($transactions as $transaction) {
            $total += $this->getRelevantField($transaction);
        }

        return $total / $months;
    }

    abstract function getRelevantField(array $transaction): string;
}
