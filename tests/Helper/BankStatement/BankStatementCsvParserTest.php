<?php

namespace App\Tests\Helper\BankStatement;

use App\Helper\BankStatement\BankStatementCsvParser;
use App\Helper\FileOpener;
use App\Helper\MoneyRowParser;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class BankStatementCsvParserTest extends KernelTestCase
{
    private const BANK_STATEMENT_FILE_PATH = __DIR__ . '/../../../files/bank_statement.csv';

    private FileOpener $fileOpener;
    private MoneyRowParser $moneyRowParser;
    private BankStatementCsvParser $bankStatementCsvParser;

    /** Reuse code between tests */
    protected function setUp(): void
    {
        $this->fileOpener = new FileOpener();
        $this->moneyRowParser = new MoneyRowParser();
        $this->bankStatementCsvParser = new BankStatementCsvParser($this->fileOpener, $this->moneyRowParser);
    }

    public function testHappyPath(): void
    {
        $transactions = $this->bankStatementCsvParser->parseFile(self::BANK_STATEMENT_FILE_PATH);
        $this->assertNotEmpty($transactions);

        // Date
        $this->assertNotNull($transactions[0][0]);
        $this->assertEquals($transactions[0][0]->format('Y-m-d'), '2016-10-01');

        // Payment type
        $this->assertIsString($transactions[0][1]);
        $this->assertEquals($transactions[0][1], 'ATM');

        // Address
        $this->assertIsString($transactions[0][2]);
        $this->assertEquals($transactions[0][2], 'High Street, 11:22am');

        // Money Out
        $this->assertIsFloat($transactions[0][3]);
        $this->assertEquals($transactions[0][3], 10.0);

        // Money In
        $this->assertIsFloat($transactions[0][4]);
        $this->assertEquals($transactions[0][4], 0.0);

        // Balance
        $this->assertIsFloat($transactions[0][5]);
        $this->assertEquals($transactions[0][5], 1173.0);
    }
}
