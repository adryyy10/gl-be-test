<?php

namespace App\Helper;

use RuntimeException;

class FileOpener
{
    /**
     * Opens a file and returns the file handle.
     *
     * @param string $filePath Path to the file.
     * @return resource File handle.
     * @throws RuntimeException if the file cannot be opened.
     */
    public function open(string $filePath): mixed
    {
        if (($handle = @fopen($filePath, 'r')) === false) {
            throw new RuntimeException(sprintf('Failed to open the file: %s', $filePath));
        }
        return $handle;
    }
}
