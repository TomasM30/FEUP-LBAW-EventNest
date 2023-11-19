<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// Added to define Eloquent relationships.
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Event extends Model
{
    protected $table = 'Event';
    public $timestamps  = false;

    protected $fillable = [
        'Event_id', 'title', 'description', 'type', 'date', 'capacity', 'ticket_limit', 'place', 'User_id'
    ];

}
