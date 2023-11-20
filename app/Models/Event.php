<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;



class Event extends Model
{
    use HasFactory;
       
    
    public $timestamps = false;
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

    protected $attributes = [
        'type' => 'public',
    ]; 

    public function owner()
    {
        return $this->belongsTo(User::class);
    }

    public function attendees()
    {
        return $this->hasMany(EventsParticipants::class,'id_event','id');
    }

    public function publicEvents()
    {
        return Event::where('type','public')->get();
    } 

    

    

    



    



}

