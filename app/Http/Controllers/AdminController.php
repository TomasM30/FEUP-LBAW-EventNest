<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Authenticated;
use App\Models\Event;


class AdminController extends Controller
{
    public function showDashboard()
    {
        $authenticatedUsers = Authenticated::with('user')->get();
        $events = Event::all();
    
        return view('pages.admin_dashboard', ['users' => $authenticatedUsers, 'events' => $events]);
    }
}
