<?php

namespace App\Tests\Helper;

use App\Entity\Property;
use App\Helper\AffordablePropertyFinder;
use App\Repository\PropertyRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectRepository;
use PHPUnit\Framework\TestCase;

class AffordablePropertyFinderTest extends TestCase
{
    private AffordablePropertyFinder $affordablePropertyFinder;
    private EntityManagerInterface $entityManager;
    private ObjectRepository $propertyRepository;

    protected function setUp(): void
    {
      $this->entityManager = $this->createMock(EntityManagerInterface::class);
      $this->propertyRepository = $this->createMock(PropertyRepository::class);
      $this->entityManager->expects($this->any())
          ->method('getRepository')
          ->willReturn($this->propertyRepository);

        $this->affordablePropertyFinder = new AffordablePropertyFinder($this->entityManager);
    }

    public function testGetAffordablePropertiesEmptyDatabase(): void
    {
        $this->propertyRepository->method('findAll')->willReturn([]);

        $result = $this->affordablePropertyFinder->getAffordableProperties(3000, 1000);

        $this->assertEmpty($result);
    }

    public function testGetAffordablePropertiesWithAffordableProperties(): void
    {
        $property1 = $this->createProperty('123 Test St', 1000.0);
        $property2 = $this->createProperty('456 Test Rd', 1200.0);

        // Mock repository to return a list of properties
        $this->propertyRepository->method('findAll')->willReturn([$property1, $property2]);

        $result = $this->affordablePropertyFinder->getAffordableProperties(3000, 1000); // Net income: 2000

        $this->assertCount(2, $result); // Both properties are affordable
    }

    public function testGetAffordablePropertiesWithUnaffordableProperties(): void
    {
        $property1 = $this->createProperty('789 Test Ave', 2000.0);
        $property2 = $this->createProperty('1010 Test Blvd', 2500.0);

        // Mock repository to return a list of properties
        $this->propertyRepository->method('findAll')->willReturn([$property1, $property2]);

        $result = $this->affordablePropertyFinder->getAffordableProperties(3000, 1000); // Net income: 2000

        $this->assertEmpty($result); // Not a sigle property is affordable
    }

    public function testGetAffordablePropertiesOnlyOne(): void
    {
      $property1 = $this->createProperty('123 Test St', 1000.0);
      $property2 = $this->createProperty('456 Test Rd', 1750.0);

        // Mock repository to return a list of properties
        $this->propertyRepository->method('findAll')->willReturn([$property1, $property2]);

        $result = $this->affordablePropertyFinder->getAffordableProperties(3000, 1000); // Net income: 2000

        $this->assertCount(1, $result); // Both properties are affordable
    }

    private function createProperty(string $address, float $price): Property
    {
        $property = new Property();
        $property->setAddress($address);
        $property->setPrice($price);

        return $property;
    }
}
