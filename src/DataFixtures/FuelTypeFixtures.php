<?php

namespace App\DataFixtures;

use App\Entity\FuelType;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class FuelTypeFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $fuelTypes = ['SP95', 'SP98', 'SP95-E10', 'Diesel', 'GPL', 'E85'];
        foreach ($fuelTypes as $name) {
            $fuelType = new FuelType();
            $fuelType->setName($name);
            $manager->persist($fuelType);
            $this->addReference($name, $fuelType);
        }
        $manager->flush();
    }
}
