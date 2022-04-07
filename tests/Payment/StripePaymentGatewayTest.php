<?php

declare(strict_types=1);

namespace App\Tests\Payment;

use App\Payment\StripePaymentGateway;
use PHPUnit\Framework\TestCase;
use Psr\Log\NullLogger;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

final class StripePaymentGatewayTest extends TestCase
{
    public function testTokenizeCreditCard(): void
    {
        $response = $this->createMock(ResponseInterface::class);
        $response->expects($this->once())->method('toArray')->willReturn(['credit_card_token' => '82638625432R3']);

        $httpClient = $this->createMock(HttpClientInterface::class);
        $httpClient
            ->expects($this->once())
            ->method('request')
            ->with('POST', '/api/credit_card')
            ->willReturn($response);

        $gateway = new StripePaymentGateway($httpClient, new NullLogger());

        $this->assertSame('82638625432R3', $gateway->tokenizeCreditCard('19732342333'));
    }
}