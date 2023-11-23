<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;


use App\Models\Event;
use App\Models\User;
use App\Models\Admin;

use App\Models\EventParticipant;
use App\Models\AuthenticatedUser;


// use App\Models\EventMessage;
// use App\Models\EventNotification;
use App\Models\FavoriteEvent;
// use App\Models\EventHashtag;
// use App\Models\TicketType;
// use App\Models\Report;
// use App\Models\File;
// use App\Models\Poll;

class EventController extends Controller
{
    public function createEvent(Request $request)
    {

        $this->authorize('create', Event::class);

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
        $event = Event::find($id);
        if (!$event) {
            return redirect()->back()->with('message', 'Event not found');
        }

        $this->authorize('delete', $event);

        DB::beginTransaction();

        try {

            // Retrieve the event
            $event = Event::findOrFail($id);

            // Delete the related records
            $event->eventparticipants()->delete();
            // $event->eventmessage()->delete();
            // $event->eventnotification()->delete();
            $event->favoriteevent()->delete();
            // $event->eventhashtags()->delete();
            // $event->tickettype()->delete();
            // $event->report()->delete();
            // $event->file()->delete();
            // $event->poll()->delete();

            // Delete the event
            $event->delete();

            // Commit the transaction
            DB::commit();

            return redirect()->route('events')->with('message', 'Event deletion successful');
        } catch (\Exception $e) {
            DB::rollback();

            Log::error('Event deletion failed: ' . $e->getMessage());

            // and return an error message
            return redirect()->back()->with('message', 'Event deletion failed');
        }
    }

    public function listPublicEvents()
    {   
        $user = Auth::user();

        $events = Event::where('type', 'public')->where('id_user', '!=', $user->id)->get();

        return view('pages.events', ['events' => $events,
                                    'user'=> $user]);
    }


    public function showEventDetails($id) 
    {
        $data = [];
        $data['event'] = Event::find($id);
        $data['isParticipant'] = $this->joinedEvent(Auth::user(), $data['event']);
        $data['isAdmin'] = Admin::where('id_user', Auth::user()->id)->first();
        $data['isOrganizer'] = $data['event']->id_user == Auth::user()->id;

        return view('pages.event_details', $data);
    }

    public function joinedEvent($user, $event){
        
        return EventParticipant::where('id_user', $user->id)
                                ->where('id_event', $event->id)
                                ->exists();
    }


    public function addUser(Request $request)
    {
        $event = Event::find($request->eventId);
        if (!$event) {
            return redirect()->back()->with('message', 'Event not found');
        }

        $this->authorize('addUser', $event);
            
        try {
            if (!(AuthenticatedUser::where('id_user', $request->id_user)->exists())|| !(Event::where('id', $request->eventId)->exists())) 
                return redirect()->back()->with('message', 'User or event not found');
            
            DB::BeginTransaction();


            EventParticipant::insert([
            'id_user' => $request->id_user,
            'id_event' => $request->eventId,
            ]);


            DB::commit();

            } catch (\Exception $e) {
       
                DB::rollback();
                Log::error('User jailed to join event: ' . $e->getMessage()); 
                return redirect()->back()->with('message', 'User jailed to join event!');
            }    
        return redirect()->back()->with('message', 'Added user successfully');
    }

    public function removeUser(Request $request)
    {
        $event = Event::find($request->eventId);
        if (!$event) {
            return redirect()->back()->with('message', 'Event not found');
        }
        $this->authorize('removeUser', $event);
            
        try {
            if (!(AuthenticatedUser::where('id_user', $request->id_user)->exists())|| !(Event::where('id', $request->eventId)->exists())) 
                return redirect()->back()->with('message', 'User or event not found');
            
            DB::BeginTransaction();

            EventParticipant::where('id_user', $request->id_user)
                            ->where('id_event', $request->eventId)
                            ->delete();
            DB::commit();

            } catch (\Exception $e) {
       
                DB::rollback();
                Log::error('User jailed to leave event: ' . $e->getMessage()); 
                return redirect()->back()->with('message', 'User jailed to leave event!');
            }
        return redirect()->back()->with('message', 'Removed user successfully');
    }

    public function joinEvent(Request $request)
    {

        $event = Event::find($request->eventId);
        if (!$event) {
            return redirect()->back()->with('message', 'Event not found');
        }
    
        $this->authorize('joinEvent', $event);
            
        try {
            if (!(AuthenticatedUser::where('id_user', $request->id_user)->exists())|| !(Event::where('id', $request->eventId)->exists())) 
                return redirect()->back()->with('message', 'User or event not found');
            
            DB::BeginTransaction();

            EventParticipant::insert([
            'id_user' => $request->id_user,
            'id_event' => $request->eventId,
            ]);


            DB::commit();

            } catch (\Exception $e) {
       
                DB::rollback();
                Log::error('User jailed to join event: ' . $e->getMessage()); 
                return redirect()->back()->with('message', 'User jailed to join event!');
            }    
            return redirect()->back()->with('message', 'Joined event successfully');
        }

    public function leaveEvent(Request $request)
    {
        $event = Event::find($request->eventId);
        if (!$event) {
            return redirect()->back()->with('message', 'Event not found');
        }
    
        $this->authorize('leaveEvent', $event);
            
        try {
            if (!(AuthenticatedUser::where('id_user', $request->id_user)->exists())|| !(Event::where('id', $request->eventId)->exists())) 
                return redirect()->back()->with('message', 'User or event not found');
            
            DB::BeginTransaction();

            EventParticipant::where('id_user', $request->id_user)
                            ->where('id_event', $request->eventId)
                            ->delete();
            DB::commit();

            } catch (\Exception $e) {
       
                DB::rollback();
                Log::error('User jailed to leave event: ' . $e->getMessage()); 
                return redirect()->back()->with('message', 'User jailed to leave event!');
            }
        return redirect()->back()->with('message', 'Left event successfully');
    }

    public function showSearchEvents($events)
    {
        $user = Auth::user();
        $newEvents = $events->where('id_user', '!=', $user->id);
        return view('pages.event_lists', ['events' => $newEvents]);
    }

    public function search(Request $request)
    {
        $search = $request->get('search');
        $events = Event::where('title', 'ILIKE', '%' . $search . '%')->get();
    
        return $this->showSearchEvents($events);
    }

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
