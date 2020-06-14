<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;


class TaskControllerTest extends WebTestCase
{

    public function testAddNewTask()
    {
        $client = static::createClientWithAuthentication('main');

        $crawler = $client->request('GET', '/tasks/create');

        $form = $crawler->filter('button.btn-success.pull-right')->form();
        $form['task[title]'] = "TaskPhpUnit";
        $form['task[content]'] = "Hello je suis un test php unit";

        $client->submit($form);

        $crawler = $client->followRedirect();

        $this->assertSame(1, $crawler->filter('div.alert.alert-success')->count());

    }
}