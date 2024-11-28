<?php

namespace App\Helper;

class MonthlyIncomeCalculator extends BaseMonthlyCalculator
{
    public function getRelevantField(array $transaction): string
    {
        return $transaction[4];
    }
}
