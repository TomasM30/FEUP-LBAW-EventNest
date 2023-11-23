<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hashtag extends Model
{
    use HasFactory;

    protected $table = 'hashtag';

    protected $primaryKey = 'id';

    public function events()
    {
        return $this->belongsToMany(Event::class, 'eventhashtag', 'id_hashtag', 'id_event');
    }
}
