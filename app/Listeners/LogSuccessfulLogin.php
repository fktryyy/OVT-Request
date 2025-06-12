<?php
namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use App\Models\LoginHistory;
use Illuminate\Support\Facades\Log;

class LogSuccessfulLogin
{
    public function handle(Login $event)
    {
        $user = $event->user;
        $ip = request()->ip();
        $now = now();

        // Cegah duplikasi dengan cek: sudahkah login dicatat dalam 10 detik terakhir?
        $alreadyLogged = LoginHistory::where('user_id', $user->id)
            ->where('ip_address', $ip)
            ->where('logged_in_at', '>=', $now->subSeconds(10)) // login terakhir dalam 10 detik
            ->exists();

        if (! $alreadyLogged) {
            LoginHistory::create([
                'user_id' => $user->id,
                'ip_address' => $ip,
                'logged_in_at' => $now,
            ]);
        }
    }
}
