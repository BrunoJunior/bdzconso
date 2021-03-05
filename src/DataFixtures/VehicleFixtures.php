<?php

namespace App\DataFixtures;

use App\Entity\Vehicle;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class VehicleFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        foreach ($this->getData() as $data) {
            $vehicle = new Vehicle();
            $vehicle->setManufacturer($data[0]);
            $vehicle->setModel($data[1]);
            $vehicle->setYear($data[2]);
            $vehicle->setUser($this->getReference($data[3]));
            foreach ($data[4] as $fuelType) {
                $vehicle->addCompatibleFuel($this->getReference($fuelType));
            }
            $manager->persist($vehicle);
            $this->addReference($data[3] . '_' . $data[0] . ' ' . $data[1], $vehicle);
        }
        $manager->flush();
    }

    private function getData(): array {
        return [
            ['Honda', 'Insight', 2011, 'tom_admin@symfony.com', ['SP95', 'SP95-E10', 'SP98']],
            ['Honda', 'Civic', 2011, 'jane_admin@symfony.com', ['SP95', 'SP95-E10', 'SP98']]
        ];
    }

    /**
     * This method must return an array of fixtures classes
     * on which the implementing class depends on
     *
     * @return array
     */
    function getDependencies()
    {
       return [
           UserFixtures::class,
           FuelTypeFixtures::class
       ];
    }
}
