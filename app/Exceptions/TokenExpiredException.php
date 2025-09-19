<?php

namespace App\Exceptions;

use Exception;

class TokenExpiredException extends Exception
{
    protected $message = 'Token has expired';
    protected $code = 401;
}