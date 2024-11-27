<?php

namespace App\Tests\Helper;

use App\Enum\PaymentType;
use App\Helper\MonthlyExpensesCalculator;
use PHPUnit\Framework\TestCase;

class MonthlyExpensesCalculatorTest extends TestCase
{
    private MonthlyExpensesCalculator $calculator;

    protected function setUp(): void
    {
        $this->calculator = new MonthlyExpensesCalculator();
    }

    public function testCalculateEmptyIncomeTransactions(): void
    {
        $expenseTransactions = [];
        $result = $this->calculator->calculate($expenseTransactions);

        $this->assertEquals(0, $result);
    }

    public function testCalculateSingleTransaction(): void
    {
        $expenseTransactions = [
            [new \DateTime('2023-10-15'), PaymentType::BANK_CREDIT->value, 'From flatmate for phone and broadband', 2000.0, 0],
        ];
        $result = $this->calculator->calculate($expenseTransactions);

        $this->assertEquals(1000, $result);
    }

    public function testCalculateMultipleTransactions(): void
    {
        $expenseTransactions = [
            [new \DateTime('2023-10-15'), PaymentType::BANK_CREDIT->value, 'From flatmate for phone and broadband', 2000.0, 0],
            [new \DateTime('2023-11-15'), PaymentType::BANK_CREDIT->value, 'From flatmate for phone and broadband', 3000.0, 0],
        ];
        $result = $this->calculator->calculate($expenseTransactions);

        $this->assertEquals(2500, $result);
    }

    public function testCalculateCustomMonths(): void
    {
        $expenseTransactions = [
            [new \DateTime('2023-10-15'), PaymentType::BANK_CREDIT->value, 'From flatmate for phone and broadband', 2000.0, 0],
            [new \DateTime('2023-11-15'), PaymentType::BANK_CREDIT->value, 'From flatmate for phone and broadband', 3000.0, 0],
        ];
        $result = $this->calculator->calculate($expenseTransactions, 4);

        $this->assertEquals(1250, $result);
    }
}
