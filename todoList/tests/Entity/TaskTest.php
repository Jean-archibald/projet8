<?php

namespace App\Tests\Entity;

use App\Entity\Task;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class TaskTest extends KernelTestCase
{


    public function getEntity(): Task
    {

        $task = new Task();
        $task->setCreatedAt(new \DateTime());
        $task->setTitle('title test');
        $task->setContent('Le contenu de cette task');

        return $task;
    }

    public function assertHasErrors(Task $task, int $number = 0)
    {
        self::bootKernel();
        $error = self::$container->get('validator')->validate($task);
        $this->assertCount($number, $error);
    }

    public function testValidTaskEntity()
    {

        $this->assertHasErrors($this->getEntity(), 0);

    }

    public function testInvalidTaskEntityWhenTitleIsBlank()
    {

        $task = $this->getEntity();
        $task->setTitle('');
        $this->assertHasErrors($task, 1);
    }

    public function testInvalidUserEntityWhenContentIsBlank()
    {

        $task = $this->getEntity();
        $task->setContent('');
        $this->assertHasErrors($task, 1);
    }


}