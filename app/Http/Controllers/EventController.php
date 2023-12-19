<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\TicketType;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

use App\Models\Event;
use App\Models\User;
use App\Models\Admin;
use App\Models\EventParticipant;
use App\Models\EventHashtag;
use App\Models\Hashtag;
use App\Models\AuthenticatedUser;
use App\Models\Notification;
use App\Models\EventNotification;
use App\Models\RequestNotification;
use App\Models\FavoriteEvents;
use App\Models\Report;
use App\Http\Controllers\FileController;
use PHPUnit\Framework\Attributes\Ticket;


class EventController extends Controller
{
    public function createEvent(Request $request)
    {
        try {
            $this->authorize('create', Event::class);
            $this->authorize('create', TicketType::class);

            $user = Auth::user()->authenticated;


            DB::BeginTransaction();

            log::info($request->all());

            
            $request->validate([
                'title' => 'required|string',
                'description' => 'required|string|max:500',
                'type' => 'in:public,private,approval',
                'date' => 'required|after_or_equal:today',
                'capacity' => 'required|integer|min:2',
                'ticket_limit' => [
                    'integer',
                    'min:1',
                    function ($attribute, $value, $fail) use ($request) {
                        if ($value > $request->capacity) {
                            $fail($attribute.' must be less than or equal to capacity.');
                        }
                    },
                ],
                'ticket_price' => Rule::requiredIf($request->event_type == 'tickets' && (Auth::user()->authenticated->is_verified !== false)).'|integer|min:1',
                'place' => 'required|string',
                'hashtags' => 'array',
                'hashtags.*' => 'exists:hashtag,id',
            ]);
            

            $event = Event::create([
                'title' => $request->title,
                'description' => $request->description,
                'type' => $request->input('type', 'public'),
                'date' => $request->date,
                'capacity' => $request->capacity,
                'ticket_limit' => $request->ticket_limit ? $request->ticket_limit : $request->capacity,
                'place' => ucfirst($request->place),
                'id_user' => Auth::user()->id,
                'image' => null,
            ]);

            if ($request->has('ticket_price')) {
                TicketType::create([
                    'id_event' => $event->id,
                    'title' => $request->title,
                    'price' => $request->ticket_price,
                    'category' => 'default',
                    'availability' => $request->capacity,
                ]);
            }

            if ($request->hashtags) {
                foreach ($request->hashtags as $hashtagId) {
                    EventHashtag::create([
                        'id_event' => $event->id,
                        'id_hashtag' => $hashtagId,
                    ]);
                }
            }

            EventParticipant::insert([
                'id_user' => Auth::user()->id,
                'id_event' => $event->id,
                ]);
            
            if ($request->hasFile('file')) {
                $request->merge(['id' => $event->id, 'type' => 'event']);
                $fileController = new FileController();
                $uploadResponse = $fileController->upload($request);
                if ($uploadResponse instanceof \Illuminate\Http\RedirectResponse) {
                    DB::commit();
                    return redirect()->route('events.details', ['id' => $event->id]);
                } else if (isset($uploadResponse['file'])) {
                    DB::rollback();
                    return redirect()->back()->withErrors(['file' => $uploadResponse['file']]);
                }
            }

            DB::commit();
        
            return redirect()->route('events.details', ['id' => $event->id]);
            
        } catch (ValidationException $e) {
            log::info($e->getMessage());
            DB::rollback();
            throw $e;
        } catch (\Exception $e) {
            log::info($e->getMessage());
            DB::rollback();
            throw $e;
        }
    }

    public function makeOrder(Request $request) {

        $ticketTypes = TicketType::where('id_event', $request->id_event)->get();
        if (!$ticketTypes) {
            return redirect()->back()->with('message', 'Tickets not found');
        }
    
        if (!(AuthenticatedUser::where('id_user', $request->id_user)->exists())|| !(Event::where('id', $request->id_event)->exists())){
            return redirect()->back()->with('message', 'User or event not found');
        }
    
        $event = Event::find($request->id_event);
        if($event->ticket_limit < $request->amount){
            return redirect()->back()->with('error', 'Exceeded ticket limit');
        } 
        else if($event->capacity < $request->amount){
            return redirect()->back()->with('error', 'Not enough tickets available for this order');
        } 
    
        $totalTickets = Order::where('id_user', $request->id_user)
            ->whereHas('tickets', function ($query) use ($ticketTypes) {
                $query->where('id_ticket_type', $ticketTypes->first()->id);
            })
            ->sum('quantity');
    
        if ($totalTickets + $request->amount > $event->ticket_limit) {
            return redirect()->back()->with('error', 'Exceeded ticket limit for this user');
        }
    
        session([
            'order' => [
                'id_user' => $request->id_user,
                'id_event' => $request->id_event,
                'amount' => $request->amount,
                'ticketType' => $ticketTypes->first(),
            ]
        ]);
    
        return redirect()->route('paypal.payment');
    }

    public function deleteEvent(Request $request)
    {
        $id = $request->id;
        $event = Event::find($id);
        if (!$event) {
            return redirect()->back()->with('message', 'Event not found');
        }

        $this->authorize('delete', $event);

        DB::beginTransaction();

        try {

            $event = Event::findOrFail($id);

            if ($event->eventnotification) {
                $event->eventnotification()->delete();
                $event->eventnotification->notification()->delete();
            }

            $event->report()->delete();


            $event->eventparticipants()->delete();
            $event->favoriteevent()->delete();
            $event->hashtags()->detach();


            $event->delete();

            DB::commit();

            return redirect()->route('events')->with('message', 'Event deletion successful');
        } catch (\Exception $e) {
            DB::rollback();
            log::info($e->getMessage());
            return redirect()->back()->with('message', 'Event deletion failed');
        }
    }
    

    public function listEvents()
    {   
        $now = Carbon::now();
    
        Event::where('date', '<', $now)
            ->where('closed', false)
            ->update(['closed' => true]);
    
        $user = Auth::user();
    
        $query = Event::query();
    
        if ($user->isAdmin()) {
            log::info('User is admin');
            $query->where('closed', false);
        } else {
            $query->whereIn('type', ['approval', 'public'])
                  ->where('id_user', '!=', $user->id)
                  ->where('closed', false);
        }
    
        $events = $query->orderBy('date')->get();
    
        $hashtags = Hashtag::orderBy('title')->get();
        $places = Event::getUniquePlaces()->sortBy('place');
    
        return view('pages.events', ['events' => $events, 'user'=> $user, 'hashtags' => $hashtags, 'places' => $places]);
    }   


    public function showEventDetails(Request $request) 
    {
        $id = $request->id;
        $user = Auth::user();
        $hashtags = Hashtag::all();
        $data = [];
        $data['event'] = Event::find($id);
        $this->authorize('viewEvent', $data['event']);
        $data['isParticipant'] = $this->joinedEvent(Auth::user(), $data['event']);
        $data['isAdmin'] = Admin::where('id_user', Auth::user()->id)->first();
        $data['isOrganizer'] = $data['event']->id_user == Auth::user()->id;
        $data['hashtags'] = $hashtags;
        $data['attendees'] = $data['event']->eventparticipants()->paginate(10);

        $data['participants'] = $data['event']->eventparticipants()->pluck('id_user')->toArray();

        $data['invitedUsers'] = DB::table('eventnotification')->join('notification', 'eventnotification.id', '=', 'notification.id')
                                                            ->where('inviter_id', Auth::user()->id)
                                                            ->where('id_event', $data['event']->id)
                                                            ->pluck('notification.id_user')
                                                            ->toArray(); 

        $data['notInvited'] = AuthenticatedUser::whereNotIn('id_user', $data['participants'])
                            ->whereNotIn('id_user', $data['invitedUsers'])
                            ->get();
                            
        $data['nonParticipants'] = AuthenticatedUser::whereNotIn('id_user', $data['participants'])->get();
        $data['alreadyReported'] = Report::where('id_user', Auth::user()->id)
                                        ->where('id_event', $data['event']->id)
                                        ->where('closed', false)
                                        ->exists();
        $data['user'] = $user;

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

            $notification = Notification::find($request->notificationId);
            $action = $request->action;
            
            if ($notification) {
                $notification->eventnotification()->delete();
                $notification->delete();
            }

            $receiverId = $request->id_user;

            if($action == 'request'){
                if (!$this->createNotification('request_accepted', $receiverId, null, $request->eventId)) {
                    return redirect()->back()->with('message', 'Failed to create notification!');
                }
            } else {
                if (!$this->createNotification('added_to_event', $receiverId, null, $request->eventId)) {
                    return redirect()->back()->with('message', 'Failed to create notification!');
                }
            }

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
            if (!(AuthenticatedUser::where('id_user', $request->id_user)->exists())|| !(Event::where('id', $request->eventId)->exists())){
                return redirect()->back()->with('message', 'User or event not found');  
            }
            
            DB::BeginTransaction();

            EventParticipant::where('id_user', $request->id_user)
                            ->where('id_event', $request->eventId)
                            ->delete();

            
            $user = User::find($request->id_user);
            $receiverId = $request->id_user;

            $notificationIds = Notification::where('id_user', $request->id_user)
            ->whereHas('eventnotification', function ($query) use ($request) {
                $query->where('id_event', $request->eventId);
            })
            ->pluck('id');

            EventNotification::whereIn('id', $notificationIds)->delete();

            Notification::whereIn('id', $notificationIds)->delete();


            if (!$this->createNotification('removed_from_event', $receiverId, null, $request->eventId)) {
                return redirect()->back()->with('message', 'Failed to create notification!');
            }

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
            if (!(AuthenticatedUser::where('id_user', $request->id_user)->exists())|| !(Event::where('id', $request->eventId)->exists())){
                return redirect()->back()->with('message', 'User or event not found');
            }
            
            DB::BeginTransaction();

            EventParticipant::insert([
            'id_user' => $request->id_user,
            'id_event' => $request->eventId,
            ]);

            if ($request->has('notificationId')) {
                $notification = Notification::find($request->notificationId);
                $senderId = $notification->id_user;
                if ($notification) {
                    $notification->eventnotification()->delete();
                    $notification->delete();
                }
                
                $receiverId = $event->id_user;
                $eventId = $request->eventId;
                if (!$this->createNotification('invitation_accepted', $receiverId, $senderId, $eventId)) {
                    return redirect()->back()->with('message', 'Failed to create notification!');
                }
            }



            DB::commit();

        } catch (\Exception $e) {
    
            DB::rollback();
            ('User jailed to join event: ' . $e->getMessage()); 
            return redirect()->back()->with('message', 'User jailed to join event!');
        }    
        return redirect()->route('events.details', ['id' => $request->eventId]);
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

    public function editEvent (Request $request)
    {

        try{
            DB::BeginTransaction();

            $event = Event::find($request->id);

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
                $event->place = ucfirst($request->place);
            }

            if ($request->has('hashtags')) {
                $request->validate([
                    'hashtags' => 'array',
                    'hashtags.*' => 'exists:hashtag,id',
                ]);

                $event->hashtags()->detach();

                foreach ($request->hashtags as $hashtagId) {
                    EventHashtag::create([
                        'id_event' => $event->id,
                        'id_hashtag' => $hashtagId,
                    ]);
                }
            }

            $event->save();

            $participants = $event->eventparticipants()->where('id_user', '!=', $event->id_user)->get();
            foreach ($participants as $participant) {
                $this->createNotification('event_edited', $participant->id_user, null, $event->id);
            }

            if ($request->hasFile('file')) {
                $request->merge(['id' => $event->id, 'type' => 'event']);
                $fileController = new FileController();
                $uploadResponse = $fileController->upload($request);

                if ($uploadResponse instanceof \Illuminate\Http\RedirectResponse) {
                    return redirect()->route('events.details', ['id' => $event->id]);
                } else if (isset($uploadResponse['file'])) {
                    return redirect()->back()->withErrors(['file' => $uploadResponse['file']]);
                }
            }

            DB::commit();

            return redirect()->back()->with('success', 'Event successfully created');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Event creation failed');                                                                      
        }
    }

    public function createNotification($notificationType, $receiverId, $senderId = null, $eventId = null, $reportId = null)
    {
        try {
            DB::BeginTransaction();


            $notification = Notification::create([
                'type' => $notificationType,
                'id_user' => $receiverId,
                'report_id' => $reportId,
            ]);
    
            EventNotification::create([
                    'id' => $notification->id,
                    'inviter_id' => $senderId,
                    'id_event' => $eventId,
            ]);
    
            DB::commit();
        } catch (\Exception $e){
            log::info($e->getMessage());
            DB::rollback();
            return false;
        }
        return true;
    }

    public function deleteNotification($notificationId)
    {
        $notification = Notification::find($notificationId);
        if ($notification) {
    
            $newNotificationType = '';
            if ($notification->type == 'invitation_received') {
                $newNotificationType = 'invitation_rejected';
            } elseif ($notification->type == 'request') {
                $newNotificationType = 'request_rejected';
            } elseif ($notification->type == 'report_received') {
                $notification->report->update(['closed' => true]);
                $newNotificationType = 'report_closed';
            }
    
            if ($newNotificationType != '') {
                $receiverId = $notification->eventnotification->inviter_id;
                $senderId = $notification->id_user;
                $eventId = $notification->eventnotification->id_event;
                if (!$this->createNotification($newNotificationType, $receiverId, $senderId, $eventId)) {
                    return redirect()->back()->with('message', 'Failed to create notification!');
                }
            }
    
            if ($notification->eventnotification) {
                $notification->eventnotification->delete();
            }
            $notification->delete();
            return redirect()->back()->with('success', 'Notification successfully deleted!');
        } else {
            return redirect()->back()->with('error', 'Notification not found!');
        }
    }


    public function addNotification(Request $request)
    {
        $event = Event::find($request->eventId);
        if (!$event) {
            return redirect()->back()->with('message', 'Event not found');
        }
        
        $sender = auth()->user();   
        $receiverId = $request->id_user; 
        $action = $request->action;

        if (!AuthenticatedUser::where('id_user', $sender->id)->exists()) {
            return redirect()->back()->with('message', 'Sender not found');
        }

        if (!AuthenticatedUser::where('id_user', $receiverId)->exists()) {
            return redirect()->back()->with('message', 'Receiver not found');
        }


        if ($event->type == 'public' || ($event->type == 'approval' && $action == 'invitation') || ($event->type == 'private' && $action == 'invitation')) {
            $notificationType = 'invitation_received';
            $this->authorize('inviteUser', $event);
        } 
        elseif ($event->type == 'approval' && $action == 'request') {
            $notificationType = 'request';
            $this->authorize('requestToJoin', $event);
        }
    
        try {
            $receiver = User::where('id', $receiverId)->firstOrFail();
        } catch (ModelNotFoundException $e) {
            log::info($e->getMessage());
            return redirect()->back()->with('message', 'Receiver not found');
        }
    
        $notificationExists = Notification::where('id_user', $receiver->id)
            ->whereHas('eventnotification', function ($query) use ($sender, $request) {
                $query->where('inviter_id', $sender->id)
                    ->where('id_event', $request->eventId);
            })->exists();
        
        if ($notificationExists) {
            return redirect()->back()->with('message', 'Notification already sent!');
        }
    
        if (!$this->createNotification($notificationType, $receiver->id, $sender->id, $request->eventId)) {
            return redirect()->back()->with('message', 'Failed to send Notification!');
        }
    
        return redirect()->back()->with('success', 'Notification successfully sent!');
    }

    public function search(Request $request)
    {
        $user = Auth::user();
        $search = $request->get('search');
        $filteredEventIds = $request->input('events');

        $query = Event::query();

        if ($user->isAdmin()) {
            $query->where('closed', false);
        } else {
            $query->whereIn('type', ['approval', 'public'])
                ->where('id_user', '!=', $user->id)
                ->where('closed', false);
        }

        if ($filteredEventIds) {
            $query = $query->whereIn('id', $filteredEventIds);
        }

        if (!empty($search)) {
            $searchTerms = explode(' ', $search);

            foreach ($searchTerms as $term) {
                $query = $query->whereRaw("tsvectors @@ plainto_tsquery('portuguese', ?)", [$term]);
            }
        }

        $events = $query->get();
        return view('pages.event_lists', ['events' => $events]);
    }


    public function order(Request $request)
    {
        $user = Auth::user();
        $orderBy = $request->input('orderBy', 'date');
        $direction = $request->input('direction', 'asc');
        $eventIds = $request->input('events');
        $search = $request->input('search');

        $query = Event::query();

        if (!empty($eventIds)) {
            $query = $query->whereIn('id', $eventIds);
        }

        if (!empty($search)) {
            $searchTerms = explode(' ', $search);

            foreach ($searchTerms as $term) {
                $query = $query->whereRaw("tsvectors @@ plainto_tsquery('portuguese', ?)", [$term]);
            }
        }

        if ($user->isAdmin()) {
            $query->where('closed', false);
        } else {
            $query->whereIn('type', ['approval', 'public'])
                ->where('id_user', '!=', $user->id)
                ->where('closed', false);
        }

        $events = $query->orderBy($orderBy, $direction)->get();

        return view('pages.event_lists', ['events' => $events]);
    }

    public function filter(Request $request)
    {
        $user = Auth::user();
        $hashtags = $request->input('hashtags');
        $places = $request->input('places');
        $search = $request->input('search');

        $query = Event::query();

        if ($user->isAdmin()) {
            $query->where('closed', false);
        } else {
            $query->whereIn('type', ['approval', 'public'])
                ->where('id_user', '!=', $user->id)
                ->where('closed', false);
        }

        if (!empty($search)) {
            $searchTerms = explode(' ', $search);

            foreach ($searchTerms as $term) {
                $query = $query->whereRaw("tsvectors @@ plainto_tsquery('portuguese', ?)", [$term]);
            }
        }

        $events = $query->when($hashtags, function ($query, $hashtags) {
                        return $query->whereHas('hashtags', function ($query) use ($hashtags) {
                            $query->whereIn('id', $hashtags);
                        });
                    })
                    ->when($places, function ($query, $places) {
                        return $query->whereIn('place', $places);
                    })
                    ->get();

        $filteredEventsHtml = view('pages.event_lists', ['events' => $events])->render();
        $filteredEventIds = $events->pluck('id')->all();

        return response()->json([
            'html' => $filteredEventsHtml,
            'ids' => $filteredEventIds,
        ]);
    }               

    public function cancelEvent(Request $request){
        $event = Event::find($request->eventId);
        if (!$event) {
            return redirect()->back()->with('message', 'Event not found');
        }

        $this->authorize('cancelEvent', $event);
        $event->update(['closed' => true]);

        $participants = $event->eventparticipants()->where('id_user', '!=', $event->id_user)->get();

        foreach ($participants as $participant) {

            $notificationIds = Notification::where('id_user', $participant->id_user)
                                           ->whereHas('eventnotification', function ($query) use ($request) {
                                               $query->where('id_event', $request->eventId);
                                           })
                                           ->pluck('id');
    
            EventNotification::whereIn('id', $notificationIds)->delete();
    
            Notification::whereIn('id', $notificationIds)->delete();

            $this->createNotification('event_canceled', $participant->id_user, null, $event->id);
        }

        return redirect()->back()->with('message', 'Event cancelled successfully');
    }

    public function reportEvent(Request $request, $id){
        try {

            DB::BeginTransaction();

            $request->validate([
                'title' => 'required|max:255',
                'content' => 'required|max:500',
            ]);
    
            $report = Report::create([
                'title' => $request->input('title'),
                'content' => $request->input('content'),
                'id_user' => Auth::id(),
                'id_event' => $id,
                'file' => null,
            ]);
    
            if ($request->hasFile('file')) {
                $request->merge(['id' => $report->id, 'type' => 'report']);
                $fileController = new FileController();
                $uploadResponse = $fileController->upload($request);
            }

            $admins = Admin::all();
            $senderId = Auth::user()->id;
            foreach ($admins as $admin) {
                $this->createNotification('report_received', $admin->id_user, $senderId, $id, $report->id);
            }

            DB::commit();
    
            return redirect()->back()->with('success', 'Report submitted successfully');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }


}
