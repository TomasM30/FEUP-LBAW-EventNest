<?php
namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

use App\Models\AuthenticatedUser;
use App\Http\Controllers\FileController;



// Added to define Eloquent relationships.
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use HasFactory;

    // Don't add create and update timestamps in database.
    public $timestamps  = false;

    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'google_id',
        'profile_image',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function isAdmin() {
        return $this->hasOne(Admin::class, 'id_user')->exists();
    }

    public function authenticated(){
        return $this->hasOne(AuthenticatedUser::class, 'id_user');
    }

    public function getProfileImage() {
        return FileController::get('profile', $this->id);
    }
    
    public function notifications(){
        return $this->hasMany(Notification::class, 'id_user');
    }

    public function isVerified() {
        return $this->is_verified;
    }
    
}
