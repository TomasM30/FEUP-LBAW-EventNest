<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AuthenticatedUser;
use App\Models\Event;
use App\Models\User;
use App\Models\EventParticipant;
use App\Models\FavouriteEvents;
use Illuminate\Support\Facades\Log;



class AuthenticatedUserController extends Controller
{
    public function showUserEvents($id)
    {
        $authenticatedUser = AuthenticatedUser::find($id);

        if (!$authenticatedUser) {
            return redirect()->back()->with('message', 'User not found');
        }
        
        $this->authorize('userEvents', $authenticatedUser);
    
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
