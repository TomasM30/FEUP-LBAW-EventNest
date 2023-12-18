<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EventComment extends Model
{
    public $timestamps  = false;
    
    protected $fillable = [
        'type',
        'content',
        'id_event',
        'id_user',
    ];

    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'eventcomment';

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = true;


    public function event()
    {
        return $this->belongsTo(Event::class, 'id_event');
    }

    public function authenticated()
    {
        return $this->belongsTo(AuthenticatedUser::class, 'id_user');
    }

}
