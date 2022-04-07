<?php

declare(strict_types=1);

namespace App\Payment;

use Psr\Log\LoggerInterface;

final class DummyPaymentGateway implements PaymentGatewayInterface
{
    private LoggerInterface $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function tokenizeCreditCard(string $creditCard, array $options = []): string
    {
        $this->logger->error('Tokenizing credit card {card}.', ['card' => $creditCard]);

        return '29846T2398728R6356';
    }
}