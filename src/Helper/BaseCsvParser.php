<?php

namespace App\Helper;

abstract class BaseCsvParser
{
    public function __construct(
        public readonly FileOpener $fileOpener)
    {
    }

    public function parseFile(string $filePath): array
    {
        $handle = $this->fileOpener->open($filePath);
        $parsedRows = $this->parseRows($handle);
        fclose($handle);

        return $parsedRows;
    }

    abstract protected function parseRows($handle): array;
}
