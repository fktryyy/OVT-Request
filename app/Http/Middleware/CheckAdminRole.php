<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckAdminRole
{
    public function handle(Request $request, Closure $next)
    {
        // Memeriksa apakah user sudah login dan apakah role-nya admin
        if (Auth::check() && Auth::user()->role !== 'admin') {
            // Redirect jika bukan admin
            return redirect('/home')->with('error', 'Anda tidak memiliki akses ke riwayat login.');
        }

        return $next($request);  // Melanjutkan request jika role admin
    }
}

