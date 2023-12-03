<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AuthenticatedUser;
use App\Models\Event;
use App\Models\User;
use App\Models\EventParticipant;
use App\Models\FavouriteEvents;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;


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

    public function editAccount (Request $request) {
        $user = Auth::user();

        $this -> authorize('editAccount', $user);

        if(!$user) {
            return redirect()->back()->with('error','User not found');
        }
        if($request->has('name') && !empty($request->name)){
            $request->validate(['name' => 'required|string|max:250']);
            $user->name = $request->name;
        }
        if($request->has('username') && !empty($request->username)){
            $request->validate(['username' => 'required|string|max:250|unique:users']);
            $user->username = $request->username;
        }
        if($request->has('email') && !empty($request->email)){
            $request->validate(['email' => 'required|email|max:250|unique:users']);
            $user->email = $request->email;
        }
        if($request->has('newPassword') && !empty($request->newPassword)){
            $request->validate(['newPassword' => 'required|min:8|confirmed']);
        }
        if($request->has('repeatPassword') && !empty($request->repeatPassword) && ($request->repeatPassword == $request->newPassword)){
            $request->validate(['repeatPassword' => 'required|min:8|confirmed']);
            $user->password = Hash::make($request->newPassword);
        }

        $user->save();

        return redirect()->back()->with('success', 'Account successfully updated');
    }

}
