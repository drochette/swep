<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppUserFixtures extends Fixture
{
    public function __construct(private UserPasswordHasherInterface $userPasswordHasher)
    {
    }

    public function load(ObjectManager $manager): void
    {
        $password = $this->userPasswordHasher->hashPassword(new User(), 'toto');

        $user = new User();
        $user->setEmail('rochette.damien@gmail.com');
        $user->setPassword($password);
        $user->setRoles(['ROLE_USER']);
        $user->setApiToken(sha1(time()));

        $manager->persist($user);

        $userAdmin = new User();
        $userAdmin->setEmail('adminuser@example.fr');
        $userAdmin->setPassword($this->userPasswordHasher->hashPassword(new User(), 'password_admin'));
        $userAdmin->setRoles(['ROLE_ADMIN']);
        $userAdmin->setApiToken(sha1(time()));
        $manager->persist($userAdmin);

        $manager->flush();
    }
}
