<?php
namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable;

    protected $fillable = [
        'name', 'email', 'username', 'password', 'employee_id', 'role', 'nip'
    ];

    protected $hidden = ['password'];

    protected $casts = [
        'employee_id' => 'integer',
        'role' => 'string',
    ];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    public function loginHistories()
    {
        return $this->hasMany(\App\Models\LoginHistory::class);
    }
}
