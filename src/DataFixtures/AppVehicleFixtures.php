<?php

namespace App\DataFixtures;

use App\Entity\Vehicle;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppVehicleFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $vehicle = new Vehicle();
        $vehicle->setId(1);
        $vehicle->setLabel('Peugeot 208');

        $manager->persist($vehicle);
        $manager->flush();
    }
}
