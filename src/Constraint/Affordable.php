<?php

declare(strict_types=1);

namespace App\Constraint;

use Attribute;
use Symfony\Component\Validator\Constraint;

#[Attribute]
class Affordable extends Constraint
{
    /**
     * @param mixed|null $options
     * @param array|null $groups
     * @param mixed|null $payload
     */
    public function __construct(mixed $options = null, ?array $groups = null, mixed $payload = null)
    {
        parent::__construct($options, $groups, $payload);
    }

    /**
     * @return string
     */
    public function validatedBy(): string
    {
        return AffordableValidator::class;
    }

    /**
     * @return string|array|string[]
     */
    public function getTargets(): string|array
    {
        return self::CLASS_CONSTRAINT;
    }
}
