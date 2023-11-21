<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AuthenticatedUser;
use App\Models\Event;
use App\Models\EventParticipant;
use App\Models\FavouriteEvents;


class AuthenticatedUserController extends Controller
{
    public function showUserEvents($id)
    {
        $authenticatedUser = AuthenticatedUser::find($id);
    
        // Fetch the events created by the user
        $createdEvents = Event::where('id_user', $authenticatedUser->id_user)->get();
    
        // Fetch the events the user has joined
        $joinedEvents = Event::whereHas('eventParticipants', function ($query) use ($authenticatedUser) {
            $query->where('id_user', $authenticatedUser->id_user);
        })->get();
    
        // Fetch the user's favorite events
        $favoriteEvents = Event::whereHas('favoriteEvent', function ($query) use ($authenticatedUser) {
            $query->where('id_user', $authenticatedUser->id_user);
        })->get();
    
        return view('pages.user_events', [
            'user' => $authenticatedUser->user,
            'createdEvents' => $createdEvents,
            'joinedEvents' => $joinedEvents,
            'favoriteEvents' => $favoriteEvents,
        ]);
    }
}
