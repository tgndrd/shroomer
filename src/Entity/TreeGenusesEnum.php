<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use App\Model\Cost;
use Symfony\Component\Serializer\Annotation\Groups;

#[
    ApiResource(normalizationContext: ['groups' => ['read']], mercure: false),
    GetCollection
]
enum TreeGenusesEnum: string implements PayableInterface
{
    case GENUS_PINUS = 'Spruce';
    case GENUS_QUERCUS = 'Quercus';
    case GENUS_CASTANEA = 'Chestnut';
    case GENUS_FRAXINUS = 'Fraxinus';

    #[Groups('read')]
    public function getName(): string
    {
        return $this->value;
    }

    #[Groups(['read'])]
    public function getCost(): Cost
    {
        return match($this) {
            self::GENUS_PINUS => new Cost(resourceFlora: 250,resourceFauna: 0,resourceEntomofauna: 0),
            self::GENUS_CASTANEA => new Cost(resourceFlora: 300,resourceFauna: 0,resourceEntomofauna: 0),
            self::GENUS_QUERCUS => new Cost(resourceFlora: 350,resourceFauna: 0,resourceEntomofauna: 0),
            self::GENUS_FRAXINUS => new Cost(resourceFlora: 750,resourceFauna: 0,resourceEntomofauna: 0),
        };
    }

    public function getLetter(): string
    {
        return match($this) {
            self::GENUS_PINUS => 's',
            self::GENUS_CASTANEA => 'c',
            self::GENUS_QUERCUS => 'q',
            self::GENUS_FRAXINUS => 'f',
        };
    }

    /**
     * it returns available mycelium type by tree genuses
     *
     * @param TreeGenusesEnum $genus
     *
     * @return array
     */
    public static function getMyceliums(TreeGenusesEnum $genus): array
    {
        return match ($genus) {
            self::GENUS_PINUS => [
                MyceliumGenusEnum::GENUS_XEROCOMUS,
                MyceliumGenusEnum::GENUS_CANTHARELLUS,
            ],
            self::GENUS_CASTANEA => [
                MyceliumGenusEnum::GENUS_BOLETUS,
                MyceliumGenusEnum::GENUS_XEROCOMUS
            ],
            self::GENUS_QUERCUS => [
                MyceliumGenusEnum::GENUS_AMANITA,
                MyceliumGenusEnum::GENUS_CANTHARELLUS,
            ],
            self::GENUS_FRAXINUS => [
                MyceliumGenusEnum::GENUS_MORCHELLA,
                MyceliumGenusEnum::GENUS_AMANITA,
                MyceliumGenusEnum::GENUS_BOLETUS,
                MyceliumGenusEnum::GENUS_CANTHARELLUS,
                MyceliumGenusEnum::GENUS_XEROCOMUS,
            ],
        };
    }
}
