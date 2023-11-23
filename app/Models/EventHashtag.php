<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventHashtag extends Model
{
    use HasFactory;
    protected $table = 'eventhashtag';
    protected $primaryKey = ['id_event', 'id_hashtag'];
    public $incrementing = false;
    public $timestamps = false;

    public function event()
    {
        return $this->belongsTo(Event::class, 'id_event');
    }
    
}
