<?php

declare(strict_types=1);

namespace App\ApiPlatform\Serializer;

use Money\Currencies\ISOCurrencies;
use Money\Formatter\DecimalMoneyFormatter;
use Money\Money;
use Money\MoneyFormatter;
use Symfony\Component\Serializer\Normalizer\ContextAwareNormalizerInterface;

final class MoneyNormalizer implements ContextAwareNormalizerInterface
{
    private MoneyFormatter $formatter;

    public function __construct()
    {
        $this->formatter = new DecimalMoneyFormatter(new ISOCurrencies());
    }

    /**
     * @param mixed $object
     * @param array<string, mixed> $context
     */
    public function supportsNormalization($object, ?string $format = null, array $context = []): bool
    {
        return $object instanceof Money;
    }

    /**
     * @param Money                $object
     * @param array<string, mixed> $context
     *
     * @return array<string, mixed>
     */
    public function normalize($object, ?string $format = null, array $context = []) // @phpcs:ignore
    {
        $data = $object->jsonSerialize();

        return [
            'amount' => $data['amount'],
            'currency' => $data['currency'],
            'decimalAmount' => $this->formatter->format($object),
        ];
    }
}
