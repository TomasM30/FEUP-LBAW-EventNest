<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Support\Facades\Log;


use App\Models\Event;

class EventController extends Controller
{

    public function createEvent(Request $request)
    {
        Log::info('Request data', $request->all());
    
        $request->validate([
            'title' => 'required|string',
            'description' => 'required|string',
            'type' => 'in:public,private,approval',
            'date' => 'required|date_format:d-m-Y|after_or_equal:today',
            'capacity' => 'required|integer',
            'ticket_limit' => 'required|integer',
            'place' => 'required|string',
        ]);
    
        Log::info('Request data', $request->all());
    
        $event = Event::create([
            'title' => $request->title,
            'description' => $request->description,
            'type' => $request->input('type', 'public'),
            'date' => Carbon::createFromFormat('d-m-Y', $request->date)->format('Y-m-d'),
            'capacity' => $request->capacity,
            'ticket_limit' => $request->ticket_limit,
            'place' => $request->place,
            'id_user' => Auth::user()->id,
        ]);
    
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
