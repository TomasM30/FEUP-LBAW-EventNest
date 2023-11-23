<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Event extends Model
{
    public $timestamps  = false;

    protected $fillable = [
        'title',
        'description',
        'type',
        'date',
        'capacity',
        'ticket_limit',
        'place',
        'id_user',
    ];

    use HasFactory;
    
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'event';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = true;
    
    public function eventparticipants(){
        return $this->hasMany(EventParticipant::class, 'id_event');
    }

    public function favoriteevent(){
        return $this->hasMany(FavouriteEvents::class, 'id_event');
    }

    public function hashtags()
    {
        return $this->belongsToMany(Hashtag::class, 'eventhashtag', 'id_event', 'id_hashtag');
    }

    public function user(){
        return $this->belongsTo(User::class, 'id_user');
    }


    /*
    public function eventmessage(){
        return $this->hasMany(EventMessage::class, 'id_event');
    }

    public function eventnotification(){
        return $this->hasMany(EventNotification::class, 'id_event');
    }

    public function tickettype(){
        return $this->hasMany(TicketType::class, 'id_event');
    }

    public function report(){
        return $this->hasMany(Report::class, 'id_event');
    }

    public function file(){
        return $this->hasMany(File::class, 'id_event');
    }

    public function poll(){
        return $this->hasMany(Poll::class, 'id_event');
    }*/


}
