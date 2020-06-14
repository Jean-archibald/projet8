<?php


namespace App\DataFixtures;

use App\Entity\Task;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class TaskFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $user = new User();
        $user->setUsername('JohnTest');
        $user->setPassword('$2y$10$WyMSmN0QqpgnMpFFnd/bl.5e978zVOu27R0ppfBKAq1JrPjjWbM9i');
        $user->setEmail('testemail@test.fr');
        $user->setRoles(['ROLE_USER']);
        $manager->persist($user);

        for($i = 0; $i < 10; $i++) {
            $task = new Task();
            $task->setTitle('TitleSuper'.$i);
            $task->setContent('Hello Bros');
            $task->setUsers($user);
            $manager->persist($task);
        }
        $manager->flush();
    }
}