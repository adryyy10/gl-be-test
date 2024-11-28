<?php

namespace App\Helper\Property;

use App\Helper\BaseCsvParser;
use App\Interface\CsvParser;

class PropertyCsvParser extends BaseCsvParser implements CsvParser
{
    protected function parseRows($handle): array
    {
        $properties = [];
        fgetcsv($handle); // Skip header

        while (($row = fgetcsv($handle)) !== false) {
            $properties[] = [
                (int) $row[0],
                $row[1],
                (int) $row[2],
            ];
        }

        return $properties;
    }
}
