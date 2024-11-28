<?php

namespace App\Helper;

class MonthlyExpensesCalculator extends BaseMonthlyCalculator
{
    public function getRelevantField(array $transaction): string
    {
        return $transaction[3];
    }
}
