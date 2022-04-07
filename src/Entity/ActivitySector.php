<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\ActivitySectorRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(uniqueConstraints={
 *   @ORM\UniqueConstraint("activity_sector_name_unique", columns={"name"}),
 * })
 * @ORM\Entity(repositoryClass=ActivitySectorRepository::class)
 *
 * @ApiResource(
 *   description="The activity sectors that are linked to the `CrowdfundingCampaign` resources.",
 *   order={"name": "ASC"},
 *   paginationClientEnabled=true,
 *   paginationItemsPerPage=15,
 *   paginationMaximumItemsPerPage=30,
 *   normalizationContext={
 *     "groups": {"activity_sector:read"},
 *     "skip_null_values": false,
 *   },
 *   denormalizationContext={
 *     "groups": {"activity_sector:write"},
 *     "skip_null_values": false,
 *   },
 *   itemOperations={
 *     "get"={
 *        "normalization_context"={
 *           "groups"={
 *             "activity_sector:read",
 *             "activity_sector:read:item",
 *           },
 *        },
 *        "openapi_context"={
 *          "summary": "This endpoint returns one complete activity sector",
 *          "description": "This endpoint returns one complete activity sector resource found by its primary identifier.",
 *        },
 *     },
 *     "put",
 *     "patch",
 *   },
 * )
 */
class ActivitySector
{
    /**
     * The activity sector unique identifier.
     *
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer", options={"unsigned": true})
     *
     * @ApiProperty(writable=false, example="140")
     *
     * @Groups("activity_sector:read")
     */
    private ?int $id = null;

    /**
     * The activity sector name.
     *
     * @ORM\Column(length=50)
     *
     * @ApiProperty(example="Childcare")
     *
     * @Groups({"activity_sector:read", "activity_sector:write"})
     *
     * @Assert\NotBlank(message="The activity sector name is required.")
     * @Assert\Length(
     *   min=5,
     *   max=50,
     *   minMessage="The activity sector name is too short. It must be at least {{ limit }} characters.",
     *   maxMessage="The activity sector name is too long. It must be at most {{ limit }} characters.",
     * )
     */
    private string $name = '';

    /**
     * The activity sector description.
     *
     * @ORM\Column(type="text", nullable=true)
     *
     * @Groups({"activity_sector:read:item", "activity_sector:write"})
     *
     * @Assert\NotBlank(message="The activity sector description is required.")
     * @Assert\Length(
     *   max=2500,
     *   maxMessage="The activity sector name is too long. It must be at most {{ limit }} characters.",
     * )
     */
    private ?string $description = null;

    /**
     * Whether the activity sector is browsable by the users.
     *
     * @ORM\Column(type="boolean", options={"default": 0})
     *
     * @Groups({"activity_sector:read"})
     *
     * @ApiProperty(
     *   attributes={
     *     "security": "is_granted('ROLE_ADMIN')",
     *   }
     * )
     */
    private bool $isEnabled = true;

    /**
     * @var Collection<int, CrowdfundingCampaign>
     *
     * @ORM\OneToMany(targetEntity=CrowdfundingCampaign::class, mappedBy="activitySector")
     */
    private Collection $campaigns;

    public function __construct(string $name)
    {
        $this->name = $name;
        $this->campaigns = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    public function getIsEnabled(): bool
    {
        return $this->isEnabled;
    }

    public function isEnabled(): bool
    {
        return $this->isEnabled;
    }

    public function setIsEnabled(bool $isEnabled): void
    {
        $this->isEnabled = $isEnabled;
    }

    /**
     * @return Collection<int, CrowdfundingCampaign>
     */
    public function getCampaigns(): Collection
    {
        return $this->campaigns;
    }

    public function addCampaign(CrowdfundingCampaign $campaign): void
    {
        if (! $this->campaigns->contains($campaign)) {
            $this->campaigns->add($campaign);
            $campaign->setActivitySector($this);
        }
    }

    public function removeCampaign(CrowdfundingCampaign $campaign): void
    {
        if ($this->campaigns->removeElement($campaign)) {
            // set the owning side to null (unless already changed)
            if ($campaign->getActivitySector() === $this) {
                $campaign->setActivitySector(null);
            }
        }
    }
}
