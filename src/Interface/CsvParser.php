<?php

namespace App\Interface;

interface CsvParser
{
  public function parseFile(string $filePath): array;
}
