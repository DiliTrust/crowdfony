<?php

declare(strict_types=1);

namespace App\Payment;

use Psr\Log\LoggerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

final class StripePaymentGateway implements PaymentGatewayInterface
{
    private HttpClientInterface $api;
    private LoggerInterface $logger;

    public function __construct(HttpClientInterface $api, LoggerInterface $logger)
    {
        $this->api = $api;
        $this->logger = $logger;
    }

    public function tokenizeCreditCard(string $creditCard, array $options = []): string
    {
        $this->logger->info('Tokenizing credit card with Stripe.');

        $response = $this->api->request('POST', '/api/credit_card');
        $data = $response->toArray();

        return $data['credit_card_token'];
    }
}