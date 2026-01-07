<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\MyceliumRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;
use Symfony\Component\Serializer\Annotation\Groups;

#[Entity(repositoryClass: MyceliumRepository::class)]
class Mycelium
{
    #[Id]
    #[GeneratedValue(strategy: 'SEQUENCE')]
    #[Column(type: Types::INTEGER, nullable: false)]
    private ?int $id = null;

    #[Column(name: "genus", type: Types::STRING, nullable: false, enumType: MyceliumGenusEnum::class)]
    private MyceliumGenusEnum $genus;

    #[ManyToOne(targetEntity: Tree::class, inversedBy: "myceliums")]
    #[JoinColumn(nullable: false)]
    private Tree $tree;

    #[OneToMany(targetEntity: Sporocarp::class, mappedBy: 'mycelium')]
    #[Groups([Zone::class])]
    private Collection $sporocarps;

    public function __construct()
    {
        $this->sporocarps = new ArrayCollection();
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
     * @return Collection
     */
    public function getSporocarps(): Collection
    {
        return $this->sporocarps;
    }

    /**
     * @param Sporocarp $sporocarp
     *
     * @return void
     */
    public function addSporocarp(Sporocarp $sporocarp): void
    {
        foreach ($this->sporocarps as $carp) {
            if ($carp->getId() === $sporocarp->getId()) {
                return;
            }
        }

        $this->sporocarps[] = $sporocarp;
    }

    /**
     * @return MyceliumGenusEnum
     */
    public function getGenus(): MyceliumGenusEnum
    {
        return $this->genus;
    }

    /**
     * @param MyceliumGenusEnum $genus
     *
     * @return void
     */
    public function setGenus(MyceliumGenusEnum $genus): void
    {
        $this->genus = $genus;
    }

    /**
     * @return Tree
     */
    public function getTree(): Tree
    {
        return $this->tree;
    }

    /**
     * @param Tree $tree
     *
     * @return void
     */
    public function setTree(Tree $tree): void
    {
        $this->tree = $tree;
    }
}
