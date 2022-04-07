<?php

declare(strict_types=1);

namespace App\Tests\Api;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use App\Entity\User;
use App\Repository\UserRepository;

final class UserApiTest extends ApiTestCase
{
    /**
     * @dataProvider provideDeniedUserEmail
     */
    public function testGetUserProfileIsDenied(string $emailAddress): void
    {
        $client = static::createClient();

        $client->request('POST', '/api/login', [
            'json' => [
                'username' => $emailAddress,
                'password' => 'password',
            ],
        ]);

        $repository = static::getContainer()->get(UserRepository::class);
        \assert($repository instanceof UserRepository);

        $investor = $repository->findOneBy(['emailAddress' => 'investor@example.com']);
        \assert($investor instanceof User);

        $client->request('GET', '/api/users/' . $investor->getId());

        $this->assertResponseStatusCodeSame(403);
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
    }

    public function provideDeniedUserEmail(): \Generator
    {
        yield ['campaign.manager@example.com'];
        yield ['accountant@example.com'];
    }

    /**
     * @dataProvider provideGrantedUserEmail
     */
    public function testGetUserProfileIsGranted(string $emailAddress): void
    {
        $client = static::createClient();

        $client->request('POST', '/api/login', [
            'json' => [
                'username' => $emailAddress,
                'password' => 'password',
            ],
        ]);

        $repository = static::getContainer()->get(UserRepository::class);
        \assert($repository instanceof UserRepository);

        $investor = $repository->findOneBy(['emailAddress' => 'investor@example.com']);
        \assert($investor instanceof User);

        $client->request('GET', '/api/users/' . $investor->getId());

        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
    }

    public function provideGrantedUserEmail(): \Generator
    {
        yield ['admin@example.com'];
        yield ['investor@example.com'];
    }
}
