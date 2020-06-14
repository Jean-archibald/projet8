<?php

namespace App\Tests\Controller;


use Liip\TestFixturesBundle\Test\FixturesTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SecurityControllerTest extends WebTestCase
{

    use FixturesTrait;

    public function testLoginIsUp()
    {
        $client = static::createClient();
        $client->request('GET','/login');

        $this->assertSame(200, $client->getResponse()->getStatusCode());

    }

    public function testLoginWithBadCredentials()
    {
        $client = static::createClient();
        $crawler = $client->request('GET','/login');
        $form = $crawler->selectButton('Se connecter')->form([
            '_username' => 'Utilisateur',
            '_password' => 'fakepassword'
        ]);
        $client->submit($form);
        $this->assertResponseRedirects('/login');
        $client->followRedirect();
        $this->assertSelectorExists('.alert.alert-danger');

    }

    public function testSuccessfullLogin()
    {
        $this->loadFixtureFiles([__DIR__.'/users.yaml']);
        $client = static::createClient();
        $crawler = $client->request('GET','/login');
        $form = $crawler->selectButton('Se connecter')->form([
            '_username' => 'Administrateur',
            '_password' => 'test'
        ]);
        $client->submit($form);
        $this->assertResponseRedirects('/tasks');

    }

}