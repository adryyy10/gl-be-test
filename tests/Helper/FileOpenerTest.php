<?php

namespace App\Tests\Helper;

use App\Helper\FileOpener;
use RuntimeException;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class FileOpenerTest extends KernelTestCase
{
    private const PROPERTIES_FILE_PATH = __DIR__ . '/../../files/properties.csv';
    private const PROPERTIES_FILE_INVALID_PATH = __DIR__ . '/../../files/invalid-file-path.csv';

    private FileOpener $fileOpener;

    protected function setUp(): void
    {
        $this->fileOpener = new FileOpener();
    }


    public function testHappyPath(): void
    {
        $handle = $this->fileOpener->open(self::PROPERTIES_FILE_PATH);
        $this->assertNotFalse($handle);
    }

    public function testParseInvalidFilePath(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage(sprintf('Failed to open the file: %s', self::PROPERTIES_FILE_INVALID_PATH));

        $this->fileOpener->open(self::PROPERTIES_FILE_INVALID_PATH);
    }
}
