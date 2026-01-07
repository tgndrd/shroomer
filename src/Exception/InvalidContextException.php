<?php

declare(strict_types=1);

namespace App\Exception;

use Exception;

class InvalidContextException extends Exception
{
    /**
     * @param string $contextKey
     * @param string $contextFqn
     */
    public function __construct(string $contextKey, string $contextFqn)
    {
        $message = sprintf('it expect an %s in context at key %s', $contextFqn, $contextKey);
        parent::__construct($message);
    }
}
