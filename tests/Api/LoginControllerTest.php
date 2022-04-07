<?php

declare(strict_types=1);

namespace App\Tests\Api;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;

final class LoginControllerTest extends ApiTestCase
{
    public function testLoginIsSuccessful(): void
    {
        static::createClient()->request('POST', '/api/login', [
            'json' => [
                'username' => 'admin@example.com',
                'password' => 'password',
            ],
        ]);

        $this->assertResponseIsSuccessful();
        $this->assertJsonContains(['api_token' => 'my_custom_api_token']);
    }

    public function testLoginFails(): void
    {
        static::createClient()->request('POST', '/api/login', [
            'json' => [
                'username' => 'admin@example.com',
                'password' => 'wrong_password',
            ],
        ]);

        $this->assertResponseStatusCodeSame(401);
    }
}
