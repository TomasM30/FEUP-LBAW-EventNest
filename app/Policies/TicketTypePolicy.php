<?php

namespace App\Policies;

use App\Models\AuthenticatedUser;
use App\Models\TicketType;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class TicketTypePolicy
{
    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): Response
    {
        return AuthenticatedUser::where('id_user', $user->id)->exists()
            ? Response::allow()
            : Response::deny('You must be an authenticated user to create an event.');    }

}
