<?php

namespace App\Tests\Helper\Transaction;

use App\Enum\PaymentType;
use App\Helper\Transaction\RecurringTransactionIdentifier;
use PHPUnit\Framework\TestCase;

class RecurringTransactionIdentifierTest extends TestCase
{
    private RecurringTransactionIdentifier $transactionIdentifier;

    protected function setUp(): void
    {
        $this->transactionIdentifier = new RecurringTransactionIdentifier();
    }

    public function testIdentifyRecurringTransactionEmptyGroupedTransactions(): void
    {
        $groupedTransactions = [];
        [$incomeTransactions, $expenseTransactions] = $this->transactionIdentifier->identifyRecurringTransaction($groupedTransactions);

        $this->assertEmpty($incomeTransactions);
        $this->assertEmpty($expenseTransactions);
    }

    public function testIdentifyRecurringTransactionSingleGroup(): void
    {
        $groupedTransactions = [
            'Bank Credit|From flatmate for phone and broadband' => [
                '2023-10' => [
                    [new \DateTime('2023-10-15'), PaymentType::BANK_CREDIT->value, 'From flatmate for phone and broadband', 0, 2000.0],
                ],
                '2023-11' => [
                    [new \DateTime('2023-11-15'), PaymentType::BANK_CREDIT->value, 'From flatmate for phone and broadband', 0, 2000.0],
                ],
            ],
        ];

        [$incomeTransactions, $expenseTransactions] = $this->transactionIdentifier->identifyRecurringTransaction($groupedTransactions);

        $this->assertCount(2, $incomeTransactions);
        $this->assertEmpty($expenseTransactions);
        $this->assertEquals(2000.0, $incomeTransactions[0][4]);
        $this->assertEquals(2000.0, $incomeTransactions[1][4]);
    }

    public function testIdentifyRecurringTransactionMultipleGroups(): void
    {
        $groupedTransactions = [
            'Direct Debit|Mobile Phone Insurance' => [
                '2023-10' => [
                    [new \DateTime('2023-10-01'), PaymentType::DIRECT_DEBIT->value, 'Mobile Phone Insurance', 1000.0, 0],
                ],
                '2023-11' => [
                    [new \DateTime('2023-11-01'), PaymentType::DIRECT_DEBIT->value, 'Mobile Phone Insurance', 1000.0, 0],
                ],
            ],
            'Bank Credit|From flatmate for phone and broadband' => [
                '2023-10' => [
                    [new \DateTime('2023-10-15'), PaymentType::BANK_CREDIT->value, 'From flatmate for phone and broadband', 0, 2000.0],
                ],
                '2023-11' => [
                    [new \DateTime('2023-11-15'), PaymentType::BANK_CREDIT->value, 'From flatmate for phone and broadband', 0, 2000.0],
                ],
            ],
            'Bank Credit|From flatmate for TV licence' => [
                '2023-11' => [
                    [new \DateTime('2023-11-05'), PaymentType::BANK_CREDIT->value, 'From flatmate for TV licence', 150.0, 0],
                ],
            ],
        ];

        [$incomeTransactions, $expenseTransactions] = $this->transactionIdentifier->identifyRecurringTransaction($groupedTransactions);

        $this->assertCount(2, $incomeTransactions);
        $this->assertCount(2, $expenseTransactions);

        $this->assertEquals(2000.0, $incomeTransactions[0][4]);
        $this->assertEquals(1000.0, $expenseTransactions[0][3]);
    }
}
