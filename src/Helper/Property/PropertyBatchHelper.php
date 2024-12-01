<?php

namespace App\Helper\Property;

use App\Entity\Property;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class that helps to batch persist new entities.
 */
class PropertyBatchHelper
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    public function considerAddingProperties(OutputInterface $output, array $properties, int $batchSize = 20): void
    {
        foreach ($properties as $index => $propertyData) {
            // Skip loop if property already added
            $existingProperty = $this->entityManager->getRepository(Property::class)->find($propertyData[0]);
            if ($existingProperty) {
                $output->writeln('Property already inserted');
                continue;
            }

            $property = new Property();
            $property->setAddress($propertyData[1]);
            $property->setPrice($propertyData[2]);

            $this->entityManager->persist($property);
            $output->writeln('Inserted property with id: '.$propertyData[0]);

            // Flush and clear in batches
            if (0 === $index % $batchSize) {
                $this->entityManager->flush();
                $this->entityManager->clear();
            }
        }

        // Flush remaining records
        $this->entityManager->flush();
        $this->entityManager->clear();
    }
}
