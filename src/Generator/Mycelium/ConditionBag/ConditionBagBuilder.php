<?php

declare(strict_types=1);

namespace App\Generator\Mycelium\ConditionBag;

use App\Condition\AbstractCondition;
use App\Entity\MyceliumGenusEnum;
use RuntimeException;

class ConditionBagBuilder
{
    private iterable $conditionBagBuilders;

    public function __construct(iterable $conditionBagBuilders)
    {
        $this->conditionBagBuilders = $conditionBagBuilders;
    }

    /**
     * @param MyceliumGenusEnum $genus
     *
     * @return AbstractCondition[]
     */
    public function build(MyceliumGenusEnum $genus): array
    {
        foreach ($this->conditionBagBuilders as $conditionBagBuilder) {
            if (!$conditionBagBuilder instanceof ConditionBagBuilderInterface) {
                throw new RuntimeException(
                    sprintf(
                        '%s expects a collection of %s',
                        self::class,
                        ConditionBagBuilderInterface::class
                    )
                );
            }

            if ($conditionBagBuilder->supports($genus)) {
                return $conditionBagBuilder->builds();
            }
        }

        throw new RuntimeException(
            sprintf(
                'no %s found to support %s',
                ConditionBagBuilderInterface::class,
                $genus->value
            )
        );
    }
}
