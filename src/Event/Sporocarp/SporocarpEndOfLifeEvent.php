<?php

declare(strict_types=1);

namespace App\Event\Sporocarp;

use App\Entity\Sporocarp;

class SporocarpEndOfLifeEvent
{
    private Sporocarp $sporocarp;

    /**
     * @param Sporocarp $sporocarp
     */
    public function __construct(Sporocarp $sporocarp)
    {
        $this->sporocarp = $sporocarp;
    }

    /**
     * @return Sporocarp
     */
    public function getSporocarp(): Sporocarp
    {
        return $this->sporocarp;
    }
}
