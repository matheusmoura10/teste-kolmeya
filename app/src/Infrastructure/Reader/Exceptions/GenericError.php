<?php

namespace App\src\Infrastructure\Reader\Exceptions;

use Exception;

final class GenericError extends Exception
{
    public function __construct(string $message = 'Generic error', int $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
