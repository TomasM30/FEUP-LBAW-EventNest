<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AuthenticatedUser;
use App\Models\Event;
use App\Models\User;
use App\Models\EventParticipant;
use App\Models\FavouriteEvents;
use App\Models\Notification;
use App\Models\InvitationNotification;


class AuthenticatedUserController extends Controller
{
    public function showUserEvents($id)
    {
        $authenticatedUser = AuthenticatedUser::find($id);

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

    public function showUserNotifications($id) {

        $authenticatedUser = AuthenticatedUser::find($id);
        $userId = $authenticatedUser->id_user;

        if (!$authenticatedUser) {
            return redirect()->back()->with('message', 'User not found');
        }
        
        $this->authorize('userNotifications', $authenticatedUser);

        $notifications = Notification::where('id_user', $userId)
            ->with(['invitationnotification', 'invitationnotification.event'])
            ->get();
    
        return view('pages.user_notifications', ['notifications' => $notifications]);
    }

}
