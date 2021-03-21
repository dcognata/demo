<?php

namespace App\DataFixtures;

use App\Domain\Auth\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
         $user = new User();
         $manager->persist($user);

         $manager->flush();
    }
}
