<?php

declare(strict_types=1);

namespace App\Condition;

class DeltaTemperature extends AbstractCondition
{
    private int $minimumDelta;

    /**
     * @param int $minimumDelta Any minimum temperature of the iteration bellow this value will be refused
     */
    public function __construct(int $minimumDelta)
    {
        $this->minimumDelta = $minimumDelta;
    }

    /**
     * @return int
     */
    public function getMinimumDelta(): int
    {
        return $this->minimumDelta;
    }
}
