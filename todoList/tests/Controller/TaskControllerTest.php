<?php

namespace App\Tests\Controller;

use App\Tests\NeedLogin;
use Liip\TestFixturesBundle\Test\FixturesTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Entity\User;


class TaskControllerTest extends WebTestCase
{

    use FixturesTrait;
    use NeedLogin;

    public function testAddNewTask()
    {
        $client = static::createClient(array(), array(
            'HTTP_HOST'       => 'localhost:8000',
        ));
        $users = $this->loadFixtureFiles([__DIR__.'/users.yaml']);
        /** @var User $user */
        $this->login($client, $users['user_user']);

        $crawler = $client->request('GET','/tasks/create');
        $form = $crawler->filter('button.btn-success.pull-right')->form();
        $form['task[title]'] = "TaskPhpUnit";
        $form['task[content]'] = "Hello je suis un test php unit";
        $client->submit($form);

        $crawler = $client->followRedirect();

        $this->assertSame(1, $crawler->filter('div.alert.alert-success')->count());

    }

}