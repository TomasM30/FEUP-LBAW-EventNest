<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventsParticipants extends Model
{
    
    use HasFactory;
    
    protected $primaryKey = null; // You may set this to null if using composite primary keys

    public $incrementing = false; // Since there's no single primary key

    public $timestamps = false; // You may set this to true if you have timestamps

    protected $fillable = [
        'id_user',
        'id_event',
    ];
}
