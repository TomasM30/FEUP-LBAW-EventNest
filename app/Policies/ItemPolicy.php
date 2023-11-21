<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Card;
use App\Models\Event;

class ItemPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if a user can create an item.
     */


    /**
     * Determine if a user can delete an item.
     */
    public function delete(User $user, Event $item): bool
    {
        // User can only delete items in cards they own.
        return $user->id === $item->card->user_id;
    }
}
