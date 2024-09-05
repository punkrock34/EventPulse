<?php

namespace App\Constants;

enum DatabaseTables: string
{
    case USERS = 'users';
    case EVENTS = 'events';
    case EVENT_PARTICIPANTS = 'event_participants';
    case ATTACHMENTS = 'attachments';
}
