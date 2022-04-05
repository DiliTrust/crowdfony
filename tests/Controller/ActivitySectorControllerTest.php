<?php

declare(strict_types=1);

namespace App\Tests\Controller;

use App\Entity\ActivitySector;
use App\Repository\ActivitySectorRepository;
use Symfony\Bridge\Doctrine\DataCollector\DoctrineDataCollector;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpKernel\DataCollector\TimeDataCollector;

final class ActivitySectorControllerTest extends WebTestCase
{
    public function testNotFoundActivitySector(): void
    {
        $client = static::createClient();
        $client->request('GET', '/sectors/0');

        $this->assertResponseStatusCodeSame(404);
    }

    public function testBrowseInactiveActivitySector(): void
    {
        $client = static::createClient();

        $repository = static::getContainer()->get(ActivitySectorRepository::class);
        \assert($repository instanceof ActivitySectorRepository);

        $sector = $repository->findOneBy(['isEnabled' => false]);
        \assert($sector instanceof ActivitySector);

        $client->request('GET', '/sectors/' . $sector->getId());

        $this->assertResponseStatusCodeSame(404);
    }

    public function testBrowseActivitySectors(): void
    {
        $client = static::createClient();
        $client->enableProfiler();

        $client->request('GET', '/sectors');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('title', 'Startups Activity Sectors');
        $this->assertSelectorTextContains('h1', 'Startups Activity Sectors');
        $this->assertSelectorTextContains('ul', 'Automotive, transport and mobility');
        $this->assertSelectorTextNotContains('ul', 'Fitness & sports');

        if ($profile = $client->getProfile()) {
            $db = $profile->getCollector('db');
            \assert($db instanceof DoctrineDataCollector);

            $this->assertLessThan(5, $db->getQueryCount());

            $time = $profile->getCollector('time');
            \assert($time instanceof TimeDataCollector);

            $this->assertLessThan(200, $time->getDuration());
        }

        $client->clickLink('Healthtech & healthcare');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('title', 'Healthtech & healthcare');
        $this->assertSelectorTextContains('h1', 'Healthtech & healthcare');
        $this->assertSelectorExists('p.sector-description');

        $client->clickLink('Back to listing');

        $this->assertResponseIsSuccessful();
        $this->assertRequestAttributeValueSame('_route', 'app_activity_sector_list');
    }
}
