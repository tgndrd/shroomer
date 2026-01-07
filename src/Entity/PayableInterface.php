<?php

declare(strict_types=1);

namespace App\Entity;

use App\Model\Cost;

interface PayableInterface
{
    public function getCost(): Cost;
}
