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


        if (!ctype_digit($userId)){
            return redirect()->route('events')->with('error', 'Not found');
        }

        $user = User::find($userId);  
        
        $this->authorize('userNotifications', $user);

        $notifications = Notification::where('id_user', $userId)
            ->with(['eventnotification', 'eventnotification.event'])
            ->get();
    
        return view('pages.user_notifications', ['notifications' => $notifications]);
    }

    public function getUserById(Request $request, $id)
    {
        if (!ctype_digit($id)){
            return redirect()->route('events')->with('error', 'Not found');
        }

        $user = User::find($id);
        if ($user) {
            return response()->json([
                'id' => $user->id,
                'username' => $user->username,
                'profile_image' => $user->profile_image
            ]);
        } else {
            return response()->json(['error' => 'User not found'], 404);
        }

    }
}
