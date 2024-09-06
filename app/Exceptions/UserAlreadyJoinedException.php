<?php

namespace App\Exceptions;

use Exception;

class UserAlreadyJoinedException extends Exception
{
    protected $message = 'You have already joined this event.';

    protected $code = 'error';
}
