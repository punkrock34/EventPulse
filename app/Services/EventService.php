<?php

namespace App\Services;

use App\Exceptions\EventNotFoundException;
use App\Exceptions\UnexpectedErrorException;
use App\Models\Event;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class EventService
{
    public function getEvent(int $id): Event
    {
        try {
            return Event::with('attachments')->findOrFail($id);
        } catch (ModelNotFoundException $e) {
            Log::error("Event not found: {$e->getMessage()}");
            throw new EventNotFoundException;
        } catch (\Exception $e) {
            Log::error("Error fetching event: {$e->getMessage()}");
            throw new UnexpectedErrorException;
        }
    }

    public function getUserEvents(): array
    {
        try {
            return Auth::user()->events->toArray();
        } catch (\Exception $e) {
            Log::error("Error fetching user events: {$e->getMessage()}");
            throw new UnexpectedErrorException;
        }
    }

    public function createEvent(array $data): Event
    {
        try {
            return Event::create($data);
        } catch (\Exception $e) {
            Log::error("Error creating event: {$e->getMessage()}");
            throw new UnexpectedErrorException;
        }
    }

    public function updateEvent(Event $event, array $data): Event
    {
        try {
            $event->update($data);

            return $event;
        } catch (\Exception $e) {
            Log::error("Error updating event: {$e->getMessage()}");
            throw new UnexpectedErrorException;
        }
    }

    public function deleteEvent(Event $event): void
    {
        try {
            $event->delete();
        } catch (\Exception $e) {
            Log::error("Error deleting event: {$e->getMessage()}");
            throw new UnexpectedErrorException;
        }
    }
}
