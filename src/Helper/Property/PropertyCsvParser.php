<?php

namespace App\Helper\Property;

use App\Helper\FileOpener;
use App\Interface\CsvParser;

class PropertyCsvParser implements CsvParser
{
  public function __construct(
    public readonly FileOpener $fileOpener
  ){}

  /**
   * Parses property CSV file
   *
   * @param string $filePath Path to the file.
   * @return array $properties.
   * @throws RuntimeException if the file cannot be opened.
   */
  public function parseFile(string $filePath): array
  {
    $properties = [];

    $handle = $this->fileOpener->open($filePath);

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
