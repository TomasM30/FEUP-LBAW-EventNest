<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use App\Models\AuthenticatedUser;
use Illuminate\Support\Facades\Auth;

class GoogleController extends Controller
{
    public function redirect() {
        return Socialite::driver('google')->redirect();
    }

    public function callbackGoogle() {

        $google_user = Socialite::driver('google')->stateless()->user();
        $user = User::where('google_id', $google_user->getId())->first();
        
        // If the user does not exist, create one
        if (!$user) {

            // Store the provided name, email, and Google ID in the database
            $new_user = User::create([
                'name' => $google_user->getName(),
                'email' => $google_user->getEmail(),
                'username' => explode('@', $google_user->getEmail())[0],
                'google_id' => $google_user->getId(),
            ]);

            $authenticated_user = AuthenticatedUser::create([
                'id_user' => $new_user->id
            ]);

            Auth::login($new_user);


        // Otherwise, simply log in with the existing user
        } else {
            Auth::login($user);
        }

        // After login, redirect to homepage
        return redirect()->intended('events');
    }
}

