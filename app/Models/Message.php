<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;
    public $timestamps = false;


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['content', 'id_event', 'id_user', 'date'];

    protected $table = 'messages';

    /**
     * Get the user that owns the message.
     */
    public function authenticated()
    {
        return $this->belongsTo(AuthenticatedUser::class, 'id_user');
    }

    /**
     * Get the event that the message is associated with.
     */
    public function event()
    {
        return $this->belongsTo(Event::class, 'id_event');
    }
}