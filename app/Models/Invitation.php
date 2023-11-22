<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invitation extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'sender_id',
        'receiver_id',
        'id_event',
    ];

    use HasFactory;

    protected $table = 'invitation';

    protected $primaryKey = 'id';

    public function sender(){
        return $this->hasOne(AuthenticatedUser::class, 'id_user', 'sender_id');
    }

    public function receiver(){
        return $this->hasOne(AuthenticatedUser::class, 'id_user', 'receiver_id');
    }

    public function event(){
        return $this->belongsTo(Event::class, 'id', 'id_event');
    }

}
