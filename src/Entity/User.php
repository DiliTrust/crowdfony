<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use function Symfony\Component\String\u;

/**
 * @ORM\Table(
 *   uniqueConstraints={
 *     @ORM\UniqueConstraint(name="user_email_address_unique", columns={"email_address"}),
 *   }
 * )
 * @ORM\Entity(repositoryClass=UserRepository::class)
 *
 * @ApiResource(
 *   itemOperations={
 *     "get"={
 *       "security": "is_granted('ROLE_ADMIN') or object == user",
 *     },
 *     "put"={
 *       "security": "is_granted('ROLE_ADMIN') or object == user",
 *     }
 *   }
 * )
 *
 * @UniqueEntity({"emailAddress"}, message="This email address is already taken.", errorPath="emailAddress")
 */
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    public const ADMIN = 'ROLE_ADMIN';
    public const CAMPAIGN_MANAGER = 'ROLE_CAMPAIGN_MANAGER';
    public const INVESTOR = 'ROLE_INVESTOR';
    public const ACCOUNTANT = 'ROLE_ACCOUNTANT';
    public const LEGALIST = 'ROLE_LEGALIST';

    public const AVAILABLE_ROLES = [
        self::ADMIN,
        self::CAMPAIGN_MANAGER,
        self::INVESTOR,
        self::ACCOUNTANT,
        self::LEGALIST,
    ];

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id = null;

    /**
     * @ORM\Column(length=180)
     */
    private string $emailAddress = '';

    /**
     * @var string[]
     *
     * @ORM\Column(type="json")
     */
    private array $roles = [];

    /**
     * The hashed password.
     *
     * @ORM\Column
     */
    private string $password = '';

    /**
     * @ORM\OneToMany(targetEntity=FundInvestment::class, mappedBy="investor")
     */
    private $portfolio;

    public function __construct(string $emailAddress)
    {
        $this->emailAddress = u($emailAddress)->lower()->trim()->toString();
        $this->portfolio = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmailAddress(): string
    {
        return $this->emailAddress;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return $this->emailAddress;
    }

    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername(): string
    {
        return $this->emailAddress;
    }

    /**
     * @return string[]
     *
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param string[] $roles
     */
    public function setRoles(array $roles): void
    {
        $this->roles = $roles;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
    }

    public function getApiToken(): ?string
    {
        return 'my_custom_api_token';
    }

    /**
     * @return Collection<int, FundInvestment>
     */
    public function getPortfolio(): Collection
    {
        return $this->portfolio;
    }

    public function addPortfolio(FundInvestment $portfolio): self
    {
        if (!$this->portfolio->contains($portfolio)) {
            $this->portfolio[] = $portfolio;
            $portfolio->setInvestor($this);
        }

        return $this;
    }

    public function removePortfolio(FundInvestment $portfolio): self
    {
        if ($this->portfolio->removeElement($portfolio)) {
            // set the owning side to null (unless already changed)
            if ($portfolio->getInvestor() === $this) {
                $portfolio->setInvestor(null);
            }
        }

        return $this;
    }
}
