<?php

namespace App\DataFixtures;

use App\Entity\Campus;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppCampusFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $campusParis = new Campus();
        $campusParis->setLabel('Paris');
        $this->addReference('campus-paris', $campusParis);

        $manager->persist($campusParis);


        $campusBlois = new Campus();
        $campusBlois->setLabel('Blois');
        $this->addReference('campus-blois', $campusBlois);
        $manager->persist($campusBlois);


        $manager->flush();
    }
}
