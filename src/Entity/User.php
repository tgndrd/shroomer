<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Post;
use App\Model\Cost;
use App\Provider\UserProvider;
use App\Repository\UserRepository;
use App\State\UserPasswordHasher;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\OrderBy;
use Doctrine\ORM\Mapping\PrePersist;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\UniqueConstraint;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;

#[Entity(repositoryClass: UserRepository::class)]
#[HasLifecycleCallbacks]
#[Table(name: 'app_user')]
#[UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
#[UniqueEntity('email')]
#[ApiResource(
    operations: [
        new Get(uriTemplate: '/user', provider: UserProvider::class),
        new Post(uriTemplate: '/register', processor: UserPasswordHasher::class),
    ],
    normalizationContext: ['groups' => [self::GROUP_READ_USER]],
    denormalizationContext: ['groups' => [self::GROUP_WRITE_USER]],
    mercure: ['private' => true]
)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    public const string ROLE_USER = 'ROLE_USER';
    public const string ROLE_ADMIN = 'ROLE_ADMIN';

    public const string GROUP_WRITE_USER = 'write_user';
    public const string GROUP_READ_USER = 'read_user';

    #[Groups([self::GROUP_READ_USER])]
    #[Id]
    #[GeneratedValue(strategy: 'SEQUENCE')]
    #[Column(type: Types::INTEGER, nullable: false)]
    private ?int $id = null;

    #[Email]
    #[NotBlank]
    #[Groups([self::GROUP_READ_USER, self::GROUP_WRITE_USER])]
    #[Column(type: Types::STRING, length: 180, nullable: false)]
    private ?string $email = null;

    #[Column(type: Types::STRING, length: 255, nullable: false)]
    private ?string $password = null;

    #[NotBlank]
    #[Groups([self::GROUP_WRITE_USER])]
    private ?string $plainPassword = null;

    #[OneToMany(targetEntity: Zone::class, mappedBy: 'user', cascade: ['persist'])]
    #[OrderBy(['id' => 'DESC'])]
    private Collection $zones;

    #[Column(type: Types::JSON)]
    private array $roles = [];

    #[Groups([self::GROUP_READ_USER])]
    #[Column(type: Types::INTEGER)]
    private int $resourceFlora = 0;

    #[Groups([self::GROUP_READ_USER])]
    #[Column(type: Types::INTEGER)]
    private int $resourceFauna = 0;

    #[Groups([self::GROUP_READ_USER])]
    #[Column(type: Types::INTEGER)]
    private int $resourceEntomofauna = 0;

    public function __construct()
    {
        $this->zones = new ArrayCollection();
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @param string $email
     *
     * @return $this
     */
    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     *
     * @return list<string>
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        $roles[] = self::ROLE_USER;

        return array_unique($roles);
    }

    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * @param string $password
     *
     * @return $this
     */
    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * @return Collection
     */
    public function getZones(): Collection
    {
        return $this->zones;
    }

    /**
     * @param Zone $zone
     *
     * @return void
     */
    public function addZone(Zone $zone): void
    {
        foreach ($this->zones as $z) {
            if ($z->getId() == $zone->getId()) {
                return;
            }
        }

        $this->zones->add($zone);
    }

    /**
     * @return string|null
     */
    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    /**
     * @param string|null $plainPassword
     *
     * @return void
     */
    public function setPlainPassword(?string $plainPassword): void
    {
        $this->plainPassword = $plainPassword;
    }

    /**
     * @return int
     */
    public function getResourceFlora(): int
    {
        return $this->resourceFlora;
    }

    /**
     * @param int $resourceFlora
     *
     * @return void
     */
    public function setResourceFlora(int $resourceFlora): void
    {
        $this->resourceFlora = $resourceFlora;
    }

    /**
     * @return int
     */
    public function getResourceFauna(): int
    {
        return $this->resourceFauna;
    }

    /**
     * @param int $resourceFauna
     *
     * @return void
     */
    public function setResourceFauna(int $resourceFauna): void
    {
        $this->resourceFauna = $resourceFauna;
    }

    /**
     * @return int
     */
    public function getResourceEntomofauna(): int
    {
        return $this->resourceEntomofauna;
    }

    /**
     * @param int $resourceEntomofauna
     *
     * @return void
     */
    public function setResourceEntomofauna(int $resourceEntomofauna): void
    {
        $this->resourceEntomofauna = $resourceEntomofauna;
    }

    /**
     * @param Cost $cost
     *
     * @return bool
     */
    public function canAfford(Cost $cost): bool
    {
        if ($cost->getResourceFauna() > $this->resourceFauna) {
            return false;
        }

        if ($cost->getResourceFlora() > $this->resourceFlora) {
            return false;
        }

        return !($cost->getResourceEntomofauna() > $this->resourceEntomofauna);
    }

    /**
     * @param Cost $cost
     *
     * @return bool
     */
    public function afford(Cost $cost): bool
    {
        if (!$this->canAfford($cost)) {
            return false;
        }

        $this->resourceFlora -= $cost->getResourceFlora();
        $this->resourceFauna -= $cost->getResourceFauna();
        $this->resourceEntomofauna -= $cost->getResourceEntomofauna();

        return true;
    }

    #[PrePersist]
    public function onPrePersist(): void
    {
        if ($this->zones->count()) {
            return;
        }

        $zone = new Zone();
        $zone->setUser($this);
        $zone->setName(sprintf('%s starting zone', $this->getEmail()));

        $this->zones->add($zone);

        $this->setResourceFlora(1000);
        $this->setResourceFlora(1000);
    }

    public function incrementResourceEntomofauna(): void
    {
        $this->resourceEntomofauna++;
    }

    public function incrementResourceFauna(): void
    {
        $this->resourceFauna++;
    }

    public function incrementResourceFlora():void
    {
        $this->resourceFlora+=3;
    }
}
