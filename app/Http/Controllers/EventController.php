<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Event;

class EventController extends Controller
{
    /**
     * Creates a new item.
     */
    protected function create(Request $request)
    {
        $this->authorize('create', Event::class);

        $event = new Event();
        $event->title = $request->title;
        $event->description = $request->description;
        $event->type = $request->input('type', 'public');
        $event->date = date('Y-m-d H:i');
        $event->capacity = $request->capacity;
        $event->ticket_limit = $request->ticket_limit;
        $event->place = $request->place;
        $event->User_id = Auth::user()->id;
        $event->save();

        return redirect()->back()->with('success', 'Event successfully created');
    }

}
