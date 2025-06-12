<?php

// app/Models/LoginHistory.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoginHistory extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'ip_address', 'logged_in_at'];

    protected $dates = ['logged_in_at'];

    // Definisikan relasi dengan User
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}