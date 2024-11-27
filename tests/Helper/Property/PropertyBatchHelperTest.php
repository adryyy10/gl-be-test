<?php

namespace App\Tests\Helper\Property;

use App\Entity\Property;
use App\Helper\Property\PropertyBatchHelper;
use App\Repository\PropertyRepository;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Output\OutputInterface;

class PropertyBatchHelperTest extends TestCase
{
    private EntityManagerInterface $entityManager;
    private PropertyRepository $propertyRepository;
    private OutputInterface $output;
    private PropertyBatchHelper $propertyBatchHelper;

    protected function setUp(): void
    {
        // Mock PropertyRepository
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->propertyRepository = $this->createMock(PropertyRepository::class);
        $this->entityManager->expects($this->any())
            ->method('getRepository')
            ->willReturn($this->propertyRepository);

        $this->output = $this->createMock(OutputInterface::class);
        $this->propertyBatchHelper = new PropertyBatchHelper($this->entityManager);
    }

    public function testInsertNewProperties(): void
    {
        $properties = [
            [1, '123 Fake Street', 100],
            [2, '456 Test Street', 200],
        ];

        // There are no properties in DB yet so it should return null
        $this->propertyRepository->expects($this->exactly(2))
            ->method('find')
            ->willReturn(null);

        $this->output->expects($this->exactly(2))
            ->method('writeln')
            ->withConsecutive(
                ['Inserted property with id: 1'],
                ['Inserted property with id: 2']
            );

        $this->propertyBatchHelper->considerAddingProperties($this->output, $properties);
    }

    public function testSkipAlreadyInsertedProperties(): void
    {
        $properties = [
            [1, '123 Fake Street', 100000.0],
            [2, '456 Elm Street', 200000.0],
        ];

        // Mock existing properties
        $this->propertyRepository->expects($this->exactly(2))
            ->method('find')
            ->willReturn(
                $this->createMock(Property::class),
                $this->createMock(Property::class)
            );
        
        $this->output->expects($this->exactly(2))
            ->method('writeln')
            ->withConsecutive(
                ['Property already inserted'],
                ['Property already inserted']
            );

        $this->propertyBatchHelper->considerAddingProperties($this->output, $properties);
    }
}
