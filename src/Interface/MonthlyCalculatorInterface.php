<?php

namespace App\Interface;

interface MonthlyCalculatorInterface
{
    public function calculate(array $transactions, int $months = 2): int;
}
