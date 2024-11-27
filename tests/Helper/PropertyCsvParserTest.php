<?php

namespace App\Tests\Helper;

use App\Helper\PropertyCsvParser;
use RuntimeException;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class PropertyCsvParserTest extends KernelTestCase
{
    private const PROPERTIES_FILE_PATH = __DIR__ . '/../../files/properties.csv';
    private const PROPERTIES_FILE_INVALID_PATH = __DIR__ . '/../../files/invalid-file-path.csv';

    private PropertyCsvParser $propertyCsvParser;

    /** Reuse code between tests */
    protected function setUp(): void
    {
        $this->propertyCsvParser = new PropertyCsvParser();
    }

    public function testHappyPath(): void
    {
        $properties = $this->propertyCsvParser->parseFile(self::PROPERTIES_FILE_PATH);
        $this->assertNotEmpty($properties);

        // Id
        $this->assertIsInt($properties[0][0]);
        $this->assertEquals($properties[0][0], 1);

        // Address
        $this->assertEquals($properties[0][1], '99  Brackley Road, KW17 9QS');
        $this->assertIsString($properties[0][1]);

        // Price
        $this->assertEquals($properties[0][2], 300);
        $this->assertIsInt($properties[0][2]);
    }

    public function testParseInvalidFilePath(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage(sprintf('Failed to open the file: %s', self::PROPERTIES_FILE_INVALID_PATH));

        $this->propertyCsvParser->parseFile(self::PROPERTIES_FILE_INVALID_PATH);
    }
}
