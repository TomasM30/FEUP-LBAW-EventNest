<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Authenticated extends Model
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

    public function user(){
        return $this->belongsTo(User::class, 'id_user');
    }

    public function events(){
        return $this->belongsToMany(Event::class, 'eventparticipants', 'id_user', 'id_event');
    }
}
