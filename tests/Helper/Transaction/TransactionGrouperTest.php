<?php

namespace App\Tests\Helper\Transaction;

use App\Enum\PaymentType;
use App\Helper\Transaction\TransactionGrouper;
use PHPUnit\Framework\TestCase;

class TransactionGrouperTest extends TestCase
{
    private TransactionGrouper $transactionGrouper;

    protected function setUp(): void
    {
        $this->transactionGrouper = new TransactionGrouper();
    }

    public function testGroupByMonthAndDetailsWithEmptyTransactions(): void
    {
        $parsedTransactions = [];
        $result = $this->transactionGrouper->groupByMonthAndDetails($parsedTransactions);

        $this->assertEmpty($result, 'Grouping an empty transaction list should return an empty array.');
    }

    public function testGroupByMonthAndDetailsSingleTransaction(): void
    {
        $parsedTransactions = [
            [new \DateTime('2023-10-15'), PaymentType::CARD_PAYMENT->value, 'Credit Card Bill', 50.0],
        ];

        $result = $this->transactionGrouper->groupByMonthAndDetails($parsedTransactions);

        $expected = [
            'Card Payment|Credit Card Bill' => [
                '2023-10' => [
                    $parsedTransactions[0],
                ],
            ],
        ];

        $this->assertEquals($expected, $result);
    }

    public function testGroupByMonthAndDetailsMultipleTransactions(): void
    {
        $parsedTransactions = [
            [new \DateTime('2023-10-15'), PaymentType::CARD_PAYMENT->value, 'Credit Card Bill', 50.0],
            [new \DateTime('2023-10-20'), PaymentType::CARD_PAYMENT->value, 'Credit Card Bill', 75.0],
            [new \DateTime('2023-11-01'), PaymentType::CARD_PAYMENT->value, 'Credit Card Bill', 100.0],
            [new \DateTime('2023-11-05'), PaymentType::DIRECT_DEBIT->value, 'Mobile Phone', 25.0],
        ];

        $result = $this->transactionGrouper->groupByMonthAndDetails($parsedTransactions);

        $expected = [
            'Card Payment|Credit Card Bill' => [
                '2023-10' => [
                    $parsedTransactions[0],
                    $parsedTransactions[1],
                ],
                '2023-11' => [
                    $parsedTransactions[2],
                ],
            ],
            'Direct Debit|Mobile Phone' => [
                '2023-11' => [
                    $parsedTransactions[3],
                ],
            ],
        ];

        $this->assertEquals($expected, $result);
    }
}
