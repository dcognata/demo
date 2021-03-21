<?php

namespace App\DataFixtures;

use App\Domain\Auth\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private UserPasswordEncoderInterface $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        $user = new User();
        $user->setUsername('administrator');
        $user->addRole('ROLE_YODA');

        $user->setPassword(
            $this->passwordEncoder->encodePassword(
                $user,
                'password'
            )
        );

        $manager->persist($user);
        $manager->flush();
    }
}
