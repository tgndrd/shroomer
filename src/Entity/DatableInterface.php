<?php

declare(strict_types=1);

namespace App\Entity;

interface DatableInterface
{
    /**
     * @return int
     */
    public function getAge(): int;

    /**
     * @param int $age
     *
     * @return void
     */
    public function setAge(int $age): void;

    /**
     * @return int
     */
    public function toNextAge(): int;

    /**
     * @param int $age
     *
     * @return bool
     */
    public function isYoungerThan(int $age): bool;
}
