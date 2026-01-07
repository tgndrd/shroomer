<?php

declare(strict_types=1);

namespace App\Constraint;

use App\Entity\PayableInterface;
use App\Entity\User;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\CallbackValidator;

class AffordableValidator extends CallbackValidator
{
    /**
     * @param Security $security
     */
    public function __construct(private readonly Security $security)
    {
    }

    /**
     * @param mixed      $object
     * @param Constraint $constraint
     *
     * @return void
     */
    public function validate(mixed $object, Constraint $constraint): void
    {
        $user = $this->security->getUser();

        if (!$user instanceof User) {
            $this->context->buildViolation('The User is not connected')
                ->atPath(get_class($object))
                ->addViolation();

            return;
        }

        if (!$object instanceof PayableInterface) {
            $this->context->buildViolation(sprintf('The object is not an %s', PayableInterface::class))
                ->atPath(get_class($object))
                ->addViolation();

            return;
        }

        $cost = $object->getCost();

        if (!$user->canAfford($cost)) {
            $this->context->buildViolation('You do not have enough resources!')
                ->atPath(get_class($object))
                ->addViolation();
        }
    }
}
