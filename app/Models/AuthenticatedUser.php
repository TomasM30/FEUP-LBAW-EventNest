<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuthenticatedUser extends Model
{
    use HasFactory;

    // Don't add create and update timestamps in database.
    public $timestamps  = false;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'authenticated';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id_user';

    protected $fillable = ['id_user', 'is_verified', 'is_blocked'];


    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = true;

    public function user(){
        return $this->belongsTo(User::class, 'id_user');
    }

    public function events(){
        return $this->belongsToMany(Event::class, 'eventparticipants', 'id_user', 'id_event');
    }

    public function favouriteEvents()
    {
        return $this->hasMany(FavouriteEvents::class, 'id_user');
    }

    public function messages()
    {
        return $this->hasMany(Message::class, 'id_user', 'id_user');
    }
}
