<?php

declare(strict_types=1);

namespace App\Condition;

class OlderThan extends AbstractCondition
{
    private int $age;

    /**
     * The resolver MUST receive an object to be compared. The object MUST implements a DatableInterface
     *
     * @param int $age age must be higher than this value
     */
    public function __construct(int $age)
    {
        $this->age = $age;
    }

    /**
     * @return int
     */
    public function getAge(): int
    {
        return $this->age;
    }
}
