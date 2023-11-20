<?php

namespace App\Http\Controllers;

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
            return response()->json(['message' => 'An error occurred while deleting the event']);
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

    
}
