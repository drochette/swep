<?php

namespace App\Tests\Unit\Entity;

use App\Entity\User;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    public function testAssertUserIsAnAdmin(): void
    {
        $user = new User();
        $user->setRoles(['ROLE_ADMIN']);

        $this->assertTrue($user->isAdmin());
        $this->assertFalse($user->isUser());
    }

    public function testAssertUserIsAUSer(): void
    {
        $user = new User();
        $user->setRoles(['ROLE_USER']);

        $this->assertTrue($user->isUser());
        $this->assertFalse($user->isAdmin());
    }

    public function testUserHasEmailIdentifier(): void
    {
        $user = new User();
        $user->setEmail('test@user.com');
        $user->setRoles(['ROLE_USER']);

        $this->assertEquals('test@user.com', $user->getUserIdentifier());
    }

    public function testGetters(): void
    {
        $user = new User();
        $user->setEmail('test@user.com');
        $user->setRoles(['ROLE_USER']);
        $user->setPassword('user_password');
        $user->setApiToken('api_token');

        $this->assertEquals(
            [
                'test@user.com',
                'user_password',
                'api_token',
            ],
            [
                $user->getEmail(),
                $user->getPassword(),
                $user->getApiToken(),
            ]
        );
    }
}
