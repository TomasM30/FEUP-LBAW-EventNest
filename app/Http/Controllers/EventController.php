<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use Illuminate\Support\Facades\DB;
use App\Models\EventsParticipants;

class EventController extends Controller
{

    public function listPublicEvents()
    {   
        // if(!Auth::check()){
        //     return redirect('login');
        //  }
        // $this->authorize('list', Group::class);

        $event = new Event();
        
        $publicEvents = $event->publicEvents();

        return response()->json($publicEvents);
    }


    public function listEventAttendees($id) 
    {
        $event = Event::find($id);
        $attendees = $event->attendees()->get();
        return response()->json($attendees);
    }


    public function addUserToEvent(Request $request)
    {

        //TODO: Verify user is auth

        $id_user = $request->id_user;
        $eventId = $request->eventId;
        $authenticated = Authenticated::find($id_user)->get();
        $event = Event::find($eventId)->get();
        
        DB::BeginTransaction();

        EventParticipants::Insert([
            'id_user' => $authenticated->id_user,
            'id_event' => $event->id,
        ]);

        //TODO: Generates notification

        DB::commit();
    }


    public function createPublicEvent(Request $request)
    {
        //TODO: verify user is auth

        DB::BeginTransaction();

        Event::Insert([
            'title' => $request->title,
            'description'=> $request->description,
            'type'=> 'public',
            'date'=> $request->date,
            'capacity'=> $request->capacity,
            'ticket_limit'=> $request->ticket_limit,
            'place'=> $request->place, 
            'id_user',
        ]);

        DB::commit();

    }

    public function details($id)
    {
        $event = Event::find($id)->get();
        $details = $event->only(['title','desciption','type','date','place']);

        return response()->json($details);
    }
    
}
