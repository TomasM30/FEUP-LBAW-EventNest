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
use App\Models\Eventhashtag;
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
        try {
            $this->authorize('create', Event::class);

            ($request->all());
    
            $request->validate([
                'title' => 'required|string',
                'description' => 'required|string',
                'type' => 'in:public,private,approval',
                'date' => 'required|after_or_equal:today',
                'capacity' => 'required|integer|min:2',
                'ticket_limit' => [
                    'required',
                    'integer',
                    'min:0',
                    function ($attribute, $value, $fail) use ($request) {
                        if ($value > $request->capacity) {
                            $fail($attribute.' must be less than or equal to capacity.');
                        }
                    },
                ],
                'place' => 'required|string',
            ]);
            
            DB::statement("SELECT setval(pg_get_serial_sequence('event', 'id'), coalesce((SELECT MAX(id) FROM event), 0) + 1, false)");
            $event = Event::create([
                'title' => $request->title,
                'description' => $request->description,
                'type' => $request->input('type', 'public'),
                'date' => $request->date,
                'capacity' => $request->capacity,
                'ticket_limit' => $request->ticket_limit,
                'place' => $request->place,
                'id_user' => Auth::user()->id,
            ]);

            EventParticipant::insert([
                'id_user' => Auth::user()->id,
                'id_event' => $event->id,
                ]);
        
            return redirect()->back()->with('success', 'Event successfully created');
        } catch (ValidationException $e) {
            throw $e;
        }
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

            $event->eventparticipants()->delete();
            $event->favoriteevent()->delete();
            $event->hashtags()->detach();
            // $event->eventmessage()->delete();
            // $event->eventnotification()->delete();

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
            log::debug($e);
            DB::rollback();
            // and return an error message
            return redirect()->back()->with('message', 'Event deletion failed');
        }
    }

    public function listPublicEvents()
    {   
        $now = Carbon::now();

        Event::where('date', '<', $now)
            ->where('closed', false)
            ->update(['closed' => true]);

        $user = Auth::user();

        $events = Event::where('type', 'public')
                        ->where('id_user', '!=', $user->id)
                        ->where('closed', false)
                        ->orderBy('date')
                        ->get();
        $now = Carbon::now();

        return view('pages.events', ['events' => $events, 'user'=> $user]);
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

        ($event->id_user == Auth::user()->id);

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
                ('User jailed to join event: ' . $e->getMessage()); 
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
                ('User jailed to leave event: ' . $e->getMessage()); 
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
                ('User jailed to join event: ' . $e->getMessage()); 
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
                ('User jailed to leave event: ' . $e->getMessage()); 
                return redirect()->back()->with('message', 'User jailed to leave event!');
            }
        return redirect()->back()->with('message', 'Left event successfully');
    }

    public function editEvent (Request $request, $id)
    {
        $event = Event::find($id);

        $this->authorize('editEvent', $event);

        if (!$event) {
            return redirect()->back()->with('error', 'Event not found');
        }

        if ($request->has('title') && !empty($request->title)) {
            $request->validate(['title' => 'string']);
            $event->title = $request->title;
        }

        if ($request->has('description') && !empty($request->description)) {
            $request->validate(['description' => 'string']);
            $event->description = $request->description;
        }

        if ($request->has('type') && !empty($request->type)) {
            $request->validate(['type' => 'in:public,private,approval']);
            $event->type = $request->type;
        }

        if ($request->has('date') && !empty($request->date)) {
            $request->validate(['date' => 'after_or_equal:today']);
            $event->date = $request->date;
        }

        if ($request->has('capacity') && !empty($request->capacity)) {
            $request->validate(['capacity' => 'integer|min:0']);
            $event->capacity = $request->capacity;
        }

        if ($request->has('ticket_limit') && !empty($request->ticket_limit)) {
            $request->validate([
                'ticket_limit' => [
                    'integer',
                    'min:0',
                    function ($attribute, $value, $fail) use ($request) {
                        if ($value > $request->capacity) {
                            $fail($attribute.' must be less than or equal to capacity.');
                        }
                    },
                ],
            ]);
            $event->ticket_limit = $request->ticket_limit;
        }

        if ($request->has('place') && !empty($request->place)) {
            $request->validate(['place' => 'string']);
            $event->place = $request->place;
        }

        $event->save();

        return redirect()->back()->with('success', 'Event successfully updated');
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
        $events = Event::where('title', 'ILIKE', '%' . $search . '%')
                        ->where('type', 'public')
                        ->where('closed', false)
                        ->get();    
        return $this->showSearchEvents($events);
    }
}
