<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AuthenticatedUser;
use App\Models\Event;
use App\Models\User;
use App\Models\EventParticipant;
use App\Models\FavouriteEvents;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\FileController;
use App\Models\Report;
use App\Models\Notification;
use App\Models\EventNotification;
use App\Models\Hashtag;
use App\Models\Message;
use App\Models\EventComment;
use App\Models\Order;
use App\Models\Ticket;
use App\Models\TicketType;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;


class AuthenticatedUserController extends Controller
{
    public function showUserEvents(Request $request)
    {

        if (!ctype_digit($request->route('id'))){
            return redirect()->route('events')->with('error', 'Not found');
        }

        $authenticatedUser = AuthenticatedUser::find($request->route('id'));

        if (!$authenticatedUser) {
            return redirect()->back()->with('message', 'User not found');
        }

        $this->authorize('userEvents', $authenticatedUser);

        $createdEvents = Event::where('id_user', $authenticatedUser->id_user)->paginate(21);

        $hashtags = Hashtag::orderBy('title')->get();
        $places = Event::getUniquePlaces()->sortBy('place');

        return view('pages.user_events', [
            'user' => $authenticatedUser->user,
            'createdEvents' => $createdEvents,
            'hashtags' => $hashtags,
            'places' => $places
        ]);
    }

    public function showUserjoinedEvents(Request $request)
    {
        if (!ctype_digit($request->route('id'))){
            return redirect()->route('events')->with('error', 'Not found');
        }

        $authenticatedUser = AuthenticatedUser::find($request->route('id'));

        if (!$authenticatedUser) {
            return redirect()->back()->with('message', 'User not found');
        }

        $this->authorize('userEvents', $authenticatedUser);

        $joinedEvents = Event::where('closed', false)
            ->where(function ($query) use ($authenticatedUser) {
                $query->whereHas('eventParticipants', function ($query) use ($authenticatedUser) {
                    $query->where('id_user', $authenticatedUser->id_user);
                });
            })
            ->paginate(21);


        $hashtags = Hashtag::orderBy('title')->get();
        $places = Event::getUniquePlaces()->sortBy('place');

        return view('pages.user_joinedEvents', [
            'user' => $authenticatedUser->user,
            'joinedEvents' => $joinedEvents,
            'hashtags' => $hashtags,
            'places' => $places
        ]);
    }

    public function showUserfavouriteEvents(Request $request)
    {
        if (!ctype_digit($request->route('id'))){
            return redirect()->route('events')->with('error', 'Not found');
        }

        $authenticatedUser = AuthenticatedUser::find($request->route('id'));

        if (!$authenticatedUser) {
            return redirect()->back()->with('message', 'User not found');
        }

        $this->authorize('userEvents', $authenticatedUser);

        $favouriteEvents = Event::whereHas('favouriteevent', function ($query) use ($authenticatedUser) {
            $query->where('id_user', $authenticatedUser->id_user);
        })->paginate(21);


        $hashtags = Hashtag::orderBy('title')->get();
        $places = Event::getUniquePlaces()->sortBy('place');

        return view('pages.user_favouriteEvents', [
            'user' => $authenticatedUser->user,
            'favouriteEvents' => $favouriteEvents,
            'hashtags' => $hashtags,
            'places' => $places
        ]);
    }

    public function showUserattendedEvents(Request $request)
    {
        if (!ctype_digit($request->route('id'))){
            return redirect()->route('events')->with('error', 'Not found');
        }

        $authenticatedUser = AuthenticatedUser::find($request->route('id'));

        if (!$authenticatedUser) {
            return redirect()->back()->with('message', 'User not found');
        }

        $this->authorize('userEvents', $authenticatedUser);
        $attendedEvents = Event::where('closed', true)->whereHas('eventParticipants', function ($query) use ($authenticatedUser) {
            $query->where('id_user', $authenticatedUser->id_user);
        })->paginate(21);

        $hashtags = Hashtag::orderBy('title')->get();
        $places = Event::getUniquePlaces()->sortBy('place');

        return view('pages.user_attendedEvents', [
            'user' => $authenticatedUser->user,
            'attendedEvents' => $attendedEvents,
            'hashtags' => $hashtags,
            'places' => $places
        ]);
    }

    public function showUserProfile(Request $request)
    {
        $id = $request->route('id');

        if (!ctype_digit($id)){
            return redirect()->route('events')->with('error', 'Not found');
        }

        $authenticatedUser = AuthenticatedUser::find($id);

        if (!$authenticatedUser) {
            return redirect()->back()->with('message', 'User not found');
        }

        $eventsHosted = Event::where('id_user', $id)->count();
        $eventsJoined = EventParticipant::where('id_user', $id)->count();

        $hostedEventIds = Event::where('id_user', $id)->pluck('id');

        $totalParticipants = EventParticipant::whereIn('id_event', $hostedEventIds)->count();

        $totalTicketsBought = Ticket::whereHas('ticketType', function ($query) use ($hostedEventIds) {
            $query->whereIn('id_event', $hostedEventIds);
        })->count();

        $totalParticipants += $totalTicketsBought;

        return view('pages.user_profile', [
            'user' => $authenticatedUser->user,
            'eventsHosted' => $eventsHosted,
            'eventsJoined' => $eventsJoined,
            'totalParticipants' => $totalParticipants
        ]);
    }


    public function updateUserProfile(Request $request, $id)
    {

        $request->validate([
            'username' => 'required|string|max:255|unique:users,username,' . $id,
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
        ]);

        $user = User::find($id);
        if (!$user) {
            return redirect()->back()->withErrors(['error' => 'User not found.']);
        }

        $user->username = $request->username;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->save();


        if ($request->hasFile('file')) {
            $request->merge(['id' => $user->id, 'type' => 'profile']);
            $fileController = new FileController();
            $uploadResponse = $fileController->upload($request);
            if ($uploadResponse instanceof \Illuminate\Http\RedirectResponse) {
                return $uploadResponse;
            } else if (isset($uploadResponse['file'])) {
                return redirect()->back()->withErrors(['file' => $uploadResponse['file']]);
            }
        }

        return redirect()->back()->with('success', 'Profile updated successfully!');
    }

    public function updateUserPassword(Request $request, $id)
    {
        $user = User::find($id);

        if (!$user) {
            return redirect()->back()->withErrors(['error' => 'User not found.']);
        }

        $request->validate([
            'current_password' => $user->password ? 'required' : '',
            'new_password' => 'required|min:8|confirmed',
        ]);

        if ($user->password && !Hash::check($request->current_password, $user->password)) {
            return redirect()->back()->withErrors(['current_password' => 'Current password is incorrect. Please try again.']);
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        if (auth::user()->isAdmin()) {
            return redirect('/dashboard')->with('success', 'Password changed successfully');
        } else {
            Auth::logout();
            return redirect('/login')->with('success', 'Password changed successfully. Please log in with your new password.');
        }
    }

    public function deleteUser($id)
    {

        try {
            DB::beginTransaction();

            $user = User::find($id);

            $eventController = new EventController;
            $authenticatedUser = $user->authenticated;
            $events = $authenticatedUser->events->where('id_user', $user->id);


            Message::where('id_user', $user->id)->update(['id_user' => null]);

            Report::where('id_user', $user->id)
                ->update(['closed' => true, 'id_user' => null]);

            EventComment::where('id_user', $user->id)
                ->update(['id_user' => null]);

            foreach ($events as $event) {
                $request = new Request(['eventId' => $event->id]);
                $eventController->cancelEvent($request);
                $event->update(['id_user' => null]);
            }

            $eventNotifications = EventNotification::where('inviter_id', $id)->get();
            $notificationIds = $eventNotifications->pluck('id')->toArray();
            EventNotification::where('inviter_id', $id)->delete();
            Notification::whereIn('id', $notificationIds)->delete();
    
            $notifications = Notification::where('id_user', $id)->get();
            $eventNotificationIds = $notifications->pluck('id')->toArray();
            EventNotification::whereIn('id', $eventNotificationIds)->delete();
            Notification::where('id_user', $id)->delete();

            EventParticipant::where('id_user', $user->id)->delete();
            Order::where('id_user', $user->id)->update(['id_user' => null]);
            FavouriteEvents::where('id_user', $user->id)->delete();
            $authenticatedUser->delete();
            $user->delete();

            DB::commit();
            return redirect('/login')->with('success', 'User deleted successfully.');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function verifyUser(Request $request)
    {
        $user = Auth::user();
        $authenticatedUser = $user->authenticated;
        $authenticatedUser->is_verified = true;
        $authenticatedUser->save();

        return redirect()->back()->with('success', 'User verified successfully!');
    }

    public function showContactUsForm()
    {
        return view('pages.contactus');
    }
}
