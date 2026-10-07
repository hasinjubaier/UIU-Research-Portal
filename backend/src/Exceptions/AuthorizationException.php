<?php

namespace App\Exceptions;

use Exception;

class AuthorizationException extends Exception
{
    public function __construct(string $message = 'Unauthorized access', int $code = 403)
    {
        parent::__construct($message, $code);
    }
}
