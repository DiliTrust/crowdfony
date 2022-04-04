<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\ActivitySector;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

final class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $sectors[] = new ActivitySector('Apparel & accessories, home and personal');
        $sectors[] = new ActivitySector('Automotive, transport and mobility');
        $sectors[] = new ActivitySector('Building, property & land management');
        $sectors[] = $education = new ActivitySector('Education and training');
        $sectors[] = new ActivitySector('Energy and renewables');
        $sectors[] = new ActivitySector('Entertainment & media');
        $sectors[] = new ActivitySector('Financial services & payments');
        $sectors[] = $fitness = new ActivitySector('Fitness & sports');
        $sectors[] = new ActivitySector('Food & beverage (FMCG)');
        $sectors[] = new ActivitySector('Healthtech & healthcare');
        $sectors[] = new ActivitySector('Leisure, hospitality & tourism');
        $sectors[] = new ActivitySector('Manufacturing/R&D');

        foreach ($sectors as $sector) {
            $sector->setIsEnabled(true);

            $manager->persist($sector);
        }

        $education->setIsEnabled(false);
        $fitness->setIsEnabled(false);

        $manager->flush();
    }
}
