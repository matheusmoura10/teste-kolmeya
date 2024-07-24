<?php

namespace App\src\Infrastructure\Reader\Exceptions;

use Exception;

final class NotSupportedException extends Exception
{
    public function __construct(string $message = 'Not supported', int $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
