<?php

namespace App\Exceptions;

use Exception;

class EventJoinException extends Exception
{
    protected $message = 'The Event you are looking for does not exist.';

    protected $code = 'error';
}
