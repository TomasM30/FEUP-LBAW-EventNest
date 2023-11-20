<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AuthenticatedUser;


class AuthenticatedUserController extends Controller
{
    public function showUserEvents($id)
    {
        $authenticatedUser = AuthenticatedUser::with('events')->find($id);
    
        return view('pages.user_events', ['user' => $authenticatedUser->user, 'events' => $authenticatedUser->events]);
    }
}
