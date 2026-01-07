<?php

declare(strict_types=1);

namespace App\ConditionResolver;

use App\Exception\InvalidContextException;

abstract class AbstractConditionResolver implements ConditionResolverInterface
{
    /**
     * @param array  $context
     * @param string $key
     * @param string $fqn
     *
     * @return object
     * @throws InvalidContextException
     */
    protected function getContextKey(array $context, string $key, string $fqn): object
    {
        if (!array_key_exists($key, $context)) {
            throw new InvalidContextException($key, $fqn);
        }

        $contextItem = $context[$key];

        if (!$contextItem instanceof $fqn) {
            throw new InvalidContextException($key, $fqn);
        }

        return $contextItem;
    }
}
