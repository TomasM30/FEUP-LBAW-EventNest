<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AuthenticatedUser;
use App\Models\Event;
use App\Models\User;
use App\Models\EventParticipant;
use App\Models\FavoriteEvents;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\FileController;
use App\Models\Notification;
use App\Models\EventNotification;
use Illuminate\Support\Facades\Hash;



class AuthenticatedUserController extends Controller
{
    public function showUserEvents(Request $request)
    {
        $authenticatedUser = AuthenticatedUser::find($request->route('id'));

        if (!$authenticatedUser) {
            return redirect()->back()->with('message', 'User not found');
        }
        
        $this->authorize('userEvents', $authenticatedUser);
    
        $createdEvents = Event::where('id_user', $authenticatedUser->id_user)->get();
    
        $joinedEvents = Event::where('closed', false)->whereHas('eventParticipants', function ($query) use ($authenticatedUser) {
            $query->where('id_user', $authenticatedUser->id_user);
        })->get();
    
        $favoriteEvents = Event::whereHas('favoriteEvent', function ($query) use ($authenticatedUser) {
            $query->where('id_user', $authenticatedUser->id_user);
        })->get();

        $attendedEvents = Event::where('closed', true)->whereHas('eventParticipants', function ($query) use ($authenticatedUser) {
            $query->where('id_user', $authenticatedUser->id_user);
        })->get();
    
        return view('pages.user_events', [
            'user' => $authenticatedUser->user,
            'createdEvents' => $createdEvents,
            'joinedEvents' => $joinedEvents,
            'favoriteEvents' => $favoriteEvents,
            'attendedEvents' => $attendedEvents,
        ]);
    }

    public function showUserProfile(Request $request) {
        $id = $request->route('id');
        $authenticatedUser = AuthenticatedUser::find($id);

        if (!$authenticatedUser) {
            return redirect()->back()->with('message', 'User not found');
        }

        $eventsHosted = Event::where('id_user', $id)->count();
        $eventsJoined = EventParticipant::where('id_user', $id)->count();
        $totalParticipants = EventParticipant::whereIn('id_event', Event::where('id_user', $id)->pluck('id'))->count();

        return view('pages.user_profile', [
            'user' => $authenticatedUser->user,
            'eventsHosted' => $eventsHosted,
            'eventsJoined' => $eventsJoined,
            'totalParticipants' => $totalParticipants
        ]);
    }


    public function updateUserProfile (Request $request) {

        $request->validate([
            'username' => 'required|string|max:255|unique:users,username,' . Auth::user()->id,
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . Auth::user()->id,
        ]);

        $user = Auth::user();
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

        return redirect()->back()->with('message', 'Profile updated successfully!');    
    }

    public function updateUserPassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ]);
    
        $user = Auth::user();
    
        if ($user->password && !Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect']);
        }
    
        $user->password = Hash::make($request->new_password);
        $user->save();
        Auth::logout();

        return redirect('/login')->with('success', 'Password changed successfully. Please log in with your new password.');
    }

    public function deleteUser() {
        $user = Auth::user();
    
        $eventController = new EventController;
        $authenticatedUser = $user->authenticated;
    
        $events = $authenticatedUser->events->where('id_user', $user->id);

        $eventNotificationIds = EventNotification::where('inviter_id', $user->id)->pluck('id');
        EventNotification::whereIn('id', $eventNotificationIds)->delete();
        Notification::whereIn('id', $eventNotificationIds)->delete();

        $notificationIds = Notification::where('id_user', $user->id)->pluck('id');
        EventNotification::whereIn('id', $notificationIds)->delete();
        Notification::whereIn('id', $notificationIds)->delete();
    
        foreach ($events as $event) {
            $request = new Request(['eventId' => $event->id]);
            $eventController->cancelEvent($request);
            $event->update(['id_user' => null]);
        }

    
        EventParticipant::where('id_user', $user->id)->delete();
        FavoriteEvents::where('id_user', $user->id)->delete();
        $authenticatedUser->delete();
        $user->delete();
    
        return redirect('/login')->with('success', 'User deleted successfully.');
    }

}
