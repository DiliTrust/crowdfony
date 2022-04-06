<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
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
use Symfony\Component\String\Slugger\AsciiSlugger;

/**
 * @ORM\Table(uniqueConstraints={
 *   @ORM\UniqueConstraint("campaign_slug_unique", columns={"slug"}),
 * })
 * @ORM\Entity(repositoryClass=CrowdfundingCampaignRepository::class)
 *
 * @ApiResource(
 *   order={"id": "DESC"},
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
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id = null;

    /**
     * @ORM\Column(length=100)
     */
    private string $company = '';

    /**
     * @ORM\Column(length=100)
     */
    private string $project = '';

    /**
     * @ORM\Column(length=255)
     */
    private ?string $slug = null;

    /**
     * @ORM\Column(length=3, options={"default": "EUR"})
     */
    private string $currency = 'EUR';

    /**
     * @ORM\Column(length=2, options={"default": "FR"})
     */
    private string $country = 'FR';

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private ?string $description = null;

    /**
     * @phpstan-var CampaignStatusType::*
     *
     * @ORM\Column(type="CampaignStatusType")
     */
    private string $status = CampaignStatusType::DRAFTING;

    /**
     * @ORM\Column(type="integer", nullable=true, options={"unsigned": true})
     */
    private ?int $minFundingTarget = null;

    /**
     * @ORM\Column(type="integer", nullable=true, options={"unsigned": true})
     */
    private ?int $idealFundingTarget = null;

    /**
     * @ORM\Column(type="integer", nullable=true, options={"unsigned": true})
     */
    private ?int $maxFundingTarget = null;

    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
    private ?\DateTimeImmutable $openingAt = null;

    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
    private ?\DateTimeImmutable $closingAt = null;

    /**
     * @ORM\Column(length=100, nullable=true)
     */
    private ?string $timezone = null;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private \DateTimeImmutable $createdAt;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private \DateTimeImmutable $updatedAt;

    /**
     * @ORM\ManyToOne(targetEntity=ActivitySector::class, inversedBy="campaigns")
     * @ORM\JoinColumn(nullable=false, onDelete="RESTRICT")
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

        $slugger = new AsciiSlugger();

        $this->slug = $slugger->slug($this->company)->lower() . '--' . $slugger->slug($this->project)->lower();
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

    public function getMinFundingTarget(): ?int
    {
        return $this->minFundingTarget;
    }

    public function setMinFundingTarget(?int $minFundingTarget): void
    {
        $this->minFundingTarget = $minFundingTarget;
    }

    public function getIdealFundingTarget(): ?int
    {
        return $this->idealFundingTarget;
    }

    public function setIdealFundingTarget(?int $idealFundingTarget): void
    {
        $this->idealFundingTarget = $idealFundingTarget;
    }

    public function getMaxFundingTarget(): ?int
    {
        return $this->maxFundingTarget;
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
}
