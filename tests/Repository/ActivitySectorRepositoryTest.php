<?php

declare(strict_types=1);

namespace App\Tests\Repository;

use App\Entity\ActivitySector;
use App\Repository\ActivitySectorRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

final class ActivitySectorRepositoryTest extends KernelTestCase
{
    public function testFindActiveSectors(): void
    {
        $repository = static::getContainer()->get(ActivitySectorRepository::class);
        \assert($repository instanceof ActivitySectorRepository);

        $sectors = $repository->findActiveSectors();

        $this->assertNotEmpty($sectors);
        $this->assertContainsOnlyInstancesOf(ActivitySector::class, $sectors);

        foreach ($sectors as $sector) {
            $this->assertTrue($sector->isEnabled());
        }
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        $entityManager = static::getContainer()->get(EntityManagerInterface::class);
        $entityManager->close();
    }
}
