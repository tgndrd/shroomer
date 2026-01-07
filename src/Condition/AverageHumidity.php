<?php

declare(strict_types=1);

namespace App\Condition;

use RuntimeException;

class AverageHumidity extends AbstractCondition
{
    private int $humidity;
    private int $duration;

    /**
     * @param int $humidity the average humidity upon last X iteration must be upper than this
     * @param int $duration the average must be calculated upon this duration
     */
    public function __construct(int $humidity, int $duration = 7)
    {
        if ($duration === 0) {
            throw new RuntimeException('Duration cannot be zero');
        }

        $this->humidity = $humidity;
        $this->duration = $duration;
    }

    /**
     * @return int
     */
    public function getHumidity(): int
    {
        return $this->humidity;
    }

    /**
     * @return int
     */
    public function getDuration(): int
    {
        return $this->duration;
    }
}
