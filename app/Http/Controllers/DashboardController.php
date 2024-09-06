<?php

namespace App\Http\Controllers;

use App\Services\EventService;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function __construct(
        protected EventService $eventService
    ) {}

    public function index()
    {
        return Inertia::render('Dashboard', [
            'events' => $this->eventService->getUserEvents(),
            'joinedEvents' => $this->eventService->getUserJoinedEvents(),
        ]);
    }
}
