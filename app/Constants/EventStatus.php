<?php

namespace App\Constants;

enum EventStatus: string
{
    case UPCOMING = 'UPCOMING';
    case ONGOING = 'ONGOING';
    case COMPLETED = 'COMPLETED';
    case CANCELED = 'CANCELED';
    case POSTPONED = 'POSTPONED';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
