<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Http\Controllers\FileController;

class TicketType extends Model
{
    public $timestamps  = false;

    protected $fillable = [
        'id_event',
        'title',
        'price',
        'category',
        'availability',
    ];

    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tickettype';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id';

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

    public function tickets()
    {
        return $this->hasMany(Ticket::class, 'id_ticket_type');
    }
}