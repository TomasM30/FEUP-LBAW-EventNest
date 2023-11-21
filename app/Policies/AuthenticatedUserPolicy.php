<?php

namespace App\Policies;

use App\Models\AuthenticatedUser;
use App\Models\User;
use App\Models\Admin;
use Illuminate\Auth\Access\Response;


class AuthenticatedUserPolicy
{
    public function userEvents(User $user, AuthenticatedUser $other): Response
    {
        if ($user->id === $other->id_user || Admin::where('id_user', $user->id)->exists()) {
            return Response::allow();
        }
    
        return Response::deny('You can only view your own events or if you are an admin.');
    }
}
