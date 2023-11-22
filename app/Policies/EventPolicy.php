<?php

namespace App\Policies;

use App\Models\Event;
use App\Models\User;
use App\Models\AuthenticatedUser;
use App\Models\EventParticipant;
use App\Models\Admin;
use Illuminate\Auth\Access\Response;

class EventPolicy
{
    public function create(User $user): Response
    {
        return AuthenticatedUser::where('id_user', $user->id)->exists()
        ? Response::allow()
        : Response::deny('You must be an authenticated user to create an event.');    }

    public function addUser(User $user, Event $event): Response
    {
        if (!Admin::where('id_user', $user->id)->exists() && $user->id !== $event->user_id) {
            return Response::deny('You must be an admin or the event organizer to add a user to the event.');
        }

        return Response::allow();
    }

    public function removeUser(User $user, Event $event): Response
    {
        if (!Admin::where('id_user', $user->id)->exists() && $user->id !== $event->user_id) {
            return Response::deny('You must be an admin or the event organizer to remove a user from the event.');
        }
    
        return Response::allow();
    }

    public function joinEvent(User $user, Event $event): Response
    {
        if (Admin::where('id_user', $user->id)->exists()) {
            return Response::deny('You must be an authenticated user to participate.');
        }
    
        return AuthenticatedUser::where('id_user', $user->id)->exists() && !EventParticipant::where('id_event', $event->id)->where('id_user', $user->id)->exists()
            ? Response::allow()
            : Response::deny('You must not be a participant of the event to join it.');
    }

    public function leaveEvent(User $user, Event $event): Response
    {
        if (Admin::where('id_user', $user->id)->exists()) {
            return Response::deny('You must be an authenticated user to participate.');
        }
    
        return AuthenticatedUser::where('id_user', $user->id)->exists() && EventParticipant::where('id_event', $event->id)->where('id_user', $user->id)->exists()
            ? Response::allow()
            : Response::deny('You must be a participant of the event to leave it.');
    }

    public function delete(User $user, Event $event): Response
    {
        return $user->id === $event->user_id || Admin::where('id_user', $user->id)->exists()
            ? Response::allow()
            : Response::deny('You must be the organizer of the event or an admin to delete the event.');
    }
}