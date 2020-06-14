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

    public function testDisplayButtonLoginForm()
    {
        $client = static::createClient();
        $client->request('GET','/login');
        $this->assertSelectorTextContains("button", "Se connecter");
    }

    public function testLoginWithBadCredentials()
    {
        $client = static::createClient(array(), array(
            'HTTP_HOST'       => 'localhost:8000',
        ));
        $crawler = $client->request('GET','/login');
        $form = $crawler->selectButton('Se connecter')->form([
            '_username' => 'Utilisateur',
            '_password' => 'fakepassword'
        ]);
        $client->submit($form);
        $this->assertResponseRedirects('http://localhost:8000/login');
        $client->followRedirect();
        $this->assertSelectorExists('.alert.alert-danger');

    }

    public function testSuccessfullLogin()
    {
        $this->loadFixtureFiles([__DIR__.'/users.yaml']);
        $client = static::createClient(array(), array(
            'HTTP_HOST'       => 'localhost:8000',
        ));
        $crawler = $client->request('GET','/login');
        $form = $crawler->selectButton('Se connecter')->form([
            '_username' => 'Utilisateur',
            '_password' => 'test'
        ]);
        $client->submit($form);
        $this->assertResponseRedirects('http://localhost:8000/tasks');
        $client->followRedirect();
        $this->assertSelectorExists('a.pull-right.btn.btn-danger');

    }

}