<?php

namespace App\Tests\Helper;

use App\Enum\PaymentType;
use App\Helper\MonthlyIncomeCalculator;
use PHPUnit\Framework\TestCase;

class MonthlyIncomeCalculatorTest extends TestCase
{
    private MonthlyIncomeCalculator $calculator;

    protected function setUp(): void
    {
        $this->calculator = new MonthlyIncomeCalculator();
    }

    public function testCalculateEmptyIncomeTransactions(): void
    {
        $incomeTransactions = [];
        $result = $this->calculator->calculate($incomeTransactions);

        $this->assertEquals(0, $result);
    }

    public function testCalculateSingleTransaction(): void
    {
        $incomeTransactions = [
            [new \DateTime('2023-10-15'), PaymentType::BANK_CREDIT->value, 'From flatmate for phone and broadband', 0, 2000.0],
        ];
        $result = $this->calculator->calculate($incomeTransactions);

        $this->assertEquals(1000, $result);
    }

    public function testCalculateMultipleTransactions(): void
    {
        $incomeTransactions = [
            [new \DateTime('2023-10-15'), PaymentType::BANK_CREDIT->value, 'From flatmate for phone and broadband', 0, 2000.0],
            [new \DateTime('2023-11-15'), PaymentType::BANK_CREDIT->value, 'From flatmate for phone and broadband', 0, 3000.0],
        ];
        $result = $this->calculator->calculate($incomeTransactions);

        $this->assertEquals(2500, $result);
    }

    public function testCalculateCustomMonths(): void
    {
        $incomeTransactions = [
            [new \DateTime('2023-10-15'), PaymentType::BANK_CREDIT->value, 'From flatmate for phone and broadband', 0, 2000.0],
            [new \DateTime('2023-11-15'), PaymentType::BANK_CREDIT->value, 'From flatmate for phone and broadband', 0, 3000.0],
        ];
        $result = $this->calculator->calculate($incomeTransactions, 4);

        $this->assertEquals(1250, $result);
    }
}
