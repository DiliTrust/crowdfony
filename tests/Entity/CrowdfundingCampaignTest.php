<?php

declare(strict_types=1);

namespace App\Tests\Entity;

use App\DBAL\Types\CampaignStatusType;
use App\Entity\CrowdfundingCampaign;
use Money\Money;
use PHPUnit\Framework\TestCase;

final class CrowdfundingCampaignTest extends TestCase
{
    public function testTimezoneIsRequired(): void
    {
        $campaign = new CrowdfundingCampaign('Microsoft', 'Hololens', 'USD', 'US');
        $campaign->setOpeningAt(new \DateTimeImmutable('2022-01-01 10:00:00'));
        $campaign->setClosingAt(new \DateTimeImmutable('2022-01-31 23:59:59'));

        $this->assertTrue($campaign->isTimezoneRequired());
    }

    /**
     * @dataProvider provideTimezoneNotRequiredData
     */
    public function testTimezoneIsNotRequired(?\DateTimeImmutable $openingAt, ?\DateTimeImmutable $closingAt, ?string $timezone): void
    {
        $campaign = new CrowdfundingCampaign('Microsoft', 'Hololens', 'USD', 'US');
        $campaign->setOpeningAt($openingAt);
        $campaign->setClosingAt($closingAt);
        $campaign->setTimezone($timezone);

        $this->assertFalse($campaign->isTimezoneRequired());
    }

    public function provideTimezoneNotRequiredData(): \Generator
    {
        yield [null, null, null];
        yield [new \DateTimeImmutable('2022-01-01 10:00:00'), null, null];
        yield [null, new \DateTimeImmutable('2022-01-31 10:00:00'), null];
        yield [new \DateTimeImmutable('2022-01-01 10:00:00'), new \DateTimeImmutable('2022-01-31 10:00:00'), 'Europe/Berlin'];
    }

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
