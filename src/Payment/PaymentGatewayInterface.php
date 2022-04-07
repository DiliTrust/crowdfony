<?php

declare(strict_types=1);

namespace App\Payment;

interface PaymentGatewayInterface
{
    /**
     * @param array<string, mixed> $options
     */
    public function tokenizeCreditCard(string $creditCard, array $options = []): string;
}