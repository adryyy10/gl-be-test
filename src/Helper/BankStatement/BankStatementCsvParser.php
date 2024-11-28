<?php

namespace App\Helper\BankStatement;

use App\Helper\BaseCsvParser;
use App\Helper\FileOpener;
use App\Helper\MoneyRowParser;

class BankStatementCsvParser extends BaseCsvParser
{

    public function __construct(
        FileOpener $fileOpener,
        public readonly MoneyRowParser $moneyRowParser,
    )
    {
        parent::__construct($fileOpener);
    }

    protected function parseRows($handle): array
    {
        $transactions = [];

        // Skip the unnecessary lines at the top
        for ($i = 0; $i < 11; ++$i) {
            fgetcsv($handle);
        }

        while (($data = fgetcsv($handle)) !== false) {
            if (empty(array_filter($data))) {
                continue; // Skip possible empty lines
            }

            $date = \DateTime::createFromFormat('jS F Y', $data[0]);
            $paymentType = $data[1];
            $details = $data[2];
            $moneyOut = $this->moneyRowParser->parse($data[3]);
            $moneyIn = $this->moneyRowParser->parse($data[4]);
            $balance = $this->moneyRowParser->parse($data[5]);

            $transactions[] = [$date, $paymentType, $details, $moneyOut, $moneyIn, $balance];
            // $transactions[] = new Transaction($date, $paymentType, $details, $moneyOut, $moneyIn);
        }

        return $transactions;
    }
}
