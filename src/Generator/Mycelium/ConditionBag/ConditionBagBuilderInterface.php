<?php

declare(strict_types=1);

namespace App\Generator\Mycelium\ConditionBag;

use App\Entity\MyceliumGenusEnum;

interface ConditionBagBuilderInterface
{
    /**
     * The methods return true when its support a given mycelium genus.
     *
     * @param MyceliumGenusEnum $genus
     *
     * @return bool
     */
    public function supports(MyceliumGenusEnum $genus): bool;

    /**
     * The method builds a condition bag.
     *
     * @return array
     */
    public function builds(): array;
}
