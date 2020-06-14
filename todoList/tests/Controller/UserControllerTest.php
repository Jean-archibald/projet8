<?php


namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class UserControllerTest extends WebTestCase
{


    public function testUsersPageIsRestricted()
    {
        $client = static::createClient(array(), array(
            'HTTP_HOST'       => 'localhost:8000',
        ));

        $client->request('GET','/users');
        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);
    }

    public function testRedirectToLogin()
    {
        $client = static::createClient(array(), array(
            'HTTP_HOST'       => 'localhost:8000',
        ));
        $client->request('GET','/users');
        $this->assertResponseRedirects('/login');

    }

    public function testUsersListPageIsUp()
    {
        $client = static::createClient(array(), array(
            'HTTP_HOST'       => 'localhost:8000',
        ));
        $client->request('GET','/users');
        $this->assertSame(200, $client->getResponse()->getStatusCode());


    }

    public function testAddNewUsers()
    {
        $client = $this->createClient();
       # $userRepository = static::$container->get(UserRepository::class);
        #$testUser = $userRepository->findOneByUsername('Administrateur');
       # $client->loginUser($testUser);

        $crawler = $client->request('GET','/users/create');
        $client->followRedirects(true);

        $crawler = $client->submitForm('Ajouter', [
            'user[username]' => "UserPhpUnit",
            'user[email]' => "UserPhpUnit@php.unit",
            'user[password][first]' => "test",
            'user[password][second]' => "test",
            'user[roles][0]' => "ROLE_ADMIN",
        ]);
        $client->followRedirects(true);
        $crawler = $client->followRedirect();

        $this->assertSame(1,$crawler->filter('div.alert.alert-success')->count());

    }
}