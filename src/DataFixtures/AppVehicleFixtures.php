<?php

namespace App\DataFixtures;

use App\Entity\Campus;
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
        $vehicle->setCampus($this->getReference('campus-paris', Campus::class));
        $manager->persist($vehicle);


        $vehicleBlois = new Vehicle();
        $vehicleBlois->setId(1);
        $vehicleBlois->setLabel('Renault R5');
        $vehicleBlois->setCampus($this->getReference('campus-blois', Campus::class));
        $manager->persist($vehicleBlois);


        $manager->flush();
    }
}
