<?php

declare(strict_types=1);

namespace App\ConditionResolver;

use App\Condition\AbstractCondition;
use App\Condition\OlderThan;
use App\Entity\DatableInterface;
use App\Exception\InvalidContextException;

final class OlderThanResolver extends AbstractConditionResolver
{
    /**
     * @param AbstractCondition $abstractCondition
     *
     * @return bool
     */
    public function supports(AbstractCondition $abstractCondition): bool
    {
        return $abstractCondition instanceof OlderThan;
    }

    /**
     * @param OlderThan $abstractCondition
     * @param array     $context
     *
     * @return     bool
     * @throws     InvalidContextException
     * @inheritDoc
     */
    public function resolve(AbstractCondition $abstractCondition, array $context = []): bool
    {
        $datable = $this->getContextKey($context, 'datable', DatableInterface::class);

        return $datable->getAge() > $abstractCondition->getAge();
    }
}
