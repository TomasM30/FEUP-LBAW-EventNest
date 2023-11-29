<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'type',
        'id_user',
    ];

    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'notification';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    public function user(){
        return $this->belongsTo(AuthenticatedUser::class, 'id_user');
    }

    public function invitationnotification(){
        return $this->hasOne(InvitationNotification::class, 'id');
    }
}