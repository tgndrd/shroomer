<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Post;
use App\Constraint\Affordable;
use App\Model\Cost;
use App\Repository\TreeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\PrePersist;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Attribute\SerializedName;

#[Entity(repositoryClass: TreeRepository::class)]
#[ApiResource(operations: [new Post(
    denormalizationContext: ['groups' => Tree::GROUP_WRITE_TREE],
    security: "is_granted('tree_add', user)",
    name: Tree::POST_TREE_ROUTE
)])]
#[Affordable]
#[HasLifecycleCallbacks]
class Tree implements DatableInterface, PayableInterface
{
    public const string POST_TREE_ROUTE = 'app_post_tree';
    public const string GROUP_WRITE_TREE = 'write_tree';

    use DatableTrait;

    public const array MYCELIUM_SLOT_PER_AGES = [
        0 => 0,
        15 => 1,
        50 => 2,
        200 => 3,
        400 => 4,
    ];

    #[Id]
    #[GeneratedValue(strategy: 'SEQUENCE')]
    #[Column(type: Types::INTEGER, nullable: false)]
    #[Groups(Zone::class)]
    private ?int $id = null;

    #[Column(name: "genus", type: Types::STRING, nullable: false, enumType: TreeGenusesEnum::class)]
    #[Groups([Zone::class, Tree::GROUP_WRITE_TREE])]
    private TreeGenusesEnum $genus;

    #[ManyToOne(targetEntity: Zone::class, inversedBy: "trees")]
    #[Groups(Tree::GROUP_WRITE_TREE)]
    #[JoinColumn(nullable: false)]
    private Zone $zone;

    #[OneToMany(targetEntity: Mycelium::class, mappedBy: "tree")]
    private Collection $myceliums;

    public function __construct()
    {
        $this->myceliums = new ArrayCollection();
    }

    /**
     * It returns the number of mycelium slot according to the age of the tree
     *
     * @return int
     */
    #[Groups([Zone::class])]
    #[SerializedName('slot')]
    public function getMyceliumSlot(): int
    {
        $keys = array_reverse(array_keys(self::MYCELIUM_SLOT_PER_AGES));

        foreach ($keys as $key) {
            if ($key <= $this->getAge()) {
                return self::MYCELIUM_SLOT_PER_AGES[$key];
            }
        }

        return 0;
    }

    private function getSporocarpSlot(int $slot): ?Sporocarp
    {
        $mycelium = $this->myceliums->get($slot);

        if (!$mycelium instanceof Mycelium) {
            return null;
        }

        $sporocarp = $mycelium->getSporocarps()->first();

        if (!$sporocarp instanceof Sporocarp) {
            return null;
        }

        return $sporocarp;
    }

    #[Groups([Zone::class])]
    #[SerializedName('slot_1')]
    public function getFirstPossibleSporocarp(): ?Sporocarp
    {
        return $this->getSporocarpSlot(0);
    }

    #[Groups([Zone::class])]
    #[SerializedName('slot_2')]
    public function getSecondPossibleSporocarp(): ?Sporocarp
    {
        return $this->getSporocarpSlot(1);

    }

    #[Groups([Zone::class])]
    #[SerializedName('slot_3')]
    public function getThirdPossibleSporocarp(): ?Sporocarp
    {
        return $this->getSporocarpSlot(2);
    }

    #[Groups([Zone::class])]
    #[SerializedName('slot_4')]
    public function getFourthPossibleSporocarp(): ?Sporocarp
    {
        return $this->getSporocarpSlot(3);
    }

    #[Groups([Zone::class])]
    #[SerializedName('letter')]
    public function getLetter(): string
    {
        return $this->genus->getLetter();
    }
    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int|null $id
     *
     * @return void
     */
    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return TreeGenusesEnum
     */
    public function getGenus(): TreeGenusesEnum
    {
        return $this->genus;
    }

    /**
     * @param TreeGenusesEnum $genus
     *
     * @return void
     */
    public function setGenus(TreeGenusesEnum $genus): void
    {
        $this->genus = $genus;
    }

    /**
     * @return Zone
     */
    public function getZone(): Zone
    {
        return $this->zone;
    }

    /**
     * @param Zone $zone
     *
     * @return void
     */
    public function setZone(Zone $zone): void
    {
        $this->zone = $zone;
    }

    /**
     * @return Collection
     */
    public function getMyceliums(): Collection
    {
        return $this->myceliums;
    }

    /**
     * @param Mycelium $mycelium
     *
     * @return void
     */
    public function addMycelium(Mycelium $mycelium): void
    {
        foreach ($this->myceliums as $mycel) {
            if ($mycel->getId() == $mycelium->getId()) {
                return;
            }
        }

        $this->myceliums[] = $mycelium;
    }

    /**
     * @return Cost
     */
    public function getCost(): Cost
    {
        return $this->genus->getCost();
    }

    #[PrePersist]
    function onPrePersist(): void
    {
        $this->getZone()->getUser()->afford($this->getGenus()->getCost());
    }
}
