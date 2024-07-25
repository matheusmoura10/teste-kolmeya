<?php

namespace App\src\Infrastructure\Reader\Exceptions;

use Exception;

 class NotFoundException extends Exception
{
    public function __construct(string $message = 'Not found', int $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
