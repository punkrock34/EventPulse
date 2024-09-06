<?php

namespace App\Exceptions;

use Exception;

class EventOwnerCannotJoinException extends Exception
{
    protected $message = 'You are already listed as the organizer of this event and cannot join as a participant.';

    protected $code = 'error';
}
