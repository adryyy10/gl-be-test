<?php

namespace App\Tests\Helper\Property;

use App\Helper\FileOpener;
use App\Helper\Property\PropertyCsvParser;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class PropertyCsvParserTest extends KernelTestCase
{
    private const PROPERTIES_FILE_PATH = __DIR__ . '/../../../files/properties.csv';

    private FileOpener $fileOpener;
    private PropertyCsvParser $propertyCsvParser;

    /** Reuse code between tests */
    protected function setUp(): void
    {
        $this->fileOpener = new FileOpener();
        $this->propertyCsvParser = new PropertyCsvParser($this->fileOpener);
    }

    public function testHappyPath(): void
    {
        $properties = $this->propertyCsvParser->parseFile(self::PROPERTIES_FILE_PATH);
        $this->assertNotEmpty($properties);

        // Id
        $this->assertIsInt($properties[0][0]);
        $this->assertEquals($properties[0][0], 1);

        // Address
        $this->assertIsString($properties[0][1]);
        $this->assertEquals($properties[0][1], '99  Brackley Road');

        // Postcode
        $this->assertIsString($properties[0][2]);
        $this->assertEquals($properties[0][2], 'KW17 9QS');

        // Price
        $this->assertIsInt($properties[0][3]);
        $this->assertEquals($properties[0][3], 300);
    }
}
