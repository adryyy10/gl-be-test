<?php

namespace App\Tests\Command;

use RuntimeException;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;

class AffordabilityCheckTest extends KernelTestCase
{
    private const BANK_STATEMENT_FILE_PATH = __DIR__ . '/../../files/bank_statement.csv';
    private const PROPERTIES_FILE_PATH = __DIR__ . '/../../files/properties.csv';

    private CommandTester $commandTester;

    /** Reuse code between tests */
    protected function setUp(): void
    {
        self::bootKernel();
        $application = new Application(self::$kernel);
        $command = $application->find('app:affordability-check');
        $this->commandTester = new CommandTester($command);   
    }

    public function testHappyPath(): void
    {
        $this->commandTester->execute([
            'bankStatementFile' => self::BANK_STATEMENT_FILE_PATH,
            'propertiesFile' => self::PROPERTIES_FILE_PATH,
        ]);
    
        $this->commandTester->assertCommandIsSuccessful();
    }

    public function testMissingPropertyArgument(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Not enough arguments (missing: "propertiesFile").');

        $this->commandTester->execute([
            'bankStatementFile' => self::BANK_STATEMENT_FILE_PATH,
        ]);
    }

    public function testMissingBankStatementArgument(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Not enough arguments (missing: "bankStatementFile").');

        $this->commandTester->execute([
            'propertiesFile' => self::PROPERTIES_FILE_PATH,
        ]);
    }
}
