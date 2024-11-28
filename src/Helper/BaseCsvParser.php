<?php

namespace App\Helper;

abstract class BaseCsvParser
{
    protected FileOpener $fileOpener;

    public function __construct(FileOpener $fileOpener)
    {
        $this->fileOpener = $fileOpener;
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
