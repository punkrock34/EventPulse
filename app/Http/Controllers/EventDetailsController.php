<?php

namespace App\Http\Controllers;

use App\Http\Requests\EventUpdateRequest;
use App\Services\EventService;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class EventDetailsController extends Controller
{
    public function __construct(
        protected EventService $eventService
    ) {}

    public function index()
    {
        $event = $this->eventService->getEvent(request()->route('id'));

        return Inertia::render('EventDetails', [
            'event' => $event->toArray(),
            'isOwner' => $event->owner_id === Auth::id(),
        ]);
    }

    public function update(EventUpdateRequest $request)
    {
        $event = $this->eventService->getEvent($request->route('id'));
        $data = $request->validated();

        try {
            $this->eventService->updateEvent($event, $data);

            return Inertia::render('EventDetails', [
                'event' => $event->toArray(),
                'success' => 'Event updated successfully',
            ]);
        } catch (\Exception $e) {
            return Inertia::render('EventDetails', [
                'event' => $event->toArray(),
                'errors' => [$e->getCode() => $e->getMessage()],
            ]);
        }
    }
}
