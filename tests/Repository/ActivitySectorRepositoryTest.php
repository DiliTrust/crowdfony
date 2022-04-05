<?php

declare(strict_types=1);

namespace App\Tests\Repository;

use App\Entity\ActivitySector;
use App\Repository\ActivitySectorRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

final class ActivitySectorRepositoryTest extends KernelTestCase
{
    /**
     * @dataProvider provideInactiveSectorName
     */
    public function testCannotFindInactiveSector(string $sectorName): void
    {
        $repository = static::getContainer()->get(ActivitySectorRepository::class);
        \assert($repository instanceof ActivitySectorRepository);

        $sector = $repository->findOneBy(['name' => $sectorName]);
        \assert($sector instanceof ActivitySector);

        $this->assertNull($repository->findActiveSector($sector->getId())); // @phpstan-ignore-line
    }

    public function provideInactiveSectorName(): \Generator
    {
        yield ['Fitness & sports'];
        yield ['Education and training'];
    }

    public function testFindOneActiveSector(): void
    {
        $repository = static::getContainer()->get(ActivitySectorRepository::class);
        \assert($repository instanceof ActivitySectorRepository);

        $sector = $repository->findOneBy(['name' => 'Energy and renewables']);
        \assert($sector instanceof ActivitySector);

        $this->assertSame($sector, $repository->findActiveSector($sector->getId())); // @phpstan-ignore-line
    }

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
