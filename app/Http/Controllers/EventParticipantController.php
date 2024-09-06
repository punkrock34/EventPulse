<?php

namespace App\Http\Controllers;

use App\Http\Requests\EventJoinRequest;
use App\Services\EventParticipantService;

class EventParticipantController extends Controller
{
    public function __construct(
        protected EventParticipantService $eventParticipantService
    ) {}

    public function join(EventJoinRequest $request)
    {
        try {
            $eventId = $request->input('event_id');
            $this->eventParticipantService->joinEvent($eventId);

            return response()->json(['message' => 'Successfully joined the event.'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 409);
        }
    }
}
