<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\AuthenticatedUser;
use App\Models\Event;


class AdminController extends Controller
{
    public function showDashboard()
    {
        $this->authorize('viewDashboard', Admin::class);
        $authenticatedUsers = AuthenticatedUser::with('user')->get()->sortBy('user.username');
        $events = Event::orderBy('date')->get();
    
        return view('pages.admin_dashboard', ['users' => $authenticatedUsers, 'events' => $events]);
    }
}
