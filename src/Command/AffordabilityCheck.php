<?php
namespace App\Command;

use App\helper\PropertyCsvParser;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:affordability-check',
    description: 'Performs an affordability check based on bank statement and properties'
)]
class AffordabilityCheck extends Command
{
    protected function configure(): void
    {
        $this
            ->addArgument('bankStatementFile', InputArgument::REQUIRED, 'Path to the bank statement CSV file')
            ->addArgument('propertiesFile', InputArgument::REQUIRED, 'Path to the properties CSV file');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $propertiesFile = $input->getArgument('propertiesFile');
        $bankStatementFile = $input->getArgument('bankStatementFile');

        return Command::SUCCESS;
    }
}
