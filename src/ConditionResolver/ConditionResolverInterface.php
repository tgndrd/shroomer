<?php

declare(strict_types=1);

namespace App\ConditionResolver;

use App\Condition\AbstractCondition;

interface ConditionResolverInterface
{
    /**
     * @param AbstractCondition $abstractCondition
     *
     * @return bool
     */
    public function supports(AbstractCondition $abstractCondition): bool;

    /**
     * @param AbstractCondition $abstractCondition
     * @param array             $context
     *
     * @return bool
     */
    public function resolve(AbstractCondition $abstractCondition, array $context = []): bool;
}
