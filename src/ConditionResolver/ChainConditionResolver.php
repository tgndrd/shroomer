<?php

declare(strict_types=1);

namespace App\ConditionResolver;

use App\Condition\AbstractCondition;
use RuntimeException;

class ChainConditionResolver
{
    private iterable $conditionResolvers;

    /**
     * @param iterable $conditionResolvers
     */
    public function __construct(iterable $conditionResolvers)
    {
        $this->conditionResolvers = $conditionResolvers;
    }

    /**
     * @param AbstractCondition[] $conditions
     * @param array $context
     *
     * @return bool
     */
    public function resolveConditions(array $conditions, array $context = []): bool
    {
        foreach ($conditions as $condition) {
            if (!$this->resolve($condition, $context)) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param AbstractCondition $abstractCondition
     * @param array             $context
     *
     * @return bool
     */
    public function resolve(AbstractCondition $abstractCondition, array $context = []): bool
    {
        foreach ($this->conditionResolvers as $conditionResolver) {
            if (!$conditionResolver instanceof ConditionResolverInterface) {
                throw new RuntimeException(
                    sprintf(
                        '%s expects a collection of %s',
                        self::class,
                        ConditionResolverInterface::class
                    )
                );
            }

            if ($conditionResolver->supports($abstractCondition)) {
                return $conditionResolver->resolve($abstractCondition, $context);
            }
        }

        throw new RuntimeException(
            sprintf(
                'no %s found to support %s',
                ConditionResolverInterface::class,
                $abstractCondition::class
            )
        );
    }
}
