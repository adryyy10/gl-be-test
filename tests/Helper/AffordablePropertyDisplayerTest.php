<?php

namespace App\Tests\Helper;

use App\Entity\Property;
use App\Helper\AffordablePropertyDisplayer;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Output\OutputInterface;

class AffordablePropertyDisplayerTest extends TestCase
{
    private AffordablePropertyDisplayer $displayer;
    private OutputInterface $output;

    protected function setUp(): void
    {
        $this->displayer = new AffordablePropertyDisplayer();
        $this->output = $this->createMock(OutputInterface::class);
    }

    public function testDisplayWithNoAffordableProperties(): void
    {
        $this->output->expects($this->never())->method('writeln');

        $this->displayer->display([], $this->output);
    }

    public function testDisplayWithAffordableProperties(): void
    {
        $property1 = $this->createProperty('123 test adri St', 1000.00);
        $property2 = $this->createProperty('456 test adri Rd', 1200.00);

        $this->output->expects($this->exactly(3))
            ->method('writeln')
            ->withConsecutive(
                ['AFFORDABLE PROPERTIES'],
                ['Address: 123 test adri St, Price: 1000.00'],
                ['Address: 456 test adri Rd, Price: 1200.00']
            );

        $this->displayer->display([$property1, $property2], $this->output);
    }

    private function createProperty(string $address, float $price): Property
    {
        $property = new Property();
        $property->setAddress($address);
        $property->setPrice($price);

        return $property;
    }
}
