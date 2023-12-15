<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\FavouriteEvent;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

use App\Models\Event;
use App\Models\User;
use App\Models\Admin;
use App\Models\EventParticipant;
use App\Models\EventHashtag;
use App\Models\Hashtag;
use App\Models\AuthenticatedUser;
use App\Models\Notification;
use App\Models\EventNotification;
use App\Models\EventComment;
use App\Models\RequestNotification;
use App\Models\FavouriteEvents;
use App\Models\Report;
use App\Http\Controllers\FileController;


class EventController extends Controller
{
    public function createEvent(Request $request)
    {
        try {
            $this->authorize('create', Event::class);

            DB::BeginTransaction();

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
                            $fail($attribute . ' must be less than or equal to capacity.');
                        }
                    },
                ],
                'place' => 'required|string',
                'hashtags2' => 'array',
                'hashtags2.*' => 'exists:hashtag,id',
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

            if ($request->hashtags2) {
                foreach ($request->hashtags2 as $hashtagId) {
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
                    return redirect()->route('events.details', ['id' => $event->id])->with('success', 'Your event was successfully created!');
                } else if (isset($uploadResponse['file'])) {
                    DB::rollback();
                    return redirect()->back()->withErrors(['file' => $uploadResponse['file']]);
                }
            }

            DB::commit();

            return redirect()->route('events.details', ['id' => $event->id])->with('success', 'Your event was successfully created!');
        } catch (ValidationException $e) {
            DB::rollback();
            throw $e;
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function deleteEvent(Request $request)
    {
        $id = $request->id;
        $event = Event::find($id);
        if (!$event) {
            return redirect()->back()->with('error', 'Event not found');
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
            $event->favouriteevent()->delete();
            $event->hashtags()->detach();


            $event->delete();

            DB::commit();

            return redirect()->route('events')->with('success', 'Event deletion successful');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Event deletion failed');
        }
    }

    public function addEventAsFavourite(Request $request)
    {
        try {
            if (!(AuthenticatedUser::where('id_user', $request->id_user)->exists()) || !(Event::where('id', $request->id_event)->exists()))
                return redirect()->back()->with('error', 'User or event not found');

            DB::BeginTransaction();

            FavouriteEvents::insert([
                'id_user' => $request->id_user,
                'id_event' => $request->id_event,
            ]);

            DB::commit();
        } catch (ModelNotFoundException $e) {
            DB::rollBack();
            log::info($e->getMessage());
            return redirect()->back()->with('error', 'User failed to add event as favourite!');
        }
        return redirect()->back()->with('success', 'Event added as favourite successfully');
    }

    public function removeEventAsFavourite(Request $request)
    {
        try {
            if (!(AuthenticatedUser::where('id_user', $request->id_user)->exists()) || !(Event::where('id', $request->id_event)->exists()))
                return redirect()->back()->with('error', 'User or event not found');

            DB::BeginTransaction();

            FavouriteEvents::where('id_user', $request->id_user)
                ->where('id_event', $request->id_event)
                ->delete();

            DB::commit();
        } catch (ModelNotFoundException $e) {
            DB::rollBack();
            log::info($e->getMessage());
            return redirect()->back()->with('error', 'User failed to remove event as favourite!');
        }
        return redirect()->back()->with('success', 'Event removed as favourite successfully');
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
            $query->where('closed', false);
        } else {
            $query->whereIn('type', ['approval', 'public'])
                ->where('id_user', '!=', $user->id)
                ->where('closed', false);
        }

        $events = $query->orderBy('date')->paginate(21);

        $hashtags = Hashtag::orderBy('title')->get();
        $places = Event::getUniquePlaces()->sortBy('place');


        return view('pages.events', ['events' => $events, 'user' => $user, 'hashtags' => $hashtags, 'places' => $places]);
    }


    public function showEventDetails(Request $request)
    {
        $id = $request->id;
        $user = Auth::user();
        $hashtags = Hashtag::all();
        $data = [];
        $data['event'] = Event::find($id);
        $this->authorize('viewEvent', $data['event']);
        $data['isParticipant'] = $data['event']->isParticipant(Auth::id());
        $data['isAdmin'] = $user->isAdmin();
        $data['isOrganizer'] = $data['event']->id_user == Auth::user()->id;
        $data['hashtags'] = $hashtags;
        $data['attendees'] = $data['event']->eventparticipants()->paginate(15);
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

        $data['alreadyReported'] =  $data['event']->alreadyReported(Auth::id());

        $data['alreadyRequested'] =  $data['event']->alreadyRequested(Auth::id());

        $data['isFavourite'] =  $data['event']->isFavourite(Auth::id());
        $data['user'] = $user;
        $data['comments'] = EventComment::where('id_event', $id);


        if ($request->ajax()) {
            return view('partials.attendeesTable', ['attendees' => $data['attendees'], 'event' => $data['event']])->render();
        }

        return view('pages.event_details', $data);
    }

    public function joinedEvent($user, $event)
    {
        return EventParticipant::where('id_user', $user->id)
            ->where('id_event', $event->id)
            ->exists();
    }

    public function addUser(Request $request)
    {
        $event = Event::find($request->eventId);
        if (!$event) {
            return redirect()->back()->with('error', 'Event not found');
        }


        $this->authorize('addUser', $event);

        try {
            if (!(AuthenticatedUser::where('id_user', $request->id_user)->exists()) || !(Event::where('id', $request->eventId)->exists()))
                return redirect()->back()->with('error', 'User or event not found');

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

            if ($action == 'request') {
                if (!$this->createNotification('request_accepted', $receiverId, null, $request->eventId)) {
                    return redirect()->back()->with('error', 'Failed to create notification!');
                }
            } else {
                if (!$this->createNotification('added_to_event', $receiverId, null, $request->eventId)) {
                    return redirect()->back()->with('error', 'Failed to create notification!');
                }
            }

            DB::commit();
            return redirect()->back()->with('success', 'Added user successfully');
        } catch (\Exception $e) {

            DB::rollback();
            ('User jailed to join event: ' . $e->getMessage());
            return redirect()->back()->with('error', 'User jailed to join event!');
        }
    }

    public function removeUser(Request $request)
    {
        $event = Event::find($request->eventId);
        if (!$event) {
            return redirect()->back()->with('error', 'Event not found');
        }
        $this->authorize('removeUser', $event);

        try {
            if (!(AuthenticatedUser::where('id_user', $request->id_user)->exists()) || !(Event::where('id', $request->eventId)->exists())) {
                return redirect()->back()->with('error', 'User or event not found');
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
                return redirect()->back()->with('error', 'Failed to create notification!');
            }

            DB::commit();
            return redirect()->back()->with('success', 'Removed user successfully');
        } catch (\Exception $e) {

            DB::rollback();
            ('User jailed to leave event: ' . $e->getMessage());
            return redirect()->back()->with('error', 'User jailed to leave event!');
        }
    }

    public function joinEvent(Request $request)
    {

        $event = Event::find($request->eventId);
        if (!$event) {
            return redirect()->back()->with('error', 'Event not found');
        }

        $this->authorize('joinEvent', $event);

        try {
            if (!(AuthenticatedUser::where('id_user', $request->id_user)->exists()) || !(Event::where('id', $request->eventId)->exists())) {
                return redirect()->back()->with('error', 'User or event not found');
            }

            DB::BeginTransaction();

            EventParticipant::insert([
                'id_user' => $request->id_user,
                'id_event' => $request->eventId,
            ]);

            if ($request->has('notificationId')) {
                $notification = Notification::find($request->notificationId);
                $receiverId = $notification->eventnotification->inviter_id;
                $senderId = $notification->id_user;
                $eventId = $request->eventId;
                if ($notification) {
                    $notification->eventnotification()->delete();
                    $notification->delete();
                }
                if (!$this->createNotification('invitation_accepted', $receiverId, $senderId, $eventId)) {
                    log::info('Failed to create notification!');
                    return redirect()->back()->with('error', 'Failed to create notification!');
                }
            }

            DB::commit();
            return redirect()->route('events.details', ['id' => $request->eventId])->with('success', 'Joined event successfully');
        } catch (\Exception $e) {

            DB::rollback();
            ('User jailed to join event: ' . $e->getMessage());
            return redirect()->back()->with('error', 'User jailed to join event!');
        }
    }

    public function leaveEvent(Request $request)
    {
        $event = Event::find($request->eventId);
        if (!$event) {
            return redirect()->back()->with('error', 'Event not found');
        }

        $this->authorize('leaveEvent', $event);

        try {
            if (!(AuthenticatedUser::where('id_user', $request->id_user)->exists()) || !(Event::where('id', $request->eventId)->exists()))
                return redirect()->back()->with('error', 'User or event not found');

            DB::BeginTransaction();

            EventParticipant::where('id_user', $request->id_user)
                ->where('id_event', $request->eventId)
                ->delete();
            DB::commit();
        } catch (\Exception $e) {

            DB::rollback();
            ('User jailed to leave event: ' . $e->getMessage());
            return redirect()->back()->with('error', 'User jailed to leave event!');
        }

        if ($event->type == 'private') {
            return redirect()->route('events')->with('success', 'Left event successfully');
        } else {
            return redirect()->back()->with('success', 'Left event successfully');
        }
    }

    public function editEvent(Request $request)
    {

        try {
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
                                $fail($attribute . ' must be less than or equal to capacity.');
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

            if ($request->has('hashtags2')) {
                $request->validate([
                    'hashtags2' => 'array',
                    'hashtags2.*' => 'exists:hashtag,id',
                ]);

                $event->hashtags()->detach();

                foreach ($request->hashtags2 as $hashtagId) {
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
                    DB::commit();
                    return redirect()->route('events.details', ['id' => $event->id]);
                } else if (isset($uploadResponse['file'])) {
                    DB::rollback();
                    return redirect()->back()->withErrors(['file' => $uploadResponse['file']]);
                }
            }

            DB::commit();

            return redirect()->back()->with('success', 'Event successfully edited!');
        } catch (ValidationException $e) {
            DB::rollback();
            throw $e;
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
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
        } catch (\Exception $e) {
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
            return redirect()->back()->with('error', 'Event not found');
        }

        $sender = auth()->user();
        $receiverId = $request->id_user;
        $action = $request->action;

        if (!AuthenticatedUser::where('id_user', $sender->id)->exists()) {
            return redirect()->back()->with('error', 'Sender not found');
        }

        if (!AuthenticatedUser::where('id_user', $receiverId)->exists()) {
            return redirect()->back()->with('error', 'Receiver not found');
        }


        if ($event->type == 'public' || ($event->type == 'approval' && $action == 'invitation') || ($event->type == 'private' && $action == 'invitation')) {
            $notificationType = 'invitation_received';
            $this->authorize('inviteUser', $event);
        } elseif ($event->type == 'approval' && $action == 'request') {
            $notificationType = 'request';
            $this->authorize('requestToJoin', $event);
        }

        try {
            $receiver = User::where('id', $receiverId)->firstOrFail();
        } catch (ModelNotFoundException $e) {
            log::info($e->getMessage());
            return redirect()->back()->with('error', 'Receiver not found');
        }

        $notificationExists = Notification::where('id_user', $receiver->id)
            ->whereHas('eventnotification', function ($query) use ($sender, $request) {
                $query->where('inviter_id', $sender->id)
                    ->where('id_event', $request->eventId);
            })->exists();

        if ($notificationExists) {
            return redirect()->back()->with('error', 'Invite already sent!');
        }

        if (!$this->createNotification($notificationType, $receiver->id, $sender->id, $request->eventId)) {
            return redirect()->back()->with('error', 'Failed to send Invite!');
        }

        log::info($request->type);
        if ($request->type == "request") {
            return redirect()->back()->with('success', 'Request successfully sent!');
        } else {
            return redirect()->back()->with('success', 'Invite successfully sent!');
        }
    }

    public function filter(Request $request)
    {
        $hashtags = $request->input('hashtags');
        $places = $request->input('places');
        $search = $request->input('search');
        $orderBy = $request->input('orderBy', 'date');
        $direction = $request->input('direction', 'asc');
        $type = $request->input('type');
        $query = Event::query();

        if ($type == 'main') {
            $user = Auth::user();
        } else {
            $user = AuthenticatedUser::where('id_user', $request->route('id'))->firstOrFail();
        }

        log::info($user);


        if ($user->isAdmin() && $type == 'main') {
            $query->where('closed', false)->paginate(21);
        } elseif ($type == 'main') {
            $query->whereIn('type', ['approval', 'public'])
                ->where('id_user', '!=', $user->id)
                ->where('closed', false)->paginate(21);
        } elseif ($type == 'created') {
            $query->where('id_user', $user->id)->paginate(21);
        } elseif ($type == 'joined') {
            $query->where('closed', false)->whereHas('eventParticipants', function ($query) use ($user) {
                $query->where('id_user', $user->id);
            })->paginate(21);
        } elseif ($type == 'favorite') {
            $query->whereHas('favouriteevent', function ($query) use ($authenticatedUser) {
                $query->where('id_user', $authenticatedUser->id_user);
            })->paginate(21);
        } elseif ($type == 'attended') {
            $query->where('closed', true)
                ->whereHas('eventParticipants', function ($query) use ($user) {
                    $query->where('id_user', $user->id);
                })->paginate(21);
        }

        if (!empty($search)) {
            $searchTerms = explode(' ', $search);

            foreach ($searchTerms as $term) {
                $query = $query->whereRaw("tsvectors @@ plainto_tsquery('english', ?)", [$term]);
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
            ->orderBy($orderBy, $direction)
            ->paginate(21);

        $filteredEventsHtml = view('partials.event_lists', ['events' => $events])->render();
        $filteredEventIds = $events->pluck('id')->all();

        return response()->json([
            'html' => $filteredEventsHtml,
            'ids' => $filteredEventIds,
        ]);
    }

    public function cancelEvent(Request $request)
    {
        $event = Event::find($request->eventId);
        if (!$event) {
            return redirect()->back()->with('error', 'Event not found');
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

        return redirect()->back()->with('success', 'Event cancelled successfully');
    }

    public function reportEvent(Request $request, $id)
    {
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
                if ($uploadResponse instanceof \Illuminate\Http\RedirectResponse) {
                    DB::commit();
                } else if (isset($uploadResponse['file'])) {
                    DB::rollback();
                    return redirect()->back()->withErrors(['file' => $uploadResponse['file']]);
                }
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

    public function addComment(Request $request, $id)
    {
        try {
            DB::BeginTransaction();

            EventComment::create([
                'type' => 'comment',
                'content' => $request->input('content'),
                'id_event' => $id,
                'id_user' => Auth::id(),
            ]);

            DB::commit();
            return redirect()->back()->with('success', 'Comment added successfullly');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function removeComment($id, $commentId)
    {    
        try {

            DB::BeginTransaction();
            $comment = EventComment::findOrFail($commentId);
            $comment->delete();
            DB::commit();
            return redirect()->back()->with('success', 'Comment deleted successfullly');
        } catch(\Exception $e){
            DB::rollback();
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }

    }
}
