<?php

declare(strict_types=1);

namespace App\Tests\Entity;

use App\DBAL\Types\CampaignStatusType;
use App\Entity\CrowdfundingCampaign;
use Money\Money;
use PHPUnit\Framework\TestCase;

final class CrowdfundingCampaignTest extends TestCase
{
    public function testGetFundraisingProgressPercentage(): void
    {
        $campaign = new CrowdfundingCampaign('Microsoft', 'Hololens', 'USD', 'US');
        $campaign->setStatus(CampaignStatusType::COLLECTING_FUNDS);
        $campaign->setMinFundingTarget(150_000_00);
        $campaign->setIdealFundingTarget(200_000_00);
        $campaign->setMaxFundingTarget(300_000_00);

        $this->assertEquals(Money::USD(150_000_00), $campaign->getMinFundingTarget());
        $this->assertEquals(Money::USD(200_000_00), $campaign->getIdealFundingTarget());
        $this->assertEquals(Money::USD(300_000_00), $campaign->getMaxFundingTarget());
        $this->assertEquals(Money::USD(0), $campaign->getTotalCollectedFunds());
        $this->assertSame(0, $campaign->getFundraisingProgressPercentage());
    }
}
