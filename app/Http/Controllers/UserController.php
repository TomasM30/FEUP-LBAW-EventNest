<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use App\Models\Notification;
use App\Models\EventNotification;

class UserController extends Controller
{
    public function findAll(){
         
        $users = User::find(3); 
        return response()->json($users);   
    }

    public function showUserNotifications(Request $request) {
        $userId = $request->route('id');    
        $notifications = Notification::where('id_user', $userId)
            ->with(['eventnotification', 'eventnotification.event'])
            ->get();
    
        return view('pages.user_notifications', ['notifications' => $notifications]);
    }
}
