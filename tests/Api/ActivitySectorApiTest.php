<?php

declare(strict_types=1);

namespace App\Tests\Api;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use App\Entity\ActivitySector;
use App\Repository\ActivitySectorRepository;

final class ActivitySectorApiTest extends ApiTestCase
{
    public function testGetCollection(): void
    {
        $response = static::createClient()->request('GET', '/api/activity_sectors');

        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');

        // Asserts that the returned JSON is a superset of this one
        $this->assertJsonContains([
            '@context' => '/api/contexts/ActivitySector',
            '@id' => '/api/activity_sectors',
            '@type' => 'hydra:Collection',
            'hydra:view' => [
                '@id' => '/api/activity_sectors?page=1',
                '@type' => 'hydra:PartialCollectionView',
                'hydra:first' => '/api/activity_sectors?page=1',
                'hydra:next' => '/api/activity_sectors?page=2',
            ],
        ]);

        $this->assertCount(15, $response->toArray()['hydra:member']);

        $sectors = $response->toArray()['hydra:member'];

        $this->assertContains('Automotive, transport and mobility', \array_column($sectors, 'name'));
        $this->assertMatchesResourceCollectionJsonSchema(ActivitySector::class);
    }

    public function testCreateActivitySectorWithValidationErrorsFails(): void
    {
        static::createClient()->request('POST', '/api/activity_sectors', [
            'json' => [
                'name' => 'F',
                'description' => '',
            ],
        ]);

        $this->assertResponseStatusCodeSame(422);
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');

        // Asserts that the returned JSON is a superset of this one
        $this->assertJsonContains([
            '@context' => '/api/contexts/ConstraintViolationList',
            '@type' => 'ConstraintViolationList',
            'hydra:title' => 'An error occurred',
            'hydra:description' => 'name: The activity sector name is too short. It must be at least 5 characters.
description: The activity sector description is required.',
            'violations' => [
                [
                    'propertyPath' => 'name',
                    'message' => 'The activity sector name is too short. It must be at least 5 characters.',
                    'code' => '9ff3fdc4-b214-49db-8718-39c315e33d45',
                ],
                [
                    'propertyPath' => 'description',
                    'message' => 'The activity sector description is required.',
                    'code' => 'c1051bb4-d103-4f74-8988-acbcafc7fdc3',
                ],
            ],
        ]);
    }

    public function testGetDisabledActivitySectorIsNotAllowed(): void
    {
        $client = static::createClient();

        $sector = $this->getActivitySectorRepository()->findOneBy(['name' => 'Education and training']);
        \assert($sector instanceof ActivitySector);

        $client->request('GET', '/api/activity_sectors/' . $sector->getId());

        $this->assertResponseStatusCodeSame(404);
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
    }

    public function testCreateActivitySectorSucceeds(): void
    {
        static::createClient()->request('POST', '/api/activity_sectors', [
            'json' => [
                'name' => 'Legal & citizen tech startups',
                'description' => 'Invest in startups that simplifies administrative tasks for companies & individuals.',
            ],
        ]);

        $this->assertResponseStatusCodeSame(201);
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');

        $sector = $this->getActivitySectorRepository()->findOneBy([], ['id' => 'DESC']);
        \assert($sector instanceof ActivitySector);

        $this->assertJsonContains([
            '@context' => '/api/contexts/ActivitySector',
            '@id' => '/api/activity_sectors/' . $sector->getId(),
            '@type' => 'ActivitySector',
            'id' => $sector->getId(),
            'name' => 'Legal & citizen tech startups',
        ]);

        static::createClient()->request('GET', '/api/activity_sectors/' . $sector->getId());

        $this->assertResponseStatusCodeSame(200);
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');

        $this->assertJsonContains([
            '@context' => '/api/contexts/ActivitySector',
            '@id' => '/api/activity_sectors/' . $sector->getId(),
            '@type' => 'ActivitySector',
            'id' => $sector->getId(),
            'name' => 'Legal & citizen tech startups',
            'description' => 'Invest in startups that simplifies administrative tasks for companies & individuals.',
        ]);
    }

    private function getActivitySectorRepository(): ActivitySectorRepository
    {
        $repository = static::getContainer()->get(ActivitySectorRepository::class);
        \assert($repository instanceof ActivitySectorRepository);

        return  $repository;
    }
}
