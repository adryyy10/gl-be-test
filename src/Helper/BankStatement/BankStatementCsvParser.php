<?php

namespace App\Helper\BankStatement;

use App\Helper\FileOpener;
use App\Interface\CsvParser;

class BankStatementCsvParser implements CsvParser
{
    public function __construct(
        public readonly FileOpener $fileOpener
    ){}

    public function parseFile(string $filePath): array
    {
        $handle = $this->fileOpener->open($filePath);

        $transactions = [];

        // Skip the unnecessary lines at the top
        for ($i = 0; $i < 11; $i++) {
            fgetcsv($handle);
        }
    
        while (($data = fgetcsv($handle)) !== false) {
            if (empty(array_filter($data))) {
                continue; // Skip possible empty lines
            }
    
            $date = \DateTime::createFromFormat('jS F Y', $data[0]);
            $paymentType = $data[1];
            $details = $data[2];
            $moneyOut = !empty($data[3]) ? (float)str_replace(['£', ','], '', $data[3]) : 0.0;
            $moneyIn = !empty($data[4]) ? (float)str_replace(['£', ','], '', $data[4]) : 0.0;
            $balance = !empty($data[5]) ? (float)str_replace(['£', ','], '', $data[5]) : 0.0;
    
            $transactions[] = [$date, $paymentType, $details, $moneyOut, $moneyIn, $balance];
            //$transactions[] = new Transaction($date, $paymentType, $details, $moneyOut, $moneyIn);
        }
        fclose($handle);

        return $transactions;
    }
}
