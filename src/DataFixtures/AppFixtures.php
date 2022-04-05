<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Factory\ActivitySectorFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

final class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        ActivitySectorFactory::createOne(['name' => 'Apparel & accessories, home and personal', 'isEnabled' => true]);
        ActivitySectorFactory::createOne(['name' => 'Automotive, transport and mobility', 'isEnabled' => true]);
        ActivitySectorFactory::createOne(['name' => 'Building, property & land management', 'isEnabled' => true]);
        ActivitySectorFactory::createOne(['name' => 'Education and training', 'isEnabled' => false]);
        ActivitySectorFactory::createOne(['name' => 'Energy and renewables', 'isEnabled' => true]);
        ActivitySectorFactory::createOne(['name' => 'Entertainment & media', 'isEnabled' => true]);
        ActivitySectorFactory::createOne(['name' => 'Financial services & payments', 'isEnabled' => true]);
        ActivitySectorFactory::createOne(['name' => 'Fitness & sports', 'isEnabled' => false]);
        ActivitySectorFactory::createOne(['name' => 'Food & beverage (FMCG)', 'isEnabled' => true]);
        ActivitySectorFactory::createOne(['name' => 'Healthtech & healthcare', 'isEnabled' => true]);
        ActivitySectorFactory::createOne(['name' => 'Leisure, hospitality & tourism', 'isEnabled' => true]);
        ActivitySectorFactory::createOne(['name' => 'Manufacturing/R&D', 'isEnabled' => true]);

        ActivitySectorFactory::createMany(24);

        $manager->flush();
    }
}
