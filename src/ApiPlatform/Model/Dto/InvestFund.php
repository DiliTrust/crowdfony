<?php

declare(strict_types=1);

namespace App\ApiPlatform\Model\Dto;

use ApiPlatform\Core\Annotation\ApiProperty;
use App\Entity\CrowdfundingCampaign;
use Symfony\Component\Validator\Constraints as Assert;

final class InvestFund
{
    /**
     * @ApiProperty(example="/api/crowdfunding_campaigns/16", required=true)
     *
     * @Assert\NotBlank(message="The crowdfunding campaign IRI is required.")
     */
    private ?CrowdfundingCampaign $campaign = null;

    /**
     * The equity amount to invest.
     *
     * @ApiProperty(
     *   example=100000,
     *   required=true,
     *   openapiContext={
     *     "type": "integer",
     *     "format": "int32",
     *   }
     * )
     *
     * @Assert\GreaterThanOrEqual(10000)
     */
    private int $equityAmount = 0;

    /**
     * The credit card number scheme.
     *
     * @ApiProperty(example="4012888888881881", required=true)
     *
     * @Assert\NotBlank
     * @Assert\CardScheme({"AMEX", "VISA", "MASTERCARD"})
     */
    private string $creditCardNumber = '';

    public function __construct(
        ?CrowdfundingCampaign $campaign = null,
        int $equityAmount = 0,
        string $creditCardNumber = ''
    ) {
        $this->campaign = $campaign;
        $this->equityAmount = $equityAmount;
        $this->creditCardNumber = $creditCardNumber;
    }

    public function getCampaign(): ?CrowdfundingCampaign
    {
        return $this->campaign;
    }

    public function getEquityAmount(): int
    {
        return $this->equityAmount;
    }

    public function getCreditCardNumber(): string
    {
        return $this->creditCardNumber;
    }
}