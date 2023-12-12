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
        
        if (!$user) {

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


        } else {

            if($user->authenticated->is_blocked)
            {
                return redirect('login')->withErrors([
                    'Blocked Account' => 'Your Account has been blocked.',
                ]);
            }
    
            Auth::login($user); 
       }

        return redirect()->intended('events');
    }
}

