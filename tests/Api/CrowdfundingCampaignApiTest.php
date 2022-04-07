<?php

declare(strict_types=1);

namespace App\Tests\Api;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;

final class CrowdfundingCampaignApiTest extends ApiTestCase
{
    public function testCreateCrowdfundingCampaignIsForbiddenToUnauthorizedUsers(): void
    {
        $client = static::createClient();

        $client->request('POST', '/api/login', [
            'json' => [
                'username' => 'investor@example.com',
                'password' => 'password',
            ],
        ]);

        $client->request('POST', '/api/crowdfunding_campaigns', [
            'json' => [
                'company' => 'Foo Bar',
            ],
        ]);

        $this->assertResponseStatusCodeSame(403);
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
    }

    public function testCreateCrowdfundingCampaignFailsWithValidationErrors(): void
    {
        $client = static::createClient();

        $client->request('POST', '/api/login', [
            'json' => [
                'username' => 'campaign.manager@example.com',
                'password' => 'password',
            ],
        ]);

        $client->request('POST', '/api/crowdfunding_campaigns', [
            'json' => [
                'company' => 'Foo Bar',
            ],
        ]);

        $this->assertResponseStatusCodeSame(422);
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
    }
}
