<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\CrowdfundingCampaign;
use App\Factory\ActivitySectorFactory;
use App\Factory\CrowdfundingCampaignFactory;
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

        CrowdfundingCampaignFactory::createMany(17);

        CrowdfundingCampaignFactory::createOne([
            'company' => 'DiliTrust',
            'project' => 'Trust Suite',
            'slug' => 'dilitrust--trust-suite',
            'currency' => 'EUR',
            'country' => 'FR',
            'timezone' => 'Europe/Paris',
            'status' => CrowdfundingCampaign::STATUS_COLLECTING_FUNDS,
            'description' => CrowdfundingCampaignFactory::faker()->paragraphs(CrowdfundingCampaignFactory::faker()->numberBetween(2, 12), true),
            'openingAt' => new \DateTimeImmutable('-5 days 09:00:00'),
            'closingAt' => new \DateTimeImmutable('+25 days 14:59:59'),
            'minFundingTarget' => 100_000_00, // 100K€
            'idealFundingTarget' => 300_000_00, // 300K€
            'maxFundingTarget' => 1_000_000_00, // 1M€
        ]);

        $manager->flush();
    }
}
