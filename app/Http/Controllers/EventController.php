<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

use App\Models\Event;
use Illuminate\Validation\ValidationException;

class EventController extends Controller
{
    /**
     * Creates a new item.
     * @throws AuthorizationException
     * @throws ValidationException
     */
    public function createEvent(Request $request): \Illuminate\Http\RedirectResponse
    {
        $this->authorize('create', Event::class);

        $this->validate($request, [
            'title' => 'required|string',
            'description' => 'required|string',
            'type' => 'in:public,private,approval',
            'date' => 'required|date_format:d-m-Y|after_or_equal:today',
            'capacity' => 'required|integer',
            'ticket_limit' => 'required|integer',
            'place' => 'required|string',
        ]);

        $event = new Event();
        $event->title = $request->title;
        $event->description = $request->description;
        $event->type = $request->input('type', 'public');
        $eventDate = Carbon::createFromFormat('d-m-y', $request->date);
        $event->date = $eventDate->format('d-m-Y');
        $event->capacity = $request->capacity;
        $event->ticket_limit = $request->ticket_limit;
        $event->place = $request->place;
        $event->User_id = Auth::user()->id;
        $event->save();

        return redirect()->back()->with('success', 'Event successfully created');
    }

    /**
     * @throws AuthorizationException
     * @throws ValidationException
     */
    public function editEvent (Request $request): \Illuminate\Http\RedirectResponse
    {
        $this->authorize('edit', Event::class);

        $this->validate($request, [
            'title' => 'required|string',
            'description' => 'required|string',
            'type' => 'in:public,private',
            'capacity' => 'required|integer',
            'ticket_limit' => 'required|integer',
        ]);

        $event = Event::find($request->input('id'));

        if (!$event) {
            return redirect()->back()->with('error', 'Event not found');
        }

        $event->title = $request->title;
        $event->description = $request->description;
        $event->type = $request->input('type', 'public');
        $event->capacity = $request->capacity;
        $event->ticket_limit = $request->ticket_limit;
        $event->save();

        return redirect()->back()->with('success', 'Event successfully updated');
    }
}
