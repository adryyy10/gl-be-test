<?php

namespace App\Tests\Helper;

use App\Helper\MoneyRowParser;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class MoneyRowParserTest extends KernelTestCase
{
    private MoneyRowParser $moneyRowParser;

    protected function setUp(): void
    {
        $this->moneyRowParser = new MoneyRowParser();
    }

    public function testParse(): void
    {
        $result = $this->moneyRowParser->parse('£8.50,');
        $this->assertEquals(8.50, $result);

        $result = $this->moneyRowParser->parse('£8.50,,,,,,');
        $this->assertEquals(8.50, $result);

        $result = $this->moneyRowParser->parse('£££££8.50');
        $this->assertEquals(8.50, $result);
    }
}
