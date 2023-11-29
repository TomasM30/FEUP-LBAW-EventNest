<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvitationNotification extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'id',
        'inviter_id',
        'id_event',
    ];

    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'invitationnotification';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    public function notification(){
        return $this->belongsTo(Notification::class, 'id');
    }

    public function inviter(){
        return $this->belongsTo(AuthenticatedUser::class, 'inviter_id');
    }

    public function event(){
        return $this->belongsTo(Event::class, 'id_event');
    }
}