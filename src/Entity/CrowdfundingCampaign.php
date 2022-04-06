<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\DateFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\ExistsFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\RangeFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Core\Serializer\Filter\PropertyFilter;
use App\DBAL\Types\CampaignStatusType;
use App\Repository\CrowdfundingCampaignRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Money\Currency;
use Money\Money;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\SerializedName;

/**
 * @ORM\Table(uniqueConstraints={
 *   @ORM\UniqueConstraint("campaign_slug_unique", columns={"slug"}),
 * })
 * @ORM\Entity(repositoryClass=CrowdfundingCampaignRepository::class)
 *
 * @ApiResource(
 *   order={"id": "DESC"},
 *   normalizationContext={
 *     "groups": {"crowdfunding_campaign:read"},
 *     "skip_null_values": false,
 *   },
 *   itemOperations={
 *     "get"={
 *        "normalization_context"={
 *           "groups"={
 *             "crowdfunding_campaign:read",
 *             "crowdfunding_campaign:read:item",
 *           },
 *        },
 *     },
 *     "put",
 *     "patch",
 *     "delete",
 *   }
 * )
 * @ApiFilter(
 *   OrderFilter::class,
 *   properties={"id", "company", "project", "currency", "country", "status", "activitySector.id"},
 * )
 * @ApiFilter(
 *   SearchFilter::class,
 *   properties={
 *     "id": "exact",
 *     "company": "ipartial",
 *     "project": "ipartial",
 *     "country": "exact",
 *     "currency": "exact",
 *     "status": "iexact",
 *     "activitySector.name": "ipartial",
 *   }
 * )
 * @ApiFilter(
 *   DateFilter::class,
 *   properties={
 *     "openingAt": DateFilter::EXCLUDE_NULL,
 *     "closingAt": DateFilter::EXCLUDE_NULL,
 *   }
 * )
 * @ApiFilter(
 *   RangeFilter::class,
 *   properties={"idealFundingTarget"}
 * )
 * @ApiFilter(
 *   ExistsFilter::class,
 *   properties={"description"}
 * )
 * @ApiFilter(PropertyFilter::class)
 */
class CrowdfundingCampaign
{
    /**
     * The crowdfunding campaign identifier.
     *
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     *
     * @ApiProperty(writable=false, example="17")
     */
    private ?int $id = null;

    /**
     * The crowdfunding campaign company name.
     *
     * @ORM\Column(length=100)
     *
     * @ApiProperty(example="Microsoft")
     *
     * @Groups({"crowdfunding_campaign:read"})
     */
    private string $company = '';

    /**
     * The crowdfunding campaign project name.
     *
     * @ORM\Column(length=100)
     *
     * @ApiProperty(example="Hololens")
     *
     * @Groups({"crowdfunding_campaign:read"})
     */
    private string $project = '';

    /**
     * The crowdfunding campaign slug.
     *
     * @ORM\Column(length=255)
     *
     * @Gedmo\Slug(fields={"company", "project"})
     *
     * @ApiProperty(example="microsoft-hololens")
     *
     * @Groups({"crowdfunding_campaign:read"})
     */
    private ?string $slug = null;

    /**
     * The crowdfunding campaign currency.
     *
     * @ORM\Column(length=3, options={"default": "EUR"})
     *
     * @ApiProperty(example="USD")
     *
     * @Groups({"crowdfunding_campaign:read"})
     */
    private string $currency = 'EUR';

    /**
     * The crowdfunding campaign company country.
     *
     * @ORM\Column(length=2, options={"default": "FR"})
     *
     * @ApiProperty(example="US")
     *
     * @Groups({"crowdfunding_campaign:read"})
     */
    private string $country = 'FR';

    /**
     * The crowdfunding campaign description.
     *
     * @ORM\Column(type="text", nullable=true)
     *
     * @ApiProperty(example="Microsoft Hololens is revolutionary project for VR environments...")
     *
     * @Groups({"crowdfunding_campaign:read:item"})
     */
    private ?string $description = null;

    /**
     * The crowdfunding campaign status.
     *
     * @phpstan-var CampaignStatusType::*
     *
     * @ORM\Column(type="CampaignStatusType")
     *
     * @ApiProperty(example=CampaignStatusType::COLLECTING_FUNDS)
     *
     * @Groups({"crowdfunding_campaign:read"})
     */
    private string $status = CampaignStatusType::DRAFTING;

    /**
     * The crowdfunding campaign minimum funding goal.
     *
     * Amount is expressed in the smallest unit of the money (i.e. cents).
     *
     * @ORM\Column(type="integer", nullable=true, options={"unsigned": true})
     */
    private ?int $minFundingTarget = null;

    /**
     * The crowdfunding campaign ideal funding goal.
     *
     * Amount is expressed in the smallest unit of the money (i.e. cents).
     *
     * @ORM\Column(type="integer", nullable=true, options={"unsigned": true})
     *
     * @ApiProperty(example=45000000)
     *
     * @Groups({"crowdfunding_campaign:read"})
     * @SerializedName("fundingGoal")
     */
    private ?int $idealFundingTarget = null;

    /**
     * The crowdfunding campaign maximum funding goal.
     *
     * Amount is expressed in the smallest unit of the money (i.e. cents).
     *
     * @ORM\Column(type="integer", nullable=true, options={"unsigned": true})
     */
    private ?int $maxFundingTarget = null;

    /**
     * The crowdfunding campaign opening datetime.
     *
     * Datetime is expressed in local date & time for the related `timezone` property.
     *
     * @ORM\Column(type="datetime_immutable", nullable=true)
     *
     * @ApiProperty(example="2022-03-04 09:00:00")
     *
     * @Groups({"crowdfunding_campaign:read:item"})
     */
    private ?\DateTimeImmutable $openingAt = null;

    /**
     * The crowdfunding campaign closing datetime.
     *
     * Datetime is expressed in local date & time for the related `timezone` property.
     *
     * @ORM\Column(type="datetime_immutable", nullable=true)
     *
     * @ApiProperty(example="2022-03-22 23:59:59")
     *
     * @Groups({"crowdfunding_campaign:read:item"})
     */
    private ?\DateTimeImmutable $closingAt = null;

    /**
     * The crowdfunding campaign schedule timezone.
     *
     * @ORM\Column(length=100, nullable=true)
     *
     * @ApiProperty(example="America/Los_Angeles")
     *
     * @Groups({"crowdfunding_campaign:read:item"})
     */
    private ?string $timezone = null;

    /**
     * @ORM\Column(type="datetime_immutable")
     *
     * @Gedmo\Timestampable(on="create")
     */
    private \DateTimeImmutable $createdAt;

    /**
     * @ORM\Column(type="datetime_immutable")
     *
     * @Gedmo\Timestampable(on="update")
     */
    private \DateTimeImmutable $updatedAt;

    /**
     * The crowdfunding campaign activity sector.
     *
     * @ORM\ManyToOne(targetEntity=ActivitySector::class, inversedBy="campaigns")
     * @ORM\JoinColumn(nullable=false, onDelete="RESTRICT")
     *
     * @Groups({"crowdfunding_campaign:read"})
     */
    private ?ActivitySector $activitySector = null;

    public function __construct(string $company, ?string $project = null, string $currency = 'EUR', string $country = 'FR')
    {
        $this->company = $company;
        $this->project = $project ?: $company;
        $this->currency = $currency;
        $this->country = $country;

        $this->createdAt = new \DateTimeImmutable();
        $this->updatedAt = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCompany(): string
    {
        return $this->company;
    }

    public function setCompany(string $company): void
    {
        $this->company = $company;
    }

    public function getProject(): string
    {
        return $this->project;
    }

    public function setProject(string $project): void
    {
        $this->project = $project;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(?string $slug): void
    {
        $this->slug = $slug;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }

    public function setCurrency(string $currency): void
    {
        $this->currency = $currency;
    }

    public function getCountry(): string
    {
        return $this->country;
    }

    public function setCountry(string $country): void
    {
        $this->country = $country;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    /**
     * @phpstan-return CampaignStatusType::*
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * @phpstan-param CampaignStatusType::* $status
     */
    public function setStatus(string $status): void
    {
        $this->status = $status;
    }

    public function getMinFundingTarget(): ?Money
    {
        if ($this->minFundingTarget === null) {
            return null;
        }

        return new Money($this->minFundingTarget, new Currency($this->currency));
    }

    public function setMinFundingTarget(?int $minFundingTarget): void
    {
        $this->minFundingTarget = $minFundingTarget;
    }

    public function getIdealFundingTarget(): ?Money
    {
        if ($this->idealFundingTarget === null) {
            return null;
        }

        return new Money($this->idealFundingTarget, new Currency($this->currency));
    }

    public function setIdealFundingTarget(?int $idealFundingTarget): void
    {
        $this->idealFundingTarget = $idealFundingTarget;
    }

    public function getMaxFundingTarget(): ?Money
    {
        if ($this->maxFundingTarget === null) {
            return null;
        }

        return new Money($this->maxFundingTarget, new Currency($this->currency));
    }

    public function setMaxFundingTarget(?int $maxFundingTarget): void
    {
        $this->maxFundingTarget = $maxFundingTarget;
    }

    public function getOpeningAt(): ?\DateTimeImmutable
    {
        return $this->openingAt;
    }

    public function setOpeningAt(?\DateTimeImmutable $openingAt): void
    {
        $this->openingAt = $openingAt;
    }

    public function getClosingAt(): ?\DateTimeImmutable
    {
        return $this->closingAt;
    }

    public function setClosingAt(?\DateTimeImmutable $closingAt): void
    {
        $this->closingAt = $closingAt;
    }

    public function getTimezone(): ?string
    {
        return $this->timezone;
    }

    public function setTimezone(?string $timezone): void
    {
        $this->timezone = $timezone;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function getUpdatedAt(): \DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeImmutable $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    public function getActivitySector(): ?ActivitySector
    {
        return $this->activitySector;
    }

    public function setActivitySector(?ActivitySector $activitySector): void
    {
        $this->activitySector = $activitySector;
    }

    /**
     * Returns the total amount of collected funds.
     *
     * @ApiProperty(example="32285000")
     *
     * @Groups({"crowdfunding_campaign:read:item"})
     */
    public function getTotalCollectedFunds(): Money
    {
        return new Money(0, new Currency($this->currency));
    }

    /**
     * Returns the fundraising progress percentage.
     *
     * @ApiProperty(example=82)
     *
     * @Groups({"crowdfunding_campaign:read:item"})
     */
    public function getFundraisingProgressPercentage(): int
    {
        if (! $fundingGoal = $this->getIdealFundingTarget()) {
            return 0;
        }

        return (int) $this->getTotalCollectedFunds()->ratioOf($fundingGoal);
    }

    public function isClosingSoon(): bool
    {
        if ($this->status !== CampaignStatusType::COLLECTING_FUNDS) {
            return false;
        }

        if (! $fundingGoal = $this->getIdealFundingTarget()) {
            return false;
        }

        return $this->getTotalCollectedFunds()->greaterThanOrEqual($fundingGoal);
    }
}
