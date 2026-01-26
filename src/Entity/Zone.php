<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use App\Repository\ZoneRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\OrderBy;
use Doctrine\ORM\Mapping\PrePersist;
use Symfony\Component\Serializer\Annotation\Groups;

#[Entity(repositoryClass: ZoneRepository::class)]
#[ApiResource(normalizationContext: ['groups' => [Zone::class]])]
#[Get(security: "is_granted('zone_get', object)")]
#[GetCollection(
    uriTemplate: 'zones',
    normalizationContext: ['groups' => [self::GROUP_ZONES]],
    security: "is_granted('zone_list', user)"
)]
#[HasLifecycleCallbacks]
class Zone
{
    public const string GROUP_ZONES = 'zones';

    #[Id]
    #[GeneratedValue(strategy: 'SEQUENCE')]
    #[Column(type: Types::INTEGER, nullable: false)]
    #[Groups([Zone::class, self::GROUP_ZONES])]
    private ?int $id = null;

    #[Column(name: 'name', type: Types::STRING, length: 255, nullable: false)]
    #[Groups([Zone::class, self::GROUP_ZONES])]
    private string $name;

    #[ManyToOne(targetEntity: User::class, inversedBy: 'zones')]
    #[JoinColumn(nullable: false)]
    private User $user;

    #[OneToMany(targetEntity: Tree::class, mappedBy: 'zone')]
    #[Groups(Zone::class)]
    #[OrderBy(['id' => 'ASC'])]
    private Collection $trees;

    #[OneToMany(targetEntity: Weather::class, mappedBy: 'zone', cascade: ['persist'])]
    #[OrderBy(['id' => 'DESC'])]
    private Collection $weathers;

    public function __construct()
    {
        $this->trees = new ArrayCollection();
        $this->weathers = new ArrayCollection();
    }

    #[PrePersist]
    public function onPrePersist(): void
    {
        $weather = new Weather();
        $weather->setState(WeatherStateEnum::STATE_SUNNY);
        $weather->setHumidity(0);
        $weather->setMaxTemperature(15);
        $weather->setMinTemperature(15);
        $this->addWeather($weather);
    }

    /** todo: this must be improve to avoid extra db query
     */
    #[Groups(Zone::class)]
    public function getWeather(): Weather
    {
        return $this->weathers->first();
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return void
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return Collection
     */
    public function getTrees(): Collection
    {
        return $this->trees;
    }

    /**
     * @param Tree $tree
     *
     * @return void
     */
    public function addTree(Tree $tree): void
    {
        foreach ($this->trees as $t) {
            if ($t->getId() === $tree->getId()) {
                return;
            }
        }

        $tree->setZone($this);
        $this->trees[] = $tree;
    }

    /**
     * @param Weather $weather
     *
     * @return void
     */
    public function addWeather(Weather $weather): void
    {
        foreach ($this->weathers as $w) {
            if ($w->getId() === $weather->getId()) {
                return;
            }
        }

        $weather->setZone($this);
        $this->weathers[] = $weather;
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @param User $user
     *
     * @return void
     */
    public function setUser(User $user): void
    {
        $this->user = $user;
    }

    /**
     * @return Collection
     */
    public function getWeathers(): Collection
    {
        return $this->weathers;
    }

}
