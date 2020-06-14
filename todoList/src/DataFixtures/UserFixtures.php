<?php


namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class UserFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        for($i = 0; $i < 10; $i++) {
            $user = new User();
            $user->setUsername('JohnTest'.$i);
            $user->setPassword('$2y$10$WyMSmN0QqpgnMpFFnd/bl.5e978zVOu27R0ppfBKAq1JrPjjWbM9i');
            $user->setEmail('testemail'.$i.'@test.fr');
            $user->setRoles(['ROLE_USER']);
            $manager->persist($user);
        }
        $manager->flush();
    }
}