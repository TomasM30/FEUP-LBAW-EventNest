<?php

namespace App\Policies;

use App\Models\Admin;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class UserPolicy
{
    public function userNotifications(User $currentUser, User $user)
    {
        return $currentUser->id === $user->id
            ? Response::allow()
            : Response::deny('You do not have permission to view these notifications.');
    }
}
