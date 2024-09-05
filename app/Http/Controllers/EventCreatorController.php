<?php

namespace App\Http\Controllers;

use App\Http\Requests\EventCreateRequest;
use App\Services\EventService;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class EventCreatorController extends Controller
{
    public function __construct(
        protected EventService $eventService
    ) {}

    public function index()
    {
        return Inertia::render('CreateEvent');
    }

    public function store(EventCreateRequest $request)
    {
        $data = $request->validated();
        $data['owner_id'] = Auth::id();

        try {
            $event = $this->eventService->createEvent($data);

            return redirect()->route('event.index', $event->id);
        } catch (\Exception $e) {
            return Inertia::render('CreateEvent', ['errors' => [$e->getCode() => $e->getMessage()]]);
        }
    }
}
