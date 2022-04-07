<?php

declare(strict_types=1);

namespace App\ApiPlatform\DataTransformer;

use ApiPlatform\Core\DataTransformer\DataTransformerInterface;
use ApiPlatform\Core\Validator\ValidatorInterface;
use App\ApiPlatform\Model\Dto\InvestFund;
use App\Entity\CrowdfundingCampaign;
use App\Entity\FundInvestment;
use App\Entity\User;
use App\Payment\PaymentGatewayInterface;
use Money\Currency;
use Money\Money;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Security;

final class InvestFundDataTransformer implements DataTransformerInterface
{
    private ValidatorInterface $validator;
    private Security $security;
    private PaymentGatewayInterface $paymentGateway;

    public function __construct(
        ValidatorInterface $validator,
        Security $security,
        PaymentGatewayInterface $paymentGateway
    ) {
        $this->validator = $validator;
        $this->security = $security;
        $this->paymentGateway = $paymentGateway;
    }

    public function transform($object, string $to, array $context = []): FundInvestment
    {
        \assert($object instanceof InvestFund);

        $this->validator->validate($object);

        $campaign = $object->getCampaign();
        \assert($campaign instanceof CrowdfundingCampaign);

        $equityAmount = new Money($object->getEquityAmount(), new Currency($object->getCampaign()->getCurrency()));

        $campaign->lastInvestedAmount = $equityAmount;
        if (! $this->security->isGranted('invest', $campaign)) {
            throw new AccessDeniedException('User is not allowed to invest in campaign.');
        }

        $investor = $this->security->getUser();
        \assert($investor instanceof User);

        // Calculate processing fees
        $processingFees = $equityAmount->multiply(0.02);

        $investment = new FundInvestment(
            $campaign,
            $investor,
            $equityAmount,
            $processingFees
        );

        $investment->setCreditCardToken($this->paymentGateway->tokenizeCreditCard($object->getCreditCardNumber()));

        $campaign->addFundInvestment($investment);

        return $investment;
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