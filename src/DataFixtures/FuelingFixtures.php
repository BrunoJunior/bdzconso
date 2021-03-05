<?php

namespace App\DataFixtures;

use App\Entity\Fueling;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class FuelingFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        foreach ($this->getData() as $data) {
            $fueling = new Fueling();
            $fueling->setDate(new \DateTime($data[2]));
            $fueling->setAmount((int) ($data[6] * 100));
            $fueling->setFuelType($this->getReference($data[3]));
            $fueling->setShowedConsumption((int) ($data[8] * 10));
            $fueling->setTraveledDistance((int) ($data[7] * 10));
            $fueling->setVolume((int) ($data[4] * 1000));
            $fueling->setVolumePrice((int) ($data[5] * 100));
            $fueling->setVehicle($this->getReference($data[1] . '_' . $data[0]));
            $fueling->setAdditivedFuel(false);
            $manager->persist($fueling);
        }
        $manager->flush();
    }

    private function getData(): array {
        return [
            ['Honda Civic', 'jane_admin@symfony.com', '2018-01-01', 'SP95-E10', 50, 1.50, 75, 700, 6.5],
            ['Honda Civic', 'jane_admin@symfony.com', '2018-01-15', 'SP95-E10', 40, 1.50, 60, 600, 6],
            ['Honda Civic', 'jane_admin@symfony.com', '2018-02-01', 'SP95-E10', 45, 1.50, 67.5, 650, 6.5],
            ['Honda Civic', 'jane_admin@symfony.com', '2018-02-15', 'SP95-E10', 39, 1.50, 58.5, 600, 6],
            ['Honda Civic', 'jane_admin@symfony.com', '2018-03-01', 'SP95-E10', 49, 1.50, 73.5, 700, 6.5],
            ['Honda Civic', 'jane_admin@symfony.com', '2018-03-15', 'SP95-E10', 48, 1.50, 72, 700, 6.5],
            ['Honda Civic', 'jane_admin@symfony.com', '2018-04-01', 'SP95-E10', 47, 1.50, 70.5, 690, 6.3],
            ['Honda Civic', 'jane_admin@symfony.com', '2018-04-15', 'SP95-E10', 50, 1.50, 75, 700, 6.4],
            ['Honda Civic', 'jane_admin@symfony.com', '2018-05-01', 'SP95-E10', 47, 1.50, 70.5, 690, 6.3],
            ['Honda Civic', 'jane_admin@symfony.com', '2018-05-15', 'SP95-E10', 50, 1.50, 75, 700, 6.4],

            ['Honda Civic', 'jane_admin@symfony.com', '2017-01-02', 'SP95-E10', 50, 1.50, 75, 700, 6.5],
            ['Honda Civic', 'jane_admin@symfony.com', '2017-01-11', 'SP95-E10', 40, 1.50, 60, 600, 6],
            ['Honda Civic', 'jane_admin@symfony.com', '2017-02-10', 'SP95-E10', 45, 1.50, 67.5, 650, 6.5],
            ['Honda Civic', 'jane_admin@symfony.com', '2017-02-25', 'SP95-E10', 39, 1.50, 58.5, 600, 6],
            ['Honda Civic', 'jane_admin@symfony.com', '2017-03-02', 'SP95-E10', 49, 1.50, 73.5, 700, 6.5],
            ['Honda Civic', 'jane_admin@symfony.com', '2017-03-20', 'SP95-E10', 48, 1.50, 72, 700, 6.5],
            ['Honda Civic', 'jane_admin@symfony.com', '2017-04-05', 'SP95-E10', 47, 1.50, 70.5, 690, 6.3],
            ['Honda Civic', 'jane_admin@symfony.com', '2017-04-11', 'SP95-E10', 50, 1.50, 75, 700, 6.4],
            ['Honda Civic', 'jane_admin@symfony.com', '2017-05-03', 'SP95-E10', 47, 1.50, 70.5, 690, 6.3],
            ['Honda Civic', 'jane_admin@symfony.com', '2017-05-10', 'SP95-E10', 50, 1.50, 75, 700, 6.4],
            ['Honda Civic', 'jane_admin@symfony.com', '2017-05-25', 'SP95-E10', 39, 1.50, 58.5, 600, 6],
            ['Honda Civic', 'jane_admin@symfony.com', '2017-06-02', 'SP95-E10', 49, 1.50, 73.5, 700, 6.5],
            ['Honda Civic', 'jane_admin@symfony.com', '2017-06-20', 'SP95-E10', 48, 1.50, 72, 700, 6.5],
            ['Honda Civic', 'jane_admin@symfony.com', '2017-07-05', 'SP95-E10', 47, 1.50, 70.5, 690, 6.3],
            ['Honda Civic', 'jane_admin@symfony.com', '2017-07-11', 'SP95-E10', 50, 1.50, 75, 700, 6.4],
            ['Honda Civic', 'jane_admin@symfony.com', '2017-08-03', 'SP95-E10', 47, 1.50, 70.5, 690, 6.3],
            ['Honda Civic', 'jane_admin@symfony.com', '2017-08-10', 'SP95-E10', 50, 1.50, 75, 700, 6.4],
            ['Honda Civic', 'jane_admin@symfony.com', '2017-08-25', 'SP95-E10', 39, 1.50, 58.5, 600, 6],
            ['Honda Civic', 'jane_admin@symfony.com', '2017-09-02', 'SP95-E10', 49, 1.50, 73.5, 700, 6.5],
            ['Honda Civic', 'jane_admin@symfony.com', '2017-09-20', 'SP95-E10', 48, 1.50, 72, 700, 6.5],
            ['Honda Civic', 'jane_admin@symfony.com', '2017-10-05', 'SP95-E10', 47, 1.50, 70.5, 690, 6.3],
            ['Honda Civic', 'jane_admin@symfony.com', '2017-10-11', 'SP95-E10', 50, 1.50, 75, 700, 6.4],
            ['Honda Civic', 'jane_admin@symfony.com', '2017-11-03', 'SP95-E10', 47, 1.50, 70.5, 690, 6.3],
            ['Honda Civic', 'jane_admin@symfony.com', '2017-11-10', 'SP95-E10', 50, 1.50, 75, 700, 6.4],
            ['Honda Civic', 'jane_admin@symfony.com', '2017-12-03', 'SP95-E10', 47, 1.50, 70.5, 690, 6.3],
            ['Honda Civic', 'jane_admin@symfony.com', '2017-12-10', 'SP95-E10', 50, 1.50, 75, 700, 6.4],

            ['Honda Insight', 'tom_admin@symfony.com', '2018-01-01', 'SP95-E10', 50, 1.50, 75, 700, 6.5],
            ['Honda Insight', 'tom_admin@symfony.com', '2018-01-15', 'SP95-E10', 40, 1.50, 60, 600, 6],
            ['Honda Insight', 'tom_admin@symfony.com', '2018-02-01', 'SP95-E10', 45, 1.50, 67.5, 650, 6.5],
            ['Honda Insight', 'tom_admin@symfony.com', '2018-02-15', 'SP95-E10', 39, 1.50, 58.5, 600, 6],
            ['Honda Insight', 'tom_admin@symfony.com', '2018-03-01', 'SP95-E10', 49, 1.50, 73.5, 700, 6.5],
            ['Honda Insight', 'tom_admin@symfony.com', '2018-03-15', 'SP95-E10', 48, 1.50, 72, 700, 6.5],
            ['Honda Insight', 'tom_admin@symfony.com', '2018-04-01', 'SP95-E10', 47, 1.50, 70.5, 690, 6.3],
            ['Honda Insight', 'tom_admin@symfony.com', '2018-04-15', 'SP95-E10', 50, 1.50, 75, 700, 6.4]
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
            VehicleFixtures::class,
            FuelTypeFixtures::class
        ];
    }
}
