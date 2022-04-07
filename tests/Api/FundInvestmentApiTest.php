<?php

declare(strict_types=1);

namespace App\Tests\Api;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use App\Entity\CrowdfundingCampaign;
use App\Entity\User;
use App\Repository\CrowdfundingCampaignRepository;
use App\Repository\UserRepository;

final class FundInvestmentApiTest extends ApiTestCase
{
    public function testInvestFundInCollectingCampaign(): void
    {
        $client = static::createClient();

        $repository = static::getContainer()->get(CrowdfundingCampaignRepository::class);
        \assert($repository instanceof CrowdfundingCampaignRepository);

        $campaign = $repository->findOneBy(['slug' => 'dilitrust-trust-suite']);
        \assert($campaign instanceof CrowdfundingCampaign);

        $repository = static::getContainer()->get(UserRepository::class);
        \assert($repository instanceof UserRepository);

        $investor = $repository->findOneBy(['emailAddress' => 'investor@example.com']);
        \assert($investor instanceof User);

        $client->request('POST', '/api/login', [
            'json' => [
                'username' => $investor->getEmailAddress(),
                'password' => 'password',
            ],
        ]);

        $response = $client->request('POST', '/api/fund_investments', [
            'json' => [
                'campaign' => '/api/crowdfunding_campaigns/' . $campaign->getId(),
                'equityAmount' => 250000,
                'creditCardNumber' => '4012888888881881',
            ],
        ]);

        $this->assertResponseStatusCodeSame(201);
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');

        $this->assertJsonContains([
            'equityAmount' => 250000,
            'processingFeeAmount' => 5000,
            'totalChargedAmount' => 255000,
            'status' => 'pending',
            'campaign' => '/api/crowdfunding_campaigns/' . $campaign->getId(),
            'investor' => '/api/users/' . $investor->getId(),
        ]);
    }
}
