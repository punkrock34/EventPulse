<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class EventsController extends Controller
{
    public function __construct(
    ) {}

    public function index()
    {
        return Inertia::render('EventsListing');
    }

    public function list()
    {
        return response()->json(Event::where('owner_id', '!=', Auth::id())->get()->toArray());
    }
}
