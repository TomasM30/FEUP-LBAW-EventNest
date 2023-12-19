<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    public $timestamps  = false;

    protected $fillable = [
        'description',
        'id_order',
        'id_ticket_type',
        'price',
        'date',
    ];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'ticket';

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

    /**
     * Get the order that owns the ticket.
     */
    public function order()
    {
        return $this->belongsTo(Order::class, 'id_order');
    }

    /**
     * Get the ticket type that owns the ticket.
     */
    public function ticketType()
    {
        return $this->belongsTo(TicketType::class, 'id_ticket_type');
    }

    public function user()
    {
        return $this->order->user;
    }
}