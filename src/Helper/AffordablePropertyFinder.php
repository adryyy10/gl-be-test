<?php

namespace App\Helper;

use App\Entity\Property;
use Doctrine\ORM\EntityManagerInterface;

/**
 * This class is responsible for determining which properties are affordable based on
 * a user's average monthly income and expenses.
 */
class AffordablePropertyFinder
{
    private const MININUM_RENT_PARAMETER = 1.25;

    public function __construct(
        public readonly EntityManagerInterface $entityManager,
    ) {
    }

    /**
     * Determines affordable properties based on average monthly income and expenses.
     *
     * @return Property[] an array of Property entities that the user can afford
     */
    public function getAffordableProperties(float $averageMonthlyIncome, float $averageMonthlyExpenses): array
    {
        $affordableProperties = [];

        $properties = $this->entityManager->getRepository(Property::class)->findAll();
        foreach ($properties as $property) {
            $netIncome = $averageMonthlyIncome - $averageMonthlyExpenses;
            if ($netIncome >= $property->getPrice() * self::MININUM_RENT_PARAMETER) {
                $affordableProperties[] = $property;
            }
        }

        return $affordableProperties;
    }
}
