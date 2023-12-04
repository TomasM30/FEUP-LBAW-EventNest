<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventHashtag extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'eventhashtag';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = ['id_event', 'id_hashtag'];

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    protected $fillable = ['id_event', 'id_hashtag'];

    public $timestamps = false;

    public function event()
    {
        return $this->belongsTo(Event::class, 'id_event');
    }
    
}
