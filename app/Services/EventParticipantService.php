<?php

namespace App\Services;

use App\Exceptions\EventOwnerCannotJoinException;
use App\Exceptions\UnexpectedErrorException;
use App\Exceptions\UserAlreadyJoinedException;
use App\Models\Event;
use App\Models\EventParticipant;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class EventParticipantService
{
    /**
     * Add a participant to an event.
     *
     * @throws EventOwnerCannotJoinException
     * @throws UserAlreadyJoinedException
     */
    public function joinEvent(int $eventId)
    {
        try {
            $user = Auth::user();

            $event = Event::findOrFail($eventId);
            if ($event->owner_id === $user->id) {
                throw new EventOwnerCannotJoinException;
            }

            // Check if the user is already a participant
            if (EventParticipant::where('event_id', $eventId)->where('user_id', $user->id)->exists()) {
                throw new UserAlreadyJoinedException;
            }

            EventParticipant::create([
                'event_id' => $eventId,
                'user_id' => $user->id,
            ]);
        } catch (EventOwnerCannotJoinException|UserAlreadyJoinedException $e) {
            throw $e;
        } catch (\Exception $e) {
            Log::error("Error joining event: {$e->getMessage()}");
            throw new UnexpectedErrorException;
        }
    }
}
