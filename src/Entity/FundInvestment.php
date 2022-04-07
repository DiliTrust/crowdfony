<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\ApiPlatform\Model\Dto\InvestFund;
use App\Repository\FundInvestmentRepository;
use Doctrine\ORM\Mapping as ORM;
use Money\Money;
use Symfony\Component\Uid\Uuid;

/**
 * @ORM\Table(
 *   uniqueConstraints={
 *     @ORM\UniqueConstraint(name="fund_investment_uuid_unique", columns={"uuid"}),
 *     @ORM\UniqueConstraint(name="fund_investment_charge_transaction_id_unique", columns={"charge_transaction_id"}),
 *   }
 * )
 * @ORM\Entity(repositoryClass=FundInvestmentRepository::class)
 *
 * @ApiResource(
 *   attributes={
 *     "security": "is_granted('ROLE_INVESTOR')",
 *   },
 *   collectionOperations={
 *     "post"={
 *       "input": InvestFund::class,
 *     },
 *     "clean_outdated_investment_attempts"={
 *       "method": "DELETE",
 *       "path": "/fund_investments/outdated_attempts",
 *       "controller": "api_platform.action.delete_collection",
 *     }
 *   }
 * )
 */
class FundInvestment
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer", options={"unsigned": true})
     */
    private ?int $id = null;

    /**
     * @ORM\Column(type="guid")
     */
    private string $uuid = '';

    /**
     * @ORM\Column(type="integer", options={"unsigned": true})
     */
    private int $equityAmount;

    /**
     * @ORM\Column(type="integer", options={"unsigned": true, "default": 0})
     */
    private int $processingFeeAmount = 0;

    /**
     * @ORM\Column(type="integer", options={"unsigned": true})
     */
    private int $totalChargedAmount;

    /**
     * @ORM\Column
     */
    private string $status = 'pending';

    /**
     * @ORM\Column(nullable=true)
     */
    private ?string $creditCardToken = null;

    /**
     * @ORM\Column(nullable=true)
     */
    private ?string $chargeTransactionId = null;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private \DateTimeImmutable $createdAt;

    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
    private ?\DateTimeImmutable $chargedAt = null;

    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
    private ?\DateTimeImmutable $canceledAt = null;

    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
    private ?\DateTimeImmutable $refundedAt = null;

    /**
     * @ORM\ManyToOne(targetEntity=CrowdfundingCampaign::class, inversedBy="fundInvestments")
     * @ORM\JoinColumn(nullable=false, onDelete="RESTRICT")
     */
    private ?CrowdfundingCampaign $campaign = null;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="portfolio")
     * @ORM\JoinColumn(nullable=false, onDelete="RESTRICT")
     */
    private ?User $investor = null;

    public function __construct(CrowdfundingCampaign $campaign, User $investor, Money $equityAmount, Money $processingFeeAmount)
    {
        $this->uuid = Uuid::v4()->toRfc4122();
        $this->campaign = $campaign;
        $this->investor = $investor;
        $this->equityAmount = (int) $equityAmount->getAmount();
        $this->processingFeeAmount = (int) $processingFeeAmount->getAmount();
        $this->totalChargedAmount = (int) $equityAmount->add($processingFeeAmount)->getAmount();
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUuid(): ?string
    {
        return $this->uuid;
    }

    public function setUuid(string $uuid): self
    {
        $this->uuid = $uuid;

        return $this;
    }

    public function getEquityAmount(): ?int
    {
        return $this->equityAmount;
    }

    public function setEquityAmount(int $equityAmount): self
    {
        $this->equityAmount = $equityAmount;

        return $this;
    }

    public function getProcessingFeeAmount(): ?int
    {
        return $this->processingFeeAmount;
    }

    public function setProcessingFeeAmount(int $processingFeeAmount): self
    {
        $this->processingFeeAmount = $processingFeeAmount;

        return $this;
    }

    public function getTotalChargedAmount(): ?int
    {
        return $this->totalChargedAmount;
    }

    public function setTotalChargedAmount(int $totalChargedAmount): self
    {
        $this->totalChargedAmount = $totalChargedAmount;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getCreditCardToken(): ?string
    {
        return $this->creditCardToken;
    }

    public function setCreditCardToken(?string $creditCardToken): self
    {
        $this->creditCardToken = $creditCardToken;

        return $this;
    }

    public function getChargeTransactionId(): ?string
    {
        return $this->chargeTransactionId;
    }

    public function setChargeTransactionId(?string $chargeTransactionId): self
    {
        $this->chargeTransactionId = $chargeTransactionId;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getChargedAt(): ?\DateTimeImmutable
    {
        return $this->chargedAt;
    }

    public function setChargedAt(?\DateTimeImmutable $chargedAt): self
    {
        $this->chargedAt = $chargedAt;

        return $this;
    }

    public function getCanceledAt(): ?\DateTimeImmutable
    {
        return $this->canceledAt;
    }

    public function setCanceledAt(?\DateTimeImmutable $canceledAt): self
    {
        $this->canceledAt = $canceledAt;

        return $this;
    }

    public function getRefundedAt(): ?\DateTimeImmutable
    {
        return $this->refundedAt;
    }

    public function setRefundedAt(?\DateTimeImmutable $refundedAt): self
    {
        $this->refundedAt = $refundedAt;

        return $this;
    }

    public function getCampaign(): ?CrowdfundingCampaign
    {
        return $this->campaign;
    }

    public function setCampaign(?CrowdfundingCampaign $campaign): self
    {
        $this->campaign = $campaign;

        return $this;
    }

    public function getInvestor(): ?User
    {
        return $this->investor;
    }

    public function setInvestor(?User $investor): self
    {
        $this->investor = $investor;

        return $this;
    }
}
