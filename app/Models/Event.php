<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Http\Controllers\FileController;

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
        'closed',
        'image'
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

    public function favouriteevent(){
        return $this->hasMany(FavouriteEvents::class, 'id_event');
    }
    public function hashtags()
    {
        return $this->belongsToMany(Hashtag::class, 'eventhashtag', 'id_event', 'id_hashtag');
    }

    public function user(){
        return $this->belongsTo(User::class, 'id_user');
    }

    public function eventnotification(){
        return $this->hasOne(EventNotification::class, 'id_event');
    }

    public static function getUniquePlaces()
    {
        return self::select('place')->distinct()->get();
    }

    public function report(){
        return $this->hasMany(Report::class, 'id_event');
    }

    public function getProfileImage() {
        return FileController::get('event', $this->id);
    }

    public function isFavourite($userId)
    {
        return $this->favouriteevent()->where('id_user', $userId)->exists();
    }

    public function isParticipant($userId)
    {
        return $this->eventparticipants()->where('id_user', $userId)->exists();
    }

    public function alreadyReported($userId)
    {
        return $this->report()->where('id_user', $userId)->where('closed', false)->exists();
    }

    public function alreadyRequested($userId)
    {
        return $this->eventnotification()->whereHas('notification', function ($query) use ($userId) {
            $query->where('id_user', $userId)->where('type', 'request');
        })->exists();
    }

    public function messages()
    {
        return $this->hasMany(Message::class, 'id_event');
    }
}
