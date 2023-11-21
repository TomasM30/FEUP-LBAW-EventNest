<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\Event;
use App\Models\EventParticipant;
// use App\Models\EventMessage;
// use App\Models\EventNotification;
// use App\Models\FavoriteEvent;
// use App\Models\EventHashtag;
// use App\Models\TicketType;
// use App\Models\Report;
// use App\Models\File;
// use App\Models\Poll;

class EventController extends Controller
{
    public function createEvent(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'description' => 'required|string',
            'type' => 'in:public,private,approval',
            'date' => 'required|date_format:d-m-Y|after_or_equal:today',
            'capacity' => 'required|integer',
            'ticket_limit' => 'required|integer',
            'place' => 'required|string',
        ]);
    
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

    public function deleteEvent($id)
    {
        DB::beginTransaction();

        try {

            // Retrieve the event
            $event = Event::findOrFail($id);

            // Delete the related records
            $event->eventparticipants()->delete();
            // $event->eventmessage()->delete();
            // $event->eventnotification()->delete();
            // $event->favoriteevent()->delete();
            // $event->eventhashtags()->delete();
            // $event->tickettype()->delete();
            // $event->report()->delete();
            // $event->file()->delete();
            // $event->poll()->delete();

            // Delete the event
            $event->delete();

            // Commit the transaction
            DB::commit();

            return redirect()->back()->with('message', 'Event deleted successfully');
        } catch (\Exception $e) {
            // An error occurred; cancel the transaction...
            DB::rollback();

            // and return an error message
            return redirect()->back()->with('message', 'Event deletion failed');
        }
    }

    public function listPublicEvents()
    {   
        $events = Event::where('type', 'public')->get();
        return view('pages.events', ['events' => $events]);
    }


    public function listEventAttendees($id) 
    {
        $event = Event::find($id);
        $attendees = $event->eventparticipants()->get();
        return view('pages.event_details', ['events' => $attendees]);
    }


    /*public function addUserToEvent(Request $request)
    {
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
    }*/

    /**
     * @throws AuthorizationException
     * @throws ValidationException
     */
    public function editEvent (Request $request)
    {
        $this->validate($request, [
            'title' => 'required|string',
            'description' => 'required|string',
            'type' => 'in:public,private',
            'capacity' => 'required|integer',
            'ticket_limit' => 'required|integer',
        ]);

        $event = Event::find(1);

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
