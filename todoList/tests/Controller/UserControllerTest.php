<?php


namespace App\Tests\Controller;

use App\Tests\NeedLogin;
use Liip\TestFixturesBundle\Test\FixturesTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;


class UserControllerTest extends WebTestCase
{

    use FixturesTrait;
    use NeedLogin;

    public function testToLoginWhenRoleIsNotSufficient()
    {
        $client = static::createClient(array(), array(
            'HTTP_HOST'       => 'localhost:8000',
        ));
        $users = $this->loadFixtureFiles([__DIR__.'/users.yaml']);
        /** @var User $user */
        $this->login($client, $users['user_user']);
        $client->request('GET','/users');
        $this->assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);

    }

    public function testToLoginWhenRoleIsAdmin()
    {
        $client = static::createClient(array(), array(
            'HTTP_HOST'       => 'localhost:8000',
        ));
        $users = $this->loadFixtureFiles([__DIR__.'/users.yaml']);
        /** @var User $user */
        $this->login($client, $users['user_admin']);
        $client->request('GET','/users');
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

    }

    public function testAddUser()
    {
        $client = static::createClient(array(), array(
            'HTTP_HOST'       => 'localhost:8000',
        ));
        $users = $this->loadFixtureFiles([__DIR__.'/users.yaml']);
        /** @var User $user */
        $this->login($client, $users['user_admin']);

        $crawler = $client->request('GET','/users/create');
        $form = $crawler->filter('button.btn-success')->form();
        $form['user[username]'] = "UserPhpUnit2";
        $form['user[email]'] = "user@user.testAdd2";
        $form['user[password][first]'] = "test";
        $form['user[password][second]'] = "test";
        $form['user[roles][0]'] = "ROLE_USER";
        $client->submit($form);

        $crawler = $client->followRedirect();

        $this->assertSame(1, $crawler->filter('div.alert.alert-success')->count());

    }

}