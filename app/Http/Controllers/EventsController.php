<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class EventsController extends Controller
{
    public function __construct()
    {
    }

    public function index()
    {
        return Inertia::render('EventsListing');
    }

    public function list()
    {
        $user = Auth::user();

        $events = Event::where('owner_id', '!=', $user->id)
            ->whereDoesntHave('participants', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->get();

        return response()->json($events->toArray());
    }
}
