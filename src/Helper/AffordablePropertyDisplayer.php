<?php

namespace App\Helper;

use Symfony\Component\Console\Output\OutputInterface;

/**
 * This class is responsible for displaying affordable properties
 * in the console output.
 */
class AffordablePropertyDisplayer
{
    public function display(array $affordableProperties, OutputInterface $output): void
    {
        if (!$affordableProperties) {
            return;
        }

        $output->writeln('AFFORDABLE PROPERTIES');
        foreach ($affordableProperties as $property) {
            $output->writeln(sprintf(
                'Address: %s, Price: %.2f',
                $property->getAddress(),
                $property->getPrice()
            ));
        }
    }
}
