<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Authenticated;


class AuthenticatedController extends Controller
{
    public function showUserEvents($id)
    {
        $authenticatedUser = Authenticated::with('events')->find($id);
    
        return view('pages.user_events', ['user' => $authenticatedUser->user, 'events' => $authenticatedUser->events]);
    }
}
