<?php

namespace App\Helper;

class FileOpener
{
    /**
     * Opens a file and returns the file handle.
     *
     * @param string $filePath path to the file
     *
     * @return resource file handle
     *
     * @throws \RuntimeException if the file cannot be opened
     */
    public function open(string $filePath): mixed
    {
        if (($handle = @fopen($filePath, 'r')) === false) {
            throw new \RuntimeException(sprintf('Failed to open the file: %s', $filePath));
        }

        return $handle;
    }
}
