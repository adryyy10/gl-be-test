<?php

namespace App\Command;

use App\Helper\AffordablePropertyDisplayer;
use App\Helper\AffordablePropertyFinder;
use App\Helper\BankStatement\BankStatementCsvParser;
use App\Helper\MonthlyExpensesCalculator;
use App\Helper\MonthlyIncomeCalculator;
use App\Helper\Property\PropertyBatchHelper;
use App\Helper\Property\PropertyCsvParser;
use App\Helper\Transaction\RecurringTransactionIdentifier;
use App\Helper\Transaction\TransactionGrouper;
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
    public function __construct(
        public readonly AffordablePropertyDisplayer $affordablePropertyDisplayer,
        public readonly AffordablePropertyFinder $affordablePropertyFinder,
        public readonly BankStatementCsvParser $bankStatementCsvParser,
        public readonly MonthlyIncomeCalculator $monthlyIncomeCalculator,
        public readonly MonthlyExpensesCalculator $monthlyExpensesCalculator,
        public readonly PropertyBatchHelper $propertyBatchHelper,
        public readonly PropertyCsvParser $propertyCsvParser,
        public readonly RecurringTransactionIdentifier $recurringTransactionIdentifier,
        public readonly TransactionGrouper $transactionGrouper,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('bankStatementFile', InputArgument::REQUIRED, 'Path to the bank statement CSV file')
            ->addArgument('propertiesFile', InputArgument::REQUIRED, 'Path to the properties CSV file');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        // Parse properties
        $propertiesFile = $input->getArgument('propertiesFile');
        $parsedProperties = $this->propertyCsvParser->parseFile($propertiesFile);
        $output->writeln('Properties successfully parsed.');

        // Consider adding properties to database
        $this->propertyBatchHelper->considerAddingProperties($output, $parsedProperties);
        $output->writeln('Properties successfully added to the database.');

        // Parse bank statement
        $bankStatementFile = $input->getArgument('bankStatementFile');
        $parsedTransactions = $this->bankStatementCsvParser->parseFile($bankStatementFile);
        $output->writeln('Bank statement successfully parsed.');

        // Group transactions by month and details
        $groupedTransactions = $this->transactionGrouper->groupByMonthAndDetails($parsedTransactions);

        // Identify monthly recurring income and expenses
        list($incomeTransactions, $expenseTransactions) = $this->recurringTransactionIdentifier->identifyRecurringTransaction($groupedTransactions);

        // Calculate total monthly recurring income
        $averageMonthlyIncome = $this->monthlyIncomeCalculator->calculate($incomeTransactions);

        // Calculate total monthly recurring income
        $averageMonthlyExpenses = $this->monthlyExpensesCalculator->calculate($expenseTransactions);

        // Perform affordability check
        $affordableProperties = $this->affordablePropertyFinder->getAffordableProperties($averageMonthlyIncome, $averageMonthlyExpenses);

        // Display afforable properties
        $this->affordablePropertyDisplayer->display($affordableProperties, $output);

        return Command::SUCCESS;
    }
}
