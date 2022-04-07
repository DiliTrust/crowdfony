<?php

declare(strict_types=1);

namespace App\ApiPlatform\DataTransformer;

use ApiPlatform\Core\DataTransformer\DataTransformerInterface;
use ApiPlatform\Core\Validator\ValidatorInterface;
use App\ApiPlatform\Model\Dto\InvestFund;
use App\Entity\FundInvestment;
use App\Entity\User;
use Money\Currency;
use Money\Money;
use Symfony\Component\Security\Core\Security;

final class InvestFundDataTransformer implements DataTransformerInterface
{
    private ValidatorInterface $validator;
    private Security $security;

    public function __construct(ValidatorInterface $validator, Security $security)
    {
        $this->validator = $validator;
        $this->security = $security;
    }

    public function transform($object, string $to, array $context = []): FundInvestment
    {
        \assert($object instanceof InvestFund);

        $this->validator->validate($object);

        $investor = $this->security->getUser();
        \assert($investor instanceof User);

        $equityAmount = new Money($object->getEquityAmount(), new Currency($object->getCampaign()->getCurrency()));

        // Calculate processing fees
        $processingFees = $equityAmount->multiply(0.02);

        // Tokenize credit card with payment gateway
        // ...

        return new FundInvestment(
            $object->getCampaign(),
            $investor,
            $equityAmount,
            $processingFees
        );
    }

    public function supportsTransformation($data, string $to, array $context = []): bool
    {
        return \is_array($data)
            && ($context['input']['class'] ?? null) === InvestFund::class
            && $to === FundInvestment::class
            && ($context['operation_type'] ?? null) === 'collection'
            && ($context['collection_operation_name'] ?? null) === 'post';
    }
}