<?php

namespace App\Exceptions;

use Exception;

class EventNotFoundException extends Exception
{
    protected $message = 'The Event you are looking for does not exist.';

    protected $code = 'error';
}
