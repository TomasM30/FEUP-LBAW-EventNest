<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Authenticated extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_user';
    
    protected $fillable = [
    'is_verified',
    'id_profilepic',
    ];

    public function user() 
    {
        return $this->belongsTo(User::class, 'id','id_user');
    }

}
