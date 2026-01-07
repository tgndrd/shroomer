<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Column;
use Symfony\Component\Serializer\Annotation\Groups;

trait DatableTrait
{
    // age in day / iteration
    #[Column(name: 'age', type: Types::INTEGER, nullable: false)]
    #[Groups(Zone::class)]
    private int $age = 0;

    /**
     * @return int
     */
    public function getAge(): int
    {
        return $this->age;
    }

    /**
     * @param int $age
     *
     * @return void
     */
    public function setAge(int $age): void
    {
        $this->age = $age;
    }

    /**
     * @return int
     */
    public function toNextAge(): int
    {
        return ++$this->age;
    }

    /**
     * @param int $age
     *
     * @return bool
     */
    public function isYoungerThan(int $age): bool
    {
        return $age > $this->getAge();
    }
}
