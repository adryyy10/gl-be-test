<?php

namespace App\Helper;

use App\Interface\CsvParser;
use RuntimeException;

class PropertyCsvParser implements CsvParser
{
  public function parseFile(string $filePath): array
  {
    $properties = [];
    if (($handle = @fopen($filePath, 'r')) === false) {
      throw new RuntimeException(sprintf('Failed to open the file: %s', $filePath));
    }

    fgetcsv($handle); // Skip header
    while (($property = fgetcsv($handle)) !== false) {
        $id = (int)$property[0];
        $address = $property[1];
        $price = (int)$property[2];

        $properties[] = [$id, $address, $price];
    }
    fclose($handle);

    return $properties;
  }
}
