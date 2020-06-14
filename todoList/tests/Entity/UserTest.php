<?php


namespace App\Tests\Entity;

use App\Entity\User;
use Liip\TestFixturesBundle\Test\FixturesTrait;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class UserTest extends KernelTestCase
{

    use FixturesTrait;

    public function getEntity(): User
    {

        $user = new User();
        $user->setUsername('JohnTest');
        $user->setPassword('\\$2y\\$10\\$WyMSmN0QqpgnMpFFnd/bl.5e978zVOu27R0ppfBKAq1JrPjjWbM9i');
        $user->setEmail('testemail@test.fr');
        $user->setRoles(['ROLE_USER']);
        return $user;
    }

    public function assertHasErrors(User $user, int $number = 0){
        self::bootKernel();
        $error = self::$container->get('validator')->validate($user);
        $this->assertCount($number,$error);
    }

    public function testValidUserEntity(){

        $this->assertHasErrors($this->getEntity(), 0);

    }

    public function testInvalidUserEntityWhenUsernameIsBlank(){

        $user = $this->getEntity();
        $user->setUsername('');
        $this->assertHasErrors($user, 1);
    }

    public function testInvalidUserEntityWhenEmailIsBlank(){

        $user = $this->getEntity();
        $user->setEmail('');
        $this->assertHasErrors($user, 1);
    }

    public function testInvalidUserEntityWhenEmailFormatIsNotCorrect(){

        $user = $this->getEntity();
        $user->setEmail('ThisEmailAdressIsNotCorrect');
        $this->assertHasErrors($user, 1);
    }

    public function testInvalidUniqueEmail(){

        $this->loadFixtureFiles([dirname(__DIR__).'/fixtures/user_fixture.yaml']);
        $user = $this->getEntity();
        $user->setEmail('test@test.com');
        $this->assertHasErrors($user, 1);

    }
}